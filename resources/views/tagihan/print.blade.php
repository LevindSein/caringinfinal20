<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>FORM ALAT METERAN</title>
        <link rel="icon" href="{{asset('img/logo.png')}}">
        <link rel="stylesheet" href="{{asset('css/style-bulanan.css')}}" media="all" />
    </head>
  
    <body onload="window.print()">
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <h2 style="text-align:center;">FORM METERAN LISTRIK</h2>
        @foreach($dataset[0] as $data)
        <h3>{{$data[0]}}</h3>
        <main>
            <table class="tg">
                <tr>
                    <th class="tg-r8fv">No.</th>
                    <th class="tg-r8fv">Kontrol</th>
                    <th class="tg-r8fv">Pengguna</th>
                    <th class="tg-r8fv">Nomor</th>
                    <th class="tg-r8fv">Lalu</th>
                    <th class="tg-r8fv">Baru</th>
                    <th class="tg-r8fv">Ket.</th>
                </tr>
                <?php $x=1; ?>
                @foreach($data[1] as $d)
                <tr>
                    <td class="tg-cegc">{{$x}}</td>
                    <td class="tg-cegc">{{$d->kontrol}}</td>
                    <td class="tg-g25h" style="text-align:left;">{{$d->nama}}</td>
                    <td class="tg-g25h" style="text-align:center;">{{($d->nomor)}}</td>
                    <td class="tg-g25h" style="text-align:right;">{{number_format($d->lalu)}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <?php $x++; ?>
                @endforeach
            </table>
        </main>
        <div style="page-break-before:always;"></div>
        @endforeach
        @else
        <h2 style="text-align:center; page-break-before:always;">FORM METERAN AIR</h2>
        @foreach($dataset[1] as $data)
        <br>
        <h3>{{$data[0]}}</h3>
        <main>
            <table class="tg">
                <tr>
                    <th class="tg-r8fv">No.</th>
                    <th class="tg-r8fv">Kontrol</th>
                    <th class="tg-r8fv">Pengguna</th>
                    <th class="tg-r8fv">Nomor</th>
                    <th class="tg-r8fv">Lalu</th>
                    <th class="tg-r8fv">Baru</th>
                    <th class="tg-r8fv">Ket.</th>
                </tr>
                <?php $x=1; ?>
                @foreach($data[1] as $d)
                <tr>
                    <td class="tg-cegc">{{$x}}</td>
                    <td class="tg-cegc">{{$d->kontrol}}</td>
                    <td class="tg-g25h" style="text-align:left;">{{$d->nama}}</td>
                    <td class="tg-g25h" style="text-align:center;">{{($d->nomor)}}</td>
                    <td class="tg-g25h" style="text-align:right;">{{number_format($d->lalu)}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <?php $x++; ?>
                @endforeach
            </table>
        </main>
        @endforeach
        @endif
        @endfor
    </body>
</html>