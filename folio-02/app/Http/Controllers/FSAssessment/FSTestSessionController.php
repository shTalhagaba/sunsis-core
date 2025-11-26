<?php

namespace App\Http\Controllers\FSAssessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FSAssessment\Course;
use App\Models\FSAssessment\TestSession;
use App\Models\Training\TrainingRecord;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Exception;

class FSTestSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function create(TrainingRecord $training)
    {
        $this->authorize('create', [TestSession::class, $training]);

        $fsCoursesList = Course::select(DB::raw("CONCAT(fs_type, ' | ', title) AS course_title"), "id")
            ->orderBy('fs_type', 'asc')
            ->orderBy('title', 'asc')
            ->pluck('course_title', 'id')
            ->toArray();

        return view('trainings.fs_tests.create', compact('training', 'fsCoursesList'));
    }

    public function show(TrainingRecord $training, TestSession $fsTest)
    {
        $this->authorize('show', [$fsTest, $training]);

        $fsTest->load([
            'course',
            'responses',
            'assessments'
        ]);

        return view('trainings.fs_tests.show', compact('training', 'fsTest'));
    }

    public function startTest(TrainingRecord $training, TestSession $fsTest)
    {
        $this->authorize('show', [$fsTest, $training]);

        $fsTest->update([
            'status' => TestSession::STATUS_STARTED,
            'started_at' => now(),
        ]);

        return redirect()->route('trainings.fs_tests.show', [$training, $fsTest]);
    }

    private function attachCourse(TrainingRecord $training, $courseId, $completeBy, $attemptNo)
    {
        return $training->fsTestSessions()->create([
            'course_id' => $courseId,
            'attempt_no' => $attemptNo,
            'complete_by' => $completeBy,
            'status' => TestSession::STATUS_PENDING,
            'allocated_by' => auth()->user()->id,
        ]);
    }

    public function store(TrainingRecord $training, Request $request)
    {
        $this->authorize('create', [TestSession::class, $training]);

        $request->validate([
            'tr_id' => 'required|numeric|in:' . $training->id,
            'course_id' => 'required|numeric',
            'complete_by' => 'required|date_format:"Y-m-d"',
        ]);

        $fsTest = $this->attachCourse($training, $request->course_id, $request->complete_by, 1);

        if($request->has('next_action') && $request->next_action == 'add_more')
        {
            return back()->with(['alert-success' => 'Course has been allocated to the learner successfully.']);
        }

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Course has been allocated to the learner successfully.']);
    }

    public function destroy(TrainingRecord $training, TestSession $fsTest)
    {
        abort_if(!auth()->user()->isAdmin() && $fsTest->allocated_by != auth()->user()->id, Response::HTTP_UNAUTHORIZED);

        $fsTest->delete();

        return back()->with(['alert-success' => 'The record has been deleted successfully.']); 
    }

    public function saveTest(TrainingRecord $training, TestSession $fsTest, Request $request)
    {
        $this->authorize('show', [$fsTest, $training]);

        DB::beginTransaction();
        try
        {
            // $fsTest->responses()->delete();

            foreach($fsTest->course->questions()->where('active', true)->get() AS $question)
            {
                $answerCtrlName = "answer_for_question_{$question->id}";
                
                $existingAnswer = $fsTest->responses()->where('question_id', $question->id)->first();
                if($existingAnswer)
                {
                    $existingAnswer->update([
                        'answer_text' => $question->isDescriptive() ? $request->input($answerCtrlName) : null,
                        'answer_mcq_option_id' => $question->isMcq() ? $request->input($answerCtrlName) : null,    
                        'is_correct' => $question->isCorrectAnswer($request->input($answerCtrlName)),
                    ]);
                }
                else
                {
                    $fsTest->responses()->create([
                        'question_id' => $question->id,
                        'answer_text' => $question->isDescriptive() ? $request->input($answerCtrlName) : null,
                        'answer_mcq_option_id' => $question->isMcq() ? $request->input($answerCtrlName) : null,
                        'tr_id' => $training->id,
                        'is_correct' => $question->isCorrectAnswer($request->input($answerCtrlName)),
                    ]);
                }
            }

            DB::commit();
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        if($request->has('submission_status') && $request->submission_status == 'incomplete')
        {
            return back()->with(['alert-success' => 'Information is saved successfully.']);
        }

        $fsTest->update([
            'status' => TestSession::STATUS_SUBMITTED,
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('trainings.fs_tests.show', [$training, $fsTest])
            ->with(['alert-success' => 'Test has been saved successfully.']);
    }

    public function update(TrainingRecord $training, TestSession $fsTest, Request $request)
    {
        $this->authorize('create', [TestSession::class, $training]);
        
        $request->validate([
            'status' => 'required|string|in:' . TestSession::STATUS_APPROVED . ',' . TestSession::STATUS_NEEDS_REDO,
            'comments' => 'string|max:1000',
            'complete_by' => [
                'required_if:status,' . TestSession::STATUS_NEEDS_REDO,
            ],
        ]);

        $fsTest->update([
            'status' => $request->status,
            'score' => $fsTest->responses()->where('is_correct', 1)->count(),
        ]);

        $fsTest->assessments()->create([
            'assessor_id' => auth()->user()->id,
            'status' => $request->status,
            'comments' => $request->comments,
        ]);

        // if status is redo then we will create a new test for the learner
        if($fsTest->status == TestSession::STATUS_NEEDS_REDO)
        {
            $newFsTest = $this->attachCourse($training, $fsTest->course->id, $request->complete_by, $fsTest->attempt_no + 1);
        }

        return redirect()
            ->route('trainings.fs_tests.show', [$training, $fsTest])
            ->with(['alert-success' => 'Test has been saved successfully.']);
    }


}