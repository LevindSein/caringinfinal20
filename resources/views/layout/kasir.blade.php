<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Kasir | BP3C</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Sistem Kasir untuk Pembayaran Tagihan Nasabah atau Pedagang yang ada di Pasar Caringin">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        
        <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
        
		<link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
        <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="{{asset('vendors/base/vendor.bundle.base.css')}}">
		<link rel="stylesheet" href="{{asset('css/style.min.css')}}">
        <link rel="icon" href="{{asset('img/logo.png')}}">

        <!-- Custom styles for this page -->
        <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('vendor/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet">

        <link rel="icon" href="{{asset('img/logo.png')}}">

        <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
        <script src="{{asset('https://rawgit.com/sitepoint-editors/jsqrcode/master/src/qr_packed.js')}}"></script>
        <script src="{{asset('js/animate.min.js')}}"></script>

        <link rel="stylesheet" href="{{asset('vendor/select2/select2.min.css')}}"/>
        <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
	</head>
	<body>
        <div class="se-pre-con"></div>
		<div class="container-scroller">
			<div class="horizontal-menu">
				<nav class="navbar top-navbar col-lg-12 col-12 p-0">
					<div class="container-fluid">
						<div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
							<div class="text-center navbar-brand-wrapper d-flex align-items-center">
								<a class="navbar-brand brand-logo" href="index.html"><img src="{{asset('images/kasir.png')}}" alt="logo"/></a>
								<a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{asset('images/kasir.png')}}" alt="logo"/></a>
                            </div>
							<ul class="navbar-nav navbar-nav-right">
                                <li class="nav-item ml-0 mr-5 d-lg-flex">
                                    <button type="button" id="workasir" class="btn btn-sm">Status</button>
                                </li>
								<li class="nav-item nav-profile dropdown">
									<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
										<span class="nav-profile-name">{{Session::get('username')}}</span>
										<span class="online-status"></span>
										<img src="{{asset('img/icon_user.png')}}" alt="profile"/>
									</a>
									<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                        @if(Session::get('mode') == 'harian')
                                        <a class="dropdown-item"
                                            href="{{url('kasir/mode/bulanan')}}">
											<i class="mdi mdi-sync text-primary"></i>
											Mode Bulanan
										</a>
                                        <hr>
                                        @else
                                        <a class="dropdown-item"
                                            href="{{url('kasir/mode/harian')}}">
											<i class="mdi mdi-sync text-primary"></i>
											Mode Harian
										</a>
                                        <hr>
                                        <a class="dropdown-item"
                                            data-toggle="modal"
                                            data-target="#mySisa">
											<i class="mdi mdi-book-minus text-primary"></i>
											Rekap Sisa
										</a>
                                        <!-- <a class="dropdown-item"
                                            target="_blank"
                                            href="{{url('kasir/selesai')}}">
											<i class="mdi mdi-book-plus text-primary"></i>
											Rekap Selesai
                                        </a> -->
                                        @endif
                                        <a class="dropdown-item"
                                            data-toggle="modal"
                                            data-target="#myUtama">
											<i class="mdi mdi-database text-primary"></i>
											Rekap Pendapatan
										</a>
                                        <hr>
                                        <a class="dropdown-item"
                                            href="{{url('kasir/settings')}}">
                                            <i class="mdi mdi-settings text-primary"></i>
                                            Settings
                                        </a>
                                        <a 
                                            class="dropdown-item"
                                            data-toggle="modal"
                                            data-target="#logoutModal">
											<i class="mdi mdi-logout text-primary"></i>
											Logout
										</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</nav>
			</div>
			<div class="container-fluid page-body-wrapper">
				<div class="main-panel">
					<div class="content-wrapper">
						<div class="row mt-4">
							<div class="col-lg-12 grid-margin stretch-card">
								<div class="card shadow">
									<div class="card-body">
										@yield('content')
									</div>
								</div>
							</div>
						</div>
					</div>
					<footer class="footer">
						<div class="footer-wrap">
							<div class="d-sm-flex justify-content-center justify-content-sm-between">
								<span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright &copy;2020</span>
								<span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">PT Pengelola Pusat Perdagangan Caringin Bandung</span>
							</div>
						</div>
					</footer>
				</div>
			</div>
        </div>
        
        <div
            class="modal fade"
            id="myUtama"
            tabIndex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pencarian Rekap</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form class="user" action="{{url('kasir/utama')}}" target="_blank" method="GET">
                        <div class="modal-body-short">
                            <div class="form-group col-lg-12">
                                <br>
                                <input
                                    required
                                    placeholder="Masukkan Tanggal Rekap" class="form-control" type="text" onfocus="(this.type='date')"
                                    autocomplete="off"
                                    type="date"
                                    name="tgl_utama"
                                    id="tgl_utama">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="mySisa"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rekap Sisa Tagihan</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form class="user" action="{{url('kasir/sisa')}}" target="_blank" method="GET">
                        <div class="modal-body-short">
                            <div class="form-group col-lg-12">
                                <label for="sisatagihan">Pilih Rekap Sisa Tagihan</label>
                                <select class="form-control" name="sisatagihan" id="sisatagihan" required>
                                    <option selected value="all">Semua</option>
                                    <option value="sebagian">Sebagian</option>
                                </select>
                                <div class="form-group col-lg-12" style="display:none;" id="divrekapsisa">
                                    <br>
                                    <label for="sebagian">Blok <span style="color:red;">*</span></label>
                                    <div class="form-group">
                                        <select class="sebagian" name="sebagian[]" id="sebagian" required multiple></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="logoutModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin untuk logout?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body-short">Pilih "Logout" di bawah ini jika anda siap untuk mengakhiri sesi anda saat ini.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="{{url('logout')}}">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        @yield('modal')

        <script>
            $(window).on('load',function () {
                $(".se-pre-con")
                    .fadeIn("fast")
                    .fadeOut("fast");;
            });
            
            $('#sebagian').select2({
                placeholder: '--- Pilih Blok ---',
                ajax: {
                    url: "/cari/blok",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (blok) {
                        return {
                        results:  $.map(blok, function (bl) {
                            return {
                            text: bl.nama,
                            id: bl.nama
                            }
                        })
                        };
                    },
                    cache: true
                }
            });

            $("#sisatagihan").change(function() {
                var val = $(this).val();
                if(val === "all") {
                    $("#divrekapsisa").hide();
                    $("#sebagian").prop('required',false);
                    $('#sebagian').val('');
                    $('#sebagian').html('');
                }
                else if(val === "sebagian") {
                    $("#divrekapsisa").show();
                    $("#sebagian").prop('required',true);
                }
            });
        </script>
        <script src="{{asset('js/work.js')}}"></script>
        
        <!-- Core plugin JavaScript-->
        <script src="{{asset('vendors/base/vendor.bundle.base.js')}}"></script>

        <!-- Page level plugins -->
        <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/responsive.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/responsiveBootstrap.min.js')}}"></script>

        <!--for column table toggle-->
        <script>
            $(document).ready(function() {
                $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                } ); 
            });
        </script>
        <script>
            jQuery.fn.dataTable.Api.register('processing()', function (show) {
                return this.iterator('table', function (ctx) {
                    ctx.oApi._fnProcessingDisplay(ctx, show);
                });
            });
        </script>
        
        @yield('js')
	</body>
</html>