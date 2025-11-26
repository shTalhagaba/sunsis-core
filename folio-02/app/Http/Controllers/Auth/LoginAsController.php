<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginAsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function showLoginAs()
    {
        $linkedUsersList = [];

        foreach(auth()->user()->linkedUsers AS $linkedUser)
        {
            $linkedUsersList[$linkedUser->id] = $linkedUser->full_name . ' [' . optional($linkedUser->systemUserType)->description . ']';
        }

        foreach(auth()->user()->linkedByUsers AS $linkedByUser)
        {
            $linkedUsersList[$linkedByUser->id] = $linkedByUser->full_name . ' [' . optional($linkedByUser->systemUserType)->description . ']';
        }

        abort_if(count($linkedUsersList) == 0, Response::HTTP_UNAUTHORIZED, 'You don\'t have other linked accounts.');

        return view('auth.login_as', compact('linkedUsersList'));
    }

    public function executeLoginAs(Request $request)
    {
        $linkedAccountIds = array_merge( 
            auth()->user()->linkedUsers()->pluck('id')->toArray(),
            auth()->user()->linkedByUsers()->pluck('id')->toArray()
        );

        abort_if( count($linkedAccountIds) == 0, Response::HTTP_UNAUTHORIZED, 'You don\'t have other linked accounts.' );

        $request->validate([
            'linked_account_id' => 'required|numeric|in:' . implode(',', $linkedAccountIds),
            'linked_account_password' => 'required|string'
        ]);

        $linkedAccount = User::findOrFail($request->linked_account_id);
        if (! (Hash::check($request->get('linked_account_password'), $linkedAccount->password)) )
        {
            return back()
                ->with( ['alert-danger' => 'Incorrect password, please try again.'] )
                ->withInput();
        }

        // Switch User Account
        // Log out the current account
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Log in as the new account
        Auth::login($linkedAccount);

        // Regenerate the session ID to prevent session fixation
        $request->session()->regenerate();

        // Redirect to home page after login
        return redirect()->route('home')->with('alert-success', 'Logged in as ' . $linkedAccount->full_name . '.');
    }
}
