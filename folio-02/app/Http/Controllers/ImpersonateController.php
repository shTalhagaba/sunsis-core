<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function impersonate(\App\Models\User $user)
    {

        \Session::put('impersonate', $user->id);

        return redirect()->route('profile.show');
    }

    public function stopImpersonate()
    {
        \Session::forget('impersonate');

        return redirect()->route('home');

    }
}
