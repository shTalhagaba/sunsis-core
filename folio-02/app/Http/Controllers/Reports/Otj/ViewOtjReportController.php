<?php

namespace App\Http\Controllers\Reports\Otj;

use App\Exports\TrainingOtjExport;
use App\Exports\ViewOtjReportExport;
use App\Filters\OtjFilters;
use App\Filters\ViewOtjReportFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training\TrainingRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ViewOtjReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, ViewOtjReportFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        $query = TrainingRecord::filter($filters)
            ->caseloadCondition(auth()->user())
            ->join('users AS students', 'students.id', '=', 'tr.student_id')
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
            ->addSelect(DB::raw('(SELECT DATE_FORMAT(date, "%d/%m/%Y") FROM otj WHERE tr_id = tr.id AND status = "Accepted" ORDER BY date DESC LIMIT 1) AS latest_otj_activity_date'))
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

        $records = $query->paginate(session('otj_per_page', config('model_filters.default_per_page')));

        return view('reports.otj.index', compact('records', 'filters'));
    }

    public function export(ViewOtjReportFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        return Excel::download(new ViewOtjReportExport($filters), 'OTJ Report.xlsx');
    }

    public function exportOtjh(TrainingRecord $training, OtjFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        return Excel::download(new TrainingOtjExport($filters, $training), 'OTJH Report.xlsx');
    }
}
