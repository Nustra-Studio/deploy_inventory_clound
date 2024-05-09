<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\UserCabang;
use App\Models\opname;
use App\Imports\OpnameImports;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class OpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.opname.index');
    }
    public function __construct()
    {
        $this->middleware('opname');
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
        $data = $request->input('data_table_values');
        $data = json_decode($data, true);
        foreach($data as $item){
            if(!empty($item['jumlah'])){
                opname::where('barcode',$item['barcode'])
            ->where('id_toko',$item['id_toko'])
            ->update([
                'perubahan'=>$item['jumlah'],
                'status'=>'new',
            ]);
            }
        }
        return redirect()->route('opname.index')->with('success', 'Data Berhasil Di Tambahkan');
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
    public function product(Request $request,$id){
        if($request->filled('q')){
            $data = opname::where('id_toko', $id)->where('barcode', 'LIKE', '%'. $request->get('q'). '%')->get();
        }
        elseif($request->filled('namaproduct')){
            $data = opname::where('id_toko', $id)->where('barcode', 'LIKE', '%'. $request->get('namaproduct'). '%')->get();
        }
        else{
            $data = opname::where('id_toko', $id)->limit(20)->get();
        }
        return response()->json($data);
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
    public function excel(Request $request){
        try {
            Excel::import(new OpnameImports, request()->file('file'));
            return redirect()->back()->with('success', 'Data Imported');
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
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
    public function logout()
    {
        Auth::guard('user_cabang')->logout();
        return redirect('/login-opname');
    }

}
