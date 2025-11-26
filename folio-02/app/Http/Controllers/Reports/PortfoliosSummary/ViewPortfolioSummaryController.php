<?php

namespace App\Http\Controllers\Reports\PortfoliosSummary;

use App\Exports\ViewPortfolioSummaryExport;
use App\Filters\ViewPortfolioSummaryFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioUnitIqa;
use App\Models\Training\TrainingRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ViewPortfolioSummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
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

            case UserTypeLookup::TYPE_MANAGER:
                $assessorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', auth()->user()->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_ASSESSOR)
                    ->pluck('caseload_account_id')
                    ->toArray();
                $tutorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', auth()->user()->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_TUTOR)
                    ->pluck('caseload_account_id')
                    ->toArray();
                $verifierIds = DB::table('user_caseload_accounts')
                    ->where('user_id', auth()->user()->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_VERIFIER)
                    ->pluck('caseload_account_id')
                    ->toArray();

                $query->where(function ($q1) use ($assessorIds, $tutorIds, $verifierIds) {
                    $q1
                        ->whereIn('tr.tutor', $tutorIds)
                        ->orWhereIn('tr.verifier', $verifierIds)
                        ->orWhere(function ($q2) use ($assessorIds) {
                            $q2->whereIn('tr.primary_assessor', $assessorIds)
                                ->orWhereIn('tr.secondary_assessor', $assessorIds);
                        });
                });
                break;

            default:
                $query->where('tr.employer_location', auth()->user()->employer_location);
                break;
        }
    }

    public function index(Request $request, ViewPortfolioSummaryFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        $statusAccepted = PortfolioUnitIqa::STATUS_IQA_ACCEPTED;
        $statusReferred = PortfolioUnitIqa::STATUS_IQA_REFERRED;

        $query = Portfolio::filter($filters)
            ->with(['units'])
            ->join('tr', 'tr.id', '=', 'portfolios.tr_id')
            ->join('users AS students', 'students.id', '=', 'tr.student_id')
            ->join('programmes', 'programmes.id', '=', 'tr.programme_id')
            ->join('qualifications', 'qualifications.qan', '=', 'portfolios.qan')
            ->select(
                'tr.student_id',
                'tr.programme_id',
                'programmes.title as programme_title',
                'qualifications.owner_org_rn as owner_org_rn',
                'portfolios.*',
                DB::raw('CONCAT(students.firstnames, " ", students.surname) as full_name')
            )
            ->addSelect(DB::raw('
                (SELECT CONCAT(primary_assessors.firstnames, " ", primary_assessors.surname)
                FROM users AS primary_assessors
                WHERE primary_assessors.id = tr.primary_assessor) as primary_assessor_name
            '))
            ->addSelect(DB::raw('
                (SELECT CONCAT(secondary_assessors.firstnames, " ", secondary_assessors.surname)
                FROM users AS secondary_assessors
                WHERE secondary_assessors.id = tr.secondary_assessor) as secondary_assessor_name
            '))
            ->addSelect(DB::raw('
                (SELECT CONCAT(verifiers.firstnames, " ", verifiers.surname)
                FROM users AS verifiers
                WHERE verifiers.id = tr.verifier) as verifier_name
            '))
            ->addSelect(DB::raw('
                (SELECT COUNT(*)
                FROM portfolio_pcs
                INNER JOIN portfolio_units ON portfolio_pcs.portfolio_unit_id = portfolio_units.id
                WHERE portfolio_units.portfolio_id = portfolios.id) as total_pcs
            '))
            ->addSelect(DB::raw('
                (SELECT COUNT(*)
                FROM portfolio_pcs
                INNER JOIN portfolio_units ON portfolio_pcs.portfolio_unit_id = portfolio_units.id
                WHERE portfolio_units.portfolio_id = portfolios.id AND portfolio_pcs.assessor_signoff = 1) as signed_off_pcs
            '))
            ->addSelect(DB::raw("
                (SELECT COUNT(*)
                FROM portfolio_units
                WHERE portfolio_units.portfolio_id = portfolios.id AND portfolio_units.iqa_status = {$statusAccepted}) as iqa_passed_units
            "))
            ->addSelect(DB::raw("
                (SELECT COUNT(*)
                FROM portfolio_units
                WHERE portfolio_units.portfolio_id = portfolios.id AND portfolio_units.iqa_status = {$statusReferred}) as iqa_referred_units
            "))
            ->addSelect(DB::raw("
                (SELECT pui.comments
                FROM portfolio_units_iqa pui
                INNER JOIN portfolio_units pu ON pu.id = pui.portfolio_unit_id
                WHERE pu.portfolio_id = portfolios.id
                LIMIT 1
                ) as iqa_comment
            "))
            ->addSelect(DB::raw("
                (SELECT pui.iqa_type
                FROM portfolio_units_iqa pui
                INNER JOIN portfolio_units pu ON pu.id = pui.portfolio_unit_id
                WHERE pu.portfolio_id = portfolios.id
                LIMIT 1
                ) as iqa_type
            "))
            ->addSelect(DB::raw("
                (SELECT pui.created_at
                FROM portfolio_units_iqa pui
                INNER JOIN portfolio_units pu ON pu.id = pui.portfolio_unit_id
                WHERE pu.portfolio_id = portfolios.id
                LIMIT 1
                ) as sample_date
            "));

        if (auth()->user()->user_type !== UserTypeLookup::TYPE_QUALITY_MANAGER) {
            $this->addCaseloadCondition($query);
        }

        $portfolios = $query->paginate(session('trs_per_page', config('model_filters.default_per_page')));

        return view('reports.portfolios.index', compact('portfolios', 'filters'));
    }

    public function export(ViewPortfolioSummaryFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        $type = request()->routeIs('reports.sampling*') ? 'sampling' : 'portfolios';

        return Excel::download(
            new ViewPortfolioSummaryExport($filters, $type),
            ucfirst($type) . '.xlsx'
        );
    }
}
