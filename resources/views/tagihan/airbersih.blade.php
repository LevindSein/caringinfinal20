@extends('tagihan.form')
@section('title')
<h3 class="h3 mb-4 text-primary"><b>{{$dataset->kd_kontrol}}</b></h3>
<title>Tambah Tagihan Air Bersih | BP3C</title>
@endsection
@section('body')
<form class="user" action="{{url('tagihan/airbersih')}}" method="POST">
    @csrf
    <div class="form-group col-lg-12">
        <label for="nama">Pengguna</label>
        <input
            required
            value="{{$dataset->nama}}"
            name="nama"
            class="form-control"
            id="nama">
    </div>
    <div class="form-group col-lg-12">
        <label for="lokasi">No.Los</label>
        <input
            readonly
            value="{{$ket->no_alamat}}"
            name="lokasi"
            class="form-control"
            id="lokasi">
    </div>
    @if($ket->lok_tempat != NULL)
    <div class="form-group col-lg-12">
        <label for="ket">Kode Sebelumnya</label>
        <input
            readonly
            value="{{$ket->lok_tempat}}"
            name="ket"
            class="form-control"
            id="ket">
    </div>
    @endif
    <div class="form-group col-lg-12">
        <label for="awal">Stand Awal Air Bersih <span style="color:red;">*</span></label>
        <input
            required
            value="{{number_format($dataset->awal_airbersih)}}"
            autocomplete="off"
            type="text" 
            pattern="^[\d,]+$"
            name="awal"
            class="form-control"
            id="awal">
    </div>
    <div class="form-group col-lg-12">
        <label for="akhir">Stand Akhir Air Bersih <span style="color:red;">*</span></label>
        <input
            autofocus
            required
            value="{{number_format($suggest)}}"
            autocomplete="off"
            type="text" 
            pattern="^[\d,]+$"
            name="akhir"
            class="form-control"
            id="akhir">
    </div>
    <div class="form-group col-lg-12">
        <label for="laporan">Laporan (optional)</label>
        <textarea autocomplete="off" name="laporan" class="form-control" id="laporan">{{$dataset->ket}}</textarea>
    </div>
    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$dataset->id}}">
    <input type="hidden" name="kd_kontrol" id="kd_kontrol" value="{{$dataset->kd_kontrol}}">
    <div class="form-group col-lg-12">
        <Input type="submit" id="tambah" value="Tambah Tagihan" class="btn btn-primary btn-user btn-block">
    </div>
</form>
@endsection

@section('jsplus')
<script src="{{asset('js/form-airbersih.js')}}"></script>
@endsection