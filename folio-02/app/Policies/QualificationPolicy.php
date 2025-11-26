<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Qualifications\Qualification;
use Illuminate\Auth\Access\HandlesAuthorization;

class QualificationPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        if($user->isStaff() && $user->can('submenu-view-qualifications'))
        {
            return true;
        }

        return false;
    }

    public function export(User $user)
    {
        if($user->isStaff() && $user->can('submenu-view-qualifications'))
        {
            return true;
        }

        return false;
    }
    
    public function show(User $user, Qualification $qualification)
    {
        if($user->isStaff() && $user->can('read-qualification'))
        {
            return true;
        }
        
        return false;
    }

    public function create(User $user)
    {
        if($user->isStaff() && $user->can('create-qualification'))
        {
            return true;
        }
        
        return false;
    }

    public function update(User $user)
    {
        if($user->isStaff() && $user->can('update-qualification'))
        {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Qualification $qualification)
    {
        if($user->isStaff() && $user->can('delete-qualification'))
        {
            return true;
        }
        
        return false;
    }
}
