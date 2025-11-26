<?php

namespace App\Policies;

use App\Models\Todo\TodoTask;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoTaskPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        return $user->isActive();
    }

    public function create(User $user)
    {
        return $user->isActive();
    }

    public function show(User $user, TodoTask $task)
    {
        return $task->belongs_to === $user->id || $task->created_by === $user->id;
    }
    
    public function edit(User $user, TodoTask $task)
    {
        return $task->belongs_to === $user->id || $task->created_by === $user->id;
    }
    
    public function delete(User $user, TodoTask $task)
    {
        return $task->created_by === $user->id;
    }
}
