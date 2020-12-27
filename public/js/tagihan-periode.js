$(document).ready(function(){
    $('#tabelTagihan').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/tagihan/periode?" + 
                 "bulan="          + document.getElementById('bln_periode').value + 
                 "&tahun="         + document.getElementById('thn_periode').value,
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

    window.setInterval(function(){
        if(localStorage["update"] == "1"){
            localStorage["update"] = "0";
            $('#tabelTagihan').DataTable().ajax.reload(function(){}, false);
        }
    }, 500);

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
                
                if(data.result.sub_listrik != 0 ){
                    $('#divEditListrik').show();
                    $('#stt_listrik').val('ok');
                    $('#dayaListrik').val(data.result.daya_listrik.toLocaleString());
                    $('#awalListrik').val(data.result.awal_listrik.toLocaleString());
                    $('#akhirListrik').val(data.result.akhir_listrik.toLocaleString());

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

                if(data.result.sub_airbersih !== 0 ){
                    $('#divEditAirBersih').show();
                    $('#stt_airbersih').val('ok');
                    $('#awalAir').val(data.result.awal_airbersih.toLocaleString());
                    $('#akhirAir').val(data.result.akhir_airbersih.toLocaleString());

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

                if(data.result.sub_keamananipk !== 0 ){
                    $('#divEditKeamananIpk').show();
                    $('#stt_keamananipk').val('ok');
                    $('#keamananipk').val(data.result.sub_keamananipk.toLocaleString());
                    $('#dis_keamananipk').val(data.result.dis_keamananipk.toLocaleString());
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
                
                if(data.result.sub_kebersihan !== 0 ){
                    $('#divEditKebersihan').show();
                    $('#stt_kebersihan').val('ok');
                    $('#kebersihan').val(data.result.sub_kebersihan.toLocaleString());
                    $('#dis_kebersihan').val(data.result.dis_kebersihan.toLocaleString());
                    
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
                
                if(data.result.ttl_airkotor != 0 ){
                    $('#divEditAirKotor').show();
                    $('#stt_airkotor').val('ok');
                    $('#airkotor').val(data.result.ttl_airkotor.toLocaleString());
                }

                if(data.result.ttl_lain != 0 ){
                    $('#divEditLain').show();
                    $('#stt_lain').val('ok');
                    $('#lain').val(data.result.ttl_lain.toLocaleString());
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
				var html = '';
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                    $('#form_tagihan')[0].reset();
                    $('#myTagihan').modal('hide');
                    $('#tabelTagihan').DataTable().ajax.reload(function(){}, false);
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

	$('#ok_button').click(function(){
		$.ajax({
			url:"/tagihan/destroy/"+id_tagihan,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
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
    
    $('#publish').click(function(){
        window.open(
            '/tagihan/publish',
            '_blank'
        );
    });

    $(document).on('click', '.sync', function(){
        $('#process').show();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({       
			url: "/tagihan/sinkronisasi",
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				if(data.errors)
				{
                    $('#process').hide();
                    alert(data.errors);
                    location.reload();
				}
				if(data.success)
				{
                    $('#process').hide();
                    location.reload();
                }
            },
            error: function(data){
                alert('Oops! Kesalahan Sistem');
                $('#process').hide();
                location.reload();
            }
		});
    });

    $(document).on('click', '#tambah_manual', function(){
        $('#form_manual')[0].reset();
        $("#dayaListrik_manual").prop('required',false);
        $("#awalListrik_manual").prop('required',false);
        $("#akhirListrik_manual").prop('required',false);
        $("#awalAir_manual").prop('required',false);
        $("#akhirAir_manual").prop('required',false);
        $("#keamananipk_manual").prop('required',false);
        $("#dis_keamananipk_manual").prop('required',false);
        $("#kebersihan_manual").prop('required',false);
        $("#dis_kebersihan_manual").prop('required',false);
        $("#airkotor_manual").prop('required',false);
        $("#lain_manual").prop('required',false);
        $("#action_btn_manual").prop("disabled", true);

        $("#dayaListrik_manual").val('');
        $("#awalListrik_manual").val('');
        $("#akhirListrik_manual").val('');
        $("#awalAir_manual").val('');
        $("#akhirAir_manual").val('');
        $("#keamananipk_manual").val('');
        $("#dis_keamananipk_manual").val('');
        $("#kebersihan_manual").val('');
        $("#dis_kebersihan_manual").val('');
        $("#airkotor_manual").val('');
        $("#lain_manual").val('');

        $("#stt_listrik_manual").val('');
        $("#stt_airbersih_manual").val('');
        $("#stt_keamananipk_manual").val('');
        $("#stt_kebersihan_manual").val('');
        $("#stt_airkotor_manual").val('');
        $("#stt_lain_manual").val('');
        
        var listrik = 1;
        var air = 1;
        var keamananipk = 1;
        var kebersihan = 1;

        $("#dayaListrik_manual,#awalListrik_manual,#akhirListrik_manual").on("change paste keyup", function() {
            $("#dayaListrik_manual").prop('required',true);
            $("#awalListrik_manual").prop('required',true);
            $("#akhirListrik_manual").prop('required',true);

            var daya = $('#dayaListrik_manual').val();
            daya = daya.split(',');
            daya = daya.join('');
            daya = parseInt(daya);

            var awal = $('#awalListrik_manual').val();
            awal = awal.split(',');
            awal = awal.join('');
            awal = parseInt(awal); 
        
            var akhir = $('#akhirListrik_manual').val();
            akhir = akhir.split(',');
            akhir = akhir.join('');
            akhir = parseInt(akhir);

            if(akhir >= awal && daya > 0){
                listrik = 1;
            }
            else{
                listrik = 0;
            }
    
            $("#stt_listrik_manual").val('ok');

            if(listrik * air * keamananipk * kebersihan == 0){
                $("#action_btn_manual").prop("disabled", true);
            }
            else{
                $("#action_btn_manual").prop("disabled", false);
            }
        });

        $("#akhirAir_manual,#awalAir_manual").on("change paste keyup", function() {
            $("#awalAir_manual").prop('required',true);
            $("#akhirAir_manual").prop('required',true);

            var akhir = $('#akhirAir_manual').val();
            akhir = akhir.split(',');
            akhir = akhir.join('');
            akhir = parseInt(akhir);

            var awal = $('#awalAir_manual').val();
            awal = awal.split(',');
            awal = awal.join('');
            awal = parseInt(awal); 
            
            if(akhir >= awal){
                air = 1;
            }
            else{
                air = 0;
            }

            $("#stt_airbersih_manual").val('ok');

            if(listrik * air * keamananipk * kebersihan == 0){
                $("#action_btn_manual").prop("disabled", true);
            }
            else{
                $("#action_btn_manual").prop("disabled", false);
            }
        });

        $("#keamanaipk_manual,#dis_keamananipk_manual").on("change paste keyup", function() {
            $("#keamananipk_manual").prop('required',true);

            var akhir = $('#dis_keamananipk_manual').val();
            akhir = akhir.split(',');
            akhir = akhir.join('');
            akhir = parseInt(akhir);

            var awal = $('#keamananipk_manual').val();
            awal = awal.split(',');
            awal = awal.join('');
            awal = parseInt(awal); 
            
            if(akhir <= awal){
                keamananipk = 1;
            }
            else{
                keamananipk = 0;
            }

            $("#stt_keamananipk_manual").val('ok');

            if(listrik * air * keamananipk * kebersihan == 0){
                $("#action_btn_manual").prop("disabled", true);
            }
            else{
                $("#action_btn_manual").prop("disabled", false);
            }
        });
        
        $("#kebersihan_manual,#dis_kebersihan_manual").on("change paste keyup", function() {
            $("#kebersihan_manual").prop('required',true);
            var akhir = $('#dis_kebersihan_manual').val();
            akhir = akhir.split(',');
            akhir = akhir.join('');
            akhir = parseInt(akhir);

            var awal = $('#kebersihan_manual').val();
            awal = awal.split(',');
            awal = awal.join('');
            awal = parseInt(awal); 
            
            if(akhir <= awal){
                kebersihan = 1;
            }
            else{
                kebersihan = 0;
            }

            $("#stt_kebersihan_manual").val('ok');

            if(listrik * air * keamananipk * kebersihan == 0){
                $("#action_btn_manual").prop("disabled", true);
            }
            else{
                $("#action_btn_manual").prop("disabled", false);
            }
        });
        
        $("#airkotor_manual").on("change paste keyup", function() {
            $("#airkotor_manual").prop('required',true);

            $("#stt_airkotor_manual").val('ok');
            
            if(listrik * air * keamananipk * kebersihan == 0){
                $("#action_btn_manual").prop("disabled", true);
            }
            else{
                $("#action_btn_manual").prop("disabled", false);
            }
        });
        
        $("#lain_manual").on("change paste keyup", function() {
            $("#lain_manual").prop('required',true);

            $("#stt_lain_manual").val('ok');
            
            if(listrik * air * keamananipk * kebersihan == 0){
                $("#action_btn_manual").prop("disabled", true);
            }
            else{
                $("#action_btn_manual").prop("disabled", false);
            }
        });
    });

    $('#form_manual').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/tagihan/tambah',
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
				var html = '';
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
                    console.log(data.errors);
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                    $('#form_manual')[0].reset();
                    $('#myManual').modal('hide');
                    $('#tabelTagihan').DataTable().ajax.reload(function(){}, false);
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

    $('#kontrol_manual').select2({
        placeholder: '--- Pilih Tempat ---',
        ajax: {
            url: "/cari/alamat",
            dataType: 'json',
            delay: 250,
            processResults: function (alamat) {
                return {
                results:  $.map(alamat, function (al) {
                    return {
                    text: al.kd_kontrol,
                    id: al.id
                    }
                })
                };
            },
            cache: true
        }
    });

    $('#pengguna_manual').select2({
        placeholder: '--- Pilih Nasabah ---',
        ajax: {
            url: "/cari/nasabah",
            dataType: 'json',
            delay: 250,
            processResults: function (nasabah) {
                return {
                results:  $.map(nasabah, function (nas) {
                    return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.nama
                    }
                })
                };
            },
            cache: true
        }
    });
    
    document
        .getElementById('awalListrik_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('akhirListrik_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('dayaListrik_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('awalAir_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('akhirAir_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('keamananipk_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('dis_keamananipk_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('kebersihan_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
        .getElementById('dis_kebersihan_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('airkotor_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    
    document
        .getElementById('lain_manual')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

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
});