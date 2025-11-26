<?php

namespace App\Http\Controllers\Training\DeliveryPlans;

use App\Helpers\AppHelper;
use App\Helpers\SunesisHelper;
use App\Models\Programmes\ProgrammeDeliveryPlanSession;
use App\Models\Programmes\ProgrammeDeliveryPlanSessionTask;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\OtjTypeLookup;
use App\Models\Programmes\ProgrammeQualificationUnitPC;
use App\Models\Training\Otj;
use App\Models\Training\PortfolioPC;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingDeliveryPlanSessionKSB;
use App\Models\Training\TrainingRecord;
use App\Notifications\Otj\OtjLogCreated;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class TrainingDeliveryPlanSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function create(TrainingRecord $training)
    {
        $this->authorize('edit', $training);

        // $elements = DB::table('portfolio_pcs')
        //     ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
        //     ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
        //     ->where('portfolios.tr_id', $training->id)
        //     // ->where('portfolios.main', true)
        //     // ->whereIn('portfolio_pcs.category', [PcCategoryLookup::KSB_KNOWLEDGE, PcCategoryLookup::KSB_SKILLS, PcCategoryLookup::KSB_BEHAVIOURS])
        //     ->select('portfolio_pcs.id', 'portfolio_pcs.title', 'portfolio_pcs.delivery_hours', 'portfolio_pcs.category')
        //     ->orderBy('portfolio_pcs.pc_sequence')
        //     ->get();

        $selectedElements = [];

        return view('trainings.sessions.create', compact('training', 'selectedElements'));
    }

    public function store(TrainingRecord $training, Request $request)
    {
        $this->authorize('edit', $training);

        $request->validate([
            'session_number' => 'required|string',
            // 'session_sequence' => 'required|integer',
            'session_details_1' => 'nullable|string|max:5000',
            'session_details_2' => 'nullable|string|max:5000',
            'elements' => 'nullable|array',
            'session_type' => 'required|string',
        ]);

        $session = $training->sessions()
            ->create([
                'session_number' => $request->input('session_number'),
                'session_sequence' => $request->input('session_sequence'),
                'session_details_1' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_1')),
                'session_details_2' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_2')),
                'session_start_date' => $request->input('session_start_date'),
                'extra_session' => 1,
                'session_type' => $request->input('session_type'),
                'assessor_type' => $request->has('assessor_sign')
                    ? auth()->user()->assessor_type
                    : null,
                'assessor_id' => $request->has('assessor_sign')
                    ? auth()->user()->id
                    : null,
            ]);

        if (is_array($request->input('elements')) && count($request->input('elements')) > 0) {
            $portfolioPcs = PortfolioPC::whereIn('id', $request->input('elements'))
                ->get();

            foreach ($portfolioPcs as $portfolioPc) {
                $session->ksb()
                    ->create([
                        'sequence' => $portfolioPc->pc_sequence,
                        'pc_title' => $portfolioPc->title,
                        'delivery_hours' => $portfolioPc->delivery_hours,
                        'tr_pc_id' => $portfolioPc->id,
                        'system_code' => $portfolioPc->system_code,
                    ]);
            }
        }

        return redirect()->route('trainings.show', $training);
    }

    public function show(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        abort_if($training->id != $session->tr_id, Response::HTTP_UNAUTHORIZED, 'Bad Request');

        $sessionStartDateAudits = $session->audits()
            ->where('event', 'updated')
            ->where('old_values', 'LIKE', '%session_start_date%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trainings.sessions.show', compact('training', 'session', 'sessionStartDateAudits'));
    }

    public function edit(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        // TODO: this is not working as expected for PCs selection
        // Workaround: PCs grid is not shown during edit.

        $this->authorize('edit', $training);

        $elements = DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', $training->id)
            ->select('portfolio_pcs.id', 'portfolio_pcs.title', 'portfolio_pcs.delivery_hours', 'portfolio_pcs.category')
            ->orderBy('portfolio_pcs.pc_sequence')
            ->get();

        $selectedElements = $session->ksb()->pluck('id')->toArray();

        return view('trainings.sessions.edit', compact('training', 'session', 'elements', 'selectedElements'));
    }

    public function update(TrainingRecord $training, TrainingDeliveryPlanSession $session, Request $request)
    {
        if ($request->has('subaction') && $request->subaction == 'unlock_session') {
            if (!$session->isLocked()) {
                return back()->with(['alert-danger' => 'Session is already unlocked.']);
            }

            $session->update([
                'student_comments' => null,
                'assessor_comments' => null,
                'student_sign' => 0,
                'student_sign_date' => null,
                'assessor_sign' => 0,
                'assessor_sign_date' => null,
            ]);

            return back()->with(['alert-success' => 'Session has been unlocked.']);
        }

        $session->update([
            'session_number' => $request->input('session_number'),
            'session_sequence' => $request->input('session_sequence'),
            'session_details_1' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_1')),
            'session_details_2' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_2')),
            'session_start_date' => $request->input('session_start_date'),
            'session_type' => $request->input('session_type'),
            'assessor_type' => $request->has('assessor_sign')
                ? auth()->user()->assessor_type
                : null,
            'assessor_id' => $request->has('assessor_sign')
                ? auth()->user()->id
                : null,
        ]);

        return redirect()
            ->route('trainings.sessions.show', [$training, $session])
            ->with(['alert-success' => 'Session has been updated.']);
    }

    // refresh from the programme
    public function refresh(Request $request)
    {

        $validatedData = $request->validate([
            'tr_id' => 'required',
        ], [
            'tr_id.required' => 'Missing querystring argument: Training ID',
        ]);

        $tr = TrainingRecord::find($validatedData['tr_id']);

        $programme = $tr->programme;

        if ($programme->sessions()->count() == 0) {
            return response()->json([
                'alert_danger' => 'Programme does not have any sessions.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $sessionDates = $this->calculateSessionDates($tr->start_date, $tr->planned_end_date, $programme->sessions()->count());

        // pick up the title and id of all the pcs of this training record. The reason is that we will have to compare the title of programme_qualification_unit_pcs with the title of portfolio_pcs
        // currently this is the only way to match the pcs of the programme with the pcs of the training record
        // for now i am using system_code to match the pcs (as title is not unique) portfolio_pcs.system_code = programme_qualification_unit_pcs.system_code
        $pcsOfTrainingRecord = DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', $tr->id)
            ->get(['portfolio_pcs.id', 'portfolio_pcs.title', 'portfolio_pcs.system_code']);

        // the delivery hours should come from Sunesis (skills scan)
        $skillsScanData = collect();
        if ($tr->sunesis_id) {
            $skillsScanData = SunesisHelper::getRowsWithJoins(
                'ob_learner_ksb', // Main table
                [
                    ['ob_tr', 'ob_learner_ksb.tr_id', '=', 'ob_tr.id'],   // First JOIN
                    ['tr', 'ob_tr.sunesis_tr_id', '=', 'tr.id']           // Second JOIN
                ],
                [
                    ['tr.id', '=', $tr->sunesis_id]  // WHERE condition
                ],
                [
                    'ob_learner_ksb.id',
                    'ob_learner_ksb.evidence_title',
                    'ob_learner_ksb.del_hours'
                ]
            );
        }

        DB::beginTransaction();
        try {
            // first remove all the sessions and ksbs
            $existingUnsignedSessionIds = $tr->sessions()
                ->where('assessor_sign', 0)
                ->where('student_sign', 0)
                ->pluck('id')
                ->toArray();
            TrainingDeliveryPlanSessionKSB::whereIn('dp_session_id', $existingUnsignedSessionIds)->delete();

            $tr->sessions()
                ->where('assessor_sign', 0)
                ->where('student_sign', 0)
                ->delete();

            // now populate afresh
            $loop = 0;
            foreach ($programme->sessions()->orderBy('id')->get() as $programmeSession) {
                $trSession = $tr->sessions()->create([
                    'session_number' => $programmeSession->session_number,
                    'session_sequence' => $programmeSession->session_sequence,
                    'session_details_1' => preg_replace('/[^\x00-\x7F]/', '', $programmeSession->session_details_1),
                    'session_details_2' => preg_replace('/[^\x00-\x7F]/', '', $programmeSession->session_details_2),
                    'session_planned_hours' => $programmeSession->session_planned_hours,
                    'session_start_date' => $sessionDates[$loop],
                ]);
                $loop++;

                $decodedPcs = json_decode($programmeSession->session_pcs);
                if (!is_array($decodedPcs)) {
                    $programmeSessionPcs = collect([]);
                } else {
                    $programmeSessionPcs = ProgrammeQualificationUnitPC::whereIn('id', $decodedPcs)
                        ->orderBy('pc_sequence')
                        ->get();
                }

                foreach ($programmeSessionPcs as $programmeSessionPc) {
                    $matchedItem = $skillsScanData->firstWhere('evidence_title', $programmeSessionPc->title);
                    if ($matchedItem) {
                        $deliveryHours = $matchedItem->del_hours;
                    } else {
                        $deliveryHours = $programmeSessionPc->delivery_hours;
                    }
                    $trSession->ksb()->create([
                        'tr_pc_id' => optional($pcsOfTrainingRecord->where('title', $programmeSessionPc->title)->first())->id,
                        'system_code' => optional($pcsOfTrainingRecord->where('system_code', $programmeSessionPc->system_code)->first())->system_code,
                        'sequence' => $programmeSessionPc->pc_sequence,
                        'pc_title' => preg_replace('/[^\x00-\x7F]/', '', $programmeSessionPc->title),
                        // 'delivery_hours' => $programmeSessionPc->delivery_hours,
                        'delivery_hours' => $deliveryHours,
                    ]);
                }

                $this->refreshTasks($programmeSession, $trSession);
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'alert_danger' => $ex->getMessage(),
            ]);
        }

        return response()->json([
            'alert_success' => 'Delivery plan has been refreshed successfully.',
        ]);
    }

    protected function refreshTasks(ProgrammeDeliveryPlanSession $programmeSession, TrainingDeliveryPlanSession $trSession)
    {

        $tasks = $programmeSession->tasks;

        foreach ($tasks as $task) {
            /** @var ProgrammeDeliveryPlanSessionTask $task */
            $exist = $trSession->tasks()
                ->where('pro_task_id', $task->id)
                ->where('tr_id', $trSession->tr_id)
                ->exists();
            if (!$exist) {
                $trTask = $trSession->tasks()->create([
                    'pro_task_id' => $task->id,
                    'tr_id' => $trSession->tr_id,
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
    }

    public function showViewOrSign(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        abort_if(
            auth()->user()->isStudent() && auth()->user()->id !== $training->student_id,
            Response::HTTP_UNAUTHORIZED
        );

        abort_if(
            !in_array($session->id, $training->sessions()->pluck('id')->toArray()),
            Response::HTTP_UNAUTHORIZED
        );

        return view('trainings.sessions.view_or_sign', compact('training', 'session'));
    }

    // saving the form - learner and assessor signs
    public function saveViewOrSign(TrainingRecord $training, TrainingDeliveryPlanSession $session, Request $request, FileUploadService $fileUploadService)
    {
        abort_if(
            auth()->user()->isStudent() && auth()->user()->id !== $training->student_id,
            Response::HTTP_UNAUTHORIZED
        );

        abort_if(
            auth()->user()->isStudent() && !$training->isEditableByStudent(),
            Response::HTTP_UNAUTHORIZED
        );

        abort_if(
            !in_array($session->id, $training->sessions()->pluck('id')->toArray()),
            Response::HTTP_UNAUTHORIZED
        );

        if (!auth()->user()->isStudent()) {
            $request->validate([
                'actual_date' => 'required|date_format:"Y-m-d"',
                'session_start_time' => 'required|date_format:"H:i"',
                'session_end_time' => 'required|date_format:"H:i"|after:session_start_time',
                'assessor_comments' => 'required',
                'session_type' => 'required',
            ], [
                'session_start_time.required' => 'Please enter a start time.',
                'session_start_time.date_format' => 'Start time must be in the format HH:MM.',
                'session_end_time.required' => 'Please enter an end time.',
                'session_end_time.date_format' => 'End time must be in the format HH:MM.',
                'session_end_time.after' => 'End time must be after the start time.',
            ]);

            $fileUploadService->validate($request);
        }

        $session->update([
            'student_comments' => $request->input('student_comments', $session->student_comments),
            'assessor_comments' => preg_replace('/[^\x00-\x7F]/', '', $request->input('assessor_comments', $session->assessor_comments)),
            'student_sign' => $request->input('student_sign', $session->student_sign),
            'assessor_sign' => $request->input('assessor_sign', $session->assessor_sign),
            'student_sign_date' => $request->has('student_sign') && $session->student_sign_date == '' ? now()->format('Y-m-d') : $session->student_sign_date,
            'assessor_sign_date' => $request->has('assessor_sign') && $session->assessor_sign_date == '' ? now()->format('Y-m-d') : $session->assessor_sign_date,
            'actual_date' => $request->input('actual_date', $session->actual_date),
            'session_start_time' => $request->input('session_start_time', $session->session_start_time),
            'session_end_time' => $request->input('session_end_time', $session->session_end_time),
            'session_type' => $request->input('session_type', $session->session_type),
            'assessor_type' =>
            $request->has('assessor_sign')
                ? auth()->user()->assessor_type
                : null,
            'assessor_id' => $request->has('assessor_sign')
                ? auth()->user()->id
                : null,
        ]);

        if ($request->has('session_evidence')) {
            if ($session->media->count() > 0) {
                $session->media->first->delete();
            }

            $fileUploadService->uploadAndAttachMedia($request, $session, 'session_evidence');
        }

        if ($session->student_sign && $session->assessor_sign) {
            $this->createOtjAndNotifyLearner($training, $session);
            AppHelper::cacheUnreadCountForUser($training->student);
        }

        return redirect()
            ->route('trainings.sessions.show', [$training, $session]);
    }

    private function uploadOtjEvidence(UploadedFile $sessionEvidence, TrainingDeliveryPlanSession $session)
    {
        $ext = pathinfo(trim($sessionEvidence->getClientOriginalName()), PATHINFO_EXTENSION);
        $customFileName = md5(env('APP_KEY') . now() . $session->id) . '.' . $ext;

        $session->addMediaFromRequest('session_evidence')
            ->usingFileName($customFileName)
            ->withCustomProperties(['uploaded_by' => auth()->user()->id])
            ->toMediaCollection('session_evidences', 's3');
    }

    private function createOtjAndNotifyLearner(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        $otjDuration = null;
        if (!is_null($session->actual_date) && !is_null($session->session_start_time) && !is_null($session->session_end_time)) {
            $startTime = Carbon::parse($session->actual_date->format('Y-m-d') . ' ' . $session->session_start_time);
            $endTime = Carbon::parse($session->actual_date->format('Y-m-d') . ' ' . $session->session_end_time);
            $otjDuration = $endTime->diff($startTime)->format('%H:%I');
        }

        $otj = $training->otj()->create([
            'title' => substr($session->session_details_1, 0, 500),
            'date' => optional($session->actual_date)->format('Y-m-d') ?? null,
            'start_time' => $session->session_start_time,
            'duration' => $otjDuration,
            'type' => OtjTypeLookup::OTJ_TYPE_DP_SESSION,
            'status' => Otj::STATUS_AWAITING,
        ]);

        $training->student->notify(new OtjLogCreated($training, $otj, $session->id));
    }

    public function destroy(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        abort_if(!auth()->user()->isAssessor() && !auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        abort_if(
            !in_array($session->id, $training->sessions()->pluck('id')->toArray()),
            Response::HTTP_UNAUTHORIZED
        );

        if ($session->hasLearnerSigned() || $session->hasAssessorSigned()) {
            return back()->with(['alert-danger' => 'This session cannot be deleted.']);
        }

        $session->delete();

        return redirect()
            ->route('trainings.show', [$training])
            ->with(['alert-success' => 'Delivery plan session has been deleted successfully.']);
    }

    private function calculateSessionDates($startDate, $plannedEndDate, $totalSessions)
    {
        $startDate = Carbon::parse($startDate);
        $plannedEndDate = Carbon::parse($plannedEndDate);

        // Calculate total duration in days
        $totalDurationDays = $plannedEndDate->diffInDays($startDate);

        // Calculate new interval (days per session)
        $intervalDays = $totalDurationDays / $totalSessions;

        // Generate session dates
        $sessionDates = [];
        for ($i = 0; $i < $totalSessions; $i++) {
            $sessionDate = $startDate->copy()->addDays(round($i * $intervalDays));
            $sessionDates[] = $sessionDate->toDateString();
        }

        return $sessionDates;
    }
}
