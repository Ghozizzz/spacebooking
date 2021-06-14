<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
        // role 1 = user, 2 = admin, 3 = faculty admin
        if(session('role') == 1 && empty(session('facilities'))){
            abort(403);
        }
        return $next($request);
    }
}
