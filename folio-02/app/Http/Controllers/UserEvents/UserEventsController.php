<?php

namespace App\Http\Controllers\UserEvents;

use App\Filters\UserEventFilters;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserEventRequest;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Student;
use App\Models\User;
use App\Models\UserEvents\UserEvent;
use App\Models\UserEvents\UserEventParticipant;
use App\Notifications\UserEvent\EventInvited;
use App\Notifications\UserEvent\RemovedFromEvent;
use App\Notifications\UserTask\TaskNotification;
use App\Services\Users\UserEventService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserEventsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, UserEventFilters $filters)
    {
        $this->authorize('index', UserEvent::class);

        $userId = auth()->user()->id;
        $events = UserEvent::filter($filters)
            ->with(['creator', 'participants'])
            ->where(function ($query) use ($userId) {
                return $query->where('user_id', $userId)
                    ->orWhereHas('participants', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    });
            })
            ->paginate(session('user_events_per_page', config('model_filters.default_per_page')));

        return view('user_events.index', compact('events', 'filters'));
    }

    public function diary(Request $request)
    {
        // if(request()->ajax())
        {
            $eventsCollection = collect();
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $type = $request->get("type"); // Get the type parameter

            $userId = auth()->user()->id;
            $query = UserEvent::whereDate('start', '>=', $start)
                ->whereDate('end', '<=', $end)
                ->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->orWhereHas('participants', function ($p) use ($userId) {
                            $p->where('user_id', $userId);
                        });

                    if (auth()->user()->isVerifier()) {
                        $q->orWhere('assign_iqa_id', $userId);
                    }
                });

            $userEvents = $query->orderBy('start', 'asc')->get(['id', 'start', 'end', 'description', 'event_status', 'title', 'event_type', 'user_id', 'color', 'task_type', 'type', 'task_status', 'assign_iqa_id']);

            foreach ($userEvents as $entry) {
                $creator = User::find($entry->user_id);
                $iqaUser = \App\Models\User::find($entry->assign_iqa_id);

                // Calculate number of days in this recurring event
                $start = Carbon::parse($entry->start);
                $end = Carbon::parse($entry->end);
                $days = $start->diffInDays($end) + 1; // include last day

                $actualStart = $start->format('Y-m-d\TH:i:s'); 
                $actualEnd = $end->format('Y-m-d\TH:i:s');  

                for ($d = 0; $d < $days; $d++) {
                    $date = $start->copy()->addDays($d);
                    $eventsCollection->push((object)[
                        'id' => $entry->id,
                        'title' => $entry->title,
                        'type' => $entry->type,
                        'start' => $date->format('Y-m-d') . 'T' . Carbon::parse($entry->start)->format('H:i:s'),
                        'start_time' => Carbon::parse($entry->start)->format('H:i:s'),
                        'end' => $date->format('Y-m-d') . 'T' . Carbon::parse($entry->end)->format('H:i:s'),
                        'description' => \Str::limit($entry->description, 500),
                        'event_type' => AppHelper::getUserEventsTypes($entry->event_type),
                        'task_type' => AppHelper::getUserTaskTypes($entry->task_type),
                        'event_status' => AppHelper::getUserEventsStatus($entry->event_status),
                        'task_status' => AppHelper::getUserTasksStatus($entry->task_status),
                        'assign_iqa' => $iqaUser ? $iqaUser->firstnames . ' ' . $iqaUser->surname : ' ',
                        'created_by' => optional($creator)->full_name . ' [' . optional($creator)->systemUserType->description . ']',
                        'backgroundColor' => $entry->color,
                        'allDay' => false,
                        'link' => route('user_events.show', $entry->id),
                        'actual_start' => $actualStart, // full original start datetime
                        'actual_end' => $actualEnd,     // full original end datetime
                    ]);
                }
            }

            if (auth()->user()->isVerifier()) {
                $verifier = auth()->user();

                $samplePlans = DB::table('iqa_plan_entries')
                    ->join('iqa_sample_plans', 'iqa_plan_entries.iqa_plan_id', '=', 'iqa_sample_plans.id')
                    ->join('tr', 'iqa_plan_entries.training_id', '=', 'tr.id')
                    ->join('users as students', 'tr.student_id', '=', 'students.id')
                    ->where('iqa_sample_plans.verifier_id', $verifier->id)
                    ->where('iqa_status', 'PLANNED')
                    ->where(function ($query) use ($start, $end) {
                        $query->whereBetween('iqa_plan_entries.reminder_date', [$start, $end])
                            ->orWhereBetween('iqa_plan_entries.planned_completion_date', [$start, $end]);
                    })
                    ->select([
                        'learning_aim_qan',
                        'learning_aim_title',
                        'unit_unique_ref_number',
                        'unit_owner_ref',
                        'planned_completion_date',
                        'students.firstnames',
                        'students.surname',
                        'iqa_sample_plans.created_by',
                        'iqa_sample_plans.id AS plan_id',
                        'tr.id AS training_id',
                        'portfolio_unit_id',
                        'iqa_plan_entries.id AS iqa_plan_entry_id',
                        'iqa_plan_entries.planned_completion_date',
                        'reminder_date',
                    ])
                    ->get();


                foreach ($samplePlans as $entry) {
                    $eventsCollection->push((object)[
                        'id' => $entry->iqa_plan_entry_id,
                        'start' => Carbon::parse($entry->reminder_date)->format('Y-m-d') . 'T09:00:00',
                        'end' => Carbon::parse($entry->reminder_date)->format('Y-m-d') . 'T12:00:00',
                        'title' => "Sample Plan: " . $entry->learning_aim_title,
                        'description' => "Sample Plan for " . $entry->firstnames . " " . $entry->surname . " (QAN: " . $entry->learning_aim_qan . ") - " .
                            "Unit: " . $entry->unit_unique_ref_number . " (" . $entry->unit_owner_ref . ")",
                        'event_type' => 'IQA Sample Plan',
                        'event_status' => 'Planned',
                        'created_by' => optional(User::find($entry->created_by))->full_name . ' [' . optional(User::find($entry->created_by))->systemUserType->description . ']',
                        'backgroundColor' => '#FF0000', // Red color for IQA Sample Plans
                        'allDay' => false,
                        'link' => route('trainings.unit.iqa.show', [
                            'training' => $entry->training_id,
                            'unit' => $entry->portfolio_unit_id,
                            'iqa_sample_id' => $entry->plan_id,
                            'iqa_entry_id' => $entry->iqa_plan_entry_id
                        ]),
                    ]);
                }
            }

            $eventsCollection = $eventsCollection->sortBy(function ($event) {
                return Carbon::parse($event->start)->timestamp;
            })->values();
            return response()->json($eventsCollection);
        }
    }

    private function addCaseloadCondition()
    {
        $relatedUserIds = [];
        $userId = auth()->user()->id;
        switch (auth()->user()->user_type) {
            case UserTypeLookup::TYPE_ADMIN:
                $relatedUserIds = User::withActiveAccess()
                    ->select('users.id')
                    ->pluck('id')
                    ->toArray();
                break;

            case UserTypeLookup::TYPE_ASSESSOR:
                $relatedUserIds = Student::WhereHas('training_records', function ($query) use ($userId) {
                    return $query->where(function ($q) use ($userId) {
                        return $q->where('tr.primary_assessor', '=', $userId)
                            ->orWhere('tr.secondary_assessor', '=', $userId);
                    });
                })
                    ->select(DB::raw("CONCAT(users.firstnames, ' ', users.surname) AS name"), "users.id")
                    ->orderBy('firstnames')
                    ->pluck('name', 'id')
                    ->toArray();
                break;

            case UserTypeLookup::TYPE_TUTOR:
                $trIds = DB::table('portfolios')->where('fs_tutor_id', $userId)->pluck('tr_id')->toArray();
                $relatedUserIds = Student::WhereHas('training_records', function ($query) use ($trIds, $userId) {
                    return $query->where('tr.tutor', '=', $userId)
                        ->orWhereIn('tr.id', $trIds);
                })
                    ->select(DB::raw("CONCAT(users.firstnames, ' ', users.surname) AS name"), "users.id")
                    ->orderBy('firstnames')
                    ->pluck('name', 'id')
                    ->toArray();
                break;

            case UserTypeLookup::TYPE_VERIFIER:
                $trIds = DB::table('portfolios')->where('fs_verifier_id', $userId)->pluck('tr_id')->toArray();
                $relatedUserIds = Student::WhereHas('training_records', function ($query) use ($trIds, $userId) {
                    return $query->where('tr.verifier', '=', $userId)
                        ->orWhereIn('tr.id', $trIds);
                })
                    ->select(DB::raw("CONCAT(users.firstnames, ' ', users.surname) AS name"), "users.id")
                    ->orderBy('firstnames')
                    ->pluck('name', 'id')
                    ->toArray();
                break;

            case UserTypeLookup::TYPE_STUDENT:
                $student = Student::find(auth()->user()->id);
                $primaryAssessors = $student->training_records()
                    ->select('tr.primary_assessor')
                    ->pluck('primary_assessor')
                    ->toArray();

                $secondaryAssessors = $student->training_records()
                    ->whereNotNull('secondary_assessor')
                    ->select('tr.secondary_assessor')
                    ->pluck('secondary_assessor')
                    ->toArray();

                $relatedUserIds = array_merge($primaryAssessors, $secondaryAssessors);
                break;

            case UserTypeLookup::TYPE_EMPLOYER_USER:
                $assessorIds = DB::table('employer_user_assessor')->where('employer_user_id', $userId)->pluck('assessor_id')->toArray();
                $relatedUserIds = Student::WhereHas('training_records', function ($query) use ($userId, $assessorIds) {
                    return $query->where('tr.employer_location', '=', $userId)
                        ->where(function ($q) use ($assessorIds) {
                            $q->whereIn('tr.primary_assessor', $assessorIds)
                                ->orWhereIn('tr.secondary_assessor', $assessorIds);
                        });
                })
                    ->select(DB::raw("CONCAT(users.firstnames, ' ', users.surname) AS name"), "users.id")
                    ->orderBy('firstnames')
                    ->pluck('name', 'id')
                    ->toArray();
                break;

            default:
                break;
        }

        return $relatedUserIds;
    }

    public function create(Request $request)
    {
        $this->authorize('create', UserEvent::class);

        $participantsSystemUsers = [];
        $participantsStudents = [];
        if (auth()->user()->isStaff()) {
            $participantsSystemUsers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->withActiveAccess()
                ->where('user_type', '!=', UserTypeLookup::TYPE_STUDENT)
                ->where('id', '!=', auth()->user()->id)
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();

            $participantsStudents = DB::table('users')
                ->whereIn('users.id', $this->addCaseloadCondition())
                ->select(DB::raw("CONCAT(users.firstnames, ' ', users.surname) AS name"), "users.id")
                ->orderBy('firstnames')
                ->pluck('name', 'id')->toArray();
        }

        $mode = 'create';

        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        return view('user_events.create', compact('participantsSystemUsers', 'mode', 'participantsStudents', 'verifiers'));
    }

    public function store(StoreUserEventRequest $request, UserEventService $userEventService)
    {
        $this->authorize('create', UserEvent::class);
        try {
            $event = $userEventService->create(auth()->user(), $request->all());
        } catch (Exception $ex) {

            //dd($ex);
            return back()
                ->with(['alert-danger' => 'Failed to create an event.']);
        }

        return redirect()
            ->route('user_events.index')
            ->with([
                'alert-success' => ($request->type === 'task'
                    ? 'Task has been created successfully.'
                    : 'Event has been created successfully.')
            ]);
    }

    public function show(UserEvent $event)
    {
        $this->authorize('show', $event);

        $event->load(['creator', 'participants']);

        return view('user_events.show', compact('event'));
    }

    public function edit(UserEvent $event)
    {
        $this->authorize('edit', $event);

        $participantsSystemUsers = [];
        $participantsStudents = [];
        if (auth()->user()->isStaff()) {
            $participantsSystemUsers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->withActiveAccess()
                ->where('user_type', '!=', UserTypeLookup::TYPE_STUDENT)
                ->where('id', '!=', auth()->user()->id)
                ->orderBy('firstnames')
                ->pluck('name', 'id')
                ->toArray();

            $participantsStudents = Student::whereIn('users.id', $this->addCaseloadCondition())
                ->select(DB::raw("CONCAT(users.firstnames, ' ', users.surname) AS name"), "users.id")
                ->orderBy('firstnames')
                ->pluck('name', 'id')->toArray();
        }

        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $mode = 'edit';
        return view('user_events.edit', compact(
            'participantsSystemUsers',
            'verifiers',
            'event',
            'mode',
            'participantsStudents'
        ));
    }

    public function update(UserEvent $event, StoreUserEventRequest $request, UserEventService $userEventService)
    {
        $this->authorize('edit', $event);

        $newStart = \Carbon\Carbon::parse($request->start_date . ' ' . $request->start_time)->format('Y-m-d H:i:s');
        $newEnd   = \Carbon\Carbon::parse($request->end_date . ' ' . $request->end_time)->format('Y-m-d H:i:s');

        // Fill new values (but don’t save yet)
        $event->fill([
            'title'       => $request->title,
            'description' => $request->description,
            'start'       => $newStart,
            'end'         => $newEnd,
            'assign_iqa_id' => $request->assign_iqa_id,
            'task_type' => $request->task_type
        ]);

        $dirty = $event->getDirty();

        try {
            $userEventService->update($event, $request->all(), $dirty);
        } catch (\Exception $ex) {

            return back()
                ->with(['alert-danger' => 'Failed to update event.']);
        }



        return redirect()
            ->route('user_events.index')
            ->with([
                'alert-success' => ($request->type === 'task'
                    ? 'Task has been updated successfully.'
                    : 'Event has been updated successfully.')
            ]);
    }

    public function destroy(UserEvent $event, UserEventService $userEventService)
    {
        $this->authorize('delete', $event);

        DB::beginTransaction();
        try {
            $userEventService->delete($event);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }

        $message = $event->type === 'event'
            ? 'Event has been deleted successfully.'
            : 'Task has been deleted successfully.';

        return redirect()
            ->route('user_events.index')
            ->with(['alert-success' => $message]);
    }

    public function updateStatusByParticipant(UserEvent $event, Request $request, UserEventService $userEventService)
    {
        $this->authorize('show', $event);

        $request->validate([
            'user_id' => 'required|numeric|in:' . auth()->user()->id,
            'event_id' => 'required|numeric|in:' . $event->id,
            'status' => 'required|numeric|in:2,3',
            'comments' => 'nullable|max:500',
        ]);

        $userEventParticipant = UserEventParticipant::where('user_id', $request->user_id)
            ->where('event_id', $event->id)
            ->first();

        $userEventParticipant->update([
            'status' => $request->status,
            'comments' => $request->comments,
        ]);

        $userEventService->sendInvitationFeedbackNotification($userEventParticipant);

        return redirect()
            ->back()
            ->with(['alert-success' => 'Information is saved successfully.']);
    }

    public function removeParticipant(UserEvent $event, Request $request)
    {
        $this->authorize('delete', $event);

        $validator = Validator::make($request->all(), [
            'event_id' => 'required|numeric|in:' . $event->id,
            'participant_id' => 'required|string',
            'tr_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $participantId = decrypt($request->participant_id);
            $trId = !empty($request->tr_id) ? decrypt($request->tr_id) : '';

            if ($trId == '') {
                $event->participants()->detach($participantId);
            } else {
                $event->participants()->wherePivot('tr_id', $trId)->detach($participantId);
            }

            $participant = User::find($participantId);
            $participant->notify(new RemovedFromEvent($event));
            AppHelper::cacheUnreadCountForUser($participant);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            return response()->json([
                'success' => false,
                'message' => [$ex->getMessage()]
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => [$ex->getMessage()]
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => 'Participant has been removed from this event.',
        ]);
    }

    public function addParticipant(UserEvent $event, Request $request)
    {
        $this->authorize('edit', $event);

        $validator = Validator::make($request->all(), [
            'event_id' => 'required|numeric|in:' . $event->id,
            'participant_id' => 'required|string',
            'tr_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        $participantId = 0;
        try {
            $participantId = decrypt($request->participant_id);
            $trId = !empty($request->tr_id) ? decrypt($request->tr_id) : '';

            if ($trId == '') {
                $event->participants()->attach($participantId);
            } else {
                $event->participants()->attach($participantId, ['tr_id' => $trId]);
            }

            $participant = User::find($participantId);
            $participant->notify(new EventInvited($event));
            AppHelper::cacheUnreadCountForUser($participant);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            return response()->json([
                'success' => false,
                'message' => [$ex->getMessage()]
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => [$ex->getMessage()]
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => 'Participant has been added to the event.',
        ]);
    }



    public function updateStatus(UserEvent $task, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|integer',
        ]);

        $status = (int) $validated['status'];
        $task->update(['task_status' => $status]);

        // Send notifications based on status
        if ($status === UserEvent::STATUS_COMPLETED) {
            $task->creator->notify(new TaskNotification($task, 'completed'));
            AppHelper::cacheUnreadCountForUser($task->creator);
        } elseif ($status === UserEvent::STATUS_SIGNOFF) {
            $task->assignedIqa->notify(new TaskNotification($task, 'signed_off'));
            AppHelper::cacheUnreadCountForUser($task->assignedIqa);
        }

        return response()->json(['status' => 'success']);
    }
}