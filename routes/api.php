<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', 'Api\ApiCabang@login')->name('api.login');
Route::post('/singkron','Api\ApiSingkron@store');
Route::group(['middleware' => 'ApiCabang'], function () {
    // get route barang
    Route::get('/barang', 'Api\ApiCabang@barang')->name('api.barang');
    Route::get('/supplier', 'Api\ApiCabang@supplier')->name('api.supplier');
    Route::post('/createuser','Api\ApiCabang@usercreate')->name('api.createuser');
    Route::post('/updateuser','Api\ApiCabang@userupdate')->name('api.updateuser');
    Route::post('/deleteuser','Api\ApiCabang@userdelete')->name('api.deleteuser');
    Route::post('/deletetransaction','Api\ApiCabang@deletebarang')->name('api.deletebarang');
    Route::prefix('opname')->group(function () {
        Route::post('/push','Api\ApiCabang@opname')->name('api.opname');
        Route::get('/get','Api\ApiCabang@opnamelist')->name('api.list.opname');
        Route::post('/return','Api\ApiCabang@returnopname')->name('api.returnopname');
    });
    // listcabang dengan model
    Route::get('/listcabang', 'Api\ApiCabang@listcabang')->name('api.listcabang');
    Route::prefix('cabangmember')->group(function () {
        Route::post('/register', 'Api\ApiMember@register')->name('api.member.register');
        Route::get('/membertoken', 'Api\ApiMember@membertoken')->name('api.member');
        Route::post('/belanja', 'Api\ApiMember@belanja')->name('api.member.belanja');
        Route::get('/poin','Api\ApiMember@poin')->name('api.member.poin');
        Route::post('/transaksi','Api\ApiMember@transaksi')->name('api.member.transaksi');
        Route::post('/resetmember','Api\ApiMember@reset')->name('api.member.reset');
    });
    
});
Route::prefix('member')->group( function () {
    Route::group(['middleware' => 'Cors'], function() {
        // Route::post('/loginowner', 'Api\ApiMember@login')->name('api.member.login');
        Route::match(['post', 'options'], "/loginowner", "Api\ApiMember@login")->middleware("cors");
    });
    Route::post('/login', 'Api\ApiMember@login')->name('api.member.login');
    Route::post('/register', 'Api\ApiMember@register')->name('api.member.register');
    Route::group(['middleware' => 'ApiMember'], function () {
        // get route barang
        Route::get('/home', 'Api\ApiMember@home')->name('api.member.home');
        Route::get('/transaction', 'Api\ApiMember@transaction')->name('api.member.transaction');
        Route::get('/editmember', 'Api\ApiMember@editdata')->name('api.member.edit');
        Route::post('/updatemember', 'Api\ApiMember@updatemember')->name('api.member.update');
        
    });
});

Route::prefix('owner')->group(function (){
    Route::post('/login', 'Api\ApiOwner@login')->name('api.owner.login');
    Route::group(['middleware'=>'ApiOwner'], function(){
        // get cabang name
        Route::get('/listcabang', 'Api\ApiOwner@cabang')->name('api.owner.cabang');
        Route::get('/laporan','Api\ApiOwner@cabanglaporan')->name('api.owner.laporan');
        Route::get('/home', 'Api\ApiOwner@cabangbarang')->name('api.owner.cabangbarang');
        Route::get('/gudang/add', 'Api\ApiOwner@gudangadd')->name('api.owner.gudangadd');
        Route::get('/gudang/out', 'Api\ApiOwner@gudangout')->name('api.owner.gudangout');
        Route::get('/top-10', 'Api\ApiOwner@top')->name('api.owner.top');
        Route::get('/hariancabang','Api\ApiOwner@hariancabang')->name('api.harian.cabang');

    });

});
