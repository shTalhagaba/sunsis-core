<?php

namespace App\Http\Controllers\Training;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\IQA\IqaPlanEntry;
use App\Models\IQA\IqaSamplePlan;
use App\Models\Lookups\TrainingEvidenceCategoryLookup;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Notifications\TrainingIQA\IqaResponse;
use App\Notifications\TrainingIQA\PortfolioUnitIQAd;
use App\Services\Students\Trainings\PortfolioUnitIqaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PortfolioUnitIqaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    private function authorizationCheck(TrainingRecord $training, PortfolioUnit $unit)
    {
        $this->authorize('show', $training);
        abort_if(! auth()->user()->can('iqa-assessment'), Response::HTTP_UNAUTHORIZED);
        abort_if(! in_array($unit->id, $training->units()->select('portfolio_units.id as portfolio_unit_id')->pluck('portfolio_unit_id')->toArray()), Response::HTTP_UNAUTHORIZED);
    }

    public function showUnitIqaForm(TrainingRecord $training, PortfolioUnit $unit, Request $request)
    {
        //$this->authorizationCheck($training, $unit);

        $iqaSamplePlan = new IqaSamplePlan();
        $cancelUrl = route('trainings.show', $training);
        if($request->has('iqa_sample_id'))
        {
            //abort_if(! $this->validateRequestForSampleId($request->iqa_sample_id, $training->id, $unit->id), Response::HTTP_UNAUTHORIZED);

            $iqaSamplePlan = IqaSamplePlan::findOrFail($request->iqa_sample_id);
            //$this->authorize('show', $iqaSamplePlan);
            $cancelUrl = route('iqa_sample_plans.show', $iqaSamplePlan);
        }
        
        $distinctEvidences = DB::table('pc_evidence_mappings')
            ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->select('pc_evidence_mappings.tr_evidence_id')
            ->distinct()
            ->where('portfolio_units.id', '=', $unit->id)
            ->get();

        $evidencesMapped = collect();
        if($distinctEvidences->count() > 0)
        {
            $evidencesMapped = TrainingRecordEvidence::whereIn('id', $distinctEvidences->pluck('tr_evidence_id')->toArray())
                ->with(['media', 'categories'])
                ->get();
        }

        foreach($evidencesMapped AS $e)
        {
            $e->evidence_checked_status = DB::table('tr_evidence_iqa_checked_status')
                ->where('tr_evidence_id', $e->id)
                ->where('user_id', auth()->user()->id)
                ->value('checked');
        }

        $latestIqa = $unit->iqa()->latest()->first();
        $acceptedPcsInLastAssessment = (!is_null($latestIqa) && $latestIqa->accepted_pcs != '') ? explode(",", $latestIqa->accepted_pcs) : [];
        $rejectedPcsInLastAssessment = (!is_null($latestIqa) && $latestIqa->rejected_pcs != '') ? explode(",", $latestIqa->rejected_pcs) : [];

        $assessmentMethods = TrainingEvidenceCategoryLookup::orderBy('description')
            ->pluck('description', 'id')
            ->toArray();
        $iqaPlanEntry = $iqaSamplePlan->entries()->where('id', $request->iqa_entry_id)->first();
        $plannedAssessmentMethods = ($iqaPlanEntry && !is_null($iqaPlanEntry->assessment_methods)) ? json_decode($iqaPlanEntry->assessment_methods) : [];

        return view('trainings.iqa.unit_iqa',
            compact(
                'training', 'unit', 'distinctEvidences', 'acceptedPcsInLastAssessment', 'rejectedPcsInLastAssessment', 
                'evidencesMapped', 'iqaSamplePlan', 'cancelUrl', 'assessmentMethods', 'plannedAssessmentMethods', 'iqaPlanEntry'
            ));
    }

    // public function showUnitIqaForm(TrainingRecord $training, PortfolioUnit $unit, Request $request)
    // {
    //     //$this->authorizationCheck($training, $unit);

    //     $iqaSamplePlan = new IqaSamplePlan();
    //     $cancelUrl = route('trainings.show', $training);
    //     if($request->has('iqa_sample_id'))
    //     {
    //         //abort_if(! $this->validateRequestForSampleId($request->iqa_sample_id, $training->id, $unit->id), Response::HTTP_UNAUTHORIZED);

    //         $iqaSamplePlan = IqaSamplePlan::findOrFail($request->iqa_sample_id);
    //         //$this->authorize('show', $iqaSamplePlan);
    //         $cancelUrl = route('iqa_sample_plans.show', $iqaSamplePlan);
    //     }
        
    //     $distinctEvidences = DB::table('pc_evidence_mappings')
    //         ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
    //         ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
    //         ->select('pc_evidence_mappings.tr_evidence_id')
    //         ->distinct()
    //         ->where('portfolio_units.id', '=', $unit->id)
    //         ->get();

    //     $evidencesMapped = collect();
    //     if($distinctEvidences->count() > 0)
    //     {
    //         $evidencesMapped = TrainingRecordEvidence::whereIn('id', $distinctEvidences->pluck('tr_evidence_id')->toArray())
    //             ->with(['media', 'categories'])
    //             ->get();
    //     }

    //     $latestIqa = $unit->iqa()->latest()->first();
    //     $acceptedPcsInLastAssessment = !is_null($latestIqa) ? explode(",", $latestIqa->accepted_pcs) : [];
    //     $rejectedPcsInLastAssessment = !is_null($latestIqa) ? explode(",", $latestIqa->rejected_pcs) : [];

    //     return view('trainings.iqa.unit_iqa',	
    //         compact('training', 'unit', 'distinctEvidences', 'acceptedPcsInLastAssessment', 'rejectedPcsInLastAssessment', 'evidencesMapped', 'iqaSamplePlan', 'cancelUrl'));
    // }

    public function storeUnitIqa(TrainingRecord $training, PortfolioUnit $unit, Request $request, PortfolioUnitIqaService $unitIqaService)
    {
        //$this->authorizationCheck($training, $unit);
        //abort_if($unit->id != $request->portfolio_unit_id, Response::HTTP_UNAUTHORIZED);
        
        $iqaSamplePlan = new IqaSamplePlan();
        $forwardUrl = route('trainings.show', $training);
        if($request->has('iqa_sample_id') && !is_null($request->iqa_sample_id))
        {
            //abort_if(! $this->validateRequestForSampleId($request->iqa_sample_id, $training->id, $unit->id), Response::HTTP_UNAUTHORIZED);

            $iqaSamplePlan = IqaSamplePlan::findOrFail($request->iqa_sample_id);
            //$this->authorize('show', $iqaSamplePlan);
            $forwardUrl = route('iqa_sample_plans.show', $iqaSamplePlan);
        }

        $request->validate([
            'comments' => 'required',
        ]);

        $acceptedPcs = [];
        $rejectedPcs = [];
        foreach($unit->pcs()->pluck('id')->toArray() AS $pc_id)
        {
            $key = "pc_iqa_status_{$pc_id}";
            if($request->has($key))
            {
                if($request->$key == '1')
                    $acceptedPcs[] = $pc_id;
                elseif ($request->$key == '2')
                    $rejectedPcs[] = $pc_id;
            }
        }

        $input['accepted_pcs'] = $acceptedPcs;
        $input['rejected_pcs'] = $rejectedPcs;
        $input['comments'] = $request->comments;
        $input['fully_completed'] = $request->fully_completed;
        $input['iqa_type'] = $request->iqa_type;

        DB::beginTransaction();
        try
        {
            $unitIqaService->store($training, $unit, $iqaSamplePlan, $input);
            // update the plan entry record 
            if(!empty($request->iqa_sample_entry_id))
            {
                DB::table('iqa_plan_entries')
                    ->where('id', $request->iqa_sample_entry_id)
                    ->update([
                        'actual_assessment_methods' => $request->has('actual_assessment_methods') ? json_encode($request->actual_assessment_methods) : null,
                        'completion_date' => $request->fully_completed ? now()->format('Y-m-d') : null,
                        'iqa_status' => $request->fully_completed ? IqaPlanEntry::IQA_ENTRY_STATUS_COMPLETED : IqaPlanEntry::IQA_ENTRY_STATUS_IN_PROGRESS,
                    ]);
            }

            DB::commit();
        }
        catch(\Throwable $exception)
        {
            DB::rollBack();
            return back()
                ->with(['alert-danger' => $exception->getMessage()]);
        }

        // Notify the assessor about IQA feedback
        $training->primaryAssessor->notify( new PortfolioUnitIQAd($training, $unit, auth()->user()) );
        AppHelper::cacheUnreadCountForUser($training->primaryAssessor);
        
        return redirect($forwardUrl);
    }

    public function showUnitIqaReplyForm(TrainingRecord $training, PortfolioUnit $unit, Request $request)
    {
        //$this->authorize('show', $training);
        //abort_if(! in_array($unit->id, $training->units()->select('portfolio_units.id as portfolio_unit_id')->pluck('portfolio_unit_id')->toArray()), Response::HTTP_UNAUTHORIZED);

        $iqaSamplePlan = new IqaSamplePlan();
        $cancelUrl = route('trainings.show', $training);
        if($request->has('iqa_sample_id'))
        {
            $iqaSamplePlan = IqaSamplePlan::findOrFail($request->iqa_sample_id);
            //$this->authorize('show', $iqaSamplePlan);
            $cancelUrl = route('iqa_sample_plans.show', $iqaSamplePlan);
        }
        
        $distinctEvidences = DB::table('pc_evidence_mappings')
            ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->select('pc_evidence_mappings.tr_evidence_id')
            ->distinct()
            ->where('portfolio_units.id', '=', $unit->id)
            ->get();

        $evidencesMapped = collect();
        if($distinctEvidences->count() > 0)
        {
            $evidencesMapped = TrainingRecordEvidence::whereIn('id', $distinctEvidences->pluck('tr_evidence_id')->toArray())
                ->with(['media', 'categories'])
                ->get();
        }

        $latestIqa = $unit->iqa()->latest()->first();
        $acceptedPcsInLastAssessment = !is_null($latestIqa) ? explode(",", $latestIqa->accepted_pcs) : [];
        $rejectedPcsInLastAssessment = !is_null($latestIqa) ? explode(",", $latestIqa->rejected_pcs) : [];

        return view('trainings.iqa.unit_iqa',
            compact('training', 'unit', 'distinctEvidences', 'acceptedPcsInLastAssessment', 'rejectedPcsInLastAssessment', 'evidencesMapped', 'iqaSamplePlan', 'cancelUrl'));
            
    }

    public function storeUnitIqaReplyForm(TrainingRecord $training, PortfolioUnit $unit, Request $request, PortfolioUnitIqaService $unitIqaService)
    {
        //$this->authorize('show', $training);
        //abort_if(! in_array($unit->id, $training->units()->select('portfolio_units.id as portfolio_unit_id')->pluck('portfolio_unit_id')->toArray()), Response::HTTP_UNAUTHORIZED);
        //abort_if($unit->id != $request->portfolio_unit_id, Response::HTTP_UNAUTHORIZED);

        $request->validate([
            'comments' => 'required',
        ]);

        $input['comments'] = $request->comments;

        DB::beginTransaction();
        try
        {
            $unitIqaService->storeAssessorResponse($training, $unit, $input);
            DB::commit();
        }
        catch(\Throwable $exception)
        {
            DB::rollBack();
            return back()
                ->with(['alert-danger' => $exception->getMessage()]);
        }

        // Notify the verifier about IQA feedback
        $training->verifierUser->notify(new IqaResponse($training, $unit, auth()->user()));
        AppHelper::cacheUnreadCountForUser($training->verifierUser);

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'The information has been saved successfully.']);
    }

    private function validateRequestForSampleId($sampleId, $trainingId, $unitId)
    {
        // check to make sure that given portfolio unit id belongs to the iqa sample. Someone may temper with iqa sample id in address bar
        return DB::table('iqa_sample_plan_tr_units')
            ->where('iqa_sample_id', $sampleId)
            ->where('tr_id', $trainingId)
            ->where('portfolio_unit_id', $unitId)
            ->count();
    }
}