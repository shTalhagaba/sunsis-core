<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Address\UserAddressService;
use App\Services\Profile\ProfileService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();

        $homeAddress = $user->homeAddress();
        $workAddress = $user->workAddress();

        return view('profile', compact('user', 'homeAddress', 'workAddress'));
    }    

    public function update(StoreProfileRequest $request, ProfileService $profileService, UserAddressService $userAddressService)
    {
        $user = Auth::user();

        $user->update($request->input());

        $userAddressService->saveAddresses($user, $request->input());

        if ($request->file('avatar'))
        {
            try
            {
                $profileService->uploadAvatar($request->file('avatar'), $user);
            }
            catch(\Throwable $exception)
            {
                return back()
                    ->with(['alert-danger' => $exception->getMessage()]);
            }
        }

        return redirect()
            ->route('profile.show')
            ->with(['alert-success', 'Your changes have been saved successfully.']);
    }

    public function showChangePassword()
    {
        return view('change_password');
    }

    public function updatePassword(Request $request)
    {
        if (! (Hash::check($request->get('current-password'), Auth::user()->password)) )
        {
            return back()
                ->with( ['alert-danger' => 'Your current password does not match with the password you provided. Please try again.'] );
        }

        if( strcmp($request->get('current-password'), $request->get('new-password')) == 0 )
        {
            return back()
                ->with(['alert-danger' => 'New Password cannot be same as your current password. Please choose a different password.']);
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => [
                'required',
                'confirmed',
                'min:12',
                function($attribute, $value, $fail)
                {
                    if(! preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value))
                    {
                        $fail('New password must contain at least one uppercase and one lowercase letter.');
                    }
                },
                function($attribute, $value, $fail)
                {
                    if(! preg_match('/\pL/u', $value))
                    {
                        $fail('New password must contain at least one letter.');
                    }
                },
                function($attribute, $value, $fail)
                {
                    if(! preg_match('/\pN/u', $value))
                    {
                        $fail('New password must contain at least one number.');
                    }
                }
            ],
        ]);

        Auth::user()
            ->update([
                'password' => bcrypt($validatedData['new-password']),
                'password_changed_at' => now()->toDateTimeString(),
            ]);

        Auth::logoutOtherDevices($validatedData['new-password']);

        event(new PasswordReset(Auth::user()));

        return back()
            ->with(['alert-success' => 'Your changes have been saved successfully.']);
    }
}
