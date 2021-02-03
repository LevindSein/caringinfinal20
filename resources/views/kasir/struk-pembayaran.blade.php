@extends('layout.kasir')
@section('content')
<title>Struk Tagihan | BP3C</title>
<div class="form-group">
    <h3 style="color:#16aaff;font-weight:700;text-align:center;">STRUK TAGIHAN</h3>
</div>
<span id="form_result"></span>
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
                    <th style="text-align:center;"><b>Bulan Bayar</b></th>
                    <th style="text-align:center;"><b>Pengguna</b></th>
                    <th style="text-align:center;"><b>Ket</b></th>
                    <th style="text-align:center;"><b>Action</b></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/struk-tagihan.js')}}"></script>
@endsection