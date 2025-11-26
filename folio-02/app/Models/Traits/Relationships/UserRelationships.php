<?php

namespace App\Models\Traits\Relationships;

use App\Models\CalendarEvent;
use App\Models\LearningResources\LearningResource;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Notification;
use App\Models\Organisations\Location;
use App\Models\Organisations\Organisation;
use App\Models\Todo\TodoTask;
use App\Models\User;
use App\Models\UserEvents\UserEvent;

trait UserRelationships
{
    public function receivedNotifications()
    {
        return $this->hasMany(Notification::class, 'notifier_id');
    }

    public function calendar_events()
    {
        return $this->hasMany(CalendarEvent::class, 'user_id');
    }

    public function createdEvents()
    {
        return $this->hasMany(UserEvent::class, 'user_id');
    }

    public function participatedEvents()
    {
        return $this->belongsToMany(UserEvent::class, 'user_event_participants', 'user_id', 'event_id');
    }
    
    public function todo_tasks()
    {
        return $this->hasMany(TodoTask::class, 'belongs_to');
    }

    public function linkedUsers()
    {
        return $this->belongsToMany(User::class, 'user_links', 'user_id', 'linked_user_id')
                    ->withTimestamps();
    }

    public function linkedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_links', 'linked_user_id', 'user_id')
                    ->withTimestamps();
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'employer_location');
    }

    public function employer()
    {
        return $this->hasOneThrough(
            Organisation::class,
            Location::class,
            'id', // Foreign key on org_locations table...
            'id', // Foreign key on orgs table...
            'employer_location', // Local key on users table...
            'organisation_id' // Local key on org_locations table...
        );
    }

    public function systemUserType()
    {
        return $this->hasOne(UserTypeLookup::class, 'id', 'user_type');
    }

    public function learningResources()
    {
        return $this->belongsToMany(LearningResource::class, 'learning_resource_user')
                    ->withPivot('liked', 'bookmarked')
                    ->withTimestamps();
    }
}