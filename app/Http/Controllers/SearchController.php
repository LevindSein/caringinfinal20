<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blok;
use App\Models\User;
use App\Models\TempatUsaha;
use App\Models\AlatAir;
use App\Models\AlatListrik;

class SearchController extends Controller
{
    public function cariBlok(Request $request){
        $blok = [];
        if ($request->has('q')) {
            $cariBlok = $request->q;
            $blok = Blok::select('id', 'nama')->where('nama', 'LIKE', '%'.$cariBlok.'%')->get();
        }
        return response()->json($blok);
    }

    public function cariNasabah(Request $request){
        $nasabah = [];
        if ($request->has('q')) {
            $cariNasabah = $request->q;
            $nasabah = User::select('id', 'nama', 'ktp')
            ->where('nama', 'LIKE', '%'.$cariNasabah.'%')
            ->orWhere('ktp', 'LIKE', '%'.$cariNasabah.'%')
            ->get();
        }
        return response()->json($nasabah);
    }

    public function cariAlamat(Request $request){
        $alamat = [];
        if ($request->has('q')) {
            $cariAlamat = $request->q;
            $alamat = TempatUsaha::select('id', 'kd_kontrol')->where('kd_kontrol', 'LIKE', '%'.$cariAlamat.'%')->get();
        }
        return response()->json($alamat);
    }

    public function cariAlatAir(Request $request){
        $alat = [];
        if ($request->has('q')) {
            $cariAlat = $request->q;
            $alat = AlatAir::where([['kode', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orWhere([['nomor', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->get();
        }
        return response()->json($alat);
    }

    public function cariAlatListrik(Request $request){
        $alat = [];
        if ($request->has('q')) {
            $cariAlat = $request->q;
            $alat = AlatListrik::where([['kode', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orWhere([['nomor', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orWhere([['daya', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->get();
        }
        return response()->json($alat);
    }
}
