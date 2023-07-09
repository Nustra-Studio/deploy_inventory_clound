<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\suplier;
use App\Models\barang;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = suplier::all();
        return view('pages.supplier.index' , compact('data'));
    }
    public function barang($uuid)
    {
        $data = barang::where('id_supplier', $uuid)->get();
        $nama = suplier::where('uuid', $uuid)->value('nama');
        return view('pages.supplier.barang',compact('data','nama'));
    }
    public function list(){
        $data = suplier::all();
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data =[
            'nama' => $request->nama,
            'product' => $request->supplier,
            'keterangan' => $request->keterangan,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'category_barang_id'=> $request->category,
            'uuid' => $request->uuid,
        ];
        DB::table('supliers')->insert($data);
        return redirect()->route('supllier.index')->with('success', 'Data supplier berhasil ditambahkan');
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
    public function caribarang($id){
        $ids = suplier::where('nama', $id)->value('uuid');
        $data = barang::where('id_supplier', $ids)->get();
        return response()->json($data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $data = DB::table('supliers')->where('uuid', $id)->first();
        return view('pages.supplier.update', compact('data'));
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
        $this->validate($request, [
            'nama'=> 'required',
            'supplier'=> 'required',
            'keterangan'=> 'required',
            'alamat'=> 'required',
            'telepon'=> 'required',
            'category'=> 'required',

        ]);
        $data = $request->all();
        $data =[
            'nama' => $request->nama,
            'product' => $request->supplier,
            'keterangan' => $request->keterangan,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'category_barang_id'=> $request->category,
        ];
        DB::table('supliers')->where('uuid', $id)->update($data);
        return redirect()->route('supllier.index')->with('success', 'Data supplier berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('supliers')->where('uuid', $id)->delete();
        return redirect()->route('supllier.index')->with('success', 'Data supplier berhasil dihapus');
    }
}
