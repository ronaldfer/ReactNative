<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ProjectManagerMiddleware
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
        // dd(Auth::user());
        if (Auth::check() && Auth::user()->roles->role->name == 'ProjectManager') {
            return $next($request);
        }elseif (Auth::check() && Auth::user()->roles->role->name == 'Admin') {
            dd('pm else');
            
        }
        // return back();
        return $next($request);
    }
}
