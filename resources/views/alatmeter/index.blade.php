@extends( Session::get('role') == 'master' ? 'layout.master' : 'layout.admin')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Data Alat Meteran | BP3C</title>
<div class = "container-fluid">
    <ul class="tabs-animated-shadow tabs-animated nav">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0">
                <span>Listrik</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-1" data-toggle="tab" href="#tab-animated-1">
                <span>Air Bersih</span>
            </a>
        </li>
    </ul>
</div>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data Alat Meteran</h6>
            <div>
                <button 
                    type="button"
                    name="add_alat"
                    id="add_alat" 
                    class="btn btn-sm btn-success"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Alat Meter</b></button>
                &nbsp;
                <a 
                    href="{{url('utilities/meteran/print')}}"
                    type="submit" 
                    target="_blank"
                    class="btn btn-sm btn-info disabled"><b>
                    <i class="fas fa-fw fa-print fa-sm text-white-50"></i> Print Form</b></a>
            </div>
        </div>
        <div class="card-body">
            
            <div class="tab-content">
                <div class="tab-pane active" id="tab-animated-0" role="tabpanel">
                    @include('alatmeter.listrik')
                </div>
                <div class="tab-pane" id="tab-animated-1" role="tabpanel">
                    @include('alatmeter.airbersih')
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
                <h5>Apakah yakin hapus data alat?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <div class="modal-body-short">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data alat.</div>
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
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Alat Meteran</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="form_result"></span>
            <form class="user" id="form_alat" method="POST">
                <div class="modal-body-short">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label for="nomor">Nomor Alat  <span style="color:red;">*</span></label>
                        <input
                            type="text"
                            autocomplete="off"
                            maxLength="30"
                            style="text-transform:uppercase;"
                            name="nomor"
                            class="form-control"
                            id="nomor">
                    </div>
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-2">Tambah Alat</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="radioMeter"
                                    id="listrik"
                                    value="listrik"
                                    data-related-item="divListrik"
                                    checked>
                                <label class="form-check-label" for="listrik">
                                    Listrik
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="meteranListrik">
                                <div class="form-group" id="divListrik">
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="standListrik"
                                        id="standListrik"
                                        placeholder="Masukkan Stand Akhir Listrik">
                                    <br>
                                    <div class="input-group">
                                        <input 
                                            type="text" 
                                            autocomplete="off" 
                                            class="form-control input-group"
                                            name="dayaListrik"
                                            id="dayaListrik"
                                            placeholder="Masukkan Daya Listrik"
                                            aria-describedby="inputGroupPrepend">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">Watt</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="radioMeter"
                                    id="air"
                                    value="air"
                                    data-related-item="divAir">
                                <label class="form-check-label" for="air">
                                    Air Bersih
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="meteranAir">
                                <div class="form-group" id="divAir">
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="standAir"
                                        id="standAir"
                                        placeholder="Masukkan Stand Akhir Air">
                                </div>
                            </div>
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
<!-- Tambah Content pada Body JS -->
<script src="{{asset('js/alat-meter.js')}}"></script>
@endsection