<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pemakaian Kebersihan | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/style-pemakaian1.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>
    <body onload="window.print()">  
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <h2 style="text-align:center;">REKAP PEMAKAIAN KEBERSIHAN<br>{{$bln}}</h2>
        <main>
            <table class="tg">
                <tr>
                    <th class="tg-r8fv">No.</th>
                    <th class="tg-r8fv">Blok</th>
                    <th class="tg-r8fv">Jumlah Los</th>
                    <th class="tg-r8fv">Subtotal</th>
                    <th class="tg-r8fv">Diskon</th>
                    <th class="tg-r8fv">Tagihan</th>
                    <th class="tg-r8fv">Realisasi</th>
                    <th class="tg-r8fv">Selisih</th>
                </tr>
                <?php $no = 1; ?>
                @foreach($rekap as $d)
                <tr>
                    <td class="tg-cegc">{{$no}}</td>
                    <td class="tg-cegc">{{$d->blok}}</td>
                    <td class="tg-cegc">{{$d->pengguna}}</td>
                    <td class="tg-g25h">{{number_format($d->subtotal)}}</td>
                    <td class="tg-g25h">{{number_format($d->diskon)}}</td>
                    <td class="tg-g25h">{{number_format($d->tagihan)}}</td>
                    <td class="tg-g25h">{{number_format($d->realisasi)}}</td>
                    <td class="tg-g25h">{{number_format($d->selisih)}}</td>
                </tr>
                <?php $no++; ?>
                @endforeach
                <tr>
                    <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                    <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekap[0])}}</td>
                    <td class="tg-8m6k">{{number_format($ttlRekap[1])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[2])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[3])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[4])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[5])}}</td>
                </tr>
            </table>
        </main>
        @else
        <h2 style="text-align:center;page-break-before:always">RINCIAN PEMAKAIAN KEBERSIHAN<br>{{$bln}}</h2>
        @foreach($rincian as $data)
        <div>
            <br>
            <h3>{{$data[0]}}</h3>
            <main>
                <table class="tg">
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Kontrol</th>
                        <th class="tg-r8fv">Pengguna</th>
                        <th class="tg-r8fv">Jml Los</th>
                        <th class="tg-r8fv">Subtotal</th>
                        <th class="tg-r8fv">Diskon</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <th class="tg-r8fv">Realisasi</th>
                        <th class="tg-r8fv">Selisih</th>
                    </tr>
                    <?php $no = 1; ?>
                    @foreach($data[1] as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->kontrol}}</td>
                        <td class="tg-cegc" style="text-align:left;white-space: normal;">{{$d->pengguna}}</td>
                        <td class="tg-cegc">{{number_format($d->jumlah)}}</td>
                        <td class="tg-cegc">{{number_format($d->subtotal)}}</td>
                        <td class="tg-cegc">{{number_format($d->diskon)}}</td>
                        <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                        <td class="tg-cegc">{{number_format($d->realisasi)}}</td>
                        <td class="tg-cegc">{{number_format($d->selisih)}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    @foreach($data[2] as $d)
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="3">Total</td>
                        <td class="tg-8m6k">{{number_format($d->jumlah)}}</td>
                        <td class="tg-8m6k">{{number_format($d->subtotal)}}</td>
                        <td class="tg-8m6k">{{number_format($d->diskon)}}</td>
                        <td class="tg-8m6k">{{number_format($d->tagihan)}}</td>
                        <td class="tg-8m6k">{{number_format($d->realisasi)}}</td>
                        <td class="tg-8m6k">{{number_format($d->selisih)}}</td>
                    </tr>
                    @endforeach
                </table>
            </main>
        </div>
        @endforeach
        @endif
        @endfor
    </body>
</html>