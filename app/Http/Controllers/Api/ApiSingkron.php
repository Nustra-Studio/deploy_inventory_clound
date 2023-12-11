<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\category_barang;
use App\Models\cabang;
use App\Models\user_cabang;
use App\Models\suplier;
use App\Models\barang;
use App\Models\harga_khusus;
use App\Models\history_transaction;
use App\Models\supplier;
use App\Models\category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;



class ApiSingkron extends Controller
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
        $key = $request->input('key');
    
        if (method_exists($this, $key)) {
            try {
                $response = $this->{$key}($request);
                return $response;
            } catch (\Exception $e) {

                return response()->json(['status' => 'error','data'=>$request
                    , 'message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid key'], 400);
        }
    }
    
    private function categorycabang($request)
    {
        $data = $request->only(['name', 'keterangan', 'uuid']);
    
        try {
            DB::table('category_cabangs')->insert($data);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan secara lokal'], 200);
        } catch (\Exception $e) {
            // Tangani pengecualian jika terjadi kesalahan saat menyimpan ke database lokal
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    private function categorybarang($request){
        $data = $request->only(['name', 'keterangan', 'uuid']);
    
        try {
            category_barang::create($data);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan secara lokal'], 200);
        } catch (\Exception $e) {
            // Tangani pengecualian jika terjadi kesalahan saat menyimpan ke database lokal
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    private function cabang($request)
    {
        // create database for request data
        $data = $request->all();
        // create new data
        try {
            $namas = $data['nama'];
            $nama = str_replace(' ', '_', $namas);
            $database = "cabang_$nama";
            $query = "
            CREATE TABLE cabang_$nama (
                `id` bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `category_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `id_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `kode_barang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_jual` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_pokok` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_grosir` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `stok` int(48) NOT NULL,
                `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `merek_barang` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `type_barang_id` varchar(158) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            DB::statement($query);
            $query2 = " 
            CREATE TABLE `transaction_$database` (
                `id` bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `name` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `jumlah` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `kode_barang` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `status` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `id_member` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_pokok` int(21) DEFAULT NULL,
                `harga_jual` int(21) DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            DB::statement($query2);
            user_cabang::create([
                'cabang_id' => $data['uuid'],
                'uuid' => Str::random(40),
                'username' => "supervisor_$nama",
                'password' => Hash::make('cintabunda123'),
                'role' => "supervisor",
                'api_key' => Str::random(40),
            ]);
            $newdata = [
                'nama' => $data['nama'],
                'alamat' => $data['alamat'],
                'kepala_cabang' => $data['kepala_cabang'],
                'telepon' => $data['telepon'],
                'category_id' => $data['category_id'],
                'uuid'=> $data['uuid'],
                'database' => "cabang_$nama",
                'keterangan'=>$data['keterangan']
            ];
            DB::table('cabangs')->insert($newdata);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan secara lokal'], 200);
        } catch (\Exception $e) {
            // Tangani pengecualian jika terjadi kesalahan saat menyimpan ke database lokal
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    }
    private function supplier($request){
        $data =[
            'nama' => $request->nama,
            'product' => $request->supplier,
            'keterangan' => $request->keterangan,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'category_barang_id'=> $request->category,
            'uuid' => $request->uuid,
        ];
        try {
            DB::table('supliers')->insert($data);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan secara lokal'], 200);
        } catch (\Exception $e) {
            // Tangani pengecualian jika terjadi kesalahan saat menyimpan ke database lokal
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    private function barang($request)
    {   
        try{
            $request = $request->data_master;
            $request = json_encode($request);
            $request = json_decode($request, true);
            $data_master =[
                'name' => $request->name,
                'merek_barang' => $request->merek_barang,
                'uuid' => $request->uuid,
                'id_supplier' => $request->supplier,
                'category_id' => $request->category_barang,
                'harga_pokok' => $request->harga_pokok,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->jumlah,
                'kode_barang' => $request->kode,
                'keterangan' => $request->keterangan,
            ];
            $request = $request->data_history;
            $request = json_encode($request);
            $request = json_decode($request, true);
            $uuid= hash('sha256', uniqid(mt_rand(), true));
            $data_history = [
                'uuid' => $uuid,
                'name' => $request->name,
                'jumlah' => $request->jumlah,
                'kode_barang' => $kode,
                'uuid_barang' => $request->uuid,
                'harga_pokok' => $request->harga_pokok,
                'harga_jual' => $request->harga_jual,
                'id_supllayer' => $request->id_supplier,
                'status' => 'masuk',
            ];
            $push = barang::create($data_master);
            $up = history_transaction::create($data_history);
                for($j=0; $j < count($request->nama); $j++){
                    $uuid= hash('sha256', uniqid(mt_rand(), true));
                    $data_harga = [
                        'uuid' => $uuid,
                        'id_barang'=> $request->uuid,
                        'harga' => $request->harga[$j],
                        'jumlah_minimal' => $request->jumlah_minimal[$j],
                        'diskon' => $request->diskon[$j],
                        'keterangan' => $request->nama[$j],
                        // 'satuan' => $request->satuan[$j],
                    ];
                    $push = harga_khusus::create($data_harga);
                }
        return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan secara lokal'], 200);
        }
    catch (\Exception $e) {
        // Tangani pengecualian jika terjadi kesalahan saat menyimpan ke database lokal
        return response()->json(['status' => 'error','data'=>$request
                    , 'message' => $e->getMessage()], 500);
    }
}
    private function input_barang()
    {
        $data = $request->input('data_table_values');
        $data = json_decode($data, true);
        $bulan = date('m');
        $tahun = date('y');
        $nomorUrut = str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
        $singkatan = "PM";
        $kode_tranasction = $singkatan.$bulan.$tahun.$nomorUrut;
        $uuid = Str::uuid()->toString();
        foreach ($data as $row) {
            $supplier = suplier::where('nama', $row['supplier'])->value('uuid');
            $stock = barang::where('name', $row['Name'])->value('stok');
            $kode = barang::where('name', $row['Name'])->first();
            $stock = $stock + $row['jumlah'];
            DB::table('barangs')->updateOrInsert(
                ['name' => $row['Name']],
                [
                    'stok' => $stock,
                    'Harga_pokok' => $row['Harga_pokok'],
                    'harga_jual' => $row['harga_jual'],
                    'id_supplier' => $supplier,
                ]
            );
            $uuid= hash('sha256', uniqid(mt_rand(), true));
            $data_history = [
            'uuid' => $uuid,
            'name' => $row['Name'],
            'jumlah' => $row['jumlah'],
            'kode_barang' => $kode->kode_barang,
            'uuid_barang' => $kode->uuid,
            'harga_pokok' => $kode->harga_pokok,
            'harga_jual' => $kode->harga_jual,
            'id_supllayer' => $supplier,
            'kode_transaction'=>$kode_tranasction,
            'status' => 'masuk',
        ];
        $push = history_transaction::create($data_history);
        }
        
        return redirect()->route('barang.index')->with('success', 'Data Berhasil Di Tambahkan');
    }
    private function distribusi()
    {
        $bulan = date('m');
        $tahun = date('y');
        $nomorUrut = str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
        $singkatan = "PD";
        $kode_tranasction = $singkatan.$bulan.$tahun.$nomorUrut;
        $total = count($request->input('jumlah'));
        $database = cabang::where('uuid', '=' ,"$request->id_cabang")->value('database');
        $nama = cabang::where('uuid', '=' ,"$request->id_cabang")->value('nama');
        for ($i=0; $i < $total; $i++) { 
            $kode = $request->input('kode')[$i];
            $stocks = $request->input('jumlah')[$i];
            $data = barang::where('kode_barang', '=' ,"$kode")->first();
            $check = DB::table("$database")->where('kode_barang', '=' ,"$kode")->first();
            if ($check) {
                $stock = $check->stok + $stocks;
                DB::table("$database")->where('kode_barang', '=' ,"$kode")->update([
                    'stok' => $stock,
                    'Harga_pokok' => $data->Harga_pokok,
                    'harga_jual' => $data->harga_jual,
                ]);
                $uuid= hash('sha256', uniqid(mt_rand(), true));
                $data_history = [
                    'uuid' => $uuid,
                    'name' => $data->name,
                    'jumlah' => $stocks,
                    'kode_barang' => $data->kode_barang,
                    'uuid_barang' => $data->uuid,
                    'harga_pokok' => $data->harga_pokok,
                    'harga_jual' => $data->harga_jual,
                    'id_supllayer' => $data->id_supplier,
                    'status' => 'keluar',
                    'keterangan' => 'distribusi',
                    'kode_transaction'=>$kode_tranasction,
                    'id_cabang' => $request->id_cabang,
                ];
                history_transaction::create($data_history);
                $data_stock = $data->stok - $stocks;
                $data->update([
                    'stok' => $data_stock,
                ]);
            }else{
                DB::table("$database")->insert([
                    'id' => $data->id,
                    'name' => $data->name,
                    'merek_barang' => $data->merek_barang,
                    'uuid' => $data->uuid,
                    'id_supplier' => $data->id_supplier,
                    'category_id' => $data->category_id,
                    'harga_pokok' => $data->harga_pokok,
                    'harga_jual' => $data->harga_jual,
                    'stok' => $stocks,
                    'kode_barang' => $data->kode_barang,
                    'keterangan' => $kode_tranasction,
                ]);
                $uuid= hash('sha256', uniqid(mt_rand(), true));
                $data_history = [
                    'uuid' => $uuid,
                    'name' => $data->name,
                    'jumlah' => $stocks,
                    'kode_barang' => $data->kode_barang,
                    'uuid_barang' => $data->uuid,
                    'harga_pokok' => $data->harga_pokok,
                    'harga_jual' => $data->harga_jual,
                    'id_supllayer' => $data->id_supplier,
                    'status' => 'keluar',
                    'keterangan' => 'distribusi',
                    'kode_transaction'=>$kode_tranasction,
                    'id_cabang' => $request->id_cabang,
                ];
                history_transaction::create($data_history);
                $data_stock = $data->stok - $stocks;
                $data->update([
                    'stok' => $data_stock,
                ]);
            }

        }
        return redirect()->route('distribusi.index')->with('success', "Barang Berhasil Di Distribusikan ke $nama  ");
    }
    private function history()
    {
        
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
