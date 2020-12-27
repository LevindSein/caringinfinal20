@extends('layout.master')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Data Blok | BP3C</title>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data Blok</h6>
            <div>
                <button 
                    type="button"
                    name="add_blok"
                    id="add_blok" 
                    class="btn btn-sm btn-success"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Blok</b></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive ">
                <table
                    class="table"
                    id="tabelBlok"
                    width="100%"
                    cellspacing="0"
                    style="font-size:0.75rem;">
                    <thead class="table-bordered">
                        <tr>
                            <th style="width:10px;">No.</th>
                            <th>Blok</th>
                            <th>Jumlah</th>
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
<!-- Tambah Content pada Body modal -->
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Apakah yakin hapus data blok?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <div class="modal-body-short">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data blok.</div>
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
    tabIndex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Blok</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="form_result"></span>
            <form class="user" id="form_blok" method="POST">
                <div class="modal-body-short">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label for="blokInput">Blok <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="text"
                            name="blokInput"
                            id="blokInput"
                            maxlength="8"
                            style="text-transform:uppercase;"
                            class="form-control"
                            placeholder="EX : A-10">
                    </div>
                    <!-- <div class="keamananipk-persen">
                        <div class="form-group col-lg-12">
                            <label for="keamanan">Persentase Keamanan</label>
                            <div class="input-group">
                                <input 
                                    type="number"
                                    autocomplete="off"
                                    min="0"
                                    max="100"
                                    class="form-control keamanan"
                                    name="keamanan"
                                    id="keamanan"
                                    oninput="functionKeamanan()"
                                    placeholder="Persentase Keamanan"
                                    aria-describedby="inputGroupPrepend">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="ipk">Persentase IPK</label>
                            <div class="input-group">
                                <input 
                                    type="number"
                                    autocomplete="off"
                                    min="0"
                                    max="100"
                                    class="form-control ipk"
                                    name="ipk"
                                    id="ipk"
                                    oninput="functionIpk()"
                                    placeholder="Persentase IPK"
                                    aria-describedby="inputGroupPrepend">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">%</span>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
<!-- Tambah Content pada Body JS -->
<script src="{{asset('js/blok.js')}}"></script>
@endsection