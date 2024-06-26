<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\cabang;
use App\Models\supplier;
use App\Models\category;
use App\Models\singkron;
use App\Models\history_transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        $data = $request->all();
        $url = env('APP_API');
        $response = Http::timeout(10)->get($url);

        try {
            $data['singkron'] = 'singkron';
            $data['key'] = 'distribusi';

            // Kirim data ke server API
            $apiResponse = $this->sendToApi($url, $data);

            // Cek status dari respons API
            if ($apiResponse && $apiResponse['status'] === 'success') {
                // Simpan data ke database lokal
                $this->storeLocally($data ,$request);
                return redirect()->back()->with('success', 'Data Distribusi  berhasil disimpan dan disinkronkan ke server');
            } else {
                // Tangani kesalahan respons API
                throw new \Exception('Terjadi kesalahan saat menyinkronkan data ke server');
            }
        } catch (\Exception $e) {
            // Tangani kesalahan apapun yang terjadi
            // Simpan data ke database lokal tanpa menyinkronkan ke server
            $data['singkron'] = 'not_singkron';
            $this->storeLocally($data , $request);
            return redirect()->back()->with('error', $e->getMessage());
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
    private function storeLocally($data , $request)
    {
        try {
        $singkron = $data['singkron'];
        $bulan = date('m');
        $tahun = date('y');
        $nomorUrut = str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
        $singkatan = "PD";
        $kode_tranasction = $singkatan.$bulan.$tahun.$nomorUrut;
        $total = count($request->input('jumlah'));
        $database = cabang::where('uuid', '=' ,"$request->id_cabang")->value('database');
        $nama = cabang::where('uuid', '=' ,"$request->id_cabang")->value('nama');
        for ($i=0; $i < $total; $i++) { 
            $kode = $request->input('kode')[$i];
            $stocks = $request->input('jumlah')[$i];
            $data = barang::where('kode_barang', '=' ,"$kode")->first();
            $check = DB::table("$database")->where('kode_barang', '=' ,"$kode")->first();
            if (!empty($check)) {
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
                    'kode_transaction'=>$kode_tranasction,
                    'id_cabang' => $request->id_cabang,
                    'singkron'=>$singkron,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                history_transaction::create($data_history);
                $singkron =  [
                    'name'=>'barang',
                    'status'=>'distribusi',
                    'uuid'=>$uuid,
                ];
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
                    'keterangan' => $kode_tranasction,
                    'created_at' => date('Y-m-d H:i:s')
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
                    'kode_transaction'=>$kode_tranasction,
                    'id_cabang' => $request->id_cabang,
                    'singkron'=>$singkron,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                history_transaction::create($data_history);

                $data_stock = $data->stok - $stocks;
                $data->update([
                    'stok' => $data_stock,
                ]);
                $singkron =  [
                    'name'=>'barang',
                    'status'=>'distribusi',
                    'uuid'=>$uuid,
                ];
            }
            singkron::create($singkron);
        }
            
        } catch (\Exception $e) {
            \Log::error('Error storing data locally: ' . $e->getMessage());
        }
    }
    
    public function barang($uuid){
        $barang =  barang::where('uuid', '!=', 'hidden')->limit(1500)->get();
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
