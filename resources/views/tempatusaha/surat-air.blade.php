@extends('layout.master')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <title>Surat Air Bersih | BP3C</title>
            <h6 class="m-0 font-weight-bold text-primary">Data Surat Pengajuan Air Bersih Bulan {{$bulan}}</h6>
        </div>
        <div class="card-body">
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
        </div>
    </div>    
</div>
@endsection

@section('js')
<!-- Tambah Content pada Body JS -->
@endsection