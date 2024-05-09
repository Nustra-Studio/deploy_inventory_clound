<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cabang;
use App\Models\user_cabang;
use App\Models\singkron;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
// tambahkan db
use Illuminate\Support\Facades\DB;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = cabang::all();
        return view('pages.cabang.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('pages.cabang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                // create database for request data
        $data = $request->all();
        $singkron =  [
            'name'=>'cabang',
            'status'=>"create",
            'uuid'=>$request->uuid,
        ];
            $this->storeLocally($data);
            singkron::insert($singkron);
            return redirect()->route('cabang.index')->with('success', 'Data berhasil disimpan dan disinkronkan ke server');

        // $url = env('APP_API');
        // $response = Http::timeout(1)->get($url);

        // try {
        //     $data['keterangan'] = 'singkron';
        //     $data['key'] = 'cabang';

        //     // Kirim data ke server API
        //     $apiResponse = $this->sendToApi($url, $data);

        //     // Cek status dari respons API
        //     if ($apiResponse && $apiResponse['status'] === 'success') {
        //         // Simpan data ke database lokal
        //         $this->storeLocally($data);
        //         return redirect()->route('cabang.index')->with('success', 'Data berhasil disimpan dan disinkronkan ke server');
        //     } else {
        //         // Tangani kesalahan respons API
        //         throw new \Exception('Terjadi kesalahan saat menyinkronkan data ke server');
        //     }
        // } catch (\Exception $e) {
        //     // Tangani kesalahan apapun yang terjadi
        //     // Simpan data ke database lokal tanpa menyinkronkan ke server
        //     $data['keterangan'] = 'not_singkron';
        //     $this->storeLocally($data);
        //     return redirect()->route('cabang.index')->with('error', $e->getMessage());
        // }

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

        
    private function storeLocally($data)
    {
        try {
            $namas = $data['nama'];
            $nama = str_replace(' ', '_', $namas);
            $database = "cabang_$nama";
            $query = "
            CREATE TABLE cabang_$nama (
                `id` bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `category_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `id_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `kode_barang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_jual` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_pokok` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_grosir` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `stok` int(48) NOT NULL,
                `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `merek_barang` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `type_barang_id` varchar(158) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            DB::statement($query);
            $query2 = " 
            CREATE TABLE `transaction_$database` (
                `id` bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `name` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `jumlah` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `kode_barang` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `status` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `id_member` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `harga_pokok` int(21) DEFAULT NULL,
                `harga_jual` int(21) DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            DB::statement($query2);
            user_cabang::create([
                'cabang_id' => $data['uuid'],
                'uuid' => Str::random(40),
                'username' => "supervisor_$nama",
                'password' => Hash::make('cintabunda123'),
                'role' => "supervisor",
                'api_key' => Str::random(40),
            ]);
            $newdata = [
                'nama' => $data['nama'],
                'alamat' => $data['alamat'],
                'kepala_cabang' => $data['kepala_cabang'],
                'telepon' => $data['telepon'],
                'category_id' => $data['category_id'],
                'uuid'=> $data['uuid'],
                'database' => "cabang_$nama",
                'keterangan'=>$data['keterangan']
            ];
            cabang::create($newdata);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
                // create database for request data
                $data = cabang::where('uuid', $id)->first();
                return view('pages.cabang.update', compact('data'));
            
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
        // buatkan validasi
        $this->validate($request, [
            'nama' => 'required',
            'alamat' => 'required',
            'kepala_cabang' => 'required',
            'telepon' => 'required',
            'category_id' => 'required',
        ]);
        $data = $request->all();
        // update data
        $newdata = [
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'kepala_cabang' => $data['kepala_cabang'],
            'telepon' => $data['telepon'],
            'category_id' => $data['category_id'],
        ];
        $singkron =  [
            'name'=>'cabang',
            'status'=>"update",
            'uuid'=>$request->uuid,
        ];
        DB::table('cabangs')->where('uuid', $id)->update($newdata);
        singkron::insert($singkron);
        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
                // delete data
                $data = cabang::where('id', $id)->first();
                $nama = str_replace(' ', '_', $data->nama);
                $database = "cabang_$nama";
                DB::statement("DROP TABLE cabang_$nama");
                DB::statement("DROP TABLE transaction_$database");
                cabang::where('id', $id)->delete();
                $singkron =  [
                    'name'=>'cabang',
                    'status'=>"delete",
                    'uuid'=>$request->uuid,
                ];
                singkron::insert($singkron);
                return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil dihapus');
    }
}
