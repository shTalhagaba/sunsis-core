<?php

namespace App\Http\Controllers\Training\Evidences;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvidenceIqaRequest;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Notifications\TrainingEvidence\EvidenceIQAd;
use App\Services\Students\Trainings\Evidences\TrainingRecordEvidenceService;

class TrainingRecordEvidenceIqaController extends Controller
{
    public $trainingRecordEvidenceService;

    public function __construct(TrainingRecordEvidenceService $trainingRecordEvidenceService)
    {
        $this->middleware(['auth']);
        $this->trainingRecordEvidenceService = $trainingRecordEvidenceService;
    }

    public function iqa(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
	    $this->authorize('iqa', [$evidence, $training]);

        $result = $evidence->getMappings();

        return view('trainings.evidences.iqa', compact('training', 'evidence', 'result'));
    }

    public function saveIqaAssessment(TrainingRecord $training, TrainingRecordEvidence $evidence, StoreEvidenceIqaRequest $request)
    {
        $this->authorize('iqa', [$evidence, $training]);

        $this->trainingRecordEvidenceService->iqa($training, $evidence, $request->validated());

        $training->primaryAssessor->notify(new EvidenceIQAd($evidence, auth()->user()));
        AppHelper::cacheUnreadCountForUser($training->primaryAssessor);

        return redirect()->route('trainings.show', $training);
    }
}