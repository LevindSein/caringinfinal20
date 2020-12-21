@extends(Session::get('role') == 'master' ? 'layout.master' : (Session::get('role') == 'admin' ? 'layout.admin' : 'layout.manajer'))
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            @yield('title')
            <div>
                <a 
                    href="{{url('tempatusaha')}}" 
                    type="submit"
                    title="Home"
                    class="btn btn-sm btn-info"><b><i class="fas fa-fw fa-home fa-sm text-white"></i></b>
                </a>
                &nbsp;
                <button 
                    type="button"
                    name="add_tempat"
                    id="add_tempat" 
                    class="btn btn-sm btn-success"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Tempat Usaha</b></button>
                &nbsp;
                <a 
                    href="{{url('tempatusaha/rekap')}}" 
                    type="submit"
                    title="Rekap Tempat Usaha"
                    class="btn btn-sm btn-danger"><b>Rekap</b>
                </a>
                &nbsp;
                <div class="dropdown no-arrow" style="display:inline-block">
                    <a 
                        class="dropdown-toggle btn btn-sm btn-warning" 
                        href="#" 
                        role="button" 
                        data-toggle="dropdown"
                        aria-haspopup="true" 
                        aria-expanded="false"
                        title="Menu Lainnya"><b>Menu
                        <i class="fas fa-ellipsis-v fa-sm fa-fw"></i></b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Pengguna Fasilitas:</div>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/fasilitas/airbersih')) ? 'active' : '' }}" href="{{url('tempatusaha/fasilitas/airbersih')}}">Air Bersih</a>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/fasilitas/listrik')) ? 'active' : '' }}" href="{{url('tempatusaha/fasilitas/listrik')}}">Listrik</a>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/fasilitas/keamananipk')) ? 'active' : '' }}" href="{{url('tempatusaha/fasilitas/keamananipk')}}">Keamanan & IPK</a>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/fasilitas/kebersihan')) ? 'active' : '' }}" href="{{url('tempatusaha/fasilitas/kebersihan')}}">Kebersihan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/fasilitas/airkotor')) ? 'active' : '' }}" href="{{url('tempatusaha/fasilitas/airkotor')}}">Air Kotor</a>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/fasilitas/diskon')) ? 'active' : '' }}" href="{{url('tempatusaha/fasilitas/diskon')}}">Diskon / Bebas Bayar</a>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/fasilitas/lain')) ? 'active' : '' }}" href="{{url('tempatusaha/fasilitas/lain')}}">Lain - Lain</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
        @yield('body')
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
                <h5>Apakah yakin hapus data tempat?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="confirm_result"></span>
            <div class="modal-body-short">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data tempat.</div>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Tempat Usaha</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <span id="form_result"></span>
            <form class="user" id="form_tempat" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label for="blok">Blok <span style="color:red;">*</span></label>
                        <div class="form-group">
                            <select class="blok" name="blok" id="blok" required></select>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="los">Nomor Los <span style="color:red;">*</span></label>
                        <input
                            required
                            type="text"
                            autocomplete="off"
                            name="los"
                            id="los"
                            class="form-control"
                            placeholder="1A,2,3 (Pisahkan dengan Koma)">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="lokasi">Lokasi</label>
                        <input
                            type="text"
                            name="lokasi"
                            autocomplete="off"
                            id="lokasi"
                            maxLength="50"
                            class="form-control"
                            placeholder="Pedagang K5 Di Depan A-1-001">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="los">Bentuk Usaha <span style="color:red;">*</span></label>
                        <input
                            required
                            type="text"
                            name="usaha"
                            autocomplete="off"
                            style="text-transform: capitalize;"
                            id="usaha"
                            maxLength="30"
                            class="form-control"
                            placeholder="Misal : Distributor Logistik">
                    </div>

                    <!-- Pemilik -->
                    <div class="form-group col-lg-12">
                        <label for="pemilik">Pemilik Tempat <span style="color:red;">*</span></label>
                        <div class="form-group">
                            <select class="pemilik" name="pemilik" id="pemilik" required></select>
                        </div>
                    </div>

                    <!-- Pengguna -->
                    <div class="form-group col-lg-12">
                        <label for="pengguna">Pengguna Tempat <span style="color:red;">*</span></label>
                        <div class="form-group">
                            <select class="pengguna" name="pengguna" id="pengguna" required></select>
                        </div>
                    </div>

                    <!-- Fasilitas -->
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-2">Fasilitas</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="air"
                                    id="myCheck1"
                                    data-related-item="myDiv1">
                                <label class="form-check-label" for="myCheck1">
                                    Air Bersih
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayAir">
                                <label for="myDiv1">Meteran Air <span style="color:red;">*</span></label>
                                <div class="form-group" id="myDiv1">
                                    <select class="meterAir" name="meterAir" id="meterAir"></select>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input
                                            checked
                                            class="form-check-input"
                                            type="radio"
                                            name="radioAirBersih"
                                            id="semua_airbersih"
                                            value="semua_airbersih">
                                        <label class="form-check-label" for="semua_airbersih">
                                            Bayar Semua
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="radioAirBersih"
                                            id="dis_airbersih"
                                            value="dis_airbersih">
                                        <label class="form-check-label" for="dis_airbersih">
                                            Diskon
                                        </label>
                                    </div>
                                    <div class="form-group" style="display:none" id="diskonBayarAir">
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <input 
                                                    type="number" 
                                                    autocomplete="off" 
                                                    class="form-control" 
                                                    min="0"
                                                    max="100"
                                                    name="persenDiskonAir" 
                                                    id="persenDiskonAir" 
                                                    placeholder="Persen" 
                                                    aria-describedby="inputGroupPrepend">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroupPrepend">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="radioAirBersih"
                                            id="hanya_airbersih"
                                            value="hanya_airbersih">
                                        <label class="form-check-label" for="hanya_airbersih">
                                            Hanya Bayar
                                        </label>
                                    </div>
                                    <div class="form-group" style="display:none" id="hanyaBayarAir">
                                        <div class="col-sm-12">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="hanya[]"
                                                    id="hanyaPemakaianAir"
                                                    value="byr">
                                                <label class="form-check-label" for="hanyaPemakaianAir">
                                                    Pemakaian
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="hanya[]"
                                                    id="hanyaBebanAir"
                                                    value="beban">
                                                <label class="form-check-label" for="hanyaBebanAir">
                                                    Beban
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="hanya[]"
                                                    id="hanyaPemeliharaanAir"
                                                    value="pemeliharaan">
                                                <label class="form-check-label" for="hanyaPemeliharaanAir">
                                                    Pemeliharaan
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="hanya[]"
                                                    id="hanyaArkotAir"
                                                    value="arkot">
                                                <label class="form-check-label" for="hanyaArkotAir">
                                                    Air Kotor
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="hanya[]"
                                                    id="hanyaChargeAir"
                                                    value="charge"
                                                    data-related-item="chargeAir">
                                                <label class="form-check-label" for="hanyaChargeAir">
                                                    Charge
                                                </label>
                                            </div>
                                            <div class="form-group" style="display:none" id="displayCharge">
                                                <div class="col-sm-12" id="chargeAir">
                                                    <div class="input-group">
                                                        <input 
                                                            type="number" 
                                                            autocomplete="off" 
                                                            class="form-control" 
                                                            min="0"
                                                            max="100"
                                                            name="persenChargeAir" 
                                                            id="persenChargeAir" 
                                                            placeholder="Persen" 
                                                            aria-describedby="inputGroupPrepend">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">%</span>
                                                        </div>
                                                        <select class="form-control" name="chargeAir" id="dariChargeAir">
                                                            <option value="ttl">Tagihan</option>
                                                            <option value="byr">Pemakaian</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="listrik"
                                    id="myCheck2"
                                    data-related-item="myDiv2">
                                <label class="form-check-label" for="myCheck2">
                                    Listrik
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayListrik">
                                <label for="myDiv2">Meteran Listrik <span style="color:red;">*</span></label>
                                <div class="form-group" id="myDiv2">
                                    <select class="meterListrik" name="meterListrik" id="meterListrik"></select>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="dis_listrik"
                                            id="dis_listrik"
                                            value="dis_listrik"
                                            data-related-item="diskonBayarListrik">
                                        <label class="form-check-label" for="dis_listrik">
                                            Diskon
                                        </label>
                                    </div>
                                    <div class="form-group" style="display:none" id="displayListrikDiskon">
                                        <div class="col-sm-12" id="diskonBayarListrik">
                                            <div class="input-group">
                                                <input 
                                                    type="number" 
                                                    autocomplete="off" 
                                                    class="form-control" 
                                                    min="0"
                                                    max="100"
                                                    name="persenDiskonListrik" 
                                                    id="persenDiskonListrik" 
                                                    placeholder="Persen" 
                                                    aria-describedby="inputGroupPrepend">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroupPrepend">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="keamananipk"
                                    id="myCheck3"
                                    data-related-item="myDiv3">
                                <label class="form-check-label" for="myCheck3">
                                    Keamanan & IPK
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayKeamananIpk">
                                <label for="myDiv3">Kategori Tarif <span style="color:red;">*</span></label>
                                <select class="form-control" name="trfKeamananIpk" id="myDiv3">
                                    <option selected hidden value="">--- Pilih Tarif ---</option>
                                    @foreach($trfKeamananIpk as $tarif)
                                    <option value="{{$tarif->tarif}}">Rp. {{number_format($tarif->tarif)}}</option>
                                    @endforeach
                                </select>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="dis_keamananipk"
                                            id="dis_keamananipk"
                                            value="dis_keamananipk"
                                            data-related-item="diskonBayarKeamananIpk">
                                        <label class="form-check-label" for="dis_keamananipk">
                                            Diskon
                                        </label>
                                    </div>
                                    <div class="form-group" style="display:none" id="displayKeamananIpkDiskon">
                                        <div class="col-sm-12" id="diskonBayarKeamananIpk">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                                </div>
                                                <input 
                                                    type="text" 
                                                    autocomplete="off" 
                                                    class="form-control"
                                                    name="diskonKeamananIpk" 
                                                    id="diskonKeamananIpk" 
                                                    placeholder="Nominal" 
                                                    aria-describedby="inputGroupPrepend">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kebersihan"
                                    id="myCheck4"
                                    data-related-item="myDiv4">
                                <label class="form-check-label" for="myCheck4">
                                    Kebersihan
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayKebersihan">
                                <label for="myDiv4">Kategori Tarif <span style="color:red;">*</span></label>
                                <select class="form-control" name="trfKebersihan" id="myDiv4">
                                    <option selected hidden value="">--- Pilih Tarif ---</option>
                                    @foreach($trfKebersihan as $tarif)
                                    <option value="{{$tarif->tarif}}">Rp. {{number_format($tarif->tarif)}}</option>
                                    @endforeach
                                </select>
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="dis_kebersihan"
                                            id="dis_kebersihan"
                                            value="dis_kebersihan"
                                            data-related-item="diskonBayarKebersihan">
                                        <label class="form-check-label" for="dis_kebersihan">
                                            Diskon
                                        </label>
                                    </div>
                                    <div class="form-group" style="display:none" id="displayKebersihanDiskon">
                                        <div class="col-sm-12" id="diskonBayarKebersihan">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                                </div>
                                                <input 
                                                    type="text"
                                                    autocomplete="off" 
                                                    class="form-control"
                                                    name="diskonKebersihan" 
                                                    id="diskonKebersihan" 
                                                    placeholder="Nominal" 
                                                    aria-describedby="inputGroupPrepend">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="sidebar-divider d-none d-md-block">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="airkotor"
                                    id="myCheck5"
                                    data-related-item="myDiv5">
                                <label class="form-check-label" for="myCheck5">
                                    Air Kotor
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayAirKotor">
                                <label for="myDiv5">Kategori Tarif <span style="color:red;">*</span></label>
                                <select class="form-control" name="trfAirKotor" id="myDiv5">
                                    <option selected hidden value="">--- Pilih Tarif ---</option>
                                    @foreach($trfAirKotor as $tarif)
                                    <option value="{{$tarif->id}}">Rp. {{number_format($tarif->tarif)}}</option>
                                    @endforeach
                                </select>
                            </div>

                            
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="lain"
                                    id="myCheck6"
                                    data-related-item="myDiv6">
                                <label class="form-check-label" for="myCheck6">
                                    Lain - Lain
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayLain">
                                <label for="myDiv6">Kategori Tarif <span style="color:red;">*</span></label>
                                <select class="form-control" name="trfLain" id="myDiv6">
                                    <option selected hidden value="">--- Pilih Tarif ---</option>
                                    @foreach($trfLain as $tarif)
                                    <option value="{{$tarif->id}}">Rp. {{number_format($tarif->tarif)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-2">Metode Bayar</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                    disabled
                                    class="form-check-input"
                                    type="radio"
                                    name="cicilan"
                                    id="cicilan1"
                                    value="0"
                                    checked>
                                <label class="form-check-label" for="cicilan1">
                                    Kontan
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    disabled
                                    class="form-check-input"
                                    type="radio"
                                    name="cicilan"
                                    id="cicilan2"
                                    value="1">
                                <label class="form-check-label" for="cicilan2">
                                    Cicil
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="ketCicil">
                                <select class="form-control" name="ket_cicil" id="ket_cicil">
                                    <option disabled="disabled" selected="selected" hidden="hidden" value="">--- Pilih Cicilan ---</option>
                                    <option value="2">2x</option>
                                    <option value="4">4x</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-2">Status Tempat</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="status"
                                    id="myStatus1"
                                    value="1"
                                    checked="checked">
                                <label class="form-check-label" for="myStatus1">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="status"
                                    id="myStatus2"
                                    value="2">
                                <label class="form-check-label" for="myStatus2">
                                    Pasif
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="ketStatus">
                                <input
                                    type="text"
                                    name="ket_tempat"
                                    id="ket_tempat"
                                    maxLength="50"
                                    class="form-control"
                                    placeholder="Jelaskan Kondisi Tempat">
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
<script src="{{asset('js/tempat-usaha.js')}}"></script>
@endsection