<?php

namespace App\Services\Qualifications;

use App\Models\Qualifications\Qualification;
use App\Models\Qualifications\QualificationUnit;
use Illuminate\Support\Facades\DB;
use Exception;

class QualificationService
{
    public function create(array $qualificationData)
    {
        $systemCode = $this->generateSystemCode($qualificationData['title'], $qualificationData['level']);

        $qualification = Qualification::create( array_merge($qualificationData, ['system_code' => $systemCode]) );

        return $qualification;
    }
    
    public function update(array $qualificationData, Qualification $qualification)
    {
        $systemCode = $this->generateSystemCode($qualificationData['title'], $qualificationData['level']);

        $qualification->update( array_merge($qualificationData, ['system_code' => $systemCode]) );

        return $qualification;
    }

    public function delete(Qualification $qualification)
    {
        return $qualification->delete();
    }

    public function clone(Qualification $sourceQualification, array $inputData)
    {
        $sourceQualification->load([
            'units' => function ($query) {
                $query->orderBy('unit_sequence');
            },
            'units.pcs' => function ($query) {
                $query->orderBy('pc_sequence');
            },
        ]);

        $newQualification = $sourceQualification->replicate([
            'system_code',
            'created_at',
            'updated_at',
        ]);
        $newQualification->title = $inputData['new_qualification_title'];
        $newQualification->push();

        $selectedUnits = $inputData['chkUnit'];
        $selectedPCs = $inputData['chkPC'];

        if(is_array($selectedUnits) && count($selectedUnits) > 0)
        {
            foreach($selectedUnits AS $unit_id)
            {
                $sourceQualificationUnit = $sourceQualification->units->where('id', $unit_id)->first();

                $newQualificationUnit = $sourceQualificationUnit->replicate([
                    'qualification_id',
                    'created_at',
                    'updated_at',
                ]);
                $newQualificationUnit->qualification_id = $newQualification->id;
                $newQualificationUnit->push();

                foreach($sourceQualificationUnit->pcs->whereIn('id', $selectedPCs) AS $sourceQualificationPc)
                {
                    $newQualificationUnitPc = $sourceQualificationPc->replicate([
                        'unit_id',
                        'created_at',
                        'updated_at',
                    ]);
                    $newQualificationUnitPc->unit_id = $newQualificationUnit->id;
                    $newQualificationUnitPc->push();
                }
            }
        }

        return $newQualification;
    }

    private function generateSystemCode($title, $level)
    {
        $expr = '/(?<=\s|^)[a-z]/i';
        preg_match_all($expr, str_replace(['level', 'Level', 'LEVEL'], '', $title), $matches);
        return implode('', $matches[0]) . '-' . $level;
    }

    // units and pcs

    public function createUnit(Qualification $qualification, array $unitData)
    {
        $unit = $qualification->units()->create([
            'unit_sequence' => in_array('unit_sequence', $unitData) ? $unitData['unit_sequence'] : $qualification->units->count()+1,
            'unit_owner_ref' => $unitData['unit_owner_ref'],
            'unique_ref_number' => $unitData['unique_ref_number'],
            'title' => \Str::limit($unitData['title'], 850, ''),
            'unit_group' => $unitData['unit_group'],
            'glh' => $unitData['glh'],
            'unit_credit_value' => $unitData['unit_credit_value'],
            'learning_outcomes' => $unitData['learning_outcomes'],
            'system_code' => $qualification->qan . '|' . $unitData['unique_ref_number'],
        ]);

        $this->refreshUnitPcs($unit, $unitData);

        return $unit;
    }

    public function updateUnit(QualificationUnit $unit, array $unitData)
    {
        $unit->update([
            'unit_owner_ref' => $unitData['unit_owner_ref'],
            'unique_ref_number' => $unitData['unique_ref_number'],
            'title' => \Str::limit($unitData['title'], 850, ''),
            'unit_group' => $unitData['unit_group'],
            'glh' => $unitData['glh'],
            'unit_credit_value' => $unitData['unit_credit_value'],
            'learning_outcomes' => $unitData['learning_outcomes'],
            'system_code' => $unit->qualification->qan . '|' . $unitData['unique_ref_number'],
        ]);

        DB::beginTransaction();
        try
        {
            $unit->pcs()->delete();

            $this->refreshUnitPcs($unit, $unitData);
        }
        catch(Exception $ex)
        {
            DB::rollback();
            throw $ex;
        }
        DB::commit();

        return $unit;
    }

    public function deleteUnit(QualificationUnit $unit)
    {
        return $unit->delete();
    }

    private function refreshUnitPcs(QualificationUnit $unit, array $unitData)
    {
        $pcsCount = isset($unitData['number_of_pcs']) ? $unitData['number_of_pcs'] : 0;

        for($i = 1; $i <= $pcsCount; $i++)
        {
            $prefix = 'pc_'.$i.'_';
            $prefixSequence = $prefix.'sequence'; 
            $prefixTitle = $prefix.'title'; 
            if( isset($unitData[$prefixSequence]) && trim( $unitData[$prefixTitle] ) != '' )
            {
                $unit->pcs()->create([
                    'pc_sequence'=> $unitData[$prefix.'sequence'],
                    'reference'=> $unitData[$prefix.'reference'],
                    'title'=> \Str::limit($unitData[$prefix.'title'], 850, ''),
                    'category'=> $unitData[$prefix.'category'],
                    'min_req_evidences'=> $unitData[$prefix.'min_req_evidences'],
                    'description'=> \Str::limit($unitData[$prefix.'description'], 500, ''),
                    'system_code' => $unit->qualification->qan . '|' . $unit->unique_ref_number . '|' . $unitData[$prefix.'reference'],
                ]);
            }
        }

        return $unit;
    }
}