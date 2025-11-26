<?php

namespace App\Policies;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\Portfolio;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPortfolioPolicy
{
    use HandlesAuthorization;

    public function create(User $user, $training)
    {
        if( 
            $user->can('update-training-record') && 
            in_array($user->user_type, [
                UserTypeLookup::TYPE_ASSESSOR, 
                UserTypeLookup::TYPE_TUTOR, 
                UserTypeLookup::TYPE_VERIFIER,
                ]) 
        )
        {
            return $this->isInUserCaseload($user, $training);
        }

        if($user->isAdmin() && $user->can('update-training-record'))
        {
            return true;
        }
        
        return false;
    }

    public function edit(User $user, Portfolio $portfolio, TrainingRecord $training)
    {
        if( !in_array( $portfolio->id, $training->portfolios()->pluck('id')->toArray() ) )
        {
            return false;
        }
        
        if( 
            $user->can('update-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $training);
        }

        if($user->isAdmin() && $user->can('update-training-record'))
        {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Portfolio $portfolio, TrainingRecord $training)
    {
        if( !in_array( $portfolio->id, $training->portfolios()->pluck('id')->toArray() ) )
        {
            return false;
        }
        
        if( 
            $user->can('delete-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $training);
        }

        if($user->isAdmin() && $user->can('delete-training-record'))
        {
            return true;
        }
        
        return false;
    }

    private function isInUserCaseload(User $user, TrainingRecord $trainingRecord)
    {
        if( $user->user_type == UserTypeLookup::TYPE_ASSESSOR )
        {
            return in_array($user->id, [$trainingRecord->primaryAssessor->id, optional($trainingRecord->secondaryAssessor)->id]);
        }

        if( $user->user_type == UserTypeLookup::TYPE_TUTOR )
        {
            return $user->id === $trainingRecord->tutor;
        }

        if( $user->user_type == UserTypeLookup::TYPE_VERIFIER )
        {
            return $user->id === $trainingRecord->verifier;
        }

        if( $user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER )
        {
            return $user->employer_location === $trainingRecord->location->id;
        }
    }
}
