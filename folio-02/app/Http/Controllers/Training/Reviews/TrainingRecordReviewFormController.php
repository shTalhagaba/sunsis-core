<?php

namespace App\Http\Controllers\Training\Reviews;

use App\Facades\AppConfig;
use App\Http\Controllers\Controller;
use App\Mail\ReviewFormMailToEmployer;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingReview;
use App\Models\Training\TrainingReviewForm;
use App\Models\User;
use App\Services\Students\Trainings\Reviews\TrainingRecordReviewService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Carbon\CarbonInterval;
use App\Services\FileUploadService;
// use PDF;


class TrainingRecordReviewFormController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except(['showSignatureForm', 'storeSignatureForm']);
    }

    private function getCalculatedFields(TrainingRecord $training, TrainingReview $review)
    {
        /*
        "svPortfolioUnits" => array:1 [
            209 => {
                +"progress": 22
                +"progress_on_last_review": 0
            }
        ]
        */
        $formData = [];
        $lastReview = $review->lastReview();
        // if(! is_null($lastReview) )
        // {
        //     $lastReviewFormData = is_null($lastReview->form->form_data) ? [] : (array) json_decode($lastReview->form->form_data);
        //     $lastReviewFormData['svPortfolioUnits'] = isset($lastReviewFormData['svPortfolioUnits']) ? (array) $lastReviewFormData['svPortfolioUnits'] : [];
        // }

        if (!is_null($lastReview)) 
        {
            if (!is_null($lastReview->form)) 
            {
                $lastReviewFormData = is_null($lastReview->form->form_data) 
                    ? [] 
                    : (array) json_decode($lastReview->form->form_data);

                $lastReviewFormData['svPortfolioUnits'] = isset($lastReviewFormData['svPortfolioUnits']) 
                    ? (array) $lastReviewFormData['svPortfolioUnits'] 
                    : [];
            } 
            else 
            {
                $lastReviewFormData = [];
                $lastReviewFormData['svPortfolioUnits'] = [];
            }
        }

        $formData['svLastReviewActualDate'] = !is_null($lastReview) ? optional($lastReview->meeting_date)->format('d/m/Y') : '';
        $formData['svTotalContrcatedHours'] = $training->totalContrcatedHours();
        $formData['svTotalOtj'] = $training->otj_hours;
        $formData['svTargetPercentageOfOtj'] = round( ($review->daysSinceTrainingStart()/$training->durationInDays())*100 );
        $formData['svActualOtjToDate'] = $training->completedOtj();
        $formData['svExpectedOtjToDate'] = round( $training->otj_hours*($formData['svTargetPercentageOfOtj']/100) );
        $formData['svExpectedOtjDeviation'] = (round($training->completedOtj(false)/3600)) - $formData['svExpectedOtjToDate'];
        $formData['svLastOtjActivity'] = optional($training->otj()->accepted()->latest('date')->latest('start_time')->first())->toArray();
        $formData['svLastOtjActivityDurationFormatted'] = '';
        if(isset($formData['svLastOtjActivity']['id']))
        {
            list($hours, $minutes, $seconds) = sscanf($formData['svLastOtjActivity']['duration'], '%d:%d:%d');
            $duration = CarbonInterval::hours($hours)->minutes($minutes)->seconds($seconds);
            $formData['svLastOtjActivityDurationFormatted'] = $duration->cascade()->forHumans();
        }

        $formData['svPortfolioUnits'] = [];

        foreach($training->portfolios AS $portfolio)
        {
            foreach ($portfolio->units as $unit)
            {
                $formData['svPortfolioUnits'][$unit->id] = (object) [
                    'progress' => $unit->getProgressPercentageGreen(),
                    'progress_on_last_review' => 0,
                ];

                if(isset($lastReviewFormData['svPortfolioUnits']) && array_key_exists($unit->id, $lastReviewFormData['svPortfolioUnits']))
                {
                    $formData['svPortfolioUnits'][$unit->id]->progress_on_last_review = $lastReviewFormData['svPortfolioUnits'][$unit->id]->progress; 
                }
            }
        }

        return $formData;
    }

    public function show(TrainingRecord $training, TrainingReview $review)
    {
        $this->authorize('show', [$review, $training]);

        if(is_null($review->form))
        {
            $reviewForm = $review->form()->create();
        }
        else
        {
            $reviewForm = $review->form;
        }

        $formData = is_null($reviewForm->form_data) ? [] : (array) json_decode($reviewForm->form_data);
        if( isset($formData['svPortfolioUnits']) )
        {
            $formData['svPortfolioUnits'] = (array) $formData['svPortfolioUnits'];
        }
        if( isset($formData['svLastOtjActivity']->id) )
        {
            $formData['svLastOtjActivity'] = (array) $formData['svLastOtjActivity'];
        }

        $formFolder = AppConfig::get('REVIEW_FORM_FOLDER') ?? 'default';
        $formVersionFolder = AppConfig::get('REVIEW_FORM_VERSION') ?? 'v1';

        if(! $reviewForm->locked())
        {
            // then calculate the information which is to be severd.
            $formData = array_merge($formData, $this->getCalculatedFields($training, $review));
        }

        if(auth()->user()->isStudent())
        {
            return view('trainings.reviews.form.student_view', compact('training', 'review', 'reviewForm', 'formFolder', 'formVersionFolder', 'formData'));
        }
        elseif(auth()->user()->user_type == UserTypeLookup::TYPE_EMPLOYER_USER)
        {
            return view('trainings.reviews.form.employer_view', compact('training', 'review', 'reviewForm', 'formFolder', 'formVersionFolder', 'formData'));
        }

        $employerUsers = \DB::table('users')
            ->select(['users.id', 'users.firstnames', 'users.surname', 'users.primary_email'])
            ->where('users.user_type', UserTypeLookup::TYPE_EMPLOYER_USER)
            ->where('employer_location', $training->employer_location)
            ->get();

        return view('trainings.reviews.form.assessor_view', compact('training', 'review', 'reviewForm', 'formFolder', 'formVersionFolder', 'formData', 'employerUsers'));
    }

    public function update(TrainingRecord $training, TrainingReview $review, Request $request, TrainingRecordReviewService $trainingRecordReviewService, FileUploadService $fileUploadService)
    {
        $this->authorize('show', [$review, $training]);

        $request->validate([
            'form_id' => 'required|numeric|in:' . $review->form->id,
        ]);

        $reviewForm = $review->form;

	    if($request->has('subaction') && $request->subaction == 'unlock_review_form')
        {
            if(!$reviewForm->locked())
            {
                return back()->with(['alert-danger' => 'Review form is already unlocked.']);
            }
            
            $reviewForm->update([
                'assessor_signed' => 0,
                'learner_signed' => 0,
                'employer_signed' => 0,
                'assessor_signed_at' => null,
                'learner_signed_at' => null,
                'employer_signed_at' => null,
            ]);

            $review->update([
                'assessor_signed_at' => null,
                'learner_signed_at' => null,
                'employer_signed_at' => null,
            ]);

            return back()->with(['alert-success' => 'Review has been unlocked.']);
        }

        if($reviewForm->completed())
        {
            return back()
                ->withErrors('This review form is locked for further changes.')
                ->withInput();
        }

        if( auth()->user()->isStudent() && $reviewForm->learner_signed )
        {
            return back()
                ->withErrors('This review form is locked for further changes.')
                ->withInput();
        }
        
        if( auth()->user()->user_type == UserTypeLookup::TYPE_EMPLOYER_USER && $reviewForm->employer_signed )
        {
            return back()
                ->withErrors('This review form is locked for further changes.')
                ->withInput();
        }
        

        $severInfo = false;
        if( auth()->user()->isAssessor() )
        {
            $review->update([
                'meeting_date' => $request->meeting_date,
            ]);
            if($request->has('assessor_signed') && $request->assessor_signed == 1)
            {
                $severInfo = true;
            }
        }

        if( auth()->user()->isStudent() )
        {
            $request->validate([
                'learner_comments' => 'required|max:1500',
                'learner_signed' => 'required|numeric|in:1',
            ]);
        }

        if( auth()->user()->user_type == UserTypeLookup::TYPE_EMPLOYER_USER )
        {
            $request->validate([
                'employer_comments' => 'required|max:1500',
                'employer_signed' => 'required|numeric|in:1',
            ]);
        }

        $formData = $request->all();
        if($severInfo)
        {
            $formData = array_merge($formData, $this->getCalculatedFields($training, $review));
        }

        $form = $trainingRecordReviewService->updateForm(
            $training, $review, $reviewForm, $formData, auth()->user()->user_type
        );

	    if ($request->hasFile('review_form_attachment')) 
        {
            $fileUploadService->uploadAndAttachMedia($request, $form, 'review_form_attachment');
        }

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Review details have been updated successfully.']);
    }

    public function employerSignatureEmail(TrainingRecord $training, TrainingReview $review, Request $request, TrainingRecordReviewService $trainingRecordReviewService)
    {
        $this->authorize('show', [$review, $training]);
        
        abort_if(! auth()->user()->isAdmin() && ! auth()->user()->isAssessor(), Response::HTTP_UNAUTHORIZED );

        if($review->form->completed())
        {
            return back()->withErrors('This review form is already completed.');
        }

        if(! $review->form->readyForEmployerSign())
        {
            return back()->withErrors('This review form is not yet ready for employer/line manager signature.');
        }

        $request->validate([
            'employer_user' => 'required|numeric|in:' . $training->employer->users()->where('user_type', UserTypeLookup::TYPE_EMPLOYER_USER)->pluck('users.id')->implode(','),
        ]);

        $employerUser = User::findOrFail($request->employer_user);

        $signedUrl = $trainingRecordReviewService->generateSignedUrl($review, $employerUser);

        Mail::to($employerUser->primary_email)->send(new ReviewFormMailToEmployer($signedUrl, $employerUser, $review));

        if($request->ajax())
        {
            return response()
                ->json([
                    'message' => 'Email is sent successfully',
                ]);
        }

        return back()->with(['message' => 'Email is sent successfully']);
    }

    public function showSignatureForm(Request $request)
    {
        if (!$request->hasValidSignature()) 
        {
            abort(Response::HTTP_FORBIDDEN, 'Invalid URL');
        }

        $entities = $this->validateAndGetModels($request->form_id, $request->review_id, $request->training_id, $request->user_id);

        $reviewForm = $entities['reviewForm'];
        $review = $entities['review'];
        $training = $entities['training'];
        $signatory = $entities['signatory'];

        $formFolder = AppConfig::get('REVIEW_FORM_FOLDER') ?? 'default';
        $formVersionFolder = AppConfig::get('REVIEW_FORM_VERSION') ?? 'v1';
        $formData = is_null($reviewForm->form_data) ? [] : (array) json_decode($reviewForm->form_data);

        $formData = is_null($reviewForm->form_data) ? [] : (array) json_decode($reviewForm->form_data);
        if( isset($formData['svPortfolioUnits']) )
        {
            $formData['svPortfolioUnits'] = (array) $formData['svPortfolioUnits'];
        }
        if( isset($formData['svLastOtjActivity']->id) )
        {
            $formData['svLastOtjActivity'] = (array) $formData['svLastOtjActivity'];
        }

        return view('trainings.reviews.form.employer_view_open', compact('training', 'review', 'reviewForm', 'formFolder', 'formVersionFolder', 'formData', 'signatory'));
    }

    public function storeSignatureForm(Request $request, TrainingRecordReviewService $trainingRecordReviewService)
    {
        $request->validate([
            'training_id' => 'required|numeric',
            'review_id' => 'required|numeric',
            'form_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        $entities = $this->validateAndGetModels($request->form_id, $request->review_id, $request->training_id, $request->user_id);

        $reviewForm = $entities['reviewForm'];
        $review = $entities['review'];
        $training = $entities['training'];
        $signatory = $entities['signatory'];

        $form = $trainingRecordReviewService->updateForm(
            $training, $review, $reviewForm, $request->all(), $signatory->user_type
        );

        \App\Models\DocumentSignature::logSignatures($form, $signatory->id, $signatory->full_name);

        return view('trainings.reviews.form.employer_view_success_message');
    }

    /*
    public function export(TrainingRecord $training, TrainingReview $review)
    {
        $this->authorize('show', [$review, $training]);
        abort_if(is_null($review->form), Response::HTTP_BAD_REQUEST, 'Review form is not completed yet.');

        $reviewForm = $review->form;

        $formData = is_null($reviewForm->form_data) ? [] : (array) json_decode($reviewForm->form_data);
        if( isset($formData['svPortfolioUnits']) )
        {
            $formData['svPortfolioUnits'] = (array) $formData['svPortfolioUnits'];
        }
        if( isset($formData['svLastOtjActivity']->id) )
        {
            $formData['svLastOtjActivity'] = (array) $formData['svLastOtjActivity'];
        }

        $formFolder = AppConfig::get('REVIEW_FORM_FOLDER') ?? 'default';
        $formVersionFolder = AppConfig::get('REVIEW_FORM_VERSION') ?? 'v1';

        if(! $reviewForm->locked())
        {
            // then calculate the information which is to be severd.
            $formData = array_merge($formData, $this->getCalculatedFields($training, $review));
        }
        $pdf = PDF::loadView('trainings.reviews.form.export', [
            'training' => $training,
            'review' => $review,
            'reviewForm' => $reviewForm,
            'formFolder' => $formFolder,
            'formVersionFolder' => $formVersionFolder,
            'formData' => $formData,
        ]);
        return $pdf->stream();
    }
    */
    
    private function validateAndGetModels($formId, $reviewId, $trainingId, $signatoryId)
    {
        $reviewForm = TrainingReviewForm::findOrFail($formId);
        $review = TrainingReview::findOrFail($reviewId);
        $training = TrainingRecord::findOrFail($trainingId);
        $signatory = User::findOrFail($signatoryId);

        abort_if( $reviewForm->id != $review->form->id, Response::HTTP_UNAUTHORIZED );
        abort_if( !in_array($review->id, $training->reviews()->pluck('id')->toArray()), Response::HTTP_UNAUTHORIZED );
        abort_if( $signatory->employer->id != $training->employer->id, Response::HTTP_UNAUTHORIZED );
        if($reviewForm->completed())
        {
            abort(Response::HTTP_FORBIDDEN, 'This form is fully signed.');
        }
        if(! $reviewForm->readyForEmployerSign())
        {
            abort(Response::HTTP_FORBIDDEN, 'This form is not yet ready to sign.');
        }

        return [
            'reviewForm' => $reviewForm,
            'review' => $review,
            'training' => $training,
            'signatory' => $signatory,
        ];
    }

}