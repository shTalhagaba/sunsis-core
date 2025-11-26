<?php

namespace App\Exports;

use App\Models\IQA\IqaSamplePlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IqaSamplePlansExport implements FromCollection, WithHeadings
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
            'IQA Personnel',
            'Programme',
            'Type',
            'Status',
            'Complete By',
            'Number of Units',
            'Unit References',
            'Number of Learners',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = IqaSamplePlan::filter($this->filters)
            ->with(['programme', 'verifier', 'units'])
            ->withCount(['units', 'trainings']);

        if( auth()->user()->isVerifier() )
        {
            $query->where('iqa_sample_plans.verifier_id', '=', auth()->user()->id);
        }

        $plans = $query->get();

        $result = collect();
        foreach($plans AS $plan)
        {
            $result->push([
                $plan->title,
                $plan->verifier->full_name,
                $plan->programme->title,
                ucwords($plan->type),
                $plan->status,
                optional($plan->completed_by_date)->format('d/m/Y'),
                $plan->units_count,
                $plan->units->pluck('unique_ref_number')->implode(', '),
                $plan->trainings_count,
            ]);
        }

        return $result;
    }
}
