<?php

namespace App\Exports;

use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Training\TrainingRecord;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class ViewOtjReportExport implements FromCollection, WithHeadings
{
    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Programme',
            'Employer',
            'Training Status',
            'Start Date',
            'Planned End Date',
            'Actual End Date',
            'Primary Assessor',
            'Secondary Assessor',
            'Verifier',
            'OTJ Hours Due',
            'OTJ Hours Actual',
            'OTJ Progress',
            'Last OTJ Activity Date',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = TrainingRecord::filter($this->filters)
            ->caseloadCondition(auth()->user())
            ->join('users AS students', 'students.id','=','tr.student_id')
            ->with(['student', 'programme', 'employer', 'primaryAssessor', 'secondaryAssessor', 'verifierUser'])
            ->select(
                'tr.student_id', 
                'tr.programme_id', 
                'tr.employer_location', 
                'tr.start_date', 
                'tr.planned_end_date', 
                'tr.actual_end_date', 
                'tr.status_code', 
                'tr.primary_assessor', 
                'tr.secondary_assessor', 
                'tr.verifier', 
                // 'tr.otj_hours as otj_hours_due', 
                'students.firstnames',
            )
            ->addSelect(DB::raw('(SELECT ROUND((SUM(HOUR(duration))*60 + SUM(MINUTE(duration)))/60) FROM otj WHERE tr_id = tr.id AND status = "Accepted" AND is_otj = 1) AS otj_hours_actual'))
            ->addSelect(DB::raw('(SELECT date FROM otj WHERE tr_id = tr.id AND status = "Accepted" ORDER BY date DESC LIMIT 1) AS latest_otj_activity_date'))
            ->addSelect(DB::raw('
IF
(
tr.otj_hours = 0, "", 
IF
(
    (SELECT (SUM(HOUR(duration))*60 + SUM(MINUTE(duration)))/60 FROM otj WHERE tr_id = tr.id AND status = "Accepted" AND is_otj = 1) >= 
    ( tr.otj_hours/(TIMESTAMPDIFF(MONTH, tr.start_date, tr.planned_end_date)) * TIMESTAMPDIFF(MONTH, tr.start_date, CURDATE()))
    ,
    "On Track", "Behind"
)
) AS otj_progress            
            '))
            ->addSelect(DB::raw('
IF (
    CURDATE() < tr.start_date,
    0,
    (
      IF(
        CURDATE() > tr.planned_end_date,
        tr.otj_hours,
        ROUND (tr.otj_hours / DATEDIFF(tr.planned_end_date, tr.start_date) * DATEDIFF(CURDATE(), tr.start_date))
      )
    )
  ) AS otj_hours_due
            '));

        $records = $query->get();

        $result = collect();
        foreach($records AS $training)
        {
            $result->push([
                $training->student->full_name,
                $training->programme->title,
                optional($training->employer)->legal_name,
                TrainingStatusLookup::getDescription($training->status_code),
                $training->start_date->format('d/m/Y'),
                $training->planned_end_date->format('d/m/Y'),
                optional($training->actual_end_date)->format('d/m/Y'),
                $training->primaryAssessor->full_name,
                optional($training->secondaryAssessor)->full_name,
                optional($training->verifierUser)->full_name,
                $training->otj_hours_due,
                $training->otj_hours_actual,
                (int)$training->otj_hours_due > 0 ? $training->otj_progress : '',
                $training->latest_otj_activity_date
            ]);
        }

        return $result;
    }
}
