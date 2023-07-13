<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\suplier;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ApiCabang extends Controller
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
    public function supplier(){
        $supplier = suplier::all();
        return response()->json($supplier);
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = user_cabang::where('username', $username)->first();
    
        if (!$user || !Hash::check($password, $user->password)) {
            // Autentikasi gagal
            return response()->json(['message' => 'Invalid username or password'], 401);
        }
    
        // Autentikasi berhasil
        $tokens = Str::random(40);
        $user->update(['api_key' => $tokens]);
        $token = $user->api_key;
    
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
        public function barang(Request $request)
        {
            $request->validate([
                'token' => 'required',
            ]);
            $uuid = $request->input('uuid');
            $barang = user_cabang::where('uuid', $uuid)->first();
            return response()->json($barang);
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
}
