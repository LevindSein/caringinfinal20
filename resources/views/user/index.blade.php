@extends('layout.master')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Data User | BP3C</title>
<div class = "container-fluid">
    <ul class="tabs-animated-shadow tabs-animated nav">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0">
                <span>Admin</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-1" data-toggle="tab" href="#tab-animated-1">
                <span>Manajer</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-2" data-toggle="tab" href="#tab-animated-2">
                <span>Keuangan</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-3" data-toggle="tab" href="#tab-animated-3">
                <span>Kasir</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-4" data-toggle="tab" href="#tab-animated-4">
                <span>Nasabah</span>
            </a>
        </li>
    </ul>
</div>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
            <div>
                <button 
                    type="button"
                    name="add_user"
                    id="add_user" 
                    class="btn btn-sm btn-success"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> User</b></button>
            </div>
        </div>
        <div class="card-body">
            
            <div class="tab-content">
                <div class="tab-pane active" id="tab-animated-0" role="tabpanel">
                    @include('user.admin')
                </div>
                <div class="tab-pane" id="tab-animated-1" role="tabpanel">
                    @include('user.manajer')
                </div>
                <div class="tab-pane" id="tab-animated-2" role="tabpanel">
                    @include('user.keuangan')
                </div>
                <div class="tab-pane" id="tab-animated-3" role="tabpanel">
                    @include('user.kasir')
                </div>
                <div class="tab-pane" id="tab-animated-4" role="tabpanel">
                    @include('user.nasabah')
                </div>
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
                <h5>Apakah yakin hapus data user?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <div class="modal-body-short">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data user.</div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div id="resetModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Password Anda</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body-short">
                <div class="text-center">
                    <h5><b><span id="password_baru">Password Baru</span></b></h5>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" data-dismiss="modal" class="btn btn-success">OK</button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myModal"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="form_result"></span>
            <form class="user" id="form_user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label for="ktp">Nomor KTP <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="tel"
                            min="0"
                            name="ktp"
                            maxlength="17"
                            class="form-control"
                            id="ktp"
                            placeholder="321xxxxx">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="nomor">Nama  <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            name="nama"
                            class="form-control"
                            style="text-transform: capitalize;"
                            id="nama"
                            placeholder="Nama Sesuai KTP">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="username">Username</label>
                        <input
                            required
                            readonly
                            type="text"
                            style="text-transform: lowercase;"
                            class="form-control"
                            name="username"
                            id="username">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="password">Password</label>
                        <div>
                            <input
                                readonly
                                type="password"
                                class="form-control"
                                name="password"
                                id="password">
                            <span 
                                toggle="#password" 
                                style="float:right;margin-right:10px;margin-top:-25px;position:relative;z-index:2;"
                                class="fa fa-fw fa-eye toggle-password"></span>
                        </div>
                    </div>
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-2">Role</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleAdmin"
                                    value="admin"
                                    checked>
                                <label class="form-check-label" for="roleAdmin">
                                    Admin
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleManajer"
                                    value="manajer">
                                <label class="form-check-label" for="roleManajer">
                                    Manajer
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleKeuangan"
                                    value="keuangan">
                                <label class="form-check-label" for="roleKeuangan">
                                    Keuangan
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleKasir"
                                    value="kasir">
                                <label class="form-check-label" for="roleKasir">
                                    Kasir
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <input
                                autocomplete="off" 
                                type="text" 
                                class="form-control" 
                                maxlength="20" 
                                name="email" 
                                id="email" 
                                placeholder="youremail" 
                                aria-describedby="inputGroupPrepend">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">@gmail.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="hp">No. Handphone <span style="color:red;">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">+62</span>
                            </div>
                            <input 
                                required 
                                autocomplete="off"
                                type="tel" 
                                class="form-control" 
                                maxlength="12" 
                                name="hp" 
                                id="hp" 
                                placeholder="8783847xxx" 
                                aria-describedby="inputGroupPrepend">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" class="btn btn-primary btn-sm" name="action_btn" id="action_btn" value="Tambah" />
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myOtoritas"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Otoritas Admin</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="result_otoritas"></span>
            <form class="user" id="form_otoritas" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label for="username">Username</label>
                        <input
                            readonly
                            name="username_otoritas"
                            class="form-control"
                            id="username_otoritas">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="nama">Nama</label>
                        <input
                            readonly
                            name="nama_otoritas"
                            class="form-control"
                            id="nama_otoritas">
                    </div>
                    <hr class="sidebar-divider d-none d-md-block col-lg-10 text-center">
                    <div class="form-group col-lg-12">
                        <div class="form-group">
                            <label for="blokOtoritas">Blok <span style="color:red;">*</span></label>
                            <div class="form-group">
                                <select style="width:100%" class="blokOtoritas" name="blokOtoritas[]" id="blokOtoritas" multiple></select>
                            </div>
                        </div>
                    </div>
                    <div class="text-center form-group">
                        <strong>Pilih Pengelolaan :</strong>
                    </div>
                    <div class="form-group col-lg-12 justify-content-between" style="display: flex;flex-wrap: wrap;">
                        <div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="pedagang"
                                    value="pedagang">
                                <label class="form-check-label" for="pedagang">
                                    Pedagang
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="tempatusaha"
                                    value="tempatusaha">
                                <label class="form-check-label" for="tempatusaha">
                                    Tempat Usaha
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="tagihan"
                                    value="tagihan">
                                <label class="form-check-label" for="tagihan">
                                    Tagihan
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="blok"
                                    value="blok">
                                <label class="form-check-label" for="blok">
                                    Blok
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="pemakaian"
                                    value="pemakaian">
                                <label class="form-check-label" for="pemakaian">
                                    Pemakaian
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="pendapatan"
                                    value="pendapatan">
                                <label class="form-check-label" for="pendapatan">
                                    Pendapatan
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="datausaha"
                                    value="datausaha">
                                <label class="form-check-label" for="datausaha">
                                    Data Usaha
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="alatmeter"
                                    value="alatmeter">
                                <label class="form-check-label" for="alatmeter">
                                    Alat Meter
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="tarif"
                                    value="tarif">
                                <label class="form-check-label" for="tarif">
                                    Tarif Fasilitas
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="harilibur"
                                    value="harilibur">
                                <label class="form-check-label" for="harilibur">
                                    Hari Libur
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id_otoritas" id="hidden_id_otoritas" />
                    <input type="submit" class="btn btn-primary btn-sm" name="action_btn_otoritas" id="action_btn_otoritas" value="Update" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Tambah Content pada Body JS -->
<script src="{{asset('js/user.js')}}"></script>
@endsection