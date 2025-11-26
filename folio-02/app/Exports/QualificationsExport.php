<?php

namespace App\Exports;

use App\Models\Qualifications\Qualification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QualificationsExport implements FromCollection, WithHeadings
{
    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'QAN',
            'Owner',
            'Title',
            'Level',
            'Status',
            'Type',
            'Sector Subject Area',
            'Total Units',
            'Total Credits',
            'Regulation Start Date',
            'Operational Start Date',
            'Operational End Date',
            'Certification End Date',
            'Min GLH',
            'Max GLH',
            'GLH',
            'Total Qual. Time',
            'Overall Grading Type',
            'Assessment Methods',
            'Links to Specification',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $qualifications = Qualification::filter($this->filters)
            ->withCount(['units'])
            ->get();

        $result = collect();
        foreach($qualifications AS $qualification)
        {
            $result->push([
                $qualification->qan,
                $qualification->owner_org_acronym,
                $qualification->title,
                $qualification->level,
                $qualification->status,
                $qualification->type,
                $qualification->ssa,
                $qualification->units_count,
                $qualification->total_credits,
                $qualification->regulation_start_date,
                $qualification->operational_start_date,
                $qualification->operational_end_date,
                $qualification->certification_end_date,
                $qualification->min_glh,
                $qualification->max_glh,
                $qualification->glh,
                $qualification->total_qual_time,
                $qualification->overall_grading_type,
                $qualification->assessment_methods,
                $qualification->link_to_specs,
            ]);
        }

        return $result;
    }
}
