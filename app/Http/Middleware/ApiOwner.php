<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\member;

class ApiOwner
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
        $token = $request->input('access_token');
        $token_db = member::where('kode_akses', $token)->value('kode_akses');
        if (!$token ) {
            return response()->json(['message' => 'pleast enter token akses'], 401);
        }
        elseif ($token != $token_db) {
            return response()->json(['message' => 'token akses tidak valid'], 401);
        }

        return $next($request);
    }
}
