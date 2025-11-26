<?php

namespace App\Http\Controllers;

use App\Filters\TrainingRecordEvidenceFilters;
use App\Helpers\ReportHelper;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Student;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Models\Training\TrainingReview;
use App\Models\User;
use App\Models\UserEvents\UserEvent;
use App\Services\Students\Trainings\Evidences\TrainingRecordEvidenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check_first_time_login']);
    }

    public function index()
    {
        if (!auth()->check()) {
            auth()->logout();
            request()->session()->invalidate();

            return redirect()->route('login');
        }

        if (auth()->user()->is_support) {
            return view('perspective.home');
        } elseif (auth()->user()->isStudent()) {
            $student = Student::find(auth()->user()->id);
            return view('students.home', compact('student'));
        } elseif (auth()->user()->user_type == UserTypeLookup::TYPE_EMPLOYER_USER) {
            $user = auth()->user();

            return view('employer_user_home', compact('user'));
        }

        $user = auth()->user();

        $continuingStudentsPlannedToFinish = ReportHelper::continuingStudentsDueToFinish($user, 6);
        $trainingRecordEvidenceService = new TrainingRecordEvidenceService();
        $number_of_evidences_to_assess_query = $trainingRecordEvidenceService->unpaginatedIndex(auth()->user()->user_type, new TrainingRecordEvidenceFilters(request()));
        $number_of_evidences_to_assess_query->where('tr_evidences.evidence_status', '=', TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED);
        $number_of_evidences_to_assess = $number_of_evidences_to_assess_query->count();
        //$learners_not_logged_in = ReportHelper::continuingLearnersNotLoggedIn($user, 30);
        //$learners_not_logged_in = $learners_not_logged_in->count();
        $newStartsInLast6Months = ReportHelper::newStartsInLast6Months();
        $over_due_reviews =     TrainingRecord::where(function ($q) {
            $q->where('tr.primary_assessor', auth()->user()->id)
                ->orWhere('tr.secondary_assessor', auth()->user()->id);
        })
            ->whereHas('reviews', fn($q) => $q->overdueReview())
            ->count('tr.id');
        // $totalContinuing = TrainingRecord::caseloadCondition(auth()->user())->where('status_code', TrainingStatusLookup::STATUS_CONTINUING)->count();
        // $overstayers = TrainingRecord::caseloadCondition(auth()->user())->where('status_code', TrainingStatusLookup::STATUS_CONTINUING)->where('planned_end_date', '<', now()->format('Y-m-d'))->count();
        // $percentageOfOverstayers = $overstayers > 0 ? round(($overstayers/$totalContinuing)*100) : 0;

        $assessor_type_report = DB::table('tr_dp_sessions')
            ->select(
                'assessor_type as role',
                DB::raw("SUM(CASE WHEN session_type = 'face_to_face' THEN 1 ELSE 0 END) as face_to_face"),
                DB::raw("SUM(CASE WHEN session_type = 'remote' THEN 1 ELSE 0 END) as remote")
            )
            ->where('assessor_sign', 1)
            ->whereNotNull('assessor_type')
            ->groupBy('assessor_type')
            ->orderBy('assessor_type')
            ->get();

        $incompleteTasks = UserEvent::query()
            ->where(function ($q) {
                // Group both conditions together
                $q->where(function ($sub) {
                    // Case 1: Incomplete & overdue
                    $sub->where('task_status', 1)
                        ->where('end', '<', now()->setTimezone(config('app.timezone')));
                })
                    ->orWhere(function ($sub) {
                        // Case 2: Completed late
                        $sub->whereNotNull('completed_at')->where('task_status', '!=', 4)
                            ->whereColumn('completed_at', '>', 'end');
                    });
            })
            ->where(function ($q) {
                // User/Verifier restriction applies to all above
                if (auth()->user()->isVerifier()) {
                    $q->where('assign_iqa_id', auth()->id());
                } else {
                    $q->where('user_id', auth()->id());
                }
            })
            ->get();

        return view('home', compact(
            'user',
            'continuingStudentsPlannedToFinish',
            'number_of_evidences_to_assess',
            // 'learners_not_logged_in', 
            'newStartsInLast6Months',
            // 'totalContinuing',
            // 'overstayers',
            // 'percentageOfOverstayers',
            'incompleteTasks',
            'over_due_reviews',
            'assessor_type_report'
        ));
    }

    public function home____()
    {
        $user = auth()->user();

        $avatar_url = $user->avatar_url;
        if (!\Session::exists('user.avatar_url')) {
            \Session::put('user.avatar_url', $avatar_url);
        }

        $student = $user;

        return $user->isStudent() ? view('students.home', compact('student')) : view('home', compact('user'));
    }

    public function showLogoutOtherDevices()
    {
        return view('logout_from_other_devices');
    }

    public function logoutOtherDevices(Request $request)
    {
        if (!(\Hash::check($request->get('current-password'), auth()->user()->password))) {
            return back()->with("error", "Your current password does not match with the password you provided. Please try again.");
        }
        auth()->logoutOtherDevices($request->get('current-password'));
        return redirect()->route('logout-other-devices.show')->with('success', 'You have been logged out from all other devices.');
    }
}
