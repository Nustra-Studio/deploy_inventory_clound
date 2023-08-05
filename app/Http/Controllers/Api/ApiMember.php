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
    public function home(Request $request){
        $input = $request->all();
        $member = member::where('phone', $input['nomor_hp'])->first();
        $poin = poin_member::where('id_member', $member->uuid)->first();
        $transaction = transaction_member::where('id_member', $member->uuid)->limit(5)->get();
        $data = [
            'member' => $member,
            'poin' => $poin,
            'transaction' => $transaction,
        ];
        return response()->json($data);
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
                        'email' => $member->email,
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
                        'access_token' => $member->kode_akses,
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
        $datas = $request->data;
        $phone= $datas['phone'];
        $phone_data = 0;
        $data_hp = member::where('phone', $phone)->value('phone');
        $phone_data = $data_hp;
        if($phone = $phone_data){
            return response()->json([
                'success' => false,
                'message' => 'Register Gagal Data Sudah Ada',
                'data' => ''
            ], 400);
        }
        else{
            $input = $request->data;
            $data = [
                'uuid' => Str::random(60),
                'name' => $input['nama'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'kode_akses' => Str::random(60),
                'expait_kode' => time() + 600,
                'status' => 'member',
                'alamat' => $input['alamat'],
            ];
            $member = member::create($data);
            $member_data = [
                'nama' => $member->name,
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
    public function editdata(Request $request)
    {
        $uuid = $request->uuid;
        $member = member::where('uuid', $uuid)->first();
        if(!$member){
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => ''
            ], 401);
        }
        else{
            $member = [
                $member->name,
                $member->email,
            ];
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Data Ditemukan',
                    'data' => $member
                ], 200);

        }
    }
    public function membertoken(Request $request){
        $data = $input['token_member'];
        $member = member::where('kode_akses', $data)->first();
    }
    public function transaction(Request $request){
        $data = $input['token_member'];
        $member = member::where('kode_akses', $data)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatemember (Request $request)
    {
        $data = $request->data;
        $email = $data['email'];
        $username = $data['username'];
        if(empty($data['password'])){
            $member = member::where('uuid', $request->uuid)->update([
                'name' => $username,
                'email' => $email,
            ]);
        }
        else{
            $password = Hash::make($data['password']);
            $member = member::where('uuid', $request->uuid)->update([
            'name' => $username,
            'email' => $email,
            'password' => $password,
        ]);
        }
        return response ()->json([
            'success' => true,
            'message' => 'Data Berhasil Diubah',
            'data' => $member
        ], 200);
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
