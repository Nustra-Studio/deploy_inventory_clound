<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\history_transaction;
use Carbon\Carbon;
use PDF;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    // public function pembelian() penjualan ()\
    public function pembelian()
    {
        $data = history_transaction::where('status','masuk')->get();
        $hidde = [
            'supplier' => 'all',
            'from' => '',
            'to' => '',
        ];
        return view('pages.transaction.pembelian',compact('data','hidde'));
    }
    public function pengeluaran()
    {
        $data = history_transaction::where('status','keluar')->get();
        $hidde = [
            'cabang' => 'all',
            'from' => '',
            'to' => '',
        ];
        return view('pages.transaction.pengeluaran',compact('data','hidde'));
    }
    public function pembelian_pdf(Request $request)
    {
    
        if($request->to == null){
            if($request->supplier == "all"){
                $data = history_transaction::where('status','masuk')->get();
            }
            else{
                $data = history_transaction::where('status','masuk')->where('id_supllayer',$request->supplier)->get();
                
            }
        }
        else{
            if($request->supplier == "all"){
                $data = history_transaction::where('status','masuk')->whereBetween('created_at', [$request->from , $request->to ])->get();
            }
            else{
                $data = history_transaction::where('status','masuk')->where('id_supllayer',$request->supplier)->whereBetween('created_at', [$request->from , $request->to ])->get();
                
            }
        }
        if ($data->isEmpty()) {
            return redirect()->back()->with('error','Data Tidak Ditemukan');
        }
        else{
            $status = "masuk";

            // Generate the PDF
            $pdf = PDF::loadView('pages.transaction.pdf', compact('data', 'status'));
            
            // Set the paper size
            $pdf->setPaper('a4');
            
            // Stream or download the PDF
            return $pdf->stream('document.pdf');
        }
        
    }
    public function pengeluaran_pdf(Request $request)
    {   
        if($request->to == null){
            if($request->cabang == "all"){
                $data = history_transaction::where('status','keluar')->get();
            }
            else{
                $data = history_transaction::where('status','keluar')->where('id_cabang',$request->cabang)->get();
                
            }
        }
        else{
            if($request->cabang == "all"){
                $data = history_transaction::where('status','keluar')->whereBetween('created_at', [$request->from , $request->to ])->get();
            }
            else{
                $data = history_transaction::where('status','keluar')->where('id_cabang',$request->cabang)->whereBetween('created_at', [$request->from , $request->to ])->get();
                
            }
        }
        $status = "keluar";
        if ($data->isEmpty()) {
            return redirect()->back()->with('error','Data Tidak Ditemukan');
        }
        else{
            // Generate the PDF
            $pdf = PDF::loadView('pages.transaction.pdf', compact('data', 'status'));
            
            // Set the paper size
            $pdf->setPaper('a4');
            
            // Stream or download the PDF
            return $pdf->stream('document.pdf');
        }

    }
    public function pembelian_cari(Request $request)
    {
        $date_from = Carbon::createFromFormat('d-M-Y', $request->from);
        $date_to = Carbon::createFromFormat('d-M-Y', $request->to);
        $from = $date_from->format('Y-m-d');
        $to = $date_to->format('Y-m-d');
        if($request->supplier == "all"){
            $data = history_transaction::where('status','masuk')->whereBetween('created_at', [$from , $to ])->get();
        }
        else{
            $data = history_transaction::where('status','masuk')->where('id_supllayer',$request->supplier)->whereBetween('created_at', [$from , $to ])->get();
            
        }
        $hidde = [
            'supplier' => $request->supplier,
            'from' => $from,
            'to' => $to,
        ];
        return view('pages.transaction.pembelian',compact('data','hidde'));
    }
    public function pengeluaran_cari(Request $request)
    {
        $date_from = Carbon::createFromFormat('d-M-Y', $request->from);
        $date_to = Carbon::createFromFormat('d-M-Y', $request->to);
        $from = $date_from->format('Y-m-d');
        $to = $date_to->format('Y-m-d');
        if($request->cabang == "all"){
            $data = history_transaction::where('status','keluar')->whereBetween('created_at', [$from , $to ])->get();
        }
        else{
            $data = history_transaction::where('status','keluar')->where('id_cabang',$request->cabang)->whereBetween('created_at', [$from , $to ])->get();
            
        }
        $hidde = [
            'cabang' => $request->cabang,
            'from' => $from,
            'to' => $to,
        ];
        return view('pages.transaction.pengeluaran',compact('data','hidde'));
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
