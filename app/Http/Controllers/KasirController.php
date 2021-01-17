<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

use App\Models\Kasir;
use App\Models\Tagihan;
use App\Models\Struk70mm;
use App\Models\Pembayaran;
use App\Models\IndoDate;
use App\Models\User;
use App\Models\Harian;
use App\Models\Item;

use App\Models\TempatUsaha;
use App\Models\Sinkronisasi;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;

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
            ->where([['stt_lunas',0],['stt_publish',1],['ttl_tagihan','!=',0]]);
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
            ->where([['stt_lunas',0],['stt_publish',1],['bln_tagihan',Session::get('periode')],['ttl_tagihan','!=',0]]);
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

        if($request->ajax()){
            $dataset = array();

            //Periode ini -----------------------------------------
            
            $data = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['bln_tagihan','>=',date('Y-m',time())]])->orderBy('bln_tagihan','asc')->get();
            $dataset['listrik'] = 0;
            $dataset['airbersih'] = 0;
            $dataset['keamananipk'] = 0;
            $dataset['kebersihan'] = 0;
            $dataset['airkotor'] = 0;
            $dataset['lain'] = 0;
            
            $dataset['dylistrik'] = 0;
            $dataset['awlistrik'] = 0;
            $dataset['aklistrik'] = 0;
            $dataset['pklistrik'] = 0;
            
            $dataset['awairbersih'] = 0;
            $dataset['akairbersih'] = 0;
            $dataset['pkairbersih'] = 0;
            
            if($data != NULL){
                $listrik = 0;
                $denlistrik = 0;
                $airbersih = 0;
                $denairbersih = 0;
                $keamananipk = 0;
                $kebersihan = 0;
                $airkotor= 0;
                $lain = 0;
                foreach($data as $d){
                    $listrik = $listrik + $d->sel_listrik;
                    $denlistrik = $denlistrik + $d->den_listrik;
                    $dayalistrik = $d->daya_listrik;
                    $awallistrik = $d->awal_listrik;
                    $akhirlistrik = $d->akhir_listrik;
                    $pakailistrik = $d->pakai_listrik;
                    
                    $airbersih = $airbersih + $d->sel_airbersih;
                    $denairbersih = $denairbersih + $d->den_airbersih;
                    $awalairbersih = $d->awal_airbersih;
                    $akhirairbersih = $d->akhir_airbersih;
                    $pakaiairbersih = $d->pakai_airbersih;
                    
                    $keamananipk = $keamananipk + $d->sel_keamananipk;
    
                    $kebersihan = $kebersihan + $d->sel_kebersihan;
                    
                    $airkotor = $airkotor + $d->sel_airkotor;
    
                    $lain = $lain + $d->sel_lain;
                }
                
                if($listrik != 0 || $listrik != NULL){
                    $dataset['listrik'] = $listrik - $denlistrik;
                    $dataset['dylistrik'] = $dayalistrik;
                    $dataset['awlistrik'] = $awallistrik;
                    $dataset['aklistrik'] = $akhirlistrik;
                    $dataset['pklistrik'] = $pakailistrik;
                }
                if($airbersih != 0 || $airbersih != NULL){
                    $dataset['airbersih'] = $airbersih - $denairbersih;
                    $dataset['awairbersih'] = $awalairbersih;
                    $dataset['akairbersih'] = $akhirairbersih;
                    $dataset['pkairbersih'] = $pakaiairbersih;
                }
                if($keamananipk != 0 || $keamananipk != NULL)
                    $dataset['keamananipk'] = $keamananipk;
                if($kebersihan != 0 || $kebersihan != NULL)
                    $dataset['kebersihan'] = $kebersihan;
                if($airkotor != 0 || $airkotor != NULL)
                    $dataset['airkotor'] = $airkotor;
                if($lain != 0 || $lain != NULL)
                    $dataset['lain'] = $lain;
            }
            
            //Periode Lalu ----------------------------------------

            $data = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['stt_denda','!=',NULL],['bln_tagihan','<',date('Y-m',time())]])->get();
            $dataset['tunglistrik'] = 0;
            $dataset['tungairbersih'] = 0;
            $dataset['tungkeamananipk'] = 0;
            $dataset['tungkebersihan'] = 0;
            $dataset['tungairkotor'] = 0;
            $dataset['tunglain'] = 0;
            if($data != NULL){
                $listrik = 0;
                $denlistrik = 0;
                $airbersih = 0;
                $denairbersih = 0;
                $keamananipk = 0;
                $kebersihan = 0;
                $airkotor = 0;
                $lain = 0;
                foreach($data as $d){
                    $listrik    = $listrik + $d->sel_listrik;
                    $denlistrik = $denlistrik + $d->den_listrik;
                    
                    $airbersih    = $airbersih + $d->sel_airbersih;
                    $denairbersih = $denairbersih + $d->den_airbersih;

                    $keamananipk = $keamananipk + $d->sel_keamananipk;

                    $kebersihan = $kebersihan + $d->sel_kebersihan;
                    
                    $airkotor = $airkotor + $d->sel_airkotor;
                    
                    $lain = $lain + $d->sel_lain;
                }

                if($listrik != 0 || $listrik != NULL)
                    $dataset['tunglistrik'] = $listrik - $denlistrik;
                
                if($airbersih != 0 || $airbersih != NULL)
                    $dataset['tungairbersih'] = $airbersih - $denairbersih;

                if($keamananipk != 0 || $keamananipk != NULL)
                    $dataset['tungkeamananipk'] = $keamananipk;

                if($kebersihan != 0 || $kebersihan != NULL)
                    $dataset['tungkebersihan'] = $kebersihan;

                if($airkotor != 0 || $airkotor != NULL)
                    $dataset['tungairkotor'] = $airkotor;

                if($lain != 0 || $lain != NULL)
                    $dataset['tunglain'] = $lain;
            }

            //Periode Ini + Lalu ----------------------------------

            $no_faktur = '';
            $data = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1]])->get();
            $dataset['denlistrik'] = 0;
            $dataset['denairbersih'] = 0;
            if($data != NULL){
                $listrik = 0;
                $airbersih = 0;
                foreach($data as $d){
                    $listrik = $listrik + $d->den_listrik;
                    $airbersih = $airbersih + $d->den_airbersih;

                    if($d->no_faktur === NULL){
                        $faktur = Sinkronisasi::where('sinkron', date('Y-m-01',time()))->first();
                        $tgl_faktur = $faktur->sinkron;
                        $nomor = $faktur->faktur + 1;
                        $faktur->faktur = $nomor;
                        if($nomor < 10)
                            $nomor = '000'.$nomor;
                        else if($nomor >= 10 && $nomor < 100)
                            $nomor = '00'.$nomor;
                        else if($nomor >= 100 && $nomor < 1000)
                            $nomor = '0'.$nomor;
                        else
                            $nomor = $nomor;
        
                        $no_faktur = $nomor.'/'.str_replace('-','/',$tgl_faktur);
                        $faktur->save();
                        $d->no_faktur = $no_faktur;
                        $d->save();
                    }
                    else{
                        $no_faktur = $d->no_faktur;
                    }
                }

                if($listrik != 0 || $listrik != NULL)
                    $dataset['denlistrik'] = $listrik;

                if($airbersih != 0 || $airbersih != NULL)
                    $dataset['denairbersih'] = $airbersih;
            }

            $data = TempatUsaha::where('kd_kontrol',$kontrol)->first();
            if($data != NULL){
                $dataset['pedagang'] = User::find($data->id_pengguna)->nama;
                $dataset['los'] = $data->no_alamat;
                $dataset['lokasi'] = $data->lok_tempat;
            }
            else{
                $dataset['pedagang'] = '';
                $dataset['los'] = '';
                $dataset['lokasi'] = '';
            }
            $dataset['faktur'] = Crypt::encryptString($no_faktur);

            return response()->json(['result' => $dataset]);
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
        $data = array();
        try{
            if($request->totalTagihan == 0){
                return response()->json(['errors' => 'Transaksi 0 Tidak Dapat Dilakukan']);
            }

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

                $pembayaran->sub_tagihan = $d->sub_tagihan;
                $pembayaran->diskon = $d->dis_tagihan;
                $pembayaran->ttl_tagihan = $d->ttl_tagihan;
                $pembayaran->realisasi = $total;
                $pembayaran->sel_tagihan = $selisih;
                $pembayaran->stt_denda = $d->stt_denda;
                $pembayaran->save();

                //-------------------------------------------------------------
                $total = 0;
                $selisih = $d->sel_tagihan;

                $data['checkAirBersih'] = FALSE;
                $data['checkListrik'] = FALSE;
                $data['checkKeamananIpk'] = FALSE;
                $data['checkKebersihan'] = FALSE;
                $data['checkAirKotor'] = FALSE;
                $data['checkLain'] = FALSE;

                if(empty($request->checkAirBersih) == FALSE){
                    $d->rea_airbersih = $d->ttl_airbersih;
                    $total = $total + $d->rea_airbersih;
                    $selisih = $selisih - $d->sel_airbersih;
                    $d->sel_airbersih = 0;
                    $data['checkAirBersih'] = TRUE;
                }

                if(empty($request->checkListrik) == FALSE){
                    $d->rea_listrik = $d->ttl_listrik;
                    $total = $total + $d->rea_listrik;
                    $selisih = $selisih - $d->sel_listrik;
                    $d->sel_listrik = 0;
                    $data['checkListrik'] = TRUE;
                }

                if(empty($request->checkKeamananIpk) == FALSE){
                    $d->rea_keamananipk = $d->ttl_keamananipk;
                    $total = $total + $d->rea_keamananipk;
                    $selisih = $selisih - $d->sel_keamananipk;
                    $d->sel_keamananipk = 0;
                    $data['checkKeamananIpk'] = TRUE;
                }

                if(empty($request->checkKebersihan) == FALSE){
                    $d->rea_kebersihan = $d->ttl_kebersihan;
                    $total = $total + $d->rea_kebersihan;
                    $selisih = $selisih - $d->sel_kebersihan;
                    $d->sel_kebersihan = 0;
                    $data['checkKebersihan'] = TRUE;
                }

                if(empty($request->checkAirKotor) == FALSE){
                    $d->rea_airkotor = $d->ttl_airkotor;
                    $total = $total + $d->rea_airkotor;
                    $selisih = $selisih - $d->sel_airkotor;
                    $d->sel_airkotor = 0;
                    $data['checkAirKotor'] = TRUE;
                }

                if(empty($request->checkLain) == FALSE){
                    $d->rea_lain = $d->ttl_lain;
                    $total = $total + $d->rea_lain;
                    $selisih = $selisih - $d->sel_lain;
                    $d->sel_lain = 0;
                    $data['checkLain'] = TRUE;
                }

                if($selisih == 0){
                    $d->stt_lunas = 1;
                    $d->stt_denda = NULL;
                }

                if($total != 0){
                    $d->stt_bayar = 1;
                }
                $d->save();

                Tagihan::totalTagihan($d->id);
            }

            $data['kd_kontrol'] = $request->tempatId;
            $data['pedagang'] = $request->pedagang;
            $data['los'] = $request->los;
            $data['lokasi'] = $request->lokasi;
            $data['faktur'] = $request->faktur;

            $data['taglistrik'] = $request->taglistrik;
            $data['tagtunglistrik'] = $request->tagtunglistrik;
            $data['tagdenlistrik'] = $request->tagdenlistrik;
            $data['tagawlistrik'] = $request->tagawlistrik;
            $data['tagaklistrik'] = $request->tagaklistrik;
            $data['tagdylistrik'] = $request->tagdylistrik;
            $data['tagpklistrik'] = $request->tagpklistrik;
            
            $data['tagairbersih'] = $request->tagairbersih;
            $data['tagtungairbersih'] = $request->tagtungairbersih;
            $data['tagdenairbersih'] = $request->tagdenairbersih;
            $data['tagawairbersih'] = $request->tagawairbersih;
            $data['tagakairbersih'] = $request->tagakairbersih;
            $data['tagpkairbersih'] = $request->tagpkairbersih;
            
            $data['tagkeamananipk'] = $request->tagkeamananipk;
            $data['tagtungkeamananipk'] = $request->tagtungkeamananipk;
            
            $data['tagkebersihan'] = $request->tagkebersihan;
            $data['tagtungkebersihan'] = $request->tagtungkeamananipk;
            
            $data['tagairkotor'] = $request->tagairkotor;
            $data['tagtungairkotor'] = $request->tagtungairkotor;

            $data['taglain'] = $request->taglain;
            $data['tagtunglain'] = $request->tagtunglain;

            $data['totalTagihan'] = $request->totalTagihan;

            $data['status'] = 'success';

            return response()->json(['result' => $data]);
        } catch(\Exception $e){
            $data['status'] = 'error';
            return response()->json(['result' => $data]);
        }
    }

    public function testdata($data){
        echo($data);
        
        // $json = json_decode($data);
        // echo Crypt::decryptString($json->faktur);
        
    }

    public function bayar($objData){
        $json = json_decode($objData);
        $kontrol  = $json->kd_kontrol;
        $pedagang = $json->pedagang;
        $los = $json->los;
        $lokasi = $json->lokasi;
        $faktur = Crypt::decryptString($json->faktur);

        $listrik         = number_format($json->taglistrik);
        $tunglistrik     = number_format($json->tagtunglistrik);
        $denlistrik      = number_format($json->tagdenlistrik);
        $dayalistrik     = number_format($json->tagdylistrik);
        $awallistrik     = number_format($json->tagawlistrik);
        $akhirlistrik    = number_format($json->tagaklistrik);
        $pakailistrik    = number_format($json->tagpklistrik);
        
        $airbersih       = number_format($json->tagairbersih);
        $tungairbersih   = number_format($json->tagtungairbersih);
        $denairbersih    = number_format($json->tagdenairbersih);
        $awalairbersih   = number_format($json->tagawairbersih);
        $akhirairbersih  = number_format($json->tagakairbersih);
        $pakaiairbersih  = number_format($json->tagpkairbersih);

        $keamananipk     = number_format($json->tagkeamananipk);
        $tungkeamananipk = number_format($json->tagtungkeamananipk);
        
        $kebersihan      = number_format($json->tagkebersihan);
        $tungkebersihan  = number_format($json->tagtungkebersihan);
        
        $airkotor        = number_format($json->tagairkotor);
        $tungairkotor    = number_format($json->tagtungairkotor);
        
        $lain            = number_format($json->taglain);
        $tunglain        = number_format($json->tagtunglain);

        $total           = number_format($json->totalTagihan);

        $nama            = Session::get('username');

        $profile = CapabilityProfile::load("POS-5890");
        $connector = new RawbtPrintConnector();
        $printer = new Printer($connector,$profile);
        $i = 1;
        try{
            if(Session::get('printer') == 'panda'){
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setFont(Printer::FONT_A);
                $printer -> setEmphasis(true);
                $printer -> text("BADAN PENGELOLA PUSAT\nPERDAGANGAN CARINGIN\n");
                $printer -> setEmphasis(false);
                $printer -> selectPrintMode();
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setFont(Printer::FONT_B);
                $printer -> feed();
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> setEmphasis(true);
                $printer -> text("Nama    : $pedagang\n");
                $printer -> text("Kontrol : $kontrol\n");
                $printer -> text("Los     : $los\n");
                if($lokasi != ''){
                    $printer -> text("Lokasi  : $lokasi\n");
                }
                $printer -> setEmphasis(false);
                $printer -> feed();
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk70mm("Items","Rp.",true, true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------");
                if($json->checkListrik){
                    $printer -> feed();
                    if($json->taglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Listrik",$listrik,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Daya" ,$dayalistrik,false));
                        $printer -> text(new Struk70mm("Awal" ,$awallistrik,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirlistrik,false));
                        $printer -> text(new Struk70mm("Pakai",$pakailistrik,false));
                        $i++;
                    }
                    if($json->tagtunglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Listrik",$tunglistrik,true));
                        $i++;
                    }
                    if($json->tagdenlistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Listrik",$denlistrik,true));
                        $i++;
                    }
                }
                if($json->checkAirBersih){
                    $printer -> feed();
                    if($json->tagairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Bersih",$airbersih,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Awal" ,$awalairbersih,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirairbersih,false));
                        $printer -> text(new Struk70mm("Pakai",$pakaiairbersih,false));
                        $i++;
                    }
                    if($json->tagtungairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Bersih",$tungairbersih,true));
                        $i++;
                    }
                    if($json->tagdenairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Air Bersih",$denairbersih,true));
                        $i++;
                    }
                }
                if($json->checkKeamananIpk){
                    $printer -> feed();
                    if($json->tagkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Keamanan IPK",$keamananipk,true));
                        $i++;
                    }
                    if($json->tagtungkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Keamanan IPK",$tungkeamananipk,true));
                        $i++;
                    }
                }
                if($json->checkKebersihan){
                    $printer -> feed();
                    if($json->tagkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Kebersihan",$kebersihan,true));
                        $i++;
                    }
                    if($json->tagtungkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Kebersihan",$tungkebersihan,true));
                        $i++;
                    }
                }
                if($json->checkAirKotor){
                    $printer -> feed();
                    if($json->tagairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Kotor",$airkotor,true));
                        $i++;
                    }
                    if($json->tagtungairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Kotor",$tungairkotor,true));
                        $i++;
                    }
                }
                if($json->checkLain){
                    $printer -> feed();
                    if($json->taglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Lain Lain",$lain,true));
                        $i++;
                    }
                    if($json->tagtunglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Lain Lain",$tunglain,true));
                        $i++;
                    }
                }
                $printer -> feed();
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk70mm("Total",$total,true,true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------\n");
                $printer -> text("No.Faktur : $faktur\n");
                $printer -> text("Dibayar pada ".date('d/m/Y H:i:s',time())."\n");
                $printer -> text("Struk ini adalah bukti pembayaran\nyang sah. Harap Disimpan.\n");
                $printer -> text("Ksr : $nama\n");
                $printer -> feed();
            }
            else if(Session::get('printer') == 'androidpos'){

            }
            else{
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setFont(Printer::FONT_A);
                $printer -> setEmphasis(true);
                $printer -> text("BADAN PENGELOLA PUSAT\nPERDAGANGAN CARINGIN\n");
                $printer -> setEmphasis(false);
                $printer -> selectPrintMode();
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setFont(Printer::FONT_B);
                $printer -> feed();
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> setEmphasis(true);
                $printer -> text("Nama    : $pedagang\n");
                $printer -> text("Kontrol : $kontrol\n");
                $printer -> text("Los     : $los\n");
                if($lokasi != ''){
                    $printer -> text("Lokasi  : $lokasi\n");
                }
                $printer -> setEmphasis(false);
                $printer -> feed();
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk70mm("Items","Rp.",true, true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------");
                if($json->checkListrik){
                    $printer -> feed();
                    if($json->taglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Listrik",$listrik,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Daya" ,$dayalistrik,false));
                        $printer -> text(new Struk70mm("Awal" ,$awallistrik,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirlistrik,false));
                        $printer -> text(new Struk70mm("Pakai",$pakailistrik,false));
                        $i++;
                    }
                    if($json->tagtunglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Listrik",$tunglistrik,true));
                        $i++;
                    }
                    if($json->tagdenlistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Listrik",$denlistrik,true));
                        $i++;
                    }
                }
                if($json->checkAirBersih){
                    $printer -> feed();
                    if($json->tagairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Bersih",$airbersih,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Awal" ,$awalairbersih,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirairbersih,false));
                        $printer -> text(new Struk70mm("Pakai",$pakaiairbersih,false));
                        $i++;
                    }
                    if($json->tagtungairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Bersih",$tungairbersih,true));
                        $i++;
                    }
                    if($json->tagdenairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Air Bersih",$denairbersih,true));
                        $i++;
                    }
                }
                if($json->checkKeamananIpk){
                    $printer -> feed();
                    if($json->tagkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Keamanan IPK",$keamananipk,true));
                        $i++;
                    }
                    if($json->tagtungkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Keamanan IPK",$tungkeamananipk,true));
                        $i++;
                    }
                }
                if($json->checkKebersihan){
                    $printer -> feed();
                    if($json->tagkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Kebersihan",$kebersihan,true));
                        $i++;
                    }
                    if($json->tagtungkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Kebersihan",$tungkebersihan,true));
                        $i++;
                    }
                }
                if($json->checkAirKotor){
                    $printer -> feed();
                    if($json->tagairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Kotor",$airkotor,true));
                        $i++;
                    }
                    if($json->tagtungairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Kotor",$tungairkotor,true));
                        $i++;
                    }
                }
                if($json->checkLain){
                    $printer -> feed();
                    if($json->taglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Lain Lain",$lain,true));
                        $i++;
                    }
                    if($json->tagtunglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Lain Lain",$tunglain,true));
                        $i++;
                    }
                }
                $printer -> feed();
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk70mm("Total",$total,true,true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------\n");
                $printer -> text("No.Faktur : $faktur\n");
                $printer -> text("Dibayar pada ".date('d/m/Y H:i:s',time())."\n");
                $printer -> text("Struk ini adalah bukti pembayaran\nyang sah. Harap Disimpan.\n");
                $printer -> text("Ksr : $nama\n");
                $printer -> feed();
                $printer -> cut();
            }
        }catch(\Exception $e){
            return response()->json(['status' => 'Transaksi Berhasil, Gagal Print Struk']);
        }finally{
            $printer->close();
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
                'den_listrik',
                'den_airbersih',
            )
            ->first();

            $data['listrik']      = $dataset->sel_listrik - $dataset->den_listrik;
            $data['denlistrik']   = $dataset->den_listrik;
            $data['airbersih']    = $dataset->sel_airbersih - $dataset->den_airbersih;
            $data['denairbersih'] = $dataset->den_airbersih;
            $data['keamananipk']  = $dataset->sel_keamananipk;
            $data['kebersihan']   = $dataset->sel_kebersihan;
            $data['airkotor']     = $dataset->sel_airkotor;
            $data['lain']         = $dataset->sel_lain;

            return response()->json(['result' => $data]);
        }
    }

    public function storePeriode(Request $request)
    {
        $bayar = '';
        $tagihan = '';
        try{
            if($request->totalTagihan == 0){
                return response()->json(['errors' => 'Transaksi 0 Tidak Dapat Dilakukan']);
            }
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
                $pembayaran->stt_denda = $d->stt_denda;
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
                    $d->stt_denda = NULL;
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
            $rekap[$i]['listrik']     = $setor[0]->listrik - $setor[0]->denlistrik;
            $rekap[$i]['denlistrik']  = $setor[0]->denlistrik;
            $rekap[$i]['airbersih']   = $setor[0]->airbersih - $setor[0]->denairbersih;
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
                $rin[$j]['listrik']  = $r->byr_listrik - $r->byr_denlistrik;
                $rin[$j]['denlistrik']  = $r->byr_denlistrik;
                $rin[$j]['airbersih']  = $r->byr_airbersih - $r->byr_denairbersih;
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
                    
                    $tagihan->stt_denda = $p->stt_denda;

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
            $data = Harian::where([['tgl_bayar',date('Y-m-d',time())],['id_kasir',Session::get('userId')]])->orderBy('id','desc');
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
            $data = Harian::where([['tgl_bayar',Session::get('harian')],['id_kasir',Session::get('userId')]])->orderBy('id','desc');
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
        $bulan = IndoDate::bulan(date('Y-m',time()),' ');
        $data = Tagihan::where([['stt_lunas',0],['stt_publish',1],['ttl_tagihan','!=',0]])->select('kd_kontrol')->groupBy('kd_kontrol')->orderBy('kd_kontrol','asc')->get();
        $dataset = array();
        $i = 0;
        foreach($data as $d){
            $dataset[$i]['kd_kontrol'] = $d->kd_kontrol;
            $tagihan = Tagihan::where([['kd_kontrol',$d->kd_kontrol],['stt_lunas',0],['stt_publish',1]])
            ->select(
                DB::raw('SUM(sel_tagihan) as tagihan'),
                DB::raw('SUM(sel_listrik) as listrik'),
                DB::raw('SUM(sel_airbersih) as airbersih'),
                DB::raw('SUM(sel_keamananipk) as keamananipk'),
                DB::raw('SUM(sel_kebersihan) as kebersihan'),
                DB::raw('SUM(sel_airkotor) as airkotor'),
                DB::raw('SUM(sel_lain) as lain'),
                )
            ->get();
            if($tagihan != NULL){
                $listrik = $tagihan[0]->listrik;
                $airbersih = $tagihan[0]->airbersih;
                $keamananipk = $tagihan[0]->keamananipk;
                $kebersihan = $tagihan[0]->kebersihan;
                $airkotor = $tagihan[0]->airkotor;
                $lain = $tagihan[0]->lain;
                $total = $tagihan[0]->tagihan;
            }
            else{
                $listrik = 0;
                $airbersih = 0;
                $keamananipk = 0;
                $kebersihan = 0;
                $airkotor = 0;
                $lain = 0;
                $total = 0;
            }
            $dataset[$i]['listrik'] = $listrik;
            $dataset[$i]['airbersih'] = $airbersih;
            $dataset[$i]['keamananipk'] = $keamananipk;
            $dataset[$i]['kebersihan'] = $kebersihan;
            $dataset[$i]['airkotor'] = $airkotor;
            $dataset[$i]['lain'] = $lain;
            $dataset[$i]['total'] = $total;

            $tempat = TempatUsaha::where('kd_kontrol', $d->kd_kontrol)->first();
            if($tempat != NULL){
                $lokasi = $tempat->lok_tempat;
                $los = $tempat->no_alamat;
            }
            else{
                $lokasi = '';
                $los = '';
            }
            $dataset[$i]['lokasi'] = $lokasi;
            $dataset[$i]['los'] = $los;

            $pengguna = TempatUsaha::where('kd_kontrol',$d->kd_kontrol)->select('id_pengguna')->first();
            if($pengguna != NULL){
                $pengguna = User::find($pengguna->id_pengguna)->nama;
            }
            else{
                $pengguna = '';
            }
            $dataset[$i]['pengguna'] = $pengguna;
            $i++;
        }
        return view('kasir.sisa',[
            'dataset' => $dataset,
            'bulan' => $bulan,
        ]);
    }

    public function getselesai(){
        $bulan = IndoDate::bulan(date('Y-m',time()),' ');
        $data = Pembayaran::where('bln_bayar',date('Y-m',time()))->select('kd_kontrol')->groupBy('kd_kontrol')->orderBy('kd_kontrol','asc')->get();
        $dataset = array();
        $i = 0;
        foreach($data as $d){
            $dataset[$i]['kd_kontrol'] = $d->kd_kontrol;
            $tagihan = Pembayaran::where([['kd_kontrol',$d->kd_kontrol],['bln_bayar',date('Y-m',time())]])
            ->select(
                DB::raw('SUM(realisasi) as tagihan'),
                DB::raw('SUM(byr_listrik) as listrik'),
                DB::raw('SUM(byr_airbersih) as airbersih'),
                DB::raw('SUM(byr_keamananipk) as keamananipk'),
                DB::raw('SUM(byr_kebersihan) as kebersihan'),
                DB::raw('SUM(byr_airkotor) as airkotor'),
                DB::raw('SUM(byr_lain) as lain'),
                )
            ->get();
            if($tagihan != NULL){
                $listrik = $tagihan[0]->listrik;
                $airbersih = $tagihan[0]->airbersih;
                $keamananipk = $tagihan[0]->keamananipk;
                $kebersihan = $tagihan[0]->kebersihan;
                $airkotor = $tagihan[0]->airkotor;
                $lain = $tagihan[0]->lain;
                $total = $tagihan[0]->tagihan;
            }
            else{
                $listrik = 0;
                $airbersih = 0;
                $keamananipk = 0;
                $kebersihan = 0;
                $airkotor = 0;
                $lain = 0;
                $total = 0;
            }
            $dataset[$i]['listrik'] = $listrik;
            $dataset[$i]['airbersih'] = $airbersih;
            $dataset[$i]['keamananipk'] = $keamananipk;
            $dataset[$i]['kebersihan'] = $kebersihan;
            $dataset[$i]['airkotor'] = $airkotor;
            $dataset[$i]['lain'] = $lain;
            $dataset[$i]['total'] = $total;

            $tempat = TempatUsaha::where('kd_kontrol', $d->kd_kontrol)->first();
            if($tempat != NULL){
                $lokasi = $tempat->lok_tempat;
                $los = $tempat->no_alamat;
            }
            else{
                $lokasi = '';
                $los = '';
            }
            $dataset[$i]['lokasi'] = $lokasi;
            $dataset[$i]['los'] = $los;

            $pengguna = TempatUsaha::where('kd_kontrol',$d->kd_kontrol)->select('id_pengguna')->first();
            if($pengguna != NULL){
                $pengguna = User::find($pengguna->id_pengguna)->nama;
            }
            else{
                $pengguna = '';
            }
            $dataset[$i]['pengguna'] = $pengguna;
            $i++;
        }
        return view('kasir.selesai',[
            'dataset' => $dataset,
            'bulan' => $bulan,
        ]);
    }

    public function settings(){
        return view('kasir.settings');
    }

    public function printer(Request $request){
        if($request->ajax()){
            try{
                if(Session::get('printer') == 'panda'){
                    Session::put('printer','androidpos');
                }
                else{
                    Session::put('printer','panda');
                }

                $agent = new Agent();
                if($agent->isDesktop()){
                    Session::put('printer','desktop');
                }

                return response()->json(['success' => Session::get('printer')]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => $e]);
            }
        }
    }
}
