<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category_cabangs;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CategoryCabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = category_cabangs::all();
        return view('pages.category_cabang.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('pages.category_cabang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = env('APP_API');
        $response = Http::timeout(1)->get($url);

        if ($response->successful()) {
            $datanew = [
                'key'=>'categorycabang',
                'name' => $data['name'],
                'keterangan' => $data['keterangan'],
                'uuid' => $data['uuid'],
                'status'=>'singkron'
            ];
            $url ="$url/api/singkron";
            $response = Http::post($url, $datanew);
            $apiResponse = $response->json();
            dd($apiResponse);
            $datanew = [
                'name' => $data['name'],
                'keterangan' => $data['keterangan'],
                'uuid' => $data['uuid'],
                'status'=>'singkron'
            ];
            DB::table('category_cabangs')->insert($datanew);

            // return redirect()->route('categorycabang.index')->with('success','Data Berhasil Ditambahkan Dan Juga Ke server');
        } else {
            $datanew = [
                'name' => $data['name'],
                'keterangan' => $data['keterangan'],
                'uuid' => $data['uuid'],
                'status'=>'not_singkron'
            ];
            DB::table('category_cabangs')->insert($datanew);
            return redirect()->route('categorycabang.index')->with('success','Data Berhasil Ditambahkan Tapi Tidak Keserver');
        }
        $data = $request->all();
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
        $data = category_cabangs::where('uuid',$id)->first();
        return view('pages.category_cabang.update',compact('data'));
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
        $this -> validate(request(),[
            'name' => 'required',
            'keterangan' => 'required',
        ]);
        $data = $request->all();
        $datanew = [
            'name' => $data['name'],
            'keterangan' => $data['keterangan'],
        ];
        DB::table('category_cabangs')->where('uuid',$id)->update($datanew);
        return redirect()->route('categorycabang.index')->with('success','Data Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('category_cabangs')->where('uuid',$id)->delete();
        return redirect()->route('categorycabang.index')->with('success','Data Berhasil Dihapus');
    }
}
