<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
     * Get the password reset validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'username' => 'required|string|max:50',
            'password' => [
                'required',
                'confirmed',
                'min:12',
                function($attribute, $value, $fail)
                {
                    if(! preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value))
                    {
                        $fail('Password must contain at least one uppercase and one lowercase letter.');
                    }
                },
                function($attribute, $value, $fail)
                {
                    if(! preg_match('/\pL/u', $value))
                    {
                        $fail('Password must contain at least one letter.');
                    }
                },
                function($attribute, $value, $fail)
                {
                    if(! preg_match('/\pN/u', $value))
                    {
                        $fail('Password must contain at least one number.');
                    }
                }
            ],
        ];
    }    

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset_l')->with(
            ['token' => $token, 'email' => $request->email, 'username' => $request->username]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    /*
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }
    */

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $givenUsername = $request->username;
        $givenEmail = $request->email;

        // Find reset request by username
        $resetRecord = DB::table('password_resets')
            ->where('username', $givenUsername)
            ->where('email', $givenEmail)
            ->first();

        if (!$resetRecord || !password_verify($request->token, $resetRecord->token)) {
            return back()->withErrors(['username' => 'Invalid reset token']);
        }

        // Update user's password
        $user = User::query()
            ->where('username', $givenUsername)
            ->where(function($query) use ($givenEmail){
                $query->where('email', $givenEmail)
                    ->orWhere('primary_email', $givenEmail)
                    ->orWhere('secondary_email', $givenEmail);
            })
            ->first();
        if (!$user) {
            return back()->withErrors(['username' => 'User not found']);
        }

        DB::table('users')
            ->where('username', $givenUsername)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_resets')->where('username', $givenUsername)->where('email', $givenEmail)->delete();

        event(new PasswordReset( $user ));

        return redirect('/login');
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'username', 'password', 'password_confirmation', 'token'
        );
    }
}
