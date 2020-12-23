<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukMobile extends Model
{
    private $fasilitas;
    private $awal;
    private $akhir;
    private $pakai;
    private $harga;
    private $status;

    public function __construct($fasilitas = '', $awal = '', $akhir = '', $pakai = '', $harga = '', $status = '')
    {
        $this->fasilitas = $fasilitas;
        $this->awal = $awal;
        $this->akhir = $akhir;
        $this->pakai = $pakai;
        $this->harga = $harga;
        $this->status = $status;
    }

    public function __toString()
    {
        if($this->status == 'listrik'){
            $fasilitasWidth = 8;
            $awalWidth = 10;
            $akhirWidth = 10;
            $pakaiWidth = 7;
            $hargaWidth = 13;
            $fasilitasShow = str_pad($this->fasilitas, $fasilitasWidth) ;
            $awalShow = str_pad($this->awal, $awalWidth, ' ', STR_PAD_LEFT);
            $akhirShow = str_pad($this->akhir, $akhirWidth, ' ', STR_PAD_LEFT);
            $pakaiShow = str_pad($this->pakai, $pakaiWidth, ' ', STR_PAD_LEFT);
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$awalShow$akhirShow$pakaiShow$hargaShow\n";
        }

        if($this->status == 'airbersih'){
            $fasilitasWidth = 8;
            $awalWidth = 8;
            $akhirWidth = 10;
            $pakaiWidth = 7;
            $hargaWidth = 13;
            $fasilitasShow = str_pad($this->fasilitas, $fasilitasWidth) ;
            $awalShow = str_pad($this->awal, $awalWidth, ' ', STR_PAD_LEFT);
            $akhirShow = str_pad($this->akhir, $akhirWidth, ' ', STR_PAD_LEFT);
            $pakaiShow = str_pad($this->pakai, $pakaiWidth, ' ', STR_PAD_LEFT);
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$awalShow$akhirShow$pakaiShow$hargaShow\n";
        }

        if($this->status == 'keamananipk' || $this->status == 'kebersihan' || $this->status == 'airkotor' || $this->status == 'tunggakan' || $this->status == 'denda' || $this->status == 'lain'){
            $fasilitasWidth = 38; //38
            $hargaWidth = 10;
            $fasilitasShow = str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }

        if($this->status == 'header'){
            $fasilitasWidth = 8;
            $awalWidth = 9;
            $akhirWidth = 10;
            $pakaiWidth = 7;
            $hargaWidth = 13;
            $fasilitasShow = str_pad($this->fasilitas, $fasilitasWidth) ;
            $awalShow = str_pad($this->awal, $awalWidth, ' ', STR_PAD_LEFT);
            $akhirShow = str_pad($this->akhir, $akhirWidth, ' ', STR_PAD_LEFT);
            $pakaiShow = str_pad($this->pakai, $pakaiWidth, ' ', STR_PAD_LEFT);
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$awalShow$akhirShow$pakaiShow$hargaShow\n";
        }
    }
}
