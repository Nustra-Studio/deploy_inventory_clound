<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category_barang;
use App\Models\singkron;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =category_barang::all();
        return view('pages.category.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
                $data = $request->only(['name', 'uuid']);
                $data['keterangan'] = 'singkron';
                $singkron =  [
                    'name'=>'categorybarang',
                    'status'=>'create',
                    'uuid'=>$request->uuid,
                ];
                $this->storeLocally($data);
                singkron::insert($singkron);
                return redirect()->route('category.index')->with('success', 'Data berhasil disimpan dan disinkronkan ke server');

    }        
    private function storeLocally($datas)
        {
            try {
                category_barang::insert($datas);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = category_barang::where('uuid',$id)->first();
        return view('pages.category.update',compact('data'));
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
        $this->validate($request,['name'=>'required']);
        $data = $request->all();
        $update = category_barang::where('uuid',$id)->first();
        $singkron =  [
            'name'=>'categorybarang',
            'status'=>'update',
            'uuid'=>$update->uuid,
        ];
        category_barang::where('uuid',$id)->first()->update($data);
        singkron::insert($singkron);
        
        
        return redirect()->route('category.index')->with('success','Data Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = category_barang::find($id);
        $singkron =  [
            'name'=>'categorybarang',
            'status'=>'delete',
            'uuid'=>$category->uuid,
        ];
        singkron::insert($singkron);
        $category->delete();
        return redirect()->route('category.index')->with('success','Data Berhasil Dihapus');
    }
}
