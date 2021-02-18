<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware
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
        if (Auth::check() && Auth::user()->role == 'Admin') {
            return $next($request);
        }elseif (Auth::check() && Auth::user()->role == 'ProjectManager') {
            return redirect('/pm-login');
        }/*else {
            return redirect('/customer');
        }*/
        /*return $next($request);*/
    }
}
