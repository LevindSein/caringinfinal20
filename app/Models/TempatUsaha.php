<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatUsaha extends Model
{
    use HasFactory;
    protected $table ='tempat_usaha';
    protected $fillable = [
        'id',
        'trf_kebersihan',
        'trf_keamananipk',
        'trf_listrik',
        'trf_airbersih',
        'trf_airkotor',
        'trf_lain',
        'id_pengguna',
        'id_pemilik',
        'kd_kontrol',
        'no_alamat',
        'jml_alamat',
        'bentuk_usaha',
        'id_meteran_air',
        'id_meteran_listrik',
        'blok',
        'daya',
        'dis_airbersih',
        'dis_listrik',
        'dis_keamananipk',
        'dis_kebersihan',
        'stt_cicil',
        'stt_tempat',
        'ket_tempat',
        'lok_tempat',
        'stt_bongkar',
        'updated_at',
        'created_at'
    ];
}
