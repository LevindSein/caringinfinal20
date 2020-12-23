<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndoDate extends Model
{
    use HasFactory;

    public static function tanggal($date,$separator){
        $tanggal = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $date);
        return $pecahkan[2]. $separator . $tanggal[ (int)$pecahkan[1] ] . $separator . $pecahkan[0];
    }

    public static function bulan($date,$separator){
        $tanggal = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $date);
        return $tanggal[ (int)$pecahkan[1] ] . $separator . $pecahkan[0];
    }
}
