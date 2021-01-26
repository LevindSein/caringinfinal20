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
        <div id="process" style="display:none;text-align:center;">
            <p>Please Wait, Updating <img src="{{asset('img/updating.gif')}}"/></p>
        </div>
        <br>
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
                            <thead>
                                <tr>
                                    <th colspan="2"></th>
                                    <th colspan="15">Halaman ini berfungsi untuk melakukan verifikasi tagihan yang akan di publish. Harap pastikan dan apabila terdapat kesalahan, lakukan edit data sebelum publish dilakukan</th>
                                    <th colspan="3">
                                        <button
                                            id="publish"
                                            type="submit"
                                            class="publish btn btn-danger"><b>
                                            <i class="fas fa-fw fa-paper-plane fa-sm text-white-50"></i> PUBLISH NOW</b>
                                        </button>
                                    </th>
                                </tr>
                                <tr>
                                    <th rowspan="2">Kontrol</th>
                                    <th rowspan="2">Pengguna</th>
                                    <th colspan="5" class="listrik">Listrik</th>
                                    <th colspan="4" class="air">Air Bersih</th>
                                    <th colspan="2" class="keamanan">K.aman IPK</th>
                                    <th colspan="2" class="kebersihan">Kebersihan</th>
                                    <th rowspan="2" style="background-color:rgba(50, 255, 255, 0.2);">A.Kotor</th>
                                    <th rowspan="2" style="background-color:rgba(255, 50, 255, 0.2);">Lain&#178;</th>
                                    <th rowspan="2" style="background-color:rgba(255, 212, 71, 0.2);">Jumlah</th>
                                    <th rowspan="2">Keterangan</th>
                                    <th rowspan="2">Review</th>
                                </tr>
                                <tr>
                                    <th class="listrik-hover">Daya</th>
                                    <th class="listrik-hover">Lalu</th>
                                    <th class="listrik-hover">Baru</th>
                                    <th class="listrik-hover">Pakai</th>
                                    <th class="listrik-hover">Total</th>
                                    <th class="air-hover">Lalu</th>
                                    <th class="air-hover">Baru</th>
                                    <th class="air-hover">Pakai</th>
                                    <th class="air-hover">Total</th>
                                    <th class="keamanan">Disc</th>
                                    <th class="keamanan">Total</th>
                                    <th class="kebersihan">Disc</th>
                                    <th class="kebersihan">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>    
        </div>

        <div
            class="modal fade"
            id="myModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body-short">
                        <div id="divBulan1">
                            <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                                <div>
                                    <h5><strong><span id="bulan1"></span></strong></h5>
                                </div>
                                <div>
                                    <h5><strong><span id="totalbulan1"></span></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div id="divBulan2">
                            <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                                <div>
                                    <h5><strong><span id="bulan2"></span></strong></h5>
                                </div>
                                <div>
                                    <h5><strong><span id="totalbulan2"></span></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div id="divBulan3">
                            <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                                <div>
                                    <h5><strong><span id="bulan3"></span></strong></h5>
                                </div>
                                <div>
                                    <h5><strong><span id="totalbulan3"></span></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div id="divBulan4">
                            <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                                <div>
                                    <h5><strong><span id="bulan4"></span></strong></h5>
                                </div>
                                <div>
                                    <h5><strong><span id="totalbulan4"></span></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div id="divBulan5">
                            <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                                <div>
                                    <h5><strong><span id="bulan5"></span></strong></h5>
                                </div>
                                <div>
                                    <h5><strong><span id="totalbulan5"></span></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div id="divBulan6">
                            <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                                <div>
                                    <h5><strong><span id="bulan6"></span></strong></h5>
                                </div>
                                <div>
                                    <h5><strong><span id="totalbulan6"></span></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                            <div>
                                <h4><strong><span id="bulanini" style="color:#3f6ad8;"></span></strong></h4>
                            </div>
                            <div>
                                <h4><strong><span id="totalbulanini" style="color:#3f6ad8;"></span></strong></h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="okbutton btn btn-primary btn-sm" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="myChecking"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Checking . . .</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form_checking">
                        @csrf
                        <div class="modal-body-short">
                            <div class="form-group col-lg-12">
                                <label for="pesan">Pesan</label>
                                <textarea autocomplete="off" name="pesan" class="form-control" id="pesan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="hidden_id" name="hidden_id"/>
                            <input type="submit" class="btn btn-primary btn-sm" value="Checking"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="{{asset('js/publish.js')}}"></script>

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