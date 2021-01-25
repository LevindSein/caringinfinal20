<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;

use App\Models\TempatUsaha;
use App\Models\Pedagang;

use App\Models\AlatListrik;
use App\Models\AlatAir;

use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

use App\Models\PasangAlat;
use App\Models\Sinkronisasi;
use App\Models\Tagihan;
use App\Models\Bongkaran;

use App\Models\IndoDate;

use App\Models\Dokumen;

class TempatController extends Controller
{
    public function __construct()
    {
        $this->middleware('tempatusaha');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = TempatUsaha::orderBy('kd_kontrol', 'asc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Print QR" name="qr" id="'.$data->id.'" class="qr"><i class="fas fa-qrcode" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('no_alamat', function ($data) {
                    return '<span style="white-space:normal;">'.$data->no_alamat.'</span>';
                })
                ->editColumn('stt_tempat', function ($data) {
                    if($data->stt_tempat == 2) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                })
                ->editColumn('dis_airbersih', function ($data) {
                    if($data->dis_airbersih == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                })
                ->editColumn('dis_listrik', function ($data) {
                    if($data->dis_listrik == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                })
                ->editColumn('dis_keamananipk', function ($data) {
                    if($data->dis_keamananipk === null || $data->dis_keamananipk === 0) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                })
                ->editColumn('dis_kebersihan', function ($data) {
                    if($data->dis_kebersihan === null || $data->dis_kebersihan === 0) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                })
                ->addColumn('pengguna', function($data){
                    $pengguna = Pedagang::where('id',$data->id_pengguna)->select('nama')->first();
                    if($pengguna == null) return '<span style="color:#1cc88a;">idle</span>';
                    else return $pengguna['nama'];
                })
                ->addColumn('pemilik', function($data){
                    $pemilik = Pedagang::where('id',$data->id_pemilik)->select('nama')->first();
                    if($pemilik == null) return '<span style="color:#1cc88a;">idle</span>';
                    else return $pemilik['nama'];
                })
                ->addColumn('kodeAir', function($data){
                    $kode = AlatAir::where('id',$data->id_meteran_air)->select('kode')->first();
                    if($kode == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $kode['kode'];
                })
                ->addColumn('kodeListrik', function($data){
                    $kode = AlatListrik::where('id',$data->id_meteran_listrik)->select('kode')->first();
                    if($kode == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $kode['kode'];
                })
                ->addColumn('trfKeamananIpk', function($data){
                    $tarif = TarifKeamananIpk::where('id',$data->trf_keamananipk)->select('tarif')->first();
                    if($tarif == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($tarif['tarif']);
                })
                ->addColumn('trfKebersihan', function($data){
                    $tarif = TarifKebersihan::where('id',$data->trf_kebersihan)->select('tarif')->first();
                    if($tarif == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($tarif['tarif']);
                })
                ->addColumn('trfLain', function($data){
                    $tarif = TarifLain::where('id',$data->trf_lain)->select('tarif')->first();
                    if($tarif == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($tarif['tarif']);
                })
                ->addColumn('trfArkot', function($data){
                    $tarif = TarifAirKotor::where('id',$data->trf_airkotor)->select('tarif')->first();
                    if($tarif == null) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($tarif['tarif']);
                })
                ->rawColumns([
                    'action',
                    'no_alamat',
                    'pengguna',
                    'pemilik',
                    'stt_tempat',
                    'kodeAir',
                    'kodeListrik',
                    'trfKeamananIpk',
                    'trfKebersihan',
                    'trfLain',
                    'trfArkot',
                    'dis_keamananipk',
                    'dis_kebersihan',
                    'dis_airbersih',
                    'dis_listrik'])
                ->make(true);
        }
        return view('tempatusaha.data',[
            'airAvailable'=>TempatUsaha::airAvailable(),
            'listrikAvailable'=>TempatUsaha::listrikAvailable(),
            'trfKeamananIpk'=>TempatUsaha::trfKeamananIpk(),
            'trfKebersihan'=>TempatUsaha::trfKebersihan(),
            'trfAirKotor'=>TempatUsaha::trfAirKotor(),
            'trfLain'=>TempatUsaha::trfLain()
        ]);
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
        try{
            $tanggal = date("Y-m-d", time());

            //deklarasi model
            $tempat = new TempatUsaha;

            //blok
            $blok = $request->blok;
            $tempat->blok = $blok;
            
            //no_alamat
            $los = strtoupper($request->los);
            $tempat->no_alamat = $los;
            
            //jml_alamat
            $los = explode(",",$los);
            $tempat->jml_alamat = count($los);
            
            //kd_kontrol
            $kode = TempatUsaha::kode($blok,$los);
            $tempat->kd_kontrol = $kode;
            
            //bentuk_usaha
            if($request->usaha != null)
                $tempat->bentuk_usaha = ucwords($request->usaha);
            else
                $tempat->bentuk_usaha = NULL;
            
            //lok_tempat
            $lokasi = $request->lokasi;
            if($lokasi != NULL){
                $tempat->lok_tempat = $lokasi;
            }

            //id_pemilik
            $id_pemillik = $request->pemilik;
            $tempat->id_pemilik = $id_pemillik;

            // //id_pengguna
            $id_pengguna = $request->pengguna;
            $tempat->id_pengguna = $id_pengguna;

            //Fasilitas
            if(empty($request->air) == FALSE){
                $tempat->trf_airbersih = 1;
                $id_meteran_air = $request->meterAir;
                $tempat->id_meteran_air = $id_meteran_air;

                //Normal
                if($request->radioAlatAir == 'normal_airbersih'){
                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();
                }

                //Pasang Alat
                if($request->radioAlatAir == 'pasang_airbersih'){
                    $tempat->trf_airbersih = NULL;
                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 0;
                    $meteran->save();

                    //Download Surat Perintah Bayar
                    //Download Surat Perintah Ganti / Pasang
                }

                //Ganti Alat
                if($request->radioAlatAir == 'ganti_airbersih'){
                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();

                    //Download Surat Perintah Ganti
                }

                $diskon = array();
                if($request->radioAirBersih == "semua_airbersih"){
                    $tempat->dis_airbersih = NULL;
                }
                else if($request->radioAirBersih == 'dis_airbersih'){
                    if($request->persenDiskonAir != NULL){
                        $diskon['type'] = 'diskon';
                        $diskon['value'] = $request->persenDiskonAir;
                        $tempat->dis_airbersih = json_encode($diskon);
                    }
                    else{
                        $tempat->dis_airbersih = NULL;
                    }
                }
                else{
                    $pilihanDiskon = array('byr','beban','pemeliharaan','arkot','charge');
                    if($request->hanya != NULL){
                        $diskon['type'] = 'hanya';
                        $hanya = array();
                        $j = 0;
                        for($i=0; $i<count($pilihanDiskon); $i++){
                            if(in_array($pilihanDiskon[$i],$request->hanya)){
                                if($pilihanDiskon[$i] == 'charge'){
                                    if($request->persenChargeAir != NULL){
                                        $persen = $request->persenChargeAir;
                                        $dari = $request->chargeAir;
                                        $value = $persen.','.$dari;
                                        $hanya[$j] = [$pilihanDiskon[$i] => $value];
                                    }
                                }
                                else{
                                    $hanya[$j] = $pilihanDiskon[$i];
                                }
                                $j++;
                            }
                        }
                        $diskon['value'] = $hanya;
                        $tempat->dis_airbersih = json_encode($diskon);
                    }
                    else{
                        $tempat->dis_airbersih = NULL;
                    }
                }

                //Tagihan Pasang
                //Download Surat Perintah Bayar

                
                //Tagihan Ganti
                //Ganti Baru atau Ganti Rusak
                //Download Surat Perintah Bayar
            }

            if(empty($request->listrik) == FALSE){
                $tempat->trf_listrik = 1;
                $id_meteran_listrik = $request->meterListrik;
                $tempat->id_meteran_listrik = $id_meteran_listrik;

                //Normal
                if($request->radioAlatListrik == 'normal_listrik'){
                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();
                }

                //Pasang Alat
                if($request->radioAlatListrik == 'pasang_listrik'){
                    $tempat->trf_listrik = NULL;
                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 0;
                    $meteran->save();

                    //Download Surat Perintah Bayar
                    //Download Surat Perintah Ganti / Pasang
                    
                }

                //Ganti Alat
                if($request->radioAlatListrik == 'ganti_listrik'){
                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();

                    //Download Surat Perintah Ganti
                }

                if(empty($request->dis_listrik) == FALSE){
                    if($request->persenDiskonListrik == NULL){
                        $tempat->dis_listrik = 0;
                    }
                    else{
                        $tempat->dis_listrik = $request->persenDiskonListrik;
                    }
                }
                else{
                    $tempat->dis_listrik = NULL;
                }
            }

            if(empty($request->keamananipk) == FALSE){
                $tarif = TarifKeamananIpk::where('tarif',$request->trfKeamananIpk)->select('id')->first();
                $tempat->trf_keamananipk = $tarif->id;

                if(empty($request->dis_keamananipk) == FALSE){
                    if($request->diskonKeamananIpk == NULL){
                        $tempat->dis_keamananipk = 0;
                    }
                    else{
                        $diskon = explode(',',$request->diskonKeamananIpk);
                        $diskon = implode('',$diskon);
                        $tempat->dis_keamananipk = $diskon;
                    }
                }
                else{
                    $tempat->dis_keamananipk = NULL;
                }
            }

            if(empty($request->kebersihan) == FALSE){
                $tarif = TarifKebersihan::where('tarif',$request->trfKebersihan)->select('id')->first();
                $tempat->trf_kebersihan = $tarif->id;

                if(empty($request->dis_kebersihan) == FALSE){
                    if($request->diskonKebersihan == NULL){
                        $tempat->dis_kebersihan = 0;
                    }
                    else{
                        $diskon = explode(',',$request->diskonKebersihan);
                        $diskon = implode('',$diskon);
                        $tempat->dis_kebersihan = $diskon;
                    }
                }
                else{
                    $tempat->dis_kebersihan = NULL;
                }
            }

            if(empty($request->airkotor) == FALSE){
                $tempat->trf_airkotor = $request->trfAirKotor;
            }
            else{
                $tempat->trf_airkotor = NULL;
            }

            if(empty($request->lain) == FALSE){
                $tempat->trf_lain = $request->trfLain;
            }
            else{
                $tempat->trf_lain = NULL;
            }

            // stt_cicil / Metode Pembayaran
            $stt_cicil = $request->cicilan;
            if($stt_cicil == "0"){
                $tempat->stt_cicil = 0; //Kontan
            }
            else if ($stt_cicil == "1"){
                $tempat->stt_cicil = 1; //Cicil
            }

            // stt_tempat
            $stt_tempat = $request->status;
            if($stt_tempat == "1"){
                $tempat->stt_tempat = 1;
            }
            else if($stt_tempat == "2"){
                $tempat->stt_tempat = 2;
                $tempat->ket_tempat = $request->ket_tempat;
            }

            //Save Record Tempat Usaha Baru
            $tempat->save();
            return response()->json(['success' => 'Data Berhasil Ditambah.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }
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
        if(request()->ajax())
        {
            $data = TempatUsaha::find($id);

            $pemilik = Pedagang::find($data->id_pemilik);
            if($pemilik != NULL) {
                $data['id_pemilik'] = $pemilik->id;
                $data['pemilik'] = $pemilik->nama;
            }
            else {
                $data['id_pemilik'] = null;
            }

            $pengguna = Pedagang::find($data->id_pengguna);
            if($pengguna != NULL) {
                $data['id_pengguna'] = $pengguna->id;
                $data['pengguna'] = $pengguna->nama;
            }
            else {
                $data['id_pengguna'] = null;
            }

            if($data->id_meteran_air !== NULL || $data->trf_airbersih !== NULL){
                $meterAir = AlatAir::find($data->id_meteran_air);
                if($meterAir !== NULL){
                    $data['meterAir'] = $meterAir->kode." - ".$meterAir->nomor." (".$meterAir->akhir.")";
                    $data['meterAirId'] = $meterAir->id;
                }
                else {
                    $data['meterAirId'] = null;
                }

                if($data->dis_airbersih != NULL){
                    $diskon = json_decode($data->dis_airbersih);
                    if($diskon->type == 'diskon'){
                        $data['bebasAir'] = 'diskon';
                        $data['diskonAir'] = $diskon->value;
                    }
                    else{
                        $data['bebasAir'] = 'hanya';
                        $data['diskonAir'] = $diskon->value;
                    }
                }
            }

            if($data->id_meteran_listrik !== NULL || $data->trf_listrik !== NULL){
                $meterListrik = AlatListrik::find($data->id_meteran_listrik);
                if($meterListrik !== NULL){
                    $data['meterListrik'] = $meterListrik->kode." - ".$meterListrik->nomor." (".$meterListrik->akhir.' - '.$meterListrik->daya." W)";
                    $data['meterListrikId'] = $meterListrik->id;
                }
                else {
                    $data['meterListrikId'] = null;
                }
            }

            if($data->trf_keamananipk != NULL){
                $tarif = TarifKeamananIpk::find($data->trf_keamananipk);
                if($tarif != NULL){
                    $data['tarifKeamananIpk'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifKeamananIpkId'] = $tarif->tarif;
                }
            }

            if($data->trf_kebersihan != NULL){
                $tarif = TarifKebersihan::find($data->trf_kebersihan);
                if($tarif != NULL){
                    $data['tarifKebersihan'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifKebersihanId'] = $tarif->tarif;
                }
            }

            if($data->trf_airkotor != NULL){
                $tarif = TarifAirKotor::find($data->trf_airkotor);
                if($tarif != NULL){
                    $data['tarifAirKotor'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifAirKotorId'] = $tarif->id;
                }
            }

            if($data->trf_lain != NULL){
                $tarif = TarifLain::find($data->trf_lain);
                if($tarif != NULL){
                    $data['tarifLain'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifLainId'] = $tarif->id;
                }
            }

            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{
            $tanggal = date("Y-m-d", time());

            //deklarasi model
            $tempat = TempatUsaha::find($request->hidden_id);

            //blok
            $blok = $request->blok;
            $tempat->blok = $blok;
            
            //no_alamat
            $los = strtoupper($request->los);
            $tempat->no_alamat = $los;
            $alamat = $los;
            
            //jml_alamat
            $los = explode(",",$los);
            $tempat->jml_alamat = count($los);
            
            //kd_kontrol
            $kode = TempatUsaha::kode($blok,$los);
            $kodeLama = $tempat->kd_kontrol;
            $tempat->kd_kontrol = $kode;
            
            //bentuk_usaha
            if($request->usaha != null){
                $tempat->bentuk_usaha = ucwords($request->usaha);
                $usaha = ucwords($request->usaha);
            }
            else{
                $tempat->bentuk_usaha = NULL;
                $usaha = "usaha";
            }

            //lok_tempat
            $lokasi = $request->lokasi;
            if($lokasi != NULL){
                $tempat->lok_tempat = $lokasi;
            }
            else{
                $tempat->lok_tempat = NULL;
            }

            //id_pemilik
            $id_pemillik = $request->pemilik;
            $tempat->id_pemilik = $id_pemillik;

            // //id_pengguna
            $id_pengguna = $request->pengguna;
            $tempat->id_pengguna = $id_pengguna;

            //Fasilitas
            if(empty($request->air) == FALSE){
                $tempat->trf_airbersih = 1;
                $id_meteran_air = $request->meterAir;
                $tempat->id_meteran_air = $id_meteran_air;

                //Normal
                if($request->radioAlatAir == 'normal_airbersih'){
                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();
                }

                //Pasang Alat
                if($request->radioAlatAir == 'pasang_airbersih'){
                    $tempat->trf_airbersih = NULL;
                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 0;
                    $meteran->save();

                    //Download Surat Perintah Bayar
                    //Download Surat Perintah Ganti / Pasang
                }

                //Ganti Alat
                if($request->radioAlatAir == 'ganti_airbersih'){
                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();

                    //Download Surat Perintah Ganti
                }

                $diskon = array();
                if($request->radioAirBersih == "semua_airbersih"){
                    $tempat->dis_airbersih = NULL;
                }
                else if($request->radioAirBersih == 'dis_airbersih'){
                    if($request->persenDiskonAir != NULL){
                        $diskon['type'] = 'diskon';
                        $diskon['value'] = $request->persenDiskonAir;
                        $tempat->dis_airbersih = json_encode($diskon);
                    }
                    else{
                        $tempat->dis_airbersih = NULL;
                    }
                }
                else{
                    $pilihanDiskon = array('byr','beban','pemeliharaan','arkot','charge');
                    if($request->hanya != NULL){
                        $diskon['type'] = 'hanya';
                        $hanya = array();
                        $j = 0;
                        for($i=0; $i<count($pilihanDiskon); $i++){
                            if(in_array($pilihanDiskon[$i],$request->hanya)){
                                if($pilihanDiskon[$i] == 'charge'){
                                    if($request->persenChargeAir != NULL){
                                        $persen = $request->persenChargeAir;
                                        $dari = $request->chargeAir;
                                        $value = $persen.','.$dari;
                                        $hanya[$j] = [$pilihanDiskon[$i] => $value];
                                    }
                                }
                                else{
                                    $hanya[$j] = $pilihanDiskon[$i];
                                }
                                $j++;
                            }
                        }
                        $diskon['value'] = $hanya;
                        $tempat->dis_airbersih = json_encode($diskon);
                    }
                    else{
                        $tempat->dis_airbersih = NULL;
                    }
                }
            }
            else{
                if($tempat->trf_airbersih != NULL){
                    $meteran = AlatAir::find($tempat->id_meteran_air);
                    $meteran->stt_sedia = 0;
                    $meteran->stt_bayar = 0;
                    $meteran->save();

                    //Bongkaran
                }

                if($tempat->id_meteran_air !== NULL){
                    $meteran = AlatAir::find($tempat->id_meteran_air);
                    $meteran->stt_sedia = 0;
                    $meteran->stt_bayar = 0;
                    $meteran->save();
                }

                $tempat->trf_airbersih = NULL;
                $tempat->id_meteran_air = NULL;
                $tempat->dis_airbersih = NULL;
            }

            if(empty($request->listrik) == FALSE){
                $tempat->trf_listrik = 1;
                $id_meteran_listrik = $request->meterListrik;
                $tempat->id_meteran_listrik = $id_meteran_listrik;
               
                //Normal
                if($request->radioAlatListrik == 'normal_listrik'){
                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();
                }

                //Pasang Alat
                if($request->radioAlatListrik == 'pasang_listrik'){
                    $tempat->trf_listrik = NULL;
                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 0;

                    $pDaya   = $meteran->daya;
                    $pSeri   = $meteran->nomor;
                    $pAwal   = $meteran->akhir;

                    $meteran->save();

                    // Download Surat Perintah Bayar
                    // Download Surat Perintah Ganti / Pasang
                    
                    //Surat Pasang
                    $pNama = Pedagang::find($request->pengguna);
                    $pKtp  = $pNama->ktp;
                    $pNama = $pNama->nama;

                    $pTarif = TarifListrik::first();
                    $pTarif = $pTarif->trf_pasang;

                    $kopsurat = Sinkronisasi::where('sinkron', date('Y-m-01',time()))->first();
                    $tgl_surat = $kopsurat->sinkron;
                    $periode_surat = strtotime($tgl_surat);
                    $bln_surat = date('Y-m', $periode_surat);
                    $periode_surat = IndoDate::kopsurat($bln_surat,'/');

                    $nomor = $kopsurat->surat + 1;
                    $kopsurat->surat = $nomor;
                    if($nomor < 10)
                        $nomor = '000'.$nomor;
                    else if($nomor >= 10 && $nomor < 100)
                        $nomor = '00'.$nomor;
                    else if($nomor >= 100 && $nomor < 1000)
                        $nomor = '0'.$nomor;
                    else
                        $nomor = $nomor;
                    
                    $kopsurat->save();

                    $dok = new Dokumen;
                    $dok->kd_kontrol = $kode;
                    $dok->nama = $pNama;
                    $dok->tgl_tagihan = date('Y-m-d',time());

                    //-----------------------------------------------------
                    
                    $no_surat = $nomor."/PB/LIS/UME/".$periode_surat;
                    $pKopSurat = 'Nomor : '.$no_surat;

                    $document = file_get_contents('rtf/BG1.rtf');

                    $judul = "SAMBUNGAN BARU LISTRIK";
                    $nosurat = $pKopSurat;
                    $tarif = "Rp. ".number_format($pTarif * $pDaya);
                    $permohonan = "sambungan baru listrik dengan daya $pDaya VA";
                    $tanggal = IndoDate::tanggal(date('Y-m-d',time()),' ');

                    $document = str_replace("#JUDULSURAT", $judul, $document);
                    $document = str_replace("#NOMORSURAT", $nosurat, $document);
                    $document = str_replace("#NAMA", $pNama, $document);
                    $document = str_replace("#KTP", $pKtp, $document);
                    $document = str_replace("#BLOK", $blok, $document);
                    $document = str_replace("#ALAMAT", $alamat, $document);
                    $document = str_replace("#NOKONTROL", $kode, $document);
                    $document = str_replace("#LOKASI", $lokasi, $document);
                    $document = str_replace("#NOSERI", $pSeri, $document);
                    $document = str_replace("#TARIF", $tarif, $document);
                    $document = str_replace("#PERMOHONAN", $permohonan, $document);
                    $document = str_replace("#KEPERLUAN", $usaha, $document);
                    $document = str_replace("#FASILITAS", "Listrik", $document);
                    $document = str_replace("#TANGGAL", $tanggal, $document);

                    $dok->srt_permohonan = $document;

                    //-----------------------------------------------------

                    $no_surat = $nomor."/BA/LIS/UME/".$periode_surat;
                    $pKopSurat = 'Nomor : '.$no_surat;

                    $document = file_get_contents('rtf/BG3.rtf');

                    $nosurat = $pKopSurat;

                    $document = str_replace("#JUDULSURAT", "KWH", $document);
                    $document = str_replace("#NOMORSURAT", $nosurat, $document);
                    $document = str_replace("#TANGGAL", "....................", $document);
                    $document = str_replace("#ALAT", "kWh Meter", $document);
                    $document = str_replace("#PERUSAHAAN", "PT. (Persero) Perusahaan Listrik Negara", $document);
                    $document = str_replace("#NAMA", $pNama, $document);
                    $document = str_replace("#NOKONTROL", $kode, $document);
                    $document = str_replace("#NOSERI", $pSeri, $document);
                    $document = str_replace("#AWALMETER", $pAwal, $document);
                    $document = str_replace("#FASILITAS", "listrik", $document);

                    $dok->srt_acara = $document;

                    //------------------------------------------------------

                    $no_surat = $nomor."/KB/LIS/UME/".$periode_surat;
                    $pKopSurat = 'Nomor : '.$no_surat;

                    $document = file_get_contents('rtf/PB.rtf');

                    $nosurat = $pKopSurat;
                    $tanggal = IndoDate::tanggal(date('Y-m-d',time()),' ');
                    $tarif = number_format($pTarif * $pDaya);

                    $tunggakan = 0;
                    $bongkaran = Bongkaran::where('kd_kontrol',$kodeLama)->get();
                    if($bongkaran != NULL){
                        foreach($bongkaran as $b){
                            $tunggakan = $tunggakan + $b->listrik;
                        }
                    }

                    $bayar     = number_format($tunggakan + ($pTarif * $pDaya));
                    $tunggakan = number_format($tunggakan);

                    $document = str_replace("#ALAT", "KWH METER", $document);
                    $document = str_replace("#NAMA", $pNama, $document);
                    $document = str_replace("#NOMORSURAT", $nosurat, $document);
                    $document = str_replace("#NOKONTROL", $kode, $document);
                    $document = str_replace("#TANGGAL", $tanggal, $document);
                    $document = str_replace("#NAMA", $pNama, $document);
                    $document = str_replace("#FASILITAS", "Listrik", $document);
                    $document = str_replace("#BIAYA", $tarif, $document);
                    $document = str_replace("#TUNGGAKAN", $tunggakan, $document);
                    $document = str_replace("#BAYAR", $bayar, $document);
                    $document = str_replace("#ADMIN", Session::get('username'), $document);

                    $dok->srt_bayar = $document;

                    //------------------------------------------------------

                    $no_surat = $nomor."/SP/LIS/UME/".$periode_surat;
                    $pKopSurat = 'Nomor : '.$no_surat;

                    $document = file_get_contents('rtf/BG4.rtf');

                    $nosurat = $pKopSurat;
                    $tanggal = IndoDate::tanggal(date('Y-m-d',time()),' ');

                    $document = str_replace("#JUDULSURAT", "SAMBUNGAN BARU LISTRIK", $document);
                    $document = str_replace("#NOMORSURAT", $nosurat, $document);
                    $document = str_replace("#NAMA", $pNama, $document);
                    $document = str_replace("#KTP", $pKtp, $document);
                    $document = str_replace("#BLOK", $blok, $document);
                    $document = str_replace("#ALAMAT", $alamat, $document);
                    $document = str_replace("#NOKONTROL", $kode, $document);
                    $document = str_replace("#LOKASI", $lokasi, $document);
                    $document = str_replace("#NOSERI", $pSeri, $document);
                    $document = str_replace("#KEPERLUAN", $usaha, $document);
                    $document = str_replace("#ALAT", "kWH Meter", $document);
                    $document = str_replace("#TANGGAL", $tanggal, $document);

                    $dok->srt_pasang = $document;

                    //------------------------------------------------------

                    $dok->stt_surat  = 1;
                    $dok->keterangan = "Pasang";
                    $dok->save();

                    $tarifP = TarifListrik::first();
                    $tunggakan = 0;
                    $bongkaran = Bongkaran::where('kd_kontrol',$kodeLama)->get();
                    if($bongkaran != NULL){
                        foreach($bongkaran as $b){
                            $tunggakan = $tunggakan + $b->listrik;
                        }
                    }
                    $pasang = new PasangAlat;
                    $penggunaPasang = Pedagang::find($id_pengguna);
                    if($penggunaPasang != NULL)
                        $pasang->nama = $penggunaPasang->nama;
                    else
                        $pasang->nama = 'Unknown';
                    $pasang->blok = $blok;
                    $pasang->kd_kontrol = $kode;
                    $pasang->tgl_tagihan = date('Y-m-d',time());
                    $pasang->bln_tagihan = date('Y-m',time());
                    $pasang->thn_tagihan = date('Y',time());
                    $pasang->sub_tagihan = $tarifP->trf_pasang * $pDaya;
                    $pasang->tunggakan   = $tunggakan;
                    $pasang->ttl_tagihan = $pasang->sub_tagihan + $pasang->tunggakan;
                    $pasang->sel_tagihan = $pasang->ttl_tagihan - $pasang->rea_tagihan;
                    $pasang->stt_pasang = 1;
                    $pasang->keterangan = 'Listrik';
                    $pasang->via_tambah = Session::get('username');
                    $pasang->save();
                }

                //Ganti Alat
                if($request->radioAlatListrik == 'ganti_listrik'){
                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();

                    //Download Surat Perintah Ganti
                }
                
                if(empty($request->dis_listrik) == FALSE){
                    if($request->persenDiskonListrik == NULL){
                        $tempat->dis_listrik = 0;
                    }
                    else{
                        $tempat->dis_listrik = $request->persenDiskonListrik;
                    }
                }
                else{
                    $tempat->dis_listrik = NULL;
                }
            }
            else{
                if($tempat->trf_listrik !== NULL){
                    $meteran = AlatListrik::find($tempat->id_meteran_listrik);
                    $meteran->stt_sedia = 0;
                    $meteran->stt_bayar = 0;
                    $meteran->save();
                    
                    //Bongkaran
                    $tagihan = Tagihan::where([['kd_kontrol',$kodeLama],['stt_lunas',0],['sel_listrik','>',0]])->get();
                    if($tagihan != NULL){
                        $tunggakan = 0;
                        foreach($tagihan as $t){
                            $tunggakan = $tunggakan + $t->sel_listrik;

                            $t->daya_listrik = NULL;
                            $t->awal_listrik = NULL;
                            $t->akhir_listrik = NULL;
                            $t->pakai_listrik = NULL;
                            $t->byr_listrik = NULL;
                            $t->rekmin_listrik = NULL;
                            $t->blok1_listrik = NULL;
                            $t->blok2_listrik = NULL;
                            $t->beban_listrik = NULL;
                            $t->bpju_listrik = NULL;
                            $t->sub_listrik = 0;
                            $t->dis_listrik = 0;
                            $t->ttl_listrik = 0;
                            $t->rea_listrik = 0;
                            $t->sel_listrik = 0;
                            $t->den_listrik = 0;
                            $t->stt_listrik = NULL;
                            $t->save();

                            Tagihan::totalTagihan($t->id);

                            $dataTagihan = Tagihan::find($t->id);
                            if($dataTagihan->sel_tagihan === 0){
                                $dataTagihan->stt_lunas = 1;
                                $dataTagihan->stt_bayar = 1;
                            }
                            $dataTagihan->save();
                        }
                        
                        $bongkaran = new Bongkaran;
                        $bongkaran->tgl_bongkar = date('Y-m-d',time());
                        $bongkaran->kd_kontrol  = $kodeLama;
                        $bongkaran->listrik     = $tunggakan;
                        $bongkaran->via_bongkar = Session::get('username');
                        $bongkaran->save();
                    }
                }

                if($tempat->id_meteran_listrik !== NULL){
                    $meteran = AlatListrik::find($tempat->id_meteran_listrik);
                    $meteran->stt_sedia = 0;
                    $meteran->stt_bayar = 0;
                    $meteran->save();
                }

                $tempat->trf_listrik = NULL;
                $tempat->daya = NULL;
                $tempat->id_meteran_listrik = NULL;
                $tempat->dis_listrik = NULL;
            }

            if(empty($request->keamananipk) == FALSE){
                $tarif = TarifKeamananIpk::where('tarif',$request->trfKeamananIpk)->select('id')->first();
                $tempat->trf_keamananipk = $tarif->id;

                if(empty($request->dis_keamananipk) == FALSE){
                    if($request->diskonKeamananIpk == NULL){
                        $tempat->dis_keamananipk = 0;
                    }
                    else{
                        $diskon = explode(',',$request->diskonKeamananIpk);
                        $diskon = implode('',$diskon);
                        $tempat->dis_keamananipk = $diskon;
                    }
                }
                else{
                    $tempat->dis_keamananipk = NULL;
                }
            }
            else{
                $tempat->trf_keamananipk = NULL;
                $tempat->dis_keamananipk = NULL;
            }

            if(empty($request->kebersihan) == FALSE){
                $tarif = TarifKebersihan::where('tarif',$request->trfKebersihan)->select('id')->first();
                $tempat->trf_kebersihan = $tarif->id;

                if(empty($request->dis_kebersihan) == FALSE){
                    if($request->diskonKebersihan == NULL){
                        $tempat->dis_kebersihan = 0;
                    }
                    else{
                        $diskon = explode(',',$request->diskonKebersihan);
                        $diskon = implode('',$diskon);
                        $tempat->dis_kebersihan = $diskon;
                    }
                }
                else{
                    $tempat->dis_kebersihan = NULL;
                }
            }
            else{
                $tempat->trf_kebersihan = NULL;
                $tempat->dis_kebersihan = NULL;
            }

            if(empty($request->airkotor) == FALSE){
                $tempat->trf_airkotor = $request->trfAirKotor;
            }
            else{
                $tempat->trf_airkotor = NULL;
            }

            if(empty($request->lain) == FALSE){
                $tempat->trf_lain = $request->trfLain;
            }
            else{
                $tempat->trf_lain = NULL;
            }

            // stt_cicil / Metode Pembayaran
            $stt_cicil = $request->cicilan;
            if($stt_cicil == "0"){
                $tempat->stt_cicil = 0; //Kontan
            }
            else if ($stt_cicil == "1"){
                $tempat->stt_cicil = 1; //Cicil
            }

            // stt_tempat
            $stt_tempat = $request->status;
            if($stt_tempat == "1"){
                $tempat->stt_tempat = 1;
                $tempat->ket_tempat = NULL;
            }
            else if($stt_tempat == "2"){
                $tempat->stt_tempat = 2;
                $tempat->ket_tempat = $request->ket_tempat;
            }

            //Save Record Tempat Usaha Baru
            $tempat->save();

            return response()->json(['success' => 'Data Berhasil Diupdate']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Diupdate']);
            // return response()->json(['errors' => $e]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = TempatUsaha::findOrFail($id);
        $listrikId = $data->id_meteran_listrik;
        $airId = $data->id_meteran_air;

        try{
            if($listrikId != NULL){
                $alat = AlatListrik::find($listrikId);
                if($alat != NULL){
                    $alat->stt_sedia = 0;
                    $alat->stt_bayar = 0;
                    $alat->save();
                }
            }
            
            if($data->id_meteran_air != NULL){
                $alat = AlatAir::find($airId);
                if($alat != NULL){
                    $alat->stt_sedia = 0;
                    $alat->stt_bayar = 0;
                    $alat->save();
                }
            }

            $data->delete();
        }
        catch(\Exception $e){
            return response()->json(['status' => 'Data gagal dihapus.']);
        }
        return response()->json(['status' => 'Data telah dihapus.']);
    }

    public function rekap(){
        return view('tempatusaha.rekap',[
            'dataset' => TempatUsaha::rekap(),
            'airAvailable'=>TempatUsaha::airAvailable(),
            'listrikAvailable'=>TempatUsaha::listrikAvailable(),
            'trfKeamananIpk'=>TempatUsaha::trfKeamananIpk(),
            'trfKebersihan'=>TempatUsaha::trfKebersihan(),
            'trfAirKotor'=>TempatUsaha::trfAirKotor(),
            'trfLain'=>TempatUsaha::trfLain()
        ]);
    }

    public function rekapdetail($blok){
        return view('tempatusaha.details-rekap',[
            'dataset' => TempatUsaha::detailRekap($blok),
            'blok'=>$blok
        ]);
    }

    public function potensi(){
        return view('tempatusaha.potensi');
    }

    public function fasilitas($fas){
        $dataset = TempatUsaha::fasilitas($fas);
        if($fas == 'airbersih'){
            $fasilitas = 'Air Bersih';
        }
        else if($fas == 'listrik'){
            $fasilitas = 'Listrik';
        }
        else if($fas == 'keamananipk'){
            $fasilitas = 'Keamanan & IPK';
        }
        else if($fas == 'kebersihan'){
            $fasilitas = 'Kebersihan';
        }
        else if($fas == 'airkotor'){
            $fasilitas = 'Air Kotor';
        }
        else if($fas == 'diskon'){
            $fasilitas = 'Diskon / Bebas Bayar';
        }
        else if($fas == 'lain'){
            $fasilitas = 'Lain - Lain';
        }
        
        if($fas == 'diskon'){
            $view = 'tempatusaha.diskon';
        }
        else{
            $view = 'tempatusaha.fasilitas';
        }
        return view($view,[
            'dataset'   => $dataset,
            'fasilitas' => $fasilitas,
            'fas'       => $fas,
            'airAvailable'=>TempatUsaha::airAvailable(),
            'listrikAvailable'=>TempatUsaha::listrikAvailable(),
            'trfKeamananIpk'=>TempatUsaha::trfKeamananIpk(),
            'trfKebersihan'=>TempatUsaha::trfKebersihan(),
            'trfAirKotor'=>TempatUsaha::trfAirKotor(),
            'trfLain'=>TempatUsaha::trfLain()
        ]);
    }

    public function qr($id){
        $dataset = TempatUsaha::find($id);
        $kode = 'KODEKONTROL@'.$dataset->kd_kontrol;
        $kontrol = $dataset->kd_kontrol;

        return view('tempatusaha.qr',[
            'id'=>$id,
            'kode'=>$kode,
            'kontrol'=>$kontrol
        ]);
    }

    public function pengajuan($fas){
        if($fas == 'listrik'){
            $dataset = Dokumen::where('stt_surat',1)->orderBy('id','desc')->get();
            return view('tempatusaha.surat-listrik',[
                'dataset' => $dataset
            ]);
        }

        if($fas == 'air'){
            $dataset = Dokumen::where('stt_surat',2)->orderBy('id','desc')->get();
            return view('tempatusaha.surat-air',[
                'dataset' => $dataset
            ]);
        }
    }
}
