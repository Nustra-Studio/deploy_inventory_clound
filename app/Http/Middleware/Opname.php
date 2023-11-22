<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Opname
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
        // Opname Middleware
        public function handle(Request $request, Closure $next)
        {
            \Log::info('Opname middleware started');

            if (Auth::guard('user_cabang')->check()) {
                return $next($request);
            } else {
                \Log::warning('Opname middleware: Redirecting to login');
                return redirect('/login-opname');
            }
        }

    
    
}
