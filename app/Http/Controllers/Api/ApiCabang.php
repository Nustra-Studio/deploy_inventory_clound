<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\barang;
use App\Models\cabang;
use App\Models\suplier;
use App\Models\opname;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
    public function dummy(){
        
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
        public function listcabang(){
        $cabang = cabang::all();
        return response()->json($cabang);
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
            $uuid = user_cabang::where('uuid', $uuid)->first();
            $id = $uuid->cabang_id;
            $db_cabang = cabang::where('uuid', $id)->first();
            $db_cabang = $db_cabang->database;
            $barang = DB::table("$db_cabang")->get();

            $dataFinal = DB::table("$db_cabang as c")
                        ->join('supliers as s', 'c.id_supplier', '=', 's.uuid')
                        ->select(
                            'c.*','s.nama as merek_barang'
                        )
                        ->get();
        
            return response()->json($dataFinal);
        }
    
    public function usercreate(Request $request){
        $data = $request->data;
        $username = $data['username'];
        $password = $data['password'];
        $uuid = $data['uuid'];
        $role = $data['role'];
        $cabang_id = $data['cabang_id'];
        $user = user_cabang::create([
            'cabang_id' => $cabang_id,
            'uuid' => $uuid,
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role,
            'api_key' => Str::random(40),
        ]);
        return response()->json(["status" => "success",'data' => $user], 200);
    }
    public function userupdate(Request $request){
        $data = $request->data;
        $password = $data['password'];
        $uuid = $data['uuid'];
        $role = $data['role'];
        $id_cabang = $data['cabang_id'];
        $datas = user_cabang::where('uuid',$uuid)
        ->where('cabang_id',$id_cabang)
        ->update([
            'password' => Hash::make($password),
            'role' => $role
        ]);
        if(!empty($datas)){
            return response()->json(["status" => "success update data"], 200);
        }
        else{
            return response()->json(["status" => "error data not found"], 404);
        }
    }
    public function userdelete(Request $request){
        $data = $request->data;
        $uuid = $data['uuid'];
        $id_cabang = $data['cabang_id'];
        $datas = user_cabang::where('uuid',$uuid)
        ->where('cabang_id',$id_cabang)
        ->delete();
        if(!empty($datas)){
            return response()->json(["status" => "success Delete user cabang"], 200);
        }
        else{
            return response()->json(["status" => "error data not found"], 404);
        }
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
    public function deletebarang (Request $request){
        $uuid = $request->input('uuid');
        $uuid = user_cabang::where('uuid', $uuid)->first();
        $id = $uuid->cabang_id;
        $db_cabang = cabang::where('uuid', $id)->first();
        $db_cabang = $db_cabang->database;
        $barang = DB::table("$db_cabang")->get();
        
        $dataList = $request->data;
        // dd($dataList);
        foreach($dataList as $item) {
            DB::table("$db_cabang")->where('kode_barang', $item['kode_barang'])->delete();

        }
            
        // foreach ($barang as $item){
        //     DB::table("$db_cabang")->where('uuid', $item->uuid)->delete();
        // }
        // return response()->json($dataList, 200);
        return response()->json("Semua data telah dihapus.", 200);


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
    public function opname(Request $request){
        $id_toko = $request->input('id_toko');
        $data = $request->data;
        $uuid = $data['uuid'];
        $barcode = $data['barcode'];
        $stock = $data['stock'];
        // update data for name
        $check = opname::where('barcode',$barcode)->where('id_toko',$id_toko)->first();
        if(empty($check)){
            opname::create([
                'id_toko'=>$id_toko,
                'uuid'=>$uuid,
                'barcode'=>$barcode,
                'perubahan'=>'',
                'stock'=>$stock,
                'status'=>'old',
            ]);
        }
        else{
            $id = $check->id;
            $stocks = $check->stock;
            $stock = $stock + $stocks;
            opname::where('id',$id)
                        ->where('id_toko',$id_toko)
                        ->update([
                            'perubahan'=>'',
                            'stock'=>$stock,
                            'status'=>'old',
                        ]);
        }
        return response()->json("Semua data telah buat.", 200);
    }
    public function opnamelist(Request $request){
        $id_toko = $request->input('uuid');
        $data = opname::where('id_toko', $id_toko)->where('status', 'new')->get();
        $datas = opname::where('id_toko', $id_toko)->where('status', 'new')->first();
        
        if(!$datas){
            return response()->json([
                'status' => 'tidak ada data opname terbaru',
            ], 200);
        }
        
        return response()->json([
            'status' => 'success read data',
            'data' => $data
        ], 200);
    }
    
    public function returnopname (Request $request){
        $uuid = $request->input('barcode');
        $id_toko = $request->input('uuid');
        $data = opname::where('uuid',$uuid)->where('barcode',$id_toko)->first();
        $data->delete();
        return response()->json(
            [
                'message'=>'success update data',
                'barcode'=>$uuid
            ],200);
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
