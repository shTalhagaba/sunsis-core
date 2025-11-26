<?php

namespace App\Models;

use App\Models\Lookups\EthnicityLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Tags\Tag;
use App\Models\Training\TrainingRecord;
use Illuminate\Database\Eloquent\Builder;

class Student extends User
{
    protected $table = 'users';

    protected $guard_name = 'web';

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function training_records()
    {
        return $this->hasMany(TrainingRecord::class, 'student_id');
    }

    public function authentications()
    {
        return $this->morphMany(AuthenticationLog::class, 'authenticatable')
            ->latest('login_at');
    }

    public function latestAuth()
    {
        return $this->hasOne(AuthenticationLog::class, 'authenticatable_id')
            ->latest('login_at');
    }

    public function ethnicity()
    {
        return $this->hasOne(EthnicityLookup::class, 'id', 'ethnicity');
    }

    public function tags() 
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'Student')
            ->orderBy('order_column');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('student', function (Builder $builder) {
            $builder->where('user_type', UserTypeLookup::TYPE_STUDENT);
        });
    }

    public function getMorphClass()
    {
        return 'App\\Models\\User';
    }  
}
