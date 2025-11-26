<?php

namespace App\Exports;

use App\Models\LookupManager;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class TrainingEvidencesExport implements FromCollection, WithHeadings
{
    private $filters;
    private $query;

    public function __construct($filters, $query)
    {
        $this->filters = $filters;
        $this->query = $query;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Evidence Created',
            'Evidence Name',
            'Evidence Status',
            'Evidence Categories',
            'Evidence Description',
            'Learner Comments',
            'Learner Declaration',
            'Assessment Status',
            'Assessment Comments',
            'Assessment By',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $records = $this->query->get();

        $result = collect();
        foreach($records AS $evidence)
        {
            $result->push([
                $evidence->training_record->student->full_name,
                Carbon::parse($evidence->created_at)->format('d/m/Y'),
                $evidence->evidence_name,
                $evidence->evidence_status,
                $evidence->categories->pluck('description')->implode(', '),
                $evidence->evidence_desc,
                $evidence->learner_comments,
                $evidence->learner_declaration == 1 ? 'Yes' : 'No',
                optional($evidence->latestAssessment)->statusDescription(),
                optional($evidence->latestAssessment)->assessment_comments,
                LookupManager::nameOfUser(optional($evidence->latestAssessment)->created_by),
            ]);
        }

        return $result;
    }
}
