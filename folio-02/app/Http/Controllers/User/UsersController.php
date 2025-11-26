<?php

namespace App\Http\Controllers\User;

use App\Events\NewUserNotificationToSupportEvent;
use App\Exports\SystemUsersExport;
use App\Facades\AppConfig;
use App\Filters\UserFilters;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\LookupManager;
use App\Models\Lookups\UserTypeLookup;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, UserFilters $filters)
    {
        $this->authorize('index', User::class);

        $users = User::filter($filters)
            ->excludingStudents()
            ->with(['employer', 'systemUserType', 'latestAuth'])
            ->withCount(['authentications'])
            ->paginate(session('users_per_page', config('model_filters.default_per_page')));

        return view('admin.users.index', compact('users', 'filters'));
    }

    public function export(UserFilters $filters)
    {
        $this->authorize('export', User::class);

        return Excel::download(new SystemUsersExport($filters), 'system users.xlsx');
    }

    public function show(User $user)
    {
        abort_if($user->isStudent(), 403);

        $this->authorize('show', $user);

        $homeAddress = $user->homeAddress();
        $workAddress = $user->workAddress();

        $employerLinkedAssessors = collect([]);
        if ($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER) {
            $employerLinkedAssessors = DB::table('employer_user_assessor')
                ->join('users', 'employer_user_assessor.assessor_id', '=', 'users.id')
                ->where('employer_user_id', $user->id)
                ->select(['users.id', 'users.firstnames', 'users.surname', 'users.username', 'users.primary_email'])
                ->get();
        }

        $linkedUserAccounts = collect([]);
        if ($user->isStaff()) {
            $linkedUserAccounts = $user->linkedUsers;
            $linkedUserAccounts = $linkedUserAccounts->merge($user->linkedByUsers);
        }

        return view('admin.users.show', compact('user', 'homeAddress', 'workAddress', 'employerLinkedAssessors', 'linkedUserAccounts'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        $homeAddress = new Address();
        $workAddress = new Address();

        $employers = LookupManager::getEmployersLocationsDDL();

        return view('admin.users.create', compact('homeAddress', 'workAddress', 'employers'));
    }

    public function store(StoreUserRequest $request, UserService $userService)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();
        if ($request->gender === 'SELF' && $request->gender_self_describe) {
            $data['gender'] = $request->gender_self_describe;
        }
        $user = $userService->create($data);

        if (
            $user->isStaff() &&
            AppConfig::get('FOLIO_SEND_EMAIL_TO_PERSPECTIVE_ON_USER_CREATION')
        ) {
            event(
                new NewUserNotificationToSupportEvent(
                    $user,
                    User::staffUsers()->withActiveAccess()->count(),
                    User::staffUsers()->withInActiveAccess()->count()
                )
            );
        }

        return redirect()
            ->route('users.show', $user)
            ->with(['alert-success' => 'User has been created successfully.']);
    }

    public function edit(User $user)
    {
        abort_if($user->isStudent(), 403);

        $this->authorize('update', $user);

        $homeAddress = $user->homeAddress();
        $workAddress = $user->workAddress();

        $employers = LookupManager::getEmployersLocationsDDL(optional($user->employer)->id);

        return view('admin.users.edit', compact('user', 'homeAddress', 'workAddress', 'employers'));
    }

    public function update(User $user, StoreUserRequest $request, UserService $userService)
    {
        $this->authorize('update', $user);

        if (
            $user->id === auth()->user()->id &&
            (!$request->has('web_access') || !in_array($request->web_access, ["on", 1]))
        ) {
            return back()->with(['alert-danger' => 'You cannot disable your own access.']);
        }
        //dd($request->validated());
        $data = $request->validated();
        if ($request->gender === 'SELF' && $request->gender_self_describe) {
            $data['gender'] = $request->gender_self_describe;
        }

        $user = $userService->update($data, $user);

        return redirect()
            ->route('users.show', $user)
            ->with(['alert-success' => 'User record is updated successfully.']);
    }

    public function destroy(User $user, UserService $userService)
    {
        abort_if($user->isStudent(), 403);

        $this->authorize('delete', $user);

        if (auth()->user()->id == $user->id) {
            return back()->with(['alert-danger' => 'You cannot delete your own account in the system.']);
        }

        if ($user->isAssessor()) {
            $related_students = DB::table('tr')
                ->where('primary_assessor', $user->id)
                ->orWhere('secondary_assessor', $user->id)
                ->count();

            if ($related_students > 0) {
                return back()->with(['alert-danger' => 'Delete aborted, user is an assessor of ' . $related_students . ' students.']);
            }
        }

        if ($user->isTutor()) {
            $related_students = DB::table('tr')
                ->where('tutor', $user->id)
                ->count();

            if ($related_students > 0) {
                return back()->with(['alert-danger' => 'Delete aborted, user is tutor of ' . $related_students . ' students.']);
            }
        }

        if ($user->isVerifier()) {
            $related_students = DB::table('tr')
                ->where('verifier', $user->id)
                ->count();

            if ($related_students > 0) {
                return back()->with(['alert-danger' => 'Delete aborted, user is verifier of ' . $related_students . ' students.']);
            }
        }

        $userService->delete($user);

        return redirect()
            ->route('users.index')
            ->with(['alert-success' => 'User record is successfully deleted from the system.']);
    }
}