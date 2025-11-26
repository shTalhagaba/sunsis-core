<?php

namespace App\Http\Controllers\Training\DeliveryPlans;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use App\Models\Programmes\ProgrammeDeliveryPlanSessionTask;
use App\Models\Training\PortfolioPC;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\Training\TrainingRecord;
use App\Notifications\DeliveryPlanTask\TaskCreatedForLearner;
use App\Notifications\DeliveryPlanTask\TaskSubmittedByLearner;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingDeliveryPlanSessionTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $tasks = TrainingDeliveryPlanSessionTask::all();
        return response()->json($tasks);
    }

    public function create(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        abort_if(auth()->user()->isStudent(), 401);
        abort_if($training->id !== $session->tr_id, 401);

        $sessionKsbtitles = $session->ksb()->pluck('pc_title')->toArray();
        $sessionKsbSystemCodes = $session->ksb()->pluck('system_code')->toArray();

        $elements = $this->getSessionElements($training->id, $sessionKsbtitles, $sessionKsbSystemCodes);

        $selectedElements = [];

        return view('trainings.sessions.tasks.create', compact('training', 'session', 'elements', 'selectedElements'));
    }

    public function store(TrainingRecord $training, TrainingDeliveryPlanSession $session, Request $request, FileUploadService $fileUploadService)
    {
        abort_if(auth()->user()->isStudent(), 401);

        $validatedData = $request->validate([
            'tr_id' => 'required|numeric|in:' . $training->id,
            'dp_session_id' => 'required|numeric|in:' . $session->id,
            'title' => 'required|string',
            'start_date' => 'required|date',
            'complete_by' => 'required|date',
            'details' => 'nullable|string',
            // 'task_pcs' => 'array',
            'elements' => 'array',
        ]);

        $validatedData['details'] = preg_replace('/[^\x00-\x7F]/', '', $validatedData['details']);

        $task = TrainingDeliveryPlanSessionTask::create($validatedData + ['created_by' => auth()->id(), 'status' => TrainingDeliveryPlanSessionTask::STATUS_PENDING]);

        if (!empty($validatedData['elements'])) {
            DB::table('tr_task_pcs')->insert(array_map(function ($pcId) use ($task) {
                return ['task_id' => $task->id, 'pc_id' => $pcId];
            }, $validatedData['elements']));
        }

        if ($request->hasFile('tr_task_files')) {
            $fileUploadService->uploadAndAttachMedia($request, $task, 'tr_task_files');
        }

        $training->student->notify(new TaskCreatedForLearner($task));

        return redirect()->route('trainings.sessions.show', [$training, $session])->with(['alert-success' => 'Task has been created successfully.']);
    }

    public function show(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task)
    {
        abort_if(
            (
                $task->tr_id !== $training->id ||
                $task->dp_session_id != $session->id ||
                $session->tr_id != $training->id ||
                (auth()->user()->isStudent() && auth()->user()->id != $training->student->id)
            ),
            401
        );

        $linkedEvidenceId = DB::table('tr_task_evidence_links')
            ->where('tr_task_evidence_links.tr_task_id', $task->id)
            ->value('tr_evidence_id');

        return view('trainings.sessions.tasks.show', compact('training', 'session', 'task', 'linkedEvidenceId'));
    }

    public function edit(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task)
    {
        abort_if(auth()->user()->isStudent(), 401);

        $sessionKsbtitles = $session->ksb()->pluck('pc_title')->toArray();
        $sessionKsbSystemCodes = $session->ksb()->pluck('system_code')->toArray();

        $elements = $this->getSessionElements($training->id, $sessionKsbtitles, $sessionKsbSystemCodes);

        $selectedElements = $task->pcs();
        $selectedElementsUnitIds = DB::table('portfolio_units')
            ->join('portfolio_pcs', 'portfolio_units.id', '=', 'portfolio_pcs.portfolio_unit_id')
            ->whereIn('portfolio_pcs.id', $selectedElements)
            ->distinct()
            ->pluck('portfolio_units.id')
            ->toArray();

        return view('trainings.sessions.tasks.edit', compact('training', 'session', 'task', 'elements', 'selectedElements', 'selectedElementsUnitIds'));
    }

    public function update(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task, Request $request, FileUploadService $fileUploadService)
    {
        $validatedData = $request->validate([
            'id' => 'required|numeric|in:' . $task->id,
            'tr_id' => 'required|numeric|in:' . $training->id,
            'dp_session_id' => 'required|numeric|in:' . $session->id,
            'title' => 'required|string',
            'start_date' => 'required|date',
            'complete_by' => 'required|date',
            'details' => 'nullable|string',
            // 'task_pcs' => 'array',
            'elements' => 'array',
        ]);

        $validatedData['details'] = preg_replace('/[^\x00-\x7F]/', '', $validatedData['details']);

        $task->update($validatedData);

        DB::table('tr_task_pcs')->where('task_id', $task->id)->delete();
        if ($request->has('elements')) {
            DB::table('tr_task_pcs')->insert(array_map(function ($pcId) use ($task) {
                return ['task_id' => $task->id, 'pc_id' => $pcId];
            }, $validatedData['elements']));
        }

        if ($request->hasFile('tr_task_files')) {
            $fileUploadService->uploadAndAttachMedia($request, $task, 'tr_task_files');
        }

        return redirect()->route('trainings.sessions.show', [$training, $session])->with(['alert-success' => 'Task has been updated successfully.']);
    }

    public function destroy(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task)
    {
        DB::table('tr_task_pcs')->where('task_id', $task->id)->delete();
        $task->delete();

        return back()->with(['alert-success' => 'Task has been deleted successfully.']);
    }

    public function refresh(Request $request)
    {
        $validatedData = $request->validate([
            'tr_id' => 'required',
            'session_id' => 'required',
        ], [
            'tr_id.required' => 'Missing querystring argument: Training ID',
            'session_id.required' => 'Missing querystring argument: Training ID',
        ]);

        $task_ids = $request->input('task_ids');

        $tr = TrainingRecord::find($validatedData['tr_id']);
        $trSession = $tr->sessions()->where('id', $request->input('session_id'))->first();

        $programme = $tr->programme;

        $programmeSession = $tr->programme->sessions()->where('session_number', $trSession->session_number)->first();

        if (!$programmeSession) {
            return response()->json([
                'status' => "danger",
                'alert' => "Session not found in programme",
            ]);
        }

        $tasks = $programmeSession->tasks->when(!empty($task_ids), function ($q) use ($task_ids) {
            return $q->whereIn('id', $task_ids);
        });

        if (!$tasks->count()) {
            return response()->json([
                'status' => "danger",
                'alert' => "Tasks not found in programme session",
            ]);
        }

        DB::beginTransaction();
        try {

            foreach ($tasks as $task) {
                /** @var ProgrammeDeliveryPlanSessionTask $task */
                $trTask = $trSession->tasks()->where('tr_id', $trSession->tr_id)
                    ->where('pro_task_id', $task->id)->first();
                if (!$trTask) {
                    $trTask = $trSession->tasks()->create([
                        'tr_id' => $trSession->tr_id,
                        'pro_task_id' => $task->id,
                        'title' => $task->title,
                        'details' => $task->details,
                        'created_by' => auth()->id(),
                        'status' => TrainingDeliveryPlanSessionTask::STATUS_PENDING
                    ]);

                    if ($trTask) {
                        $pcIds = $task->pcIds();

                        DB::table('tr_task_pcs')->where('task_id', $task->id)->delete();
                        if (!empty($pcIds)) {
                            DB::table('tr_task_pcs')->insert(array_map(function ($pcId) use ($task) {
                                return ['task_id' => $task->id, 'pc_id' => $pcId];
                            }, $pcIds));
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'status' => "danger",
                'alert' => $ex->getMessage(),
            ]);
        }

        return response()->json([
            'status' => "success",
            'alert' => 'Delivery plan session tasks has been refreshed successfully.',
        ]);
    }

    public function saveLearnerWork(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task, Request $request, FileUploadService $fileUploadService)
    {
        $fileUploadOnly = $request->input('file_upload_only', false);
        if ($fileUploadOnly) {
            if ($request->files->count() === 0) {
                return back()->with(['alert-danger' => 'No file given.']);
            }

            $fileUploadRequest = new FileUploadRequest(array_keys($request->files->all()));
            $request->validate(
                $fileUploadRequest->rules(),
                $fileUploadRequest->messages()
            );

            $fileUploadService->uploadAndAttachMedia($request, $task, 'tr_task_evidence');

            return back()->with(['alert-success' => 'Evidence has been uploaded successfully.']);
        }

        $task->learner_comments = preg_replace('/[^\x00-\x7F]/', '', $request->comments);
        if ($request->has('learner_signed') && $request->learner_signed == 1) {
            $task->learner_signed_datetime = now();
            $task->status = TrainingDeliveryPlanSessionTask::STATUS_SUBMITTED;
            $task->history()->create([
                'tr_id' => $training->id,
                'comments' => preg_replace('/[^\x00-\x7F]/', '', $request->comments),
                'status' => TrainingDeliveryPlanSessionTask::STATUS_SUBMITTED,
                'created_by' => auth()->user()->id,
            ]);
            $training->primaryAssessor->notify(new TaskSubmittedByLearner($task));
        }
        $task->save();
        return back()->with(['alert-success' => 'Task has been saved successfully.']);
    }

    public function saveAssessment(TrainingRecord $training, TrainingDeliveryPlanSession $session, TrainingDeliveryPlanSessionTask $task, Request $request, FileUploadService $fileUploadService)
    {
        $fileUploadOnly = $request->input('file_upload_only', false);
        if ($fileUploadOnly) {
            if ($request->files->count() === 0) {
                return back()->with(['alert-danger' => 'No file given.']);
            }

            $fileUploadRequest = new FileUploadRequest(array_keys($request->files->all()));
            $request->validate(
                $fileUploadRequest->rules(),
                $fileUploadRequest->messages()
            );

            if ($request->has('feedback_file')) {
                $fileUploadService->uploadAndAttachMedia($request, $task, 'tr_task_feedback_file', ['feedback_file' => true]);
            } else {
                $fileUploadService->uploadAndAttachMedia($request, $task, 'tr_task_evidence', ['feedback_file' => false]);
            }

            return back()->with(['alert-success' => 'Evidence has been uploaded successfully.']);
        }

        $task->assessor_comments = $request->comments;
        if ($request->has('assessor_signed') && $request->assessor_signed == 1) {
            $task->assessor_signed_datetime = now();
            $task->status = $request->status;
            $task->history()->create([
                'tr_id' => $training->id,
                'comments' => $request->comments,
                'status' => $request->status,
                'created_by' => auth()->user()->id,
            ]);
        }
        $task->save();
        return back()->with(['alert-success' => 'Task has been saved successfully.']);
    }

    private function getSessionElements($trainingId, $sessionKsbtitles, $sessionKsbSystemCodes)
    {
        return DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', $trainingId)
            ->select('portfolio_pcs.id', 'portfolio_pcs.title', 'portfolio_pcs.delivery_hours', 'portfolio_pcs.category')
            // ->whereIn('portfolio_pcs.title', $sessionKsbtitles)
            ->whereIn('portfolio_pcs.system_code', $sessionKsbSystemCodes)
            ->orderBy('portfolio_pcs.pc_sequence')
            ->get();
    }
}