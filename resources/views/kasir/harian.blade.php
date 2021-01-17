@extends('layout.kasir')
@section('content')
<div class="form-group">
    <h3 style="color:#16aaff;font-weight:700;text-align:center;">{{$tanggal}}</h3>
</div>
<div class="form-group d-flex align-items-center justify-content-center">
    <div>
        <a 
            href="{{url('kasir/harian')}}"
            type="button"
            class="btn btn-outline-inverse-info"
            title="Home">
            <i class="mdi mdi-home btn-icon-append"></i>  
        </a>
    </div>
    &nbsp;
    <div>
        <a 
            type="button"
            class="btn btn-outline-inverse-info"
            data-toggle="modal"
            data-target="#myPendapatan"
            title="Cari Pendapatan">
            <i class="mdi mdi-magnify btn-icon-append"></i>  
        </a>
    </div>
    &nbsp;
    <div>
        <button
            type="button"
            class="btn btn-outline-inverse-info"
            data-toggle="modal"
            data-target="#myPenerimaan"
            title="Penerimaan Harian">
            <i class="mdi mdi-printer btn-icon-append"></i>  
        </button>
    </div>
    &nbsp;
    <div>
        <button
            id="tambah"
            type="button"
            class="tambah btn btn-outline-inverse-info"
            title="Tambah">
            <i class="mdi mdi-plus btn-icon-append"></i>  
        </button>
    </div>
</div>
<span id="form_result"></span>
<div id="container">
    <div id="qr-result" hidden="">
        <input hidden id="outputData"></input>
    </div>
    <canvas hidden="" id="qr-canvas"></canvas>
</div>
<form>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group col-lg-12">
                <label for="exampleInput1">Form</label>
                <input type="text" class="form-control" id="exampleInput1" placeholder="Username">
            </div>
            <div class="form-group col-lg-12">
                <label for="exampleInput2">Form</label>
                <input type="text" class="form-control" id="exampleInput2" placeholder="Username">
            </div>
            <div class="form-group col-lg-12">
                <label for="exampleInput3">Form</label>
                <input type="text" class="form-control" id="exampleInput3" placeholder="Username">
            </div>
            <div class="form-group col-lg-12">
                <label for="exampleInput4">Form</label>
                <input type="text" class="form-control" id="exampleInput4" placeholder="Username">
            </div>
            <div class="form-group col-lg-12">
                <label for="exampleInput5">Form</label>
                <input type="text" class="form-control" id="exampleInput5" placeholder="Username">
            </div>
        </div>
        <div class="col-lg-6">
            <button type="button" class="btn btn-primary btn-rounded btn-icon float-right">
                <i class="mdi mdi-plus"></i>
            </button>
        </div>  
    </div>
    <br>
    <div class="col-lg-12" style="text-align:center;">
        <input type="submit" class="btn btn-primary mr-2" value="Submit" />
    </div>
</form>

<div
    class="modal fade"
    id="myPenerimaan"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cetak Penerimaan Harian</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('kasir/harian/penerimaan')}}" target="_blank" method="GET">
                <div class="modal-body-short">
                    <div class="form-group col-lg-12">
                        <br>
                        <input
                            required
                            placeholder="Masukkan Tanggal Penerimaan" class="form-control" type="text" onfocus="(this.type='date')"
                            autocomplete="off"
                            type="date"
                            name="tgl_penerimaan"
                            id="tgl_penerimaan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myPendapatan"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pencarian Pendapatan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('kasir/harian/pendapatan')}}" method="GET">
                <div class="modal-body-short">
                    <div class="form-group col-lg-12">
                        <br>
                        <input
                            required
                            placeholder="Masukkan Tanggal Pendapatan" class="form-control" type="text" onfocus="(this.type='date')"
                            autocomplete="off"
                            type="date"
                            name="tgl_pendapatan"
                            id="tgl_pendapatan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Apakah yakin hapus data pendapatan?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <div class="modal-body-short">
                <span>Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data pendapatan.</span>
            </div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myManual"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manual"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" id="form_manual" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group col-lg-12">
                            <label for="nama">Nama <span style="color:red;">*</span></label>
                            <input
                                autocomplete="off"
                                placeholder="Masukkan Nama"
                                required
                                style="text-transform:capitalize;"
                                name="nama"
                                class="form-control"
                                id="nama">
                        </div>
                        <hr>
                        <div class="form-group col-lg-12">
                            <label for="keamananlos">Keamanan LOS</label>
                            <input
                                placeholder="Masukkan Nilai"
                                autocomplete="off"
                                type="text" 
                                pattern="^[\d,]+$"
                                name="keamananlos"
                                class="form-control"
                                id="keamananlos">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="kebersihanlos">Kebersihan LOS</label>
                            <input
                                placeholder="Masukkan Nilai"
                                autocomplete="off"
                                type="text" 
                                pattern="^[\d,]+$"
                                name="kebersihanlos"
                                class="form-control"
                                id="kebersihanlos">
                        </div>
                        <hr>
                        <div class="form-group col-lg-12">
                            <label for="kebersihanpos">Kebersihan POS</label>
                            <input
                                placeholder="Masukkan Nilai"
                                autocomplete="off"
                                type="text" 
                                pattern="^[\d,]+$"
                                name="kebersihanpos"
                                class="form-control"
                                id="kebersihanpos">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="kebersihanlebihpos">Kebersihan Lebih POS</label>
                            <input
                                placeholder="Masukkan Nilai"
                                autocomplete="off"
                                type="text" 
                                pattern="^[\d,]+$"
                                name="kebersihanlebihpos"
                                class="form-control"
                                id="kebersihanlebihpos">
                        </div>
                        <hr>
                        <div class="form-group col-lg-12">
                            <label for="abonemen">Abonemen</label>
                            <input
                                placeholder="Masukkan Nilai"
                                autocomplete="off"
                                type="text" 
                                pattern="^[\d,]+$"
                                name="abonemen"
                                class="form-control"
                                id="abonemen">
                        </div>
                        <hr>
                        <div class="form-group col-lg-12">
                            <label for="laporan">Laporan (optional)</label>
                            <textarea placeholder="Masukkan Laporan" autocomplete="off" name="laporan" class="form-control" id="laporan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id"/>
                    <input type="hidden" name="action" id="action" class="btn btn-primary btn-sm" value="Add" />
                    <input type="submit" name="action_btn" id="action_btn" class="btn btn-primary btn-sm" value="Tambah" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/kasir-harian.js')}}"></script>
@endsection