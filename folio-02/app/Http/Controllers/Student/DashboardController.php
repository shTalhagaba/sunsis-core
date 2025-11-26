<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Student;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getLearnerTasks()
    {
        if (!auth()->user() || !optional(auth()->user())->isStudent()) 
        {
            return;
        }

        $student = Student::find( auth()->user()->id );
        $trainingRecord = $student->training_records()->where('status_code', TrainingStatusLookup::STATUS_CONTINUING)->orderBy('tr.start_date', 'DESC')->limit(1)->first();
        if(!$trainingRecord)
        {
            return;
        }

        // get all the delivery plan sessions which are signed by the assessor and not yet signed by the learner
        $sessions = TrainingDeliveryPlanSession::where('tr_id', $trainingRecord->id)
            ->where('assessor_sign', 1)
            ->where('student_sign', 0)
            ->orderBy('tr_dp_sessions.assessor_sign_date')
            ->get();

        if($sessions->count() > 0)
        {
            echo '<table class="table table-bordered"><caption class="bolder text-primary">Delivery Plan Sessions</caption>';
            echo '<thead><tr><th>Session Planned Date</th><th>Session Actual Date</th><th>Action</th></tr></thead>';
            echo '<tbody>';
            foreach($sessions AS $session)        
            {
                $url = route('trainings.sessions.show', [$trainingRecord->id, $session->id]);
                echo '<tr>';
                echo '<td>' . optional($session->session_start_date)->format('d/m/Y') . '</td>';
                echo '<td>' . optional($session->actual_date)->format('d/m/Y') . '</td>';
                echo '<td>';
                echo '<button class="btn btn-xs btn-white btn-info btn-round" type="button" onclick="window.location.href=\'' . $url .'\'"><i class="ace-icon fa fa-folder-open"></i> View</button>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }


        //get all the DP pending tasks.
        $tasks = TrainingDeliveryPlanSessionTask::where('tr_id', $trainingRecord->id)
            ->pending()
            ->whereNull('tr_tasks.assessor_signed_datetime')
            ->orderBy('tr_tasks.complete_by')
            ->get();

        if($tasks->count() > 0)
        {
            echo '<table class="table table-bordered"><caption class="bolder text-primary">Delivery Plan Sessions Tasks</caption>';
            echo '<thead><tr><th>Task</th><th>Complete By</th><th>Action</th></tr></thead>';
            echo '<tbody>';
            foreach($tasks AS $task)        
            {
                $url = route('trainings.sessions.tasks.show', [$task->tr_id, $task->dp_session_id, $task->id]);
                echo '<tr>';
                echo '<td>' . $task->title . '</td>';
                echo '<td>';
                echo optional($task->complete_by)->format('d/m/Y') . '<br><span class="small text-info">' . optional($task->complete_by)->diffForHumans() . '</span>';
                echo '</td>';
                echo '<td>';
                echo '<button class="btn btn-xs btn-white btn-info btn-round" type="button" onclick="window.location.href=\'' . $url .'\'"><i class="ace-icon fa fa-folder-open"></i> View</button>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        
        
    }
}
