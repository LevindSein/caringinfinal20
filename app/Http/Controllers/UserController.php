<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use Exception;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = User::where('role','admin')->orderBy('nama','asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Otoritas" name="otoritas" id="'.$data->id.'" class="otoritas"><i class="fas fa-hand-point-up" style="color:#36b9cc;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Reset" name="reset" id="'.$data->id.'" class="reset"><i class="fas fa-key fa-sm" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit fa-sm" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('ktp', function ($ktp) {
                    if ($ktp->ktp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $ktp->ktp;
                })
                ->editColumn('email', function ($email) {
                    if ($email->email == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $email->email;
                })
                ->editColumn('hp', function ($hp) {
                    if ($hp->hp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $hp->hp;
                })
                ->editColumn('otoritas', function ($otoritas) {
                    if ($otoritas->otoritas == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                })
                ->rawColumns(['action','ktp','email','hp','otoritas'])
                ->make(true);
        }
        return view('user.index');
    }

    public function manajer(Request $request){
        if($request->ajax()){
            $data = User::where('role','manajer')->orderBy('nama','asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Reset" name="reset" id="'.$data->id.'" class="reset"><i class="fas fa-key fa-sm" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit fa-sm" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('ktp', function ($ktp) {
                    if ($ktp->ktp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $ktp->ktp;
                })
                ->editColumn('email', function ($email) {
                    if ($email->email == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $email->email;
                })
                ->editColumn('hp', function ($hp) {
                    if ($hp->hp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $hp->hp;
                })
                ->rawColumns(['action','ktp','email','hp'])
                ->make(true);
        }
    }

    public function keuangan(Request $request){
        if($request->ajax()){
            $data = User::where('role','keuangan')->orderBy('nama','asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Reset" name="reset" id="'.$data->id.'" class="reset"><i class="fas fa-key fa-sm" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit fa-sm" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('ktp', function ($ktp) {
                    if ($ktp->ktp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $ktp->ktp;
                })
                ->editColumn('email', function ($email) {
                    if ($email->email == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $email->email;
                })
                ->editColumn('hp', function ($hp) {
                    if ($hp->hp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $hp->hp;
                })
                ->rawColumns(['action','ktp','email','hp'])
                ->make(true);
        }
    }

    public function kasir(Request $request){
        if($request->ajax()){
            $data = User::where('role','kasir')->orderBy('nama','asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Reset" name="reset" id="'.$data->id.'" class="reset"><i class="fas fa-key fa-sm" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit fa-sm" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('ktp', function ($ktp) {
                    if ($ktp->ktp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $ktp->ktp;
                })
                ->editColumn('email', function ($email) {
                    if ($email->email == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $email->email;
                })
                ->editColumn('hp', function ($hp) {
                    if ($hp->hp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $hp->hp;
                })
                ->rawColumns(['action','ktp','email','hp'])
                ->make(true);
        }
    }

    public function nasabah(Request $request){
        if($request->ajax()){
            $data = User::where('role','nasabah')->orderBy('nama','asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<a type="button" title="Reset" name="reset" id="'.$data->id.'" class="reset"><i class="fas fa-key fa-sm" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('ktp', function ($ktp) {
                    if ($ktp->ktp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $ktp->ktp;
                })
                ->editColumn('email', function ($email) {
                    if ($email->email == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $email->email;
                })
                ->editColumn('hp', function ($hp) {
                    if ($hp->hp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return $hp->hp;
                })
                ->rawColumns(['action','ktp','email','hp'])
                ->make(true);
        }
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
        if(request()->ajax())
        {
            $data = User::findOrFail($id);
            if($data->email != NULL){
                $email = substr($data->email, 0, strpos($data->email, '@'));
                $data['email'] = $email;
            }
            if($data->hp != NULL){
                $hp = substr($data->hp,2);
                $data['hp'] = $hp;
            }

            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
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
        $data = User::findOrFail($id);
        $dataset = array();
        try{
            $role = $data->role;
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
    
    /**
     * Reset the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request,$id)
    {
        if($request->ajax()){
            try{
                $pass = str_shuffle('abcdefghjkmnpqrstuvwxyz123456789');
                $pass = substr($pass,0,7);

                $password = md5(hash('gost',$pass));

                User::findOrFail($id)->update([
                    'password' => $password
                ]);
                return response()->json(['success' => $pass]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Something wrong']);
            }
        }
    }

    /**
     * Show the form for editing authority the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function etoritas($id)
    {
        if(request()->ajax())
        {
            $data = User::findOrFail($id);

            if($data->otoritas == NULL){
                $data['blok'] = $data->otoritas;
            }
            else{
                $otoritas  = json_decode($data->otoritas);
                $data['bloks'] = $otoritas[0]->blok;
                $data['pedagang'] = $otoritas[1]->pedagang;
                $data['tempatusaha'] = $otoritas[2]->tempatusaha;
                $data['tagihan'] = $otoritas[3]->tagihan;
                $data['blok'] = $otoritas[4]->blok;
                $data['pemakaian'] = $otoritas[5]->pemakaian;
                $data['pendapatan'] = $otoritas[6]->pendapatan;
                $data['datausaha'] = $otoritas[7]->datausaha;
                $data['alatmeter'] = $otoritas[8]->alatmeter;
                $data['tarif'] = $otoritas[9]->tarif;
                $data['harilibur'] = $otoritas[10]->harilibur;
            }

            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update Authority the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function otoritas(Request $request)
    {
        $pilihanKelola = array('pedagang','tempatusaha','tagihan','blok','pemakaian','pendapatan','datausaha','alatmeter','tarif','harilibur');

        $kelola[] = ['blok' => $request->blokOtoritas];

        try{
            if($request->blokOtoritas != NULL){
                for($i=0; $i<count($pilihanKelola); $i++){
                    if(in_array($pilihanKelola[$i],$request->kelola)){
                        $kelola[] = [$pilihanKelola[$i] => true];
                    }
                    else{
                        $kelola[] = [$pilihanKelola[$i] => false];
                    }
                }
        
                $json = json_encode($kelola);
            }
            else{
                $json = NULL;
            }
            $data = User::find($request->hidden_id_otoritas);
            $data->otoritas = $json;
            $data->save();
            return response()->json(['success' => 'Otoritas Berhasil Diupdate.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Otoritas Gagal Diupdate.']);
        }
    }
}
