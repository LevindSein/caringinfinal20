@extends('layout.master')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Potensi Tempat Usaha | BP3C</title>
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
                <span>Keamanan IPK</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-c-3" data-toggle="tab" href="#tab-animated-3">
                <span>Kebersihan</span>
            </a>
        </li>
    </ul>
</div>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Potensi Tempat Usaha</h6>
            <div>
                <a 
                    href="{{url('tempatusaha')}}" 
                    type="submit"
                    title="Home"
                    class="btn btn-sm btn-info"><b><i class="fas fa-fw fa-home fa-sm text-white"></i></b>
                </a>
                &nbsp;
                @if(Session::get('role') == 'master' || Session::get('role') == 'admin')
                <button 
                    type="button"
                    name="add_tempat"
                    id="add_tempat" 
                    class="btn btn-sm btn-primary"><b>
                    <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Tempat Usaha</b></button>
                &nbsp;
                @endif
                <div class="dropdown no-arrow" style="display:inline-block">
                    <a 
                        class="dropdown-toggle btn btn-sm btn-danger" 
                        href="#" 
                        role="button" 
                        data-toggle="dropdown"
                        aria-haspopup="true" 
                        aria-expanded="false"
                        title="Menu Lainnya"><b>Menu
                        <i class="fas fa-ellipsis-v fa-sm fa-fw"></i></b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        @if(Session::get('role') == 'master' || Session::get('role') == 'manajer')
                        <div class="dropdown-header">Manajemen:</div>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/rekap')) ? 'active' : '' }}" href="{{url('tempatusaha/rekap')}}" target="_blank">Data Rekap</a>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/potensi')) ? 'active' : '' }}" href="{{url('tempatusaha/potensi')}}" target="_blank">Data Potensi</a>
                        <div class="dropdown-divider"></div>
                        @endif
                        @if(Session::get('role') == 'master' || Session::get('role') == 'admin')
                        <div class="dropdown-header">Surat Terkait:</div>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/pengajuan/listrik')) ? 'active' : '' }}" href="{{url('tempatusaha/pengajuan/listrik')}}" target="_blank">Surat Listrik</a>
                        <a class="dropdown-item {{ (request()->is('tempatusaha/pengajuan/air')) ? 'active' : '' }}" href="{{url('tempatusaha/pengajuan/air')}}" target="_blank">Surat Air</a>
                        <div class="dropdown-divider"></div>
                        @endif
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
            <div class="tab-content">
                <div class="tab-pane active" id="tab-animated-0" role="tabpanel">
                    <h6 class="m-0 font-weight-bold text-primary">Potensi : 1000 dari 1400 Tempat Usaha</h6>
                    <br>
                    <div class="table-responsive ">
                        <table
                            class="table"
                            id="tabelListrik"
                            width="100%"
                            cellspacing="0"
                            style="font-size:0.75rem;">
                            <thead class="table-bordered">
                                <tr>
                                    <th>Kontrol</th>
                                    <th>Nama</th>
                                    <th>Los</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab-animated-1" role="tabpanel">
                Air Bersih
                </div>
                <div class="tab-pane" id="tab-animated-2" role="tabpanel">
                Keamanan IPK
                </div>
                <div class="tab-pane" id="tab-animated-3" role="tabpanel">
                Kebersihan
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection

@section('js')
<!-- Tambah Content pada Body JS -->
@endsection