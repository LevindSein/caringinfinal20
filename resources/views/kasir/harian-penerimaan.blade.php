<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Penerimaan Kasir | Mode Harian</title>
        <link rel="stylesheet" href="{{asset('css/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <header class="clearfix">
                <h2 style="text-align:center;">Penerimaan Harian</h2>
                <div id="project">
                    <div>
                        <span>Nama Kasir</span>:
                        {{Session::get('username')}}</div>
                    <div>
                        <span>Tanggal Bayar</span>:
                        {{$tanggal}}</div>
                </div>
            </header>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th class="tg-r8fv" rowspan="2">No</th>
                            <th class="tg-r8fv" rowspan="2">Nama</th>
                            <th class="tg-r8fv" colspan="2">LOS</th>
                            <th class="tg-r8fv" colspan="2">POS</th>
                            <th class="tg-r8fv" rowspan="2">Abonemen</th>
                            <th class="tg-r8fv" rowspan="2">Total</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">Keamanan</th>
                            <th class="tg-r8fv">Kebersihan</th>
                            <th class="tg-r8fv">Kebersihan</th>
                            <th class="tg-r8fv">> Kebersihan</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="8" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $i = 1; 
                    $keamanan_los = 0;
                    $kebersihan_los = 0;
                    $kebersihan_pos = 0;
                    $kebersihan_lebih_pos = 0;
                    $abonemen = 0;
                    $total = 0;?>
                    @foreach($dataset as $d)
                        <tr>
                            <td class="tg-r8fz"><b>{{$i}}</b></td>
                            <td class="tg-r8fz">{{$d->nama}}</td>
                            <td class="tg-r8fx">{{number_format($d->keamanan_los)}}</td>
                            <td class="tg-r8fx">{{number_format($d->kebersihan_los)}}</td>
                            <td class="tg-r8fx">{{number_format($d->kebersihan_pos)}}</td>
                            <td class="tg-r8fx">{{number_format($d->kebersihan_lebih_pos)}}</td>
                            <td class="tg-r8fx">{{number_format($d->abonemen)}}</td>
                            <td class="tg-r8fx">{{number_format($d->total)}}</td>
                        </tr>
                    <?php 
                    $i++;
                    $keamanan_los = $keamanan_los + $d->keamanan_los;
                    $kebersihan_los = $kebersihan_los + $d->kebersihan_los;
                    $kebersihan_pos = $kebersihan_pos + $d->kebersihan_pos;
                    $kebersihan_lebih_pos = $kebersihan_lebih_pos + $d->kebersihan_lebih_pos;
                    $abonemen = $abonemen + $d->abonemen;
                    $total = $total + $d->total;
                    ?>
                    @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="8" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="2"><b>Total<b></td>
                        <td class="tg-r8fx"><b>{{number_format($keamanan_los)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($kebersihan_los)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($kebersihan_pos)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($kebersihan_lebih_pos)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($abonemen)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($total)}}</b></td>
                    </tr>
                </table>
                <div id="notices">
                    <div style="text-align:right;">
                        <b>Bandung, {{$cetak}}</b>
                    </div>
                    <br><br><br><br><br>
                    <div class="notice" style="text-align:right;">{{Session::get('username')}}</div>
                </div>
            </main>
        </div>
    </body>
</html>