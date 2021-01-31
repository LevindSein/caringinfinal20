<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

use App\Models\TempatUsaha;

class Tagihan extends Model
{
    use HasFactory;
    protected $table ='tagihan';
    protected $fillable = [
        'id',
        'no_faktur',
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
        'stt_prabayar',
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
        'no_alamat',
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
        'via_publish',
        'warna_airbersih',
        'warna_listrik',
        'review',
        'reviewer',
        'lok_tempat',
        'updated_at',
        'created_at'
    ];

    public static function fasilitas($id,$fas){
        try{
            if($fas == 'diskon'){
                $data = DB::table('tagihan')
                ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                ->where([
                    ['tagihan.kd_kontrol',$id],
                    ['tagihan.stt_lunas',0],
                    ['tagihan.stt_publish',1]
                ])
                ->select(DB::raw("SUM(sel_tagihan) as selisih"))
                ->get();
            }
            else{
                $data = DB::table('tagihan')
                ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                ->where([
                    ['tagihan.kd_kontrol',$id],
                    ['tagihan.stt_lunas',0],
                    ['tagihan.stt_publish',1]
                ])
                ->select(DB::raw("SUM(sel_$fas) as selisih"))
                ->get();
            }
            
            return $data[0]->selisih;
        }
        catch(\Exception $e){
            return 0;
        }
    }

    public static function listrik($awal, $akhir, $daya, $id){
        $tarif = TarifListrik::find(1);
        $tagihan = Tagihan::find($id);

        $tagihan->via_tambah  = Session::get('username');
        
        $batas_rekmin = round(18 * $daya /1000);
        $pakai_listrik = $akhir - $awal;

        $a = round(($daya * $tarif->trf_standar) / 1000);
        $blok1_listrik = $tarif->trf_blok1 * $a;

        $b = $pakai_listrik - $a;
        $blok2_listrik = $tarif->trf_blok2 * $b;
        $beban_listrik = $daya * $tarif->trf_beban;

        $c = $blok1_listrik + $blok2_listrik + $beban_listrik;
        $rekmin_listrik = 53.44 * $daya;

        if($pakai_listrik <= $batas_rekmin){
            $bpju_listrik = ($tarif->trf_bpju / 100) * $rekmin_listrik;
            $blok1_listrik = 0;
            $blok2_listrik = 0;
            $beban_listrik = 0;
            $byr_listrik = $bpju_listrik + $rekmin_listrik;
            $ttl_listrik = $byr_listrik;
        }
        else{
            $bpju_listrik = ($tarif->trf_bpju / 100) * $c;
            $rekmin_listrik = 0;
            $byr_listrik = $bpju_listrik + $blok1_listrik + $blok2_listrik + $beban_listrik;
            $ttl_listrik = $byr_listrik;
        }

        $tagihan->daya_listrik = $daya;
        $tagihan->awal_listrik = $awal;
        $tagihan->akhir_listrik = $akhir;
        $tagihan->pakai_listrik = $pakai_listrik;
        $tagihan->byr_listrik = $byr_listrik;
        $tagihan->rekmin_listrik = $rekmin_listrik;
        $tagihan->blok1_listrik = $blok1_listrik;
        $tagihan->blok2_listrik = $blok2_listrik;
        $tagihan->beban_listrik = $beban_listrik;
        $tagihan->bpju_listrik = $bpju_listrik;

        $tagihan->sub_listrik = round($ttl_listrik + ($ttl_listrik * ($tarif->trf_ppn / 100)));

        $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
        $diskon = 0;
        if($tempat != NULL){
            if($tempat->dis_listrik === NULL){
                $diskon = 0;
            }
            else{
                $diskon = $tempat->dis_listrik;
                $diskon = ($tagihan->sub_listrik * $diskon) / 100;
            }
        }
        else{
            $diskon = $tagihan->dis_listrik;
            if($diskon > $tagihan->sub_listrik){
                $diskon = $tagihan->sub_listrik;
            }
        }
        
        $total = $tagihan->sub_listrik - $diskon; 
        $tagihan->dis_listrik = $diskon;
        $tagihan->ttl_listrik = $total + $tagihan->den_listrik;
        $tagihan->sel_listrik = $tagihan->ttl_listrik - $tagihan->rea_listrik;
        
        $tagihan->stt_listrik = 1;

        $warna = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_listrik',1]])->orderBy('id','desc')->limit(3)->get();
        if($warna != NULL){
            $warna_ku = 0;
            foreach($warna as $war){
                $warna_ku = $warna_ku + $war->pakai_listrik;
            }
            $warna = round($warna_ku / 3);
        
            $lima_persen             = $warna * (5/100);
            $seratussepuluh_persen   = ($warna * (110/100)) + $warna;
            $seratuslimapuluh_persen = ($warna * (150/100)) + $warna;
            
            if($pakai_listrik <= $lima_persen){
                $warna = 1;
            }
            else if($pakai_listrik >= $seratussepuluh_persen && $pakai_listrik < $seratuslimapuluh_persen){
                $warna = 2;
            }
            else if($pakai_listrik >= $seratuslimapuluh_persen){
                $warna = 3;
            }
            else{
                $warna = 0;
            }
        }
        else{
            $warna = 0;
        }

        $tagihan->warna_listrik = $warna;
        
        $tagihan->save();
    }

