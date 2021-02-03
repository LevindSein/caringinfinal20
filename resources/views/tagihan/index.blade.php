@extends('layout.master')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

<?php 
    use Carbon\Carbon;
    $time = Carbon::now();
    $now = date('Y-m-d',strtotime($time));
    $check = date('Y-m-20',strtotime($time));
?>

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Tagihan Pedagang | BP3C</title>
<span id="form_result"></span>
<div id="process" style="display:none;text-align:center;">
    <p>Please Wait, Updating <img src="{{asset('img/updating.gif')}}"/></p>
</div>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tagihan Periode {{$periode}}</h6><input type="hidden" id="periode" value="{{$periode}}"/>
            <div>
                @if(Session::get('role') == 'master' || Session::get('otoritas')->tagihan)
                @if($notif == 1)
                <a
                    id="notification"
                    href="{{url('tagihan/notification')}}"
                    type="submit"><b>
                    <i class="fas fa-fw fa-bell text-danger faa-ring animated"></i></b>
                </a>
                &nbsp;
                @endif
                @endif
                <a
                    href="{{url('tagihan')}}"
                    type="submit"
                    class="btn btn-sm btn-primary"><b>
                    <i class="fas fa-fw fa-home fa-sm text-white"></i></b>
                </a>
                &nbsp;
                @if(Session::get('role') == 'master' || Session::get('otoritas')->tagihan)
                <button 
                    type="button"
                    name="add_listrik"
                    id="add_listrik" 
                    class="btn btn-sm btn-warning"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Listrik </b> <span class="badge badge-pill badge-light">{{$listrik_badge}}</span>
                </button>
                &nbsp;
                <button 
                    type="button"
                    name="add_air"
                    id="add_air" 
                    class="btn btn-sm btn-info"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Air Bersih </b> <span class="badge badge-pill badge-light">{{$air_badge}}</span>
                </button>
                &nbsp;
                @endif
                <div class="dropdown no-arrow" style="display:inline-block">
                    <a 
                        class="dropdown-toggle btn btn-sm btn-success" 
                        href="#" 
                        role="button" 
                        data-toggle="dropdown"
                        aria-haspopup="true" 
                        aria-expanded="false"><b>
                        Menu
                        <i class="fas fa-ellipsis-v fa-sm fa-fw"></i></b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Tagihan:</div>
                        @if(Session::get('role') == 'master' || Session::get('otoritas')->publish)
                        <button
                            class="dropdown-item" 
                            id="publish"
                            type="submit">
                            <i class="fas fa-fw fa-paper-plane fa-sm text-gray-500"></i> Publish
                        </button>
                        @endif
                        @if(Session::get('role') == 'master' || Session::get('otoritas')->tagihan)
                        @if($now >= $check)
                        <button
                            class="dropdown-item" 
                            id="sinkronisasi"
                            type="submit">
                            <i class="fas fa-fw fa-sync fa-sm text-gray-500"></i> Sinkronisasi
                        </button>
                        @endif
                        <button 
                            id="tambah_manual"
                            class="dropdown-item" 
                            href="#" 
                            data-toggle="modal" 
                            data-target="#myManual"
                            type="submit">
                            <i class="fas fa-fw fa-plus fa-sm text-gray-500"></i> Manual
                        </button>
                        @endif
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-header">Periode:</div>
                        <a 
                            class="dropdown-item" 
                            href="#"
                            data-toggle="modal" 
                            data-target="#myModal" 
                            type="submit">
                            <i class="fas fa-fw fa-search fa-sm text-gray-500"></i> Cari Tagihan
                        </a>
                        @if(Session::get('role') == 'master' || Session::get('otoritas')->tagihan)
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-header">Edaran:</div>
                        <a 
                            class="dropdown-item"
                            href="{{url('tagihan/print')}}"
                            type="submit" 
                            target="_blank">
                            <i class="far fa-fw fa-file fa-sm text-gray-500"></i> Form Pendataan
                        </a>
                        <a 
                            class="dropdown-item" 
                            href="#" 
                            data-toggle="modal" 
                            data-target="#myPemberitahuan" 
                            type="submit">
                            <i class="fas fa-fw fa-exclamation fa-sm text-gray-500"></i> Pemberitahuan
                        </a>
                        <!-- <a 
                            class="dropdown-item" 
                            href="#" 
                            data-toggle="modal" 
                            data-target="#myPembayaran"
                            type="submit">
                            <i class="fas fa-fw fa-dollar-sign fa-sm text-gray-500"></i> Pembayaran
                        </a> -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive ">
                @if(Session::get('otoritas') != NULL && (Session::get('otoritas')->publish && Session::get('otoritas')->tagihan == false))
                <table
                    class="table"
                    id="tabelTagihan1"
                    width="100%"
                    cellspacing="0"
                    style="font-size:0.75rem;">
                    <thead class="table-bordered">
                        <tr>
                            <th rowspan="2">Kontrol</th>
                            <th rowspan="2">Pengguna</th>
                            <th colspan="5" class="listrik">Listrik</th>
                            <th colspan="4" class="air">Air</th>
                            <th rowspan="2" class="keamanan">Keamanan IPK (Rp.)</th>
                            <th rowspan="2" class="kebersihan">Kebersihan (Rp.)</th>
                            <th rowspan="2" style="background-color:rgba(50, 255, 255, 0.2);">Air Kotor (Rp.)</th>
                            <th rowspan="2" style="background-color:rgba(255, 50, 255, 0.2);">Lain - Lain (Rp.)</th>
                            <th rowspan="2">Via Publish</th>
                            <th rowspan="2" style="background-color:rgba(255, 212, 71, 0.2);">Jumlah (Rp.)</th>
                        </tr>
                        <tr>
                            <th class="listrik-hover">Daya</th>
                            <th class="listrik-hover">Lalu</th>
                            <th class="listrik-hover">Baru</th>
                            <th class="listrik-hover">Pakai</th>
                            <th class="listrik-hover">Total (Rp.)</th>
                            <th class="air-hover">Lalu</th>
                            <th class="air-hover">Baru</th>
                            <th class="air-hover">Pakai</th>
                            <th class="air-hover">Total (Rp.)</th>
                        </tr>
                    </thead>
                </table>
                @else
                <table
                    class="table"
                    id="tabelTagihan"
                    width="100%"
                    cellspacing="0"
                    style="font-size:0.75rem;">
                    <thead class="table-bordered">
                        <tr>
                            <th rowspan="2">Kontrol</th>
                            <th rowspan="2">Pengguna</th>
                            <th colspan="5" class="listrik">Listrik</th>
                            <th colspan="4" class="air">Air</th>
                            <th rowspan="2" class="keamanan">Keamanan IPK (Rp.)</th>
                            <th rowspan="2" class="kebersihan">Kebersihan (Rp.)</th>
                            <th rowspan="2" style="background-color:rgba(50, 255, 255, 0.2);">Air Kotor (Rp.)</th>
                            <th rowspan="2" style="background-color:rgba(255, 50, 255, 0.2);">Lain - Lain (Rp.)</th>
                            <th rowspan="2">Via Publish</th>
                            <th rowspan="2" style="background-color:rgba(255, 212, 71, 0.2);">Jumlah (Rp.)</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr>
                            <th class="listrik-hover">Daya</th>
                            <th class="listrik-hover">Lalu</th>
                            <th class="listrik-hover">Baru</th>
                            <th class="listrik-hover">Pakai</th>
                            <th class="listrik-hover">Total (Rp.)</th>
                            <th class="air-hover">Lalu</th>
                            <th class="air-hover">Baru</th>
                            <th class="air-hover">Pakai</th>
                            <th class="air-hover">Total (Rp.)</th>
                        </tr>
                    </thead>
                </table>
                @endif
            </div>
        </div>
    </div>    
