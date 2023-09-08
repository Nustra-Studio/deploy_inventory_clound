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
Route::group(['middleware' => 'ApiCabang'], function () {
    // get route barang
    Route::get('/barang', 'Api\ApiCabang@barang')->name('api.barang');
    Route::get('/supplier', 'Api\ApiCabang@supplier')->name('api.supplier');
    Route::post('/createuser','Api\ApiCabang@usercreate')->name('api.createuser');
    // listcabang dengan model
    Route::get('/listcabang', 'Api\ApiCabang@listcabang')->name('api.listcabang');
    Route::prefix('cabangmember')->group(function () {
        Route::post('/register', 'Api\ApiMember@register')->name('api.member.register');
        Route::get('/membertoken', 'Api\ApiMember@membertoken')->name('api.member');
        Route::post('/belanja', 'Api\ApiMember@belanja')->name('api.member.belanja');
        Route::get('/poin','Api\ApiMember@poin')->name('api.member.poin');
        Route::post('/transaksi','Api\ApiMember@transaksi')->name('api.member.transaksi');
    });
    
});
Route::prefix('member')->group(function () {
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
    Route::group(['middleware'=>'ApiOwener'], function(){
        // get cabang name
        Route::get('/listcabang', 'Api\ApiOwner@cabang')->name('api.owner.cabang');
        Route::get('/laporan','Api\ApiOwner@cabanglaporan')->name('api.owner.laporan')
        Route::get('/home', 'Api\ApiOwner@cabangbarang')->name('api.owner.cabangbarang');
        Route::get('/gudang/add', 'Api\ApiOwner@gudangadd')->name('api.owner.gudangadd');
        Route::get('/gudang/out', 'Api\ApiOwner@gudangout')->name('api.owner.gudangout');

    });
});
