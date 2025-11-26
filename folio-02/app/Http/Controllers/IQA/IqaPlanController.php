<?php

namespace App\Http\Controllers\IQA;

use App\Filters\IqaPlanFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IQA\IqaPlan;
use App\Models\Lookups\TrainingEvidenceCategoryLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Qualifications\Qualification;
use App\Models\Qualifications\QualificationUnit;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class IqaPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, IqaPlanFilters $filters)
    {
        $query = IqaPlan::filter($filters)
            ->with(['assessor', 'verifier', 'creator']);

        if( auth()->user()->isVerifier() )
        {
            $query->where('iqa_plans.verifier_id', '=', auth()->user()->id);
        }

        if( auth()->user()->isAssessor() )
        {
            $query->where('iqa_plans.assessor_id', '=', auth()->user()->id);
        }

        $plans = $query->paginate(session('iqa_plans_per_page', config('model_filters.default_per_page')));

        return view('iqav2.index', compact('plans', 'filters'));
    }

    public function create()
    {
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

        $learningAims = Qualification::orderBy('title')->pluck('title', 'id')->toArray();

        return view('iqav2.create', compact('assessors', 'verifiers', 'learningAims'));
    }

    public function store(Request $request)
    {
        $learningAim = Qualification::find($request->learning_aim_id);

        $plan = IqaPlan::query()
            ->where('verifier_id', $request->verifier_id)
            ->where('assessor_id', $request->assessor_id)
            ->where('learning_aim_qan', $request->learning_aim_qan)
            ->where('learning_aim_title', $request->learning_aim_title)
            ->first();

        if(is_null($plan))
        {
            $plan = IqaPlan::create([
                'verifier_id' => $request->verifier_id,
                'assessor_id' => $request->assessor_id,
                'learning_aim_qan' => $learningAim->qan,
                'learning_aim_title' => $learningAim->title,
                'created_by' => auth()->user()->id,
            ]);
        }

        return redirect()
            ->route('iqa_plans.show', $plan)
            ->with(['alert-success' => 'Plan has been created successfully.']);
    }

    public function show(IqaPlan $plan)
    {
        $plan->load('entries');


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

        $trainings = DB::table('portfolios')
            ->join('tr', 'tr.id', '=', 'portfolios.tr_id')
            ->join('users', 'users.id', '=', 'tr.student_id')
            ->where('portfolios.qan', $plan->learning_aim_qan)
            ->where('portfolios.title', $plan->learning_aim_title)
            ->where(function($query) use ($plan){
                return $query->where('tr.verifier', $plan->verifier_id)
                    ->orWhere('portfolios.fs_verifier_id', $plan->verifier_id);
            })
            ->where(function($query) use ($plan){
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
                'tr.status_code',
            ])
            ->get();

        $qualification = Qualification::where('qan', $plan->learning_aim_qan)
            ->where('title', $plan->learning_aim_title)
            ->first();

        $trainingIds = $trainings->count() > 0 ? $trainings->pluck('id')->toArray() : [];
        $units = $qualification->units()->select(['unit_owner_ref', 'unique_ref_number', 'title', 'system_code'])->get();

        // loop over all the units and then save which training records are actually doing those units
        $unitsTrs = [];
        foreach($units AS $unit)
        {
            $unitsTrs[$unit->system_code] = DB::table('portfolio_units')
                ->join('portfolios', 'portfolios.id', '=', 'portfolio_units.portfolio_id')
                ->whereIn('portfolios.tr_id', $trainingIds)
                ->where('portfolio_units.system_code', $unit->system_code)
                ->pluck('portfolios.tr_id')
                ->toArray();
        }

        return view('iqav2.show', compact('plan', 'trainings', 'units', 'unitsTrs'));
    }

    public function showPlanSingleEntry(IqaPlan $plan, Request $request)
    {
        // TODO: Need to make sure that plan id is not tempered in the browser.
        $training = TrainingRecord::findOrFail($request->TrainingID);
        $unitOwnerRef = $request->UnitOwnerRef;
        $uniqueRefNumber = $request->UniqueRefNumber;
        $unit = QualificationUnit::where('system_code', "{$plan->learning_aim_qan}|{$uniqueRefNumber}")->first();
        $assessmentMethods = TrainingEvidenceCategoryLookup::orderBy('description')
            ->pluck('description', 'id')
            ->toArray();
        $savedEntry = $plan->entries()
            ->where('training_id', $training->id)
            ->where('unit_unique_ref_number', $unit->unique_ref_number)
            ->where('unit_owner_ref', $unit->unit_owner_ref)
            ->first();

        return view('iqav2.plan_single_entry', compact('plan', 'training', 'unit', 'assessmentMethods', 'savedEntry'));
    }

    public function savePlanSingleEntry(IqaPlan $plan, Request $request)
    {
        $training = TrainingRecord::findOrFail($request->TrainingID);
        $unitOwnerRef = $request->UnitOwnerRef;
        $uniqueRefNumber = $request->UniqueRefNumber;

        // pick up the portfolio_units.id from the unit info
        $portfolioUnitId = DB::table('portfolio_units')
            ->join('portfolios', 'portfolios.id', '=', 'portfolio_units.portfolio_id')
            ->where('portfolios.tr_id', $training->id)
            ->where('portfolio_units.system_code', "{$plan->learning_aim_qan}|{$uniqueRefNumber}")
            ->value('portfolio_units.id');

        $plan->entries()->updateOrCreate(
            ['portfolio_unit_id' => $portfolioUnitId], 
            [
                'training_id' => $training->id,
                'unit_unique_ref_number' => $uniqueRefNumber,
                'unit_owner_ref' => $unitOwnerRef,
                'planned_completion_date' => $request->planned_completion_date,
                'assessment_methods' => json_encode($request->assessment_methods),
                'reminder_date' => $request->reminder_date,
                'iqa_status' => 'SUBMITTED',
            ] 
        );

        return redirect()->route('iqa_plans.show', $plan)->with(['alert-success' => 'Information is saved successfully.']);
    }
}
