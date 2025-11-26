<?php

namespace App\Models\Traits\Scopes;

use App\Models\Lookups\UserTypeLookup;

trait UserScopes
{
    public function scopeStudents($query)
    {
        return $query->where('user_type', UserTypeLookup::TYPE_STUDENT);
    }

    public function scopeStaffUsers($query)
    {
        return $query
            ->where('user_type', '!=', UserTypeLookup::TYPE_STUDENT)
            ->where('user_type', '!=', UserTypeLookup::TYPE_EMPLOYER_USER)
            ->where('user_type', '!=', UserTypeLookup::TYPE_EQA)
            ->where('is_support', '!=', 1);
    }

    public function scopeExcludingStudents($query)
    {
        return $query
            ->where('user_type', '!=', UserTypeLookup::TYPE_STUDENT)
            ->where('is_support', '!=', 1);
    }

    public function scopeWithActiveAccess($query)
    {
        return $query->where('web_access', 1);
    }

    public function scopeWithInActiveAccess($query)
    {
        return $query->where('web_access', 0);
    }
}