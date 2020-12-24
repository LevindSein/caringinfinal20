@extends('layout.kasir')
@section('content')
<div class="form-group">
    <h3 style="color:#16aaff;font-weight:700;text-align:center;">{{$bulan}}</h3>
</div>
<div class="form-group d-flex align-items-center justify-content-center">
    <div>
        <a 
            href="{{url('kasir')}}"
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
            data-target="#myBulanan"
            title="Bayar by Bulanan">
            <i class="mdi mdi-magnify btn-icon-append"></i>  
        </a>
    </div>
</div>
<span id="form_result"></span>
<div id="container">
    <div id="qr-result" hidden="">
        <input hidden id="outputData"></input>
    </div>
    <canvas hidden="" id="qr-canvas"></canvas>
</div>
<div class="row">
    <div class="table-responsive">
        <table 
            id="tabelKasir" 
            class="table table-bordered" 
            cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th style="text-align:center;"><b>Kontrol</b></th>
                    <th style="text-align:center;"><b>Tagihan</b></th>
                    <th style="text-align:center;"><b>Pengguna</b></th>
                    <th style="text-align:center;"><b>Ket</b></th>
                    <th style="text-align:center;"><b>Action</b></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('modal')
<div
    class="modal fade"
    id="myRincian"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="judulRincian">Rincian</h3>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" id="form_rincian" method="POST">
                <div class="modal-body">
                    @csrf
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
                    <hr>
                    <div class="col-lg-12 justify-content-between" style="display: flex;flex-wrap: wrap;">
                        <div>
                            <span style="color:#3f6ad8;"><strong>Fasilitas</strong></span>
                        </div>
                        <div>
                            <span style="color:#3f6ad8;"><strong>Nominal</strong></span>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group col-lg-12" id="divListrik">
                        <div class="justify-content-between" id="testListrik" style="display:flex;flex-wrap:wrap;">
                            <div>
                                <span id="listrik">Listrik</span>
                            </div>
                            <div>
                                <span id="nominalListrik"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="divAirBersih">
                        <div class="justify-content-between" id="testAirBersih" style="display:flex;flex-wrap:wrap;">
                            <div>
                                <span id="airbersih">Air Bersih</span>
                            </div>
                            <div>
                                <span id="nominalAirBersih"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="divKeamananIpk">
                        <div class="justify-content-between" id="testKeamananIpk" style="display:flex;flex-wrap:wrap;">
                            <div>
                                <span id="keamananipk">Keamanan & IPK</span>
                            </div>
                            <div>
                                <span id="nominalKeamananIpk"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="divKebersihan">
                        <div class="justify-content-between" id="testKebersihan" style="display:flex;flex-wrap:wrap;">
                            <div>
                                <span id="kebersihan">Kebersihan</span>
                            </div>
                            <div>
                                <span id="nominalKebersihan"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="divAirKotor">
                        <div class="justify-content-between" id="testAirKotor" style="display:flex;flex-wrap:wrap;">
                            <div>
                                <span id="airkotor">Air Kotor</span>
                            </div>
                            <div>
                                <span id="nominalAirKotor"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="divDenda">
                        <div class="justify-content-between" id="testDenda" style="display:flex;flex-wrap:wrap;">
                            <div>
                                <span id="denda">Denda</span>
                            </div>
                            <div>
                                <span id="nominalDenda"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="divLain">
                        <div class="justify-content-between" id="testLain" style="display:flex;flex-wrap:wrap;">
                            <div>
                                <span id="lain">Lain - Lain</span>
                            </div>
                            <div>
                                <span id="nominalLain"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                        <div>
                            <span style="color:#3f6ad8;"><strong>Total</strong></span>
                        </div>
                        <div>
                            <h3><strong><span id="nominalTotal" style="color:#3f6ad8;"></span></strong></h3>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input hidden id="tempatId" name="tempatId"/>
                    <input type="submit" id="printStruk" class="btn btn-primary btn-sm" value="Bayar Sekarang"/>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="hidden" id="bln_periode" value="{{Session::get('bln_periode')}}" />
<input type="hidden" id="thn_periode" value="{{Session::get('thn_periode')}}" />
<div
    class="modal fade"
    id="myBulanan"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bayar bulan apa ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('kasir/periode')}}" method="GET">
                <div class="modal-body-short">
                    <br>
                    <div class="form-group col-lg-12">
                        <label for="bulan">Bulan</label>
                        <select class="form-control" name="bulan" id="bulan">
                            <option selected hidden value="{{$month}}">Pilih Bulan</option>
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
                    <div class="form-group col-lg-12">
                        <label for="tahun">Tahun</label>
                        <select class="form-control" name="tahun" id="tahun">
                            <option selected hidden value="{{$tahun}}">{{$tahun}}</option>
                            @foreach($dataTahun as $d)
                            <option value="{{$d->thn_tagihan}}">{{$d->thn_tagihan}}</option>
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
@endsection

@section('js')
<script src="{{asset('js/kasir-periode.js')}}"></script>
@endsection