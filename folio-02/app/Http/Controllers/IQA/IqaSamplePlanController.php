<?php

namespace App\Http\Controllers\IQA;

use App\Exports\IqaSamplePlansExport;
use App\Filters\IqaSamplePlanFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIqaSamplePlanRequest;
use App\Models\IQA\IqaPlanEntry;
use App\Models\IQA\IqaSamplePlan;
use App\Models\IQA\IqaSamplePlanTraining;
use App\Models\IQA\IqaSamplePlanUnit;
use App\Models\Lookups\TrainingEvidenceCategoryLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeQualification;
use App\Models\Qualifications\Qualification;
use App\Models\Qualifications\QualificationUnit;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\PortfolioUnitIqa;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class IqaSamplePlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, IqaSamplePlanFilters $filters)
    {
        $this->authorize('index', IqaSamplePlan::class);

        $query = IqaSamplePlan::filter($filters)
            ->with(['programme', 'verifier', 'assessor', 'creator'])
            ->withCount(['units', 'trainings']);

        if (auth()->user()->isVerifier()) {
            $query->where('iqa_sample_plans.verifier_id', '=', auth()->user()->id);
        }

        if (auth()->user()->isAssessor()) {
            $query->where('iqa_sample_plans.assessor_id', '=', auth()->user()->id);
        }

        $plans = $query->paginate(session('iqa_sample_plans_per_page', config('model_filters.default_per_page')));

        return view('iqav2.index', compact('plans', 'filters'));
    }

    public function export(IqaSamplePlanFilters $filters)
    {
        $this->authorize('index', IqaSamplePlan::class);

        return Excel::download(new IqaSamplePlansExport($filters), 'IQA Sample Plans.xlsx');
    }

    public function create()
    {
        $this->authorize('create', IqaSamplePlan::class);

        $programmes = Programme::orderBy('title')
            ->where('status', true)
            ->pluck('title', 'id')
            ->toArray();

        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $assessors = [];
        $learningAims = [];

        return view('iqav2.create', compact('programmes', 'verifiers', 'assessors', 'learningAims'));
    }

    public function store(StoreIqaSamplePlanRequest $request)
    {
        $this->authorize('create', IqaSamplePlan::class);

        $plan = IqaSamplePlan::query()
            ->where('verifier_id', $request->verifier_id)
            ->where('assessor_id', $request->assessor_id)
            ->where('learning_aim_qan', $request->learning_aim_qan)
            ->where('learning_aim_title', $request->learning_aim_title)
            ->first();

        if (is_null($plan)) {
            $learningAim = Qualification::find($request->learning_aim_id);

            $plan = IqaSamplePlan::create([
                'title' => $request->title,
                'verifier_id' => $request->verifier_id,
                'programme_id' => $request->programme_id,
                'completed_by_date' => $request->completed_by_date,
                'type' => $request->type,
                'status' => 'scheduled',
                'assessor_id' => $request->assessor_id,
                'learning_aim_qan' => $learningAim->qan,
                'learning_aim_title' => $learningAim->title,
                'created_by' => auth()->user()->id,
            ]);
        }

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Plan has been created successfully. Please now add the units and learners into the plan.']);
    }

    public function show(IqaSamplePlan $plan, Request $request)
    {
        $this->authorize('show', $plan);

        $plan->load('entries');
        /*
        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $assessors = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_ASSESSOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
*/
        $trainings = DB::table('portfolios')
            ->join('tr', 'tr.id', '=', 'portfolios.tr_id')
            ->join('users', 'users.id', '=', 'tr.student_id')
            ->where('portfolios.qan', $plan->learning_aim_qan)
            ->where('portfolios.title', $plan->learning_aim_title)
            ->where(function ($query) use ($plan) {
                return $query->where('tr.verifier', $plan->verifier_id)
                    ->orWhere('portfolios.fs_verifier_id', $plan->verifier_id);
            })
            ->where(function ($query) use ($plan) {
                return $query->where('tr.primary_assessor', $plan->assessor_id)
                    ->orWhere('tr.secondary_assessor', $plan->assessor_id);
            })
            ->orderBy('users.firstnames')
            ->select([
                'tr.student_id',
                'tr.id',
                'users.firstnames',
                'users.surname',
                'tr.start_date',
                'tr.planned_end_date',
                'tr.actual_end_date',
                'tr.status_code',
            ])
            ->get();

        $qualification = Qualification::where('qan', $plan->learning_aim_qan)
            ->where('title', $plan->learning_aim_title)
            ->first();

        $trainingIds = $trainings->count() > 0 ? $trainings->pluck('id')->toArray() : [];
        if ($qualification) {
            $units = $qualification->units()->select(['unit_owner_ref', 'unique_ref_number', 'title', 'system_code'])->get();
        }

        // training records and units (to check which are doing the units)
        $unitsTrs = [];
        $records = DB::table('portfolio_units')
            ->join('portfolios', 'portfolios.id', '=', 'portfolio_units.portfolio_id')
            ->whereIn('portfolios.tr_id', $trainingIds)
            ->where('portfolios.qan', $plan->learning_aim_qan)
            ->where('portfolios.title', $plan->learning_aim_title)
            ->whereIn('portfolio_units.system_code', collect($units)->pluck('system_code'))
            ->select('portfolio_units.system_code', 'portfolios.tr_id', 'portfolio_units.iqa_completed', 'portfolio_units.id')
            ->get();

        $assessmentMethods = TrainingEvidenceCategoryLookup::orderBy('description')
            ->pluck('description', 'id')
            ->toArray();

        foreach ($records as $record) {
            $unitsTrs[$record->system_code][$record->tr_id] = [
                'tr_id' => $record->tr_id,
                'system_code' => $record->system_code,
                'iqa_completed' => $record->iqa_completed,
                'portfolio_unit_id' => $record->id,
            ];
        }

        // $portfolioUnitWithIqaNotes = PortfolioUnitIqa::whereIn('portfolio_unit_id', $records->pluck('id')->toArray())
        //     // ->pluck('portfolio_unit_id')
        //     // ->toArray()
        //     ->select(['portfolio_unit_id', 'created_at', 'iqa_type'])
        //     ->get()
        //     ;

        $portfolioUnitWithIqaNotes = PortfolioUnitIqa::from('portfolio_units_iqa as pui')
            ->whereIn('portfolio_unit_id', $records->pluck('id'))
            ->whereRaw('created_at = (SELECT MAX(created_at) 
                                    FROM portfolio_units_iqa 
                                    WHERE portfolio_unit_id = pui.portfolio_unit_id)')
            ->get(['portfolio_unit_id', 'created_at', 'iqa_type']);

        // dd($portfolioUnitWithIqaNotes);

        return view('iqav2.show', compact('plan', 'trainings', 'units', 'unitsTrs', 'assessmentMethods', 'portfolioUnitWithIqaNotes'));
    }

    public function showtbd(IqaSamplePlan $plan, Request $request)
    {
        $this->authorize('show', $plan);

        $plan->with(['units', 'trainings', 'verifier', 'assessor', 'creator'])
            ->withCount(['units', 'trainings']);

        $selectedTrainingUnits = DB::table('iqa_sample_plan_tr_units')
            ->where('iqa_sample_id', $plan->id)
            ->pluck('portfolio_unit_id')
            ->toArray();

        $view = $request->input('view') == 'grid' ? 'show_grid' : 'show';
        return view('iqa.sample.' . $view, compact('plan', 'selectedTrainingUnits'));
    }

    public function edit(IqaSamplePlan $plan)
    {
        $this->authorize('show', $plan);

        $programmes = Programme::orderBy('title')
            ->where('status', true)
            ->pluck('title', 'id')
            ->toArray();

        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $view = 'iqa.sample.edit';
        if ($plan->isOngoing()) {
            $view = 'iqa.sample.edit_basic';
        }

        return view($view, compact('plan', 'programmes', 'verifiers'));
    }

    public function updateBasic(IqaSamplePlan $plan, Request $request)
    {
        $this->authorize('updateBasic', $plan);

        $request->validate([
            'title' => 'required|max:70',
            'type' => 'required|string|in:' . implode(',', array_keys(IqaSamplePlan::getTypeList())),
            'completed_by_date' => 'required|date',
        ]);

        $plan->update([
            'title' => $request->title,
            'type' => $request->type,
            'completed_by_date' => $request->completed_by_date,
        ]);

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Plan has been updated successfully.']);
    }

    public function update(IqaSamplePlan $plan, StoreIqaSamplePlanRequest $request)
    {
        $this->authorize('update', $plan);

        DB::beginTransaction();
        try {
            $plan->trainings()->delete();
            $plan->units()->delete();
            $plan->qualifications()->delete();

            $plan->update([
                'verifier_id' => $request->verifier_id,
                'title' => $request->title,
                'type' => $request->type,
                'completed_by_date' => $request->completed_by_date,
                'programme_id' => $request->programme_id,
            ]);

            $programmeQualifications = ProgrammeQualification::whereIn('id', $request->qualifications)->get();
            foreach ($programmeQualifications as $programeQualification) {
                $plan->qualifications()->create([
                    'qan' => $programeQualification->qan,
                    'title' => $programeQualification->title,
                    'min_glh' => $programeQualification->min_glh,
                    'max_glh' => $programeQualification->max_glh,
                    'glh' => $programeQualification->glh,
                    'total_credits' => $programeQualification->total_credits,
                    'assessment_methods' => $programeQualification->assessment_methods,
                ]);
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            return back()
                ->with(['alert-danger' => $exception->getMessage()]);
        }

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Plan has been updated successfully. Please now add the units and learners into the plan.']);
    }

    public function manageUnits(IqaSamplePlan $plan)
    {
        $this->authorize('addUnitsAndTrainings', $plan);

        // prepares and opens blade view to select units of selected qualifications in the plan
        $qansSelectedForPlan = $plan->qualifications()->pluck('qan')->toArray();

        // only those units which are not part of the plan yet
        $existingUnits = $plan->units()->pluck('system_code')->toArray();

        // now only pick qualifications from programme which are selected for this plan. And for each qualification only pick up those units which are not yet added into the plan.
        $programmeQualifications = $plan
            ->programme
            ->qualifications()
            ->whereIn('qan', $qansSelectedForPlan)
            ->with(['units' => function ($query) use ($existingUnits) {
                $query->whereNotIn('system_code', $existingUnits);
            }])
            ->get();

        return view('iqa.sample.manage_units', compact('plan', 'programmeQualifications'));
    }

    public function updateUnits(IqaSamplePlan $plan, Request $request)
    {
        $this->authorize('addUnitsAndTrainings', $plan);

        $request->validate([
            'iqa_sample_id' => 'required|numeric|in:' . $plan->id,
            'programmeQualificationUnits' => 'required|array|min:1',
        ]);

        foreach ($request->programmeQualificationUnits as $programmeQualificationUnit) {
            $detail = json_decode($programmeQualificationUnit);
            $plan->units()->create((array) $detail);
        }

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Units have been added into the sample plan.']);
    }

    public function manageTrainingRecords(IqaSamplePlan $plan)
    {
        $this->authorize('addUnitsAndTrainings', $plan);

        $verifierId = $plan->verifier_id;

        $selectedTrainingIds = $plan->trainings()->pluck('tr_id')->toArray();

        $trainingRecords = DB::table('tr')
            ->select(
                'tr.id',
                'tr.student_id',
                'tr.start_date',
                'tr.start_date',
                'tr.planned_end_date',
                'tr.primary_assessor',
                'tr.actual_end_date',
                'tr.status_code',
                'tr.verifier',
                'tr.programme_id',
                'users.firstnames',
                'users.surname'
            )
            ->selectRaw("(SELECT programmes.title FROM programmes WHERE programmes.id = tr.programme_id) AS programme_title")
            ->join('users', 'tr.student_id', '=', 'users.id')
            ->join('portfolios', 'tr.id', '=', 'portfolios.tr_id')
            ->join('portfolio_units', 'portfolios.id', '=', 'portfolio_units.portfolio_id')
            ->whereNotIn('tr.id', $selectedTrainingIds)
            ->where('tr.programme_id', $plan->programme_id)
            ->where('tr.verifier', $plan->verifier_id)
            ->whereIn('portfolio_units.system_code', $plan->units()->pluck('system_code')->toArray())
            ->where(function ($query) {
                return $query->where('portfolio_units.iqa_completed', '!=', true)
                    ->orWhereNull('portfolio_units.iqa_completed');
            })
            ->where('users.user_type', UserTypeLookup::TYPE_STUDENT)
            ->distinct()
            ->get();

        $plan->load([
            'programme',
            'programme.training_records' => function ($query) use ($verifierId) {
                $query
                    ->select('id', 'start_date', 'planned_end_date', 'actual_end_date', 'status_code', 'verifier', 'programme_id', 'student_id')
                    ->where('verifier', $verifierId);
            },
            'programme.training_records.student:firstnames,surname,id'
        ]);

        return view('iqa.sample.manage_trainings', compact('plan', 'selectedTrainingIds', 'trainingRecords'));
    }

    public function updateTrainingRecords(IqaSamplePlan $plan, Request $request)
    {
        $this->authorize('addUnitsAndTrainings', $plan);

        $request->validate([
            'iqa_sample_id' => 'required|numeric|in:' . $plan->id,
            'trainings' => 'required|array|min:1',
        ]);

        foreach ($request->trainings as $trainingId) {
            $plan->trainings()->create(['tr_id' => $trainingId]);
        }

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Students have been added into the sample plan.']);
    }

    public function deleteUnit(IqaSamplePlan $plan, IqaSamplePlanUnit $unit)
    {
        $this->authorize('update', $plan);

        $unit->delete();

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Unit has been removed from the sample plan.']);
    }

    public function deleteTraining(IqaSamplePlan $plan, IqaSamplePlanTraining $training)
    {
        $this->authorize('update', $plan);

        $training->delete();

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Training record has been removed from the sample plan.']);
    }

    public function addRemoveTrainingUnits(IqaSamplePlan $plan, Request $request)
    {
        $this->authorize('addUnitsAndTrainings', $plan);

        $validator = Validator::make($request->all(), [
            'training_id' => 'required|numeric',
            'portfolio_id' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'action' => 'required|string|in:add,remove',
        ]);

        $training = TrainingRecord::findOrFail($request->training_id);
        $portfolio = Portfolio::findOrFail($request->portfolio_id);
        $portfolioUnit = PortfolioUnit::findOrFail($request->unit_id);

        if ($portfolio->tr_id !== $training->id || $portfolioUnit->portfolio_id !== $portfolio->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($request->action == 'remove') {
            // if action is remove then make sure that it has not already been added and iqa'd
            $exists = DB::table('iqa_sample_plan_tr_units')
                ->where('iqa_sample_id', $plan->id)
                ->where('tr_id', $training->id)
                ->where('portfolio_id', $portfolio->id)
                ->where('portfolio_unit_id', $portfolioUnit->id)
                ->whereIn('iqa_status', ['accepted', 'referred'])
                ->count();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This unit has been checked by an IQA so it can\'t be removed from the plan.',
                ], Response::HTTP_BAD_REQUEST);
            }

            DB::table('iqa_sample_plan_tr_units')
                ->where('iqa_sample_id', $plan->id)
                ->where('tr_id', $training->id)
                ->where('portfolio_id', $portfolio->id)
                ->where('portfolio_unit_id', $portfolioUnit->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'removed successfully from the sample plan.',
            ]);
        }

        DB::table('iqa_sample_plan_tr_units')
            ->insert([
                'iqa_sample_id' => $plan->id,
                'tr_id' => $training->id,
                'portfolio_id' => $portfolio->id,
                'portfolio_unit_id' => $portfolioUnit->id,
                'portfolio_unit_system_code' => $portfolioUnit->system_code,
                'iqa_status' => 'added',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'added successfully into the sample plan.',
        ]);
    }

    public function showPlanSingleEntry(IqaSamplePlan $plan, Request $request)
    {
        // TODO: Need to make sure that plan id is not tempered in the browser.
        $training = TrainingRecord::findOrFail($request->TrainingID);
        $unitOwnerRef = $request->UnitOwnerRef;
        $uniqueRefNumber = $request->UniqueRefNumber;
        $portfolioUnitId = $request->portfolio_unit_id;
        $unit = QualificationUnit::where('system_code', "{$plan->learning_aim_qan}|{$uniqueRefNumber}")->first();
        $assessmentMethods = TrainingEvidenceCategoryLookup::orderBy('description')
            ->pluck('description', 'id')
            ->toArray();
        $savedEntry = $plan->entries()
            ->where('portfolio_unit_id', $portfolioUnitId)
            ->where('training_id', $training->id)
            ->where('unit_unique_ref_number', $unit->unique_ref_number)
            ->where('unit_owner_ref', $unit->unit_owner_ref)
            ->first();

        return view('iqav2.plan_single_entry', compact('plan', 'training', 'unit', 'assessmentMethods', 'savedEntry', 'portfolioUnitId'));
    }

    public function savePlanSingleEntry(IqaSamplePlan $plan, Request $request)
    {
        $training = TrainingRecord::findOrFail($request->TrainingID);
        $unitOwnerRef = $request->UnitOwnerRef;
        $uniqueRefNumber = $request->UniqueRefNumber;
        $portfolioUnitId = $request->portfolio_unit_id;

        // pick up the portfolio_units.id from the unit info
        // $portfolioUnitId = DB::table('portfolio_units')
        //     ->join('portfolios', 'portfolios.id', '=', 'portfolio_units.portfolio_id')
        //     ->where('portfolios.tr_id', $training->id)
        //     ->where('portfolio_units.system_code', "{$plan->learning_aim_qan}|{$uniqueRefNumber}")
        //     ->value('portfolio_units.id');

        // $plan->entries()->updateOrCreate(
        //     ['portfolio_unit_id' => $portfolioUnitId], 
        //     [
        //         'training_id' => $training->id,
        //         'planned_completion_date' => $request->planned_completion_date,
        //         'assessment_methods' => json_encode($request->assessment_methods),
        //         'reminder_date' => $request->reminder_date,
        //         'iqa_status' => 'PLANNED',
        //         'unit_unique_ref_number' => $uniqueRefNumber,
        //         'unit_owner_ref' => $unitOwnerRef,

        //     ] 
        // );

        $this->createUpdatePlanEntry($plan, [
            'portfolioUnitId' => $portfolioUnitId,
            'trainingId' => $training->id,
            'unitOwnerRef' => $unitOwnerRef,
            'uniqueRefNumber' => $uniqueRefNumber,
            'plannedCompletionDate' => $request->planned_completion_date,
            'reminderDate' => $request->reminder_date,
            'assessmentMethods' => json_encode($request->assessment_methods),
        ]);

        return redirect()->route('iqa_sample_plans.show', $plan)->with(['alert-success' => 'Information is saved successfully.']);
    }

    public function savePlanMultiEntry(IqaSamplePlan $plan, Request $request)
    {
        $multiModeChecks = $request->input('multi_mode_checks', []);
        foreach ($multiModeChecks as $entry) {
            $this->createUpdatePlanEntry($plan, [
                'portfolioUnitId' => $entry['portfolio_unit_id'],
                'trainingId' => $entry['training_id'],
                'unitOwnerRef' => $entry['unit_owner_ref'],
                'uniqueRefNumber' => $entry['unique_ref_number'],
                'plannedCompletionDate' => $request->planned_completion_date,
                'reminderDate' => $request->reminder_date,
                'assessmentMethods' => json_encode($request->assessment_methods),
            ]);
        }

        return redirect()->route('iqa_sample_plans.show', $plan)->with(['alert-success' => 'Information is saved successfully.']);
    }

    private function createUpdatePlanEntry($plan, $inputData)
    {
        $plan->entries()->updateOrCreate(
            ['portfolio_unit_id' => $inputData['portfolioUnitId']],
            [
                'training_id' => $inputData['trainingId'],
                'planned_completion_date' => $inputData['plannedCompletionDate'],
                'assessment_methods' => $inputData['assessmentMethods'],
                'reminder_date' => $inputData['reminderDate'],
                'iqa_status' => 'PLANNED',
                'unit_unique_ref_number' => $inputData['uniqueRefNumber'],
                'unit_owner_ref' => $inputData['unitOwnerRef'],

            ]
        );
    }

    public function deletePlanEntry(IqaSamplePlan $plan, IqaPlanEntry $entry)
    {
        // $this->authorize('update', $plan);

        if ($entry->iqa_plan_id !== $plan->id) {
            return back()
                ->with(['alert-danger' => 'This entry does not belong to the selected sample plan.']);
        }

        if (!auth()->user()->isAdmin() && auth()->user()->id !== $entry->created_by) {
            return back()
                ->with(['alert-danger' => 'You do not have permission to delete this entry.']);
        }

        if ($entry->iqa_status !== IqaPlanEntry::IQA_ENTRY_STATUS_PLANNED) {
            return back()
                ->with(['alert-danger' => 'You can only delete entries which are planned.']);
        }

        $entry->delete();

        return redirect()
            ->route('iqa_sample_plans.show', $plan)
            ->with(['alert-success' => 'Entry has been removed from the sample plan.']);
    }

    public function destroy(IqaSamplePlan $plan)
    {
        if (!auth()->user()->isAdmin() && auth()->user()->id !== $plan->created_by) {
            return back()
                ->with(['alert-danger' => 'You do not have permission to delete this sample plan.']);
        }
        if ($plan->entries()->count() > 0) {
            return back()
                ->with(['alert-danger' => 'You can not delete a sample plan which has entries.']);
        }
        $plan->delete();

        return redirect()
            ->route('iqa_sample_plans.index')
            ->with(['alert-success' => 'Sample plan has been deleted successfully.']);
    }
}
