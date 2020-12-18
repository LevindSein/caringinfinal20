@extends('layout.master')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Data Pedagang | BP3C</title>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data Pedagang</h6>
            <div>
                <button 
                    type="button"
                    name="add_pedagang"
                    id="add_pedagang" 
                    class="btn btn-sm btn-success"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Pedagang</b></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive ">
                <table
                    class="table"
                    id="tabelPedagang"
                    width="100%"
                    cellspacing="0"
                    style="font-size:0.75rem;">
                    <thead class="table-bordered">
                        <tr>
                            <th style="max-width:10px;">No</th>
                            <th>Nama</th>
                            <th>No. Anggota</th>
                            <th>No. KTP</th>
                            <th>Email</th>
                            <th>No. Hp</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>    
</div>
@endsection

@section('modal')
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Apakah yakin hapus data Pedagang?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <div class="modal-body-short">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data pedagang.</div>
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
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="form_result"></span>
            <form id="form_pedagang" class="user" method="POST">
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
                        <label for="nama">Nama Pedagang <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="text"
                            style="text-transform: capitalize;"
                            name="nama"
                            class="form-control"
                            id="nama"
                            maxlength="30"
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
                    <div class="form-group col-lg-12">
                        <label for="anggota">Nomor Anggota</label>
                        <input
                            required
                            readonly
                            type="text"
                            style="text-transform: uppercase;"
                            class="form-control"
                            name="anggota"
                            id="anggota">
                    </div>
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-2">Status</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="pemilik"
                                    id="pemilik"
                                    value="pemilik"
                                    data-related-item="divPemilik">
                                <label class="form-check-label" for="pemilik">
                                    Pemilik
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayPemilik">
                                <label for="alamatPemilik" id="divPemilik">Alamat <span style="color:red;">*</span></label>
                                <div class="form-group">
                                    <select style="width:100%" class="alamatPemilik" name="alamatPemilik[]" id="alamatPemilik" multiple></select>
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="pengguna"
                                    id="pengguna"
                                    value="pengguna"
                                    data-related-item="divPengguna">
                                <label class="form-check-label" for="pengguna">
                                    Pengguna
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayPengguna">
                                <label for="alamatPengguna"  id="divPengguna">Alamat <span style="color:red;">*</span></label>
                                <div class="form-group">
                                    <select style="width:100%" class="alamatPengguna" name="alamatPengguna[]" id="alamatPengguna" multiple required></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <input type="text" autocomplete="off" class="form-control" maxlength="20" name="email" id="email" placeholder="youremail" aria-describedby="inputGroupPrepend">
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
                            <input required type="tel" autocomplete="off" class="form-control" maxlength="12" name="hp" id="hp" placeholder="8783847xxx" aria-describedby="inputGroupPrepend">
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
@endsection

@section('js')
<script src="{{asset('js/pedagang.js')}}"></script>
@endsection