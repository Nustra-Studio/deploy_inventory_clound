<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
         // Cek apakah pengguna telah login
         if (auth()->check()) {
            return $next($request);
        }

        // Jika pengguna belum login, arahkan ke halaman login
        return redirect('/login');
    }
}