</div>
@endsection

@section('modal')
<!-- Tambah Content pada Body modal -->
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Hapus Data Tagihan ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <form id="form_destroy">
            @csrf
                <div class="modal-body-short">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data tagihan.<br><br>
                    <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap:wrap;">
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkListrik"
                                    id="checkListrik"
                                    value="listrik">
                                <label for="checkListrik">
                                    Listrik
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkAirBersih"
                                    id="checkAirBersih"
                                    value="airbersih">
                                <label for="checkAirBersih">
                                    Air Bersih
                                </label>
                            </div>
                        </div>
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkKeamananIpk"
                                    id="checkKeamananIpk"
                                    value="keamananipk">
                                <label for="checkKeamananIpk">
                                    Keamanan IPK
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkKebersihan"
                                    id="checkKebersihan"
                                    value="kebersihan">
                                <label for="checkKebersihan">
                                    Kebersihan
                                </label>
                            </div>
                        </div>
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkAirKotor"
                                    id="checkAirKotor"
                                    value="airkotor">
                                <label for="checkAirKotor">
                                    Air Kotor
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkLain"
                                    id="checkLain"
                                    value="lain">
                                <label for="checkLain">
                                    Lain Lain
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="ok_button" id="ok_button" class="btn btn-danger" value="Hapus" />
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cari Periode Tagihan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('tagihan/periode')}}" method="GET">
                <div class="modal-body-short">
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <select class="form-control" name="bulan" id="bulan" required>
                            <option selected hidden value="">Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <select class="form-control" name="tahun" id="tahun" required>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary btn-sm" value="Submit" />
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myPemberitahuan"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Print Pemberitahuan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="cetakPemberitahuan">
                <div class="modal-body-short">
                    <div class="form-group">
                        <label for="blok">Pilih Blok</label>
                        <select class="form-control" name="blokPemberitahuan" id="blokPemberitahuan" required>
                            <option selected hidden value="">Pilih Blok</option>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak Pemberitahuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myPembayaran"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Print Pembayaran</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="cetakPembayaran">
                <div class="modal-body-short">
                    <div class="form-group">
                        <label for="blok">Pilih Blok</label>
                        <select class="form-control" name="blokPembayaran" id="blokPembayaran" required>
                            <option selected hidden value="">Pilih Blok</option>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="tagihanku"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Tagihan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_tagihanku" action="" method="GET">
                <div class="modal-body-short">
                    <div class="form-group">
                        <label for="blok">Pilih Blok</label>
                        <select class="form-control" name="tagihan_blok" id="tagihan_blok" required>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myTagihan"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_tagihan">Edit Tagihan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" id="form_tagihan" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group col-lg-12">
                            <label for="kontrol">Kontrol</label>
                            <input
                                readonly
                                required
                                name="kontrol"
                                class="form-control"
                                id="kontrol">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="pengguna">Pengguna </label>
                            <input
                                required
                                name="pengguna"
                                class="form-control"
                                id="pengguna">
                        </div>
                        <hr>

                        <div id="divEditListrik">
                            <input type="hidden" name="stt_listrik" id="stt_listrik" val="" />
                            <div class="form-group col-lg-12">
                                <label for="dayaListrik">Daya Listrik <span style="color:red;">*</span></label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="dayaListrik"
                                    class="form-control"
                                    id="dayaListrik">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="awalListrik">Stand Awal Listrik <span style="color:red;">*</span></label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="awalListrik"
                                    class="form-control"
                                    id="awalListrik">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="akhirListrik">Stand Akhir Listrik <span style="color:red;">*</span></label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="akhirListrik"
                                    class="form-control"
                                    id="akhirListrik">
                            </div>
                            <hr>
                        </div>
                        
                        <div id="divEditAirBersih">
                            <input type="hidden" name="stt_airbersih" id="stt_airbersih" val="" />
                            <div class="form-group col-lg-12">
                                <label for="awalAir">Stand Awal Air <span style="color:red;">*</span></label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="awalAir"
                                    class="form-control"
                                    id="awalAir">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="akhirAir">Stand Akhir Air <span style="color:red;">*</span></label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="akhirAir"
                                    class="form-control"
                                    id="akhirAir">
                            </div>
                            <hr>
                        </div>

                        <div id="divEditKeamananIpk">
                            <input type="hidden" name="stt_keamananipk" id="stt_keamananipk" val="" />
                            <div class="form-group col-lg-12">
                                <label for="keamananipk">Keamanan & IPK <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="keamananipk"
                                        class="form-control"
                                        id="keamananipk"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <div class="form-group col-lg-10">
                                <label for="dis_keamananipk">Diskon Keamanan & IPK</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="dis_keamananipk"
                                        class="form-control"
                                        id="dis_keamananipk"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>
                        
                        <div id="divEditKebersihan">
                            <input type="hidden" name="stt_kebersihan" id="stt_kebersihan" val="" />
                            <div class="form-group col-lg-12">
                                <label for="kebersihan">Kebersihan <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="kebersihan"
                                        class="form-control"
                                        id="kebersihan"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <div class="form-group col-lg-10">
                                <label for="dis_kebersihan">Diskon Kebersihan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="dis_kebersihan"
                                        class="form-control"
                                        id="dis_kebersihan"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>
                        
                        <div id="divEditAirKotor">
                            <input type="hidden" name="stt_airkotor" id="stt_airkotor" val="" />
                            <div class="form-group col-lg-12">
                                <label for="airkotor">Air Kotor <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="airkotor"
                                        class="form-control"
                                        id="airkotor"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>
                        
                        <div id="divEditLain">
                            <input type="hidden" name="stt_lain" id="stt_lain" val="" />
                            <div class="form-group col-lg-12">
                                <label for="lain">Lain - Lain <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="lain"
                                        class="form-control"
                                        id="lain"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="pesan">Pesan</label>
                            <textarea autocomplete="off" name="pesan" class="form-control" id="pesan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" class="btn btn-primary btn-sm" name="action_btn" id="action_btn" value="Edit" />
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myManual"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_tagihan">Tambah Tagihan Manual</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" id="form_manual" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div id="divkontrolmanual">
                            <div class="form-group col-lg-12">
                                <label for="kontrol">Kontrol</label>
                                <div class="form-group">
                                    <select class="kontrol_manual form-control" name="kontrol_manual" id="kontrol_manual" required></select>
                                </div>
                            </div>
                        </div>
                        <div id="divresultkontrolmanual">
                            <div class="col-lg-12 justify-content-center" style="display:flex;flex-wrap: wrap;">
                                <div>
                                    <h5><strong><span id="resultkontrolmanual" style="color:#3f6ad8;"></span></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="pengguna_manual">Pengguna </label>
                            <div class="form-group">
                                <select class="pengguna_manual form-control" name="pengguna_manual" id="pengguna_manual" required></select>
                            </div>
                        </div>
                        <hr>
                        <div id="manlistrik">
                            <div class="form-group col-lg-12">
                                <label for="dayaListrik_manual">Daya Listrik</label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="dayaListrik_manual"
                                    class="form-control"
                                    id="dayaListrik_manual">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="awalListrik_manual">Stand Awal Listrik</label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="awalListrik_manual"
                                    class="form-control"
                                    id="awalListrik_manual">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="akhirListrik_manual">Stand Akhir Listrik</label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="akhirListrik_manual"
                                    class="form-control"
                                    id="akhirListrik_manual">
                            </div>
                            <hr>
                        </div>
                        <div id="manairbersih"> 
                            <div class="form-group col-lg-12">
                                <label for="awalAir">Stand Awal Air</label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="awalAir_manual"
                                    class="form-control"
                                    id="awalAir_manual">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="akhirAir">Stand Akhir Air</label>
                                <input
                                    autocomplete="off"
                                    type="text" 
                                    pattern="^[\d,]+$"
                                    name="akhirAir_manual"
                                    class="form-control"
                                    id="akhirAir_manual">
                            </div>
                            <hr>
                        </div>
                        <div id="mankeamananipk">
                            <div class="form-group col-lg-12">
                                <label for="keamananipk_manual">Keamanan & IPK</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="keamananipk_manual"
                                        class="form-control"
                                        id="keamananipk_manual"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <div class="form-group col-lg-10">
                                <label for="dis_keamananipk_manual">Diskon Keamanan & IPK</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="dis_keamananipk_manual"
                                        class="form-control"
                                        id="dis_keamananipk_manual"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div id="mankebersihan">
                            <div class="form-group col-lg-12">
                                <label for="kebersihan_manual">Kebersihan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="kebersihan_manual"
                                        class="form-control"
                                        id="kebersihan_manual"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <div class="form-group col-lg-10">
                                <label for="dis_kebersihan_manual">Diskon Kebersihan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="dis_kebersihan_manual"
                                        class="form-control"
                                        id="dis_kebersihan_manual"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div id="manairkotor">
                            <div class="form-group col-lg-12">
                                <label for="airkotor_manual">Air Kotor</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="airkotor_manual"
                                        class="form-control"
                                        id="airkotor_manual"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div id="manlain">
                            <div class="form-group col-lg-12">
                                <label for="lain_manual">Lain - Lain</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input
                                        autocomplete="off"
                                        type="text" 
                                        pattern="^[\d,]+$"
                                        name="lain_manual"
                                        class="form-control"
                                        id="lain_manual"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="stt_listrik_manual" name="stt_listrik_manual" value="" />
                    <input type="hidden" id="stt_airbersih_manual" name="stt_airbersih_manual" value="" />
                    <input type="hidden" id="stt_keamananipk_manual" name="stt_keamananipk_manual" value="" />
                    <input type="hidden" id="stt_kebersihan_manual" name="stt_kebersihan_manual" value="" />
                    <input type="hidden" id="stt_airkotor_manual" name="stt_airkotor_manual" value="" />
                    <input type="hidden" id="stt_lain_manual" name="stt_lain_manual" value="" />
                    <input type="submit" id="action_btn_manual" class="btn btn-primary btn-sm" value="Tambah Tagihan" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('js')
<!-- Tambah Content pada Body JS -->
<script src="{{asset('js/tagihan.js')}}"></script>
@endsection