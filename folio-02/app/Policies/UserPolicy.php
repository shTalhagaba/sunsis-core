<?php

namespace App\Policies;

use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view the models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function index(User $user)
    {
        if($user->isStaff() && $user->can('submenu-system-users'))
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can export the models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function export(User $user)
    {
        if($user->isStaff() && $user->can('submenu-system-users'))
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function show(User $user, User $model)
    {
        if($user->isStaff() && $user->can('read-system-user'))
        {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->isStaff() && $user->can('create-system-user'))
        {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        // support user cannot be allowed to update
        if($model->is_support)
        {
            return false;
        }

        if($user->isStaff() && $user->can('update-system-user'))
        {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        if($user->isStaff() && $user->can('delete-system-user'))
        {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can manage access for other users models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function manageUserAccess(User $user)
    {
        if($user->isStaff() && $user->can('update-system-user'))
        {
            return true;
        }
        
        return false;
    }
}
