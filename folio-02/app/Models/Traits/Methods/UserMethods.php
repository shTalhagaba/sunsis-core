<?php

namespace App\Models\Traits\Methods;

use App\Helpers\AppHelper;
use App\Models\Address;
use App\Models\Lookups\UserTypeLookup;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

trait UserMethods
{
    public function isStaff()
    {
        return !in_array($this->user_type, [
            UserTypeLookup::TYPE_STUDENT,
            UserTypeLookup::TYPE_EMPLOYER_USER,
            UserTypeLookup::TYPE_EQA,
        ]);
    }

    public function previousLoginAt()
    {
        return optional($this->authentications()->skip(1)->first())->login_at;
    }

    public function previousLoginIp()
    {
        return optional($this->authentications()->skip(1)->first())->ip_address;
    }

    public function homeAddress()
    {
        $address = $this->addresses
            ->where('label', Address::LABEL_HOME)
            ->first();

        return is_null($address) ? new Address() : $address;
    }

    public function workAddress()
    {
        $address = $this->addresses
            ->where('label', Address::LABEL_WORK)
            ->first();

        return is_null($address) ? new Address() : $address;
    }

    public function isActive()
    {
        return $this->web_access == 1 ? true : false;
    }

    public function hasUserLoggedInAtLeastOnce()
    {
        return is_null($this->password_changed_at) ? false : true;
    }

    public function logout()
    {
        \Auth::logout();
    }

    public function isStaffUser()
    {
        return !in_array($this->user_type, [
            UserTypeLookup::TYPE_STUDENT,
            UserTypeLookup::TYPE_EMPLOYER_USER,
            UserTypeLookup::TYPE_EQA,
        ]);
    }

    public function isStudent()
    {
        return $this->user_type == UserTypeLookup::TYPE_STUDENT ? true : false;
    }

    public function isAssessor()
    {
        return $this->user_type == UserTypeLookup::TYPE_ASSESSOR ? true : false;
    }

    public function isAdmin()
    {
        return $this->user_type == UserTypeLookup::TYPE_ADMIN ? true : false;
    }

    public function isVerifier()
    {
        return $this->user_type == UserTypeLookup::TYPE_VERIFIER ? true : false;
    }

    public function isTutor()
    {
        return $this->user_type == UserTypeLookup::TYPE_TUTOR ? true : false;
    }

    public function isEmployerUser()
    {
        return $this->user_type == UserTypeLookup::TYPE_EMPLOYER_USER ? true : false;
    }

    public function isQualityManager()
    {
        return $this->user_type == UserTypeLookup::TYPE_QUALITY_MANAGER ? true : false;
    }

    // public function getAvatarUrlAttribute()
    // {
    //     $avatar_url = $this->gender == 'F' ? asset('images/avatars/default_female.png') : asset('images/avatars/default_male.png');
    //     if (!is_null($this->getMedia('avatars')->first())) {
    //         $avatar_url = $this->getFirstMediaUrl('avatars');
    //     }

    //     return $avatar_url;
    // }

    public function getAvatarUrlAttribute()
    {
        // Define default avatar paths
        $neutralAvatar = asset('images/avatars/default_neutral.jpg');
        $maleAvatar = asset('images/avatars/default_male.png');
        $femaleAvatar = asset('images/avatars/default_female.png');

        // Determine default avatar based on gender
        switch ($this->gender) {
            case 'M':
            case 'Male':
                $avatar_url = $maleAvatar;
                break;

            case 'F':
            case 'Female':
                $avatar_url = $femaleAvatar;
                break;

            case 'NB':
            case 'U':
            default:
                $avatar_url = $neutralAvatar;
                break;
        }

        // If user has uploaded an avatar, override the default
        $media = $this->getFirstMedia('avatars');

        if ($media) {
            Log::info($media->getFullUrl());
            $avatar_url = $media->getFullUrl();
        }

        return $avatar_url;
    }


    public function getSignatureAttribute()
    {
        $signature = '';
        if (!is_null($this->getMedia('signatures')->first())) {
            $signature = $this->getFirstMediaUrl('signatures');
        }

        return $signature;
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function resetPassword()
    {
        $password = AppHelper::generatePassword();

        $this->update([
            'password_changed_at' => null,
            'password' => bcrypt($password),
        ]);

        return $password;
    }

    public function updatePermissions($permissions = [])
    {
        if (empty($permissions)) {
            switch ($this->getOriginal("user_type")) {
                case UserTypeLookup::TYPE_ASSESSOR:
                    $role = Role::where('name', '=', 'Assessor')->first();
                    break;
                case UserTypeLookup::TYPE_ADMIN:
                    $role = Role::where('name', '=', 'Administrator')->first();
                    break;
                case UserTypeLookup::TYPE_TUTOR:
                    $role = Role::where('name', '=', 'Tutor')->first();
                    break;
                case UserTypeLookup::TYPE_VERIFIER:
                    $role = Role::where('name', '=', 'Verifier')->first();
                    break;
                case UserTypeLookup::TYPE_STUDENT:
                    $role = Role::where('name', '=', 'Student')->first();
                    break;
                case UserTypeLookup::TYPE_EQA:
                    $role = Role::where('name', '=', 'External Quality Assessor')->first();
                    break;
                case UserTypeLookup::TYPE_MANAGER:
                    $role = Role::where('name', '=', 'Manager')->first();
                    break;
                case UserTypeLookup::TYPE_QUALITY_MANAGER:
                    $role = Role::where('name', '=', 'Quality Manager')->first();
                    break;
                default:
                    # code...
                    break;
            }

            $permissions = isset($role) ? $role->permissions : [];
        }

        $this->syncPermissions($permissions);
    }
}
