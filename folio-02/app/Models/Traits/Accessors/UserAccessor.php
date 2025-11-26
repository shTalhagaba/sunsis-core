<?php

namespace App\Models\Traits\Accessors;

trait UserAccessor
{
    public function getFullNameAttribute()
    {
        return "{$this->firstnames} {$this->surname}";
    }

    public function getFrmResetPasswordEmailAttribute()
    {
        return "{$this->email}";
    }
}