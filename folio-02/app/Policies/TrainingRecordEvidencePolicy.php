<?php

namespace App\Policies;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class TrainingRecordEvidencePolicy
{
    use HandlesAuthorization;

    public function show(User $user, TrainingRecordEvidence $evidence, TrainingRecord $training)
    {
        if( !in_array( $evidence->id, $training->evidences()->pluck('id')->toArray() ) )
        {
            return false;
        }

        if( $user->isStudent() )
        {
            return 
                $user->id === $training->student_id &&
                $evidence->tr_id === $training->id;
        }
        
        if( 
            $user->can('read-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER, UserTypeLookup::TYPE_MANAGER, UserTypeLookup::TYPE_SYSTEM_VIEWER]) 
        )
        {
            return $this->isInUserCaseload($user, $evidence->training_record);
        }

        if($user->isAdmin() && $user->can('read-training-record'))
        {
            return true;
        }

        if($user->user_type === UserTypeLookup::TYPE_EQA)
        {
            return true;
        }
        
        return false;
    }

    public function create(User $user, $training)
    {
        if( $user->isStudent() )
        {
            return $user->id === $training->student_id && $training->isEditableByStudent();
        }

        if( 
            $user->can('read-training-record') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
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

    public function studentValidation(User $user, TrainingRecordEvidence $evidence, TrainingRecord $training)
    {
        if(! $user->isStudent())
        {
            return false;
        }

        return 
            $user->id === $training->student_id &&
            $evidence->tr_id === $training->id && 
            $training->isEditableByStudent() && 
            ! $evidence->learner_declaration; 
    }

    public function assess(User $user, TrainingRecordEvidence $evidence, TrainingRecord $training)
    {
        if( !in_array( $evidence->id, $training->evidences()->pluck('id')->toArray() ) )
        {
            return false;
        }
        
        if( 
            $user->can('assess-evidence') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $evidence->training_record);
        }

        if($user->isAdmin() && $user->can('assess-evidence'))
        {
            return true;
        }
        
        return false;
    }
    
    public function iqa(User $user, TrainingRecordEvidence $evidence, TrainingRecord $training)
    {
        if( !in_array( $evidence->id, $training->evidences()->pluck('id')->toArray() ) )
        {
            return false;
        }
        
        if( 
            $user->can('iqa-assessment') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $evidence->training_record);
        }

        if($user->isAdmin() && $user->can('iqa-assessment'))
        {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, TrainingRecordEvidence $evidence, TrainingRecord $training)
    {
        if( !in_array($evidence->getOriginal('evidence_status'), [TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED, TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED]) )
        {
            return false;
        }

        if( !in_array( $evidence->id, $training->evidences()->pluck('id')->toArray() ) )
        {
            return false;
        }

        if( $user->isStudent() )
        {
            return 
                $user->id === $training->student_id &&
                $evidence->tr_id === $training->id && 
                $training->isEditableByStudent(); 
        }
        
        if( 
            $user->can('delete-evidence') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $evidence->training_record);
        }

        if($user->isAdmin() && $user->can('delete-evidence'))
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
            return $user->id === $trainingRecord->tutor || 
                in_array(
                    $user->id, $trainingRecord->portfolios()->whereNotNull('fs_tutor_id')->pluck('fs_tutor_id')->toArray()
                );
        }

        if( $user->user_type == UserTypeLookup::TYPE_VERIFIER )
        {
            return $user->id === $trainingRecord->verifier || 
                in_array(
                    $user->id, $trainingRecord->portfolios()->whereNotNull('fs_verifier_id')->pluck('fs_verifier_id')->toArray()
                );
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
