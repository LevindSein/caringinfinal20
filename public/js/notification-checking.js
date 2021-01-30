$(document).ready(function(){
    $('#notification').show();
    $('#tabelTagihan').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/tagihan/notification",
            cache:false,
		},
		columns: [
            { data: 'kd_kontrol'     , name: 'kd_kontrol'     , class : 'text-center' },
            { data: 'nama'           , name: 'nama'           , class : 'text-center' },
            { data: 'daya_listrik'   , name: 'daya_listrik'   , class : 'text-center' },
            { data: 'awal_listrik'   , name: 'awal_listrik'   , class : 'text-center' },
            { data: 'akhir_listrik'  , name: 'akhir_listrik'  , class : 'text-center' },
            { data: 'pakai_listrik'  , name: 'pakai_listrik'  , class : 'text-center' },
            { data: 'ttl_listrik'    , name: 'ttl_listrik'    , class : 'text-center' },
            { data: 'awal_airbersih' , name: 'awal_airbersih' , class : 'text-center' },
            { data: 'akhir_airbersih', name: 'akhir_airbersih', class : 'text-center' },
            { data: 'pakai_airbersih', name: 'pakai_airbersih', class : 'text-center' },
            { data: 'ttl_airbersih'  , name: 'ttl_airbersih'  , class : 'text-center' },
            { data: 'ttl_keamananipk', name: 'ttl_keamananipk', class : 'text-center' },
            { data: 'ttl_kebersihan' , name: 'ttl_kebersihan' , class : 'text-center' },
            { data: 'ttl_airkotor'   , name: 'ttl_airkotor'   , class : 'text-center' },
            { data: 'ttl_lain'       , name: 'ttl_lain'       , class : 'text-center' },
            { data: 'ttl_tagihan'    , name: 'ttl_tagihan'    , class : 'text-center' },
            { data: 'action'         , name: 'action'         , class : 'text-center' },
        ],
        order: [[ 0, "asc" ]],
        stateSave: true,
        scrollX: true,
        deferRender: true,
        pageLength: 8,
        fixedColumns:   {
            "leftColumns": 2,
            "rightColumns": 2,
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [16] }, 
            { "bSearchable": false, "aTargets": [16] }
        ]
    });

    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
        $('#form_tagihan')[0].reset();
        $('#kontrol').val('');
        $('#pengguna').val('');
        $('#divEditListrik').hide();
        $('#divEditAirBersih').hide();
        $('#divEditKeamananIpk').hide();
        $('#divEditKebersihan').hide();
        $('#divEditAirKotor').hide();
        $('#divEditLain').hide();
        $('#stt_listrik').val('');
        $('#stt_airbersih').val('');
        $('#stt_keamananipk').val('');
        $('#stt_kebersihan').val('');
        $('#stt_airkotor').val('');
        $('#stt_lain').val('');
        $("#action_btn").prop("disabled", true);
		$.ajax({
			url :"/tagihan/"+id+"/edit",
            cache:false,
			dataType:"json",
			success:function(data)
			{
				$('#kontrol').val(data.result.kd_kontrol);
                $('#pengguna').val(data.result.nama);
                var listrik = 1;
                var air = 1;
                var keamananipk = 1;
                var kebersihan = 1;
                
                if(data.result.sub_listrik != 0 || data.result.listrik != null){
                    $('#divEditListrik').show();
                    $('#stt_listrik').val('ok');
                    if(data.result.daya_listrik != null)
                        $('#dayaListrik').val(data.result.daya_listrik.toLocaleString("en-US"));
                    if(data.result.awal_listrik != null)
                        $('#awalListrik').val(data.result.awal_listrik.toLocaleString("en-US"));
                    if(data.result.akhir_listrik != null)
                        $('#akhirListrik').val(data.result.akhir_listrik.toLocaleString("en-US"));

                    var daya = $('#dayaListrik').val();
                    daya = daya.split(',');
                    daya = daya.join('');
                    daya = parseInt(daya);

                    var awal = $('#awalListrik').val();
                    awal = awal.split(',');
                    awal = awal.join('');
                    awal = parseInt(awal); 
                
                    var akhir = $('#akhirListrik').val();
                    akhir = akhir.split(',');
                    akhir = akhir.join('');
                    akhir = parseInt(akhir);
                    
                    if(akhir >= awal && daya > 0){
                        listrik = 1;
                    }
                    else{
                        listrik = 0;
                    }

                    $("#dayaListrik,#awalListrik,#akhirListrik").on("change paste keyup", function() {
                        var daya = $('#dayaListrik').val();
                        daya = daya.split(',');
                        daya = daya.join('');
                        daya = parseInt(daya);

                        var awal = $('#awalListrik').val();
                        awal = awal.split(',');
                        awal = awal.join('');
                        awal = parseInt(awal); 
                    
                        var akhir = $('#akhirListrik').val();
                        akhir = akhir.split(',');
                        akhir = akhir.join('');
                        akhir = parseInt(akhir);

                        if(akhir >= awal && daya > 0){
                            listrik = 1;
                        }
                        else{
                            listrik = 0;
                        }
                    });
                }

                if(data.result.sub_airbersih !== 0 || data.result.airbersih != null){
                    $('#divEditAirBersih').show();
                    $('#stt_airbersih').val('ok');
                    if(data.result.awal_airbersih != null)
                        $('#awalAir').val(data.result.awal_airbersih.toLocaleString("en-US"));
                    if(data.result.akhir_airbersih != null)
                        $('#akhirAir').val(data.result.akhir_airbersih.toLocaleString("en-US"));

                    var awal = $('#awalAir').val();
                    awal = awal.split(',');
                    awal = awal.join('');
                    awal = parseInt(awal); 

                    var akhir = $('#akhirAir').val();
                    akhir = akhir.split(',');
                    akhir = akhir.join('');
                    akhir = parseInt(akhir);
                    
                    if(akhir >= awal){
                        air = 1;
                    }
                    else{
                        air = 0;
                    }

                    $("#akhirAir,#awalAir").on("change paste keyup", function() {
                        var akhir = $('#akhirAir').val();
                        akhir = akhir.split(',');
                        akhir = akhir.join('');
                        akhir = parseInt(akhir);

                        var awal = $('#awalAir').val();
                        awal = awal.split(',');
                        awal = awal.join('');
                        awal = parseInt(awal); 
                        
                        if(akhir >= awal){
                            air = 1;
                        }
                        else{
                            air = 0;
                        }
                    });
                }

                if(data.result.sub_keamananipk !== 0 || data.result.keamananipk != null){
                    $('#divEditKeamananIpk').show();
                    $('#stt_keamananipk').val('ok');
                    if(data.result.sub_keamananipk != null)
                        $('#keamananipk').val(data.result.sub_keamananipk.toLocaleString("en-US"));
                    if(data.result.dis_keamananipk != null)
                        $('#dis_keamananipk').val(data.result.dis_keamananipk.toLocaleString("en-US"));
                    var awal = $('#keamananipk').val();
                    awal = awal.split(',');
                    awal = awal.join('');
                    awal = parseInt(awal); 

                    var akhir = $('#dis_keamananipk').val();
                    akhir = akhir.split(',');
                    akhir = akhir.join('');
                    akhir = parseInt(akhir);
                    
                    if(akhir <= awal){
                        keamananipk = 1;
                    }
                    else{
                        keamananipk = 0;
                    }

                    $("#keamananipk,#dis_keamananipk").on("change paste keyup", function() {
                        var akhir = $('#dis_keamananipk').val();
                        akhir = akhir.split(',');
                        akhir = akhir.join('');
                        akhir = parseInt(akhir);

                        var awal = $('#keamananipk').val();
                        awal = awal.split(',');
                        awal = awal.join('');
                        awal = parseInt(awal); 
                        
                        if(akhir <= awal){
                            keamananipk = 1;
                        }
                        else{
                            keamananipk = 0;
                        }
                    });
                }
                
                if(data.result.sub_kebersihan !== 0 || data.result.kebersihan != null){
                    $('#divEditKebersihan').show();
                    $('#stt_kebersihan').val('ok');
                    if(data.result.sub_kebersihan != null)
                        $('#kebersihan').val(data.result.sub_kebersihan.toLocaleString("en-US"));
                    if(data.result.dis_kebersihan != null)
                        $('#dis_kebersihan').val(data.result.dis_kebersihan.toLocaleString("en-US"));
                    
                    var awal = $('#kebersihan').val();
                    awal = awal.split(',');
                    awal = awal.join('');
                    awal = parseInt(awal); 

                    var akhir = $('#dis_kebersihan').val();
                    akhir = akhir.split(',');
                    akhir = akhir.join('');
                    akhir = parseInt(akhir);
                    
                    if(akhir <= awal){
                        kebersihan = 1;
                    }
                    else{
                        kebersihan = 0;
                    }

                    $("#kebersihan,#dis_kebersihan").on("change paste keyup", function() {
                        var akhir = $('#dis_kebersihan').val();
                        akhir = akhir.split(',');
                        akhir = akhir.join('');
                        akhir = parseInt(akhir);

                        var awal = $('#kebersihan').val();
                        awal = awal.split(',');
                        awal = awal.join('');
                        awal = parseInt(awal); 
                        
                        if(akhir <= awal){
                            kebersihan = 1;
                        }
                        else{
                            kebersihan = 0;
                        }
                    });
                }
                
                if(data.result.ttl_airkotor != 0 || data.result.airkotor != null){
                    $('#divEditAirKotor').show();
                    $('#stt_airkotor').val('ok');
                    if(data.result.ttl_airkotor != null)
                        $('#airkotor').val(data.result.ttl_airkotor.toLocaleString("en-US"));
                }

                if(data.result.ttl_lain != 0 || data.result.lain != null){
                    $('#divEditLain').show();
                    $('#stt_lain').val('ok');
                    if(data.result.ttl_lain != null)
                        $('#lain').val(data.result.ttl_lain.toLocaleString("en-US"));
                }
                
                if(listrik * air * keamananipk * kebersihan == 0){
                    $("#action_btn").prop("disabled", true);
                }
                else{
                    $("#action_btn").prop("disabled", false);
                }

                $("#akhirAir,#awalAir,#akhirListrik,#awalListrik,#dayaListrik,#keamananipk,#dis_keamananipk,#kebersihan,#dis_kebersihan,#airkotor,#lain").on("change paste keyup", function() {
                    if(listrik * air * keamananipk * kebersihan == 0){
                        $("#action_btn").prop("disabled", true);
                    }
                    else{
                        $("#action_btn").prop("disabled", false);
                    }
                });

				$('#pesan').val(data.result.ket);
				$('#hidden_id').val(id);
				$('#action_btn').val('Update');
                $('#myTagihan').modal('show');
			}
        });
    });

    $('#form_tagihan').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/tagihan/update',
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                $('#form_result').show();
				var html = '';
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				else
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.result.success + '</div>';
                    $('#form_tagihan')[0].reset();
                    $('#myTagihan').modal('hide');
                    $('#tabelTagihan').DataTable().ajax.reload(function(){}, false);

                    if(data.result.notif == 0){
                        $('#notification').hide();
                    }
				}
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
			}
		});
    });

    var id_tagihan;
    $(document).on('click', '.delete', function(){
        id_tagihan = $(this).attr('id');
		$('#confirmModal').modal('show');
	});

	$('#form_destroy').on('submit',function(e){
        e.preventDefault();
		$.ajax({
			url:"/tagihan/destroy/"+id_tagihan,
            cache:false,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $('#form_result').hide();
				setTimeout(function(){
                    $('#confirmModal').modal('hide');
					$('#tabelTagihan').DataTable().ajax.reload(function(){}, false);
				}, 4000);
                html = '<div class="alert alert-info" id="info-alert"> <strong>Info! </strong>' + data.status + '</div>';
                $('#confirm_result').html(html);     
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
            },
            complete:function(){
                $('#ok_button').text('Hapus');
            }
        })
    });
    
    $(document).on('click', '.unpublish', function(){
        id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
			url :"/tagihan/unpublish/"+id,
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
                if(data.errors){
                    alert(data.errors);
                }

                if(data.unsuccess){
                    alert(data.unsuccess);
                }

                if(data.success){
                    $('#tabelTagihan').DataTable().ajax.reload(function(){}, false);
                }
            }
        });
    });

    $(document).on('click', '.publishing', function(){
        id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
			url :"/tagihan/publishing/"+id,
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
                if(data.errors){
                    alert(data.errors);
                }
                if(data.success){
                    $('#tabelTagihan').DataTable().ajax.reload(function(){}, false);
                }
            }
        });
    });

    document
        .getElementById('awalListrik')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('akhirListrik')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('dayaListrik')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('awalAir')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('akhirAir')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('keamananipk')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('dis_keamananipk')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('kebersihan')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('dis_kebersihan')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('airkotor')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('lain')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

    $(document).on('click', '.totaltagihan', function(){
        id = $(this).attr('id');
        $('#bulan1').html('');
        $('#totalbulan1').html('');
        $('#bulan2').html('');
        $('#totalbulan2').html('');
        $('#bulan3').html('');
        $('#totalbulan3').html('');
        $('#bulan4').html('');
        $('#totalbulan4').html('');
        $('#bulan5').html('');
        $('#totalbulan5').html('');
        $('#bulan6').html('');
        $('#totalbulan6').html('');
        $('#bulanini').html('');
        $('#totalbulanini').html('');
        $('#divBulan1').hide();
        $('#divBulan2').hide();
        $('#divBulan3').hide();
        $('#divBulan4').hide();
        $('#divBulan5').hide();
        $('#divBulan6').hide();
        $.ajax({       
			url: "/cari/tagihan/" + id,
            cache:false,
			method:"GET",
			dataType:"json",
			success:function(data)
			{
                $('.modal-title').html(data.result.kode + " (Total Tagihan)");
                if(data.result.totalbulan1 != 0){
                    $('#divBulan1').show();
                    $('#bulan1').html(data.result.bulan1);
                    $('#totalbulan1').html("RP. " + data.result.totalbulan1);
                }

                if(data.result.totalbulan2 != 0){
                    $('#divBulan2').show();
                    $('#bulan2').html(data.result.bulan2);
                    $('#totalbulan2').html("RP. " + data.result.totalbulan2);
                }
                
                if(data.result.totalbulan3 != 0){
                    $('#divBulan3').show();
                    $('#bulan3').html(data.result.bulan3);
                    $('#totalbulan3').html("RP. " + data.result.totalbulan3);
                }
                if(data.result.totalbulan4 != 0){
                    $('#divBulan4').show();
                    $('#bulan4').html(data.result.bulan4);
                    $('#totalbulan4').html("RP. " + data.result.totalbulan4);
                }

                if(data.result.totalbulan5 != 0){
                    $('#divBulan5').show();
                    $('#bulan5').html(data.result.bulan5);
                    $('#totalbulan5').html("RP. " + data.result.totalbulan5);
                }
                
                if(data.result.totalbulan6 != 0){
                    $('#divBulan6').show();
                    $('#bulan6').html(data.result.bulan6);
                    $('#totalbulan6').html("RP. " + data.result.totalbulan6);
                }

                $('#bulanini').html(data.result.bulanini);
                $('#totalbulanini').html("RP. " + data.result.totalbulanini);
                
                $('#myChecking').modal('show');
            }
		});
    });

    $(document).on('click', '.totallistrik', function(){
        id = $(this).attr('id');
        $('#bulan1').html('');
        $('#totalbulan1').html('');
        $('#bulan2').html('');
        $('#totalbulan2').html('');
        $('#bulan3').html('');
        $('#totalbulan3').html('');
        $('#bulan4').html('');
        $('#totalbulan4').html('');
        $('#bulan5').html('');
        $('#totalbulan5').html('');
        $('#bulan6').html('');
        $('#totalbulan6').html('');
        $('#bulanini').html('');
        $('#totalbulanini').html('');
        $('#divBulan1').hide();
        $('#divBulan2').hide();
        $('#divBulan3').hide();
        $('#divBulan4').hide();
        $('#divBulan5').hide();
        $('#divBulan6').hide();
        $.ajax({       
			url: "/cari/listrik/" + id,
            cache:false,
			method:"GET",
			dataType:"json",
			success:function(data)
			{
                $('.modal-title').html(data.result.kode + " (Listrik)");
                if(data.result.totalbulan1 != 0){
                    $('#divBulan1').show();
                    $('#bulan1').html(data.result.bulan1);
                    $('#totalbulan1').html("RP. " + data.result.totalbulan1);
                }

                if(data.result.totalbulan2 != 0){
                    $('#divBulan2').show();
                    $('#bulan2').html(data.result.bulan2);
                    $('#totalbulan2').html("RP. " + data.result.totalbulan2);
                }
                
                if(data.result.totalbulan3 != 0){
                    $('#divBulan3').show();
                    $('#bulan3').html(data.result.bulan3);
                    $('#totalbulan3').html("RP. " + data.result.totalbulan3);
                }
                if(data.result.totalbulan4 != 0){
                    $('#divBulan4').show();
                    $('#bulan4').html(data.result.bulan4);
                    $('#totalbulan4').html("RP. " + data.result.totalbulan4);
                }

                if(data.result.totalbulan5 != 0){
                    $('#divBulan5').show();
                    $('#bulan5').html(data.result.bulan5);
                    $('#totalbulan5').html("RP. " + data.result.totalbulan5);
                }
                
                if(data.result.totalbulan6 != 0){
                    $('#divBulan6').show();
                    $('#bulan6').html(data.result.bulan6);
                    $('#totalbulan6').html("RP. " + data.result.totalbulan6);
                }

                $('#bulanini').html(data.result.bulanini);
                $('#totalbulanini').html("RP. " + data.result.totalbulanini);
                
                $('#myChecking').modal('show');
            }
		});
    });

    $(document).on('click', '.totalairbersih', function(){
        id = $(this).attr('id');
        $('#bulan1').html('');
        $('#totalbulan1').html('');
        $('#bulan2').html('');
        $('#totalbulan2').html('');
        $('#bulan3').html('');
        $('#totalbulan3').html('');
        $('#bulan4').html('');
        $('#totalbulan4').html('');
        $('#bulan5').html('');
        $('#totalbulan5').html('');
        $('#bulan6').html('');
        $('#totalbulan6').html('');
        $('#bulanini').html('');
        $('#totalbulanini').html('');
        $('#divBulan1').hide();
        $('#divBulan2').hide();
        $('#divBulan3').hide();
        $('#divBulan4').hide();
        $('#divBulan5').hide();
        $('#divBulan6').hide();
        $.ajax({       
			url: "/cari/airbersih/" + id,
            cache:false,
			method:"GET",
			dataType:"json",
			success:function(data)
			{
                $('.modal-title').html(data.result.kode + " (Air Bersih)");
                if(data.result.totalbulan1 != 0){
                    $('#divBulan1').show();
                    $('#bulan1').html(data.result.bulan1);
                    $('#totalbulan1').html("RP. " + data.result.totalbulan1);
                }

                if(data.result.totalbulan2 != 0){
                    $('#divBulan2').show();
                    $('#bulan2').html(data.result.bulan2);
                    $('#totalbulan2').html("RP. " + data.result.totalbulan2);
                }
                
                if(data.result.totalbulan3 != 0){
                    $('#divBulan3').show();
                    $('#bulan3').html(data.result.bulan3);
                    $('#totalbulan3').html("RP. " + data.result.totalbulan3);
                }
                if(data.result.totalbulan4 != 0){
                    $('#divBulan4').show();
                    $('#bulan4').html(data.result.bulan4);
                    $('#totalbulan4').html("RP. " + data.result.totalbulan4);
                }

                if(data.result.totalbulan5 != 0){
                    $('#divBulan5').show();
                    $('#bulan5').html(data.result.bulan5);
                    $('#totalbulan5').html("RP. " + data.result.totalbulan5);
                }
                
                if(data.result.totalbulan6 != 0){
                    $('#divBulan6').show();
                    $('#bulan6').html(data.result.bulan6);
                    $('#totalbulan6').html("RP. " + data.result.totalbulan6);
                }

                $('#bulanini').html(data.result.bulanini);
                $('#totalbulanini').html("RP. " + data.result.totalbulanini);
                
                $('#myChecking').modal('show');
            }
		});
    });
});