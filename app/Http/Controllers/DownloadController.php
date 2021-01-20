<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\IndoDate;

class DownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('human');
    }

    public function download($file){
        if($file == 'bg1'){
            $document = file_get_contents('rtf/BG1.rtf');

            $judul = Session::get('pJudul');
            $nosurat = Session::get('pKopSurat');
            $nama = Session::get('pNama');
            $blok = Session::get('pBlok');
            $alamat = Session::get('pAlamat');
            $kontrol = Session::get('pKontrol');
            $seri = Session::get('pSeri');
            $tarif = "Rp. ".number_format(Session::get('pTarif'));
            $permohonan = Session::get('pPermohonan');
            $keperluan = Session::get('pKeperluan');
            $fasilitas = Session::get('pFasilitas');
            $tanggal = IndoDate::tanggal(date('Y-m-d',time()),' ');

            $document = str_replace("#JUDULSURAT", $judul, $document);
            $document = str_replace("#NOMORSURAT", $nosurat, $document);
            $document = str_replace("#NAMA", $nama, $document);
            $document = str_replace("#BLOK", $blok, $document);
            $document = str_replace("#ALAMAT", $alamat, $document);
            $document = str_replace("#NOKONTROL", $kontrol, $document);
            $document = str_replace("#NOSERI", $seri, $document);
            $document = str_replace("#TARIF", $tarif, $document);
            $document = str_replace("#PERMOHONAN", $permohonan, $document);
            $document = str_replace("#KEPERLUAN", $keperluan, $document);
            $document = str_replace("#FASILITAS", $fasilitas, $document);
            $document = str_replace("#TANGGAL", $tanggal, $document);

            header("Content-type: application/msword");
            header("Content-disposition: attachment; filename=Pemasangan Listrik $nama.doc");
                
            echo $document;
        }
    }
}
