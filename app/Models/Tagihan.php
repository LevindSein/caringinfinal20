<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tagihan extends Model
{
    use HasFactory;
    protected $table ='tagihan';
    protected $fillable = [
        'id',
        'nama',
        'blok',
        'kd_kontrol',
        'bln_pakai',
        'tgl_tagihan',
        'bln_tagihan',
        'thn_tagihan',
        'tgl_expired',
        'stt_lunas',
        'stt_bayar',
        'awal_airbersih',
        'akhir_airbersih',
        'pakai_airbersih',
        'byr_airbersih',
        'pemeliharaan_airbersih',
        'beban_airbersih',
        'arkot_airbersih',
        'sub_airbersih',
        'dis_airbersih',
        'ttl_airbersih',
        'rea_airbersih',
        'sel_airbersih',
        'den_airbersih',
        'daya_listrik',
        'awal_listrik',
        'akhir_listrik',
        'pakai_listrik',
        'byr_listrik',
        'rekmin_listrik',
        'blok1_listrik',
        'blok2_listrik',
        'beban_listrik',
        'bpju_listrik',
        'sub_listrik',
        'dis_listrik',
        'ttl_listrik',
        'rea_listrik',
        'sel_listrik',
        'den_listrik',
        'jml_alamat',
        'sub_keamananipk',
        'dis_keamananipk',
        'ttl_keamananipk',
        'rea_keamananipk',
        'sel_keamananipk',
        'sub_kebersihan',
        'dis_kebersihan',
        'ttl_kebersihan',
        'rea_kebersihan',
        'sel_kebersihan',
        'ttl_airkotor',
        'rea_airkotor',
        'sel_airkotor',
        'ttl_lain',
        'rea_lain',
        'sel_lain',
        'sub_tagihan',
        'dis_tagihan',
        'ttl_tagihan',
        'rea_tagihan',
        'sel_tagihan',
        'den_tagihan',
        'stt_denda',
        'stt_kebersihan',
        'stt_keamananipk',
        'stt_listrik',
        'stt_airbersih',
        'stt_airkotor',
        'stt_lain',
        'ket',
        'via_tambah',
        'stt_publish',
        'updated_at',
        'created_at'
    ];

    public static function fasilitas($id,$fas){
        try{
            $data = DB::table('tagihan')
            ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
            ->where([
                ['tagihan.kd_kontrol',$id],
                ['tagihan.stt_lunas',0],
                ['tempat_usaha.trf_'.$fas,'!=',NULL]
            ])
            ->select(DB::raw("SUM(sel_$fas) as selisih"))
            ->get();
            return $data[0]->selisih;
        }
        catch(\Exception $e){
            return 0;
        }
    }
}
