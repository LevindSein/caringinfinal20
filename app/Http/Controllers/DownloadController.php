<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Dokumen;

class DownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('human');
    }

    public function download(Request $request,$file, $id){
        $download = Dokumen::find($id);
        if($file == 'bg1'){
            header("Content-type: application/msword");
            header("Content-disposition: attachment; filename=Pemasangan Baru $download->nama.doc");
            echo $download->srt_permohonan;
        }

        if($file == 'bg3'){
            header("Content-type: application/msword");
            header("Content-disposition: attachment; filename=Berita Acara Pemasangan $download->nama.doc");
            echo $download->srt_acara;
        }

        if($file == 'pb'){
            header("Content-type: application/msword");
            header("Content-disposition: attachment; filename=Perintah Membayar $download->nama.doc");
            echo $download->srt_bayar;
        }

        if($file == 'bg4'){
            header("Content-type: application/msword");
            header("Content-disposition: attachment; filename=Surat Perintah Pemasangan $download->nama.doc");
            echo $download->srt_pasang;
        }
    }
}
