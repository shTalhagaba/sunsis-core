<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SupportMiddleware
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
        $user = User::all()->count();
        if(!($user == 1))
        {
            if(!Auth::user()->email != 'support@perspective-uk.com' && !Auth::user()->is_support)
            {
                abort('401');
            }
        }

        return $next($request);
    }
}
