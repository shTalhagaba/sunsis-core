<?php

namespace App\Exports;

use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use App\Models\Training\TrainingRecord;
use App\Models\Training\Portfolio;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\Organisations\Organisation;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PortfoliosExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'First Name(s)',
            'Surname',
            'Learner Reference',
            'Primary Email',
            //'Employer Name',
            'Programme Name',
            'Status',
            'Start Date',
            'Planned End Date',
            'Completion Date',
            'Evidences Accepted %',
            'Evidences Signed Off %',
            'Overall Progress %',
            'Primary Assessor',
            'Secondary Assessor',
            'IQA',
            'Total Units',
            'Completed Units',
            'IQA\'d Units',
            //'Last Login At',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
	    set_time_limit(0);
	    ini_set('memory_limit','2048M');

        $this->filters = (array)$this->filters;

        if(\Auth::user()->getOriginal('user_type') == UserTypeLookup::TYPE_ASSESSOR)
        {
            $this->filters['primary_assessor'] = \Auth::user()->id;
        }
        if(\Auth::user()->getOriginal('user_type') == UserTypeLookup::TYPE_VERIFIER)
        {
            $this->filters['verifier'] = \Auth::user()->id;
        }
        if(\Auth::user()->getOriginal('user_type') == UserTypeLookup::TYPE_TUTOR)
        {
            $this->filters['tutor'] = \Auth::user()->id;
        }

        $training_records = TrainingRecord::filter($this->filters)
            ->with(['student', 'primaryAssessor', 'secondaryAssessor', 'verifierUser'])
            ->whereHas('student', function ($query)  {
                if(isset($this->filters['firstnames']))
                {
                    $query->where('firstnames', 'LIKE', '%' . $this->filters['firstnames'] . '%');
                }

                if(isset($this->filters['surname']))
                {
                    $query->where('surname', 'LIKE', '%' . $this->filters['surname'] . '%');
                }

                if(isset($this->filters['employer']))
                {
                    $org = Organisation::findOrFail($this->filters['employer']);
                    $locations = $org->locations->pluck('id')->toArray();
                    $query->whereIn('employer_location', $locations);
                }

                if(isset($this->filters['gender']))
                {
                    $query->where('gender', '=', $this->filters['gender']);
                }

                if(isset($this->filters['ethnicity']))
                {
                    $query->where('ethnicity', '=', $this->filters['ethnicity']);
                }

                if(isset($this->filters['ni']))
                {
                    $query->where('ni', '=', $this->filters['ni']);
                }

                if(isset($this->filters['uln']))
                {
                    $query->where('uln', '=', $this->filters['uln']);
                }

                if(isset($this->filters['email']))
                {
                    $value = $this->filters['email'];
                    $query->where(function($query) use ($value) {
                        $query->where('email', 'LIKE', '%' . $value . '%')
                            ->orWhere('primary_email', 'LIKE', '%' . $value . '%')
                            ->orWhere('secondary_email', 'LIKE', '%' . $value . '%');
                    });
                }
            })
            ->when($this->filters['status_code'] != '', function($query) {
                $query->where('status_code', '=', $this->filters['status_code']);
            })
            ->when($this->filters['programme_id'] != '', function($query) {
                $query->where('programme_id', '=', $this->filters['programme_id']);
            })
            ->when($this->filters['from_start_date'] != '', function($query) {
                $query->where('start_date', '>=', $this->filters['from_start_date']);
            })
            ->when($this->filters['to_start_date'] != '', function($query) {
                $query->where('start_date', '<=', $this->filters['to_start_date']);
            })
            ->when($this->filters['from_actual_end_date'] != '', function($query) {
                $query->where('actual_end_date', '>=', $this->filters['from_actual_end_date']);
            })
            ->when($this->filters['to_actual_end_date'] != '', function($query) {
                $query->where('actual_end_date', '<=', $this->filters['to_actual_end_date']);
            })
            ->when($this->filters['from_planned_end_date'] != '', function($query) {
                $query->where('planned_end_date', '>=', $this->filters['from_planned_end_date']);
            })
            ->when($this->filters['to_planned_end_date'] != '', function($query) {
                $query->where('planned_end_date', '<=', $this->filters['to_planned_end_date']);
            })
            ->when($this->filters['primary_assessor'] != '', function($query) {
                $query->where('primary_assessor', '=', $this->filters['primary_assessor']);
            })
            ->when($this->filters['verifier'] != '', function($query) {
                $query->where('verifier', '=', $this->filters['verifier']);
            })
            ->when($this->filters['tutor'] != '', function($query) {
                $query->where('tutor', '=', $this->filters['tutor']);
            })
            ->orderBy($this->filters['sortBy'], $this->filters['orderBy'])
            ->get()
        ;

        // prepare arrays for eager loading
        $total_and_iqad_units_master = [];
		$sql_total_and_iqad_units = <<<SQL
