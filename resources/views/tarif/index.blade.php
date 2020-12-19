@extends( Session::get('role') == 'master' ? 'layout.master' : 'layout.admin')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Tarif Fasilitas | BP3C</title>
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
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-2" data-toggle="tab" href="#tab-animated-2">
                <span>Keamanan & IPK</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-3" data-toggle="tab" href="#tab-animated-3">
                <span>Kebersihan</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-4" data-toggle="tab" href="#tab-animated-4">
                <span>Air Kotor</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-6" data-toggle="tab" href="#tab-animated-6">
                <span>Lain - Lain</span>
            </a>
        </li>
    </ul>
</div>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tarif Fasilitas</h6>
            <div>
                <button 
                    type="button"
                    name="add_tarif"
                    id="add_tarif" 
                    class="btn btn-sm btn-success"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Tarif Fasilitas</b></button>
            </div>
        </div>
        <div class="card-body">
            
            <div class="tab-content">
                <div class="tab-pane active" id="tab-animated-0" role="tabpanel">
                    @include('tarif.listrik')
                </div>
                <div class="tab-pane" id="tab-animated-1" role="tabpanel">
                    @include('tarif.airbersih')
                </div>
                <div class="tab-pane" id="tab-animated-2" role="tabpanel">
                    @include('tarif.keamananipk')
                </div>
                <div class="tab-pane" id="tab-animated-3" role="tabpanel">
                    @include('tarif.kebersihan')
                </div>
                <div class="tab-pane" id="tab-animated-4" role="tabpanel">
                    @include('tarif.airkotor')
                </div>
                <div class="tab-pane" id="tab-animated-6" role="tabpanel">
                    @include('tarif.lain')
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection

@section('modal')
<!-- Tambah Content pada Body modal -->
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Tarif</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="form_result"></span>
            <form class="user" id="form_tarif" method="POST">
                <div class="modal-body-short">
                    @csrf
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkKeamananIpk"
                                    id="myCheck1"
                                    data-related-item="myDiv1">
                                <label class="form-check-label" for="myCheck1">
                                    Keamanan IPK
                                </label>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="input-group" id="myDiv1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="keamananIpk"
                                        id="keamananIpk"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkKebersihan"
                                    id="myCheck2"
                                    data-related-item="myDiv2">
                                <label class="form-check-label" for="myCheck2">
                                    Kebersihan
                                </label>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="input-group" id="myDiv2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="kebersihan"
                                        id="kebersihan"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkAirKotor"
                                    id="myCheck3"
                                    data-related-item="myDiv3">
                                <label class="form-check-label" for="myCheck3">
                                    Air Kotor
                                </label>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="input-group" id="myDiv3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="airkotor"
                                        id="airkotor"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkLain"
                                    id="myCheck5"
                                    data-related-item="myDiv5">
                                <label class="form-check-label" for="myCheck5">
                                    Lain - Lain
                                </label>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="input-group" id="myDiv5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="lain"
                                        id="lain"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
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
<script src="{{asset('js/tarif.js')}}"></script>
@endsection