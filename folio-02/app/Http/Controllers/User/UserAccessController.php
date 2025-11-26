<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\NewUserPassword;
use App\Models\Lookups\UserTypeLookup;
use App\Rules\UsernameRule;
use App\Services\Users\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class UserAccessController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function manageUserAccess(User $user)
    {
        $this->authorize('manageUserAccess', User::class);

        $permissions = Permission::all();
        $user_permissions = $user->permissions->pluck('id')->toArray();

        $linkedUserAccounts = $user->linkedUsers;
        $linkedUserAccounts = $linkedUserAccounts->merge($user->linkedByUsers);

        $idsNotIn = array_merge([$user->id], $linkedUserAccounts->pluck('id')->toArray());

        $usersList = User::query()
            ->orderBy('users.firstnames')
            ->staffUsers()
            ->withActiveAccess()
            ->select([
                'users.id AS id',
                DB::raw(
                    'CONCAT( "Name: ", users.firstnames, " ", users.surname, " | username: ", users.username, " | Type: ", (SELECT lookup_user_types.description FROM lookup_user_types WHERE lookup_user_types.id = users.user_type) ) AS description'
                ),
            ])
            ->whereNotIn('users.id', $idsNotIn)
            ->pluck('description', 'id')
            ->toArray();

        $assessorsList = User::query()
            ->orderBy('users.firstnames')
            ->withActiveAccess()
            ->select([
                'users.id AS id',
                DB::raw(
                    'CONCAT( "Name: ", users.firstnames, " ", users.surname, " | username: ", users.username, " | Email: ", users.primary_email ) AS description'
                ),
            ])
            ->where('users.user_type', UserTypeLookup::TYPE_ASSESSOR)
            ->whereNotIn('users.id', DB::table('employer_user_assessor')->where('employer_user_id', $user->id)->pluck('assessor_id')->toArray())
            ->pluck('description', 'id')
            ->toArray();

        $employerLinkedAssessors = DB::table('employer_user_assessor')
            ->join('users', 'employer_user_assessor.assessor_id', '=', 'users.id')
            ->where('employer_user_id', $user->id)
            ->select(['users.id', 'users.firstnames', 'users.surname', 'users.username', 'users.primary_email'])
            ->get();

        $managerLinkedAccounts = DB::table('user_caseload_accounts')
            ->join('users', 'user_caseload_accounts.caseload_account_id', '=', 'users.id')
            ->where('user_caseload_accounts.user_id', $user->id)
            ->select([
                'users.id',
                'users.firstnames',
                'users.surname',
                'users.username',
                'users.primary_email',
                'users.user_type',
            ])
            ->orderBy('users.user_type')
            ->orderBy('users.firstnames')
            ->get();

        $atvList = User::query()
            ->orderBy('users.user_type')
            ->orderBy('users.firstnames')
            ->whereIn('user_type', [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER])
            ->withActiveAccess()
            ->select([
                'users.id AS id',
                DB::raw(
                    'CONCAT( "Name: ", users.firstnames, " ", users.surname, " | username: ", users.username, " | Type: ", (SELECT lookup_user_types.description FROM lookup_user_types WHERE lookup_user_types.id = users.user_type) ) AS description'
                ),
            ])
            ->whereNotIn('users.id', $managerLinkedAccounts->pluck('id')->toArray())
            ->pluck('description', 'id')
            ->toArray();

        return view('admin.users.manage-user-access', compact('user', 'permissions', 'user_permissions', 'linkedUserAccounts', 'usersList', 'assessorsList', 'employerLinkedAssessors', 'managerLinkedAccounts', 'atvList'));
    }

    private function sendNewPasswordEmail(User $user, $password)
    {
        Mail::to($user->primary_email)
            ->later(
                now()->addMinutes(1),
                new NewUserPassword($user, $password)
            );
    }

    public function updateUsername(Request $request, User $user, UserService $userService)
    {
        $this->authorize('manageUserAccess', User::class);

        if (auth()->user()->id == $user->id) {
            return back()
                ->with([
                    'alert-class' => 'alert-danger',
                    'alert-icon' => 'fa-exclamation-circle',
                    'message_username' => 'You cannot change your own username.',
                ]);
        }

        if ($request->has('username') && $user->username == $request->username) {
            return back()
                ->with([
                    'alert-class' => 'alert-danger',
                    'alert-icon' => 'fa-exclamation-circle',
                    'message_username' => 'No change in the username.',
                ]);
        }

        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                new UsernameRule,
            ]
        ]);

        $user->update([
            'username' => $validatedData['username'],
        ]);

        $user = $userService->updateUserUsername($validatedData['username'], $user);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_username' => 'Username is successfully updated.',
            ]);
    }

    public function resetPassword(User $user)
    {
        $this->authorize('manageUserAccess', User::class);

        $password = $user->resetPassword();

        $this->sendNewPasswordEmail($user, $password);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_username' => 'Password is reset and sent to the user\'s email address.',
            ]);
    }

    public function updateWebAccess(User $user)
    {
        $this->authorize('manageUserAccess', User::class);

        if (auth()->user()->id == $user->id) {
            return back()
                ->with([
                    'alert-class' => 'alert-danger',
                    'alert-icon' => 'fa-exclamation-circle red',
                    'message_username' => 'You cannot update your own system access.',
                ]);
        }

        $user->update([
            'web_access' => ! $user->isActive(),
        ]);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_access' => 'User\'s access has been updated.',
            ]);
    }

    public function updatePermissions(User $user, Request $request)
    {
        $this->authorize('manageUserAccess', User::class);

        $user->updatePermissions($request->permissions);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_access' => 'User\'s permissions are updated successfully.',
            ]);
    }

    public function unlinkAccount(User $user, Request $request)
    {
        $this->authorize('manageUserAccess', User::class);

        $request->validate([
            'account_id' => 'required|numeric|in:' . $user->id,
        ]);

        $linkedAccountIds = array_merge(
            $user->linkedUsers()->pluck('id')->toArray(),
            $user->linkedByUsers()->pluck('id')->toArray()
        );

        $request->validate([
            'linked_account_id' => 'required|numeric|in:' . implode(',', $linkedAccountIds),
        ]);

        DB::table('user_links')
            ->where('user_id', $request->account_id)
            ->where('linked_user_id', $request->linked_account_id)
            ->delete();

        DB::table('user_links')
            ->where('user_id', $request->linked_account_id)
            ->where('linked_user_id', $request->account_id)
            ->delete();

        return back()
            ->with([
                'alert-success' => 'Accounts are unlinked successfully.',
                'alert-icon' => 'fa-check green',
            ]);
    }

    public function linkAccount(User $user, Request $request)
    {
        $this->authorize('manageUserAccess', User::class);

        abort_if($user->isStudent(), Response::HTTP_UNAUTHORIZED);

        $request->validate([
            'account_id' => 'required|numeric|in:' . $user->id,
        ]);

        $linkedAccountIds = array_merge(
            $user->linkedUsers()->pluck('id')->toArray(),
            $user->linkedByUsers()->pluck('id')->toArray()
        );

        $request->validate([
            'linked_account_id' => 'required|numeric|not_in:' . implode(',', $linkedAccountIds),
        ]);

        $linkedAccount = User::findOrFail($request->linked_account_id);
        abort_if($linkedAccount->isStudent(), Response::HTTP_UNAUTHORIZED);

        $user->linkedUsers()->attach($request->linked_account_id);

        return back()
            ->with([
                'alert-success' => 'Accounts are linked successfully.',
                'alert-icon' => 'fa-check green',
            ]);
    }

    public function unlinkAssessor(User $employer_user, Request $request)
    {
        $this->authorize('manageUserAccess', User::class);

        $request->validate([
            'employer_user_id' => 'required|numeric|in:' . $employer_user->id,
            'assessor_id' => 'required|numeric|in:' . User::withActiveAccess()->where('user_type', UserTypeLookup::TYPE_ASSESSOR)->pluck('id')->implode(','),
        ]);

        DB::table('employer_user_assessor')
            ->where('employer_user_id', $request->employer_user_id)
            ->where('assessor_id', $request->assessor_id)
            ->delete();

        return back()
            ->with([
                'alert-success' => 'Assessor is unlinked successfully.',
                'alert-icon' => 'fa-check green',
            ]);
    }

    public function linkAssessor(User $employer_user, Request $request)
    {
        $this->authorize('manageUserAccess', User::class);

        $request->validate([
            'employer_user_id' => 'required|numeric|in:' . $employer_user->id,
            'assessor_id' => 'required|numeric|in:' . User::withActiveAccess()->where('user_type', UserTypeLookup::TYPE_ASSESSOR)->pluck('id')->implode(','),
        ]);

        DB::table('employer_user_assessor')
            ->insert([
                'employer_user_id' => $request->employer_user_id,
                'assessor_id' => $request->assessor_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return back()
            ->with([
                'alert-success' => 'Assessor is linked successfully.',
                'alert-icon' => 'fa-check green',
            ]);
    }

    public function unlinkUserToCaseloadAccount(User $user, Request $request)
    {
        $this->authorize('manageUserAccess', User::class);

        $request->validate([
            'user_id' => 'required|numeric|in:' . $user->id,
            'caseload_account_id' => 'required|numeric',
        ]);

        DB::table('user_caseload_accounts')
            ->where('user_id', $request->user_id)
            ->where('caseload_account_id', $request->caseload_account_id)
            ->delete();

        return back()
            ->with([
                'alert-success' => 'Account is unlinked successfully.',
                'alert-icon' => 'fa-check green',
            ]);
    }

    public function linkUserToCaseloadAccount(User $user, Request $request)
    {
        $this->authorize('manageUserAccess', User::class);

        $request->validate([
            'user_id' => 'required|numeric|in:' . $user->id,
            'caseload_account_id' => 'required|numeric|in:' . User::withActiveAccess()->where('user_type', '!=', UserTypeLookup::TYPE_STUDENT)->pluck('id')->implode(','),
        ]);

        $linkedAccount = User::findOrFail($request->caseload_account_id);
        DB::table('user_caseload_accounts')
            ->insert([
                'user_id' => $request->user_id,
                'caseload_account_id' => $linkedAccount->id,
                'caseload_account_type' => $linkedAccount->user_type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return back()
            ->with([
                'alert-success' => 'Account is linked successfully.',
                'alert-icon' => 'fa-check green',
            ]);
    }

    public function saveColorTheme(Request $request)
    {
        $request->validate([
            'color' => 'required|string'
        ]);

        $user = auth()->user();
        if ($user) {
            $user->theme_color = $request->color;
            $user->save();
        }

        return response()->json(['status' => 'saved']);
    }
}
