<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukLarge extends Model
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
            $fasilitasWidth = 15;
            $awalWidth = 15;
            $akhirWidth = 20;
            $pakaiWidth = 20;
            $hargaWidth = 20;
            $fasilitasShow = str_pad('1. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $awalShow = str_pad($this->awal, $awalWidth, ' ', STR_PAD_LEFT);
            $akhirShow = str_pad($this->akhir, $akhirWidth, ' ', STR_PAD_LEFT);
            $pakaiShow = str_pad($this->pakai, $pakaiWidth, ' ', STR_PAD_LEFT);
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$awalShow$akhirShow$pakaiShow$hargaShow\n";
        }

        if($this->status == 'airbersih'){
            $fasilitasWidth = 15;
            $awalWidth = 15;
            $akhirWidth = 20;
            $pakaiWidth = 20;
            $hargaWidth = 20;
            $fasilitasShow = str_pad('2. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $awalShow = str_pad($this->awal, $awalWidth, ' ', STR_PAD_LEFT);
            $akhirShow = str_pad($this->akhir, $akhirWidth, ' ', STR_PAD_LEFT);
            $pakaiShow = str_pad($this->pakai, $pakaiWidth, ' ', STR_PAD_LEFT);
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$awalShow$akhirShow$pakaiShow$hargaShow\n";
        }

        if($this->status == 'keamananipk'){
            $fasilitasWidth = 80; //38
            $hargaWidth = 10;
            $fasilitasShow = str_pad('3. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }

        if($this->status == 'kebersihan'){
            $fasilitasWidth = 80; //38
            $hargaWidth = 10;
            $fasilitasShow = str_pad('4. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }

        if($this->status == 'airkotor'){
            $fasilitasWidth = 80; //38
            $hargaWidth = 10;
            $fasilitasShow = str_pad('5. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }
        
        if($this->status == 'tunggakan'){
            $fasilitasWidth = 80; //38
            $hargaWidth = 10;
            $fasilitasShow = str_pad('6. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }
        
        if($this->status == 'denda'){
            $fasilitasWidth = 80; //38
            $hargaWidth = 10;
            $fasilitasShow = str_pad('7. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }
        
        if($this->status == 'lain'){
            $fasilitasWidth = 80; //38
            $hargaWidth = 10;
            $fasilitasShow = str_pad('8. ', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }
        
        if($this->status == 'total'){
            $fasilitasWidth = 71; //38
            $hargaWidth = 19;
            $fasilitasShow = str_pad('', 5, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth, ' ', STR_PAD_LEFT);
            return "$fasilitasShow$hargaShow\n";
        }

        if($this->status == 'header'){
            $fasilitasWidth = 65; //38
            $hargaWidth = 30;
            $fasilitasShow = str_pad('', 4, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, $fasilitasWidth) ;
            $hargaShow = str_pad($this->harga, $hargaWidth);
            return "$fasilitasShow$hargaShow\n";
        }

        if($this->status == 'footer'){
            $fasilitasShow = str_pad('', 12, ' ', STR_PAD_LEFT).$this->fasilitas;
            return "$fasilitasShow\n";
        }
    }
}
