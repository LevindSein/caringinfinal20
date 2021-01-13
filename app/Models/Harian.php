<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harian extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_harian';
    protected $fillable = [
        'id',
        'tgl_bayar',
        'bln_bayar',
        'thn_bayar',
        'nama',
        'keamanan_los',
        'keamanan_pos',
        'kebersihan_los',
        'kebersihan_lebih_pos',
        'abonemen',
        'ket',
        'total',
        'updated_at',
        'created_at'];
}
