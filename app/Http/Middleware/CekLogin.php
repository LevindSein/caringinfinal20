<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\User;
use App\Models\LoginLog;
use App\Models\Sinkronisasi;
use App\Models\Tagihan;
use Jenssegers\Agent\Agent;

class CekLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $page)
    {
        if($page == 'home'){
            $pass = md5(hash('gost',$request->password));
            $user = User::where([['username', $request->username],['password',$pass]])->first();
            try{
                Session::put('userId',$user->id);
                Session::put('username',$user->nama);
                Session::put('role',$user->role);
                Session::put('login',$user->username.'-'.$user->role);

                if(LoginLog::count() > 10000){
                    LoginLog::orderBy('id','asc')->limit(1000)->delete();
                }

                $agent = new Agent();
                $loginLog = new LoginLog;
                $loginLog->username = $user->username;
                $loginLog->nama = $user->nama;
                $loginLog->ktp = $user->ktp;
                $loginLog->hp = $user->hp;
                $loginLog->role = $user->role;
                $loginLog->platform = $agent->platform()." ".$agent->version($agent->platform())." ".$agent->browser()." ".$agent->version($agent->browser());
                $loginLog->save();

                if($user->role === 'master' || $user->role === 'manajer') {
                    return redirect()->route('dashboard')->with('success','Selamat Datang');
                }
                
                if($user->role === 'kasir') {
                    Session::put('mode','bulanan');
                    return redirect()->route('kasir.index');
                }

                if($user->role === 'admin') {
                    if($user->otoritas != NULL){
                        Session::put('otoritas',json_decode($user->otoritas));
                    }
                    return redirect()->route('dashboard')->with('success','Selamat Datang');
                }
            }catch(\Exception $e){
                return redirect()->route('login')->with('error','Username atau Password Salah');
            }
        }

        if(Session::get('login') != NULL){
            if($page == 'dashboard'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','manajer','admin');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        return $next($request);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'kasir'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('kasir');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        return $next($request);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'pedagang'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[1]->pedagang)
                            return $next($request);
                        else if(Session::get('role') == 'master' || Session::get('role') == 'manajer')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'tempatusaha'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin','manajer');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[2]->tempatusaha)
                            return $next($request);
                        else if(Session::get('role') == 'master' || Session::get('role') == 'manajer')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'tagihan'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin');
                if($validator != NULL){
                    $date = date('Y-m-d',time());
                    $check = date('Y-m-25',time());
                    $time = strtotime($date);
                    $nanti = date('Y-m-01', strtotime("+1 month", $time));
                    $sekarang = date('Y-m-01',time());
                    if($date < $check){
                        $sync = Sinkronisasi::where('sinkron',$sekarang)->first();
                        if($sync == NULL){
                            Session::put('sync',$sekarang);
                        }
                        else{
                            Session::put('sync','off');
                        }
                    }
                    if($date >= $check){
                        $sync = Sinkronisasi::where('sinkron',$nanti)->first();
                        if($sync == NULL){
                            Session::put('sync',$nanti);
                        }
                        else{
                            Session::put('sync','off');
                        }
                    }

                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[3]->tagihan)
                            return $next($request);
                        else if(Session::get('role') == 'master')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'pemakaian'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin','manajer');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[5]->pemakaian)
                            return $next($request);
                        else if(Session::get('role') == 'master' || Session::get('role') == 'manajer')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'pendapatan'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin','manajer');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[6]->pendapatan)
                            return $next($request);
                        else if(Session::get('role') == 'master' || Session::get('role') == 'manajer')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'datausaha'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin','manajer');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[7]->datausaha)
                            return $next($request);
                        else if(Session::get('role') == 'master' || Session::get('role') == 'manajer')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'tarif'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[9]->tarif)
                            return $next($request);
                        else if(Session::get('role') == 'master')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'alatmeter'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[8]->alatmeter)
                            return $next($request);
                        else if(Session::get('role') == 'master')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'harilibur'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[10]->harilibur)
                            return $next($request);
                        else if(Session::get('role') == 'master')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'blok'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master','admin');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        if(Session::get('role') == 'admin' && Session::get('otoritas')[4]->blok)
                            return $next($request);
                        else if(Session::get('role') == 'master')
                            return $next($request);
                        else
                            abort(403);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'user'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        return $next($request);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }

            if($page == 'log'){
                $explode = explode('-',Session::get('login'));
                $validator = User::where([['username',$explode[0]],['role',$explode[1]]])->first();
                $roles = array('master');
                if($validator != NULL){
                    if(in_array($explode[1],$roles)){
                        return $next($request);
                    }
                    else{
                        abort(403);
                    }
                }
                else{
                    Session::flush();
                    return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
                }
            }
        }
        else{
            Session::flush();
            return redirect()->route('login')->with('info','Silahkan Login Terlebih Dahulu');
        }
    }
}
