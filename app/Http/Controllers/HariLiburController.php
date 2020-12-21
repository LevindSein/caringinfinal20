<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use Exception;

use App\Models\HariLibur;
use App\Models\IndoDate;

class HariLiburController extends Controller
{
    public function __construct()
    {
        $this->middleware('harilibur');
    }

    public function index(Request $request){
        if($request->ajax())
        {
            $data = HariLibur::orderBy('id','desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('tanggal', function ($data) {
                    return IndoDate::tanggal($data->tanggal,' ');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('harilibur.index');
    }

    public function store(Request $request){
        $rules = array(
            'tanggal' => 'required',
            'ket'     => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }

        $data = [
            'tanggal' => $request->tanggal,
            'ket'     => ucwords($request->ket),
        ];

        try{
            HariLibur::create($data);
            return response()->json(['success' => 'Data Tanggal Berhasil Ditambah']);  
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }
    }

    public function edit($id){
        if(request()->ajax())
        {
            $data = HariLibur::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, HariLibur $harilibur){
        $rules = array(
            'tanggal' => 'required',
            'ket'     => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }

        $data = [
            'tanggal' => $request->tanggal,
            'ket'     => ucwords($request->ket),
        ];

        try{
            HariLibur::whereId($request->hidden_id)->update($data);
            return response()->json(['success' => 'Data Berhasil Diupdate.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Diupdate.']);
        }
    }

    public function destroy($id){
        try{
            $tanggal = HariLibur::find($id);
            $tanggal->delete();
            return response()->json(['status' => 'Data telah dihapus.']);
        }
        catch(\Exception $e){
            return response()->json(['status' => 'Data gagal dihapus.']);
        }
    }
}
