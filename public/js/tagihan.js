$(document).ready(function(){
    $('#tabelTagihan').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/tagihan",
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
                var listrik = 0;
                var air = 0;
                var keamananipk = 0;
                var kebersihan = 0;
                
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

                $("#akhirAir,#awalAir,#akhirListrik,#awalListrik,#dayaListrik,#keamananipk,#dis_keamananipk,#kebersihan,#dis_kebersihan").on("change paste keyup", function() {
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