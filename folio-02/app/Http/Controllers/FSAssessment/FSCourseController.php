<?php

namespace App\Http\Controllers\FSAssessment;

use App\Filters\FSCourseFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FSAssessment\Course;
use App\Models\FSAssessment\CourseQuestion;
use App\Models\FSAssessment\TestSession;
use Illuminate\Support\Facades\DB;

class FSCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, FSCourseFilters $filters)
    {
        $fsCourses = Course::filter($filters)
            ->paginate(session('fs_courses_per_page', config('model_filters.default_per_page')));

        return view('fs_assessment.fs_courses.index', compact('fsCourses', 'filters'));
    }

    public function create()
    {
        return view('fs_assessment.fs_courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'fs_type' => 'required|string|max:15',
            'details' => 'nullable|string|max:800',
            'video_link' => 'nullable|url|max:2000',
        ]);

        $fsCourse = Course::create([
            'title' => $request->title,
            'fs_type' => $request->fs_type,
            'details' => $request->details,
            'video_link' => $request->video_link,
            'created_by' => auth()->user()->id,
        ]);

        return redirect()
            ->route('fs_courses.show', $fsCourse)
            ->with(['alert-success' => 'Course has been created successfully.']);
    }

    public function show(Course $fsCourse)
    {
        return view('fs_assessment.fs_courses.show', compact('fsCourse'));
    }

    private function hasBeenUsed(Course $fsCourse)
    {
        return DB::table('test_sessions')
            ->where('status', '!=', TestSession::STATUS_PENDING)
            ->where('course_id', $fsCourse->id)
            ->exists();
    }

    public function edit(Course $fsCourse)
    {
        $usedInTests = $this->hasBeenUsed($fsCourse);

        return view('fs_assessment.fs_courses.edit', compact('fsCourse', 'usedInTests'));
    }

    public function update(Course $fsCourse, Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'fs_type' => 'required|string|max:15',
            'details' => 'nullable|string|max:800',
            'video_link' => 'nullable|url|max:2000',
        ]);

        $fsCourse->update([
            'title' => $request->title,
            'fs_type' => $request->fs_type,
            'details' => $request->details,
            'video_link' => $request->video_link,
            'created_by' => auth()->user()->id,
        ]);

        return redirect()
            ->route('fs_courses.show', $fsCourse)
            ->with(['alert-success' => 'Course has been updated successfully.']);
    }

    public function destroy(Course $fsCourse)
    {
        if($this->hasBeenUsed($fsCourse))
        {
            return back()->with(['alert-danger' => 'This course has already been completed by learners, so it cannot be deleted.']);
        }

        if ($fsCourse->questions()->exists())
        {
            return back()->with(['alert-danger' => 'Course has questions, delete aborted.']);
        }

        if(! auth()->user()->isAdmin() && auth()->user()->id !== $fsCourse->created_by)
        {
            return back()->with(['alert-danger' => 'You cannot delete courses created by other users.']);
        }

        $fsCourse->delete();

        return redirect()
            ->route('fs_courses.index')
            ->with(['alert-success' => 'Course has been deleted successfully.']);
    }

}