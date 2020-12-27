@extends('tempatusaha.index')

@section('title')
<title>Data Tempat Usaha | BP3C</title>
<h6 class="m-0 font-weight-bold text-primary">Data Tempat Usaha</h6>
@endsection

@section('body')
@if(Session::get('role') == 'master' || Session::get('role') == 'admin')
<div class="table-responsive ">
    <table
        class="table"
        id="tabelTempat"
        width="100%"
        cellspacing="0"
        style="font-size:0.75rem;">
        <thead class="table-bordered">
            <tr>
                <th rowspan="2">Kontrol</th>
                <th rowspan="2">No.Los</th>
                <th rowspan="2">Pengguna</th>
                <th rowspan="2">Lokasi</th>
                <th rowspan="2">Jml.Los</th>
                <th rowspan="2">Usaha</th>
                <th colspan="2" >Listrik</th>
                <th colspan="2">Air Bersih</th>
                <th colspan="2">Keamanan & IPK</th>
                <th colspan="2">Kebersihan</th>
                <th rowspan="2">Air Kotor</th>
                <th rowspan="2">Lain - Lain</th>
                <th rowspan="2">Status</th>
                <th rowspan="2">Ket</th>
                <th rowspan="2">Pemilik</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr>
                <th>Meteran</th>
                <th>Bebas Bayar</th>
                <th>Meteran</th>
                <th>Bebas Bayar</th>
                <th>Tarif</th>
                <th>Diskon</th>
                <th>Tarif</th>
                <th>Diskon</th>
            </tr>
        </thead>
    </table>
</div>
@else
<div class="table-responsive ">
    <table
        class="table"
        id="tabelTempat1"
        width="100%"
        cellspacing="0"
        style="font-size:0.75rem;">
        <thead class="table-bordered">
            <tr>
                <th rowspan="2">Kontrol</th>
                <th rowspan="2">No.Los</th>
                <th rowspan="2">Pengguna</th>
                <th rowspan="2">Lokasi</th>
                <th rowspan="2">Jml.Los</th>
                <th rowspan="2">Usaha</th>
                <th colspan="2" >Listrik</th>
                <th colspan="2">Air Bersih</th>
                <th colspan="2">Keamanan & IPK</th>
                <th colspan="2">Kebersihan</th>
                <th rowspan="2">Air Kotor</th>
                <th rowspan="2">Lain - Lain</th>
                <th rowspan="2">Status</th>
                <th rowspan="2">Ket</th>
                <th rowspan="2">Pemilik</th>
            </tr>
            <tr>
                <th>Meteran</th>
                <th>Bebas Bayar</th>
                <th>Meteran</th>
                <th>Bebas Bayar</th>
                <th>Tarif</th>
                <th>Diskon</th>
                <th>Tarif</th>
                <th>Diskon</th>
            </tr>
        </thead>
    </table>
</div>
@endif
@endsection