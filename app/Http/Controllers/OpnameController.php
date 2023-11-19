<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\UserCabang;
use Illuminate\Support\Facades\Auth;

class OpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::guard('user_cabang')->user()->id;
        return view('pages.opname.index',compact($id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function showLoginForm()
    {
        return view('pages.auth.login_opname');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if ($this->customAuthenticate($credentials)) {
            return redirect()->intended('/opname');
        } else {
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
