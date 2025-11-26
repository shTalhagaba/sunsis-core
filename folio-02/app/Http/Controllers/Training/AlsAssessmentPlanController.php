<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LookupManager;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\AlsAssessmentPlan;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AlsAssessmentPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function create(TrainingRecord $training)
    {
        $assessorList[$training->primary_assessor] = $training->primaryAssessor->full_name;
        if($training->secondary_assessor && $training->secondaryAssessor)
        {
            $assessorList[$training->secondary_assessor] = $training->secondaryAssessor->full_name;
        }
        $assessorList = LookupManager::getAssessors();

        $tutorsList = collect();
        if($training->tutor && $training->tutorUser)
        {
            $tutorsList->put($training->tutor, $training->tutorUser->full_name);
        }

        $verifiersList = collect();
        if($training->verifier && $training->verifierUser)
        {
            $verifiersList->put($training->verifier, $training->verifierUser->full_name);
        }

        $training->portfolios
            ->filter(function ($p) {
                return $p->fs_tutor_id && $p->tutor;
            })
            ->each(function ($p) use ($tutorsList) {
                $tutorsList->put($p->fs_tutor_id, $p->tutor->full_name);
            });

        $training->portfolios
            ->filter(function ($p) {
                return $p->fs_verifier_id && $p->verifier;
            })
            ->each(function ($p) use ($verifiersList) {
                $verifiersList->put($p->fs_verifier_id, $p->verifier->full_name);
            });

        $tutorsList = $tutorsList->all();
        $tutorsList = User::withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_TUTOR)
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();

        $verifiersList = LookupManager::getVerifiers();

        return view('trainings.als_assessment.create', compact('training', 'assessorList', 'tutorsList', 'verifiersList'));
    }

    public function store(TrainingRecord $training, Request $request)
    {
        $request->validate([
            'assessor_id' => 'required|numeric',
            'fs_tutor_id' => 'nullable|numeric',
            'iqa_id' => 'required|numeric',
            'als_tutor_id' => 'nullable|numeric',
            'referral_date' => 'required|date_format:Y-m-d',
            'als_meeting_date' => 'required|date_format:Y-m-d',
        ]);

        $alsAssessment = AlsAssessmentPlan::create([
            'tr_id' => $training->id,
            'assessor_id' => $request->assessor_id,
            'fs_tutor_id' => $request->fs_tutor_id,
            'iqa_id' => $request->iqa_id,
            'als_tutor_id' => $request->als_tutor_id,
            'referral_date' => $request->referral_date,
            'als_meeting_date' => $request->als_meeting_date,
        ]);

        return redirect()->route('trainings.als_assessment.show', compact('training', 'alsAssessment'))->with(['alert-success' => 'ALS assessment record has been created successfully.']);
    }

    public function show(TrainingRecord $training, AlsAssessmentPlan $alsAssessment)
    {
        return view('trainings.als_assessment.show', compact('training', 'alsAssessment'));
    }

    public function edit(TrainingRecord $training, AlsAssessmentPlan $alsAssessment, Request $request)
    {
        
        if($request->has('subaction') && $request->subaction == 'assessment_form')
        {
            if(auth()->user()->isStudent())
            {
                return view('trainings.als_assessment.assessment_form_for_learner', compact('training', 'alsAssessment'));
            }
            elseif(auth()->user()->isVerifier())
            {
                return view('trainings.als_assessment.assessment_form_for_iqa', compact('training', 'alsAssessment'));
            }
            else
            {
                return view('trainings.als_assessment.assessment_form', compact('training', 'alsAssessment'));
            }                
        }

        $assessorList[$training->primary_assessor] = $training->primaryAssessor->full_name;
        if($training->secondary_assessor && $training->secondaryAssessor)
        {
            $assessorList[$training->secondary_assessor] = $training->secondaryAssessor->full_name;
        }
        $assessorList = LookupManager::getAssessors();

        $tutorsList = collect();
        if($training->tutor && $training->tutorUser)
        {
            $tutorsList->put($training->tutor, $training->tutorUser->full_name);
        }

        $training->portfolios
            ->filter(function ($p) {
                return $p->fs_tutor_id && $p->tutor;
            })
            ->each(function ($p) use ($tutorsList) {
                $tutorsList->put($p->fs_tutor_id, $p->tutor->full_name);
            });

        $tutorsList = $tutorsList->all();
        $tutorsList = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_TUTOR)
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();

        $verifiersList = LookupManager::getVerifiers();

        return view('trainings.als_assessment.edit', compact('training', 'assessorList', 'tutorsList', 'verifiersList', 'alsAssessment'));
    }

    public function update(TrainingRecord $training, AlsAssessmentPlan $alsAssessment, Request $request)
    {
        $request->validate([
            'assessor_id' => 'required|numeric',
            'fs_tutor_id' => 'nullable|numeric',
            'iqa_id' => 'required|numeric',
            'als_tutor_id' => 'nullable|numeric',
            'referral_date' => 'required|date_format:Y-m-d',
            'als_meeting_date' => 'required|date_format:Y-m-d',
        ]);

        $alsAssessment->update([
            'assessor_id' => $request->assessor_id,
            'fs_tutor_id' => $request->fs_tutor_id,
            'iqa_id' => $request->iqa_id,
            'als_tutor_id' => $request->als_tutor_id,
            'referral_date' => $request->referral_date,
            'als_meeting_date' => $request->als_meeting_date,
        ]);

        return redirect()->route('trainings.als_assessment.show', compact('training', 'alsAssessment'))->with(['alert-success' => 'ALS assessment record has been updated successfully.']);
    }

    public function saveForm(TrainingRecord $training, AlsAssessmentPlan $alsAssessment, Request $request)
    {
        if(auth()->user()->id === $training->student_id)
        {
            if($request->has('learner_confirm_choice') && $request->learner_confirm_choice == '1')
            {
                $alsAssessment->learner_confirm_choice = 1;
            }
            if($request->has('learner_sign') && $request->learner_sign == '1')
            {
                $alsAssessment->learner_sign = 1;    
                $alsAssessment->learner_sign_date = is_null($alsAssessment->learner_sign_date) ? now()->format('Y-m-d') : $alsAssessment->learner_sign_date;
            }
            $alsAssessment->share_with_employer = $request->input('share_with_employer');
            $alsAssessment->save();
            return redirect()->route('trainings.als_assessment.show', compact('training', 'alsAssessment'))->with(['alert-success' => 'ALS assessment record has been updated successfully.']);    
        }
        elseif(auth()->user()->id === $alsAssessment->assessor_id)
        {
            if($request->has('assessor_sign') && $request->assessor_sign == '1')
            {
                $alsAssessment->assessor_sign = 1;    
                $alsAssessment->assessor_sign_date = is_null($alsAssessment->assessor_sign_date) ? now()->format('Y-m-d') : $alsAssessment->assessor_sign_date;
            }
        }
        elseif(in_array(auth()->user()->id, [$alsAssessment->fs_tutor_id, $alsAssessment->als_tutor_id]))
        {
            if(
                auth()->user()->id === $alsAssessment->fs_tutor_id && 
                $request->has('fs_tutor_sign') 
                && $request->fs_tutor_sign == '1'
                )
            {
                $alsAssessment->fs_tutor_sign = 1;    
                $alsAssessment->fs_tutor_sign_date = is_null($alsAssessment->fs_tutor_sign_date) ? now()->format('Y-m-d') : $alsAssessment->fs_tutor_sign_date;
            }
            if(
                auth()->user()->id === $alsAssessment->als_tutor_id && 
                $request->has('als_tutor_sign') && 
                $request->als_tutor_sign == '1'
                )
            {
                $alsAssessment->als_tutor_sign = 1;    
                $alsAssessment->als_tutor_sign_date = is_null($alsAssessment->als_tutor_sign_date) ? now()->format('Y-m-d') : $alsAssessment->als_tutor_sign_date;
            }
        }
        elseif(auth()->user()->id === $alsAssessment->iqa_id)
        {
            if($request->has('iqa_sign') && $request->iqa_sign == '1')
            {
                $alsAssessment->iqa_sign = 1;    
                $alsAssessment->iqa_sign_date = is_null($alsAssessment->iqa_sign_date) ? now()->format('Y-m-d') : $alsAssessment->iqa_sign_date;
            }
        }
        elseif(!auth()->user()->isAdmin())
        {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $data = $request->except([
            'tr_id',
            'assessor_id',
            'fs_tutor_id',
            'iqa_id',
            'als_tutor_id',
            'learner_sign',
            'learner_sign_date',
            'assessor_sign',
            'assessor_sign_date',
            'fs_tutor_sign',
            'fs_tutor_sign_date',
            'iqa_sign',
            'iqa_sign_date',
            'als_tutor_sign',
            'als_tutor_sign_date',
            'learner_confirm_choice',
        ]);

        if ($request->has('recommendations')) 
        {
            $data['recommendations'] = json_encode($request->input('recommendations'));
        }

        $alsAssessment->update($data);

        return redirect()->route('trainings.als_assessment.show', compact('training', 'alsAssessment'))->with(['alert-success' => 'ALS assessment record has been updated successfully.']);
    }
}