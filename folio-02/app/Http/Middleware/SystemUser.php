<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class SystemUser
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
        abort_unless(auth()->user()->isStaff(), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
