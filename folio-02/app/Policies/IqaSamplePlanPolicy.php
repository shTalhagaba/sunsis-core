<?php

namespace App\Policies;

use App\Models\IQA\IqaSamplePlan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IqaSamplePlanPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        if (($user->isAdmin() || $user->isQualityManager() || $user->isVerifier()) && $user->can('submenu-iqa-sample-plans')) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        if (($user->isAdmin() || $user->isVerifier()) && $user->can('create-iqa-sample-plan')) {
            return true;
        }

        return false;
    }

    public function update(User $user, IqaSamplePlan $plan)
    {
        if ($plan->status != IqaSamplePlan::STATUS_SCHEDULED) {
            return false;
        }

        if ($user->isverifier()) {
            return $user->can('update-iqa-sample-plan') && $user->id === $plan->verifier_id;
        }

        if (($user->isAdmin() || $user->isQualityManager()) && $user->can('update-iqa-sample-plan')) {
            return true;
        }

        return false;
    }

    public function updateBasic(User $user, IqaSamplePlan $plan)
    {
        if ($plan->isCompleted()) {
            return false;
        }

        if ($user->isverifier()) {
            return $user->can('update-iqa-sample-plan') && $user->id === $plan->verifier_id;
        }

        if ($user->isAdmin() && $user->can('update-iqa-sample-plan')) {
            return true;
        }

        return false;
    }

    public function addUnitsAndTrainings(User $user, IqaSamplePlan $plan)
    {
        if ($plan->isCompleted()) {
            return false;
        }

        if ($user->isverifier()) {
            return $user->can('update-iqa-sample-plan') && $user->id === $plan->verifier_id;
        }

        if ($user->isAdmin() && $user->can('update-iqa-sample-plan')) {
            return true;
        }

        return false;
    }

    public function show(User $user, IqaSamplePlan $plan)
    {
        if ($user->isverifier()) {
            return $user->can('read-iqa-sample-plan') && $user->id === $plan->verifier_id;
        }

        if (($user->isAdmin() || $user->isQualityManager()) && $user->can('read-iqa-sample-plan')) {
            return true;
        }

        return false;
    }
}
