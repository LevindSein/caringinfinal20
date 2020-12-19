<?php
$role = Session::get('role');
date_default_timezone_set('Asia/Jakarta');
$sekarang = date("d-m-Y H:i:s",time());
?>

@extends( $role == 'master' ? 'layout.master' : 'layout.admin')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Riwayat Login | BP3C</title>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Login</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table 
                    class="table table-bordered" 
                    id="tabelLog"
                    cellspacing="0"
                    width="100%"
                    style="font-size:0.75rem;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>KTP</th>
                            <th>HP</th>
                            <th>Role</th>
                            <th>Platform</th>
                            <th>Login</th>
                        </tr>
                    </thead>
                </table>
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
<script src="{{asset('js/log.js')}}"></script>
@endsection