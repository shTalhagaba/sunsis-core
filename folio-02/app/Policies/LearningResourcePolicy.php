<?php

namespace App\Policies;

use App\Models\LearningResources\LearningResource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningResourcePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        if($user->isStaff())
        {
            return true;
        }
        
        return false;
    }

    public function edit(User $user, LearningResource $learningResource)
    {
        if($user->isAdmin())
        {
            return true;
        }
        elseif($user->isStaff() && $user->id == $learningResource->created_by)
        {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, LearningResource $learningResource)
    {
        if($user->isAdmin())
        {
            return true;
        }
        elseif($user->isStaff() && $user->id == $learningResource->created_by)
        {
            return true;
        }
        
        return false;
    }
}
