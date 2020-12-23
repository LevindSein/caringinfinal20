<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukLapangan extends Model
{
    private $fasilitas;
    private $harga;

    public function __construct($fasilitas = '', $harga = '')
    {
        $this->fasilitas = $fasilitas;
        $this->harga = $harga;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 22;
        $left = str_pad($this->fasilitas, $leftCols) ;
        $right = str_pad($this->harga, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
