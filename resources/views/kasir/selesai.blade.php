<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Selesai Tagihan</title>
        <link rel="stylesheet" href="{{asset('css/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <header class="clearfix">
                <h2 style="text-align:center;">Rekap Selesai Tagihan<br>{{$bulan}}</h2>
            </header>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th class="tg-r8fv">No</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Nama</th>
                            <th class="tg-r8fv" style="width:5%">No.Los</th>
                            <th class="tg-r8fv">Listrik</th>
                            <th class="tg-r8fv">Air Bersih</th>
                            <th class="tg-r8fv">Keamanan IPK</th>
                            <th class="tg-r8fv">Kebersihan</th>
                            <th class="tg-r8fv">Air Kotor</th>
                            <th class="tg-r8fv">Lain Lain</th>
                            <th class="tg-r8fv">Jumlah</th>
                            <th class="tg-r8fv">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="12" style="height:1px"></th>
                        </tr>
                    </thead>
                    <?php $i = 1; ?>
                    <tbody>
                    @foreach($dataset as $d)
                        <tr>
                            <td class="tg-r8fz"><b>{{$i}}</b></td>
                            <td class="tg-r8fz">{{$d['kd_kontrol']}}</td>
                            <td class="tg-r8fy">{{substr($d['pengguna'],0,13)}}</td>
                            <td class="tg-r8fz" style="white-space:normal;">{{$d['los']}}</td>
                            <td class="tg-r8fx">{{number_format($d['listrik'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['airbersih'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['keamananipk'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['kebersihan'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['airkotor'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['lain'])}}</td>
                            <td class="tg-r8fx"><b>{{number_format($d['total'])}}</b></td>
                            <td class="tg-r8fy" style="white-space:normal;">{{$d['lokasi']}}</td>
                        </tr>
                    <?php $i++; ?>
                    @endforeach
                    </tbody>
                </table>
            </main>
        </div>
    </body>
</html>