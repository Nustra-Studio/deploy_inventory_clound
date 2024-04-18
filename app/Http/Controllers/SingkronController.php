<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\suplier;
use App\Models\barang;
use Illuminate\Support\Facades\Http;
use App\Models\singkron;
use App\Models\singkronlog;

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
            $response = Http::timeout(1)->get($url);
            // Check if the API request was successful
            if ($response->successful()) {
                $singkron = singkron::all();
                    foreach($singkron as $item){
                        switch($item->name){
                            case'supplier':
                                $datas = suplier::where('uuid',$item->uuid)->first();
                                $data = [
                                    'key'=>$item->name,
                                    'status'=>$item->status,
                                    'data'=>$datas
                                ];
                                $apiResponse = $this->sendToApi($url, $data);
                                if ($apiResponse && $apiResponse['status'] === 'success') {
                                    dd('success');
                                }
                                else{
                                    dd('error');
                                }
                            break;
                        }
                }
            }
        } catch (\Exception $e) {
            dd($e);
            // return redirect()->route('supllier.index')->with('success', 'Data berhasil disimpan tetapi tidak disinkronkan ke server');
        
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
