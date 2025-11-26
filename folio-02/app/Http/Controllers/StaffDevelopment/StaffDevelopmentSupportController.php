<?php

namespace App\Http\Controllers\StaffDevelopment;

use App\Filters\StaffDevelopmentSupportFilters;
use App\Http\Controllers\Controller;
use App\Models\StaffDevelopment\StaffDevelopmentSupport;
use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use App\Notifications\StaffDevelopment\StaffDevelopmentSupportFormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StaffDevelopmentSupportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(StaffDevelopmentSupportFilters $filters)
    {
        $recordsQuery = StaffDevelopmentSupport::filter($filters)
            ->with(['supportTo', 'supportFrom']);

        if (! auth()->user()->isAdmin() && ! auth()->user()->isQualityManager()) {
            $recordsQuery->where(function ($query) {
                return $query->where('support_to_id', auth()->user()->id)
                    ->orWhere('support_from_id', auth()->user()->id);
            });
        }

        $records = $recordsQuery->paginate(session('staff_development_support_per_page', config('model_filters.default_per_page')));

        return view('staff_development_support.index', compact('records', 'filters'));
    }

    public function create()
    {
        $supportToList = self::supportToList();

        $questionsList = self::questionsList();

        return view('staff_development_support.create', compact('supportToList', 'questionsList'));
    }

    public function store(Request $request)
    {
        $questionsList = self::questionsList();
        $details = [];
        foreach ($questionsList as $questionId => $questionDesc) {
            $details[$questionId] = $request->$questionId;
        }
        $staffDevelopmentSupport = StaffDevelopmentSupport::create([
            'support_to_id' => $request->support_to_id,
            'support_from_id' => $request->support_from_id,
            'support_type' => $request->support_type,
            'provision_date' => $request->provision_date,
            'duration' => $request->duration,
            'details' => json_encode($details),
            'support_from_sign' => $request->support_from_sign,
            'support_from_sign_date' => ($request->support_from_sign) ? now() : null,
        ]);

        if ($request->support_from_sign) {
            $staffDevelopmentSupport->supportTo->notify(new StaffDevelopmentSupportFormSubmitted($staffDevelopmentSupport));
        }

        return redirect()->route('staff_development_support.index');
    }

    public function show($staff_development_support)
    {
        $staffDevelopmentSupport = StaffDevelopmentSupport::findOrFail($staff_development_support);

        $details = json_decode($staffDevelopmentSupport->details);

        return view('staff_development_support.show', compact('staffDevelopmentSupport', 'details'));
    }

    public function showSupportToSignForm($staff_development_support)
    {
        $staffDevelopmentSupport = StaffDevelopmentSupport::findOrFail($staff_development_support);

        abort_if($staffDevelopmentSupport->support_to_id != auth()->user()->id, Response::HTTP_UNAUTHORIZED);

        $details = json_decode($staffDevelopmentSupport->details);

        return view('staff_development_support.support_to_sign', compact('staffDevelopmentSupport', 'details'));
    }

    public function saveSupportToSignForm($staff_development_support, Request $request)
    {
        $staffDevelopmentSupport = StaffDevelopmentSupport::findOrFail($staff_development_support);

        abort_if($staffDevelopmentSupport->support_to_id != auth()->user()->id, Response::HTTP_UNAUTHORIZED);

        $details = json_decode($staffDevelopmentSupport->details);
        $details->staff_comments = $request->staff_comments;

        $staffDevelopmentSupport->update([
            'details' => json_encode($details),
            'support_to_sign' => $request->support_to_sign,
            'support_to_sign_date' => ($request->support_to_sign) ? now() : null,
        ]);

        return redirect()
            ->route('staff_development_support.show', ['staff_development_support' => $staffDevelopmentSupport])
            ->with(['alert-success' => 'Your information is saved successfully.']);
    }

    public function edit($staff_development_support)
    {
        $staffDevelopmentSupport = StaffDevelopmentSupport::findOrFail($staff_development_support);

        abort_if($staffDevelopmentSupport->signedBySupportPersonnel(), Response::HTTP_UNAUTHORIZED);

        $supportToList = self::supportToList();

        $questionsList = self::questionsList();

        $details = json_decode($staffDevelopmentSupport->details);

        return view('staff_development_support.edit', compact('staffDevelopmentSupport', 'details', 'supportToList', 'questionsList'));
    }

    public function update($staff_development_support, Request $request)
    {
        $staffDevelopmentSupport = StaffDevelopmentSupport::findOrFail($staff_development_support);

        abort_if($staffDevelopmentSupport->signedBySupportPersonnel(), Response::HTTP_UNAUTHORIZED);

        $questionsList = self::questionsList();
        $details = [];
        foreach ($questionsList as $questionId => $questionDesc) {
            $details[$questionId] = $request->$questionId;
        }
        $staffDevelopmentSupport->update([
            'support_to_id' => $request->support_to_id,
            'support_from_id' => $request->support_from_id,
            'support_type' => $request->support_type,
            'provision_date' => $request->provision_date,
            'duration' => $request->duration,
            'details' => json_encode($details),
            'support_from_sign' => $request->support_from_sign,
            'support_from_sign_date' => ($request->support_from_sign) ? now() : null,
        ]);

        if ($request->support_from_sign) {
            $staffDevelopmentSupport->supportTo->notify(new StaffDevelopmentSupportFormSubmitted($staffDevelopmentSupport));
        }

        return redirect()
            ->route('staff_development_support.show', ['staff_development_support' => $staffDevelopmentSupport])
            ->with(['alert-success' => 'Your information is saved successfully.']);
    }

    private function questionsList()
    {
        return [
            'q1' => 'Describe what support/advice/training has been provided?',
            'q2' => 'How was this identified?',
            'q3' => 'If applicable describe what additional support arrangements have been agreed? ',
        ];
    }

    private function supportToList()
    {
        $supportToQuery = User::orderBy('users.firstnames')
            ->staffUsers()
            ->withActiveAccess()
            ->where('users.id', '<>', auth()->user()->id)
            ->join('lookup_user_types', 'users.user_type', '=', 'lookup_user_types.id')
            ->select('users.id', DB::raw('CONCAT(users.firstnames, " ", users.surname, " [", lookup_user_types.description, "]") AS user_detail'));

        if (auth()->user()->isAdmin()) {
            $supportToQuery->staffUsers();
        } else {
            $supportToQuery->where('users.user_type', '<>', UserTypeLookup::TYPE_ADMIN);
        }

        return $supportToQuery->select('users.id', DB::raw('CONCAT(users.firstnames, " ", users.surname, " [", lookup_user_types.description, "]") AS user_detail'))
            ->pluck('user_detail', 'id')
            ->toArray();
    }
}
