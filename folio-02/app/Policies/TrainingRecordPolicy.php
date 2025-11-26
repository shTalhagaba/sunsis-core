<?php

namespace App\Policies;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class TrainingRecordPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        if ($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER && $user->can('submenu-view-training-records')) {
            return true;
        }

        if ($user->user_type == UserTypeLookup::TYPE_EQA) {
            return true;
        }

        if ($user->user_type == UserTypeLookup::TYPE_QUALITY_MANAGER) {
            return true;
        }

        if ($user->isStaff() && $user->can('submenu-view-training-records')) {
            return true;
        }

        return false;
    }

    public function show(User $user, TrainingRecord $trainingRecord)
    {
        if ($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER && $user->can('submenu-view-training-records')) {
            $assessorIds = DB::table('employer_user_assessor')->where('employer_user_id', $user->id)->pluck('assessor_id')->toArray();

            return
                $user->employer->id === $trainingRecord->employer->id &&
                (
                    (in_array($trainingRecord->primary_assessor, $assessorIds) || in_array($trainingRecord->secondary_assessor, $assessorIds)) ||
                    ($trainingRecord->employer_user_id === auth()->user()->id)
                );
        }

        if ($user->user_type == UserTypeLookup::TYPE_EQA) {
            return true;
        }

        if ($user->user_type == UserTypeLookup::TYPE_STUDENT) {
            return $user->id === $trainingRecord->student_id;
        }

        if (
            $user->can('read-training-record') &&
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER, UserTypeLookup::TYPE_MANAGER])
        ) {
            return $this->isInUserCaseload($user, $trainingRecord);
        }

        if (($user->isAdmin() || $user->isQualityManager()) && $user->can('read-training-record')) {
            return true;
        }

        if ($user->user_type == UserTypeLookup::TYPE_SYSTEM_VIEWER && $user->can('read-training-record')) {
            return true;
        }

        return false;
    }

    public function edit(User $user, TrainingRecord $trainingRecord)
    {
        if (
            $user->can('update-training-record') &&
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER])
        ) {
            return $this->isInUserCaseload($user, $trainingRecord);
        }

        if (($user->isAdmin() || $user->isQualityManager()) && $user->can('update-training-record')) {
            return true;
        }

        return false;
    }

    public function delete(User $user, TrainingRecord $trainingRecord)
    {
        if (
            $user->can('delete-training-record') &&
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR, UserTypeLookup::TYPE_VERIFIER])
        ) {
            return $this->isInUserCaseload($user, $trainingRecord);
        }

        if ($user->isAdmin() && $user->can('delete-training-record')) {
            return true;
        }

        return false;
    }

    private function isInUserCaseload(User $user, TrainingRecord $trainingRecord)
    {
        if ($user->user_type == UserTypeLookup::TYPE_ASSESSOR) {
            return in_array($user->id, [$trainingRecord->primaryAssessor->id, optional($trainingRecord->secondaryAssessor)->id]);
        }

        if ($user->user_type == UserTypeLookup::TYPE_TUTOR) {
            return $user->id === $trainingRecord->tutor ||
                in_array(
                    $user->id,
                    $trainingRecord->portfolios()->whereNotNull('fs_tutor_id')->pluck('fs_tutor_id')->toArray()
                );
        }

        if ($user->user_type == UserTypeLookup::TYPE_VERIFIER) {
            return $user->id === $trainingRecord->verifier ||
                in_array(
                    $user->id,
                    $trainingRecord->portfolios()->whereNotNull('fs_verifier_id')->pluck('fs_verifier_id')->toArray()
                );
        }

        if ($user->user_type == UserTypeLookup::TYPE_MANAGER) {
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
