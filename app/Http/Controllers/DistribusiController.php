<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\cabang;
use App\Models\supplier;
use App\Models\category;
use App\Models\history_transaction;
use Illuminate\Support\Facades\DB;

class DistribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('pages.distribusi.index');
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
    public function barangstore(Request $request)
    {
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
                    'keterangan' => $data->keterangan,
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

    public function barang($uuid){
        $barang = barang::all();
        $uuid_cabang = $uuid;
        return view('pages.distribusi.barang', compact('barang','uuid_cabang'));
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
