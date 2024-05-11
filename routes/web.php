<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CategoryCabangController;
use App\Http\Controllers\SingkronController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
Route::get('/', function () {
    return redirect()->route('barang.index');
});
Route::get('/home',function(){
    return redirect('/barang');
});


Route::group(['prefix' => 'error'], function(){
    Route::get('404', function () { return view('pages.error.404'); });
    Route::get('500', function () { return view('pages.error.500'); });
});
        Route::middleware(['opname'])->group(function () {
            Route::resource('opname', OpnameController::class);
            Route::get('opname/{id}/show', 'OpnameController@product')->name('opname.product');
            Route::post('opname/excel','OpnameController@excel')->name('opname.excel');
        });
        Route::get('/login-opname', 'OpnameAuthController@index')->name('opname.login');
        Route::prefix('opname')->group(function () {
            \Log::info('Opname controller started');
            Route::get('/login', 'OpnameAuthController@index')->name('opname.login');
            Route::post('/login', 'OpnameAuthController@login')->name('opname.login');
            Route::post('/logout', 'OpnameController@logout');
        });

    Route::middleware(['auth'])->group(function () {
        Route::prefix('resource')->group(function () {
            // buat kan route barang resource metode get 
            Route::get('/barang', 'BarangController@resource')->name('barang.resource');
            Route::get('/barang/datatables ', 'BarangController@datatables')->name('barang.datatables');
        });
        Route::resource('/singkron', SingkronController::class);
        Route::prefix('pdf')->group(function () {
            // Route::get('/pembelian', 'TransactionController@pembelian_pdf')->name('transaction.pembelian.pdf');
            // Route::get('/pengeluaran', 'TransactionController@pengeluaran_pdf')->name('transaction.pengeluaran.pdf');
            Route::post('/pembelian', 'TransactionController@pembelian_pdf')->name('transaction.pembelian.pdf');
            Route::post('/pengeluaran', 'TransactionController@pengeluaran_pdf')->name('transaction.pengeluaran.pdf');
        });
        Route::resource('user', UserController::class);
        Route::post('/pembelian', 'TransactionController@pembelian_cari')->name('transaction.pembelian.cari');
        Route::post('/pengeluaran', 'TransactionController@pengeluaran_cari')->name('transaction.pengeluaran.cari');
        Route::resource('/barang', BarangController::class);
        Route::post('/barang/excel','BarangController@excel')->name('barang.excel');

        Route::post('/barang/hapus', 'BarangController@hapus')->name('barang.hapus');
        Route::get('/barang/{uuid}/list', 'BarangController@list')->name('barang.list');
        Route::get('/supllier/barang/{uuid}', 'SupplierController@barang')->name('supplier.barang');
        Route::get('/supllier/list', 'SupplierController@list')->name('supplier.list');
        Route::get('/input-barang', 'BarangController@input')->name('barang.input');
        Route::post('/input-barang', 'BarangController@inputcreate')->name('barang.input.create');
        Route::get('/product/{id}/show', 'SupplierController@caribarang')->name('supplier.getProductsBySupplier');
        // route resource category,cabang,supplier,distribusi,transaction
        Route::get('/harga/{id}/{harga}/barang', 'BarangController@harga')->name('barang.harga');
    
        Route::resource('/category', CategoryController::class);
        Route::resource('/categorycabang', CategoryCabangController::class);
    
    
        Route::resource('/cabang', CabangController::class);
        Route::resource('/supllier', SupplierController::class);
        Route::resource('/distribusi', DistribusiController::class);
        Route::get('/distribusi/{uuid}/barang', 'DistribusiController@barang')->name('distribusi.barang');
        Route::post('/distribusi/barang', 'DistribusiController@barangstore')->name('distribusi.barang.store');
        Route::resource('/transaction', TransactionController::class);
        // route get pembelian pengeluaran dari root transaction
        Route::get('/pembelian', [TransactionController::class, 'pembelian'])->name('transaction.pembelian');
        Route::get('/pengeluaran', [TransactionController::class, 'pengeluaran'])->name('transaction.pengeluaran');
        
    
    });
// route resource  
    Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
