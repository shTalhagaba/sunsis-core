<?php

namespace App\Http\Controllers\FSAssessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\FSAssessment\Course;
use App\Models\FSAssessment\CourseQuestion;
use App\Models\FSAssessment\CourseQuestionOption;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;

class FSQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function create(Course $fsCourse)
    {
        return view('fs_assessment.questions.create', compact('fsCourse'));
    }

    public function store(Course $fsCourse, StoreQuestionRequest $request, FileUploadService $fileUploadService)
    {
        $this->additionalValidation($request);

        $mcqOptions = $request->input('options', []);
        $acceptableAnswers = $request->type === CourseQuestion::TYPE_DESCRIPTIVE ?
            $this->formatAcceptableAnswers($request->acceptable_answers) :
            [];

        $mcqOptions = $request->type === CourseQuestion::TYPE_MCQ ?
            $this->formatMcqOptions($request->correct_option, $request->input('options', [])) :
            [];

        $question = $fsCourse->questions()->create([
            'question_order' => $request->question_order,
            'type' => $request->type,
            'question_text' => $request->question_text,
            'correct_answer' => $request->type === CourseQuestion::TYPE_DESCRIPTIVE ? $request->correct_answer : null,
            'acceptable_answers' => $request->type === CourseQuestion::TYPE_DESCRIPTIVE ? array_values($acceptableAnswers) : null,
            'active' => $request->input('active', 1),
            'created_by'=> auth()->user()->id, 
        ]);

        if ($request->type === CourseQuestion::TYPE_MCQ) 
        {
            foreach ($mcqOptions as $questionOption) 
            {
                $question->question_options()->create([
                    'question_id' => $question->id,
                    'option_text' => $questionOption['text'],
                    'is_correct' => $questionOption['is_correct'] ? 1 : 0,
                ]);
            }
        }

        if ($request->hasFile('question_image')) 
        {
            $media = $fileUploadService->uploadAndAttachMedia($request, $question, 'question_image');
            if(isset($media[0]))
                $question->update(['file_name' => $media[0]->file_name]);
        }

        return redirect()
            ->route('fs_courses.show', $fsCourse)
            ->with(['alert-success' => 'Question has been created successfully.']);
    }

    private function hasBeenUsed(CourseQuestion $question)
    {
        return DB::table('learner_responses')->where('question_id', $question->id)->exists();
    }

    public function edit(Course $fsCourse, CourseQuestion $question)
    {
        $options = [];
        if($question->isMcq())
        {
            $index = 0;
            foreach($question->question_options AS $questionOption)
            {
                $options[++$index] = [
                    'id' => $questionOption->id,
                    'option_text' => $questionOption->option_text,
                    'is_correct' => $questionOption->is_correct,
                ];
            }
        }
        $usedInTests = $this->hasBeenUsed($question);

        return view('fs_assessment.questions.edit', compact('fsCourse', 'question', 'options', 'usedInTests'));
    }

    public function update(Course $fsCourse, CourseQuestion $question, StoreQuestionRequest $request, FileUploadService $fileUploadService)
    {
        $this->additionalValidation($request);

        $mcqOptions = $request->input('options', []);
        $acceptableAnswers = $request->type === CourseQuestion::TYPE_DESCRIPTIVE ?
            $this->formatAcceptableAnswers($request->acceptable_answers) :
            [];

        $mcqOptions = $request->type === CourseQuestion::TYPE_MCQ ?
            $this->formatMcqOptions($request->correct_option, $request->input('options', [])) :
            [];

        $question->update([
            'question_order' => $request->question_order,
            'type' => $request->type,
            'question_text' => $request->question_text,
            'correct_answer' => $request->type === CourseQuestion::TYPE_DESCRIPTIVE ? $request->correct_answer : null,
            'acceptable_answers' => $request->type === CourseQuestion::TYPE_DESCRIPTIVE ? array_values($acceptableAnswers) : null,
            'active' => $request->input('active', 1),
        ]);

        // Handle options for multiple-choice questions
        if ($request->type === CourseQuestion::TYPE_MCQ) 
        {
            $existingOptionIds = $question->question_options()->pluck('id')->toArray();
            $receivedOptionIds = collect($request->options)->pluck('id')->filter()->toArray();

            // Delete removed options
            $optionsToDelete = array_diff($existingOptionIds, $receivedOptionIds);
            CourseQuestionOption::whereIn('id', $optionsToDelete)->delete();

            // Add or update options
            foreach ($mcqOptions as $questionOption)  
            {
                if (isset($questionOption['id'])) 
                {
                    // Update existing option
                    CourseQuestionOption::where('id', $questionOption['id'])->update([
                        'option_text' => $questionOption['text'],
                        'is_correct' => isset($questionOption['is_correct']) && $questionOption['is_correct'] ? 1 : 0,
                    ]);
                } 
                else 
                {
                    // Create new option
                    CourseQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $questionOption['text'],
                        'is_correct' => isset($questionOption['is_correct']) && $questionOption['is_correct'] ? 1 : 0,
                    ]);
                }
            }
        } 
        else 
        {
            // If question type is descriptive, remove all previous options
            $question->question_options()->delete();
        }

        if ($request->hasFile('question_image'))
        {
            if($question->media->count() > 0)
            {
                $question->media->first->delete();
            }

            $media = $fileUploadService->uploadAndAttachMedia($request, $question, 'question_image');
            if(isset($media[0]))
                $question->update(['file_name' => $media[0]->file_name]);
        }

        return redirect()
            ->route('fs_courses.show', $fsCourse)
            ->with(['alert-success' => 'Question has been updated successfully.']);
    }

    public function destroy(Course $fsCourse, CourseQuestion $question)
    {
        if(! auth()->user()->isAdmin() && auth()->user()->id !== $question->created_by)
        {
            return back()->with(['alert-danger' => 'You cannot delete questions created by other users.']);
        }

        if($this->hasBeenUsed($question))
        {
            return back()->with(['alert-danger' => 'This question has already been used in the tests, so it cannot be deleted.']);
        }

        $question->delete();

        return redirect()
            ->route('fs_courses.show', $fsCourse)
            ->with(['alert-success' => 'Question has been deleted successfully.']);
    }

    private function additionalValidation(Request $request)
    {
        $rules = [];
        if($request->type === CourseQuestion::TYPE_DESCRIPTIVE)
        {
            $rules = ['correct_answer' => 'required|string|max:255'];
        }
        else if($request->type === CourseQuestion::TYPE_MCQ)
        {
            $rules = [
                'options' => 'required|array|min:2', 
                'correct_option' => 'required|numeric',
                'options.*.text' => 'required_with:options|string',
            ];
        }

        $request->validate($rules);
    }

    private function formatAcceptableAnswers($input = [])
    {
        return array_filter($input, function($value) {
            return !is_null($value) && $value !== '';
        });
    }
    
    private function formatMcqOptions($correctOption, $mcqOptions = [])
    {
        foreach ($mcqOptions as $index => &$option) 
        {
            $option['is_correct'] = $index == $correctOption;
        }
        return $mcqOptions;
    }
}
