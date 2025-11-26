<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserEvents\UserEvent;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserEventPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isActive();
    }

    public function create(User $user)
    {
        return $user->isActive() && ($user->isStaff() || $user->isStudent);
    }

    public function show(User $user, UserEvent $userEvent)
    {
        return $userEvent->user_id === $user->id
            || $userEvent->assign_iqa_id === $user->id
            || $userEvent->participants()->where('user_id', $user->id)->exists();
    }

    public function edit(User $user, UserEvent $userEvent)
    {
        return $userEvent->user_id === $user->id || $userEvent->participants()->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, UserEvent $userEvent)
    {
        return $user->isActive() && $userEvent->user_id === $user->id;
    }

    public function updateStatusByParticipant(User $user, UserEvent $userEvent)
    {
        return $user->isActive() && $userEvent->participants()->where('user_id', $user->id)->exists();
    }
}
