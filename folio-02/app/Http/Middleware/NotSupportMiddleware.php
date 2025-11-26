<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class NotSupportMiddleware
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
        if ( Auth::check() && Auth::user()->is_support  && !\Session::has('impersonate') )
        {
            //abort('403', 'You cannot access this directly, please impersonate as system user.');
            return redirect()->route('home');
        }

        return $next($request);
    }
}
