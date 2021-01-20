<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasangAlat extends Model
{
    use HasFactory;
    protected $table ='pasang_alat';
    protected $fillable = [
        'id',
        'nama',
        'blok',
        'kd_kontrol',
        'no_surat',
        'tgl_tagihan',
        'bln_tagihan',
        'thn_tagihan',
        'stt_lunas',
        'stt_bayar',
        'ttl_tagihan',
        'rea_tagihan',
        'sel_tagihan',
        'stt_pasang',
        'keterangan',
        'via_tambah',
        'updated_at',
        'created_at'
    ];
}
