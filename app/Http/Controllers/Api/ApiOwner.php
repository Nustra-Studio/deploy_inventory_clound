<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\user_cabang;
use App\Models\barang;
use App\Models\cabang;
use App\Models\suplier;
use App\Models\member;
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
    public function login(Request $request){
        
        $input = $request->all();
        $password = Hash::make($input['password']);
        $member = member::where('phone', $input['nomor_hp'])->where('status','owner')->first();
        $characters = '0123456789';
        $randomNumber = '';
        $length = 16;
        $member->kode_akses = Str::random(60);
        $member->save();
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        if ($member && Hash::check($input['password'], $member->password)) {
            $member = member::where('phone', $input['nomor_hp'])->where('status','owner')->first();
            return response()->json([
                'access_token' => $member->kode_akses,
                'success' => true,
                'message' => 'Login Berhasil',
            ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Gagal',
                    'data' => ''
                ], 401);
            }
    
    }
    public function cabang(){
        $cabang = cabang::all();
        return response()->json($cabang);
    }

    public function cabanglaporan(Request $request){
        $input = $request->all();
        $cabangDB = $input['database'];
        $tableCB = "transaction_$cabangDB";
        $startDate = now()->subWeek();
        $endDate = now();

        $dataCabangs = DB::table($tableCB)->whereBetween('created_at', [$startDate, $endDate])->first();

        $results = $results->concat($dataCabangs);

        $data = json_decode($results, true);

        $id_counts_per_day = [];
        
        $created_at = new DateTime($data['created_at']);
        $date = $created_at->format('Y-m-d');
        $id_value = $data['jumlah'];
        // $id_value = $entry['id'];

        $id_counts_per_day[$date][$id_value] = $data["harga_jual"];
        
        // if (array_key_exists($date, $id_counts_per_day)) {
        //     $id_counts_per_day[$date][$id_value] = isset($id_counts_per_day[$date][$id_value]) ? $id_counts_per_day[$date][$id_value] + 1 : 1;
        // } else {
        //     $id_counts_per_day[$date][$id_value] = 1;
        // }
        
        return response()->json($id_counts_per_day);
    }

    public function cabangbarang(){
        $cabang = cabang::all();
        $results = collect();

        foreach($cabang as $datas){
            $namas = $datas->nama;

            if($namas !== "Toko Bandung"){ // Jika bukan "Toko Bandung"
                $nama = str_replace(' ', '_', $namas);
                $database = "transaction_cabang_$nama";
                $startDate = now()->subWeek();
                $endDate = now();

                $result = DB::table($database)->whereBetween('created_at', [$startDate, $endDate])->get();

                $results = $results->concat($result);
            }
        }
            //a
            $data = json_decode($results, true);

            $id_counts_per_day = [];

            foreach ($data as $entry) {
                $created_at = new DateTime($entry['created_at']);
                $date = $created_at->format('Y-m-d');
                $id_value = $entry['jumlah'];
                // $id_value = $entry['id'];

                $id_counts_per_day[$date][$id_value] = $entry["harga_jual"];
                
                // if (array_key_exists($date, $id_counts_per_day)) {
                //     $id_counts_per_day[$date][$id_value] = isset($id_counts_per_day[$date][$id_value]) ? $id_counts_per_day[$date][$id_value] + 1 : 1;
                // } else {
                //     $id_counts_per_day[$date][$id_value] = 1;
                // }
            }

            return response()->json($id_counts_per_day);
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
    public function top(){
        $cabang = cabang::all();
        $results = collect();

        foreach($cabang as $datas){
            $namas = $datas->nama;

            if($namas !== "Toko Bandung"){ // Jika bukan "Toko Bandung"
                $nama = str_replace(' ', '_', $namas);
                $database = "transaction_cabang_$nama";
                $startDate = now()->subWeek();
                $endDate = now();

                $result = DB::table($database)->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('jumlah','desc')
                ->limit(10)
                ->get();

                $results = $results->concat($result);
            }
        }
            $data = json_decode($results, true);

            $id_counts_per_day = [];

            foreach ($data as $entry) {
                $created_at = new DateTime($entry['created_at']);
                $date = $created_at->format('Y-m-d');
                $id_value = $entry['id'];

                if (array_key_exists($date, $id_counts_per_day)) {
                    $id_counts_per_day[$date][$id_value] = isset($id_counts_per_day[$date][$id_value]) ? $id_counts_per_day[$date][$id_value] + 1 : 1;
                } else {
                    $id_counts_per_day[$date][$id_value] = 1;
                }
            }

            return response()->json($results);
    }

    public function hariancabang(){
        
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
