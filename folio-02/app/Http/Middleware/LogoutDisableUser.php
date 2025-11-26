<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogoutDisableUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( Auth::check() && ! Auth::user()->isActive() )
        {
            Auth::guard()->logout();

            $request->session()->invalidate();

            redirect('/');
        }

        return $next($request);
    }
}
