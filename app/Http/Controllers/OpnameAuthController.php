<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\UserCabang;
use App\Models\opname;
use Illuminate\Support\Facades\Auth;


class OpnameAuthController extends Controller
{
        public function __construct()
        {
            $this->middleware('opname');
        }
        public function showLoginForm()
        {
            return view('pages.auth.login_opname');
            \Log::info('login page running');
        }
        public function login(Request $request)
        {
            $credentials = $request->only('username', 'password');
            \Log::info('Attempting login with credentials: ' . json_encode($credentials));
        
            if ($this->customAuthenticate($credentials)) {
                \Log::info('Login successful');
                return redirect()->intended('/opname');
            } else {
                \Log::warning('Login failed. Invalid credentials');
                return redirect('/opname/login')->with('error', 'Invalid credentials');
            }
        }
        

        private function customAuthenticate($credentials)
        {
            if (Auth::guard('user_cabang')->attempt($credentials)) {
                return true;
            }

            return false;
        }
        public function logout()
        {
            Auth::guard('user_cabang')->logout();
            return redirect('/opname/login');
        }
}
