<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opname; // Assuming your model name is Opname
use Illuminate\Support\Facades\Auth;

class OpnameController extends Controller
{
    // ...

    public function index()
    {
        return view('pages.opname.index');
    }

    public function store(Request $request)
    {
        $data = json_decode($request->input('data_table_values'), true);

        foreach ($data as $item) {
            Opname::where('barcode', $item['barcode'])
                ->where('id_toko', $item['id_toko'])
                ->update([
                    'perubahan' => $item['jumlah'],
                    'status' => 'new',
                ]);
        }

        return redirect()->route('opname.index')->with('success', 'Data Berhasil Di Tambahkan');
    }

    // ...

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
