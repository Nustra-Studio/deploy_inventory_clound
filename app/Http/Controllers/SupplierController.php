<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\suplier;
use App\Models\barang;
use App\Models\singkron;
use Illuminate\Support\Facades\Http;

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
        $localData = [
            'nama' => $request->nama,
            'product' => $request->supplier,
            'keterangan' => 'singkron',
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'category_barang_id' => $request->category,
            'uuid' => $request->uuid,
        ];
        $singkron =  [
            'name'=>'supplier',
            'status'=>'create',
            'uuid'=>$request->uuid,
        ];
        $this->storeLocally($localData);
        singkron::insert($singkron);

        return redirect()->route('supllier.index')->with('success', 'Data berhasil disimpan tetapi tidak disinkronkan ke server');
    }   
    private function storeLocally($datas)
    {
        
        try {
            DB::table('supliers')->insert($datas);
        } catch (\Exception $e) {
            \Log::error('Error storing data locally: ' . $e->getMessage());
        }
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
    public function caribarang(Request $request,$id){
        $ids = suplier::where('nama', $id)->value('uuid');
        if($request->filled('q')){
            $data = barang::where('id_supplier', $ids)->where('name', 'LIKE', '%'. $request->get('q'). '%')->get();
        }
        elseif($request->filled('namaproduct')){
            $data = barang::where('id_supplier', $ids)->where('name', 'LIKE', '%'. $request->get('namaproduct'). '%')->get();
        }
        else{
            $data = barang::where('id_supplier', $ids)->get();
        }
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
