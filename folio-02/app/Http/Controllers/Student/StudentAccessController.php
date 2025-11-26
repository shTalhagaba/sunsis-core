<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\UsernameRule;
use App\Services\Students\StudentService;

class StudentAccessController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function manageAccess(Student $student)
    {
        $this->authorize('manageAccess', Student::class);

        return view('students.manage-access', compact('student'));
    }

    public function updateStudentUsername(Request $request, Student $student, StudentService $studentService)
    {
        $this->authorize('manageAccess', Student::class);

        if($request->has('username') && $student->username == $request->username)
        {
            return back()
                ->with([
                    'alert-class' => 'alert-danger',
                    'alert-icon' => 'fa-exclamation-circle',
                    'message_username' => 'No change in the username.',
                ]);
        }

        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                new UsernameRule,
            ]
        ]);

        $student = $studentService->updateStudentUsername($validatedData['username'], $student);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_username' => 'Username is successfully updated.',
            ]);
    }

    public function updateWebAccess(Student $student, StudentService $studentService)
    {
        $this->authorize('manageAccess', Student::class);

        $student = $studentService->updateWebAccess($student);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_access' => 'Web access is updated.',
            ]);
    }

    public function resetPassword(Student $student, StudentService $studentService)
    {
        $this->authorize('manageAccess', Student::class);

        $studentService->resetPassword($student);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_reset_password' => 'Password is reset and sent to the student\'s email address.',
            ]);
    }

    public function sendWelcomeEmail(Student $student, StudentService $studentService)
    {
        $this->authorize('manageAccess', Student::class);

        $studentService->sendWelcomeEmail($student);

        return back()
            ->with([
                'alert-class' => 'alert-success',
                'alert-icon' => 'fa-check green',
                'message_send_welcome_email' => 'Welcome email is sent to the student\'s email address.',
            ]);
    }

}