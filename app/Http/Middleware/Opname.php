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
    public function handle(Request $request, Closure $next)
    {
        // Debug statement
        // dd('Opname middleware executed');
    
        // Cek apakah pengguna telah login
        if (Auth::guard('user_cabang')->check()) {
            return $next($request);
        }
    
        // Jika pengguna belum login, arahkan ke halaman login
        else {
            return redirect('/opname/login');
        }
    }
    
}
