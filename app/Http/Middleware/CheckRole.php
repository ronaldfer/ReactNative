<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Models\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role)
    {
        if (auth()->check() && auth()->user()->hasRole($role)) {
            return $next($request);
        }/*else{
            return redirect()->route('home');
        }*/
        abort(403, 'Access denied');
    }
}