<?php 
use App\Models\IndoDate;

function indoDate($tanggal){
    return IndoDate::bulan($tanggal,' ');
}
?>

@extends(Session::get('role') == 'master' ? 'layout.master' : (Session::get('role') == 'admin' ? 'layout.admin' : 'layout.manajer'))
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<title>Pemakaian Bulanan | BP3C</title>
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Pemakaian Bulanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table
                    class="table display table-bordered"
                    id="pemakaian"
                    width="100%"
                    cellspacing="0"
                    style="font-size:0.75rem;">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Print</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($dataset as $d)
                        <tr>
                            <td class="text-center" <?php $bulan = indoDate($d->bln_pakai); ?>>{{$bulan}}</td>
                            <td class="text-center">
                                <a
                                    id="listrik"
                                    href="{{url('rekap/pemakaian',['listrik',$d->bln_pakai])}}"
                                    target="_blank"
                                    class="btn btn-light"
                                    title="Listrik">
                                    <i class="fas fa-bolt" style="color:#fd7e14;"></i></a>
                                &nbsp;
                                <a
                                    id="airbersih"
                                    href="{{url('rekap/pemakaian',['airbersih',$d->bln_pakai])}}"
                                    target="_blank"
                                    class="btn btn-light"
                                    title="Air Bersih">
                                    <i class="fas fa-tint" style="color:#36b9cc"></i></a>
                                &nbsp;
                                <a
                                    id="keamananipk"
                                    href="{{url('rekap/pemakaian',['keamananipk',$d->bln_pakai])}}"
                                    target="_blank"
                                    class="btn btn-light"
                                    title="Keamanan & IPK">
                                    <i class="fas fa-lock" style="color:#e74a3b"></i></a>
                                &nbsp;
                                <a
                                    id="kebersihan"
                                    href="{{url('rekap/pemakaian',['kebersihan',$d->bln_pakai])}}"
                                    target="_blank"
                                    class="btn btn-light"
                                    title="Kebersihan">
                                    <i class="fas fa-leaf" style="color:#1cc88a;"></i></a>
                                &nbsp;
                                <a
                                    id="airkotor"
                                    href="{{url('rekap/pemakaian',['airkotor',$d->bln_pakai])}}"
                                    target="_blank"
                                    class="btn btn-light"
                                    title="Air Kotor">
                                    <i class="fas fa-fill-drip" style="color:#5a5c69;"></i></a>
                                &nbsp;
                                <a
                                    id="lain"
                                    href="{{url('rekap/pemakaian',['lain',$d->bln_pakai])}}"
                                    target="_blank"
                                    class="btn btn-light"
                                    title="Lain - Lain">
                                    <i class="fas fa-credit-card" style="color:#6610f2;"></i></a>
                                &nbsp;
                                <a
                                    id="total"
                                    href="{{url('rekap/pemakaian',['total',$d->bln_pakai])}}"
                                    target="_blank"
                                    class="btn btn-light"
                                    title="Total Pemakaian">
                                    <i class="fas fa-dollar-sign" style="color:#f6c23e;"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
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

<script>
    $(document).ready(function () {
        $(
            '#pemakaian'
        ).DataTable({
            "processing": true,
            "bProcessing": true,
            "language": {
                'loadingRecords': '&nbsp;',
                'processing': '<i class="fas fa-spinner"></i>'
            },
            pageLength:5,
            "scrollX": true,
            "bSortable": false,
            "deferRender": true,
            "ordering": false
        });

        $('#airkotor').click(function(e) {
            e.preventDefault();
            alert('Under Construction');
        });
        
        $('#lain').click(function(e) {
            e.preventDefault();
            alert('Under Construction');
        });
    });
</script>
@endsection