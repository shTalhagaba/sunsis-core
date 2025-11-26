<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        abort(404);
        // return view('auth.passwords.email');
    }

    /*
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['frm_reset_password_username' => 'required|string|max:50']);

        $inputUsername = $request->frm_reset_password_username;

        $user = User::where('username', $inputUsername)->first();

        if(is_null($user))
        {
            return $this->notRegistered($request);
        }

        if (!is_null($user) && !$user->isActive())
        {
            return $this->sendResetLinkInActiveUserResponse($request);
        }
        elseif (!is_null($user) && !$user->hasUserLoggedInAtLeastOnce())
        {
            return $this->sendResetLinkNotLoggedInAtLeastOnceResponse($request);
        }
        else
        {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            // $response = $this->broker()->sendResetLink(['email' => $request->frm_reset_password_email]);
            
            $response = Password::broker()->sendResetLink(
                ['username' => $inputUsername, 'email' => $user->primary_email]
            );

            return $response == Password::RESET_LINK_SENT
                        ? $this->sendResetLinkResponse($request, $response)
                        : $this->sendResetLinkFailedResponse($request, $response);
        }
    }
    */

    public function sendResetLinkEmail(Request $request)
    {
        // Check if too many attempts were made
        $this->checkTooManyAttempts($request);

        $request->validate([
            'frm_reset_password_username' => 'required|string|max:50',
            'frm_reset_password_email' => 'required|string|max:255',
        ]);

        $givenUsername = $request->frm_reset_password_username;
        $givenEmail = $request->frm_reset_password_email;

        $user = User::query()
            ->where('username', $givenUsername)
            ->where(function($query) use ($givenEmail){
                $query->where('email', $givenEmail)
                    ->orWhere('primary_email', $givenEmail)
                    ->orWhere('secondary_email', $givenEmail);
            })
            ->first();

        if(is_null($user))
        {
            return $this->notRegistered($request);
        }

        if (!is_null($user) && !$user->isActive())
        {
            return $this->sendResetLinkInActiveUserResponse($request);
        }
        elseif (!is_null($user) && !$user->hasUserLoggedInAtLeastOnce())
        {
            return $this->sendResetLinkNotLoggedInAtLeastOnceResponse($request);
        }
        else
        {
            $token = Str::random(60); // Generate a unique token

            DB::table('password_resets')->updateOrInsert(
                ['username' => $givenUsername],  // Ensure only one record per email
                [
                    'email' => $user->email,  
                    'username' => $user->username, 
                    'token' => bcrypt($token),
                    'created_at' => Carbon::now()
                ]
            );

            $user->notify(new ResetPassword($token));

            return back()->with('status', 'Password reset link sent!');
        }
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
                ->withInput($request->only('frm_reset_password_username'))
                ->withErrors(['frm_reset_password_username' => trans($response)]);
    }

    protected function sendResetLinkInActiveUserResponse(Request $request)
    {
        return back()
                ->withInput($request->only('frm_reset_password_username'))
                ->withErrors(['frm_reset_password_username' => 'Your account is not active. Please contact system administrator.']);
    }

    protected function sendResetLinkNotLoggedInAtLeastOnceResponse(Request $request)
    {
        return back()
                ->withInput($request->only('frm_reset_password_username'))
                ->withErrors(['frm_reset_password_username' => 'You must have received an email containing your password. If you haven\'t received, contact system administrator.']);
    }

    protected function notRegistered(Request $request)
    {
        return back()
                ->withInput()
                ->withErrors(['frm_reset_password_username' => 'Username and Email combination not found.']);
    }

    protected function checkTooManyAttempts(Request $request)
    {
        $limiter = app(RateLimiter::class);
        $key = 'password-reset:' . Str::lower($request->input('username'));

        if ($limiter->tooManyAttempts($key, 5)) { 
            throw ValidationException::withMessages([
                'frm_reset_password_username' => ['Too many reset attempts. Please try again later.'],
            ]);
        }

        $limiter->hit($key, 60); 
    }
}
