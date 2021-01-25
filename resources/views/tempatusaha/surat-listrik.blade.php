@extends('layout.master')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

<?php
    use App\Models\IndoDate;
?>
@section('content')
<!-- Tambah Content Pada Body Utama -->
<div class = "container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <title>Surat Listrik | BP3C</title>
            <h6 class="m-0 font-weight-bold text-primary">Surat Alat Listrik</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive ">
                <table
                    class="table"
                    id="tabelSurat"
                    width="100%"
                    cellspacing="0"
                    style="font-size:0.75rem;">
                    <thead class="table-bordered">
                        <tr>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Kontrol</th>
                            <th rowspan="2">Nama</th>
                            <th colspan="6">Surat</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Permohonan</th>
                            <th>Pasang</th>
                            <th>Acara</th>
                            <th>Pembayaran</th>
                            <th>Peringatan</th>
                            <th>Bongkar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dataset as $d)
                        <tr>
                            <td style="text-align:center">{{IndoDate::tanggal($d->tgl_tagihan," ")}}</td>
                            <td style="text-align:center">{{$d->kd_kontrol}}</td>
                            <td style="text-align:center">{{$d->nama}}</td>
                            <td style="text-align:center">
                                @if($d->srt_permohonan !== NULL)
                                <a
                                    href="javascript:void(0)" 
                                    onclick="location.href='{{url('download/bg1',[$d->id])}}'" 
                                    type="submit" 
                                    class="btn btn-sm btn-primary">Download</a>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($d->srt_pasang !== NULL)
                                <a
                                    href="javascript:void(0)" 
                                    onclick="location.href='{{url('download/bg4',[$d->id])}}'" 
                                    type="submit" 
                                    class="btn btn-sm btn-primary">Download</a>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($d->srt_acara !== NULL)
                                <a
                                    href="javascript:void(0)" 
                                    onclick="location.href='{{url('download/bg3',[$d->id])}}'" 
                                    type="submit" 
                                    class="btn btn-sm btn-primary">Download</a>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($d->srt_bayar !== NULL)
                                <a
                                    href="javascript:void(0)" 
                                    onclick="location.href='{{url('download/pb',[$d->id])}}'" 
                                    type="submit" 
                                    class="btn btn-sm btn-primary">Download</a>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($d->srt_peringatan !== NULL)
                                {{$d->kd_kontrol}}
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($d->srt_bongkar !== NULL)
                                {{$d->kd_kontrol}}
                                @endif
                            </td>
                            <td style="text-align:center">{{$d->keterangan}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>
@endsection

@section('js')
<!-- Tambah Content pada Body JS -->
<script>
$('#tabelSurat').DataTable({
    "processing": true,
    "bProcessing": true,
    "language": {
        'loadingRecords': '&nbsp;',
        'processing': '<i class="fas fa-spinner"></i>'
    },
    "deferRender": true,
    "fixedColumns":   {
        "leftColumns": 3,
        "rightColumns": 1,
    },
    "scrollX": true,
    "scrollCollapse": true,
    "pageLength": 8,
    "order": [ 0, "asc" ]
});
</script>
@endsection