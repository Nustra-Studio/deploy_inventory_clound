<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\barang;
use App\Models\cabang;
use App\Models\suplier;
use DateTime;
use DB;

class ApiOwner extends Controller
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
    public function cabang(){
        $cabang = cabang::all();
        return response()->json($cabang);
    }
    public function cabanglaporan(){

    }
    public function cabangbarang(){
        $cabang = cabang::all();
        $results = collect();
        
        foreach ($cabang as $datas) {
            $namas = $datas->nama;
        
            if ($namas !== "Toko Bandung") { // Jika bukan "Toko Bandung"
                $nama = str_replace(' ', '_', $namas);
                $database = "transaction_cabang_$nama";
                $startDate = now()->subWeek();
                $endDate = now();
        
                $result = DB::table($database)->whereBetween('created_at', [$startDate, $endDate])->get();
        
                $results = $results->concat($result);
            }
        }
        
        $data = json_decode($results, true);
        
        $id_counts_per_day = [];
        
        foreach ($data as $entry) {
            $created_at = new DateTime($entry['created_at']);
            $date = $created_at->format('Y-m-d');
            $id_value = $entry['id'];
            $jumlah = 0;
        
            // Mengecek apakah tanggal sudah ada dalam array atau belum
            if (array_key_exists($date, $id_counts_per_day)) {
                // Tanggal sudah ada, tambahkan jumlah id
                $id_counts_per_day[$date] += $entry['jumlah'];
            } else {
                // Tanggal belum ada, inisialisasi jumlah id dengan 1
                $id_counts_per_day[$date] = 1;
            }
        }
        
        // Mengurutkan hasil berdasarkan tanggal terkecil
        ksort($id_counts_per_day);
        
        return response()->json($id_counts_per_day);
        
        
    }
    public function top(){
        $cabang = cabang::all();
        $results = collect();
        $id_counts = [];
        $id_uuid = [];
        $id_name = [];
        
        foreach ($cabang as $datas) {
            $namas = $datas->nama;
        
            if ($namas !== "Toko Bandung") { // Jika bukan "Toko Bandung"
                $nama = str_replace(' ', '_', $namas);
                $database = "transaction_cabang_$nama";
                $startDate = now()->subWeek();
                $endDate = now();
        
                $result = DB::table($database)->get();
                $results = $results->concat($result);
            }
        }
        
        $data = $results->toArray();
        
        foreach ($data as $item) {
            $data_barang = $item->kode_barang;
        
            if (array_key_exists($data_barang, $id_counts)) {
                $id_counts[$data_barang] += $item->jumlah;
                $id_name[$data_barang] = $item->name;
                $id_uuid[$data_barang] = $item->uuid;
            } else {
                $id_counts[$data_barang] = $item->jumlah;
                $id_name[$data_barang] = $item->name;
                $id_uuid[$data_barang] = $item->uuid;
            }
        }
        
        $data_final = [];
        
        foreach ($id_uuid as $kode_barang => $name) {
            $data_final[] = [
                "name" => $id_name[$kode_barang],
                "kode_barang" => $id_uuid[$kode_barang],
                "jumlah" => $id_counts[$kode_barang]
            ];
        }
        
        return response()->json($data_final);
        
        
    }
    public function hariancabang(){
        $cabang = cabang::all();
        $results = [];
        foreach ($cabang as $datas) {
            $namas = $datas->nama;
        
            if ($namas!== "Toko Bandung") { // Jika bukan "Toko Bandung"
                $nama = str_replace(' ', '_', $namas);
                $database = "transaction_cabang_$nama";
                $startDate = now()->subDay(); // Change to subDay() for daily data
                $endDate = now();

    $result = DB::table($database)->whereBetween('created_at', [$startDate, $endDate])->get();
                $result = [
                    "name" => $namas,
                    "data" => $result
                ];
                $results[] = $result;
            }
        }
        return response()->json($results, 200);
    } 
        
    public function gudangadd(){

    }
    public function gudangout(){

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
