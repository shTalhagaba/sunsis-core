<?php

namespace App\Policies;

use App\Models\AssessorRiskAssessment\AssessorRiskAssessment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssessorRiskAssessmentPolicy 
{
    use HandlesAuthorization;
    
    public function index(User $user)
    {
        if($user->isVerifier() || $user->isAdmin() || $user->isAssessor())
        {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        if($user->isVerifier() || $user->isAdmin())
        {
            return true;
        }

        return false;
    }

    public function show(User $user, AssessorRiskAssessment $model)
    {
        if($user->isAdmin() || $user->id === $model->creator_id || ($user->isAssessor() && $user->id === $model->assessor_id && $model->completed === 1))
        {
            return true;
        }
        
        return false;
    }

    public function edit(User $user, AssessorRiskAssessment $model)
    {
        if($user->isAdmin() || $user->id === $model->creator_id)
        {
            return true;
        }
        
        return false;
    }

    public function destroy(User $user, AssessorRiskAssessment $model)
    {
        if($user->isAdmin() || $user->id === $model->creator_id)
        {
            return true;
        }
        
        return false;
    }
}