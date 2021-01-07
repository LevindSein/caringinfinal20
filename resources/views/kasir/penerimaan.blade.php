<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Penerimaan Kasir</title>
        <link rel="stylesheet" href="{{asset('css/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <header class="clearfix">
                <h1>PENERIMAAN HARIAN KASIR</h1>
                <h2 style="text-align:center;">Rekap Penerimaan</h2>
                <div id="company" class="clearfix">
                    <div>PT. Pengelola Pusat Perdagangan Caringin</div>
                    <div>Jl. Soekarno Hatta No. 220 Blok A1 No. 21-24<br/>
                        Pasar Induk Caringin, Bandung</div>
                    <div>(022) 540-4556</div>
                </div>
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
                    <tr>
                        <th class="tg-r8fv" rowspan="3">Blok</th>
                        <th class="tg-r8fv" colspan="7">Penerimaan Tunai</th>
                        <th class="tg-r8fv" rowspan="3">Jumlah</th>
                        <th class="tg-r8fv" rowspan="3">Diskon/<br>Bebas Bayar</th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv" rowspan="2">Listrik</th>
                        <th class="tg-r8fv" rowspan="2">Air Bersih</th>
                        <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">Keamanan</th>
                        <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">Kebersihan</th>
                        <th class="tg-r8fv" colspan="2">Denda</th>
                        <th class="tg-r8fv" rowspan="2">Lain-Lain</th>
                    </tr>
                    <tr>
                        <th class="tg-ccvv">Listrik</th>
                        <th class="tg-ccvv">Air</th>
                    </tr>
                </table>
                <div id="notices">
                    <div style="text-align:right;">
                        <b>Bandung, {{date('d-m-Y',time())}}</b>
                    </div>
                    <br><br><br><br><br>
                    <div class="notice" style="text-align:right;">{{Session::get('username')}}</div>
                </div>
            </main>
        </div>
        <div style="page-break-before:always">
            <header class="clearfix">
                <h1>PENERIMAAN HARIAN KASIR</h1>
                <h2 style="text-align:center;">Rincian Penerimaan</h2>
                <div id="company" class="clearfix">
                    <div>PT. Pengelola Pusat Perdagangan Caringin</div>
                    <div>Jl. Soekarno Hatta No. 220 Blok A1 No. 21-24<br/>
                        Pasar Induk Caringin, Bandung</div>
                    <div>(022) 540-4556</div>
                </div>
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
                    <tr>
                        <th class="tg-r8fv" rowspan="3">Tgl Tagihan</th>
                        <th class="tg-r8fv" rowspan="3">Kontrol</th>
                        <th class="tg-r8fv" rowspan="3">Nama</th>
                        <th class="tg-r8fv" colspan="6">Penerimaan Tunai</th>
                        <th class="tg-r8fv" rowspan="3">Jumlah</th>
                        <th class="tg-r8fv" rowspan="3">Diskon/<br>Bebas Bayar</th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv" rowspan="2">Listrik</th>
                        <th class="tg-r8fv" rowspan="2">Air Bersih</th>
                        <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">Keamanan</th>
                        <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">Kebersihan</th>
                        <th class="tg-r8fv" colspan="2">Denda</th>
                    </tr>
                    <tr>
                        <th class="tg-ccvv">Listrik</th>
                        <th class="tg-ccvv">Air</th>
                    </tr>
                </table>
                <div id="notices">
                    <div style="text-align:right;">
                        <b>Bandung, {{date('d-m-Y',time())}}</b>
                    </div>
                    <br><br><br><br><br>
                    <div class="notice" style="text-align:right;">{{Session::get('username')}}</div>
                </div>
            </main>
        </div>
    </body>
</html>