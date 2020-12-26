<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;

use App\Models\Tagihan;
use App\Models\TempatUsaha;
use App\Models\AlatListrik;
use App\Models\AlatAir;

use App\Models\HariLibur;
use App\Models\Sinkronisasi;
use App\Models\IndoDate;
use App\Models\Blok;

class TagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware('tagihan');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $now = date("Y-m-d",time());
        $check = date("Y-m-25",time());

        if($now < $check){
            $sekarang = date("Y-m", time());
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", time());
        }
        else if($now >= $check){
            $sekarang = date("Y-m", time());
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime("+1 month", $time));
        }

        if($request->ajax())
        {
            $data = Tagihan::where('bln_tagihan',$periode);
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if($data->stt_publish === 0){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else{
                        $button = '<span class="text-center" style="color:#1cc88a;">Published</span>';
                    }
                    return $button;
                })
                ->editColumn('kd_kontrol', function ($data) {
                    $hasil = $data->kd_kontrol;
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->kd_kontrol === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('nama', function ($data) {
                    $hasil = $data->nama;
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->nama === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('daya_listrik', function ($data) {
                    $hasil = number_format($data->daya_listrik);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->daya_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('awal_listrik', function ($data) {
                    $hasil = number_format($data->awal_listrik);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->awal_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_listrik', function ($data) {
                    $hasil = number_format($data->akhir_listrik);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->akhir_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_listrik', function ($data) {
                    $hasil = number_format($data->pakai_listrik);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->pakai_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_listrik', function ($data) {
                    $hasil = number_format($data->ttl_listrik);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('awal_airbersih', function ($data) {
                    $hasil = number_format($data->awal_airbersih);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->awal_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_airbersih', function ($data) {
                    $hasil = number_format($data->akhir_airbersih);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->akhir_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_airbersih', function ($data) {
                    $hasil = number_format($data->pakai_airbersih);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->pakai_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_airbersih', function ($data) {
                    $hasil = number_format($data->ttl_airbersih);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_keamananipk', function ($data) {
                    $hasil = number_format($data->ttl_keamananipk);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_keamananipk === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_kebersihan', function ($data) {
                    $hasil = number_format($data->ttl_kebersihan);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_kebersihan === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_airkotor', function ($data) {
                    $hasil = number_format($data->ttl_airkotor);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_airkotor === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_lain', function ($data) {
                    $hasil = number_format($data->ttl_lain);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_lain === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_tagihan', function ($data) {
                    $hasil = number_format($data->ttl_tagihan);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_tagihan === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->rawColumns([
                    'action',
                    'los',
                    'kd_kontrol',
                    'nama',
                    'daya_listrik',
                    'awal_listrik',
                    'akhir_listrik',
                    'pakai_listrik',
                    'ttl_listrik',
                    'awal_airbersih',
                    'akhir_airbersih',
                    'pakai_airbersih',
                    'ttl_airbersih',
                    'ttl_keamananipk',
                    'ttl_kebersihan',
                    'ttl_airkotor',
                    'ttl_lain',
                    'ttl_tagihan',
                ])
                ->make(true);
        }

        return view('tagihan.index',[
            'periode'       => IndoDate::bulan($periode,' '),
            'tahun'         => Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','asc')->get(),
            'blok'          => Blok::select('nama')->get(),
            'listrik_badge' => Tagihan::where([['tagihan.stt_listrik',0],['tempat_usaha.trf_listrik',1]])
                                ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                                ->count(),
            'air_badge'     => Tagihan::where([['tagihan.stt_airbersih',0],['tempat_usaha.trf_airbersih',1]])
                                ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                                ->count(),
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
        if(request()->ajax())
        {
            $data = Tagihan::findOrFail($id);

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
            $id = $request->hidden_id;
            
            if($request->stt_listrik == 'ok'){
                $daya = $request->dayaListrik;
                $daya = explode(',',$daya);
                $daya = implode("",$daya);

                $awal = $request->awalListrik;
                $awal = explode(',',$awal);
                $awal = implode("",$awal);

                $akhir = $request->akhirListrik;
                $akhir = explode(',',$akhir);
                $akhir = implode("",$akhir);

                $tempat = TempatUsaha::where('kd_kontrol',$request->kontrol)->first();
                if($tempat != NULL){
                    //Update Meteran
                    $tempat->daya = $daya;
                    $tempat->save();

                    $meter = AlatListrik::find($tempat->id_meteran_listrik);
                    if($meter != NULL){
                        $meter->akhir = $akhir;
                        $meter->daya  = $daya;
                        $meter->save();
                    }
                }

                //Update Tagihan
                Tagihan::listrik($awal,$akhir,$daya,$id);
            }

            if($request->stt_airbersih == 'ok'){
                $awal = $request->awalAir;
                $awal = explode(',',$awal);
                $awal = implode("",$awal);

                $akhir = $request->akhirAir;
                $akhir = explode(',',$akhir);
                $akhir = implode("",$akhir);

                $tempat = TempatUsaha::where('kd_kontrol',$request->kontrol)->first();
                if($tempat != NULL){
                    $meter = AlatAir::find($tempat->id_meteran_air);
                    if($meter != NULL){
                        $meter->akhir = $akhir;
                        $meter->save();
                    }
                }

                //Update Tagihan
                Tagihan::airbersih($awal,$akhir,$id);
            }

            if($request->stt_keamananipk == 'ok'){
                $tarif = $request->keamananipk;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $diskon = $request->dis_keamananipk;
                $diskon = explode(',',$diskon);
                $diskon = implode("",$diskon);

                if($diskon > $tarif){
                    $diskon = $tarif;
                }
                $tagihan = Tagihan::find($id);
                $tagihan->sub_keamananipk = $tarif;
                $tagihan->dis_keamananipk = $diskon;
                $tagihan->ttl_keamananipk = $tarif - $diskon;
                $tagihan->sel_keamananipk = $tagihan->ttl_keamananipk - $tagihan->rea_keamananipk;
                $tagihan->stt_keamananipk = 1;

                //Diskon Tempat
                $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
                if($tempat != NULL){
                    $tempat->dis_keamananipk = $diskon;
                    $tempat->save();
                }

                $tagihan->save();
            }

            if($request->stt_kebersihan == 'ok'){
                $tarif = $request->kebersihan;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $diskon = $request->dis_kebersihan;
                $diskon = explode(',',$diskon);
                $diskon = implode("",$diskon);

                if($diskon > $tarif){
                    $diskon = $tarif;
                }
                $tagihan = Tagihan::find($id);
                $tagihan->sub_kebersihan = $tarif;
                $tagihan->dis_kebersihan = $diskon;
                $tagihan->ttl_kebersihan = $tarif - $diskon;
                $tagihan->sel_kebersihan = $tagihan->ttl_kebersihan - $tagihan->rea_kebersihan;
                $tagihan->stt_kebersihan = 1;

                //Diskon Tempat
                $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
                if($tempat != NULL){
                    $tempat->dis_kebersihan = $diskon;
                    $tempat->save();
                }

                $tagihan->save();
            }

            if($request->stt_airkotor == 'ok'){
                $tarif = $request->airkotor;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $tagihan = Tagihan::find($id);
                $tagihan->ttl_airkotor = $tarif;
                $tagihan->sel_airkotor = $tagihan->ttl_airkotor - $tagihan->rea_airkotor;
                $tagihan->stt_airkotor = 1;
                $tagihan->save();
            }
            
            if($request->stt_lain == 'ok'){
                $tarif = $request->lain;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $tagihan = Tagihan::find($id);
                $tagihan->ttl_lain = $tarif;
                $tagihan->sel_lain = $tagihan->ttl_lain - $tagihan->rea_lain;
                $tagihan->stt_lain = 1;
                $tagihan->save();
            }

            Tagihan::totalTagihan($id);
            return response()->json(['success' => 'Data Berhasil Diedit.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Diedit.']);
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
        $data = Tagihan::findOrFail($id);
        try{
            $data->delete();
        }
        catch(\Exception $e){
            return response()->json(['status' => 'Data gagal dihapus.']);
        }
        return response()->json(['status' => 'Data telah dihapus.']);
    }

    public function print(){
        $blok = Blok::select('nama')->get();
        $dataListrik = array();
        $dataAir = array();
        $i = 0;
        $j = 0;
        foreach($blok as $b){
            $tempatListrik = TempatUsaha::where([['blok',$b->nama],['trf_listrik',1]])->count();
            if($tempatListrik != 0){
                $dataListrik[$i][0] = $b->nama;
                $dataListrik[$i][1] = TempatUsaha::where([['tempat_usaha.blok', $b->nama],['trf_listrik',1]])
                ->leftJoin('user as pengguna','tempat_usaha.id_pengguna','=','pengguna.id')
                ->leftJoin('meteran_listrik','tempat_usaha.id_meteran_listrik','=','meteran_listrik.id')
                ->select(
                    'pengguna.nama as nama',
                    'tempat_usaha.kd_kontrol as kontrol',
                    'meteran_listrik.nomor as nomor',
                    'meteran_listrik.akhir as lalu')
                ->orderBy('tempat_usaha.kd_kontrol')
                ->get();
                $i++;
            }

            $tempatAir = TempatUsaha::where([['blok',$b->nama],['trf_airbersih',1]])->count();
            if($tempatAir != 0){
                $dataAir[$j][0] = $b->nama;
                $dataAir[$j][1] = TempatUsaha::where([['tempat_usaha.blok', $b->nama],['trf_airbersih',1]])
                ->leftJoin('user as pengguna','tempat_usaha.id_pengguna','=','pengguna.id')
                ->leftJoin('meteran_air','tempat_usaha.id_meteran_air','=','meteran_air.id')
                ->select(
                    'pengguna.nama as nama',
                    'tempat_usaha.kd_kontrol as kontrol',
                    'meteran_air.nomor as nomor',
                    'meteran_air.akhir as lalu')
                ->orderBy('tempat_usaha.kd_kontrol')
                ->get();
                $j++;
            }
        }
        $dataset = [$dataListrik,$dataAir];
        return view('tagihan.print',[
            'dataset'=>$dataset
        ]);
    }

    public function sinkronisasi(Request $request){
        if($request->ajax()){
            try{
                if(Session::get('sync') != 'off'){
                    Sinkronisasi::sinkron();
                }
                return response()->json(['success' => 'Sinkronisasi Sukses']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Sinkronisasi Gagal']);
            }
        }
    }

    public function listrik(Request $request){
        $tagihan = Tagihan::where([['stt_listrik',0],['stt_publish',0]])->orderBy('kd_kontrol','asc')->first();

        if($tagihan == NULL){
            return redirect()->route('tagihan.index');
        }
        else{
            $suggest = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_listrik',1]])->orderBy('id','desc')->limit(3)->get();
            if($suggest != NULL){
                $saran = 0;
                foreach($suggest as $sug){
                    $saran = $saran + $sug->pakai_listrik;
                }
                $suggest = round($saran / 3);
                $suggest = $suggest + $tagihan->awal_listrik;
            }
            else{
                $suggest = $tagihan->awal_listrik;
            }
            return view('tagihan.listrik',['dataset' => $tagihan, 'suggest' => $suggest]);
        }
    }

    public function listrikUpdate(Request $request){
        $daya = explode(',',$request->daya);
        $daya = implode('',$daya);
        
        $awal = explode(',',$request->awal);
        $awal = implode('',$awal);
        
        $akhir = explode(',',$request->akhir);
        $akhir = implode('',$akhir);
        
        Tagihan::listrik($awal, $akhir, $daya, $request->hidden_id);

        $tempat = TempatUsaha::where('kd_kontrol',$request->kd_kontrol)->first();
        if($tempat != NULL){
            $meter = AlatListrik::find($tempat->id_meteran_listrik);
            if($meter != NULL){
                $meter->akhir = $akhir;
                $meter->daya  = $daya;
                $meter->save();
            }
        }

        $nama = Tagihan::find($request->hidden_id);
        $nama->nama = $request->nama;
        $nama->save();

        $this->total($request->hidden_id);

        return redirect()->route('listrik');
    }
    
    public function airbersih(){
        $tagihan = Tagihan::where([['stt_airbersih',0],['stt_publish',0]])->orderBy('kd_kontrol','asc')->first();

        if($tagihan == NULL){
            return redirect()->route('tagihan.index');
        }
        else{
            $suggest = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_airbersih',1]])->orderBy('id','desc')->limit(3)->get();
            if($suggest != NULL){
                $saran = 0;
                foreach($suggest as $sug){
                    $saran = $saran + $sug->pakai_airbersih;
                }
                $suggest = round($saran / 3);
                $suggest = $suggest + $tagihan->awal_airbersih;
            }
            else{
                $suggest = $tagihan->awal_airbersih;
            }
            return view('tagihan.airbersih',['dataset' => $tagihan, 'suggest' => $suggest]);
        }
    }

    public function airbersihUpdate(Request $request){
        $awal = explode(',',$request->awal);
        $awal = implode('',$awal);
        
        $akhir = explode(',',$request->akhir);
        $akhir = implode('',$akhir);
        
        Tagihan::airbersih($awal, $akhir, $request->hidden_id);

        $tempat = TempatUsaha::where('kd_kontrol',$request->kd_kontrol)->first();
        if($tempat != NULL){
            $meter = AlatAir::find($tempat->id_meteran_air);
            if($meter != NULL){
                $meter->akhir = $akhir;
                $meter->save();
            }
        }

        $nama = Tagihan::find($request->hidden_id);
        $nama->nama = $request->nama;
        $nama->save();

        $this->total($request->hidden_id);

        return redirect()->route('airbersih');
    }

    public function total($id){
        $tagihan = Tagihan::find($id);
        //Subtotal
        $subtotal = 
                $tagihan->sub_listrik     + 
                $tagihan->sub_airbersih   + 
                $tagihan->sub_keamananipk + 
                $tagihan->sub_kebersihan  + 
                $tagihan->ttl_airkotor    + 
                $tagihan->ttl_lain;
        $tagihan->sub_tagihan = $subtotal;

        //Diskon
        $diskon = 
            $tagihan->dis_listrik     + 
            $tagihan->dis_airbersih   + 
            $tagihan->dis_keamananipk + 
            $tagihan->dis_kebersihan;
        $tagihan->dis_tagihan = $diskon;

        //Denda
        $tagihan->den_tagihan = $tagihan->den_listrik + $tagihan->den_airbersih;

        //TOTAL
        $total = 
            $tagihan->ttl_listrik     + 
            $tagihan->ttl_airbersih   + 
            $tagihan->ttl_keamananipk + 
            $tagihan->ttl_kebersihan  + 
            $tagihan->ttl_airkotor    + 
            $tagihan->ttl_lain;
        $tagihan->ttl_tagihan = $total;

        //Realisasi
        $realisasi = 
                $tagihan->rea_listrik     + 
                $tagihan->rea_airbersih   + 
                $tagihan->rea_keamananipk + 
                $tagihan->rea_kebersihan  + 
                $tagihan->rea_airkotor    + 
                $tagihan->rea_lain;
        $tagihan->rea_tagihan = $realisasi;

        //Selisih
        $selisih =
                $tagihan->sel_listrik     + 
                $tagihan->sel_airbersih   + 
                $tagihan->sel_keamananipk + 
                $tagihan->sel_kebersihan  + 
                $tagihan->sel_airkotor    + 
                $tagihan->sel_lain;
        $tagihan->sel_tagihan = $selisih;

        $tagihan->save();
    }

    public function publish(){
        $dataset = Tagihan::where('stt_publish',0)->get();
        return view('tagihan.publish',['dataset' => $dataset]);
    }

    public function publishStore(Request $request){
        if($request->ajax()){
            try{
                $dataset = Tagihan::where('stt_publish',0)->get();
                foreach($dataset as $d){
                    $d->stt_publish = 1;
                    $d->save();
                }
                return response()->json(['success' => 'Publish Sukses']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Publish Gagal']);
            }
        }
    }
}
