<?php

namespace App\Http\Controllers\Reports\VisitTypeReport;

use App\Exports\TrainingOtjExport;
use App\Exports\ViewOtjReportExport;
use App\Filters\OtjFilters;
use App\Filters\ViewOtjReportFilters;
use App\Filters\ViewVisitTypeReportFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VisitTypeReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, ViewVisitTypeReportFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        $role = $request->query('role');
        $type = $request->query('type');
        $query = TrainingDeliveryPlanSession::filter($filters)
            ->select('tr_dp_sessions.*')
            ->join('tr', 'tr.id', '=', 'tr_dp_sessions.tr_id')
            ->join('users AS students', 'students.id', '=', 'tr.student_id')
            ->with(['training', 'assessor']);

        // ✅ Filter by role (tutor / coach)
        if ($role) {
            $query->where('tr_dp_sessions.assessor_type', $role);
        }

        // ✅ Filter Visit Type
        if ($type === 'face_to_face') {
            $query->where('tr_dp_sessions.session_type', 'face_to_face');
        } elseif ($type === 'remote') {
            $query->where('tr_dp_sessions.session_type', 'remote');
        }
        // If type = all OR no type => no extra filter ✅

        $records = $query->paginate(
            session('visit_type_per_page', config('model_filters.default_per_page'))
        );
        return view('reports.session_visit_type.index', [
            'records' => $records,
            'filters' => $filters,
            'role'    => $role,
            'type'    => $type,
        ]);
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