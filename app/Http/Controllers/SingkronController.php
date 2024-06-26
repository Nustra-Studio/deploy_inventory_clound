<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\suplier;
use App\Models\barang;
use Illuminate\Support\Facades\Http;
use App\Models\singkron;
use App\Models\singkronlog;
use App\Models\category_cabangs;
use App\Models\category_barang;
use App\Models\cabang;
use App\Models\user_cabang;

class SingkronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = env('APP_API');
        try {
            // Make an HTTP request to the API
            $response = Http::timeout(2)->get($url);
            // Check if the API request was successful
            if ($response->successful()) {
                $singkron = singkron::all();
                    foreach($singkron as $item){
                        switch($item->name){
                            case'supplier':
                                $datas = suplier::where('uuid',$item->uuid)->first();
                                if(!empty($datas) || $item->status == 'delete'){
                                    if($item->status === 'delete'){
                                        $datas = $item;
                                    }
                                    $upload = Arr::except($datas->toArray(), ['id']);
                                    $data = [
                                        'key'=>$item->name,
                                        'status'=>$item->status,
                                        'data'=>$upload,
                                    ];
                                    // $data = $data['data'];
                                    // dd($data);
                                    $apiResponse = $this->sendToApi($url, $data);
                                    if ($apiResponse && $apiResponse['status'] === 'success') {
                                        singkron::where('id',$item->id)->delete();
                                    }
                                }
                                else{
                                    singkron::where('id',$item->id)->delete();
                                }
                            break;
                            case'categorycabang':
                                $datas = category_cabangs::where('uuid',$item->uuid)->first();
                                if(!empty($datas) || $item->status == 'delete'){
                                    if($item->status === 'delete'){
                                        $datas = $item;
                                    }
                                    $data = [
                                        'key'=>$item->name,
                                        'status'=>$item->status,
                                        'data'=>$datas->toArray(),
                                    ];
                                    $apiResponse = $this->sendToApi($url, $data);
                                    if ($apiResponse && $apiResponse['status'] === 'success') {
                                        singkron::where('id',$item->id)->delete();
                                    }
                                }
                                else{
                                    singkron::where('id',$item->id)->delete();
                                }
                            break;
                            case'categorybarang':
                                $datas = category_barang::where('uuid',$item->uuid)->first();
                                if(!empty($datas) || $item->status == 'delete'){
                                    if($item->status === 'delete'){
                                        $datas = $item;
                                    }
                                    $data = [
                                        'key'=>$item->name,
                                        'status'=>$item->status,
                                        'data'=>$datas->toArray(),
                                    ];
                                    $apiResponse = $this->sendToApi($url, $data);
                                    if ($apiResponse && $apiResponse['status'] === 'success') {
                                        singkron::where('id',$item->id)->delete();
                                    }
                                }
                                else{
                                    singkron::where('id',$item->id)->delete();
                                }
                            break;
                            case'cabang':
                                $cabang = cabang::where('uuid',$item->uuid)->first();
                                $user = user_cabang::where('cabang_id',$item->uuid)->first();
                                $data = [
                                    'key'=>$item->name,
                                    'status'=>$item->status,
                                    'cabang'=>$cabang,
                                    'user'=>$user
                                ];
                                $apiResponse = $this->sendToApi($url, $data);
                                    singkron::where('id',$item->id)->delete();
                            break;
                        }
                }
                return response()->json(['status' => 'success', 'message' => 'Success Singkron'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        
        }
    }
    private function sendToApi($url, $data)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("$url/api/singkron", $data);
            $message = $response->json();
            $send=[
                'name'=>$message['message'],
                'status'=>$message['status'],
            ];
            singkronlog::insert($send);
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = singkronlog::all();
        dd($data);
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
