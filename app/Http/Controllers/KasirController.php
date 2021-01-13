<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Kasir;
use App\Models\Tagihan;
use App\Models\StrukMobile;
use App\Models\StrukLarge;
use App\Models\StrukLapangan;
use App\Models\Pembayaran;
use App\Models\IndoDate;
use App\Models\User;
use App\Models\Harian;

use App\Models\TempatUsaha;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;

use Jenssegers\Agent\Agent;

use DataTables;
use Validator;
use Exception;

class KasirController extends Controller
{
    public function __construct()
    {
        $this->middleware('kasir');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $agent = new Agent();
        if($agent->isDesktop()){
            $platform = 'desktop';
        }
        else{
            $platform = 'mobile';
        }

        $dataTahun = Tagihan::select('thn_tagihan')
        ->groupBy('thn_tagihan')
        ->get();

        if($request->ajax()){
            $data = Tagihan::select('kd_kontrol')
            ->groupBy('kd_kontrol')
            ->orderBy('kd_kontrol','asc')
            ->where([['stt_lunas',0],['stt_publish',1]]);
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Bayar" name="bayar" id="'.$data->kd_kontrol.'" class="bayar btn btn-sm btn-warning">Bayar</a>';
                    return $button;
                })
                ->addColumn('prabayar', function($data){
                    $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_lunas',0],['stt_publish',1]])->get();
                    $hasil = 0;
                    foreach($tagihan as $t){
                        $hasil = $hasil + $t->stt_prabayar;
                    }
                    if($hasil == 0)
                        $button = '<a type="button" title="Prabayar" name="prabayar" id="'.$data->kd_kontrol.'" class="prabayar btn btn-sm btn-success">Ajukan</a>';
                    else
                        $button = '<a type="button" title="Prabayar" name="prabayar" id="'.$data->kd_kontrol.'" class="prabayar btn btn-sm btn-danger">Cancel</a>';
                    return $button;
                })
                ->addColumn('pengguna', function($data){
                    $pengguna = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('id_pengguna')->first();
                    if($pengguna != NULL){
                        return User::find($pengguna->id_pengguna)->nama;
                    }
                    else{
                        return '-';
                    }
                })
                ->addColumn('lokasi', function($data){
                    $lokasi = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('lok_tempat')->first();
                    if($lokasi != NULL){
                        return $lokasi->lok_tempat;
                    }
                    else{
                        return '';
                    }
                })
                ->addColumn('tagihan', function($data){
                    $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_lunas',0],['stt_publish',1]])
                    ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->rawColumns(['action','prabayar'])
                ->make(true);
        }

        return view('kasir.index',[
            'platform'=>$platform,
            'month'=>date("m", time()),
            'tahun'=>date("Y", time()),
            'dataTahun'=>$dataTahun,
            'bulan'=>IndoDate::bulan(date("Y-m", time()),' '),
        ]);
    }

    public function periode(Request $request){
        $agent = new Agent();
        if($agent->isDesktop()){
            $platform = 'desktop';
        }
        else{
            $platform = 'mobile';
        }

        $dataTahun = Tagihan::select('thn_tagihan')
        ->groupBy('thn_tagihan')
        ->get();

        $periode = $request->tahun."-".$request->bulan;
        Session::put('bln_periode',$request->bulan);
        Session::put('thn_periode',$request->tahun);
        Session::put('periode', $periode);

        if($request->ajax()){
            $data = Tagihan::select('kd_kontrol')
            ->groupBy('kd_kontrol')
            ->orderBy('kd_kontrol','asc')
            ->where([['stt_lunas',0],['stt_publish',1],['bln_tagihan',Session::get('periode')]]);
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Bayar" name="bayar" id="'.$data->kd_kontrol.'" class="bayar btn btn-sm btn-warning">Bayar</a>';
                    return $button;
                })
                ->addColumn('pengguna', function($data){
                    $pengguna = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('id_pengguna')->first();
                    if($pengguna != NULL){
                        return User::find($pengguna->id_pengguna)->nama;
                    }
                    else{
                        return '(Kosong)';
                    }
                })
                ->addColumn('lokasi', function($data){
                    $lokasi = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('lok_tempat')->first();
                    if($lokasi != NULL){
                        return $lokasi->lok_tempat;
                    }
                    else{
                        return '';
                    }
                })
                ->addColumn('tagihan', function($data){
                    $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_lunas',0],['stt_publish',1],['bln_tagihan',Session::get('periode')]])
                    ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('kasir.periode',[
            'platform'=>$platform,
            'month'=>$request->bulan,
            'tahun'=>$request->tahun,
            'dataTahun'=>$dataTahun,
            'bulan'=>IndoDate::bulan($periode,' '),
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

    public function rincian(Request $request, $kontrol){
        $bulan = date("Y-m", time());
        Session::put('periode',$bulan);

        if($request->ajax()){
            $data = array();
            $data1 = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['bln_tagihan','<',Session::get('periode')]])
            ->select(
                DB::raw('SUM(sel_listrik) as listrik'),
                DB::raw('SUM(sel_airbersih) as airbersih'),
                DB::raw('SUM(sel_keamananipk) as keamananipk'),
                DB::raw('SUM(sel_kebersihan) as kebersihan'),
                DB::raw('SUM(sel_airkotor) as airkotor'),
                DB::raw('SUM(sel_tagihan) as tunggakan'),
                DB::raw('SUM(den_tagihan) as denda'))
            ->get();
            
            $data['tunggakan']     = $data1[0]->tunggakan - $data1[0]->denda;
            $data['denda']         = $data1[0]->denda;

            $data['tlistrik'] = $data1[0]->listrik;
            $data['tairbersih']  = $data1[0]->airbersih;
            $data['tkeamananipk'] = $data1[0]->keamananipk;
            $data['tkebersihan']  = $data1[0]->kebersihan;
            $data['tairkotor']  = $data1[0]->airkotor;

            $data2 = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['bln_tagihan',Session::get('periode')]])
            ->select(
                'sel_listrik',
                'sel_airbersih',
                'sel_keamananipk',
                'sel_kebersihan',
                'sel_airkotor'
            )
            ->first();

            if($data2 != NULL){
                $data['listrik']     = $data2->sel_listrik;
                $data['airbersih']   = $data2->sel_airbersih;
                $data['keamananipk'] = $data2->sel_keamananipk;
                $data['kebersihan']  = $data2->sel_kebersihan;
                $data['airkotor']    = $data2->sel_airkotor;
            }
            else{
                $data['listrik']     = 0;
                $data['airbersih']   = 0;
                $data['keamananipk'] = 0;
                $data['kebersihan']  = 0;
                $data['airkotor']    = 0;
            }

            $data3 = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['bln_pakai','<=',$bulan]])
            ->select(DB::raw('SUM(sel_lain) as lain'))
            ->get();

