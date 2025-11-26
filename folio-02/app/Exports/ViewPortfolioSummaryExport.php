<?php

namespace App\Exports;

use App\Models\Lookups\TrainingOutcomeLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioUnitIqa;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ViewPortfolioSummaryExport implements FromCollection, WithHeadings
{
    private $filters;
    private $type;

    public function __construct($filters, $type = null)
    {
        $this->filters = $filters;
        $this->type = $type;
    }

    public function headings(): array
    {
        $portfolioHeadings = [
            'Student Name',
            'Primary Assessor',
            'Secondary Assessor',
            'Verifier',
            'Programme',
            'QAN',
            'Title',
            'Status',
            'Outcome',
            'Start Date',
            'Planned End Date',
            'Actual End Date',
            'Total Units',
            'Total PCs',
            'Signed off PCs',
            'Progress',
            'Units IQA Passed',
            'Units IQA Referred',
            'Awarding Body Registration Number',
            'Awarding Body Registration Date',
            'Certificate Applied Date',
            'Certificate Received Date',
            'Certificate Sent to Learner Date',
        ];

        if ($this->type === 'sampling') {
            // find the position of "Outcome"
            $pos = array_search('Outcome', $portfolioHeadings);

            // insert 3 new headings *after* Outcome
            array_splice($portfolioHeadings, $pos + 1, 0, [
                'Sampling Date',
                'Sampling Feedback',
                'Sampling Type',
            ]);
        }

        if ($this->type === 'portfolios') {
            // find the position of "Outcome"
            $pos = array_search('QAN', $portfolioHeadings);

            // insert 3 new headings *after* Outcome
            array_splice($portfolioHeadings, $pos + 1, 0, [
                'Awarding Body',
            ]);
        }

        return $portfolioHeadings;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $statusAccepted = PortfolioUnitIqa::STATUS_IQA_ACCEPTED;
        $statusReferred = PortfolioUnitIqa::STATUS_IQA_REFERRED;

        $query = Portfolio::filter($this->filters)
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


        $this->addCaseloadCondition($query);


        $portfolios = $query->get();

        $result = collect();
        foreach ($portfolios as $portfolio) {
            $row = [
                $portfolio->full_name,
                $portfolio->primary_assessor_name,
                $portfolio->secondary_assessor_name,
                $portfolio->verifier_name,
                $portfolio->programme_title,
                $portfolio->qan,
            ];

            if ($this->type === 'portfolios') {
                $row[] = \App\Models\LookupManager::getQualificationOwnersAcronym($portfolio->owner_org_rn);
            }

            $row[] = $portfolio->title;
            $row[] = $portfolio->status_code;
            $row[] = $portfolio->learning_outcome != ''
                ? TrainingOutcomeLookup::getDescription($portfolio->learning_outcome)
                : '';

            // insert extra sampling columns here (after Outcome)
            if ($this->type === 'sampling') {
                $row[] = $portfolio->sample_date ?? '';
                $row[] = $portfolio->iqa_comment ?? '';
                $row[] = $portfolio->iqa_type ?? '';
            }

            // continue rest of portfolio columns
            $row = array_merge($row, [
                $portfolio->start_date,
                $portfolio->planned_end_date,
                $portfolio->actual_end_date,
                $portfolio->units->count(),
                $portfolio->total_pcs ?: '0',
                $portfolio->signed_off_pcs ?: '0',
                $portfolio->total_pcs > 0
                    ? round(($portfolio->signed_off_pcs / $portfolio->total_pcs) * 100) . '%'
                    : '0%',
                $portfolio->units_iqa_passed ?: '0',
                $portfolio->units_iqa_referred ?: '0',
                $portfolio->ab_registration_number,
                $portfolio->ab_registration_date,
                $portfolio->cert_applied,
                $portfolio->cert_received,
                $portfolio->cert_sent_to_learner,
            ]);

            $result->push($row);
        }

        return $result;
    }

    private function addCaseloadCondition(Builder &$query)
    {
        switch (auth()->user()->user_type) {
            case UserTypeLookup::TYPE_ADMIN:
                break;
            case UserTypeLookup::TYPE_QUALITY_MANAGER:
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
