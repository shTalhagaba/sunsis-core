<?php

namespace App\Policies;

use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class StudentPolicy
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
        if($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER && $user->can('submenu-view-students'))
        {
            return true;
        }

        if($user->isStaff() && $user->can('submenu-view-students'))
        {
            return true;
        }

        return false;
    }
    
    public function export(User $user)
    {
        if($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER && $user->can('submenu-view-students'))
        {
            return true;
        }

        if($user->isStaff() && $user->can('submenu-view-students'))
        {
            return true;
        }

        return false;
    }

    public function show(User $user, Student $student)
    {
        if($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER && $user->can('submenu-view-students'))
        {
            $assessorIds = DB::table('employer_user_assessor')->where('employer_user_id', $user->id)->pluck('assessor_id')->toArray();
            $trainingsPrimaryAssessorIds = $student->training_records()->select('tr.primary_assessor')->distinct()->pluck('tr.primary_assessor')->toArray();
            $trainingsSecondaryAssessorIds = $student->training_records()->whereNotNull('tr.secondary_assessor')->select('tr.secondary_assessor')->distinct()->pluck('secondary_assessor')->toArray();
            $trainingAssessorIds = array_merge($trainingsPrimaryAssessorIds, $trainingsSecondaryAssessorIds);
            $studentTrIds = $student->training_records()->where('employer_user_id', auth()->user()->id)->pluck('id')->toArray();

            return 
                $user->employer->id === $student->employer->id && 
                (
                    count( array_intersect($assessorIds, $trainingAssessorIds) ) > 0 || count($studentTrIds) > 0
                )
                ;
        }
        
        if($user->isStudent())
        {
            return $user->id === $student->id;
        }

        if( 
            $user->can('read-student') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $student);
        }

        if($user->isStaff() && $user->can('read-student'))
        {
            return true;
        }
        
        return false;
    }
    
    public function create(User $user)
    {
        if($user->isStaff() && $user->can('create-student'))
        {
            return true;
        }
        
        return false;
    }
    
    public function store(User $user)
    {
        if($user->isStaff() && $user->can('create-student'))
        {
            return true;
        }
        
        return false;
    }

    public function edit(User $user, Student $student)
    {
        if( 
            $user->can('update-student') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $student);
        }

        if($user->isStaff() && $user->can('update-student'))
        {
            return true;
        }
        
        return false;
    }

    public function update(User $user, Student $student)
    {
        if( 
            $user->can('update-student') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER]) 
        )
        {
            return $this->isInUserCaseload($user, $student);
        }

        if($user->isStaff() && $user->can('update-student'))
        {
            return true;
        }
        
        return false;
    }

    public function manageAccess(User $user)
    {
        if($user->isAdmin() && $user->can('update-student'))
        {
            return true;
        }
        
        return false;
    }

    public function destroy(User $user)
    {
        if($user->isAdmin() && $user->can('delete-student') && $user->can('delete-training-record'))
        {
            return true;
        }

        return false;
    }

    private function isInUserCaseload(User $user, Student $student)
    {
        if( $user->user_type == UserTypeLookup::TYPE_ASSESSOR )
        {
            $primaryAssessorIds = $student->training_records()->pluck('primary_assessor')->toArray();
            $secondaryAssessorIds = $student->training_records()->pluck('secondary_assessor')->toArray();
            $assessorIds = array_merge($primaryAssessorIds, $secondaryAssessorIds);

            return in_array($user->id, $assessorIds);
        }

        if( $user->user_type == UserTypeLookup::TYPE_TUTOR )
        {
            $tutorIds = $student->training_records()->pluck('tutor')->toArray();
	        if( in_array($user->id, $tutorIds) )
            {
                return true;
            }

            // check portfolios
            foreach($student->training_records AS $training)
            {
                foreach($training->portfolios AS $portfolio )
                {
                    if($portfolio->fs_tutor_id === $user->id)
                    {
                        return true;
                    }
                }
            }
        }

        if( $user->user_type == UserTypeLookup::TYPE_VERIFIER )
        {
            $verifierIds = $student->training_records()->pluck('verifier')->toArray();
            if( in_array($user->id, $verifierIds) )
            {
                return true;
            }

            // check portfolios
            foreach($student->training_records AS $training)
            {
                foreach($training->portfolios AS $portfolio )
                {
                    if($portfolio->fs_verifier_id === $user->id)
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
