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
    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$dataset->id}}">
    <input type="hidden" name="kd_kontrol" id="kd_kontrol" value="{{$dataset->kd_kontrol}}">
    <div class="form-group col-lg-12">
        <Input type="submit" value="Tambah Tagihan" class="btn btn-primary btn-user btn-block">
    </div>
</form>
@endsection

@section('jsplus')
<script>
$(document).ready(function(){
    document
        .getElementById('akhir')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    
    document
        .getElementById('awal')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );

    var input = $("#akhir");
    var len = input.val().length;
    input[0].focus();
    input[0].setSelectionRange(len, len);
});
</script>
@endsection