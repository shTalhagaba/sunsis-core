<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckFirstTimeLogin
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
        if (Auth::check() && Auth::user()->password_changed_at == null)
        {
            return redirect()->route('change_password.show');
        }

        return $next($request);
    }
}
