<?php

namespace App\Services\Users;

use App\Helpers\AppHelper;
use App\Models\User;
use App\Models\UserEvents\UserEvent;
use App\Models\UserEvents\UserEventParticipant;
use App\Notifications\UserEvent\EventAccepted;
use App\Notifications\UserEvent\EventCancelled;
use App\Notifications\UserEvent\EventDeclined;
use App\Notifications\UserEvent\EventDeleted;
use App\Notifications\UserTask\TaskNotification;
use Illuminate\Support\Arr;

class UserEventService
{
    public function create(User $user, array $eventData)
    {
        $type   = strtolower(Arr::get($eventData, 'type', 'event'));
        $events = [];

        // Common fields
        $baseData = [
            'title'       => $eventData['title'],
            'start'       => $eventData['start_date'] . ' ' . $eventData['start_time'],
            'end'         => $eventData['end_date'] . ' ' . $eventData['end_time'],
            'event_type'  => $eventData['event_type'],
            'task_type'   => $eventData['task_type'],
            'description' => $eventData['description'],
            'color'       => $eventData['color'],
            'personal'    => $eventData['personal'],
            'location'    => $eventData['location'],
        ];

        if ($type === 'task' && !empty($eventData['assign_iqa_id'])) {
            // Multiple tasks (one per IQA)
            foreach ($eventData['assign_iqa_id'] as $iqaId) {
                $taskData = $baseData + [
                    'event_status' => null,
                    'task_status'  => Arr::get($eventData, 'task_status'),
                    'type'         => 'task',
                    'assign_iqa_id' => $iqaId,
                ];

                $event = $user->createdEvents()->create($taskData);
                $events[] = $event;

                // Notify assigned IQA
                if ($event && $event->assignedIqa) {
                    $event->assignedIqa->notify(new TaskNotification($event, 'assigned'));
                    AppHelper::cacheUnreadCountForUser($event->assignedIqa);
                }
            }
        } else {
            // Single event or single task
            $eventDataSingle = $baseData + [
                'event_status' => $type === 'event' ? Arr::get($eventData, 'event_status') : null,
                'task_status'  => $type === 'task'  ? Arr::get($eventData, 'task_status')  : null,
                'type'         => $type,
                'assign_iqa_id' => null,
            ];

            $event = $user->createdEvents()->create($eventDataSingle);
            $events[] = $event;
        }

        // Return single event or array (depending on need)
        return count($events) === 1 ? $events[0] : $events;
    }


    public function update(UserEvent $event, array $eventData, $dirty)
    {
        $fromStatus = $event->event_status;
        $type       = strtolower(Arr::get($eventData, 'type', 'event'));

        $baseData = [
            'title'       => $eventData['title'],
            'start'       => $eventData['start_date'] . ' ' . $eventData['start_time'],
            'end'         => $eventData['end_date'] . ' ' . $eventData['end_time'],
            'event_type'  => $eventData['event_type'],
            'task_type'   => $eventData['task_type'],
            'description' => $eventData['description'],
            'color'       => $eventData['color'],
            'personal'    => $eventData['personal'],
            'location'    => $eventData['location'],
        ];

        $events = [];

        if ($type === 'task' && !empty($eventData['assign_iqa_id'])) {

            // Get existing assigned IQA IDs
            $existingIqas = $event->assignedIqa ? [$event->assignedIqa->id] : [];

            foreach ($eventData['assign_iqa_id'] as $iqaId) {
                if (in_array($iqaId, $existingIqas)) {
                    // Update existing event for this IQA
                    $updateData = $baseData + [
                        'type'          => 'task',
                        'assign_iqa_id' => $iqaId,
                        'task_status'   => Arr::get($eventData, 'task_status', $event->task_status),
                    ];

                    $event->update($updateData);
                    $this->handleNotifications($event, $dirty);
                    $events[] = $event;
                } else {
                    // Create new event for new IQA
                    try {
                        $newEvent = UserEvent::create($baseData + [
                            'type'          => 'task',
                            'assign_iqa_id' => $iqaId,
                            'task_status'   => 1,
                            'user_id'       => auth()->id(), // <-- add this
                        ]);
                    } catch (\Exception $e) {
                    }
                    $events[] = $newEvent;

                    if ($newEvent->assignedIqa) {
                        $newEvent->assignedIqa->notify(new TaskNotification($newEvent, 'created'));
                        AppHelper::cacheUnreadCountForUser($newEvent->assignedIqa);
                    }
                }
            }
        } else {
            // Non-task event or task without IQA
            $updateData = $baseData + [
                'event_status'  => $type === 'event' ? Arr::get($eventData, 'event_status') : null,
                'type'          => $type,
                'assign_iqa_id' => $type === 'task' ? Arr::get($eventData, 'assign_iqa_id') : null,
                'task_status'   => $type === 'task' ? Arr::get($eventData, 'task_status', $event->task_status) : null,
            ];

            $event->update($updateData);
            $events[] = $event;
        }

        // Notify participants if event is cancelled
        $toStatus = $event->event_status;
        if ($fromStatus == UserEvent::STATUS_BOOKED && $toStatus == UserEvent::STATUS_CANCELLED) {
            foreach ($event->participants as $participant) {
                $participant->notify(new EventCancelled($event));
                AppHelper::cacheUnreadCountForUser($participant);
            }
        }

        return count($events) === 1 ? $events[0] : $events;
    }


    public function delete(UserEvent $event)
    {
        foreach ($event->participants as $participant) {
            $participant->notify(new EventDeleted($event));
            AppHelper::cacheUnreadCountForUser($participant);
        }

        $event->delete();
    }

    public function sendInvitationFeedbackNotification(UserEventParticipant $eventParticipant)
    {
        if ($eventParticipant->status == UserEventParticipant::STATUS_ACCEPTED) {
            $eventParticipant->event->creator->notify(new EventAccepted($eventParticipant));
        } elseif ($eventParticipant->status == UserEventParticipant::STATUS_DECLINED) {
            $eventParticipant->event->creator->notify(new EventDeclined($eventParticipant));
        }
        AppHelper::cacheUnreadCountForUser($eventParticipant->event->creator);
    }

    private function handleNotifications(UserEvent $event, array $dirty): void
    {
        if (empty($dirty) || !$event->assignedIqa) {
            return;
        }

        $type = $event->wasChanged('assign_iqa_id') ? 'assigned' : 'updated';
        $event->assignedIqa->notify(new TaskNotification($event, $type));
        AppHelper::cacheUnreadCountForUser($event->assignedIqa);
    }
}