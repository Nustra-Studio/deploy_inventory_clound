<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\member;
use App\Models\poin_member;
use App\Models\transaction_member;

class ApiMember extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }
    public function login(Request $request){

        $input = $request->all();
        $password = Hash::make($input['password']);
        $member = member::where('phone', $input['nomor_hp'])->first();
        if ($member && Hash::check($input['password'], $member->password)) {
                $time = time();
                $expainds = $member->expait_kode;
                if ($expainds < $time) {
                    $member->kode_akses = Str::random(60);
                    $member->expait_kode = time() + 600;
                    $member->save();
                    $member = member::where('phone', $input['nomor_hp'])->first();
                    // data member hanya nama uuid phone dan kode akses
                    $members = [
                        'nama' => $member->name,
                        'uuid' => $member->uuid,
                        'phone' => $member->phone,
                    ];
                    return response()->json([
                        'access_token' => $member->kode_akses,
                        'expaid_token' => $member->expait_kode,
                        'success' => true,
                        'message' => 'Login Berhasil',
                        'data' => $members,
                    ], 200);
                } else {
                    $members = [
                        'nama' => $member->name,
                        'uuid' => $member->uuid,
                        'phone' => $member->phone,
                    ];
                    return response()->json([
                        'success' => true,
                        'expaid_token' => $member->expait_kode,
                        'message' => 'Login Berhasil',
                        'data' => $members
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Gagal',
                    'data' => ''
                ], 401);
            }
    
    }
    public function register(Request $request){
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['kode_akses'] = Str::random(60);
        $input['expait_kode'] = time() + 600;
        $member = member::create($input);
        $member_data = [
            'nama' => $member->nama,
            'uuid' => $member->uuid,
            'phone' => $member->phone,
            'kode_akses' => $member->kode_akses
        ];
        if ($member) {
            return response()->json([
                'success' => true,
                'message' => 'Register Berhasil',
                'data' => $member_data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register Gagal',
                'data' => ''
            ], 401);
        }

    }
    public function logout(Request $request){

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
}
