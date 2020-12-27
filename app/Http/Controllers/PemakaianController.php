<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pemakaian;
use App\Models\IndoDate;

class PemakaianController extends Controller
{
    public function index(){
        return view('pemakaian.index',[
            'dataset'=>Pemakaian::data()
        ]);
    }

    public function fasilitas(Request $request, $fasilitas, $bulan){
        if($fasilitas == 'listrik'){
            $rekap = Pemakaian::rekapListrik($bulan);

            return view('pemakaian.listrik',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapListrik($rekap),
                'rincian'=>Pemakaian::rincianListrik($bulan),
            ]);
        }

        if($fasilitas == 'airbersih'){
            $rekap = Pemakaian::rekapAirBersih($bulan);

            return view('pemakaian.airbersih',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapAirBersih($rekap),
                'rincian'=>Pemakaian::rincianAirBersih($bulan),
            ]);
        }

        if($fasilitas == 'keamananipk'){
            $rekap = Pemakaian::rekapKeamananIpk($bulan);

            return view('pemakaian.keamananipk',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapKeamananIpk($rekap),
                'rincian'=>Pemakaian::rincianKeamananIpk($bulan),
            ]);
        }

        if($fasilitas == 'kebersihan'){
            $rekap = Pemakaian::rekapKebersihan($bulan);

            return view('pemakaian.kebersihan',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapKebersihan($rekap),
                'rincian'=>Pemakaian::rincianKebersihan($bulan),
            ]);
        }

        if($fasilitas == 'total'){
            $rekap = Pemakaian::rekapTotal($bulan);

            return view('pemakaian.total',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapTotal($rekap),
                'rincian'=>Pemakaian::rincianTotal($bulan),
            ]);
        }
    }
}
