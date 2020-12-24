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
                    $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_lunas',0],['stt_publish',1]])
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
                $pembayaran = new Pembayaran;
                $pembayaran->tgl_bayar = $tanggal;
                $pembayaran->bln_bayar = $bulan;
                $pembayaran->thn_bayar = $tahun;
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

                $pembayaran->diskon = $d->dis_tagihan;
                $pembayaran->total = $total;
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

                $d->rea_tagihan = $total;
                $d->sel_tagihan = $selisih;
                $d->save();
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
        //
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
            
            $data['tunggakan']     = $data1[0]->tunggakan;
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

    public function bayar($kontrol){
        // $time = date("d/m/Y H:i:s", time());
        // $blnSekarang = date("Y-m", time());
        // $blnLalu = strtotime($blnSekarang);
        // $blnPakai = date("Y-m", strtotime("-2 month", $blnLalu)); //-1 month seharusnya
        // $blnTagihan = Kasir::indoBln($blnSekarang);

        // $tagihan = Tagihan::where([['id_tempat',$id],['stt_lunas',0],['bln_pakai',$blnPakai]])
        // ->select(
        //     'nama as pengguna',
        //     'kd_kontrol as kontrol',
        //     'awal_airbersih as awalAir',
        //     'akhir_airbersih as akhirAir',
        //     'pakai_airbersih as pakaiAir',
        //     'awal_listrik as awalListrik',
        //     'akhir_listrik as akhirListrik',
        //     'pakai_listrik as pakaiListrik',
        //     'sel_listrik as listrik', 
        //     'sel_airbersih as airbersih', 
        //     'sel_keamananipk as keamananipk',
        //     'sel_kebersihan as kebersihan',
        //     'sel_airkotor as airkotor',
        //     'sel_lain as lain')
        // ->first();

        // $tunggakan = Tagihan::where([['id_tempat',$id],['stt_lunas',0],['bln_pakai','<',$blnPakai]])
        // ->select(
        //     DB::raw('SUM(sel_tagihan) as tunggakan'),
        //     DB::raw('SUM(den_tagihan) as denda'))
        // ->get();

        // $total = Tagihan::where([['id_tempat',$id],['stt_lunas',0]])
        // ->select(DB::raw('SUM(sel_lain) as lain'),DB::raw('SUM(sel_tagihan) as total'))
        // ->get();

        // $awalListrik = number_format($tagihan->awalListrik);
        // $akhirListrik = number_format($tagihan->akhirListrik);
        // $pakaiListrik = number_format($tagihan->pakaiListrik);
        // $listrik = number_format($tagihan->listrik);
        
        // $awalAir = number_format($tagihan->awalAir);
        // $akhirAir = number_format($tagihan->akhirAir);
        // $pakaiAir = number_format($tagihan->pakaiAir);
        // $airbersih = number_format($tagihan->airbersih);

        // $keamananipk = number_format($tagihan->keamananipk);

        // $kebersihan = number_format($tagihan->kebersihan);

        // $airkotor = number_format($tagihan->airkotor);

        // $pengguna = $tagihan->pengguna;
        // $kontrol = $tagihan->kontrol;

        // $denda = number_format($tunggakan[0]->denda);
        // $tunggakan = number_format($tunggakan[0]->tunggakan - $tagihan->lain);
        // $lain = number_format($total[0]->lain);

        // $total = number_format($total[0]->total);

        // $kasir = Session::get('username');

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
        //         $printer->text("\n\n");
        //         $printer->text("                           BADAN PENGELOLA PUSAT PERDAGANGAN CARINGIN                            \n");
        //         $printer->text("                                 KEMITRAAN KOPPAS INDUK BANDUNG                                  \n");
        //         $printer->text("                                        SEGI PEMBAYARAN                                          \n");
        //         $printer->text("=================================================================================================\n");
        //         $printer->text(new StrukLarge("Pengguna : ".$pengguna, '', '', '', "Kasir   : ".$kasir, 'header'));
        //         $printer->text(new StrukLarge("Kontrol  : ".$kontrol, '', '', '', "Tagihan : ".$blnTagihan, 'header'));
        //         $printer->text("--- FASILITAS --------------- AWAL ------------- AKHIR ------------- PAKAI ----------- JUMLAH ---\n");
        //         foreach ($items as $item) {
        //             $printer -> text($item);
        //         }
        //         $printer->text("-------------------------------------------------------------------------------------------------\n");
        //         $printer->text(new StrukLarge("Total Pembayaran", '', '', '', "Rp. ".$total, 'total'));
        //         $printer->text("-------------------------------------------------------------------------------------------------\n");
        //         $printer->text(new StrukLarge("Dibayar pada ".$time." - Total pembayaran telah termasuk PPN", '', '', '', '', 'footer')."\n");
                
        //         // // Content
        //         // $printer->text("\n ------------------------------------------ \n");
        //         // $printer->text("|             BADAN  PENGELOLA             |\n");
        //         // $printer->text("|        PUSAT PERDAGANGAN CARINGIN        |\n");
        //         // $printer->text("|             BUKTI PEMBAYARAN             |\n");
        //         // $printer->text(" ---------------- DES 2020 ---------------- \n");
        //         // $printer->text("| Pedagang : BTN                           |\n");
        //         // $printer->text("| Kontrol  : A-1-001                       |\n");
        //         // $printer->text("| Kasir    : Demo Kasir                    |\n");
        //         // $printer->text("| -- FASILITAS ------------------ HARGA -- |\n");
        //         // $printer->text("| 1. Listrik                    11,000,000 |\n");
        //         // $printer->text("|      + Daya       10,300                 |\n");
        //         // $printer->text("|        Awal      655,583                 |\n");
        //         // $printer->text("|        Akhir     656,683                 |\n");
        //         // $printer->text("|        Pakai       1,100                 |\n");
        //         // $printer->text("| 2. Air Bersih                 11,000,000 |\n");
        //         // $printer->text("|      + Awal       23,541                 |\n");
        //         // $printer->text("|        Akhir      23,662                 |\n");
        //         // $printer->text("|        Pakai         121                 |\n");
        //         // $printer->text("| 3. Keamanan IPK               11,000,000 |\n");
        //         // $printer->text("| 4. Kebersihan                 11,000,000 |\n");
        //         // $printer->text("| 5. Air Kotor                  11,000,000 |\n");
        //         // $printer->text("| 6. Tunggakan                  11,000,000 |\n");
        //         // $printer->text("| 7. Denda                      11,000,000 |\n");
        //         // $printer->text("| 8. Lain - Lain                11,000,000 |\n");
        //         // $printer->text(" ------------------------------------------ \n");
        //         // $printer->text("| TOTAL                     Rp. 11,000,000 |\n");
        //         // $printer->text(" ------------------------------------------ \n");
        //         // $printer->text("        No.Faktur : 00167/2020/12/12        \n");
        //         // $printer->text("      Dibayar pada 12/12/2020 14:42:55      \n");
        //         // $printer->text("    Total pembayaran sudah termasuk PPN.    \n");
        //         // $printer->text("    Struk ini merupakan bukti pembayaran    \n");
        //         // $printer->text("         yang sah, Harap disimpan.          \n");
        //     }
        // } catch (Exception $e) {
        //     return redirect()->route('kasirindex','now')->with('error','Kesalahan Sistem');
        // } finally {
        //     $printer->close();
        // }
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
                $pembayaran = new Pembayaran;
                $pembayaran->tgl_bayar = $tanggal;
                $pembayaran->bln_bayar = $bulan;
                $pembayaran->thn_bayar = $tahun;
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

                $pembayaran->diskon = $d->dis_tagihan;
                $pembayaran->total = $total;
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

                $d->rea_tagihan = $total;
                $d->sel_tagihan = $selisih;
                $d->save();
            }
            return response()->json(['success' => 'Transaksi Berhasil']);
        } catch(\Exception $e){
            return response()->json(['errors' => 'Transaksi Gagal']);
        }
    }

    public function bayarPeriode($kontrol){

    }
}
