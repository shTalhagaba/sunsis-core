<?php

namespace App\Http\Controllers;

use App\Helpers\ReportHelper;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Student;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\Training\TrainingRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function getTrainingRecordStatusByYear(Request $request)
    {
        $year = $request->year != '' ? $request->year : now()->year;

        $learners_by_status = ReportHelper::getTrainingRecordStatusByYear(auth()->user(), $year);
        $learners_by_status = $learners_by_status->get()->toArray();

        $startOfYear = Carbon::create($year, 8, 1, 0, 0, 0);
        $endOfYear = Carbon::create($year+1, 7, 31, 23, 59, 59);

        foreach($learners_by_status[0] AS $record_status => $record_status_count)
        {
            $box = 'blue';
            $status_code = TrainingStatusLookup::STATUS_CONTINUING;
            if($record_status == 'Completed')
            {
                $box = 'green';
                $status_code = TrainingStatusLookup::STATUS_COMPLETED;
            }
            elseif($record_status == 'Withdrawn')
            {
                $box = 'red';
                $status_code = TrainingStatusLookup::STATUS_WITHDRAWN;
            }
            // elseif($record_status == 'Temporarily Withdrawn (Not BIL)')
            // {
            //     $box = 'orange';
            //     $record_status = 'Temp. Withdrawn (Not BIL)';
            //     $status_code = TrainingStatusLookup::STATUS_TEMP_WITHDRAWN;
            // }
            // elseif($record_status == 'Deactivated')
            // {
            //     $box = 'grey';
            //     $status_code = TrainingStatusLookup::STATUS_DEACTIVATED;
            // }
            // elseif($record_status == 'Assessment Complete')
            // {
            //     $box = 'purple';
            //     $status_code = TrainingStatusLookup::STATUS_ASSESSMENT_COMPLETE;
            // }
            elseif($record_status == 'Break in Learning')
            {
                $box = 'orange2';
                $status_code = TrainingStatusLookup::STATUS_BIL;
            }
            echo '<div class="infobox infobox-' . $box . '">';
            echo '<div class="infobox-icon"><i class="ace-icon fa fa-users"></i></div>';
            echo '<div class="infobox-data">';
            $url = route('trainings.index');
            $url .= '?_reset=2&user_type=5&inc_deactivated=Yes&status_code='.$status_code.'&from_start_date=' . $startOfYear->format('Y-m-d') . '&to_start_date=' . $endOfYear->format('Y-m-d');
            $url .= '&sortBy=created_at&orderBy=ASC&perPage=20';
            echo '<span class="infobox-data-number" style="cursor: pointer;" onclick="window.location.href=\''.$url.'\'">' . ($record_status_count == '' ? 0 : $record_status_count)  . ' ' . \Str::plural('Learner', $record_status_count) . '</span>';
            echo '<div class="infobox-content small">' . $record_status . '</div>';
            echo '</div>';
            echo '</div>';
        }
    }

    private function getMainQuery()
    {
        $mainQuery = TrainingRecord::with([
            'student', 
            'programme', 
            'employer',
            'primaryAssessor',
            'secondaryAssessor',
            'verifierUser',
        ])
        ->caseloadCondition(auth()->user())
        ->join('users AS students', 'students.id','=','tr.student_id')
        ->orderBy('students.firstnames');

        return $mainQuery;
    }

    public function index(Request $request) 
    {
        $start = $request->start ?? now()->startOfMonth()->format('Y-m-d');
        $end = $request->end ?? now()->endOfMonth()->format('Y-m-d');

        $mainQuery = $this->getMainQuery();

        $starters = (clone $mainQuery)->where('status_code', TrainingStatusLookup::STATUS_CONTINUING)
            ->whereBetween('start_date', [$start, $end])
            ->get();

        $currentlyActive = (clone $mainQuery)->where('status_code', TrainingStatusLookup::STATUS_CONTINUING)
            ->where(function($query) use ($start, $end) {
                return $query->where(function($q) use ($start, $end) {
                    return $q->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('planned_end_date', [$start, $end]);    
                })
                ->orWhere(function($query) use ($start, $end) {
                    return $query->where('start_date', '<', $start)
                        ->where(function($q) use ($start, $end) {
                            return $q->whereBetween('planned_end_date', [$start, $end])
                                ->orWhere('planned_end_date', '>', $end);
                        });
                });
            })
            ->get();

        $plannedToFinishAndActive = (clone $mainQuery)->where('status_code', TrainingStatusLookup::STATUS_CONTINUING)
            ->whereBetween('planned_end_date', [$start, $end])
            ->get();

        $plannedToFinishAndCompleted = (clone $mainQuery)->where('status_code', TrainingStatusLookup::STATUS_COMPLETED)
            ->whereBetween('planned_end_date', [$start, $end])
            ->get();

        $withdrawn = (clone $mainQuery)->where('status_code', TrainingStatusLookup::STATUS_WITHDRAWN)
            ->whereBetween('actual_end_date', [$start, $end])
            ->get();

        $breakInLearning = (clone $mainQuery)->where('status_code', TrainingStatusLookup::STATUS_BIL)
            ->whereBetween('actual_end_date', [$start, $end])
            ->get();

        $achievers = (clone $mainQuery)->where('status_code', TrainingStatusLookup::STATUS_COMPLETED)
            ->whereBetween('actual_end_date', [$start, $end])
            ->get();

        $charts = [];

        for ($i = 1; $i <= 10; $i++) {
            $charts[] = [
                'id' => 'chart-container-' . $i,
                'title' => 'Chart ' . $i,
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100)]
            ];
        }

        return view('reports.dashboard', compact(
            'start', 
            'end', 
            'charts', 
            'starters', 
            'currentlyActive', 
            'plannedToFinishAndActive',
            'plannedToFinishAndCompleted',
            'withdrawn',
            'breakInLearning',
            'achievers'
        ));
    }

    public function getAssessorActions()
    {
        if(! auth()->user()->isAssessor())
            return;

        $assessor = auth()->user();

        //get all the DP tasks which are signed(completed) by the learners.
        $tasks = TrainingDeliveryPlanSessionTask::whereHas('trainingRecord', function($query) use ($assessor) {
            return $query->where('tr.primary_assessor', '=', $assessor->id)
                ->orWhere('tr.secondary_assessor', '=', $assessor->id);
        })
        ->submitted()
        ->whereNull('tr_tasks.assessor_signed_datetime')
        ->orderBy('tr_tasks.assessor_signed_datetime')
        ->get();
        
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Learner</th><th>Task</th><th>Learner Signed</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        if($tasks->count() === 0)
        {
            echo '<tr><td colspan="4"></td></tr>';
        }
        else
        {
            foreach($tasks AS $task)        
            {
                $url = route('trainings.sessions.tasks.show', [$task->tr_id, $task->dp_session_id, $task->id]);
                echo '<tr>';
                echo '<td>' . $task->trainingRecord->student->full_name . '</td>';
                echo '<td>' . $task->title . '</td>';
                echo '<td>' . optional($task->learner_signed_datetime)->format('d/m/Y H:i:s') . '</td>';
                echo '<td>';
                echo '<button class="btn btn-xs btn-white btn-info btn-round" type="button" onclick="window.location.href=\'' . $url .'\'"><i class="ace-icon fa fa-folder-open"></i> View</button>';
                echo '</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
    }

    public function getVerifierActions()
    {
        if(! auth()->user()->isVerifier())
            return;

        $verifier = auth()->user();

        $whereDateStart = now()->format('Y-m-d');
        $whereDateEnd = now()->addDays(30)->format('Y-m-d');

        $entries = DB::table('iqa_plan_entries')
            ->join('iqa_sample_plans', 'iqa_plan_entries.iqa_plan_id', '=', 'iqa_sample_plans.id')
            ->join('tr', 'iqa_plan_entries.training_id', '=', 'tr.id')
            ->join('users as students', 'tr.student_id', '=', 'students.id')
            ->where('iqa_sample_plans.verifier_id', $verifier->id)
            ->where('iqa_status', 'PLANNED')
            ->where(function($query) use ($whereDateStart, $whereDateEnd) {
                $query->whereBetween('iqa_plan_entries.reminder_date', [$whereDateStart, $whereDateEnd])
                    ->orWhereBetween('iqa_plan_entries.planned_completion_date', [$whereDateStart, $whereDateEnd]);
            })
            ->select([
                'learning_aim_qan', 'learning_aim_title', 'unit_unique_ref_number', 'unit_owner_ref', 'planned_completion_date',
                'students.firstnames', 'students.surname', 'iqa_sample_plans.created_by', 'iqa_sample_plans.id AS plan_id', 'tr.id AS training_id',
                'portfolio_unit_id', 'iqa_plan_entries.id AS iqa_plan_entry_id', 'iqa_plan_entries.planned_completion_date',
            ])
            ->get();
        
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Planned Date</th><th>Learner</th><th>IQA Unit</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        if($entries->count() === 0)
        {
            echo '<tr><td colspan="4"></td></tr>';
        }
        else
        {
            foreach($entries AS $entry)
            {
                $url = route('trainings.unit.iqa.show', [
                    'training' => $entry->training_id, 
                    'unit' => $entry->portfolio_unit_id, 
                    'iqa_sample_id' => $entry->plan_id, 
                    'iqa_entry_id' => $entry->iqa_plan_entry_id
                ]);
                echo '<tr>';
                echo '<td>' . Carbon::parse($entry->planned_completion_date)->format('d/m/Y') . '</td>';
                echo '<td>' . $entry->firstnames . ' ' . $entry->surname . '</td>';
                echo '<td>' . $entry->unit_unique_ref_number . '[' . $entry->unit_owner_ref . ']</td>';
                echo '<td>';
                echo '<button class="btn btn-xs btn-white btn-info btn-round" type="button" onclick="window.location.href=\'' . $url .'\'"><i class="ace-icon fa fa-folder-open"></i> View</button>';
                echo '</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
    }

}

//students.training.index
//http://folio/students/training_records?user_type=5&_reset=&firstnames=&surname=&inc_deactivated=No&email=&ni=&uln=&primary_assessor=41&verifier=&tutor=&status_code=1&programme_id=&from_start_date=&to_start_date=&from_planned_end_date=&to_planned_end_date=&from_actual_end_date=&to_actual_end_date=&sortBy=created_at&orderBy=ASC&perPage=20
