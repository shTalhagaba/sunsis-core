<?php

namespace App\Policies;

use App\Models\FSAssessment\TestSession;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestSessionPolicy
{
    use HandlesAuthorization;

    public function create(User $user, TrainingRecord $training)
    {
        if( 
            $user->can('read-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR]) 
        )
        {
            return $this->isInUserCaseload($user, $training);
        }

        if($user->isAdmin() && $user->can('read-training-record'))
        {
            return true;
        }
        
        return false;
    }

    public function show(User $user, TestSession $testSession, TrainingRecord $training)
    {
        if( $testSession->tr_id !== $training->id )
        {
            return false;
        }

        if($user->isStaff() && !$user->can('read-training-record'))
        {
            return false;
        }

        if( $user->isStudent() )
        {
            return 
                $user->id === $training->student_id &&
                $testSession->tr_id === $training->id; 
        }

        if( 
            $user->can('read-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER, UserTypeLookup::TYPE_MANAGER]) 
        )
        {
            return $this->isInUserCaseload($user, $testSession->training);
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