            $data['lain'] = $data3[0]->lain;

            return response()->json(['result' => $data]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bayar = '';
        $tagihan = '';
        try{
            //Pembayaran Kontan
            $id = $request->tempatId;
            $tagihan = Tagihan::where([['kd_kontrol',$id],['stt_lunas',0],['stt_publish',1]])->get();
            foreach($tagihan as $d){
                $tanggal = date("Y-m-d", time());
                $bulan = date("Y-m", time());
                $tahun = date("Y", time());
    
                //--------------------------------------------------------------
                $pembayaran = new Pembayaran;
                $pembayaran->tgl_bayar = $tanggal;
                $pembayaran->bln_bayar = $bulan;
                $pembayaran->thn_bayar = $tahun;
                $pembayaran->tgl_tagihan = $d->tgl_tagihan;
                $pembayaran->via_bayar = 'kasir';
                $pembayaran->id_kasir = Session::get('userId');
                $pembayaran->nama = Session::get('username');
                $pembayaran->blok = $d->blok;
                $pembayaran->kd_kontrol = $d->kd_kontrol;
                $pembayaran->pengguna = $d->nama;
                $pembayaran->id_tagihan = $d->id;

                $total = 0;
                $selisih = $d->sel_tagihan;

                if(empty($request->checkListrik) == FALSE){
                    $pembayaran->byr_listrik = $d->sel_listrik;
                    $pembayaran->byr_denlistrik = $d->den_listrik;
                    $pembayaran->sel_listrik = 0;

                    $total = $total + $pembayaran->byr_listrik;
                    $selisih = $selisih - $pembayaran->byr_listrik;
                }
                
                if(empty($request->checkAirBersih) == FALSE){
                    $pembayaran->byr_airbersih = $d->sel_airbersih;
                    $pembayaran->byr_denairbersih = $d->den_airbersih;
                    $pembayaran->sel_airbersih = 0;
                    
                    $total = $total + $pembayaran->byr_airbersih;
                    $selisih = $selisih - $pembayaran->byr_airbersih;
                }
                
                if(empty($request->checkKeamananIpk) == FALSE){
                    $pembayaran->byr_keamananipk = $d->sel_keamananipk;
                    $pembayaran->sel_keamananipk = 0;
                    
                    $total = $total + $pembayaran->byr_keamananipk;
                    $selisih = $selisih - $pembayaran->byr_keamananipk;
                }

                if(empty($request->checkKebersihan) == FALSE){
                    $pembayaran->byr_kebersihan = $d->sel_kebersihan;
                    $pembayaran->sel_kebersihan = 0;
                    
                    $total = $total + $pembayaran->byr_kebersihan;
                    $selisih = $selisih - $pembayaran->byr_kebersihan;
                }

                if(empty($request->checkAirKotor) == FALSE){
                    $pembayaran->byr_airkotor = $d->sel_airkotor;
                    $pembayaran->sel_airkotor = 0;
                    
                    $total = $total + $pembayaran->byr_airkotor;
                    $selisih = $selisih - $pembayaran->byr_airkotor;
                }

                if(empty($request->checkLain) == FALSE){
                    $pembayaran->byr_lain = $d->sel_lain;
                    $pembayaran->sel_lain = 0;
                    
                    $total = $total + $pembayaran->byr_lain;
                    $selisih = $selisih - $pembayaran->byr_lain;
                }

                if(empty($request->checkTunggakan) == FALSE){
                    $pembayaran->byr_listrik = $d->sel_listrik;
                    $pembayaran->byr_denlistrik = $d->den_listrik;
                    $pembayaran->sel_listrik = 0;

                    $total = $total + $pembayaran->byr_listrik;
                    $selisih = $selisih - $pembayaran->byr_listrik;

                    $pembayaran->byr_airbersih = $d->sel_airbersih;
                    $pembayaran->byr_denairbersih = $d->den_airbersih;
                    $pembayaran->sel_airbersih = 0;
                    
                    $total = $total + $pembayaran->byr_airbersih;
                    $selisih = $selisih - $pembayaran->byr_airbersih;

                    $pembayaran->byr_keamananipk = $d->sel_keamananipk;
                    $pembayaran->sel_keamananipk = 0;
                    
                    $total = $total + $pembayaran->byr_keamananipk;
                    $selisih = $selisih - $pembayaran->byr_keamananipk;

                    $pembayaran->byr_kebersihan = $d->sel_kebersihan;
                    $pembayaran->sel_kebersihan = 0;
                    
                    $total = $total + $pembayaran->byr_kebersihan;
                    $selisih = $selisih - $pembayaran->byr_kebersihan;

                    $pembayaran->byr_airkotor = $d->sel_airkotor;
                    $pembayaran->sel_airkotor = 0;
                    
                    $total = $total + $pembayaran->byr_airkotor;
                    $selisih = $selisih - $pembayaran->byr_airkotor;

                    $pembayaran->byr_lain = $d->sel_lain;
                    $pembayaran->sel_lain = 0;
                    
                    $total = $total + $pembayaran->byr_lain;
                    $selisih = $selisih - $pembayaran->byr_lain;
                }

                $pembayaran->sub_tagihan = $d->sub_tagihan;
                $pembayaran->diskon = $d->dis_tagihan;
                $pembayaran->ttl_tagihan = $d->ttl_tagihan;
                $pembayaran->realisasi = $total;
                $pembayaran->sel_tagihan = $selisih;
                $pembayaran->save();

                //-------------------------------------------------------------
                $total = 0;
                $selisih = $d->sel_tagihan;

                if(empty($request->checkAirBersih) == FALSE){
                    $d->rea_airbersih = $d->ttl_airbersih;
                    $total = $total + $d->rea_airbersih;
                    $selisih = $selisih - $d->sel_airbersih;
                    $d->sel_airbersih = 0;
                }

                if(empty($request->checkListrik) == FALSE){
                    $d->rea_listrik = $d->ttl_listrik;
                    $total = $total + $d->rea_listrik;
                    $selisih = $selisih - $d->sel_listrik;
                    $d->sel_listrik = 0;
                }

                if(empty($request->checkKeamananIpk) == FALSE){
                    $d->rea_keamananipk = $d->ttl_keamananipk;
                    $total = $total + $d->rea_keamananipk;
                    $selisih = $selisih - $d->sel_keamananipk;
                    $d->sel_keamananipk = 0;
                }

                if(empty($request->checkKebersihan) == FALSE){
                    $d->rea_kebersihan = $d->ttl_kebersihan;
                    $total = $total + $d->rea_kebersihan;
                    $selisih = $selisih - $d->sel_kebersihan;
                    $d->sel_kebersihan = 0;
                }

                if(empty($request->checkAirKotor) == FALSE){
                    $d->rea_airkotor = $d->ttl_airkotor;
                    $total = $total + $d->rea_airkotor;
                    $selisih = $selisih - $d->sel_airkotor;
                    $d->sel_airkotor = 0;
                }

                if(empty($request->checkLain) == FALSE){
                    $d->rea_lain = $d->ttl_lain;
                    $total = $total + $d->rea_lain;
                    $selisih = $selisih - $d->sel_lain;
                    $d->sel_lain = 0;
                }

                if(empty($request->checkTunggakan) == FALSE){
                    $d->rea_airbersih = $d->ttl_airbersih;
                    $total = $total + $d->rea_airbersih;
                    $selisih = $selisih - $d->sel_airbersih;
                    $d->sel_airbersih = 0;
                    $d->rea_listrik = $d->ttl_listrik;
                    $total = $total + $d->rea_listrik;
                    $selisih = $selisih - $d->sel_listrik;
                    $d->sel_listrik = 0;
                    $d->rea_keamananipk = $d->ttl_keamananipk;
                    $total = $total + $d->rea_keamananipk;
                    $selisih = $selisih - $d->sel_keamananipk;
                    $d->sel_keamananipk = 0;
                    $d->rea_kebersihan = $d->ttl_kebersihan;
                    $total = $total + $d->rea_kebersihan;
                    $selisih = $selisih - $d->sel_kebersihan;
                    $d->sel_kebersihan = 0;
                    $d->rea_airkotor = $d->ttl_airkotor;
                    $total = $total + $d->rea_airkotor;
                    $selisih = $selisih - $d->sel_airkotor;
                    $d->sel_airkotor = 0;
                    $d->rea_lain = $d->ttl_lain;
                    $total = $total + $d->rea_lain;
                    $selisih = $selisih - $d->sel_lain;
                    $d->sel_lain = 0;
                }

                
                if($selisih == 0){
                    $d->stt_lunas = 1;
                }

                if($total != 0){
                    $d->stt_bayar = 1;
                }
                $d->save();

                Tagihan::totalTagihan($d->id);
            }
            return response()->json(['success' => 'Transaksi Berhasil']);
        } catch(\Exception $e){
            return response()->json(['errors' => 'Transaksi Gagal']);
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
            $data = Harian::findOrFail($id);

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
        try{
            Harian::find($id)->delete();
            return response()->json(['status' => 'Data berhasil dihapus.']);
        }
        catch(\Exception $e){
            return response()->json(['status' => 'Data gagal dihapus.']);
        }
    }

    public function rincianPeriode(Request $request, $kontrol){
        if($request->ajax()){
            $bulan = Session::get('periode');

            $data = array();
            $dataset = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['bln_tagihan',$bulan]])
            ->select(
                'sel_listrik',
                'sel_airbersih',
                'sel_keamananipk',
                'sel_kebersihan',
                'sel_airkotor',
                'sel_lain',
                'den_tagihan',
            )
            ->first();

            $data['listrik']     = $dataset->sel_listrik;
            $data['airbersih']   = $dataset->sel_airbersih;
            $data['keamananipk'] = $dataset->sel_keamananipk;
            $data['kebersihan']  = $dataset->sel_kebersihan;
            $data['airkotor']    = $dataset->sel_airkotor;
            $data['denda']       = $dataset->den_tagihan;
            $data['lain']        = $dataset->sel_lain;

            return response()->json(['result' => $data]);
        }
    }

    public function storePeriode(Request $request)
    {
        $bayar = '';
        $tagihan = '';
        try{
            //Pembayaran Kontan
            $id = $request->tempatId;
            $tagihan = Tagihan::where([['kd_kontrol',$id],['stt_lunas',0],['stt_publish',1],['bln_tagihan',Session::get('periode')]])->get();
            foreach($tagihan as $d){
                $tanggal = date("Y-m-d", time());
                $bulan = date("Y-m", time());
                $tahun = date("Y", time());
    
                //--------------------------------------------------------------
                $pembayaran = new Pembayaran;
                $pembayaran->tgl_bayar = $tanggal;
                $pembayaran->bln_bayar = $bulan;
                $pembayaran->thn_bayar = $tahun;
                $pembayaran->tgl_tagihan = $d->tgl_tagihan;
                $pembayaran->via_bayar = 'kasir';
                $pembayaran->id_kasir = Session::get('userId');
                $pembayaran->nama = Session::get('username');
                $pembayaran->blok = $d->blok;
                $pembayaran->kd_kontrol = $d->kd_kontrol;
                $pembayaran->pengguna = $d->nama;
                $pembayaran->id_tagihan = $d->id;

                $total = 0;
                $selisih = $d->sel_tagihan;

                if(empty($request->checkListrik) == FALSE){
                    $pembayaran->byr_listrik = $d->sel_listrik;
                    $pembayaran->byr_denlistrik = $d->den_listrik;
                    $pembayaran->sel_listrik = 0;

                    $total = $total + $pembayaran->byr_listrik;
                    $selisih = $selisih - $pembayaran->byr_listrik;
                }
                
                if(empty($request->checkAirBersih) == FALSE){
                    $pembayaran->byr_airbersih = $d->sel_airbersih;
                    $pembayaran->byr_denairbersih = $d->den_airbersih;
                    $pembayaran->sel_airbersih = 0;
                    
                    $total = $total + $pembayaran->byr_airbersih;
                    $selisih = $selisih - $pembayaran->byr_airbersih;
                }
                
                if(empty($request->checkKeamananIpk) == FALSE){
                    $pembayaran->byr_keamananipk = $d->sel_keamananipk;
                    $pembayaran->sel_keamananipk = 0;
                    
                    $total = $total + $pembayaran->byr_keamananipk;
                    $selisih = $selisih - $pembayaran->byr_keamananipk;
                }

                if(empty($request->checkKebersihan) == FALSE){
                    $pembayaran->byr_kebersihan = $d->sel_kebersihan;
                    $pembayaran->sel_kebersihan = 0;
                    
                    $total = $total + $pembayaran->byr_kebersihan;
                    $selisih = $selisih - $pembayaran->byr_kebersihan;
                }

                if(empty($request->checkAirKotor) == FALSE){
                    $pembayaran->byr_airkotor = $d->sel_airkotor;
                    $pembayaran->sel_airkotor = 0;
                    
                    $total = $total + $pembayaran->byr_airkotor;
                    $selisih = $selisih - $pembayaran->byr_airkotor;
                }

                if(empty($request->checkLain) == FALSE){
                    $pembayaran->byr_lain = $d->sel_lain;
                    $pembayaran->sel_lain = 0;
                    
                    $total = $total + $pembayaran->byr_lain;
                    $selisih = $selisih - $pembayaran->byr_lain;
                }

                $pembayaran->sub_tagihan = $d->sub_tagihan;
                $pembayaran->diskon = $d->dis_tagihan;
                $pembayaran->ttl_tagihan = $d->ttl_tagihan;
                $pembayaran->realisasi = $total;
                $pembayaran->sel_tagihan = $selisih;
                $pembayaran->save();

                //-------------------------------------------------------------
                $total = 0;
                $selisih = $d->sel_tagihan;

                if(empty($request->checkAirBersih) == FALSE){
                    $d->rea_airbersih = $d->ttl_airbersih;
                    $d->sel_airbersih = 0;

                    $total = $total + $d->rea_airbersih;
                    $selisih = $selisih - $d->rea_airbersih;
                }

                if(empty($request->checkListrik) == FALSE){
                    $d->rea_listrik = $d->ttl_listrik;
                    $d->sel_listrik = 0;
                    
                    $total = $total + $d->rea_listrik;
                    $selisih = $selisih - $d->rea_listrik;
                }

                if(empty($request->checkKeamananIpk) == FALSE){
                    $d->rea_keamananipk = $d->ttl_keamananipk;
                    $d->sel_keamananipk = 0;
                    
                    $total = $total + $d->rea_keamananipk;
                    $selisih = $selisih - $d->rea_keamananipk;
                }

                if(empty($request->checkKebersihan) == FALSE){
                    $d->rea_kebersihan = $d->ttl_kebersihan;
                    $d->sel_kebersihan = 0;
                    
                    $total = $total + $d->rea_kebersihan;
                    $selisih = $selisih - $d->rea_kebersihan;
                }

                if(empty($request->checkAirKotor) == FALSE){
                    $d->rea_airkotor = $d->ttl_airkotor;
                    $d->sel_airkotor = 0;
                    
                    $total = $total + $d->rea_airkotor;
                    $selisih = $selisih - $d->rea_airkotor;
                }

                if(empty($request->checkLain) == FALSE){
                    $d->rea_lain = $d->ttl_lain;
                    $d->sel_lain = 0;
                    
                    $total = $total + $d->rea_lain;
                    $selisih = $selisih - $d->rea_lain;
                }

                
                if($selisih == 0){
                    $d->stt_lunas = 1;
                }

                if($total != 0){
                    $d->stt_bayar = 1;
                }
                $d->save();

                Tagihan::totalTagihan($d->id);
            }
            return response()->json(['success' => 'Transaksi Berhasil']);
        } catch(\Exception $e){
            return response()->json(['errors' => 'Transaksi Gagal']);
        }
    }

    public function bayarPeriode($kontrol){

    }

    public function penerimaan(Request $request){
        $tanggal = $request->tanggal;
        $cetak   = IndoDate::tanggal(date('Y-m-d',time()),' ');

        $dataset     = Pembayaran::where([['tgl_bayar',$tanggal],['id_kasir',Session::get('userId')]])->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
        $rekap       = array();
        $rek         = 0;
        $listrik     = 0;
        $denlistrik  = 0;
        $airbersih   = 0;
        $denairbersih= 0;
        $keamananipk = 0;
        $kebersihan  = 0;
        $airkotor    = 0;
        $lain        = 0;
        $jumlah      = 0;
        $diskon      = 0;

        $rin          = array();

        $i = 0;
        $j = 0;

        foreach($dataset as $d){
            $rekap[$i]['blok'] = $d->blok;
            $rekap[$i]['rek']  = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')]])->count();
            $setor = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')]])
            ->select(
                DB::raw('SUM(byr_listrik)      as listrik'),
                DB::raw('SUM(byr_denlistrik)   as denlistrik'),
                DB::raw('SUM(byr_airbersih)    as airbersih'),
                DB::raw('SUM(byr_denairbersih) as denairbersih'),
                DB::raw('SUM(byr_keamananipk)  as keamananipk'),
                DB::raw('SUM(byr_kebersihan)   as kebersihan'),
                DB::raw('SUM(byr_airkotor)     as airkotor'),
                DB::raw('SUM(byr_lain)         as lain'),
                DB::raw('SUM(realisasi)        as jumlah'),
                DB::raw('SUM(diskon)           as diskon'))
            ->get();
            $rekap[$i]['listrik']     = $setor[0]->listrik;
            $rekap[$i]['denlistrik']  = $setor[0]->denlistrik;
            $rekap[$i]['airbersih']   = $setor[0]->airbersih;
            $rekap[$i]['denairbersih']= $setor[0]->denairbersih;
            $rekap[$i]['keamananipk'] = $setor[0]->keamananipk;
            $rekap[$i]['kebersihan']  = $setor[0]->kebersihan;
            $rekap[$i]['airkotor']    = $setor[0]->airkotor;
            $rekap[$i]['lain']        = $setor[0]->lain;
            $rekap[$i]['diskon']      = $setor[0]->diskon;
            $rekap[$i]['jumlah']      = $setor[0]->jumlah;
            $rek         = $rek         + $rekap[$i]['rek'];
            $listrik     = $listrik     + $rekap[$i]['listrik'];
            $denlistrik  = $denlistrik  + $rekap[$i]['denlistrik'];
            $airbersih   = $airbersih   + $rekap[$i]['airbersih'];
            $denairbersih= $denairbersih+ $rekap[$i]['denairbersih'];
            $keamananipk = $keamananipk + $rekap[$i]['keamananipk'];
            $kebersihan  = $kebersihan  + $rekap[$i]['kebersihan'];
            $airkotor    = $airkotor    + $rekap[$i]['airkotor'];
            $lain        = $lain        + $rekap[$i]['lain'];
            $diskon      = $diskon      + $rekap[$i]['diskon'];
            $jumlah      = $jumlah      + $rekap[$i]['jumlah'];

            $rincian = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')]])->orderBy('kd_kontrol','asc')->get();
            foreach($rincian as $r){
                $rin[$j]['rek']  = date("m/Y", strtotime($r->tgl_tagihan));
                $rin[$j]['kode']  = $r->kd_kontrol;
                $rin[$j]['pengguna']  = $r->pengguna;
                $rin[$j]['listrik']  = $r->byr_listrik;
                $rin[$j]['denlistrik']  = $r->byr_denlistrik;
                $rin[$j]['airbersih']  = $r->byr_airbersih;
                $rin[$j]['denairbersih']  = $r->byr_denairbersih;
                $rin[$j]['keamananipk']  = $r->byr_keamananipk;
                $rin[$j]['kebersihan']  = $r->byr_kebersihan;
                $rin[$j]['airkotor']  = $r->byr_airkotor;
                $rin[$j]['lain']  = $r->byr_lain;
                $rin[$j]['jumlah']  = $r->realisasi;
                $rin[$j]['diskon']  = $r->diskon;

                $j++;
            }

            $i++;
        }
        $t_rekap['rek']          = $rek;
        $t_rekap['listrik']      = $listrik;
        $t_rekap['denlistrik']   = $denlistrik;
        $t_rekap['airbersih']    = $airbersih;
        $t_rekap['denairbersih'] = $denairbersih;
        $t_rekap['keamananipk']  = $keamananipk;
        $t_rekap['kebersihan']   = $kebersihan;
        $t_rekap['airkotor']     = $airkotor;
        $t_rekap['lain']         = $lain;
        $t_rekap['diskon']       = $diskon;
        $t_rekap['jumlah']       = $jumlah;

        return view('kasir.penerimaan',[
            'tanggal'   => IndoDate::tanggal($tanggal,' '), 
            'cetak'     => $cetak,
            'rekap'     => $rekap,
            't_rekap'   => $t_rekap,
            'rincian'   => $rin
        ]);
    }

    public function restore(Request $request){
        if($request->ajax()){
            $data = Pembayaran::select('kd_kontrol')
            ->groupBy('kd_kontrol')
            ->orderBy('kd_kontrol','asc')
            ->where('tgl_bayar',date('Y-m-d',time()));
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Restore" name="restore" id="'.$data->kd_kontrol.'" class="restore btn btn-sm btn-primary">Restore</a>';
                    return $button;
                })
                ->addColumn('pengguna', function($data){
                    $pengguna = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('id_pengguna')->first();
                    if($pengguna != NULL){
                        return User::find($pengguna->id_pengguna)->nama;
                    }
                    else{
                        return '(Kosong)';
                    }
                })
                ->addColumn('lokasi', function($data){
                    $lokasi = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('lok_tempat')->first();
                    if($lokasi != NULL){
                        return $lokasi->lok_tempat;
                    }
                    else{
                        return '';
                    }
                })
                ->addColumn('tagihan', function($data){
                    $tagihan = Pembayaran::where([['kd_kontrol',$data->kd_kontrol],['tgl_bayar',date('Y-m-d',time())]])
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('kasir.restore');
    }

    public function restoreStore(Request $request, $kontrol){
        if($request->ajax()){
            try{
                $pembayaran = Pembayaran::where([['kd_kontrol',$kontrol],['tgl_bayar',date('Y-m-d',time())]])->get();
                foreach($pembayaran as $p){
                    $tagihan = Tagihan::find($p->id_tagihan);
                    if($p->byr_listrik == $tagihan->ttl_listrik && $p->byr_listrik !== NULL){
                        $tagihan->rea_listrik = 0;
                        $tagihan->sel_listrik = $tagihan->ttl_listrik;
                        $tagihan->den_listrik = $p->byr_denlistrik;
                        $tagihan->stt_lunas   = 0;
                    }

                    if($p->byr_airbersih == $tagihan->ttl_airbersih && $p->byr_airbersih !== NULL){
                        $tagihan->rea_airbersih = 0;
                        $tagihan->sel_airbersih = $tagihan->ttl_airbersih;
                        $tagihan->den_airbersih = $p->byr_denairbersih;
                        $tagihan->stt_lunas   = 0;
                    }

                    if($p->byr_keamananipk == $tagihan->ttl_keamananipk && $p->byr_keamananipk !== NULL){
                        $tagihan->rea_keamananipk = 0;
                        $tagihan->sel_keamananipk = $tagihan->ttl_keamananipk;
                        $tagihan->stt_lunas   = 0;
                    }

                    if($p->byr_kebersihan == $tagihan->ttl_kebersihan && $p->byr_kebersihan !== NULL){
                        $tagihan->rea_kebersihan = 0;
                        $tagihan->sel_kebersihan = $tagihan->ttl_kebersihan;
                        $tagihan->stt_lunas   = 0;
                    }

                    if($p->byr_airkotor == $tagihan->ttl_airkotor && $p->byr_airkotor !== NULL){
                        $tagihan->rea_airkotor = 0;
                        $tagihan->sel_airkotor = $tagihan->ttl_airkotor;
                        $tagihan->stt_lunas   = 0;
                    }

                    if($p->byr_lain == $tagihan->ttl_lain && $p->byr_lain !== NULL){
                        $tagihan->rea_lain = 0;
                        $tagihan->sel_lain = $tagihan->ttl_lain;
                        $tagihan->stt_lunas   = 0;
                    }

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

                    $p->delete();
                }
                return response()->json(['success' => 'Restore Sukses']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => $e]);
            }
        }
    }

    public function prabayar(Request $request,$kontrol){
        if($request->ajax()){
            try{
                $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1]])->get();
                foreach($tagihan as $t){
                    if($t->stt_prabayar === 1){
                        $hasil = 0;
                    }
                    if($t->stt_prabayar === 0){
                        $hasil = 1;
                    }
                    $t->stt_prabayar = $hasil;
                    $t->save();
                }
                return response()->json(['success' => 'Prabayar Sukses']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Prabayar Gagal']);
            }
        }
    }

    public function mode($mode){
        if($mode == 'bulanan'){
            Session::put('mode','bulanan');
            return redirect()->route('kasir.index');
        }
        else{
            Session::put('mode','harian');
            return redirect()->route('kasir.harian');
        }
    }

    public function harian(Request $request){
        $tanggal = date('Y-m-d',time());
        $tanggal = IndoDate::tanggal($tanggal,' ');
        $agent = new Agent();
        if($agent->isDesktop()){
            $platform = 'desktop';
        }
        else{
            $platform = 'mobile';
        }

        if($request->ajax()){
            $data = Harian::where('tgl_bayar',date('Y-m-d',time()))->orderBy('id','desc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button  = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;;font-size:12px"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;font-size:12px"></i></a>';
                    return $button;
                })
                ->editColumn('keamanan_los',function($data){
                    return number_format($data->keamanan_los);
                })
                ->editColumn('kebersihan_los',function($data){
                    return number_format($data->kebersihan_los);
                })
                ->editColumn('kebersihan_pos',function($data){
                    return number_format($data->kebersihan_pos);
                })
                ->editColumn('kebersihan_lebih_pos',function($data){
                    return number_format($data->kebersihan_lebih_pos);
                })
                ->editColumn('abonemen',function($data){
                    return number_format($data->abonemen);
                })
                ->editColumn('total',function($data){
                    return number_format($data->total);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('kasir.harian',[
            'platform' => $platform,
            'tanggal'  => $tanggal
        ]);
    }

    public function harianpendapatan(Request $request){
        $tanggal = $request->tgl_pendapatan;
        Session::put('harian', $tanggal);
        $tanggal = IndoDate::tanggal($tanggal,' ');
        $agent = new Agent();
        if($agent->isDesktop()){
            $platform = 'desktop';
        }
        else{
            $platform = 'mobile';
        }
        if($request->ajax()){
            $data = Harian::where('tgl_bayar',Session::get('harian'))->orderBy('id','desc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button  = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;;font-size:12px"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;font-size:12px"></i></a>';
                    return $button;
                })
                ->editColumn('keamanan_los',function($data){
                    return number_format($data->keamanan_los);
                })
                ->editColumn('kebersihan_los',function($data){
                    return number_format($data->kebersihan_los);
                })
                ->editColumn('kebersihan_pos',function($data){
                    return number_format($data->kebersihan_pos);
                })
                ->editColumn('kebersihan_lebih_pos',function($data){
                    return number_format($data->kebersihan_lebih_pos);
                })
                ->editColumn('abonemen',function($data){
                    return number_format($data->abonemen);
                })
                ->editColumn('total',function($data){
                    return number_format($data->total);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('kasir.harian-old',[
            'platform' => $platform,
            'tanggal'  => $tanggal
        ]);
    }

    public function harianval(Request $request, $val){
        if($request->ajax()){
            try{
                if($val == 'add'){
                    $data = new Harian;

                    $data->tgl_bayar = date('Y-m-d',time());
                    $data->bln_bayar = date('Y-m',time());
                    $data->thn_bayar = date('Y',time());
                    $data->nama = ucwords($request->nama);
                    $data->id_kasir = Session::get('userId');
                    $data->kasir = Session::get('username');

                    $total = 0;

                    if($request->keamananlos != 0 | $request->keamananlos != NULL){
                        $hasil = explode(',',$request->keamananlos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->keamanan_los = $hasil;
                    $total = $total + $hasil;

                    if($request->kebersihanlos != 0 | $request->kebersihanlos != NULL){
                        $hasil = explode(',',$request->kebersihanlos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->kebersihan_los = $hasil;
                    $total = $total + $hasil;

                    if($request->kebersihanpos != 0 | $request->kebersihanpos != NULL){
                        $hasil = explode(',',$request->kebersihanpos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->kebersihan_pos = $hasil;
                    $total = $total + $hasil;

                    if($request->kebersihanlebihpos != 0 | $request->kebersihanlebihpos != NULL){
                        $hasil = explode(',',$request->kebersihanlebihpos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->kebersihan_lebih_pos = $hasil;
                    $total = $total + $hasil;

                    if($request->abonemen != 0 | $request->abonemen != NULL){
                        $hasil = explode(',',$request->abonemen);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->abonemen = $hasil;
                    $total = $total + $hasil;

                    if($request->laporan != NULL){
                        $hasil = $request->laporan;
                    }
                    else{
                        $hasil = NULL;
                    }
                    $data->ket = $hasil;

                    $data->total = $total;

                    $data->save();
                }
                if($val == 'edit'){
                    $data = Harian::find($request->hidden_id);

                    $data->nama = ucwords($request->nama);
                    $data->id_kasir = Session::get('userId');
                    $data->kasir = Session::get('username');

                    $total = 0;

                    if($request->keamananlos != 0 | $request->keamananlos != NULL){
                        $hasil = explode(',',$request->keamananlos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->keamanan_los = $hasil;
                    $total = $total + $hasil;

                    if($request->kebersihanlos != 0 | $request->kebersihanlos != NULL){
                        $hasil = explode(',',$request->kebersihanlos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->kebersihan_los = $hasil;
                    $total = $total + $hasil;

                    if($request->kebersihanpos != 0 | $request->kebersihanpos != NULL){
                        $hasil = explode(',',$request->kebersihanpos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->kebersihan_pos = $hasil;
                    $total = $total + $hasil;

                    if($request->kebersihanlebihpos != 0 | $request->kebersihanlebihpos != NULL){
                        $hasil = explode(',',$request->kebersihanlebihpos);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->kebersihan_lebih_pos = $hasil;
                    $total = $total + $hasil;

                    if($request->abonemen != 0 | $request->abonemen != NULL){
                        $hasil = explode(',',$request->abonemen);
                        $hasil = implode('',$hasil);
                    }
                    else{
                        $hasil = 0;
                    }
                    $data->abonemen = $hasil;
                    $total = $total + $hasil;

                    if($request->laporan != NULL){
                        $hasil = $request->laporan;
                    }
                    else{
                        $hasil = NULL;
                    }
                    $data->ket = $hasil;

                    $data->total = $total;

                    $data->save();
                }
                return response()->json(['success' => 'Data Ditambah']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Ditambah']);
            }
        }
    }

    public function harianpenerimaan(Request $request){
        $tanggal = $request->tgl_penerimaan;
        $tgl = $tanggal;
        $tanggal = IndoDate::tanggal($tanggal, ' ');
        $cetak = IndoDate::tanggal(date('Y-m-d',time()),' ');

        $dataset = Harian::where([['tgl_bayar',$tgl],['id_kasir',Session::get('userId')]])->get();

        return view('kasir.harian-penerimaan',[
            'dataset' => $dataset,
            'cetak'   => $cetak,
            'tanggal' => $tanggal,
        ]);
    }

    public function getutama(Request $request){
        $tanggal = $request->tgl_utama;
        $tgl = $tanggal;
        $tanggal = IndoDate::tanggal($tanggal, ' ');
        $cetak = IndoDate::tanggal(date('Y-m-d',time()),' ');

        $data  = User::where('role','kasir')->get();
        $i = 0;
        $dataset = array();
        foreach($data as $d){
            $dataset[$i]['nama'] = $d->nama;

            $harian = Harian::where([['tgl_bayar',$tgl],['id_kasir',$d->id]])->get();
            $har_total  = 0;
            foreach($harian as $t){
                $har_total = $har_total + $t->total;
            }
            $hari = $har_total;

            $bulanan = Pembayaran::where([['tgl_bayar',$tgl],['id_kasir',$d->id]])->get();
            $bul_total  = 0;
            foreach($bulanan as $b){
                $bul_total = $bul_total + $b->realisasi;
            }
            $bulan = $bul_total;

            $dataset[$i]['bulanan'] = $bulan;
            $dataset[$i]['harian'] = $hari;
            $dataset[$i]['jumlah'] = $bulan + $hari;

            $i++;
        }

        return view('kasir.utama',[
            'dataset' => $dataset,
            'cetak'   => $cetak,
            'tanggal' => $tanggal,
        ]);
    }

    public function getsisa(){
        return view('kasir.sisa');
    }

    public function getselesai(){
        return view('kasir.selesai');
    }

    public function getprabayar(){
        return view('kasir.prabayar');
    }

    public function settings(){
        return view('kasir.settings');
    }

    public function bayar($kontrol){
        // $profile = CapabilityProfile::load("POS-5890");
        // $connector = new RawbtPrintConnector();
        // $printer = new Printer($connector,$profile);

        // try {
        //     //MOBILE
        //     if(Session::get('printer') == 'panda'){
        //         $items = array(
        //             new StrukMobile("Listrik", $awalListrik, $akhirListrik, $pakaiListrik, $listrik, 'listrik'),
        //             new StrukMobile("Air Bersih", $awalAir, $akhirAir, $pakaiAir, $airbersih, 'airbersih'),
        //             new StrukMobile("K.aman IPK", '', '', '', $keamananipk, 'keamananipk'),
        //             new StrukMobile("Kebersihan", '', '', '', $kebersihan, 'kebersihan'),
        //             new StrukMobile("Air Kotor", '', '', '', $airkotor, 'airkotor'),
        //             new StrukMobile("Tunggakan", '', '', '', $tunggakan, 'tunggakan'),
        //             new StrukMobile("Denda", '', '', '', $denda, 'denda'),
        //             new StrukMobile("Lain Lain", '', '', '', $lain, 'lain'),
        //         );
        //         $printer -> setJustification(Printer::JUSTIFY_CENTER);
        //         $printer -> text("================================================\n");
        //         $printer -> text("Badan Pengelola Pusat Perdagangan Caringin.\n");
        //         $printer -> selectPrintMode();
        //         $printer -> text("Jl. Soetta No.220 Blok A1 No.21-24\n");
        //         $printer -> text("================================================\n");
        //         $printer -> setJustification(Printer::JUSTIFY_LEFT);
        //         $printer -> text("Pedagang : ".$pengguna."\n");
        //         $printer -> text("Kontrol  : ".$kontrol."\n");
        //         $printer -> text("Tagihan  : ".$blnTagihan."\n");
        //         $printer -> text("Kasir    : ".$kasir."\n");
        //         $printer -> setJustification(Printer::JUSTIFY_CENTER);
        //         $printer -> text("------------------------------------------------\n");
        //         $printer -> setJustification(Printer::JUSTIFY_LEFT);
        //         $printer -> setEmphasis(true);
        //         $printer -> text(new StrukMobile('Fasilitas', 'Awal', 'Akhir', 'Pakai', 'Rp.', 'header'));
        //         $printer -> setEmphasis(false);
        //         $printer -> text("------------------------------------------------\n");

        //         foreach ($items as $item) {
        //             $printer -> text($item);
        //         }
                
        //         $printer -> setJustification(Printer::JUSTIFY_CENTER);
        //         $printer -> text("------------------------------------------------\n");
        //         $printer -> setJustification(Printer::JUSTIFY_LEFT);
        //         $printer -> text(new Item('Total', $total));
        //         $printer -> setJustification(Printer::JUSTIFY_CENTER);
        //         $printer -> text("------------------------------------------------\n");
        //         $printer -> text("Total pembayaran telah termasuk PPN\n");
        //         $printer -> text("Dibayar pada ".$time. "\n\n");
        //         $printer -> text("Struk ini merupakan bukti\n");
        //         $printer -> text("pembayaran yang sah, harap disimpan\n");
        //         $printer -> cut();

        //     }
        //     //LAPANGAN
        //     else if(Session::get('printer') == 'androidpos'){
        //         $items = array(
        //             new StrukLapangan("Listrik", $listrik),
        //             new StrukLapangan("Air Bersih", $airbersih),
        //             new StrukLapangan("K.aman IPK", $keamananipk),
        //             new StrukLapangan("Kebersihan", $kebersihan),
        //             new StrukLapangan("Air Kotor", $airkotor),
        //             new StrukLapangan("Tunggakan", $tunggakan),
        //             new StrukLapangan("Denda", $denda),
        //             new StrukLapangan("Lain Lain", $lain),
        //         );
        //         $printer->setJustification(Printer::JUSTIFY_CENTER);
        //         $printer->text("Badan\n");
        //         $printer->text("Pengelola Pusat Perdagangan\n");
        //         $printer->text("Caringin\n");
        //         $printer->selectPrintMode();
        //         $printer->text("Jl.Soetta 220 Blok A1 No.21-24\n");
        //         $printer->text("--------------------------------\n");
        //         $printer->setJustification(Printer::JUSTIFY_LEFT);
        //         $printer->text("Pdg: ".$pengguna."\n");
        //         $printer->text("Alm: ".$kontrol."\n");
        //         $printer->text("Tgh: ".$blnTagihan."\n");
        //         $printer->text("Ksr: ".$kasir."\n");
        //         $printer->setJustification(Printer::JUSTIFY_CENTER);
        //         $printer->text("--------------------------------\n");
        //         $printer->setJustification(Printer::JUSTIFY_LEFT);
        //         $printer->setEmphasis(true);
        //         $printer->text(new StrukLapangan('Fasilitas', 'Rp.'));
        //         $printer->setEmphasis(false);
        //         $printer->text("--------------------------------\n");
    
        //         foreach ($items as $item) {
        //             $printer->text($item);
        //         }
                
        //         $printer->setJustification(Printer::JUSTIFY_CENTER);
        //         $printer->text("--------------------------------\n");
        //         $printer->setJustification(Printer::JUSTIFY_LEFT);
        //         $printer->text(new StrukLapangan('Total', $total));
        //         $printer->setJustification(Printer::JUSTIFY_CENTER);
        //         $printer->text("--------------------------------\n");
        //         $printer->text("Total pembayaran termasuk PPN\n");
        //         $printer->text("Dibayar pada ".$time. "\n\n");
        //         $printer->text("Struk ini merupakan\n");
        //         $printer->text("bukti pembayaran yang sah,\n");
        //         $printer->text("harap disimpan\n");
        //         $printer->cut();
        //     }
        //     else{
        //         $items = array(
        //             new StrukLarge("Listrik", $awalListrik, $akhirListrik, $pakaiListrik, $listrik, 'listrik'),
        //             new StrukLarge("Air Bersih", $awalAir, $akhirAir, $pakaiAir, $airbersih, 'airbersih'),
        //             new StrukLarge("K.aman IPK", '', '', '', $keamananipk, 'keamananipk'),
        //             new StrukLarge("Kebersihan", '', '', '', $kebersihan, 'kebersihan'),
        //             new StrukLarge("Air Kotor", '', '', '', $airkotor, 'airkotor'),
        //             new StrukLarge("Tunggakan", '', '', '', $tunggakan, 'tunggakan'),
        //             new StrukLarge("Denda", '', '', '', $denda, 'denda'),
        //             new StrukLarge("Lain Lain", '', '', '', $lain, 'lain'),
        //         );
        //         // Content
        //         $printer->text("\n ------------------------------------------ \n");
        //         $printer->text("|             BADAN  PENGELOLA             |\n");
        //         $printer->text("|        PUSAT PERDAGANGAN CARINGIN        |\n");
        //         $printer->text("|             BUKTI PEMBAYARAN             |\n");
        //         $printer->text(" ---------------- DES 2020 ---------------- \n");
        //         $printer->text("| Pedagang : BTN                           |\n");
        //         $printer->text("| Kontrol  : A-1-001                       |\n");
        //         $printer->text("| Kasir    : Demo Kasir                    |\n");
        //         $printer->text("| -- FASILITAS ------------------ HARGA -- |\n");
        //         $printer->text("| 1. Listrik                    11,000,000 |\n");
        //         $printer->text("|      + Daya       10,300                 |\n");
        //         $printer->text("|        Awal      655,583                 |\n");
        //         $printer->text("|        Akhir     656,683                 |\n");
        //         $printer->text("|        Pakai       1,100                 |\n");
        //         $printer->text("| 2. Air Bersih                 11,000,000 |\n");
        //         $printer->text("|      + Awal       23,541                 |\n");
        //         $printer->text("|        Akhir      23,662                 |\n");
        //         $printer->text("|        Pakai         121                 |\n");
        //         $printer->text("| 3. Keamanan IPK               11,000,000 |\n");
        //         $printer->text("| 4. Kebersihan                 11,000,000 |\n");
        //         $printer->text("| 5. Air Kotor                  11,000,000 |\n");
        //         $printer->text("| 6. Tunggakan                  11,000,000 |\n");
        //         $printer->text("| 7. Denda                      11,000,000 |\n");
        //         $printer->text("| 8. Lain - Lain                11,000,000 |\n");
        //         $printer->text(" ------------------------------------------ \n");
        //         $printer->text("| TOTAL                     Rp. 11,000,000 |\n");
        //         $printer->text(" ------------------------------------------ \n");
        //         $printer->text("        No.Faktur : 00167/2020/12/12        \n");
        //         $printer->text("      Dibayar pada 12/12/2020 14:42:55      \n");
        //         $printer->text("    Total pembayaran sudah termasuk PPN.    \n");
        //         $printer->text("    Struk ini merupakan bukti pembayaran    \n");
        //         $printer->text("         yang sah, Harap disimpan.          \n");
        //     }
        // } catch (Exception $e) {
        //     return redirect()->route('kasir.index');
        // } finally {
        //     $printer->close();
        // }
    }
}
