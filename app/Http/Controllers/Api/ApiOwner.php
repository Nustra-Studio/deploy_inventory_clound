<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\barang;
use App\Models\cabang;
use App\Models\suplier;
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
        foreach($cabang as $datas){
            $namas = $datas->nama;
            if($namas == "Toko Bandung"){

            }
            else{
            $nama = str_replace(' ', '_', $namas);
            $database = "transaction_cabang_$nama";
            $startDate = now()->subWeek(); // Mengambil tanggal satu minggu yang lalu dari sekarang
            $endDate = now(); // Mengambil tanggal saat ini
            $datas = DB::table($database)->whereBetween('created_at', [$startDate, $endDate])->get();
            }
        }
        return response()->json(
            [ 
                "hasil"=>$datas,
                "cabang"=>$namas
            ]
        );
        
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
