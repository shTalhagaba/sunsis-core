<?php

namespace App\Exports;

use App\Models\Qualifications\Qualification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SingleQualificationExport implements FromCollection, WithHeadings
{
    private $qualification;

    public function __construct(Qualification $qualification)
    {
        $qualification->load([
            'units' => function ($query) {
                $query->orderBy('unit_sequence');
            },
            'units.pcs' => function ($query) {
                $query->orderBy('pc_sequence');
            },
        ]);
        $this->qualification = $qualification;
    }

    public function headings(): array
    {
        return [
            'QAN',
            'Qualificcation Owner',
            'Qualification Title',
            'Qualification Level',
            'Unit Sequence',
            'Unit Owner Ref',
            'Unit Unique Ref',
            'Unit Title',
            'Unit Group',
            'Unit GLH',
            'Unit Credit Value',
            'Unit Learning Outcomes',
            'PC Sequence',
            'PC Reference',
            'PC Category',
            'PC Title',
            'PC Min. Required Evidences',
            'PC Description',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $result = collect();
        foreach($this->qualification->units AS $unit)
        {
            foreach($unit->pcs AS $pc)
            {
                $result->push([
                    $this->qualification->qan,
                    $this->qualification->owner_org_acronym,
                    $this->qualification->title,
                    $this->qualification->level,

                    $unit->unit_sequence,
                    $unit->unit_owner_ref,
                    $unit->unique_ref_number,
                    $unit->title,
                    $unit->unit_group,
                    $unit->glh,
                    $unit->unit_credit_value,
                    $unit->learning_outcomes,

                    $pc->pc_sequence,
                    $pc->reference,
                    $pc->category,
                    $pc->title,
                    $pc->min_req_evidences,
                    $pc->description,
                ]);
            }            
        }

        return $result;
    }
}
