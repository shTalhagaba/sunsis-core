<?php

namespace App\Helpers;

use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportHelper
{
    public static function continuingStudentsDueToFinish(User $user, $months = 3)
	{
        $query = TrainingRecord::orderBy('planned_end_date')
            ->caseloadCondition(auth()->user())
            ->where('status_code', TrainingStatusLookup::STATUS_CONTINUING)
            ->whereBetween('planned_end_date', [now()->startOfMonth()->format('Y-m-d'), now()->addMonths($months)->endOfMonth()->format('Y-m-d')])
            ->select('tr.id', 'tr.planned_end_date');
        
        $result = $query->get();

        $data = [];
        foreach($result AS $row)
        {
            $plannedEndDate = Carbon::parse($row['planned_end_date']);
            $monthName = $plannedEndDate->format('F Y');
            
            if(!isset($data[$monthName]))
            {
                $data[$monthName] = [
                    'month' => $plannedEndDate->format('F Y'),
                    'count' => 0,
                    'month_start_date' => $plannedEndDate->startOfMonth()->format('Y-m-d'),
                    'month_end_date' => $plannedEndDate->endOfMonth()->format('Y-m-d'), 
                    'overstayer' => $plannedEndDate->isBefore(now()->startOfMonth()) ? true : false,
                ];
            }
            
            $data[$monthName]['count'] += 1;
        }

        return $data;
    }

    public static function continuingLearnersWithEvidencesToAssess(User $user)
	{
        $query = TrainingRecord::query()
            ->caseloadCondition(auth()->user())
            ->where('tr.status_code', TrainingStatusLookup::STATUS_CONTINUING)
            ->withCount([
                'evidences as evidence_count' => function ($query) {
                    $query->where('evidence_status', TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED);
                }
            ])
            ->whereHas('evidences', function ($query) {
                $query->where('evidence_status', TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED);
            }, '>', 0);

        return $query;
    }

    public static function continuingLearnersNotLoggedIn(User $user, $daysNotLoggedIn = 30)
	{
        $query = TrainingRecord::query()
            ->caseloadCondition(auth()->user())
            ->leftJoin(
                DB::raw('(SELECT m1.* FROM authentication_log m1 LEFT JOIN authentication_log m2 ON (m1.authenticatable_id = m2.authenticatable_id AND m1.id < m2.id) WHERE m2.id IS NULL) AS t2'),
                function($join) {
                    $join->on('tr.student_id', '=', 't2.authenticatable_id');
            })
            ->where('tr.status_code', TrainingStatusLookup::STATUS_CONTINUING)
            ->where('t2.login_at', '<', now()->addDays(-$daysNotLoggedIn));

        return $query;
    }

    public static function getTrainingRecordStatusByYear(User $user, $year = '')
    {
        $year = $year != '' ? $year : now()->year;
        

        $startOfYear = Carbon::create($year, 8, 1, 0, 0, 0);
        $endOfYear = Carbon::create($year+1, 7, 31, 23, 59, 59);

        $query = TrainingRecord::query()
            ->whereBetween('start_date', [$startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d')])
            ->select(
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_CONTINUING.', 1, 0)) AS Continuing'),
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_COMPLETED.', 1, 0)) AS Completed'),
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_WITHDRAWN.', 1, 0)) AS Withdrawn'),
                // DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_TEMP_WITHDRAWN.', 1, 0)) AS "Temporarily Withdrawn (Not BIL)"'),
                // DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_DEACTIVATED.', 1, 0)) AS Deactivated'),
                // DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_ASSESSMENT_COMPLETE.', 1, 0)) AS "Assessment Complete"'),
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_BIL.', 1, 0)) AS "Break in Learning"'),
            );

        $query = DB::table('tr')
            ->whereBetween('start_date', [$startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d')])
            ->select(
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_CONTINUING.', 1, 0)) AS Continuing'),
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_COMPLETED.', 1, 0)) AS Completed'),
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_WITHDRAWN.', 1, 0)) AS Withdrawn'),
                // DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_TEMP_WITHDRAWN.', 1, 0)) AS "Temporarily Withdrawn (Not BIL)"'),
                // DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_DEACTIVATED.', 1, 0)) AS Deactivated'),
                // DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_ASSESSMENT_COMPLETE.', 1, 0)) AS "Assessment Complete"'),
                DB::raw('SUM(IF(tr.`status_code` = '.TrainingStatusLookup::STATUS_BIL.', 1, 0)) AS "Break in Learning"'),
            );

            AppHelper::addCaseloadConditionDatabase($query, $user);

        return $query;
    }

    public static function newStartsInLast6Months()
    {
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) 
        {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = Carbon::now()->subMonths($i)->endOfMonth();

            $count = TrainingRecord::query()
                ->caseloadCondition(auth()->user())
                ->whereBetween('start_date', [$start, $end])->count();
            
            $data[] = [
                'month' => $start->format('F Y'),
                'count' => $count,
                'month_start_date' => $start->format('Y-m-d'),
                'month_end_date' => $end->format('Y-m-d'),
            ];
        }

        return $data;
    }
}
