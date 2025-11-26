<?php

namespace App\Services\Students\Trainings\Evidences;

use App\Filters\TrainingRecordEvidenceFilters;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Notification;
use App\Models\Training\PCEvidenceMapping;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Models\Training\TrainingRecordEvidenceAssessment;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrainingRecordEvidenceService
{
    public function unpaginatedIndex($userType, TrainingRecordEvidenceFilters $filters)
    {
        $query = TrainingRecordEvidence::filter($filters)
            ->with(['training_record.student', 'training_record.programme', 'categories', 'creator', 'latestAssessment']);
        $query = $query->join('tr', 'tr_evidences.tr_id', '=', 'tr.id');
        $query = $query->join('users AS students', 'students.id', '=', 'tr.student_id');
        $query = $query->leftJoin(DB::raw('(
            SELECT ea.*
            FROM tr_evidence_assesments ea
            INNER JOIN (
                SELECT evidence_id, MAX(created_at) as max_created_at
                FROM tr_evidence_assesments
                GROUP BY evidence_id
            ) latest_ea ON ea.evidence_id = latest_ea.evidence_id AND ea.created_at = latest_ea.max_created_at
        ) AS latest_evidence_assessments'), 'tr_evidences.id', '=', 'latest_evidence_assessments.evidence_id');
        $query = $query->select('tr_evidences.*');

        switch ($userType) {
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

            default:
                $query->where('tr.employer_location', auth()->user()->employer_location);
                break;
        }

        return $query;
    }

    public function create(TrainingRecord $trainingRecord, array $evidenceData)
    {
        $evidence = new TrainingRecordEvidence();
        $evidence->tr_id = $trainingRecord->id;
        $evidence->evidence_name = $evidenceData['evidence_name'];
        $evidence->evidence_desc = $evidenceData['evidence_desc'];
        $evidence->evidence_status = TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED;
        $evidence->created_by = auth()->user()->id;
        $evidence->learner_comments = isset($evidenceData['learner_comments']) ? $evidenceData['learner_comments'] : null;
        $evidence->learner_declaration = isset($evidenceData['learner_declaration']) ? $evidenceData['learner_declaration'] : 0;
        if ($evidenceData['evidence_type'] == 'rowEvidenceFile') {
            $evidence->evidence_type = TrainingRecordEvidence::TYPE_FILE;
            $evidence->evidence_files = json_encode($evidenceData['evidence_file']);
        } elseif ($evidenceData['evidence_type'] == 'rowEvidenceRef') {
            $evidence->evidence_type = TrainingRecordEvidence::TYPE_REFERENCE;
            $evidence->evidence_ref = $evidenceData['evidence_ref'];
        } elseif ($evidenceData['evidence_type'] == 'rowEvidenceURL') {
            $evidence->evidence_type = TrainingRecordEvidence::TYPE_URL;
            $evidence->evidence_url = $evidenceData['evidence_url'];
        } elseif ($evidenceData['evidence_type'] == 'typed') {
            $evidence->evidence_type = TrainingRecordEvidence::TYPE_TYPED_SUBMISSION;
        }

        $evidence->save();

        if (isset($evidenceData['evidence_categories'])) {
            $evidenceCategories = (is_array($evidenceData['evidence_categories']) && count($evidenceData['evidence_categories']) > 0) ?
                $evidenceData['evidence_categories'] :
                [];

            $evidence->categories()->sync($evidenceCategories);
        }

        if ($evidenceData['evidence_type'] == 'rowEvidenceFile' && is_array($evidenceData['evidence_file'])) {
            foreach ($evidenceData['evidence_file'] as $file) {
                $evidence->addMedia(storage_path('tmp' . DIRECTORY_SEPARATOR  . 'uploads' . DIRECTORY_SEPARATOR . $file))
                    ->withCustomProperties(['uploaded_by' => auth()->user()->id])
                    ->toMediaCollection('evidences', 'public');
            }
        }

        if ($evidenceData['evidence_type'] == 'typed') {
            $evidence->evidence_type = TrainingRecordEvidence::TYPE_TYPED_SUBMISSION;

            DB::table('tr_evidence_typed_submissions')->updateOrInsert(
                ['tr_evidence_id' => $evidence->id],
                ['evidence_text_content' => $evidenceData['evidence_text_content']]
            );
        }

        if (isset($evidenceData['tr_dp_task_id'])) // && \App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') == 'FolioTraining')
        {
            $this->linkTask($trainingRecord, $evidence, $evidenceData['tr_dp_task_id']);
        }

        return $evidence;
    }

    private function linkTask(TrainingRecord $training, TrainingRecordEvidence $evidence, $taskId)
    {
        $task = TrainingDeliveryPlanSessionTask::with('session')->find($taskId);
        if (!$task) {
            return;
        }

        try {
            // attach media to task
            $this->attachMediaToTask($training, $evidence, $task);

            // link evidence to task
            DB::table('tr_task_evidence_links')->insert([
                'tr_task_id' => $taskId,
                'tr_evidence_id' => $evidence->id,
            ]);

            $taskStatus = auth()->user()->isStudent() ? TrainingDeliveryPlanSessionTask::STATUS_SUBMITTED : $task->status;
            $task->learner_comments = preg_replace('/[^\x00-\x7F]/', '', $evidence->learner_comments);
            $task->learner_signed_datetime = now();
            $task->status = $taskStatus;
            $task->history()->create([
                'tr_id' => $training->id,
                'comments' => preg_replace('/[^\x00-\x7F]/', '', $evidence->learner_comments),
                'status' => $taskStatus,
                'created_by' => auth()->user()->id,
            ]);
            $task->save();
        } catch (Exception $exception) {
            throw new Exception('Error linking evidence to task: ' . $exception->getMessage(), $exception->getCode());
        }
    }

    public function attachMediaToTask(TrainingRecord $training, TrainingRecordEvidence $evidence, TrainingDeliveryPlanSessionTask $task)
    {
        $sourceMediaItems = $evidence->getMedia('evidences');

        foreach ($sourceMediaItems as $sourceMediaItem) {
            $copiedMediaItem = $sourceMediaItem->copy($task, 'tr_task_evidence', $sourceMediaItem->disk);

            $copiedMediaItem->custom_properties = array_merge(
                $sourceMediaItem->custom_properties,
                [
                    'evidence_id' => $evidence->id,
                    'task_id' => $task->id,
                    'session_id' => optional($task->session)->id,
                    'training_id' => $training->id,
                    'created_by' => auth()->id(),
                ]
            );

            $copiedMediaItem->save();
        }
    }

    private function resetMapping(TrainingRecord $training, TrainingRecordEvidence $evidence, $previouslyAccepted = false)
    {
        // make sure evidence has been rejected and saved calling this function.
        $mappedPcs = $evidence->mapped_pcs;
        if ($previouslyAccepted) {
            // this means evidence was accepted previously and now its changed to rejected
            // "un signoff" the pcs for which this evidence contributed in the progress.
            foreach ($mappedPcs as $pc) {
                if ($pc->assessor_signoff == 1 && $pc->mapped_evidences->count() == $pc->min_req_evidences) {
                    $pc->assessor_signoff = 0;
                    $pc->save();
                }

                $pc->update([
                    'accepted_evidences' => $pc->getAcceptedEvidencesCount(),
                    'awaiting_evidences' => $pc->getAwaitingEvidencesCount(),
                ]);
            }
        }

        // remove evidence mappings afterwards
        $evidence->mappings()->delete();
    }

    public function assess(TrainingRecord $training, TrainingRecordEvidence $evidence, array $assessmentData)
    {
        $previouslyAccepted = $evidence->getOriginal('evidence_status') == TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED ? true : false;

        $evidence->update([
            'evidence_status' => $assessmentData['evidence_status'],
            'assessor_comments' => preg_replace('/[^\x00-\x7F]/', '', $assessmentData['assessor_comments']),
        ]);

        if ($assessmentData['evidence_status'] == TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED) {
            $this->resetMapping($training, $evidence, $previouslyAccepted);
        }

        $evidenceCategories = [];
        if (isset($assessmentData['evidence_categories'])) {
            $evidenceCategories = (is_array($assessmentData['evidence_categories']) && count($assessmentData['evidence_categories']) > 0) ?
                $assessmentData['evidence_categories'] :
                [];
        }
        $evidence->categories()->sync($evidenceCategories);

        if ($assessmentData['evidence_status'] != TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED) {
            $this->saveMapping($evidence, $assessmentData['chkPC']);
        }

        // change made: evidence assessment is stored also in separate table.
        $evidenceAssessment = $evidence->assessments()->create([
            'tr_id' => $training->id,
            'assessment_by' => TrainingRecordEvidenceAssessment::ASSESSMENT_BY_ASSESSOR,
            'assessment_status' => $assessmentData['evidence_status'],
            'assessment_comments' => preg_replace('/[^\x00-\x7F]/', '', $assessmentData['assessor_comments']),
            'created_by' => auth()->user()->id,
        ]);

        if (isset($assessmentData['assessment_feedback_file'])) {
            $fileUploadService = new FileUploadService();
            $fileUploadService->uploadAndAttachMedia(request(), $evidenceAssessment, 'assessment_feedback');
        }

        return $evidence;
    }

    public function map(TrainingRecord $training, TrainingRecordEvidence $evidence, array $mappingData)
    {
        $this->saveMapping($evidence, $mappingData['chkPC']);

        return $evidence;
    }

    private function saveMapping(TrainingRecordEvidence $evidence, array $pcs)
    {
        // only remove unsigned off pcs.
        $unsignedoff_pcs = $evidence->mapped_pcs()
            ->where('assessor_signoff', false)
            ->pluck('portfolio_pc_id')
            ->toArray();

        $evidence->mappings()
            ->whereIn('portfolio_pc_id', $unsignedoff_pcs)
            ->delete();

        if (count($pcs) > 0) {
            foreach ($pcs as $pcId) {
                PCEvidenceMapping::create([
                    'portfolio_pc_id' => $pcId,
                    'tr_evidence_id' => $evidence->id,
                    'created_by' => auth()->user()->id,
                ]);
            }
        }
    }

    public function iqa(TrainingRecord $training, TrainingRecordEvidence $evidence, array $iqaData)
    {
        $evidence->update([
            'verifier_comments' => $iqaData['verifier_comments'],
            'iqa_status' => $iqaData['iqa_status'],
        ]);

        /*
        foreach($evidence->mapped_pcs AS $pc)
        {
            $pc = \App\Models\Training\PortfolioPC::findOrFail($pc->id);
            $pc->iqa_status = $request->evidence_status == TrainingRecordEvidence::STATUS_IQA_ACCEPTED ? 1 : 0;
            $pc->save();
        }
        */

        return $evidence;
    }

    public function studentValidation(TrainingRecord $training, TrainingRecordEvidence $evidence, array $studentValidationData)
    {
        $evidence->update([
            'learner_comments' => $studentValidationData['learner_comments'],
            'learner_declaration' => $studentValidationData['learner_declaration'],
        ]);

        return $evidence;
    }

    public function delete(TrainingRecordEvidence $evidence)
    {
        foreach ($evidence->media as $mediaItem) {
            $mediaItem->delete();
        }

        return $evidence->delete();
    }
}
