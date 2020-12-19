<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use Exception;

use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

class TarifController extends Controller
{
    public function __construct()
    {
        $this->middleware('tarif');
    }

    public function index(){
        return view('tarif.index',[
            'listrik'  => TarifListrik::first(),
            'airbersih'=> TarifAirBersih::first()
        ]);
    }

    public function store(Request $request){
        if($request->radioMeter == 'listrik'){
            $rules = array(
                'standListrik' => 'required',
                'dayaListrik'  => 'required'
            );
            $role = 'listrik';
        }
        else{
            $rules = array(
                'standAir' => 'required'
            );
            $role = 'air';
        }

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }

        if($request->nomor == null){
            $nomor = NULL;
        }
        else{
            $nomor = $request->nomor;
            $nomor = strtoupper($nomor);
        }
        
        $dataset = array();
        $kode = str_shuffle('0123456789');
        $kode = substr($kode,0,5);

        try{
            if($request->radioMeter == 'listrik'){
                $akhir = explode(',',$request->standListrik);
                $akhir = implode('',$akhir);
                $daya = explode(',',$request->dayaListrik);
                $daya = implode('',$daya);
                $data = [
                    'kode'      => 'ML'.$kode,
                    'nomor'     => $nomor,
                    'akhir'     => $akhir,
                    'daya'      => $daya,
                    'stt_sedia' => 0,
                    'stt_bayar' => 0
                ];

                AlatListrik::create($data);
            }

            if($request->radioMeter == 'air'){
                $akhir = explode(',',$request->standAir);
                $akhir = implode('',$akhir);
                $data = [
                    'kode'      => 'MA'.$kode,
                    'nomor'     => $nomor,
                    'akhir'     => $akhir,
                    'stt_sedia' => 0,
                    'stt_bayar' => 0
                ];

                AlatAir::create($data);
            }
            $dataset['status'] = 'success';
            $dataset['message'] = 'Data Berhasil Ditambah';
            $dataset['role'] = $role;
            return response()->json(['result' => $dataset]);  
        }
        catch(\Exception $e){
            $dataset['status'] = 'error';
            $dataset['message'] = 'Data Gagal Ditambah';
            $dataset['role'] = $role;
            return response()->json(['result' => $dataset]);
        }
    }

    public function edit($fasilitas, $id){
        if(request()->ajax())
        {
            if($fasilitas == 'listrik'){
                $data = AlatListrik::findOrFail($id);
                return response()->json(['result' => $data]);
            }
            if($fasilitas == 'air'){
                $data = AlatAir::findOrFail($id);
                return response()->json(['result' => $data]);
            }
        }
    }

    public function update(Request $request){
        if($request->fasilitas == 'listrik'){
            $beban = explode(',',$request->bebanListrik);
            $beban = implode('',$beban);

            $blok1 = explode(',',$request->blok1);
            $blok1 = implode('',$blok1);

            $blok2 = explode(',',$request->blok2);
            $blok2 = implode('',$blok2);
            
            $denda1 = explode(',',$request->denda1);
            $denda1 = implode('',$denda1);

            $pasangListrik = explode(',',$request->pasangListrik);
            $pasangListrik = implode('',$pasangListrik);

            $data = [
                'trf_beban'        => $beban,
                'trf_blok1'        => $blok1,
                'trf_blok2'        => $blok2,
                'trf_standar'      => $request->waktu,
                'trf_bpju'         => $request->bpju,
                'trf_denda'        => $denda1,
                'trf_denda_lebih'  => $request->denda2,
                'trf_ppn'          => $request->ppnListrik,
                'trf_pasang'       => $pasangListrik
            ];      
            
            try{
                TarifListrik::whereId(1)->update($data);
                return response()->json(['success' => 'Data Berhasil Disimpan']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Disimpan']);
            }
        }

        if($request->fasilitas == 'air'){
            $beban = explode(',',$request->bebanAir);
            $beban = implode('',$beban);

            $tarif1 = explode(',',$request->tarif1);
            $tarif1 = implode('',$tarif1);

            $tarif2 = explode(',',$request->tarif2);
            $tarif2 = implode('',$tarif2);
            
            $dendaAir = explode(',',$request->dendaAir);
            $dendaAir = implode('',$dendaAir);
            
            $pemeliharaan = explode(',',$request->pemeliharaan);
            $pemeliharaan = implode('',$pemeliharaan);

            $pasangAir = explode(',',$request->pasangAir);
            $pasangAir = implode('',$pasangAir);

            $data = [
                'trf_beban'        => $beban,
                'trf_1'            => $tarif1,
                'trf_2'            => $tarif2,
                'trf_pemeliharaan' => $pemeliharaan,
                'trf_arkot'        => $request->arkot,
                'trf_denda'        => $dendaAir,
                'trf_ppn'          => $request->ppnAir,
                'trf_pasang'       => $pasangAir
            ];      
            
            try{
                TarifAirBersih::whereId(1)->update($data);
                return response()->json(['success' => 'Data Berhasil Disimpan']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Disimpan']);
            }
        }
    }

    public function destroy($fasilitas, $id){
        if($fasilitas == 'listrik'){
            $data = AlatListrik::find($id);
            $role = 'listrik';
        }
        else{
            $data = AlatAir::find($id);
            $role = 'air';
        }

        $dataset = array();
        try{
            $dataset['status'] = 'Data telah dihapus';
            $dataset['role'] = $role;
            $data->delete();
            return response()->json(['result' => $dataset]);
        }
        catch(\Exception $e){
            $dataset['status'] = 'Data gagal dihapus';
            return response()->json(['result' => $dataset]);
        }
    }
}
