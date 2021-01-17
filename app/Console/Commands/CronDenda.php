<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Models\TempatUsaha;
use App\Models\TarifAirBersih;
use App\Models\TarifListrik;
use Exception;

class CronDenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:denda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Denda';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sekarang = date('Y-m-d',time());
        $denda    = date('Y-m-15',time());
        if($sekarang >= $denda){
            try{
                $tagihan = Tagihan::where([['stt_lunas',0],['stt_prabayar',1]])->get();
                foreach($tagihan as $t){
                    $expired = $t->tgl_expired;
                    $expired = date('Y-m-d',strtotime($expired . "+1 days"));

                    if(date('Y-m-d',time()) > $expired){
                        if($t->stt_lunas === 0){
                            $t->stt_prabayar = 0;
                            $t->save();
                        }
                    }
                }

                $tagihan = Tagihan::where([['stt_lunas',0],['stt_prabayar',0]])->get();
                
                foreach($tagihan as $t){    
                    $airbersih = TarifAirBersih::first();

                    $listrik = TarifListrik::first();

                    $date1 = $t->tgl_expired;
                    $date2 = date('Y-m-d',time());
                    
                    $ts1 = strtotime($date1);
                    $ts2 = strtotime($date2);
                    
                    $year1 = date('Y', $ts1);
                    $year2 = date('Y', $ts2);
                    
                    $month1 = date('m', $ts1);
                    $month2 = date('m', $ts2);
                    
                    $day1 = date('d', $ts1);
                    $day2 = date('d', $ts2);
                    
                    if($t->stt_denda <= 4){
                        if($day2 - $day1 > 0){
                            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                            if($diff == 0 || $diff == 1 || $diff == 2 || $diff == 3){
                                $diff = $diff + 1;
                            }
                        }

                        if($day2 - $day1 <= 0){
                            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                            if($diff == 1 || $diff == 2 || $diff == 3 || $diff == 4){
                                $diff = $diff;
                            }
                        }

                        $t->stt_denda = $diff;

                        if($t->sel_airbersih > 0){
                            $t->den_airbersih = $diff * $airbersih->trf_denda;
                            $t->ttl_airbersih =  $t->sub_airbersih - $t->dis_airbersih + $t->den_airbersih;
                            $t->sel_airbersih = $t->ttl_airbersih - $t->rea_airbersih;
                        }

                        if($t->sel_listrik > 0){
                            if($t->daya_listrik <= 4400){
                                $t->den_listrik = $diff * $listrik->trf_denda;
                                $t->ttl_listrik =  $t->sub_listrik - $t->dis_listrik + $t->den_listrik;
                                $t->sel_listrik = $t->ttl_listrik - $t->rea_listrik;
                            }
                            else if($t->daya_listrik > 4400){
                                $t->den_listrik = $diff * (($listrik->trf_denda_lebih / 100) * $t->ttl_listrik);
                                $t->ttl_listrik =  $t->sub_listrik - $t->dis_listrik + $t->den_listrik;
                                $t->sel_listrik = $t->ttl_listrik - $t->rea_listrik;
                            }
                        }

                        $t->save();
                        
                        Tagihan::totalTagihan($t->id);

                        if($t->stt_denda == 2){
                            $tempat = TempatUsaha::where('kd_kontrol',$t->kd_kontrol)->first();
                            if($tempat != NULL){
                                $tempat->stt_bongkar = 0;
                                $tempat->save();
                            }
                        }

                        if($t->stt_denda == 3){
                            $tempat = TempatUsaha::where('kd_kontrol',$t->kd_kontrol)->first();
                            if($tempat != NULL){
                                $tempat->stt_bongkar = 1;
                                $tempat->save();
                            }
                        }

                        if($t->stt_denda == 4){
                            $tempat = TempatUsaha::where('kd_kontrol',$t->kd_kontrol)->first();
                            if($tempat != NULL){
                                $tempat->stt_bongkar = 1;
                                $tempat->save();
                            }
                        }
                    }
                }
                // \Log::info('Denda Fine');
            }
            catch(\Exception $e){
                \Log::info($e);
            }
        }
    }
}
