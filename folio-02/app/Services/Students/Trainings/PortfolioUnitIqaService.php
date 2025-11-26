<?php

namespace App\Services\Students\Trainings;

use App\Models\IQA\IqaSamplePlan;
use App\Models\Training\PortfolioPC;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\PortfolioUnitIqa;
use App\Models\Training\TrainingRecord;
use Exception;
use Illuminate\Support\Facades\DB;

class PortfolioUnitIqaService
{
    /**
     * 
     * This function is responsible for saving the information when IQA/Verifier verifies 
     * portfolio unit of the training record.
     * 
     * @param  App\Models\Training\TrainingRecord  $training
     * @param  App\Models\Training\PortfolioUnit  $unit
     * @param  App\Models\IQA\IqaSamplePlan|null $iqaSamplePlan
     * @param  array  $input
     * 
     * @return boolean
     */
    public function store(TrainingRecord $training, PortfolioUnit $unit, IqaSamplePlan $iqaSamplePlan, $input)
    {
        $acceptedPcs = $input['accepted_pcs'] ?? [];
        $rejectedPcs = $input['rejected_pcs'] ?? [];

        // Update IQA unit status
        $unit->update([
            'iqa_status' => count($rejectedPcs) > 0 ? PortfolioUnitIqa::STATUS_IQA_REFERRED : PortfolioUnitIqa::STATUS_IQA_ACCEPTED,
            'iqa_completed' => count($rejectedPcs) > 0 ? false : $input['fully_completed'],
            'iqa_sample_id' => $iqaSamplePlan->id ?? null, 
        ]);

        // Store iqa information in portfolio_units_iqa table
        $portfolioUnitIqa = PortfolioUnitIqa::create([
            'portfolio_unit_id' => $unit->id,
            'accepted_pcs' => implode(",", $acceptedPcs),
            'rejected_pcs' => implode(",", $rejectedPcs),
            'comments' => $input['comments'],
            'iqa_type' => $input['iqa_type'],
            'user_id' => auth()->user()->id,
            'iqa_sample_id' => $iqaSamplePlan->id,
            'system_code' => $unit->system_code,
        ]);

        // Update the IQA status of PCs
        PortfolioPC::whereIn('id', $acceptedPcs)->update(['iqa_status' => PortfolioUnitIqa::STATUS_IQA_ACCEPTED]);
        PortfolioPC::whereIn('id', $rejectedPcs)->update(['iqa_status' => PortfolioUnitIqa::STATUS_IQA_REFERRED]);

        // Update the status of related IQA sample plan
        if($iqaSamplePlan->id)
        {
            $iqaSamplePlan->updateStatus();

            // get iqa_sample_plan_tr_unit_id
            $iqaSamplePlanTrUnitId = DB::table('iqa_sample_plan_tr_units')
                ->where('iqa_sample_id', $iqaSamplePlan->id)
                ->where('tr_id', $training->id)
                ->where('portfolio_unit_id', $unit->id)
                ->value('id');
                
            if(is_null($iqaSamplePlanTrUnitId))
            {
                $iqaSamplePlanTrUnitId = DB::table('iqa_sample_plan_tr_units')
                    ->insert([
                        'iqa_sample_id' => $iqaSamplePlan->id,
                        'tr_id' => $training->id,
                        'portfolio_unit_id' => $unit->id,
                        'portfolio_id' => $unit->portfolio->id,
                        'portfolio_unit_system_code' => $unit->system_code,
                    ]);
            }
            else
            {
                // update status in iqa_sample_plan_tr_units
                DB::table('iqa_sample_plan_tr_units')
                    ->where('id', $iqaSamplePlanTrUnitId)
                    ->update([
                        'iqa_status' => $unit->iqa_status == PortfolioUnitIqa::STATUS_IQA_REFERRED ? 'rejected' : 'accepted'
                    ]);
            }

            // log iqa_sample_plan_tr_units comments
            DB::table('iqa_sample_plan_tr_unit_comments')
                ->insert([
                    'iqa_sample_plan_tr_unit_id' => $iqaSamplePlanTrUnitId,
                    'iqa_status' => $unit->iqa_status == PortfolioUnitIqa::STATUS_IQA_REFERRED ? 'rejected' : 'accepted',
                    'iqa_comments' => $input['comments'],
                    'verifier_id' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }
        
        return true;
    }

    /**
     * 
     * This function is responsible for saving the assessor response IQA/Verifier verifies 
     * portfolio unit of the training record.
     * 
     * @param  App\Models\Training\TrainingRecord  $training
     * @param  App\Models\Training\PortfolioUnit  $unit
     * @param  array  $input
     * 
     * @return boolean
     */
    public function storeAssessorResponse(TrainingRecord $training, PortfolioUnit $unit, $input)
    {
        $latestIqa = $unit->iqa()->latest()->first();

        // Store iqa reply in portfolio_units_iqa table
        $portfolioUnitIqa = PortfolioUnitIqa::create([
            'portfolio_unit_id' => $unit->id,
            'accepted_pcs' => $latestIqa->accepted_pcs,
            'rejected_pcs' => $latestIqa->rejected_pcs,
            'comments' => $input['comments'],
            'iqa_type' => $latestIqa->iqa_type,
            'user_id' => auth()->user()->id,
            'iqa_sample_id' => $latestIqa->iqa_sample_id,
            'system_code' => $latestIqa->system_code,
        ]);
        
        return true;
    }


}