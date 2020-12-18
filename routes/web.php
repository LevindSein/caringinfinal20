<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PedagangController;
use App\Http\Controllers\SearchController;

use App\Models\User;

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

//HOME
Route::get('/', function(){
    return redirect()->route('login');
});
Route::get('login',function(){
    return view('home.login');
})->name('login');

//LOGIN
Route::post('storelogin')->middleware('ceklogin:home');
//LOGOUT
Route::get('logout',function(){
    Session::flush();
    Artisan::call('cache:clear');
    return redirect()->route('login')->with('success','Sampai Bertemu');
});

Route::middleware('ceklogin:dashboard')->group(function () {
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('ceklogin:pedagang')->group(function (){
    Route::resource('pedagang', PedagangController::class);    
    Route::post('pedagang/update', [PedagangController::class, 'update']);
    Route::get('pedagang/destroy/{id}', [PedagangController::class, 'destroy']);
});

Route::get('cari/blok',[SearchController::class, 'cariBlok']);
Route::get('cari/nasabah',[SearchController::class, 'cariNasabah']);
Route::get('cari/alamat',[SearchController::class, 'cariAlamat']);