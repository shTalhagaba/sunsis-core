<?php

namespace App\Exports;

use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Programmes\Programme;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProgrammesExport implements FromCollection, WithHeadings
{
    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'Title',
            'Duration (months)',
            'EPA Duration (months)',
            'Programme Type',
            'Reference Number',
            'LARS Standard Code',
            'Off-the-job Hours',
            'First Review After (weeks)',
            'Subsequent Reviews After (weeks)',
            'Status',
            'comments',
            'Total Qualifications',
            'Total Trainings',
            'Continuing Trainings',
            'Completed Trainings',
            'Withdrawn Trainings',
            'BIL Trainings',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $programmes = Programme::filter($this->filters)
            ->with(['programmeType'])
            ->withCount([
                'qualifications',
                'training_records',
                'training_records as continuing_trainings_count' => function ($query) {
                    $query->where('status_code', TrainingStatusLookup::STATUS_CONTINUING);
                },
                'training_records as completed_trainings_count' => function ($query) {
                    $query->where('status_code', TrainingStatusLookup::STATUS_COMPLETED);
                },
                'training_records as withdrawn_trainings_count' => function ($query) {
                    $query->where('status_code', TrainingStatusLookup::STATUS_WITHDRAWN);
                },
                'training_records as bil_trainings_count' => function ($query) {
                    $query->where('status_code', TrainingStatusLookup::STATUS_BIL);
                },
            ])
            ->get();

        $result = collect();
        foreach($programmes AS $programme)
        {
            $result->push([
                $programme->title,
                $programme->duration,
                $programme->epa_duration,
                optional($programme->programmeType)->description,
                $programme->reference_number,
                $programme->lars_standard_code,
                $programme->otj_hours,
                $programme->first_review,
                $programme->review_frequency,
                $programme->status ? 'Active' : 'Not Active',
                $programme->comments,
                $programme->qualifications_count,
                $programme->training_records_count,
                $programme->continuing_trainings_count,
                $programme->completed_trainings_count,
                $programme->withdrawn_trainings_count,
                $programme->bil_trainings_count,
            ]);
        }

        return $result;
    }
}
