<?php

namespace App\Exports;

use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TrainingRecordsExport implements FromCollection, WithHeadings
{
    private $filters;
    private $checkOverDue;

    public function __construct($filters, $checkOverDue)
    {
        $this->filters = $filters;
        $this->checkOverDue = $checkOverDue;
    }

    public function headings(): array
    {
        return [
            'First Name(s)',
            'Surname',
            'Primary Email',
            'Learner Reference',
            'ULN',
            'Programme',
            'Status',
            'Start Date',
            'Planned End Date',
            'Completion Date',
            'EPA Date',
            'Primary Assessor',
            'Secondary Assessor',
            'Verifier',
            'Signed Off Percentage',
            'Employer',
            'Employer Location',
            'Employer Location Postcode',
            'Contracted Hours per Week',
            'Weeks to be worked per Year',
            'Planned OTJ Hours',
            'Completed OTJ Hours',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = TrainingRecord::filter($this->filters)
            ->when(filter_var($this->checkOverDue, FILTER_VALIDATE_BOOLEAN), function ($q) {
                $q->whereHas('reviews', function ($sub) {
                    $sub->overdueReview();
                });
            })
            ->with([
                'programme',
                'portfolios',
                'portfolios.units',
                'portfolios.units.pcs',
                'primaryAssessor',
                'secondaryAssessor',
                'verifierUser',
                'employer',
                'location',
            ]);
        $query = $query->join('users AS students', 'students.id', '=', 'tr.student_id');
        $query = $query->select('tr.*')
            ->addSelect(DB::raw('(SELECT ROUND(SUM(TIME_TO_SEC(duration)/3600)) FROM otj WHERE otj.tr_id = tr.id AND otj.status = "Accepted") as completed_otj_hours'));

        $this->addCaseloadCondition($query);

        $result = collect();
        foreach ($query->cursor() as $training) {
            $result->push([
                'firstnames' => $training->student->firstnames,
                'surname' => $training->student->surname,
                'primary_email' => $training->student->primary_email,
                'learner_ref' => $training->learner_ref,
                'uln' => $training->student->uln,
                'programme' => $training->programme->title,
                'status' => TrainingStatusLookup::getDescription($training->status_code),
                'start_date' => $training->start_date->format('d/m/Y'),
                'planned_end_date' => $training->planned_end_date->format('d/m/Y'),
                'completion_date' => optional($training->actual_end_date)->format('d/m/Y'),
                'epa_date' => optional($training->epa_date)->format('d/m/Y'),
                'primary_assessor' => $training->primaryAssessor->full_name,
                'secondary_assessor' => optional($training->secondaryAssessor)->full_name ?? '',
                'verifier' => optional($training->verifierUser)->full_name,
                'signoff_percentage' => $training->signedOffPercentage() . '%',
                'employer' => optional($training->employer)->legal_name,
                'employer_location' => optional($training->location)->address_line_1,
                'employer_location_postcode' => optional($training->location)->postcode,
                'contracted_hours_per_week' => $training->contracted_hours_per_week,
                'weeks_to_worked_per_year' => $training->weeks_to_worked_per_year,
                'planned_otj' => $training->otj_hours,
                'completed_otj' => $training->completed_otj_hours,
            ]);
        }

        return $result;
    }

    private function addCaseloadCondition(Builder &$query)
    {
        switch (auth()->user()->user_type) {
            case UserTypeLookup::TYPE_ADMIN:
                break;

            case UserTypeLookup::TYPE_ASSESSOR:
                $query->where(function ($q) {
                    $q->where('tr.primary_assessor', '=', auth()->user()->id)
                        ->orWhere('tr.secondary_assessor', '=', auth()->user()->id);
                });
                break;

            case UserTypeLookup::TYPE_TUTOR:
                $query->where('tr.tutor', '=', auth()->user()->id);
                break;

            case UserTypeLookup::TYPE_VERIFIER:
                $query->where('tr.verifier', '=', auth()->user()->id);
                break;

            case UserTypeLookup::TYPE_STUDENT:
                $query->where('tr.student_id', '=', auth()->user()->id);
                break;

            case UserTypeLookup::TYPE_EMPLOYER_USER:
                $assessorIds = DB::table('employer_user_assessor')->where('employer_user_id', auth()->user()->id)->pluck('assessor_id')->toArray();
                $query->where('tr.employer_location', auth()->user()->employer_location)
                    ->where(function ($q) use ($assessorIds) {
                        $q->whereIn('tr.primary_assessor', $assessorIds)
                            ->orWhereIn('tr.secondary_assessor', $assessorIds);
                    });
                break;

            default:
                $query->where('tr.employer_location', auth()->user()->employer_location);
                break;
        }
    }
}
