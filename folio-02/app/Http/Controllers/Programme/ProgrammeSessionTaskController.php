<?php

namespace App\Http\Controllers\Programme;

use App\Models\Programmes\ProgrammeDeliveryPlanSessionTask;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\PcCategoryLookup;
use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeDeliveryPlanSession;
use App\Rules\UniqueProgrammeSessionNumberRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgrammeSessionTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index()
    {
        $tasks = ProgrammeDeliveryPlanSessionTask::all();
        return response()->json($tasks);
    }

    public function create(Programme $programme, ProgrammeDeliveryPlanSession $session)
    {
        $this->authorize('create', Programme::class);

        $isTemplate = request()->query('is_template') == 1 ? 1 : 0;

        return view('programmes.sessions.tasks.create', compact('programme', 'session', 'isTemplate'));
    }

    public function store(Programme $programme, ProgrammeDeliveryPlanSession $session, Request $request, FileUploadService $fileUploadService)
    {

        $this->authorize('create', Programme::class);

        $request->validate([
            'dp_session_id' => 'required|numeric|in:' . $session->id,
            'title' => 'required|string',
            'details' => 'nullable|string',
            'status' => 'nullable|integer',
            'elements' => 'array',
        ]);

        $elements = $request->input('elements', []);

        $task = $session->tasks()->create([
            'title' => $request->input('title'),
            'details' => preg_replace('/[^\x00-\x7F]/', '', $request->input('details')),
            'is_template' => $request->input('is_template', 0),
            'status' => $request->input('status', 1),
            'created_by' => auth()->id()
        ]);

        if (!empty($elements)) {
            DB::table('programme_dp_task_pcs')->insert(array_map(function ($pcId) use ($task) {
                return ['task_id' => $task->id, 'pc_id' => $pcId];
            }, $elements));
        }

        if ($request->hasFile('programme_dp_task_files')) {
            $fileUploadService->uploadAndAttachMedia($request, $task, 'programme_dp_task_files');
        }

        return redirect()->route('programmes.show', $programme);
    }

    public function show(Programme $programme, ProgrammeDeliveryPlanSession $session, ProgrammeDeliveryPlanSessionTask $task)
    {
        abort_if(
            ($task->dp_session_id != $session->id),
            401
        );

        return view('programmes.sessions.tasks.show', compact('programme', 'session', 'task'));
    }

    public function edit(Programme $programme, ProgrammeDeliveryPlanSession $session, ProgrammeDeliveryPlanSessionTask $task)
    {
        $this->authorize('create', Programme::class);

        $selectedElements = $task->pcIds();
        $selectedElementsUnitIds = DB::table('programme_qualification_units')
            ->join('programme_qualification_unit_pcs', 'programme_qualification_units.id', '=', 'programme_qualification_unit_pcs.programme_qualification_unit_id')
            ->whereIn('programme_qualification_unit_pcs.id', $selectedElements)
            ->distinct()
            ->pluck('programme_qualification_units.id')
            ->toArray();

        $isTemplate = request()->query('is_template') == 1 ? 1 : 0;

        return view('programmes.sessions.tasks.edit', compact('programme', 'session', 'task', 'selectedElements', 'isTemplate', 'selectedElementsUnitIds'));
    }

    public function update(Programme $programme, ProgrammeDeliveryPlanSession $session, Request $request, ProgrammeDeliveryPlanSessionTask $task, FileUploadService $fileUploadService)
    {
        $this->authorize('create', Programme::class);

        $request->validate([
            'id' => 'required|numeric|in:' . $task->id,
            'dp_session_id' => 'required|numeric|in:' . $session->id,
            'title' => 'required|string',
            'details' => 'nullable|string',
            'status' => 'nullable|integer',
            'elements' => 'array',
        ]);

        $elements = $request->input('elements', []);

        $task->update([
            'title' => $request->input('title'),
            'details' => preg_replace('/[^\x00-\x7F]/', '', $request->input('details')),
            'status' => $request->input('status', 1),
            'updated_by' => auth()->id()
        ]);

        DB::table('programme_dp_task_pcs')->where('task_id', $task->id)->delete();
        if (!empty($elements)) {
            DB::table('programme_dp_task_pcs')->insert(array_map(function ($pcId) use ($task) {
                return ['task_id' => $task->id, 'pc_id' => $pcId];
            }, $elements));
        }

        if ($request->hasFile('programme_dp_task_files')) {
            $fileUploadService->uploadAndAttachMedia($request, $task, 'programme_dp_task_files');
        }

        return redirect()->route('programmes.show', $programme);
    }

    public function destroy(Programme $programme, ProgrammeDeliveryPlanSession $session, ProgrammeDeliveryPlanSessionTask $task)
    {
        $this->authorize('create', Programme::class);

        try {
            $trTask = TrainingDeliveryPlanSessionTask::where('pro_task_id', $task->id)
                ->where('status', TrainingDeliveryPlanSessionTask::STATUS_PENDING)->first();

            if ($trTask) {
                DB::table('tr_task_pcs')->where('task_id', $trTask->id)->delete();
                $trTask->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ':' . $e->getLine(), $e->getTrace());
        }

        $task->delete();

        return redirect()->route('programmes.show', $programme)->with(['alert-success' => 'Task has been deleted successfully.']);
    }

    private function getPcs(Programme $programme)
    {
        $elements = DB::table('programme_qualification_unit_pcs')
            ->join('programme_qualification_units', 'programme_qualification_unit_pcs.programme_qualification_unit_id', '=', 'programme_qualification_units.id')
            ->join('programme_qualifications', 'programme_qualification_units.programme_qualification_id', '=', 'programme_qualifications.id')
            ->where('programme_qualifications.programme_id', $programme->id)
            //->where('programme_qualifications.main', true)
            //->whereIn('programme_qualification_unit_pcs.category', [PcCategoryLookup::KSB_KNOWLEDGE, PcCategoryLookup::KSB_SKILLS, PcCategoryLookup::KSB_BEHAVIOURS])
            ->select(
                'programme_qualification_unit_pcs.id',
                DB::raw('CONCAT("[", programme_qualification_units.unique_ref_number, "] ", programme_qualification_unit_pcs.title) AS title'),
                'programme_qualification_unit_pcs.delivery_hours',
                'programme_qualification_unit_pcs.category',
                'programme_qualifications.title AS prog_qual_title'
            )
            ->orderBy('programme_qualifications.sequence')
            ->orderBy('programme_qualification_units.unit_sequence')
            ->orderBy('programme_qualification_unit_pcs.pc_sequence')
            ->get();

        return $elements;
    }
}
