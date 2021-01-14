<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Rekap Pendapatan | Kasir</title>
        <link rel="stylesheet" href="{{asset('css/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <header class="clearfix">
                <h2 style="text-align:center;">Rekap Pendapatan</h2>
                <div id="project">
                    <div>
                        <span>Nama Perekap</span>:
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
                            <th class="tg-r8fv">No</th>
                            <th class="tg-r8fv">Kasir</th>
                            <th class="tg-r8fv">Bulanan</th>
                            <th class="tg-r8fv">Harian</th>
                            <th class="tg-r8fv">Jumlah</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="8" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $i = 1; 
                    $harian = 0;
                    $bulanan = 0;
                    $total = 0;?>
                    @foreach($dataset as $d)
                    @if($d['jumlah'] != 0)
                        <tr>
                            <td class="tg-r8fz"><b>{{$i}}</b></td>
                            <td class="tg-r8fz">{{$d['nama']}}</td>
                            <td class="tg-r8fx">{{number_format($d['bulanan'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['harian'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['jumlah'])}}</td>
                        </tr>
                    <?php 
                    $i++;
                    $harian = $harian + $d['harian'];
                    $bulanan = $bulanan = $d['bulanan'];
                    $total = $total + $d['jumlah'];
                    ?>
                    @endif
                    @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="8" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="2"><b>Total<b></td>
                        <td class="tg-r8fx"><b>Rp. {{number_format($bulanan)}}</b></td>
                        <td class="tg-r8fx"><b>Rp. {{number_format($harian)}}</b></td>
                        <td class="tg-r8fx"><b>Rp. {{number_format($total)}}</b></td>
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