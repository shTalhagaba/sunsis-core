<?php

namespace App\Policies;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Organisations\Organisation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        if($user->isStaff() && $user->can('menu-organisations'))
        {
            return true;
        }

        return false;
    }

    public function show(User $user, Organisation $organisation)
    {
        // if($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER)
        // {
        //     return $user->employer->id === $organisation->id;
        // }

        $readPermission = 'submenu-employers';
        if($organisation->isEmployer())
        {
            $readPermission = 'read-employer-organisation';
        }

        if($user->isStaff() && $user->can($readPermission))
        {
            return true;
        }
        
        return false;
    }
    
    public function createEmployer(User $user, $organisationType)
    {
        $readPermission = 'submenu-employers';
        if($organisationType === Organisation::TYPE_EMPLOYER)
        {
            $readPermission = 'read-employer-organisation';
        }

        if($user->isStaff() && $user->can($readPermission))
        {
            return true;
        }
        
        return false;
    }
    
    public function updateEmployer(User $user, Organisation $organisation)
    {
        $readPermission = 'submenu-employers';
        if($organisation->isEmployer())
        {
            $readPermission = 'update-employer-organisation';
        }

        if($user->isStaff() && $user->can($readPermission))
        {
            return true;
        }
        
        return false;
    }
}