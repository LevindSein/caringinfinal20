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
    @if($platform == 'mobile')
    &nbsp;
    <div>
        <button
            hidden
            id="btn-scan-qr"
            type="button"
            class="btn btn-outline-inverse-info"
            title="Scan Qrcode">
            <i class="mdi mdi-qrcode-scan btn-icon-append"></i>  
        </button>
    </div>
    @endif
    <!-- &nbsp;
    <div>
        <a 
            type="button"
            class="btn btn-outline-inverse-info"
            data-toggle="modal"
            data-target="#myBulanan"
            title="Bayar by Bulanan">
            <i class="mdi mdi-magnify btn-icon-append"></i>  
        </a>
    </div> -->
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
        <a 
            href="{{url('kasir/restore')}}"
            target="_blank"
            type="button"
            class="btn btn-outline-inverse-info"
            title="Restore Tagihan">
            <i class="mdi mdi-restore btn-icon-append"></i>  
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
@if($platform == 'mobile')
<div class="form-group d-flex align-items-center justify-content-center">
    <div class="col-lg-4 ">
        <select class="btn btn-inverse-dark" style="width:100%" name="printer" id="printer">
            <option <?php if(Session::get('printer') == 'panda') { ?> selected <?php } ?> value="panda">Panda Printer Mobile 80mm</option>
            <option <?php if(Session::get('printer') == 'androidpos') { ?> selected <?php } ?> value="androidpos">Android Pos Printer 50mm</option>
        </select>
    </div>
</div>
@endif
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
            <form class="user" id="form_rincian" method="POST">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="judulRincian">Rincian</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
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
                    <div id="fasListrik">
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
                        <div class="form-group col-lg-12" id="tungdivListrik">
                            <div class="justify-content-between" id="tungtestListrik" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tunglistrik">Tunggakan Listrik</span>
                                </div>
                                <div>
                                    <span id="tungnominalListrik"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="dendivListrik">
                            <div class="justify-content-between" id="dentestListrik" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="denlistrik">Denda Listrik</span>
                                </div>
                                <div>
                                    <span id="dennominalListrik"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasAirBersih">
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
                        <div class="form-group col-lg-12" id="tungdivAirBersih">
                            <div class="justify-content-between" id="tungtestAirBersih" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungairbersih">Tunggakan Air Bersih</span>
                                </div>
                                <div>
                                    <span id="tungnominalAirBersih"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="dendivAirBersih">
                            <div class="justify-content-between" id="dentestAirBersih" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="denairbersih">Denda Air Bersih</span>
                                </div>
                                <div>
                                    <span id="dennominalAirBersih"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasKeamananIpk">
                        <div class="form-group col-lg-12" id="divKeamananIpk">
                            <div class="justify-content-between" id="testKeamananIpk" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="keamananipk">Keamanan IPK</span>
                                </div>
                                <div>
                                    <span id="nominalKeamananIpk"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="tungdivKeamananIpk">
                            <div class="justify-content-between" id="tungtestKeamananIpk" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungkeamananipk">Tunggakan Keamanan IPK</span>
                                </div>
                                <div>
                                    <span id="tungnominalKeamananIpk"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasKebersihan">
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
                        <div class="form-group col-lg-12" id="tungdivKebersihan">
                            <div class="justify-content-between" id="tungtestKebersihan" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungkebersihan">Tunggakan Kebersihan</span>
                                </div>
                                <div>
                                    <span id="tungnominalKebersihan"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasAirKotor">
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
                        <div class="form-group col-lg-12" id="tungdivAirKotor">
                            <div class="justify-content-between" id="tungtestAirKotor" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungairkotor">Tunggakan Air Kotor</span>
                                </div>
                                <div>
                                    <span id="tungnominalAirKotor"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasLain">
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
                        <div class="form-group col-lg-12" id="tungdivLain">
                            <div class="justify-content-between" id="tungtestLain" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tunglain">Tunggakan Lain - Lain</span>
                                </div>
                                <div>
                                    <span id="tungnominalLain"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                        <div>
                            <span style="color:#3f6ad8;"><strong>Total</strong></span>
                        </div>
                        <div>
                            <h3><strong><span id="nominalTotal" style="color:#3f6ad8;"></span></strong></h3>
                        </div>
                    </div>
                    
                    <input hidden id="tempatId" name="tempatId"/>
                    <input hidden id="pedagang" name="pedagang"/>
                    <input hidden id="los" name="los"/>
                    <input hidden id="lokasi" name="lokasi"/>
                    <input hidden id="faktur" name="faktur"/>
                    
                    <input hidden id="taglistrik" name="taglistrik"/>
                    <input hidden id="tagtunglistrik" name="tagtunglistrik"/>
                    <input hidden id="tagdenlistrik" name="tagdenlistrik"/>
                    <input hidden id="tagdylistrik" name="tagdylistrik"/>
                    <input hidden id="tagawlistrik" name="tagawlistrik"/>
                    <input hidden id="tagaklistrik" name="tagaklistrik"/>
                    <input hidden id="tagpklistrik" name="tagpklistrik"/>
                    
                    <input hidden id="tagairbersih" name="tagairbersih"/>
                    <input hidden id="tagtungairbersih" name="tagtungairbersih"/>
                    <input hidden id="tagdenairbersih" name="tagdenairbersih"/>
                    <input hidden id="tagawairbersih" name="tagawairbersih"/>
                    <input hidden id="tagakairbersih" name="tagakairbersih"/>
                    <input hidden id="tagpkairbersih" name="tagpkairbersih"/>
                    
                    <input hidden id="tagkeamananipk" name="tagkeamananipk"/>
                    <input hidden id="tagtungkeamananipk" name="tagtungkeamananipk"/>
                    
                    <input hidden id="tagkebersihan" name="tagkebersihan"/>
                    <input hidden id="tagtungkebersihan" name="tagtungkebersihan"/>
                    
                    <input hidden id="tagairkotor" name="tagairkotor"/>
                    <input hidden id="tagtungairkotor" name="tagtungairkotor"/>

                    <input hidden id="taglain" name="taglain"/>
                    <input hidden id="tagtunglain" name="tagtunglain"/>

                    <input hidden id="totalTagihan" name="totalTagihan"/>
                    
                    <input type="submit" id="printStruk" class="btn btn-primary btn-sm" value="Bayar Sekarang"/>
                </div>
            </form>
        </div>
    </div>
</div>

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
            <form class="user" action="{{url('kasir/penerimaan')}}" target="_blank" method="GET">
                <div class="modal-body-short">
                    <div class="form-group col-lg-12">
                        <br>
                        <input
                            required
                            placeholder="Masukkan Tanggal Penerimaan" class="form-control" type="text" onfocus="(this.type='date')"
                            autocomplete="off"
                            type="date"
                            name="tanggal"
                            id="tanggal">
                    </div>
                    <div class="form-group col-lg-12">
                        <br>
                        <label for="shift">Pilih Shift</label>
                        <select class="form-control" name="shift" id="shift">
                            <option selected value="2">Semua</option>
                            <option value="1">Shift 1</option>
                            <option value="0">Shift 2</option>
                        </select>
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
<script src="{{asset('js/kasir.js')}}"></script>

@if($platform == 'mobile')
<script src="{{asset('js/qrCodeScanner.js')}}"></script>
@endif
@endsection