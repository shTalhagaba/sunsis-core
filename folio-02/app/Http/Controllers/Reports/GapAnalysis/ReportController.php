<?php

namespace App\Http\Controllers\Reports\GapAnalysis;

use App\Exports\TrainingOtjExport;
use App\Exports\ViewOtjReportExport;
use App\Filters\OtjFilters;
use App\Filters\ViewOtjReportFilters;
use App\Filters\ViewVisitTypeReportFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioPC;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(TrainingRecord $training, Portfolio $portfolio)
    {
        $this->authorize('index', TrainingRecord::class);

        $perPage = (int) session('gap_analysis_per_page', config('model_filters.default_per_page'));
        if ($perPage <= 0) {
            $perPage = 15;
        }

        $query =  PortfolioPC::query()
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->where('portfolio_units.portfolio_id', '=', $portfolio->id)
            ->where('portfolio_pcs.assessor_signoff', '!=', 1)
            ->select(
                'portfolio_pcs.*',
                'portfolio_units.*',
                'portfolio_units.title as unit_title',
                'portfolio_pcs.title as pc_title',
                'portfolio_pcs.id as pc_id'
            )
            ->orderBy('portfolio_pcs.id', 'asc');

        $records = $query->paginate($perPage);

        return view('reports.gap_analysis.index', compact('records'));
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
