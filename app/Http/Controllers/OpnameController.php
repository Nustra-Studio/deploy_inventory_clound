<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_cabang;
use App\Models\UserCabang;
use App\Models\opname;
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
            $data = opname::where('id_toko', $id)->get();
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
    public function showLoginForm()
    {
        return view('pages.auth.login_opname');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if ($this->customAuthenticate($credentials)) {
            return redirect()->intended('/opname');
        } else {
            return redirect('/opname/login')->with('error', 'Invalid credentials');
        }
    }

    private function customAuthenticate($credentials)
    {
        if (Auth::guard('user_cabang')->attempt($credentials)) {
            return true;
        }

        return false;
    }
    public function logout()
    {
        Auth::guard('user_cabang')->logout();
        return redirect('/opname/login');
    }

}
