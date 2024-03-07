<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\member;
use App\Models\poin_member;
use App\Models\cabang;
use App\Models\transaction_member;
use  App\Models\user_cabang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiMember extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }
    public function poin(Request $request){
        $input = $request->all();
        $member = member::where('random_kode', $input['pin'])->first();
        if(empty($member->uuid)){
            $data = [
                'success' => false,
                'message' => 'kode telah kadaluarsa',
            ];
            return response()->json($data,401);
        }
        else{
            $poin = poin_member::where('id_member', $member->uuid)->first();
            $data = [
                'member' => $member,
                'poin' => $poin,
            ];
            return response()->json($data);
        }
        return response()->json(['success' => false, 'message' => 'pin tidak ditemukan']);
    }
    public function belanja(Request $request){

    }
    public function home(Request $request){
        $input = $request->all();
        $member = member::where('phone', $input['nomor_hp'])->first();
        $text = $member->random_kode;
        $spacedText = chunk_split($text, 4, ' ');
        // Menghilangkan spasi di akhir hasil
        $random_kode = rtrim($spacedText);
        $member->random_kode = $random_kode;
        $poin = poin_member::where('id_member', $member->uuid)->first();
        if(empty($poin)){
            $member = member::where('phone', $input['nomor_hp'])->first();
            $poin = [
                'uuid' => Str::uuid(60),
                'id_member' => $member->uuid,
                'poin' => 0,
                'status' => 'active',
            ];
            poin_member::create($poin);
            $poin = poin_member::where('id_member', $member->uuid)->first();
        }
        $transaction = transaction_member::where('id_member', $member->phone)->limit(4)->get();
        foreach ($transaction as $key => $value) {
            // Format harga (price)
            $amount = $transaction[$key]->harga;
            $harga = number_format($amount, 0, ',', '.');
            $transaction[$key]->harga = $harga;
        
            // // Format created_at date
            // $dateString = $transaction[$key]->created_at;
            // $dateTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $dateString);
            
            // if ($dateTime) {
            //     $formattedDate = $dateTime->format('Y-m-d');
            //     $transaction[$key]->created_at = $formattedDate;
            // } else {
            //     // Handle parsing error if needed
            //     // For example: $transaction[$key]->created_at = 'Invalid Date';
            // }
        }
        

        $data = [
            'member' => $member,
            'poin' => $poin,
            'transaction' => $transaction,
        ];
        return response()->json($data);
    }
    public function transaksi (Request $request){
        $inputs = $request->data;
        $uuid = $request->id_cabang;
        $transaction_member = 0;
        $id_member = [];
            foreach ($inputs as $input) {
            // $user_cabang = user_cabang::where('uuid', $uuid)->first();
            // $id_cabang = $user_cabang->cabang_id;
            $db_cabang = cabang::where('uuid', $uuid)->value('database');
            $db_cabang = "transaction_$db_cabang";
            $dates = date('Y-m-d H:i:s');
                if($input['id_member']!== null ){
                    $uang = $input['quantity'] * $input['harga_jual'];
                    $transaction_member += $uang;   
                    $id_member[] = $input['id_member'];
                    $add =transaction_member::create( [
                        'uuid' => Str::uuid(60),
                        'nama_barang' =>$input['nama'],
                        'jumlah_barang'=>$input['quantity'],
                        'harga'=> $input['harga_jual'],
                        'id_member'=>$input['id_member']
                    ]);
                }
            $create = DB::table($db_cabang)->insert(
                [
                    'uuid' => Str::random(60),
                    'name' => $input['nama'],
                    'jumlah' => $input['quantity'],
                    'kode_barang' => $input['barkode'],
                    'status' => 'penjualan',
                    'id_member' => $input['id_member'],
                    'keterangan' => 'penjualan',
                    'harga_pokok' => $input['harga_pokok'],
                    'harga_jual' => $input['harga_jual'],
                    'created_at' => $dates
                ]
            );
            }
           if(!empty($id_member)){
            $uuid_member = member::where('phone',$id_member[0])->first();
            $poin = poin_member::where('id_member', $uuid_member->uuid)->first();
            // $created_at = $poin->created_at;
            // $tahunDepan = $created_at->addYear();
            $basis = 25000;
            $hasilPembagian = $transaction_member / $basis;
            $hasilBulatan = floor($hasilPembagian);
            $poin = $poin->poin += $hasilBulatan;
            $data = [
                'uuid' => Str::uuid(60),
                'id_member' => $uuid_member->uuid,
                'poin' => $poin,
                'status'=>'active',
                'expaid'=> "",
            ];
        poin_member::where('id_member',$uuid_member->uuid)->update($data);
           }

    return response()->json([
        'success' => true,
        'message' => ' Transaction  Success',
    ],200);


        
        
        
    }
    public function login(Request $request){
        
        $input = $request->all();
        $password = Hash::make($input['password']);
        $member = member::where('phone', $input['nomor_hp'])->first();
        $characters = '0123456789';
        $randomNumber = '';
        $length = 16;
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        if ($member && Hash::check($input['password'], $member->password)) {
                $time = time();
                $expainds = $member->expait_kode;
                if ($expainds < $time) {
                    $member->kode_akses = Str::random(60);
                    $member->expait_kode = time() + 600;
                    $member->random_kode = $randomNumber;
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
    public function reset(Request $request){
        $phone = $request->phone;
        $nik = $request->nik;
        $passowrd = $request->password;
        $characters = '0123456789';
        $randomNumber = '';
        $length = 16;
        $uuid =Str::uuid(60);
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        $data = member::where('phone', $phone)->where('email',$nik)->first();
        if(empty($data)){
            return response()->json([
                'success' => false,
                'message' => 'Reset Password  Gagal Data Tidak Di Temukan',
            ], 404);
        }
        else{
          $update = $data->update([
                'password' => Hash::make($passowrd),
                'random_kode'=>$randomNumber
            ]);
            if($update){
                return response()->json([
                    'success' => true,
                    'message' => 'Reset Password Berhasil',
                ], 200);
            }
            else{
                return response()->json([
                    'success' =>false,
                    'message' => 'Server error',
                ], 500);
            }
        }

    }
    public function register(Request $request){
        $characters = '0123456789';
        $randomNumber = '';
        $length = 16;
        $uuid =Str::uuid(60);
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        $datas = $request;
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
                'uuid' =>$uuid,
                'name' => $input['nama'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'kode_akses' => Str::random(60),
                'expait_kode' => time() + 600,
                'status' => 'member',
                'alamat' => $input['alamat'],
                'random_kode'=>$randomNumber
            ];
            $member = member::create($data);
            $poin = [
                'uuid' => Str::uuid(60),
                'id_member' => $uuid,
                'poin' => 0,
                'status' => 'active',
            ];
            poin_member::create($poin);
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
        $input = $request->data;
        $bulan = $input['bulan'];
        $tahun = $input['tahun'];
        $id_member = $input['nomor_hp'];
        $now = Carbon::now();
        if(!empty($id_member)){
            if(empty($bulan)){
                $data = transaction_member::where('id_member',$id_member)
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->get();
            }
            else{
                $data = transaction_member::where('id_member',$id_member)
                ->whereYear('created_at',$tahun)
                ->whereMonth('created_at', $bulan)
                ->get();
            }
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Data Ditemukan',
                    'data' => $data
                ], 200); 
        }
        else{
            return response()->json(
                [
                    'success' => false,
                    'message' => 'id_member not empty',
                ], 402);
        }
        
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
