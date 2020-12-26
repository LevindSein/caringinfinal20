@extends(Session::get('role') == 'master' ? 'layout.master' : 'layout.admin')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

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
                @if(Session::get('sync') != 'off')
                <button 
                    type="submit"
                    title="Sinkronisasi"
                    class="sync btn btn-sm btn-danger"><b>
                    <i class="fas fa-fw fa-sync-alt fa-sm text-white-50"></i> Tagihan</b></span>
                </button>
                &nbsp;
                @else
                <a
                    href="{{url('tagihan/listrik')}}"
                    type="submit"
                    class="btn btn-sm btn-warning"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Listrik </b> <span class="badge badge-pill badge-light">{{$listrik_badge}}</span>
                </a>
                &nbsp;
                <a
                    href="{{url('tagihan/airbersih')}}"
                    type="submit"
                    class="btn btn-sm btn-info"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Air Bersih </b> <span class="badge badge-pill badge-light">{{$air_badge}}</span>
                </a>
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
                        <div class="dropdown-header">Preview Tagihan:</div>
                        <button
                            class="dropdown-item" 
                            id="publish"
                            type="submit">
                            <i class="fas fa-fw fa-paper-plane fa-sm text-gray-500"></i> Publish
                        </button>
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
                        <a 
                            class="dropdown-item" 
                            href="#" 
                            data-toggle="modal" 
                            data-target="#myPembayaran"
                            type="submit">
                            <i class="fas fa-fw fa-dollar-sign fa-sm text-gray-500"></i> Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive ">
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
                <h5>Apakah yakin hapus data tagihan?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <div class="modal-body-short">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data tagihan.</div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
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
            <form class="user" method="GET">
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
            <form>
                <div class="modal-body-short">
                    <div class="form-group">
                        <label for="blok">Pilih Blok</label>
                        <select class="form-control" name="blokPemberitahuan" id="blokPemberitahuan" required>
                            <option selected="selected" hidden="hidden"  value="">Pilih Blok</option>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="cetakPemberitahuan" class="btn btn-primary btn-sm">Cetak Pemberitahuan</button>
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
            <form>
                <div class="modal-body-short">
                    <div class="form-group">
                        <label for="blok">Pilih Blok</label>
                        <select class="form-control" name="blokPembayaran" id="blokPembayaran" required>
                            <option selected="selected" hidden="hidden"  value="">Pilih Blok</option>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="cetakPembayaran" class="btn btn-primary btn-sm">Cetak Pembayaran</button>
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
                                readonly
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
                            <hr>
                        </div>
                        
                        <div id="divEditLain">
                            <input type="hidden" name="stt_lain" id="stt_lain" val="" />
                            <div class="form-group col-lg-12">
                                <label for="lain">Lain - Lain <span style="color:red;">*</span></label>
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
                            <hr>
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
@endsection


@section('js')
<!-- Tambah Content pada Body JS -->
<script src="{{asset('js/tagihan.js')}}"></script>
@endsection