    public static function airbersih($awal,$akhir,$id){
        $tarif = TarifAirBersih::find(1);
        $tagihan = Tagihan::find($id);

        $tagihan->via_tambah  = Session::get('username');

        $pakai_airbersih = $akhir - $awal;
        if($pakai_airbersih > 10){
            $a = 10 * $tarif->trf_1;
            $b = ($pakai_airbersih - 10) * $tarif->trf_2;
            $byr_airbersih = $a + $b;
    
            $pemeliharaan_airbersih = $tarif->trf_pemeliharaan;
            $beban_airbersih = $tarif->trf_beban;
            $arkot_airbersih = ($tarif->trf_arkot / 100) * $byr_airbersih;

            $ttl_airbersih = $byr_airbersih + $pemeliharaan_airbersih + $beban_airbersih + $arkot_airbersih;
        }
        else{      
            $byr_airbersih = $pakai_airbersih * $tarif->trf_1;
    
            $pemeliharaan_airbersih = $tarif->trf_pemeliharaan;
            $beban_airbersih = $tarif->trf_beban;
            $arkot_airbersih = ($tarif->trf_arkot / 100) * $byr_airbersih;

            $ttl_airbersih = $byr_airbersih + $pemeliharaan_airbersih + $beban_airbersih + $arkot_airbersih;
        }

        $tagihan->awal_airbersih = $awal;
        $tagihan->akhir_airbersih = $akhir;
        $tagihan->pakai_airbersih = $pakai_airbersih;
        $tagihan->byr_airbersih = $byr_airbersih;
        $tagihan->pemeliharaan_airbersih = $pemeliharaan_airbersih;
        $tagihan->beban_airbersih = $beban_airbersih;
        $tagihan->arkot_airbersih = $arkot_airbersih;
        $tagihan->sub_airbersih = round($ttl_airbersih  + ($ttl_airbersih * ($tarif->trf_ppn / 100)));

        $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
        $diskon = 0;
        if($tempat != NULL){
            if($tempat->dis_airbersih === NULL){
                $diskon = 0;
            }
            else{
                $diskon = json_decode($tempat->dis_airbersih);
                if($diskon->type == 'diskon'){
                    $diskon = $diskon->value;
                    $diskon = ($tagihan->sub_airbersih * $diskon) / 100;
                }
                else{
                    $disc = 0;
                    $diskonArray = $diskon->value;
                    if(in_array('byr',$diskonArray)){
                        $disc = $disc + $tagihan->byr_airbersih;
                    }
                    if(in_array('beban',$diskonArray)){
                        $disc = $disc + $tagihan->beban_airbersih;
                    }
                    if(in_array('pemeliharaan',$diskonArray)){
                        $disc = $disc + $tagihan->pemeliharaan_airbersih;
                    }
                    if(in_array('arkot',$diskonArray)){
                        $disc = $disc + $tagihan->arkot_airbersih;
                    }
                    if(is_object($diskonArray[count($diskonArray) - 1])){
                        $charge = $diskonArray[count($diskonArray) - 1]->charge;
                        $charge = explode(',',$charge);
                        $persen = $charge[0];
                        if($charge[1] == 'ttl'){
                            $sale = $tagihan->sub_airbersih * ($persen / 100);
                        }
                        if($charge[1] == 'byr'){
                            $sale = $tagihan->byr_airbersih * ($persen / 100); 
                        }
                        $disc = $disc + $sale;
                    }
                    
                    $disc   = $disc + ($disc * ($tarif->trf_ppn / 100));
                    $disc   = $tagihan->sub_airbersih - $disc;
                    $diskon = $disc;
                }
            }
        }
        else{
            $diskon = $tagihan->dis_airbersih;
            if($diskon > $tagihan->sub_airbersih){
                $diskon = $tagihan->sub_airbersih;
            }
        }

        $total = $tagihan->sub_airbersih - $diskon; 
        $tagihan->dis_airbersih = $diskon;
        $tagihan->ttl_airbersih = $total + $tagihan->den_airbersih;
        $tagihan->sel_airbersih = $tagihan->ttl_airbersih - $tagihan->rea_airbersih;
        
        $tagihan->stt_airbersih = 1;

        $warna = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_airbersih',1]])->orderBy('id','desc')->limit(3)->get();
        if($warna != NULL){
            $warna_ku = 0;
            foreach($warna as $war){
                $warna_ku = $warna_ku + $war->pakai_airbersih;
            }
            $warna = round($warna_ku / 3);
        
            $lima_persen             = $warna * (5/100);
            $seratussepuluh_persen   = ($warna * (110/100)) + $warna;
            $seratuslimapuluh_persen = ($warna * (150/100)) + $warna;
            
            if($pakai_airbersih <= $lima_persen){
                $warna = 1;
            }
            else if($pakai_airbersih >= $seratussepuluh_persen && $pakai_airbersih < $seratuslimapuluh_persen){
                $warna = 2;
            }
            else if($pakai_airbersih >= $seratuslimapuluh_persen){
                $warna = 3;
            }
            else{
                $warna = 0;
            }
        }
        else{
            $warna = 0;
        }

        $tagihan->warna_airbersih = $warna;
       
        $tagihan->save();
    }

