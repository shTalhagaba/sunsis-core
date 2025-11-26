<?php

namespace App\Policies;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingReview;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class TrainingRecordReviewPolicy 
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
                UserTypeLookup::TYPE_MANAGER,
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

    public function show(User $user, TrainingReview $review, TrainingRecord $training)
    {
        if( !in_array( $review->id, $training->reviews()->pluck('id')->toArray() ) )
        {
            return false;
        }

        if( $user->isStudent() )
        {
            return 
                $user->id === $training->student_id &&
                $review->tr_id === $training->id;
        }
        
        if( 
            $user->can('read-training-record') && 
            in_array($user->user_type, [
                UserTypeLookup::TYPE_ASSESSOR, 
                UserTypeLookup::TYPE_TUTOR, 
                UserTypeLookup::TYPE_VERIFIER,
                UserTypeLookup::TYPE_EMPLOYER_USER,
                UserTypeLookup::TYPE_MANAGER,
                ]) 
        )
        {
            return $this->isInUserCaseload($user, $review->training);
        }

        if($user->isAdmin() && $user->can('read-training-record'))
        {
            return true;
        }

        if($user->user_type == UserTypeLookup::TYPE_SYSTEM_VIEWER && $user->can('read-training-record'))
        {
            return true;
        }
        
        return false;
    }

    public function edit(User $user, TrainingReview $review, TrainingRecord $training)
    {
        if( !in_array( $review->id, $training->reviews()->pluck('id')->toArray() ) )
        {
            return false;
        }

        if(optional($review->form)->locked())
        {
            return false;
        }
        
        if( 
            $user->can('update-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $review->training);
        }

        if($user->isAdmin() && $user->can('update-training-record'))
        {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, TrainingReview $review, TrainingRecord $training)
    {
        if( !in_array( $review->id, $training->reviews()->pluck('id')->toArray() ) )
        {
            return false;
        }
        
        if( 
            $user->can('delete-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $review->training);
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

	if( $user->user_type == UserTypeLookup::TYPE_MANAGER )
        {
            $ids = DB::table('user_caseload_accounts')
                ->where('caseload_account_id', $trainingRecord->verifier)
                ->orWhere('caseload_account_id', $trainingRecord->tutor)
                ->orWhere('caseload_account_id', $trainingRecord->primary_assessor)
                ->orWhere('caseload_account_id', $trainingRecord->secondary_assessor)
                ->pluck('user_id')
                ->toArray();
            return in_array($user->id, $ids);
        }
    }
}