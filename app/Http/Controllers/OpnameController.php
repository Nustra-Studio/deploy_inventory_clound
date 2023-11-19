<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_cabang;

class OpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $username = $request->input('username');
        $password = $request->input('password');

        if ($this->authenticate($username, $password)) {
            $user = $this->getUserByUsername($username);

            // Simpan ID pengguna dalam sesi
            session(['user_id' => $user->id]);

            return redirect('/opname');
        } else {
            return redirect('/login')->with('error', 'Invalid credentials');
        }
    }

    private function authenticate($username, $password)
    {
        $user = user_cabang::where('username', $username)->first();

        if ($user && password_verify($password, $user->password)) {
            return true;
        }

        return false;
    }

    private function getUserByUsername($username)
    {
        return user_cabang::where('username', $username)->first();
    }
}
