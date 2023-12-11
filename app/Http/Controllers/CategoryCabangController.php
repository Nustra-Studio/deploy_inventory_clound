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
            try {
                $url = env('APP_API');
                $response = Http::timeout(1)->get($url);

                if ($response->successful()) {
                    $data = $request->only(['name', 'keterangan', 'uuid']);
                    $data['keterangan'] = 'singkron';
                    $data['key'] = 'categorycabang';

                    // Kirim data ke server API
                    $apiResponse = $this->sendToApi($url, $data);

                    // Cek status dari respons API
                    if ($apiResponse && $apiResponse['status'] === 'success') {
                        // Simpan data ke database lokal
                        $datas = $request->only(['name', 'keterangan', 'uuid']);
                        $datas['keterangan'] = 'singkron';
                        $this->storeLocally($datas);
                        return redirect()->route('categorycabang.index')->with('success', 'Data berhasil disimpan dan disinkronkan ke server');
                    } else {
                        // Tangani kesalahan respons API
                        return redirect()->route('categorycabang.index')->with('error', 'Terjadi kesalahan saat menyinkronkan data ke server');
                    }
                } else {
                    // Simpan data ke database lokal tanpa menyinkronkan ke server
                    $data = $request->only(['name', 'keterangan', 'uuid']);
                    $data['keterangan'] = 'not_singkron';
                    $this->storeLocally($data);
                    return redirect()->route('categorycabang.index')->with('success', 'Data berhasil disimpan tetapi tidak disinkronkan ke server');
                }
            } catch (\Exception $e) {
              // Simpan data ke database lokal tanpa menyinkronkan ke server
                $data = $request->only(['name', 'keterangan', 'uuid']);
                $data['keterangan'] = 'not_singkron';
                $this->storeLocally($data);
                return redirect()->route('categorycabang.index')->with('success', 'Data berhasil disimpan tetapi tidak disinkronkan ke server');
            }

    }
    
    private function sendToApi($url, $data)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("$url/api/singkron", $data);

            if ($response->successful()) {
                return $response->json();
            } else {
                \Log::error('API Error: ' . $response->status() . ' - ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            \Log::error('Error sending data to API: ' . $e->getMessage());
            return null;
        }
    }

        
    private function storeLocally($datas)
    {
        try {
            DB::table('category_cabangs')->insert($datas);
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
