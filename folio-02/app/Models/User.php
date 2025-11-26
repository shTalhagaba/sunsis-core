<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\MessageAccessible;
use App\Models\Tags\Tag;
use App\Models\Traits\Accessors\UserAccessor;
use App\Models\Traits\Methods\UserMethods;
use App\Models\Traits\Relationships\UserRelationships;
use App\Models\Traits\Scopes\UserScopes;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Traits\Filterable;
use OwenIt\Auditing\Auditable as AuditingAuditable;

class User extends Authenticatable implements HasMedia, Auditable
{
    use Notifiable,
        HasRoles,
        HasMediaTrait,
        AuditingAuditable,
        MessageAccessible,
        Filterable,
        UserMethods,
        UserRelationships,
        UserScopes,
        UserAccessor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstnames',
        'surname',
        'gender',
        'web_access',
        'avatar',
        'settings',
        'fb_id',
        'twitter_handle',
        'primary_email',
        'ni',
        'uln',
        'secondary_email',
        'date_of_birth',
        'ethnicity',
        'employer_location',
        'email',
        'username',
        'password',
        'user_type',
        'password_changed_at',
        'onefile_id',
        'support_contact_id',
        'rag_rating',
        'assessor_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_of_birth',
    ];

    public function sendPasswordResetNotification($token)
    {
        // overriding laravel framework method.
        $this->notify(new ResetPasswordNotification($token));
    }

    public function notifyAuthenticationLogVia()
    {
        return ['mail'];
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatars')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile();

        $this->addMediaCollection('signatures')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile();
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
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

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'User')
            ->orderBy('order_column');
    }
}