    public static function totalTagihan($id){
        $tagihan = Tagihan::find($id);
        //Subtotal
        $subtotal = 
                    $tagihan->sub_listrik     + 
                    $tagihan->sub_airbersih   + 
                    $tagihan->sub_keamananipk + 
                    $tagihan->sub_kebersihan  + 
                    $tagihan->ttl_airkotor    + 
                    $tagihan->ttl_lain;
        $tagihan->sub_tagihan = $subtotal;
        
        //Diskon
        $diskon = 
                $tagihan->dis_listrik     + 
                $tagihan->dis_airbersih   + 
                $tagihan->dis_keamananipk + 
                $tagihan->dis_kebersihan;
        $tagihan->dis_tagihan = $diskon;

        //Denda
        $tagihan->den_tagihan = $tagihan->den_listrik + $tagihan->den_airbersih;
        
        //TOTAL
        $total = 
                $tagihan->ttl_listrik     + 
                $tagihan->ttl_airbersih   + 
                $tagihan->ttl_keamananipk + 
                $tagihan->ttl_kebersihan  + 
                $tagihan->ttl_airkotor    + 
                $tagihan->ttl_lain;
        $tagihan->ttl_tagihan = $total;

        //Realisasi
        $realisasi = 
                    $tagihan->rea_listrik     + 
                    $tagihan->rea_airbersih   + 
                    $tagihan->rea_keamananipk + 
                    $tagihan->rea_kebersihan  + 
                    $tagihan->rea_airkotor    + 
                    $tagihan->rea_lain;
        $tagihan->rea_tagihan = $realisasi;

        //Selisih
        $selisih =
                    $tagihan->sel_listrik     + 
                    $tagihan->sel_airbersih   + 
                    $tagihan->sel_keamananipk + 
                    $tagihan->sel_kebersihan  + 
                    $tagihan->sel_airkotor    + 
                    $tagihan->sel_lain;
        $tagihan->sel_tagihan = $selisih;

        $tagihan->save();
    }
}
