<?php

namespace App\Exports;

use App\Models\Programmes\ProgrammeQualification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProgrammeSingleQualificationExport implements FromCollection, WithHeadings
{
    private $qualification;

    public function __construct(ProgrammeQualification $qualification)
    {
        $qualification->load([
            'programme',
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
            'Programme Title',
            'QAN',
            'Qualification Title',
            'Qualification Sequence',
            'Main Qualification',
            'Qualification Proportion',
            'Qualification Duration',
            'Qualification Offset',
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
                    $this->qualification->programme->title,

                    $this->qualification->qan,
                    $this->qualification->title,
                    $this->qualification->sequence,
                    $this->qualification->main,
                    $this->qualification->proportion,
                    $this->qualification->duration,
                    $this->qualification->offset,

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
