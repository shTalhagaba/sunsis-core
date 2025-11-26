<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Programmes\Programme;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgrammePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        if($user->isStaff() && $user->can('submenu-programmes'))
        {
            return true;
        }

        return false;
    }

    public function export(User $user)
    {
        if($user->isStaff() && $user->can('submenu-programmes'))
        {
            return true;
        }

        return false;
    }
    
    public function show(User $user, Programme $programme)
    {
        if($user->isStaff() && $user->can('read-programme'))
        {
            return true;
        }
        
        return false;
    }

    public function create(User $user)
    {
        if($user->isStaff() && $user->can('create-programme'))
        {
            return true;
        }
        
        return false;
    }

    public function update(User $user)
    {
        if($user->isStaff() && $user->can('update-programme'))
        {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Programme $programme)
    {
        if($user->isStaff() && $user->can('delete-programme'))
        {
            return true;
        }
        
        return false;
    }
}
