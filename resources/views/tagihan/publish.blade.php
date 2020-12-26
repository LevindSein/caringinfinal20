<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Aplikasi PT. Pengelola Pusat Perdagangan Caringin di Kota Bandung">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        
        <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
        
        <!-- Custom fonts for this template -->
        <link
            href="{{asset('vendor/fontawesome-free/css/all.min.css')}}"
            rel="stylesheet"
            type="text/css">
        <link
            href="{{asset('https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i')}}"
            rel="stylesheet">
        
        <!-- Custom styles for this template -->
        <link href="{{asset('css/sb-admin-2-copy.min.css')}}" rel="stylesheet">

        <link href="{{asset('vendor/datatables/fixedHeader.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/fixed-columns.min.css')}}" rel="stylesheet">

        <!-- Custom styles for this page -->
        <link
            href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}"
            rel="stylesheet">

        <link rel="icon" href="{{asset('img/logo.png')}}">
        <title>Publish Tagihan | BP3C</title>

        <script src="{{asset('js/animate.min.js')}}"></script>
        
        <link rel="stylesheet" href="{{asset('vendor/select2/select2.min.css')}}"/>
        <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
    </head>

    <body id="page-top">   
        <div class="se-pre-con"> </div>
        <div class = "container-fluid">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table
                            class="table"
                            id="tabelPublish"
                            width="100%"
                            cellspacing="0"
                            style="font-size:0.75rem;">
                            <thead class="table-bordered">
                                <tr>
                                    <th colspan="12" style="width:100%">Publish</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="width:5%">Kontrol</th>
                                    <th rowspan="2" style="width:5%">Pengguna</th>
                                    <th colspan="3" style="width:15%" class="listrik">Listrik</th>
                                    <th colspan="2" style="width:10%" class="air">Air Bersih</th>
                                    <th rowspan="2" style="width:5%" class="keamanan">K.aman IPK</th>
                                    <th rowspan="2" style="width:5%" class="kebersihan">Kebersihan</th>
                                    <th rowspan="2" style="width:5%;background-color:rgba(50, 255, 255, 0.2);">A.Kotor</th>
                                    <th rowspan="2" style="width:5%;background-color:rgba(255, 50, 255, 0.2);">Lain&#178;</th>
                                    <th rowspan="2" style="width:5%;background-color:rgba(255, 212, 71, 0.2);">Jumlah</th>
                                </tr>
                                <tr>
                                    <th style="width:5%" class="listrik-hover">Daya</th>
                                    <th style="width:5%" class="listrik-hover">Pakai</th>
                                    <th style="width:5%" class="listrik-hover">Total</th>
                                    <th style="width:5%" class="air-hover">Pakai</th>
                                    <th style="width:5%" class="air-hover">Total</th>
                                </tr>
                            </thead>
                            <tbody class="table-bordered">
                                @foreach($dataset as $d)
                                <tr>
                                    <td style="width:5%" class="text-center">{{$d->kd_kontrol}}</td>
                                    <td style="width:5%" class="text-left">{{substr($d->nama,0,10)}}</td>
                                    <td style="width:5%">{{number_format($d->daya_listrik)}}</td>
                                    <td style="width:5%">{{number_format($d->pakai_listrik)}}</td>
                                    <td style="width:5%">{{number_format($d->ttl_listrik)}}</td>
                                    <td style="width:5%">{{number_format($d->pakai_airbersih)}}</td>
                                    <td style="width:5%">{{number_format($d->ttl_airbersih)}}</td>
                                    <td style="width:5%">{{number_format($d->ttl_keamananipk)}}</td>
                                    <td style="width:5%">{{number_format($d->ttl_kebersihan)}}</td>
                                    <td style="width:5%">{{number_format($d->ttl_airkotor)}}</td>
                                    <td style="width:5%">{{number_format($d->ttl_lain)}}</td>
                                    <td style="width:5%">{{number_format($d->ttl_tagihan)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>    
        </div>

        <script>
            $(document).ready(function () {
                $(
                    '#tabelPublish'
                ).DataTable({
                    "scrollCollapse": true,
                    "deferRender": true,
                    "dom": "r<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",
                    fixedHeader: {
                        header: true,
                    },
                    "paging":false,
                    "ordering":false,
                }).draw();
            });
        </script>

        <script>
            $(window).load(function () {
                $(".se-pre-con")
                    .fadeIn("slow")
                    .fadeOut("slow");;
            });
        </script>

        <!-- Bootstrap core JavaScript-->
        <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- <script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script> -->

        <!-- Core plugin JavaScript-->
        <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
        <script src="{{asset('js/autocomplete.js')}}"></script>

        <!-- Page level plugins -->
        <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

        <!-- Page level custom scripts -->
        <script src="{{asset('js/demo/datatables-demo.js')}}"></script>

        <!-- Button -->
        <script src="{{asset('js/datatables.buttons.min.js')}}"></script>
        <script src="{{asset('js/jszip.min.js')}}"></script>
        <script src="{{asset('js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>

        <script src="{{asset('vendor/datatables/fixedHeader.min.js')}}"></script>
        <script src="{{asset('js/fixed-columns.min.js')}}"></script>

        <!--for column table toggle-->
        <script>
            $(document).ready(function() {
                $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                } ); 
            });
        </script>
        
        @yield('js')
        
    </body>
</html>