<?php

namespace App\Services\Users;

use App\Events\NewUserHasBeenCreatedEvent;
use App\Helpers\AppHelper;
use App\Mail\NewUserPassword;
use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use App\Services\Address\UserAddressService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserService
{
    public function create(array $userData)
    {


        $password = AppHelper::generatePassword();

        $userData['password'] = bcrypt($password);
        $userData['web_access'] = array_key_exists('web_access', $userData) ? 1 : 0;
        $userData['email'] = $userData['primary_email'];
        $userData['username'] = strtolower($userData['username']);


        DB::beginTransaction();
        try {
            $userData['email'] = $userData['primary_email'];
            $user = User::create($userData);

            $permissions = [];
            if ($user->user_type == UserTypeLookup::TYPE_EMPLOYER_USER) {
                $permissions = [
                    'menu-students',
                    'submenu-view-students',
                    'menu-training-records',
                    'submenu-view-training-records',
                    'read-student',
                    'read-training-record',
                ];
            }

            $user->updatePermissions($permissions);

            $userAddressService = new UserAddressService();
            $userAddressService->saveAddresses($user, $userData);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        if (array_key_exists('send_login_details', $userData)) {
            event(new NewUserHasBeenCreatedEvent($user, $password));
        }

        return $user;
    }

    public function update(array $userData, User $user)
    {
        $userData['web_access'] = array_key_exists('web_access', $userData) ? 1 : 0;
        $userData['email'] = $userData['primary_email'];

        $userTypeChanged = false;
        if ($user->user_type != $userData['user_type']) {
            $userTypeChanged = true;
        }

        $user->update($userData);

        if ($userTypeChanged) {
            $user->updatePermissions();
        }

        $userAddressService = new UserAddressService();
        $userAddressService->saveAddresses($user, $userData);


        return $user;
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    public function updateUserUsername($newUsername, User $user)
    {
        $password = AppHelper::generatePassword();

        $user->update([
            'username' => strtolower($newUsername),
            'password' => bcrypt($password),
            'password_changed_at' => null,
        ]);

        $this->sendNewPasswordEmail($user, $password);

        return $user;
    }

    private function sendNewPasswordEmail(User $user, $password)
    {
        Mail::to($user->primary_email)
            ->later(
                now()->addMinutes(1),
                new NewUserPassword($user, $password)
            );
    }
}
