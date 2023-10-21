<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $role)
    {
        // Check if the authenticated user has the required role
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // User doesn't have the required role; you can customize the response
        return response('Unauthorized', 403);
    }
}
