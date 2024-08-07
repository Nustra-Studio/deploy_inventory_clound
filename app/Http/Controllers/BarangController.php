<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\barang;
use App\Models\harga_khusus;
use App\Models\category_barang;
use App\Models\history_transaction;
use App\Models\suplier;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use App\Imports\BarangImport;
use App\Imports\BarangUpdates;
use App\Models\singkron;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('pages.barang.index');
    }
    public function datarespone (Request $request){
        $name = $request->name;
        // uuid item
            if($name === "barang"){
                $data = barang::where('uuid',$request->uuid)->select(['uuid','name'])->get();
                return response()->json($data, 200);
            }
        // harga khusus
            elseif($name === "harga"){
                $data = harga_khusus::where('id_barang', $request->uuid)->get();
                return response()->json($data, 200);
            }
        // null
        else{
            return response()->json("Data Not Found", 404);
        }
    }
    public function datatables(Request $request)
    {
        $barang = barang::select(['id', 'uuid','name','id_supplier', 'kode_barang', 'category_id', 'harga_pokok', 'harga_jual', 'stok'])
                     ->orderBy('created_at', 'desc'); // Order by latest created
    
        if ($request->input('search.value')) {
            $search = $request->input('search.value');
            $suplier = suplier::where('nama', $search)->value('uuid');
            $barang->where(function($query) use ($search, $suplier) {
                $query->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");

            }); // Limit search results to 3000 records
        }
        else{
            $barang->limit(1000);
        }
    
        return DataTables::of($barang)
            ->addIndexColumn() 
            ->addColumn('category', function ($row) {
                $category = category_barang::where('uuid', $row->category_id)->first();
                return $category ? $category->name : '';
            })
            ->editColumn('harga_pokok', function ($row) {
                return 'Rp '.number_format($row->harga_pokok, 0, ',', '.');
            })
            ->editColumn('harga_jual', function ($row) {
                return 'Rp '.number_format($row->harga_jual, 0, ',', '.');
            })
            ->addColumn('suplier', function ($row) {
                $suplier = suplier::where('uuid', $row->id_supplier)->first();
                return $suplier ? $suplier->nama : 'non';
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#dynamicModal"data-item-id="'.$row->uuid.'">Show</button>';
                $btn .= ' <a href="'.url("/barang/$row->uuid/edit").'" class="btn btn-primary btn-sm">Edit</a>';
                $btn .= ' <button class="btn btn-danger btn-sm delete-button" data-id="'.$row->uuid.'">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function datadistribusi(Request $request)
    {
        // $data = barang::all();
        $barang = barang::select(['id', 'name', 'kode_barang', 'category_id', 'harga_pokok', 'harga_jual', 'stok','id_supplier','uuid'])
                    ->where('stok', '>', 0)
                    ->orderBy('created_at', 'desc');

        if ($request->input('search.value')) {
            $search = $request->input('search.value');
            $barang->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
            })->limit(3000); // Limit search results to 3000 records
        }
        else{
            $barang->limit(1000);
        }
                
        return DataTables::of($barang)
            ->addIndexColumn() 
            ->addColumn('suplier', function ($row) {
                $suplier = suplier::where('uuid', $row->id_supplier)->first();
                return $suplier ? $suplier->nama : 'non';
            })
            ->addColumn('jumlah',function($row){
                $jumlah = '<input type="number" name="jumlah" value="0" class="form-control">';
                return $jumlah;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="text-center">
                    <button type="button" class="btn btn-primary btn-icon add-to-tb-barang">
                        ->
                    </button>
                </div>';
                $btn .= " <input type='hidden' name='kode' value='$row->kode_barang'>";
                $btn .= " <input type='hidden' name='nama' value='$row->name'>";
                $btn .= " <input type='hidden' name='stock' value='$row->stock'>";
                $btn .= " <input type='hidden' name='product' value='$row'>";
                return $btn;
            })
            ->rawColumns(['action','jumlah'])
            ->make(true);
    }
    public function excel(Request $request){
        try {
            Excel::import(new BarangImport, request()->file('file'));
            return redirect()->back()->with('success', 'Data Imported');
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
    public function updateexcel(Request $request){
        try {
            $start = request()->input('start');
            $end = request()->input('end');
            Excel::import(new BarangUpdates($start,$end), request()->file('file'));
            return redirect()->back()->with('success', 'Data Imported');
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
    public function getProductsBySupplier(Request $request)
    {
        $suppliers = $request->input('supplier');
        $supplier = suplier::where('name', $suppliers)->value('uuid');
        // Fetch the products based on the selected supplier
        $products = barang::where('id_supplier', $supplier)->get();
    
        return response()->json(['products' => $products]);
    }
    public function resource( Request $request)
    {
        $data = barang::where('uuid', '=' ,"$request->uuid")->get();
        return response()->json($data, 200);
    }
    public function list($uuid){
        $data = barang::where('uuid', $uuid)->frist();
        return response()->json($data);
    }
    public function input()
    {
        return view ('pages.barang.input_barang');
    }
    public function inputcreate(Request $request){
        $data = $request->input('data_table_values');
        $data = json_decode($data, true);
        $bulan = date('m');
        $tahun = date('y');
        $nomorUrut = str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
        $singkatan = "PM";
        $kode_tranasction = $singkatan.$bulan.$tahun.$nomorUrut;
        $uuid = Str::uuid()->toString();
        try{
            $url = env('APP_API');
            $response = Http::timeout(1)->get($url);
            if ($response->successful()) {
                // Prepare data for API request
                $data = [
                    'request'=>$request->all(),
                    'key'=>'input_barang'
                ];
        
                // Send data to the server API
                $apiResponse = $this->sendToApi($url, $data);
                Log::debug($data);
        
                // Check the status of the API response
                if ($apiResponse && $apiResponse['status'] === 'success') {
                    // Save data locally to the database
                    $data = $request->input('data_table_values');
                    $data = json_decode($data, true);
                    $keterangan = 'singkron';
                    $this->storeinput($data,$request,$keterangan);
        
                    return redirect()->route('barang.index')->with('success', "Data berhasil disimpan dan disinkronkan ke server $apiResponse");
                } else {
                    // Handle API response errors
                    return redirect()->route('barang.index')->with('error', 'Terjadi kesalahan saat menyinkronkan data ke server');
                }
            } 
            else {
                // Save data locally without synchronizing to the server
                $data = $request->input('data_table_values');
                $data = json_decode($data, true);
                $keterangan = 'not_singkron';
                $this->storeinput($data,$request,$keterangan);
        
                return redirect()->route('barang.index')->with('success', 'Data berhasil disimpan tidak tersingkron ke server');
            }
        }
            catch (\Exception $e) 
            {
                $data = $request->input('data_table_values');
                $data = json_decode($data, true);
                $keterangan = 'not_singkron';
                $this->storeinput($data,$request,$keterangan);
        
                return redirect()->route('barang.index')->with('success', 'Data berhasil disimpan tidak tersingkron ke server');
            }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('pages.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $data_terakhir = DB::table('barangs')->latest('kode_barang')->first();
        $kode_terakhir = $data_terakhir->kode_barang;
        $kategori = category_barang::where('uuid',$request->category_barang)->value('name') ;
        $bulan = date('m');
        $tahun = date('y');
        if ($kode_terakhir > 999) {
                $nomorUrut = str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            } else {
                $nomorUrut = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            }

        $kode = $kategori . $bulan . $tahun . $nomorUrut;
        $data_master =[
            'name' => $request->name,
            'merek_barang' => $request->merek_barang,
            'uuid' => $request->uuid,
            'id_supplier' => $request->supplier,
            'category_id' => $request->category_barang,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->jumlah,
            'kode_barang' => $kode,
            'keterangan' => 'singkron',
        ];
        $uuid= hash('sha256', uniqid(mt_rand(), true));
        $data_history = [
            'uuid' => $uuid,
            'name' => $request->name,
            'jumlah' => $request->jumlah,
            'kode_barang' => $kode,
            'uuid_barang' => $request->uuid,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'id_supllayer' => $request->supplier,
            'status' => 'masuk',
        ];
        $data_hargas = [];
        if(!empty($request->nama)){
        for ($j = 0; $j < count($request->nama); $j++) {
            $uuid = hash('sha256', uniqid(mt_rand(), true));
            $data_harga = [
                'uuid' => $uuid,
                'id_barang' => $request->uuid[$j],
                'harga' => $request->harga[$j],
                'jumlah_minimal' => $request->jumlah_minimal[$j],
                'diskon' => $request->diskon[$j],
                'keterangan' => $request->nama[$j],
                // 'satuan' => $request->satuan[$j],
            ];
        
            $data_hargas[] = $data_harga;
        }
    }
        try {
            $this->storeLocally($data_master , $data_history , $request);
        
            return redirect()->route('barang.index')->with('success', 'Data berhasil disimpan dan disinkronkan ke server');
        } catch (\Throwable $th) {
            return redirect()->route('barang.index')->with('error', 'Sistem Error');
        }
    }

    private function storeLocally($data_master, $data_history , $request)
        {
            try {
                $push = barang::create($data_master);
                $up = history_transaction::create($data_history);
                if(!empty($request->nama)){
                    for($j=0; $j < count($request->nama); $j++){
                        $uuid= hash('sha256', uniqid(mt_rand(), true));
                        $data_harga = [
                            'uuid' => $uuid,
                            'id_barang'=> $request->uuid,
                            'harga' => $request->harga[$j],
                            'jumlah_minimal' => $request->jumlah_minimal[$j],
                            'diskon' => $request->diskon[$j],
                            'keterangan' => $request->nama[$j],
                            // 'satuan' => $request->satuan[$j],
                        ];
                        $push = harga_khusus::create($data_harga);
                    }
                }
                $singkron =  [
                    'name'=>'barang',
                    'status'=>'create',
                    'uuid'=>$data_master['uuid'],
                ];
                singkron::create($singkron);
            } catch (\Exception $e) {
                \Log::error('Error storing data locally: ' . $e->getMessage());
            }
        }
    private function storeinput($data , $request ,$keterangan){
        try {
                $data = $request->input('data_table_values');
                $data = json_decode($data, true);
                $bulan = date('m');
                $tahun = date('y');
                $nomorUrut = str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
                $singkatan = "PM";
                $kode_tranasction = $singkatan.$bulan.$tahun.$nomorUrut;
                $uuid = Str::uuid()->toString();
                foreach ($data as $row) {
                    // $supplier = suplier::where('nama', $row['supplier'])->value('uuid');
                    // $stock = barang::where('name', $row['Name'])->value('stok');
                    $Name = $row['Name'];
                    $kode = barang::where('name', $Name)->first();
                    $stock = $kode->stock;
                    $stock = $stock + $row["jumlah"];
                    $supplier = $kode->id_supplier;
                    DB::table('barangs')->updateOrInsert(
                        ['name' => $row['Name']],
                        [
                            'stok' => $stock,
                            'Harga_pokok' => $row['Harga_pokok'],
                            'harga_jual' => $row['harga_jual'],
                            'id_supplier' => $supplier,
                        ]
                    );
                    $uuid= hash('sha256', uniqid(mt_rand(), true));
                    $data_history = [
                    'uuid' => $uuid,
                    'name' => $row['Name'],
                    'jumlah' => $row['jumlah'],
                    'kode_barang' => $kode->kode_barang,
                    'uuid_barang' => $kode->uuid,
                    'harga_pokok' => $kode->harga_pokok,
                    'harga_jual' => $kode->harga_jual,
                    'id_supllayer' => $supplier,
                    'kode_transaction'=>$kode_tranasction,
                    'status' => 'masuk',
                    'keterangan'=> $keterangan
                ];
                $singkron =  [
                    'name'=>'barang',
                    'status'=>'input',
                    'uuid'=>$data_master['uuid'],
                ];
                singkron::create($data_history['uuid']);
                $push = history_transaction::create($data_history);
                }
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
        $data = barang::where('uuid', $id)->first();
        $harga = harga_khusus::where('id_barang', $data->uuid)->get();
        return view ('pages.barang.update', compact('data', 'harga'));
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
        $this->validate(request(),
        [
            'name' => 'required',
            'supplier' => 'required',
            'category_barang' => 'required',
            'harga_pokok' => 'required',
            'harga_jual' => 'required',
            'jumlah' => 'required',
        ]);
        $data_master = [
            'name' => $request->name,
            'merek_barang' => $request->merek_barang,
            'id_supplier' => $request->supplier,
            'category_id' => $request->category_barang,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ];
        for ($j = 0; $j < count($request->input('nama')); $j++) {
                if(!empty($request->harga[$j])){
                    {
                        $this->validate(request(),
                        [
                            'nama.' . $j => 'required',
                            'harga.' . $j => 'required',
                            'jumlah_minimal.' . $j => 'required',
                            'diskon.' . $j => 'required',
                        ]);
            
                        if ($request->input('status')[$j] == 'update') {
                            $uuid_barang = $request->input('uuid_barang')[$j];
                            $data_harga = [
                                'harga' => $request->harga[$j],
                                'jumlah_minimal' => $request->jumlah_minimal[$j],
                                'diskon' => $request->diskon[$j],
                                'keterangan' => $request->nama[$j],
                            ];
                            $push = harga_khusus::where('id', $uuid_barang)->update($data_harga);
                        } elseif ($request->input('status')[$j] == 'tambah') {
                            $uuid = hash('sha256', uniqid(mt_rand(), true));
                            $data_harga = [
                                'id_barang'=> $id,
                                'harga' => $request->harga[$j],
                                'jumlah_minimal' => $request->jumlah_minimal[$j],
                                'diskon' => $request->diskon[$j],
                                'keterangan' => $request->nama[$j],
                                'uuid' => $uuid,
                            ];
                            $push = harga_khusus::create($data_harga);
                        }
                    }
                }
        }
        $push = barang::where('uuid', $id)->update($data_master);
        $singkron =  [
            'name'=>'barang',
            'status'=>'update',
            'uuid'=>$id,
        ];
        return redirect()->route('barang.index')->with('success', 'Data Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = barang::where('uuid', $id)->first();
        $data->delete();
        $singkron =  [
            'name'=>'barang',
            'status'=>'delete',
            'uuid'=>$id,
        ];
        return redirect()->route('barang.index')->with('success', 'Data Berhasil Di Hapus');
    }
    public function hapus(Request $request){
        $data = harga_khusus::where('id', $request->id)->first();
        $data->delete();
        $singkron =  [
            'name'=>'barang',
            'status'=>'hapus_harga',
            'uuid'=>$request->id,
        ];
        return redirect()->route('barang.index')->with('success', 'Data Berhasil Di Hapus');
    }
}
