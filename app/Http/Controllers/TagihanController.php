<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use Exception;

use App\Models\Tagihan;
use App\Models\TempatUsaha;

use App\Models\IndoDate;
use App\Models\Blok;

class TagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware('tagihan');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $now = date("Y-m-d",time());
        $check = date("Y-m-25",time());

        if($now < $check){
            $sekarang = date("Y-m", time());
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", time());
            $pakai    = date("Y-m", strtotime("-1 month", $time));
        }
        else if($now >= $check){
            $sekarang = date("Y-m", time());
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime("+1 month", $time));
            $pakai    = date("Y-m", time());
        }

        if($request->ajax())
        {
            $data = Tagihan::where('bln_tagihan',$periode);
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('daya_listrik', function ($data) {
                    if ($data->daya_listrik == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->daya_listrik);
                })
                ->editColumn('awal_listrik', function ($data) {
                    if ($data->awal_listrik == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->awal_listrik);
                })
                ->editColumn('akhir_listrik', function ($data) {
                    if ($data->akhir_listrik == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->akhir_listrik);
                })
                ->editColumn('ttl_listrik', function ($data) {
                    if ($data->ttl_listrik == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->ttl_listrik);
                })
                ->editColumn('awal_airbersih', function ($data) {
                    if ($data->awal_airbersih == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->awal_airbersih);
                })
                ->editColumn('akhir_airbersih', function ($data) {
                    if ($data->akhir_airbersih == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->akhir_airbersih);
                })
                ->editColumn('ttl_airbersih', function ($data) {
                    if ($data->ttl_airbersih == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->ttl_airbersih);
                })
                ->editColumn('ttl_keamananipk', function ($data) {
                    if ($data->ttl_keamananipk == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->ttl_keamananipk);
                })
                ->editColumn('ttl_kebersihan', function ($data) {
                    if ($data->ttl_kebersihan == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->ttl_kebersihan);
                })
                ->editColumn('ttl_airkotor', function ($data) {
                    if ($data->ttl_airkotor == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->ttl_airkotor);
                })
                ->editColumn('ttl_lain', function ($data) {
                    if ($data->ttl_lain == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->ttl_lain);
                })
                ->editColumn('ttl_tagihan', function ($data) {
                    if ($data->ttl_tagihan == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return number_format($data->ttl_tagihan);
                })
                ->rawColumns([
                    'action',
                    'los',
                    'daya_listrik',
                    'awal_listrik',
                    'akhir_listrik',
                    'ttl_listrik',
                    'awal_airbersih',
                    'akhir_airbersih',
                    'ttl_airbersih',
                    'ttl_keamananipk',
                    'ttl_kebersihan',
                    'ttl_airkotor',
                    'ttl_lain',
                    'ttl_tagihan',
                ])
                ->make(true);
        }

        return view('tagihan.index',[
            'periode'       => IndoDate::bulan($periode,' '),
            'tahun'         => Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','asc')->get(),
            'blok'          => Blok::select('nama')->get(),
            'listrik_badge' => Tagihan::where([['tagihan.stt_listrik',0],['tempat_usaha.trf_listrik',1]])
                                ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                                ->count(),
            'air_badge'     => Tagihan::where([['tagihan.stt_airbersih',0],['tempat_usaha.trf_airbersih',1]])
                                ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                                ->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
