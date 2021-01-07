<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

use App\Models\Tagihan;
use App\Models\TempatUsaha;
use App\Models\AlatListrik;
use App\Models\AlatAir;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

use App\Models\HariLibur;
use App\Models\Sinkronisasi;
use App\Models\User;

class Sinkronisasi extends Model
{
    use HasFactory;
    protected $table = 'sinkronisasi';
    protected $fillable = ['id','sinkron','faktur','surat','updated_at','created_at'];

    public static function sinkron(){
        $sync = new Sinkronisasi;
        $sync->sinkron = Session::get('sync');

        //Sinkronisasi Tagihan
        $tgl_tagihan = Session::get('sync');
        $time = strtotime($tgl_tagihan);
        $bln_tagihan = date("Y-m", strtotime($tgl_tagihan));
        $bln_pakai = date("Y-m", strtotime("-1 month", $time));
        $thn_tagihan = date("Y", strtotime($tgl_tagihan));
        $expired = date("Y-m-15", strtotime($tgl_tagihan));
        do{
            $libur = HariLibur::where('tanggal', $expired)->first();
            if ($libur != NULL){
                $stop_date = strtotime($expired);
                $expired = date("Y-m-d", strtotime("+1 day", $stop_date));
                $done = TRUE;
            }
            else{
                $done = FALSE;
            }
        }
        while($done == TRUE);
        $tgl_expired = $expired;

        $periode = Session::get('sync');
        $tempat = TempatUsaha::select('kd_kontrol','id')->where('stt_tempat',1)->orderBy('kd_kontrol','asc')->get();
        foreach($tempat as $t){
            if($periode >= date("Y-m-d",time())){
                $tagihan = Tagihan::where([['kd_kontrol',$t->kd_kontrol],['tgl_tagihan',$periode]])->select('id')->first();
                if($tagihan == NULL){
                    $tagihan = new Tagihan;
                    //Global
                    $record               = TempatUsaha::find($t->id);
                    $pengguna             = User::find($record->id_pengguna);
                    $tagihan->nama        = $pengguna->nama;
                    $tagihan->blok        = $record->blok;
                    $tagihan->kd_kontrol  = $record->kd_kontrol;
                    $tagihan->bln_pakai   = $bln_pakai;
                    $tagihan->tgl_tagihan = $tgl_tagihan;
                    $tagihan->bln_tagihan = $bln_tagihan;
                    $tagihan->thn_tagihan = $thn_tagihan;
                    $tagihan->tgl_expired = $tgl_expired;
                    $tagihan->stt_lunas   = 0;
                    $tagihan->stt_bayar   = 0;
                    $tagihan->via_tambah  = Session::get('username');
                    $tagihan->stt_publish = 0;
                    
                    //Air Bersih
                    if($record->trf_airbersih !== NULL){
                        if($record->id_meteran_air !== NULL){
                            $meter = AlatAir::find($record->id_meteran_air);
                            if($meter != NULL){
                                $tagihan->awal_airbersih = $meter->akhir;
                            }
                        }
                        $tagihan->stt_airbersih = 0;
                    }
                    //Listrik
                    if($record->trf_listrik !== NULL){
                        if($record->id_meteran_listrik !== NULL){
                            $meter = AlatListrik::find($record->id_meteran_listrik);
                            if($meter != NULL){
                                $tagihan->awal_listrik = $meter->akhir;
                                $tagihan->daya_listrik = $meter->daya;
                            }
                        }
                        $tagihan->stt_listrik = 0;
                    }

                    $tagihan->jml_alamat  = $record->jml_alamat;

                    //KeamananIPK
                    if($record->trf_keamananipk !== NULL){
                        $tarif = TarifKeamananIpk::find($record->trf_keamananipk);
                        if($tarif != NULL){
                            $tagihan->sub_keamananipk = $tarif->tarif * $tagihan->jml_alamat;
                            $diskon = 0;
                            if($record->dis_keamananipk !== NULL){
                                $tagihan->dis_keamananipk = $record->dis_keamananipk;
                                $diskon                   = $tagihan->dis_keamananipk;
                            }
                            $tagihan->ttl_keamananipk = $tagihan->sub_keamananipk - $diskon;
                            $tagihan->rea_keamananipk = 0;
                            $tagihan->sel_keamananipk = $tagihan->ttl_keamananipk;
                        }
                        $tagihan->stt_keamananipk     = 1;
                    }

                    //Kebersihan
                    if($record->trf_kebersihan !== NULL){
                        $tarif = TarifKebersihan::find($record->trf_kebersihan);
                        if($tarif != NULL){
                            $tagihan->sub_kebersihan = $tarif->tarif * $tagihan->jml_alamat;
                            $diskon = 0;
                            if($record->dis_kebersihan !== NULL){
                                $tagihan->dis_kebersihan = $record->dis_kebersihan;
                                $diskon                  = $tagihan->dis_kebersihan;
                            }
                            $tagihan->ttl_kebersihan = $tagihan->sub_kebersihan - $diskon;
                            $tagihan->rea_kebersihan = 0;
                            $tagihan->sel_kebersihan = $tagihan->ttl_kebersihan;
                        }
                        $tagihan->stt_kebersihan     = 1;
                    }

                    //Air Kotor
                    if($record->trf_airkotor !== NULL){
                        $tarif = TarifAirKotor::find($record->trf_airkotor);
                        if($tarif != NULL){
                            $tagihan->ttl_airkotor = $tarif->tarif;
                            $tagihan->rea_airkotor = 0;
                            $tagihan->sel_airkotor = $tagihan->ttl_airkotor;
                        }
                        $tagihan->stt_airkotor     = 1;
                    }

                    //Lain - Lain
                    if($record->trf_lain !== NULL){
                        $tarif = TarifLain::find($record->trf_lain);
                        if($tarif != NULL){
                            $tagihan->ttl_lain = $tarif->tarif;
                            $tagihan->rea_lain = 0;
                            $tagihan->sel_lain = $tagihan->ttl_lain;
                        }
                        $tagihan->stt_lain     = 1;
                    }

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
        }

        $sync->save();
        Session::put('sync','off');
    }
}
