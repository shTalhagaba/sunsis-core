<?php

namespace App\Http\Controllers\Todo;

use App\Filters\TodoTaskFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoTaskRequest;
use App\Models\Student;
use App\Models\Todo\TodoTask;

class TodoTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, TodoTaskFilters $filters)
    {
        $this->authorize('index', TodoTask::class);

        $tasks = TodoTask::filter($filters)
            ->with(['createdByUser', 'communications'])
            ->ofUser( auth()->user()->id )
            ->paginate(session('todo_tasks_per_page', config('model_filters.default_per_page')));

        return view('todo.index', compact('tasks', 'filters'));
    }

    public function create()
    {
        $this->authorize('create', TodoTask::class);

        $relatedLearners = self::getRelatedLearners();

        return view('todo.create', compact('relatedLearners'));
    }

    public function store(StoreTodoTaskRequest $request)
    {
        $this->authorize('create', TodoTask::class);

        $task = TodoTask::create([
            'title' => $request->title,
            'description' => $request->description,
            'belongs_to' => (! $request->has('belongs_to') || is_null($request->belongs_to)) ? auth()->user()->id : $request->belongs_to,
            'completed' => $request->input('completed', false),
        ]);

        return redirect()
            ->route('todo_tasks.show', $task)
            ->with(['alert-success' => 'Task has been created successfully.']);
    }

    public function show(TodoTask $task)
    {
        $this->authorize('show', $task);

        return view('todo.show', compact('task'));
    }

    public function edit(TodoTask $task)
    {
        $this->authorize('edit', $task);

        return view('todo.edit', compact('task'));
    }

    public function update(TodoTask $task, StoreTodoTaskRequest $request)
    {
        $this->authorize('edit', $task);

        $task->update([
            'title' => $request->input('title', $task->title),
            'description' => $request->input('description', $task->description),
            'completed' => $request->completed,
        ]);

        if($request->header('referer') === route('todo_tasks.index'))
        {
            return back()
                ->with(['alert-success' => 'Task has been updated successfully.']);
        }

        return redirect()
            ->route('todo_tasks.show', $task)
            ->with(['alert-success' => 'Task has been updated successfully.']);
    }

    public function destroy(TodoTask $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        if(request()->ajax())
        {
            return response()->json([
                'status' => 'success',
                'message' => 'Task has been deleted successfully.',
            ]);
        }

        return redirect()
            ->route('todo_tasks.index')
            ->with(['alert-success' => 'Task has been deleted successfully.']);
    }

    public function storeCommunication(TodoTask $task, Request $request)
    {
        $request->validate(['message' => 'required|max:255']);

        $this->authorize('edit', $task);   

        $task->communications()->create(['message' => $request->message, 'user_id' => auth()->user()->id]);

        return back()
            ->with(['alert-success' => 'Note added successfully.']);
    }

    private function getRelatedLearners()
    {
        $relatedLearners = [];
        if(auth()->user()->isAssessor())
        {
            $relatedLearners = Student::orderBy('firstnames')
                ->whereHas('training_records', function($q){
                    $q->where('tr.primary_assessor', '=', auth()->user()->id)
                        ->orWhere('tr.secondary_assessor', '=', auth()->user()->id);
                })
                ->get()
                ->pluck('full_name', 'id')
                ->toArray();
        }
        elseif(auth()->user()->isTutor())
        {
            $relatedLearners = Student::orderBy('firstnames')
                ->whereHas('training_records', function($q){
                    $q->where('tr.tutor', '=', auth()->user()->id);
                })                
                ->get()
                ->pluck('full_name', 'id')
                ->toArray();
        }

        return $relatedLearners;
    }    
}