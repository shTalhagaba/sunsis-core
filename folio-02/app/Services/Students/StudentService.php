<?php

namespace App\Services\Students;

use App\Helpers\AppHelper;
use App\Mail\NewStudentWelcomeEmail;
use App\Mail\NewUserPassword;
use App\Models\Address;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Student;
use App\Models\User;
use App\Services\Address\UserAddressService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StudentService
{
    public function create(array $studentData)
    {
        $password = AppHelper::generatePassword();

        $studentData['user_type'] = UserTypeLookup::TYPE_STUDENT;
        $studentData['password'] = bcrypt($password);
        $studentData['web_access'] = array_key_exists('web_access', $studentData) ? 1 : 0;
        $studentData['email'] = $studentData['primary_email'];
        $studentData['username'] = strtolower($studentData['username']);
        

        DB::beginTransaction();
        try 
        {
            $student = Student::create( $studentData );

            $student->updatePermissions();

            $userAddressService = new UserAddressService();
            $userAddressService->saveAddresses($student, $studentData);

            DB::commit();
        } 
        catch (Exception $ex) 
        {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        if(array_key_exists('send_login_details', $studentData))
        {
            $this->sendWelcomeEmail($student);
            $this->sendNewPasswordEmail($student, $password);
        }

        return $student;
    }

    public function update(array $studentData, Student $student)
    {
        $studentData['user_type'] = UserTypeLookup::TYPE_STUDENT;
        $studentData['web_access'] = array_key_exists('web_access', $studentData) ? 1 : 0;
        $studentData['email'] = $studentData['primary_email'];

        $student->update( $studentData );

        $userAddressService = new UserAddressService();
        $userAddressService->saveAddresses($student, $studentData);

        return $student;
    }

    public function delete(Student $student)
    {
        return $student->delete();
    }

    public function updateStudentUsername($newUsername, Student $student)
    {
        $password = AppHelper::generatePassword();

        $student->update([
            'username' => strtolower($newUsername),
            'password' => bcrypt($password),
            'password_changed_at' => null,
        ]);

        $this->sendNewPasswordEmail($student, $password);

        return $student;
    }

    public function updateWebAccess(Student $student)
    {
        $student->update([
            'web_access' => ! $student->isActive()
        ]);

        return $student;
    }

    public function resetPassword(Student $student)
    {
        $password = $student->resetPassword();

        $this->sendNewPasswordEmail($student, $password);

        return true;
    }

    public function generateUniqueUsername($firstname, $surname)
    {
        $i = 8;
        $username = AppHelper::generateUsername($firstname, $surname, $i);

        while(User::where('username', $username)->exists())
        {
            $username = AppHelper::generateUsername($firstname, $surname, ++$i);
        }

        return $username;
    }

    private function sendNewPasswordEmail(Student $student, $password)
    {
        Mail::to($student->primary_email)
            ->later(
                now()->addMinutes(1), 
                new NewUserPassword($student, $password)
            );
    }

    public function sendWelcomeEmail(Student $student)
    {
        Mail::to($student->primary_email)
            ->send(new NewStudentWelcomeEmail($student));
    }
}
