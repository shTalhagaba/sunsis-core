<?php

namespace App\Http\Controllers\Training\Reviews;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainingReviewRequest;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingReview;
use App\Services\Students\Trainings\Reviews\TrainingRecordReviewService;
use App\Services\FileUploadService;

class TrainingRecordReviewController extends Controller
{
    public $trainingRecordReviewService;

    public function __construct(TrainingRecordReviewService $trainingRecordReviewService)
    {
        $this->middleware(['auth']);
        $this->trainingRecordReviewService = $trainingRecordReviewService;
    }

    public function create(TrainingRecord $training)
    {
        $this->authorize('create', [TrainingReview::class, $training]);

        $assessorList[$training->primary_assessor] = $training->primaryAssessor->full_name;
        if(!is_null($training->secondary_assessor))
        {
            $assessorList[$training->secondary_assessor] = $training->secondaryAssessor->full_name;
        }

        return view('trainings.reviews.create', compact('training', 'assessorList'));
    }

    public function store(TrainingRecord $training, StoreTrainingReviewRequest $request, FileUploadService $fileUploadService)
    {
        $this->authorize('create', [TrainingReview::class, $training]);

        $reviewData = array_merge($request->validated(), ['created_by' => auth()->user()->id]);

        $review = $this->trainingRecordReviewService->create($training, $reviewData);

	if ($request->hasFile('review_file_attachment')) 
        {
            $fileUploadService->uploadAndAttachMedia($request, $review, 'review_file_attachment');
        }

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Review has been added successfully.']);
    }

    public function show(TrainingRecord $training, TrainingReview $review)
    {
        $this->authorize('show', [$review, $training]);

        return view('trainings.reviews.show', compact('training', 'review'));
    }

    public function edit(TrainingRecord $training, TrainingReview $review)
    {
        $this->authorize('edit', [$review, $training]);

        $assessorList[$training->primary_assessor] = $training->primaryAssessor->full_name;
        if(!is_null($training->secondary_assessor))
        {
            $assessorList[$training->secondary_assessor] = $training->secondaryAssessor->full_name;
        }
        
        return view('trainings.reviews.edit', compact('training', 'assessorList', 'review'));
    }

    public function update(TrainingRecord $training, TrainingReview $review, StoreTrainingReviewRequest $request)
    {
        $this->authorize('edit', [$review, $training]);

        if(optional($review->form)->locked())
        {
            return back()
                ->withErrors('This review is locked for further changes.')
                ->withInput();
        }

        $review = $this->trainingRecordReviewService->update($training, $review, $request->validated());

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Review has been updated successfully.']);
    }
    
    public function destroy(TrainingRecord $training, TrainingReview $review)
    {
        $this->authorize('delete', [$review, $training]);

        if(optional($review->form)->locked())
        {
            return back()
                ->withErrors('This review is singed, it cannot be deleted.')
                ->withInput();
        }

        $review = $this->trainingRecordReviewService->delete($training, $review);

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Review has been deleted successfully.']);
    }
}