SELECT tr.id AS tr_id, SUM(1) AS total_units, SUM(IF(portfolio_units.`iqa_status` = 1, 1, 0)) AS iqad_units
FROM
    portfolio_units
        INNER JOIN portfolios ON portfolio_units.`portfolio_id` = portfolios.`id`
        INNER JOIN tr ON portfolios.`tr_id` = tr.`id`
GROUP BY tr.`id`
SQL;
        $result_total_and_iqad_units = \DB::select($sql_total_and_iqad_units);
        foreach($result_total_and_iqad_units AS $row)
        {
            $total_and_iqad_units_master[$row->tr_id] = (object)[
                'total_units' => (int)$row->total_units,
                'iqad_units' => (int)$row->iqad_units,
            ];
        }

        $result = [];
        foreach($training_records AS $tr)
        {
            $temp = [];
            $temp['firstnames'] = $tr->student->firstnames;
            $temp['surname'] = $tr->student->surname;
            $temp['learner_ref'] = $tr->learner_ref;
            $temp['primary_email'] = $tr->student->primary_email;
            //$temp['employer_name'] = $tr->employer->legal_name;
            foreach($tr->portfolios AS $portfolio)
            {
                $temp['programme_name'] = $portfolio->title;
                $temp['status_code'] = $tr->status_code;
                $temp['start_date'] = $portfolio->start_date;
                $temp['planned_end_date'] = Date::PHPToExcel($portfolio->planned_end_date);
                $temp['actual_end_date'] = Date::PHPToExcel($portfolio->actual_end_date);
                $temp['evidences_accepted'] = $portfolio->getProgressPercentageBlue() . '%';
                $temp['signed_off'] = $portfolio->getProgressPercentageGreen() . '%';
		        $temp['overall_progress'] = $tr->signedOffPercentage() . '%';
                $temp['primary_assessor'] = $tr->primaryAssessor->full_name;
                $temp['secondary_assessor'] = $tr->secondaryAssessor->full_name ?? '';
                $temp['iqa'] = $tr->verifierUser->full_name;
/*
		$sql_total_units = <<<SQL
SELECT DISTINCT portfolio_units.id
FROM
     portfolio_units
         INNER JOIN portfolios ON portfolio_units.`portfolio_id` = portfolios.`id`
         INNER JOIN tr ON portfolios.`tr_id` = tr.`id`
WHERE
      tr.`id` = '{$tr->id}'
SQL;
                $total_unit_ids = \DB::select($sql_total_units);
                $total_unit_ids = count($total_unit_ids);
*/
                $sql_not_completed_units = <<<SQL
SELECT DISTINCT portfolio_pcs.`portfolio_unit_id`
FROM
    portfolio_pcs
        INNER JOIN portfolio_units ON portfolio_pcs.`portfolio_unit_id` = portfolio_units.`id`
        INNER JOIN portfolios ON portfolio_units.`portfolio_id` = portfolios.`id`
        INNER JOIN tr ON portfolios.`tr_id` = tr.`id`
WHERE
      tr.`id` = '{$tr->id}' AND portfolio_pcs.`assessor_signoff` = 0
SQL;
                $not_completed_unit_ids = \DB::select($sql_not_completed_units);
                $not_completed_unit_ids = count($not_completed_unit_ids);
/*
		$sql_iqad_units = <<<SQL
SELECT DISTINCT portfolio_units.id
FROM
     portfolio_units
         INNER JOIN portfolios ON portfolio_units.`portfolio_id` = portfolios.`id`
         INNER JOIN tr ON portfolios.`tr_id` = tr.`id`
WHERE
      tr.`id` = '{$tr->id}' AND portfolio_units.`iqa_status` = 1
SQL;
                $iqad_unit_ids = \DB::select($sql_iqad_units);
                $iqad_unit_ids = count($iqad_unit_ids);
*/
                $temp['total_units'] = isset($total_and_iqad_units_master[$tr->id]) ? $total_and_iqad_units_master[$tr->id]->total_units : 0;
                $temp['completed_units'] = $temp['total_units'] - $not_completed_unit_ids;
		        $temp['iqad_units'] = isset($total_and_iqad_units_master[$tr->id]) ? $total_and_iqad_units_master[$tr->id]->iqad_units : 0;
            }

            $result[] = $temp;
        }

        return collect($result);
    }


    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
