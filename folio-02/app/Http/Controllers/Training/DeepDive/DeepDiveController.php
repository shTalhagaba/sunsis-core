<?php

namespace App\Http\Controllers\Training\DeepDive;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\DeepDive;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use App\Services\Students\Trainings\Otj\OtjStatisticsService;
use Illuminate\Support\Facades\DB;

class DeepDiveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(TrainingRecord $training)
    {
        $deepDives = $training->deepDives()->orderBy('deep_dives.created_at')->get();
        
        return view('trainings.deep_dive.index', compact('training', 'deepDives'));
    }

    public function show(TrainingRecord $training, DeepDive $deep_dive)
    {
        $deepDive = $deep_dive;
        $otjService = new OtjStatisticsService($training);
        $otjStats = $otjService->getStatistics();
        $gcseGradesList = DB::table('lookup_gcse_grades')->pluck('description', 'id')->toArray();
        $formData = !is_null($deepDive->form_data) ? json_decode($deepDive->form_data, true) : [];
        
        // dd($formData);
        return view('trainings.deep_dive.show', compact('training', 'deepDive', 'otjStats', 'formData'));
    }

    public function create(TrainingRecord $training)
    {
        $otjService = new OtjStatisticsService($training);
        $otjStats = $otjService->getStatistics();
        $gcseGradesList = DB::table('lookup_gcse_grades')->pluck('description', 'id')->toArray();
        $formData = [];
        $assessorsList = User::withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->whereIn('id', [$training->primary_assessor, $training->secondary_assessor])
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();
        $verifiersList = User::withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('id', $training->verifier)
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();
        $opsManagersList = User::staffUsers()->withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();
        $employerUsers = User::orderBy('firstnames')->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_EMPLOYER_USER)
                ->where('employer_location', $training->employer_location)
                ->pluck('name', 'id')->toArray();

        return view(
            'trainings.deep_dive.create', 
            compact('training', 'otjStats', 'gcseGradesList', 'formData', 'assessorsList', 'verifiersList', 'opsManagersList', 'employerUsers')
        );
    }

    public function store(TrainingRecord $training, Request $request)
    {
        $otjService = new OtjStatisticsService($training);
        $otjStats = $otjService->getStatistics();

        $targetProgress = $training->target_progress;
        $actualProgress = $training->signedOffPercentage();
        $expectedOtjHours = $otjStats['expectedOtjHours'] ?? 0;
        $completedOtjHours = $otjStats['completedOtjHoursFormatted'] ?? 0; 
        
        $percentBehind = $targetProgress > 0 ? 
            (($targetProgress - $actualProgress) / $targetProgress) * 100 :
            0;

        if ($actualProgress >= $targetProgress) {
            $overallStatus = 'Green';
        } elseif ($percentBehind <= 10) {
            $overallStatus = 'Amber';
        } else {
            $overallStatus = 'Red';
        }

        $deepDive = $training->deepDives()->create([
            'deep_dive_date' => $request->input('deep_dive_date'),
            'created_by' => auth()->user()->id,
            'target_progress' => $targetProgress,
            'actual_progress' => $actualProgress,
            'overall_rag_rating' => $overallStatus,
            'assessor_id' => $request->input('assessor_id'),
            'secondary_assessor_id' => $request->input('secondary_assessor_id'),
            'verifier_id' => $request->input('verifier_id'),
            'ops_manager_id' => $request->input('ops_manager_id'),
            'expected_otj' => $expectedOtjHours,
            'completed_otj' => $completedOtjHours,
            'employer_user_id' => $request->input('employer_user_id'),
            'form_data' => json_encode($request->except(['_token']))
        ]);

        return redirect()
            ->route('trainings.deep_dives.show', ['training' => $training, 'deep_dive' => $deepDive])
            ->with(['alert-success' => 'Information is saved successfully.']); 
    }

    public function edit(TrainingRecord $training, DeepDive $deep_dive)
    {
        $deepDive = $deep_dive;
        $otjService = new OtjStatisticsService($training);
        $otjStats = $otjService->getStatistics();
        $gcseGradesList = DB::table('lookup_gcse_grades')->pluck('description', 'id')->toArray();

        $formData = json_decode($deepDive->form_data, true);

        $assessorsList = User::withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->whereIn('id', [$training->primary_assessor, $training->secondary_assessor])
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();
        $verifiersList = User::withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('id', $training->verifier)
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();
        $opsManagersList = User::staffUsers()->withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();
        $employerUsers = User::orderBy('firstnames')->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_EMPLOYER_USER)
                ->where('employer_location', $training->employer_location)
                ->pluck('name', 'id')->toArray();
        
        return view('trainings.deep_dive.edit', compact('training', 'deepDive', 'otjStats', 'gcseGradesList', 'formData', 'assessorsList', 'verifiersList', 'opsManagersList', 'employerUsers'));
    }

    public function update(TrainingRecord $training, DeepDive $deep_dive, Request $request)
    {
        $deepDive = $deep_dive;
        $deepDive->update([
            'deep_dive_date' => $request->input('deep_dive_date'),
            'employer_user_id' => $request->input('employer_user_id'),
            'form_data' => json_encode($request->except(['_token', '_method']))
        ]);

        return redirect()
            ->route('trainings.deep_dives.show', ['training' => $training, 'deep_dive' => $deepDive])
            ->with(['alert-success' => 'Information is updated successfully.']); 
    }
}