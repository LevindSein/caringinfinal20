<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PedagangController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\BlokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;

use App\Models\User;
use App\Models\LoginLog;

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
    Route::post('pedagang/update', [PedagangController::class, 'update']);
    Route::get('pedagang/destroy/{id}', [PedagangController::class, 'destroy']);
    Route::resource('pedagang', PedagangController::class);
});

Route::middleware('ceklogin:tarif')->group(function(){
    Route::get('utilities/tarif', [TarifController::class, 'index']);
    Route::get('utilities/tarif/keamananipk', [TarifController::class, 'keamananipk']);
    Route::get('utilities/tarif/kebersihan', [TarifController::class, 'kebersihan']);
    Route::get('utilities/tarif/airkotor', [TarifController::class, 'airkotor']);
    Route::get('utilities/tarif/lain', [TarifController::class, 'lain']);
    Route::post('utilities/tarif/store', [TarifController::class, 'store']);
    Route::get('utilities/tarif/edit/{fasilitas}/{id}', [TarifController::class, 'edit']);
    Route::post('utilities/tarif/update', [TarifController::class, 'update']);
    Route::get('utilities/tarif/destroy/{fasilitas}/{id}', [TarifController::class, 'destroy']);
});

Route::middleware('ceklogin:alatmeter')->group(function(){
    Route::get('utilities/alatmeter', [AlatController::class, 'index']);
    Route::get('utilities/alatmeter/air', [AlatController::class, 'air']);
    Route::post('utilities/alatmeter/store', [AlatController::class, 'store']);
    Route::get('utilities/alatmeter/edit/{fasilitas}/{id}', [AlatController::class, 'edit']);
    Route::post('utilities/alatmeter/update', [AlatController::class, 'update']);
    Route::get('utilities/alatmeter/destroy/{fasilitas}/{id}', [AlatController::class, 'destroy']);
    Route::get('utilities/alatmeter/qr/{fasilitas}/{id}', [AlatController::class, 'qr']);
});

Route::middleware('ceklogin:harilibur')->group(function(){
    Route::get('utilities/harilibur', [HariLiburController::class, 'index']);
    Route::post('utilities/harilibur/store', [HariLiburController::class, 'store']);
    Route::get('utilities/harilibur/edit/{id}', [HariLiburController::class, 'edit']);
    Route::post('utilities/harilibur/update', [HariLiburController::class, 'update']);
    Route::get('utilities/harilibur/destroy/{id}', [HariLiburController::class, 'destroy']);
});

Route::middleware('ceklogin:blok')->group(function(){
    Route::get('utilities/blok', [BlokController::class, 'index']);
    Route::post('utilities/blok/store', [BlokController::class, 'store']);
    Route::get('utilities/blok/edit/{id}', [BlokController::class, 'edit']);
    Route::post('utilities/blok/update', [BlokController::class, 'update']);
    Route::get('utilities/blok/destroy/{id}', [BlokController::class, 'destroy']);
});

Route::middleware('ceklogin:user')->group(function(){
    Route::post('user/update', [UserController::class, 'update']);
    Route::get('user/destroy/{id}', [UserController::class, 'destroy']);
    Route::post('user/reset/{id}', [UserController::class, 'reset']);
    Route::get('user/{id}/otoritas', [UserController::class, 'etoritas']);
    Route::post('user/otoritas', [UserController::class, 'otoritas']);
    Route::get('user/manajer', [UserController::class, 'manajer']);
    Route::get('user/keuangan', [UserController::class, 'keuangan']);
    Route::get('user/kasir', [UserController::class, 'kasir']);
    Route::get('user/nasabah', [UserController::class, 'nasabah']);
    Route::resource('user', UserController::class);
});

Route::middleware('ceklogin:log')->group(function(){
    Route::get('log',function(Request $request){
        if($request->ajax())
        {
            $data = LoginLog::orderBy('created_at','desc')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('ktp', function ($ktp) {
                        if ($ktp->ktp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                        else return $ktp->ktp;
                    })
                    ->editColumn('hp', function ($hp) {
                        if ($hp->hp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                        else return $hp->hp;
                    })
                    ->editColumn('created_at', function ($user) {
                        return [
                           'display' => $user->created_at->format('d-m-Y H:i:s'),
                           'timestamp' => $user->created_at->timestamp
                        ];
                     })
                    ->rawColumns(['ktp','hp'])
                    ->make(true);
        }
        return view('log.index');
    })->middleware('log');
});

Route::get('cari/blok',[SearchController::class, 'cariBlok']);
Route::get('cari/nasabah',[SearchController::class, 'cariNasabah']);
Route::get('cari/alamat',[SearchController::class, 'cariAlamat']);