<?php

namespace App\Services\Profile;

use App\Models\Address;
use Illuminate\Http\UploadedFile;

class ProfileService
{
    public function uploadAvatar(UploadedFile $avatar, $user)
    {
        $ext = pathinfo(trim($avatar->getClientOriginalName()), PATHINFO_EXTENSION);
        $customFileName = md5(env('APP_KEY') . now() . $user->id) . '.' . $ext;

        $user->addMediaFromRequest('avatar')
            ->usingFileName($customFileName)
            ->toMediaCollection('avatars', 'users_avatars');
    }

    public function update(array $profielData, $user)
    {
        $user->update($profielData);
    }
}
