<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\PCEvidenceMapping;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Models\Training\TrainingRecordEvidenceAssessment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskEvidenceLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function create(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task)
    {
        // on this screen this will be the a form (evidence assessment form fields), give option to select file etc.
        return view('trainings.tasks_evidences_link.create', compact('training', 'session', 'task'));
    }

    public function store(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task, Request $request)
    {
        $fileNamesAndExtensions = [];
        foreach($task->media as $media) 
        {
            $fileNamesAndExtensions[] = $media->name . '.' . $media->extension;
        }

        try 
        {
            // create an evidence record
            $evidence = $this->createEvidence($training, $request, $task, $fileNamesAndExtensions);

            // attach categories
            $evidence->categories()->sync($request->input('evidence_categories', []));

            // attach mapped pcs (task is linked to pcs)
            $this->attachPcs($evidence, $task->pcs());

            // create an assessment record
            $evidenceAssessment = $this->createEvidenceAssessment($evidence, $training, $request);
            
            // attach media files (task media files are linked to evidence)
            $this->attachMediaToEvidence($task, $training, $session, $evidence, $evidenceAssessment);

            // record it in the table that links evidence to task
            DB::table('tr_task_evidence_links')->insert(['tr_evidence_id' => $evidence->id, 'tr_task_id' => $task->id]);
        } 
        catch(Exception $exception)
        {            
            return redirect()
                ->route('trainings.sessions.tasks.show', [$training, $session, $task])
                ->with(['alert-danger' => $exception->getCode() . ': Something went wrong, task evidence linking failed.']);
                // ->with(['alert-danger' => $exception->getCode() . ': ' . $exception->getMessage()]);
        }
        
        return redirect()
            ->route('trainings.sessions.tasks.show', [$training, $session, $task])
            ->with(['alert-success' => 'Evidence has been successfully linked to the task.']);
    }

    private function createEvidence(TrainingRecord $training, Request $request, TrainingDeliveryPlanSessionTask $task, $fileNamesAndExtensions)
    {
        $evidence = TrainingRecordEvidence::create([
            'tr_id' => $training->id,
            'evidence_name' => substr($request->input('evidence_name'), 0, 250),
            'evidence_desc' => substr($request->input('evidence_desc'), 0, 500),
            'evidence_status' => $request->input('evidence_status', TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED),
            'created_by' => auth()->user()->id,
            'learner_comments' => null,
            'learner_declaration' => 0,
            'assessor_comments' => $request->input('assessor_comments'),
            'evidence_type' => $request->input('evidence_type', TrainingRecordEvidence::TYPE_FILE),
            'evidence_files' => json_encode( $fileNamesAndExtensions ),
            'learner_declaration' => 1,
            'learner_comments' => optional($task->history()->where('created_by', $training->id)->latest()->first())->comments,
        ]);

        return $evidence;
    }

    private function attachPcs(TrainingRecordEvidence $evidence, $taskPcs)
    {
        if(count($taskPcs) > 0)
        {
            foreach($taskPcs AS $pcId)
            {
                PCEvidenceMapping::create([
                    'portfolio_pc_id' => $pcId,
                    'tr_evidence_id' => $evidence->id,
                    'created_by' => auth()->user()->id,
                ]);
            }
        }
    }

    private function createEvidenceAssessment(TrainingRecordEvidence $evidence, TrainingRecord $training, Request $request)
    {
        $evidenceAssessment = $evidence->assessments()->create([
            'tr_id' => $training->id,
            'assessment_by' => 'A',
            'assessment_status' => $request->input('assessment_status', TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED),
            'assessment_comments' => $request->input('assessor_comments'),
            'created_by' => auth()->user()->id,
        ]);

        return $evidenceAssessment;
    }
    
    private function attachMediaToEvidence(TrainingDeliveryPlanSessionTask $task, TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingRecordEvidence $evidence, TrainingRecordEvidenceAssessment $evidenceAssessment)
    {
        $sourceMediaItems = $task->getMedia('tr_task_evidence');
        foreach ($sourceMediaItems as $sourceMediaItem) 
        {
            $copiedMediaItem = $sourceMediaItem->copy($evidence, 'evidences', $sourceMediaItem->disk);

            $copiedMediaItem->custom_properties = array_merge(
                $sourceMediaItem->custom_properties, 
                [
                    'evidence_id' => $evidence->id,
                    'task_id' => $task->id,
                    'session_id' => $session->id,
                    'training_id' => $training->id,
                    'created_by' => auth()->user()->id,
                ]
            );

            $copiedMediaItem->save();
        }

        $sourceMediaItems = $task->getMedia('tr_task_feedback_file');
        foreach ($sourceMediaItems as $sourceMediaItem) 
        {
            $copiedMediaItem = $sourceMediaItem->copy($evidenceAssessment, 'assessment_feedback', $sourceMediaItem->disk);

            $copiedMediaItem->custom_properties = array_merge(
                $sourceMediaItem->custom_properties, 
                [
                    'evidence_id' => $evidence->id,
                    'task_id' => $task->id,
                    'session_id' => $session->id,
                    'training_id' => $training->id,
                    'created_by' => auth()->user()->id,
                ]
            );

            $copiedMediaItem->save();
        }
    }
}