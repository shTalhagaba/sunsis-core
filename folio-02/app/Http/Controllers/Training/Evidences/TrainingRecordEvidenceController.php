<?php

namespace App\Http\Controllers\Training\Evidences;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainingRecordEvidenceRequest;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Notifications\TrainingEvidence\EvidenceSubmitted;
use App\Services\Students\Trainings\Evidences\TrainingRecordEvidenceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TrainingRecordEvidenceController extends Controller
{
    public $trainingRecordEvidenceService;

    public function __construct(TrainingRecordEvidenceService $trainingRecordEvidenceService)
    {
        $this->middleware(['auth']);
        $this->trainingRecordEvidenceService = $trainingRecordEvidenceService;
    }

    public function create(TrainingRecord $training)
    {
        $this->authorize('create', [TrainingRecordEvidence::class, $training]);

        $typedSubmission = request()->query('_type') == 'typed' ? true : false;

        $trDpTasks = DB::table('tr_tasks')
            ->where('tr_id', $training->id)
            ->whereIn('status', [TrainingDeliveryPlanSessionTask::STATUS_PENDING, TrainingDeliveryPlanSessionTask::STATUS_REFERRED])
            ->pluck('title', 'id')
            ->toArray();

        return auth()->user()->isStudent() ?
            ($typedSubmission ? view('trainings.evidences.create_typed_evidence', compact('training', 'trDpTasks')) : view('trainings.evidences.create', compact('training', 'trDpTasks'))) :
            view('trainings.evidences.create_by_system_user', compact('training', 'trDpTasks'));
    }

    public function store(TrainingRecord $training, StoreTrainingRecordEvidenceRequest $request)
    {
        $this->authorize('create', [TrainingRecordEvidence::class, $training]);

        try {
            $evidence = $this->trainingRecordEvidenceService->create($training, $request->all());
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (auth()->user()->isStudent() && isset($evidence->id)) {
            $training->primaryAssessor->notify(new EvidenceSubmitted($evidence));
            AppHelper::cacheUnreadCountForUser($training->primaryAssessor);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'The evidence has been successfully submitted.',
            ]);
        }

        return redirect()->route('trainings.show', $training)->with('alert-success', 'The evidence has been successfully submitted.');
    }

    public function show(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
        $this->authorize('show', [$evidence, $training]);

        $linkedTaskId = DB::table('tr_task_evidence_links')
            ->where('tr_task_evidence_links.tr_evidence_id', $evidence->id)
            ->value('tr_task_id');
        $linkedTask = TrainingDeliveryPlanSessionTask::find($linkedTaskId);

        $result = $evidence->getMappings();

        return view('trainings.evidences.show', compact('training', 'evidence', 'result', 'linkedTask'));
    }

    public function map(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
        $this->authorize('show', [$evidence, $training]);

        abort_if(! in_array($evidence->getOriginal('evidence_status'), [TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED]), Response::HTTP_UNAUTHORIZED);

        return view('trainings.evidences.mapping', compact('training', 'evidence'));
    }

    public function saveMapping(TrainingRecord $training, TrainingRecordEvidence $evidence, Request $request)
    {
        $this->authorize('show', [$evidence, $training]);
        if (isset($request->chkPC)) {
            $this->trainingRecordEvidenceService->map($training, $evidence, $request->all());
        }

        return redirect()->route('trainings.show', $training);
    }

    public function destroy(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
        $this->authorize('delete', [$evidence, $training]);

        if (!in_array($evidence->getOriginal('evidence_status'), [TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED, TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED])) {
            return response()->json([
                'success' => false,
                'message' => 'This evidence cannot be deleted.'
            ]);
        }

        try {
            $this->trainingRecordEvidenceService->delete($evidence);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Evidence is deleted successfully.'
        ]);
    }

    public function resubmit(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
        $this->authorize('show', [$evidence, $training]);

        abort_if(! in_array($evidence->getOriginal('evidence_status'), [TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED]), Response::HTTP_UNAUTHORIZED);

        return view('trainings.evidences.resubmit', compact('training', 'evidence'));
    }
}
