@extends(Session::get('role') == 'master' ? 'layout.master' : (Session::get('role') == 'admin' ? 'layout.admin' : 'layout.manajer'))
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Details Tagihan Tempat Usaha | BP3C</title>
<div class = "container-fluid">
    <div class="d-sm-flex align-items-center justify-content-center">
        <h3 class="h3 mb-4 text-primary"><b>{{$blok}}</b></h3>
    </div>
    <div class="mb-4">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="card shadow col-lg-6">
                    <div class="p-4">
                        <div class="form-group col-lg-12">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <label>Kode Kontrol</label>
                                <label>Status</label>
                            </div>
                            @foreach($dataset as $d)
                            <div class="input-group">
                                <button 
                                    aria-expanded="true" 
                                    aria-controls="exampleAccordion1" 
                                    data-toggle="collapse" 
                                    href="#{{$d->kd_kontrol}}" 
                                    class="form-control shadow"
                                    aria-describedby="inputGroupPrepend"
                                    style="text-align:left;">{{$d->kd_kontrol}}
                                </button>
                                <div class="input-group-prepend">
                                    <?php
                                    if($d->stt_tempat == 1){$status = 'Aktif';}else{$status = 'Pasif';}
                                    ?>
                                    <span class="input-group-text shadow" style="{{ ($status == 'Aktif') ? 'background-color:#1cc88a;color:#ffffff;' : '' }}" id="inputGroupPrepend">{{$status}}</span>
                                </div>
                            </div>
                            <div id="exampleAccordion" data-children=".item">
                                <div class="item">
                                    <br>
                                    <div 
                                        data-parent="#exampleAccordion" 
                                        id="{{$d->kd_kontrol}}" 
                                        class="collapse">
                                        <p>Pemilik : {{$d->pemilik}}</p>
                                        <p>Pengguna : {{$d->pengguna}}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="form-group col-lg-12">
                                <a href="{{url('tempatusaha/rekap')}}" type="button" class="btn btn-primary btn-user btn-block">OK</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<!-- Tambah Content pada Body modal -->
@endsection


@section('js')
<!-- Tambah Content pada Body JS -->
@endsection