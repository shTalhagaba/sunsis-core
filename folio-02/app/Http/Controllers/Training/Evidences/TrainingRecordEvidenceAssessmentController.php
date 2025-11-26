<?php

namespace App\Http\Controllers\Training\Evidences;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvidenceAssessmentRequest;
use App\Mail\EvidenceAssessed;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Notifications\TrainingEvidence\EvidenceAssessedByPrimaryAssessor;
use App\Services\Students\Trainings\Evidences\TrainingRecordEvidenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TrainingRecordEvidenceAssessmentController extends Controller
{
    public $trainingRecordEvidenceService;

    public function __construct(TrainingRecordEvidenceService $trainingRecordEvidenceService)
    {
        $this->middleware(['auth']);
        $this->trainingRecordEvidenceService = $trainingRecordEvidenceService;
    }

    public function assess(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
        $this->authorize('assess', [$evidence, $training]);

        $assessment_ddl = TrainingRecordEvidence::getAssessmentStatusDDL();

        $selectedCategories = $evidence->categories()->pluck('lookup_tr_evidence_categories.id')->toArray();

        return view('trainings.evidences.assess', compact('training', 'evidence', 'assessment_ddl', 'selectedCategories'));
    }
    
    public function saveAssessment(TrainingRecord $training, TrainingRecordEvidence $evidence, StoreEvidenceAssessmentRequest $request)
    {
        $this->authorize('assess', [$evidence, $training]);

        $evidence = $this->trainingRecordEvidenceService->assess($training, $evidence, $request->all());

        Mail::to($training->student->primary_email)
            ->send(new EvidenceAssessed($evidence, $training->student));

        if( !is_null($training->secondaryAssessor) && $evidence->getOriginal('evidence_status') == TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED )
        {
            $training->secondaryAssessor->notify(new EvidenceAssessedByPrimaryAssessor($evidence));
            AppHelper::cacheUnreadCountForUser($training->secondaryAssessor);
        }
	
        return redirect()->route('trainings.show', $training);
    }

    public function studentValidation(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
        $this->authorize('studentValidation', [$evidence, $training]);

        $selectedCategories = $evidence->categories()->pluck('lookup_tr_evidence_categories.id')->toArray();

        return view('trainings.evidences.student_validation', compact('training', 'evidence', 'selectedCategories'));
    }

    public function saveStudentValidation(TrainingRecord $training, TrainingRecordEvidence $evidence, Request $request)
    {
        $this->authorize('studentValidation', [$evidence, $training]);

        $request->validate([
            'learner_comments' => 'nullable|max:500',
            'learner_declaration' => 'required|in:1',
        ]);

        $evidence = $this->trainingRecordEvidenceService->studentValidation($training, $evidence, $request->all());
	
        return redirect()->route('trainings.show', $training);
    }

    public function showAssessorComm(TrainingRecord $training, TrainingRecordEvidence $evidence)
    {
        return view('trainings.evidences.assessors_communication', compact('training', 'evidence'));
    }

    public function saveAssessorComm(TrainingRecord $training, TrainingRecordEvidence $evidence, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comments' => 'required|max:800',
        ]);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }

        DB::table('tr_evidence_assessors_comments')
            ->insert([
                'evidence_id' => $evidence->id,
                'comments' => $request->comments,
                'created_by' => auth()->user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $notifier_id = $training->secondaryAssessor->id;
        if(auth()->user()->id == $training->secondaryAssessor->id)
        {
            $notifier_id = $training->primaryAssessor->id;
        }

        return redirect()->route('trainings.show', $training);
    }
}