<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blok;
use App\Models\User;
use App\Models\TempatUsaha;
use App\Models\AlatAir;
use App\Models\AlatListrik;
use App\Models\Tagihan;
use App\Models\IndoDate;

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
            ->orderBy('updated_at','desc')
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
            ->orderBy('updated_at','desc')
            ->get();
        }
        return response()->json($alat);
    }

    public function cariTagihan(Request $request, $id){
        if($request->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_tagihan);
            return response()->json(['result' => $result]);
        }
    }

    public function cariListrik(Request $request, $id){
        if($request->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_listrik);
            return response()->json(['result' => $result]);
        }
    }

    public function cariAirBersih(Request $request, $id){
        if($request->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_airbersih);
            return response()->json(['result' => $result]);
        }
    }
}
