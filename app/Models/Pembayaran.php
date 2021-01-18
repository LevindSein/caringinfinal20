<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table ='pembayaran';
    protected $fillable = [
        'id',
        'tgl_bayar',
        'bln_bayar',
        'thn_bayar',
        'tgl_tagihan',
        'via_bayar',
        'id_kasir',
        'nama',
        'blok',
        'kd_kontrol',
        'pengguna',
        'id_tagihan',
        'byr_listrik',
        'byr_denlistrik',
        'sel_listrik',
        'byr_airbersih',
        'byr_denairbersih',
        'sel_airbersih',
        'byr_keamananipk',
        'sel_keamananipk',
        'byr_kebersihan',
        'sel_kebersihan',
        'byr_airkotor',
        'sel_airkotor',
        'byr_lain',
        'sel_lain',
        'sub_tagihan',
        'diskon',
        'ttl_tagihan',
        'realisasi',
        'sel_tagihan',
        'stt_denda',
        'shift',
        'updated_at',
        'created_at'
    ];
}
