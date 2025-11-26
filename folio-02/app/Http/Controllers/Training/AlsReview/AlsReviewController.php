<?php

namespace App\Http\Controllers\Training\AlsReview;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LookupManager;
use App\Models\Lookups\AlsResonableAdjustmentLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\AlsReview;
use App\Models\Training\AlsReviewSession;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AlsReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function create(TrainingRecord $training)
    {
        $assessorList[$training->primary_assessor] = $training->primaryAssessor->full_name;
        if ($training->secondary_assessor && $training->secondaryAssessor) {
            $assessorList[$training->secondary_assessor] = $training->secondaryAssessor->full_name;
        }
        $assessorList = LookupManager::getAssessors();

        $tutorsList = collect();
        if ($training->tutor && $training->tutorUser) {
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
        $tutorsList = User::withActiveAccess()->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_TUTOR)
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();

        return view('trainings.als_reviews.create', compact('training', 'assessorList', 'tutorsList'));
    }

    public function store(TrainingRecord $training, Request $request)
    {
        $request->validate([
            'assessor' => 'required|numeric',
            'tutor' => 'nullable|numeric',
            'planned_date' => 'required|date',
        ]);

        $alsReview = AlsReview::create([
            'tr_id' => $training->id,
            'assessor' => $request->assessor,
            'tutor' => $request->tutor,
            'planned_date' => $request->planned_date,
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->route('trainings.als_reviews.show', compact('training', 'alsReview'))->with(['alert-success' => 'ALS Review been created successfully.']);
    }

    public function show(TrainingRecord $training, AlsReview $alsReview)
    {
        $reasonableAdjustments = AlsResonableAdjustmentLookup::all()->sortBy('id');
        $selectedReasonableAdjustmentsAssessor = json_decode($alsReview->reasonable_adjustments_assessor) ?? [];
        $selectedReasonableAdjustmentsTutor = json_decode($alsReview->reasonable_adjustments_tutor) ?? [];
        $learnerComments = is_null($alsReview->learner_comments_to_assessor) ? null : json_decode($alsReview->learner_comments_to_assessor);
        $formData = !is_null($alsReview->form_data) ? json_decode($alsReview->form_data) : null;
        // dd($formData);
        return view('trainings.als_reviews.show', compact('training', 'alsReview', 'reasonableAdjustments', 'selectedReasonableAdjustmentsAssessor', 'selectedReasonableAdjustmentsTutor', 'learnerComments', 'formData'));
    }

    public function edit(TrainingRecord $training, AlsReview $alsReview, Request $request)
    {
        $reasonableAdjustments = AlsResonableAdjustmentLookup::all()->sortBy('id');
        $subaction = $request->input('subaction');
        if ($subaction == 'als_review_form_assessor') {
            abort_if(auth()->user()->id !== $alsReview->assessor, Response::HTTP_UNAUTHORIZED);
            $selectedReasonableAdjustments = json_decode($alsReview->reasonable_adjustments_assessor) ?? [];
            $formData = isset($alsReview->form_data) ? json_decode($alsReview->form_data) : '';
            $formUser = 'assessor';

            return view('trainings.als_reviews.als_review_form_assessor', compact('training', 'alsReview', 'reasonableAdjustments', 'selectedReasonableAdjustments', 'formData', 'formUser'));
        }
        if ($subaction == 'als_review_form_tutor') {
            abort_if(auth()->user()->id !== $alsReview->tutor, Response::HTTP_UNAUTHORIZED);
            $selectedReasonableAdjustments = json_decode($alsReview->reasonable_adjustments_assessor) ?? [];
            $formData = isset($alsReview->form_data) ? json_decode($alsReview->form_data) : '';
            $formUser = 'tutor';

            return view('trainings.als_reviews.als_review_form_tutor', compact('training', 'alsReview', 'reasonableAdjustments', 'selectedReasonableAdjustments', 'formData', 'formUser'));
        }
        if ($subaction == 'als_review_form_learner') {
            abort_if(auth()->user()->id !== $training->student_id, Response::HTTP_UNAUTHORIZED);
            $selectedReasonableAdjustmentsAssessor = json_decode($alsReview->reasonable_adjustments_assessor) ?? [];
            $selectedReasonableAdjustmentsTutor = json_decode($alsReview->reasonable_adjustments_tutor) ?? [];
            $learnerComments = is_null($alsReview->learner_comments_to_assessor) ? null : json_decode($alsReview->learner_comments_to_assessor);
            $formData = isset($alsReview->form_data) ? json_decode($alsReview->form_data) : '';
            $formUser = 'learner';

            return view(
                'trainings.als_reviews.als_review_form_learner',
                compact('training', 'alsReview', 'reasonableAdjustments', 'selectedReasonableAdjustmentsAssessor', 'selectedReasonableAdjustmentsTutor', 'learnerComments', 'formData', 'formUser')
            );
        }

        $assessorList[$training->primary_assessor] = $training->primaryAssessor->full_name;
        if ($training->secondary_assessor && $training->secondaryAssessor) {
            $assessorList[$training->secondary_assessor] = $training->secondaryAssessor->full_name;
        }
        $assessorList = LookupManager::getAssessors();

        $tutorsList = collect();
        if ($training->tutor && $training->tutorUser) {
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

        return view('trainings.als_reviews.edit', compact('training', 'alsReview', 'assessorList', 'tutorsList'));
    }

    public function update(TrainingRecord $training, AlsReview $alsReview, Request $request)
    {
        if ($request->has('basic_form')) {
            $alsReview->update([
                'assessor' => $request->assessor,
                'tutor' => $request->tutor,
                'planned_date' => $request->planned_date,
            ]);
            return redirect()->route('trainings.als_reviews.show', [$training, $alsReview])->with(['alert-success' => 'ALS Review has been updated successfully.']);
        }

        if (auth()->user()->isStudent()) {
            $alsReview->update([
                'learner_sign' => $request->has('learner_sign') ? 1 : 0,
                'learner_sign_date' => $request->has('learner_sign') ? now()->format('Y-m-d') : null,
            ]);
            return redirect()->route('trainings.als_reviews.show', [$training, $alsReview])->with(['alert-success' => 'ALS Review has been updated successfully.']);
        }

        if (auth()->user()->isAssessor()) {
            $validUpdateBy = 'assessor';
        } elseif (auth()->user()->isTutor()) {
            $validUpdateBy = 'tutor';
        } else {
            $validUpdateBy = 'assessor,tutor';
        }

        $request->validate(['update_by' => 'required|in:' . $validUpdateBy]);
        $request->validate([
            'adjustments' => ['required', 'array'],
            'adjustment_other' => [
                'nullable',
                Rule::requiredIf(function () use ($request) {
                    return in_array('99', $request->input('adjustments', []));
                }),
                'string',
                'max:2000'
            ],
        ], [
            'adjustment_other.required' => 'Please provide additional details about adjustments',
        ]);

        if ($request->update_by == 'assessor' || $request->update_by == 'tutor') {
            $updateData = [
                'date_of_review' => $request->date_of_review,
                'reasonable_adjustments_assessor' => json_encode($request->adjustments),
                'reasonable_adjustments_other_assessor' => $request->adjustment_other,
                'form_data' => json_encode(Arr::except($request->all(), ['_method', '_token', 'assessor_signed'])),
            ];
            if (auth()->user()->isAssessor()) {
                $alsReview->update(
                    array_merge(
                        $updateData,
                        ['assessor_sign' => $request->input('assessor_sign', 0), 'assessor_sign_date' => $request->has('assessor_sign') ? now()->format('Y-m-d') : null,]
                    )
                );
            }
            if (auth()->user()->isTutor()) {
                $alsReview->update(
                    array_merge(
                        $updateData,
                        ['tutor_sign' => $request->input('tutor_sign', 0), 'tutor_sign_date' => $request->has('tutor_sign') ? now()->format('Y-m-d') : null,]
                    )
                );
            }
        }

        /*
        $sessions = [];

        foreach ($request->all() as $key => $value) 
        {
            if (Str::startsWith($key, 'session_')) 
            {
                if (preg_match('/session_(\d+)_(saved_id|date|topic|support_detail)/', $key, $matches)) 
                {
                    $index = $matches[1];
                    $field = $matches[2];

                    $sessions[$index][$field] = $value;
                }
            }
        }

        foreach ($sessions as $index => $sessionData) 
        {
            $session = null;
            if($sessionData['saved_id'])
            {
                $session = $alsReview->sessions()->find($sessionData['saved_id']);
            }

            if(is_null($session))
            {
                $session = new AlsReviewSession(['als_review_id' => $alsReview->id]);
            }

            if(empty($sessionData['date']) && empty($sessionData['topic']) && empty($sessionData['support_detail']))
            {
                if($session->id)
                {
                    $session->delete();
                }
                continue;
            }

            $session->fill([
                'session_date' => $sessionData['date'] ?? null,
                'session_topics' => $sessionData['topic'] ?? null,
                'learner_support_detail' => $sessionData['support_detail'] ?? null,
                'session_type' => $request->update_by == 'tutor' ? AlsReviewSession::AlsSessionTypeTutor : AlsReviewSession::AlsSessionTypeAssessor,
            ])->save();            
        }
        */

        return redirect()->route('trainings.als_reviews.show', [$training, $alsReview])->with(['alert-success' => 'ALS Review has been saved successfully.']);
    }

    public function destroy(TrainingRecord $training, AlsReview $alsReview)
    {
        //$this->authorize('delete', [$alsReview, $training]);

        if (optional($alsReview->form)->locked()) {
            return back()
                ->withErrors('This ALS review is singed, it cannot be deleted.')
                ->withInput();
        }

        $alsReview->delete();

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'ALS Review has been deleted successfully.']);
    }

    public function saveLearnerFields(TrainingRecord $training, AlsReview $alsReview, Request $request)
    {
        $learnerAnswers = [
            'question1' => $request->question1,
            'question2' => $request->question2,
            'question3' => $request->question3,
            'question4' => $request->question4,
        ];
        $alsReview->update([
            'learner_comments_to_assessor' => json_encode($learnerAnswers),
            'learner_sign' => $request->has('learner_sign') ? 1 : 0,
            'learner_sign_date' => $request->has('learner_sign') ? now()->format('Y-m-d') : null,
        ]);
        return redirect()->route('trainings.als_reviews.show', [$training, $alsReview])->with(['alert-success' => 'ALS Review has been updated successfully.']);
    }
}
