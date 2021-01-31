$(document).ready(function(){
    $('#tabelTempat').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/tempatusaha",
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'no_alamat', name: 'no_alamat', class : 'text-center'},
			{ data: 'pengguna', name: 'pengguna', class : 'text-center' },
			{ data: 'lok_tempat', name: 'lok_tempat', class : 'text-center' },
			{ data: 'jml_alamat', name: 'jml_alamat', class : 'text-center' },
			{ data: 'bentuk_usaha', name: 'bentuk_usaha', class : 'text-center' },
			{ data: 'kodeListrik', name: 'kodeListrik', class : 'text-center' },
			{ data: 'dis_listrik', name: 'dis_listrik', class : 'text-center' },
			{ data: 'kodeAir', name: 'kodeAir', class : 'text-center' },
			{ data: 'dis_airbersih', name: 'dis_airbersih', class : 'text-center' },
			{ data: 'trfKeamananIpk', name: 'trfKeamananIpk', class : 'text-center' },
			{ data: 'dis_keamananipk', name: 'dis_keamananipk', class : 'text-center' },
			{ data: 'trfKebersihan', name: 'trfKebersihan', class : 'text-center' },
			{ data: 'dis_kebersihan', name: 'dis_kebersihan', class : 'text-center' },
			{ data: 'trfArkot', name: 'trfArkot', class : 'text-center' },
			{ data: 'trfLain', name: 'trfLain', class : 'text-center' },
			{ data: 'stt_tempat', name: 'stt_tempat', class : 'text-center' },
			{ data: 'ket_tempat', name: 'ket_tempat', class : 'text-center' },
			{ data: 'pemilik', name: 'pemilik', class : 'text-center' },
			{ data: 'action', name: 'action', class : 'text-center' }
        ],
        stateSave: true,
        scrollX: true,
        deferRender: true,
        pageLength: 8,
        fixedColumns:   {
            "leftColumns": 3,
            "rightColumns": 2,
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [19] }, 
            { "bSearchable": false, "aTargets": [19] }
        ],
        order:[[0, 'asc']]
    });

    $('#tabelTempat1').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/tempatusaha",
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'no_alamat', name: 'no_alamat', class : 'text-center'},
			{ data: 'pengguna', name: 'pengguna', class : 'text-center' },
			{ data: 'lok_tempat', name: 'lok_tempat', class : 'text-center' },
			{ data: 'jml_alamat', name: 'jml_alamat', class : 'text-center' },
			{ data: 'bentuk_usaha', name: 'bentuk_usaha', class : 'text-center' },
			{ data: 'kodeListrik', name: 'kodeListrik', class : 'text-center' },
			{ data: 'dis_listrik', name: 'dis_listrik', class : 'text-center' },
			{ data: 'kodeAir', name: 'kodeAir', class : 'text-center' },
			{ data: 'dis_airbersih', name: 'dis_airbersih', class : 'text-center' },
			{ data: 'trfKeamananIpk', name: 'trfKeamananIpk', class : 'text-center' },
			{ data: 'dis_keamananipk', name: 'dis_keamananipk', class : 'text-center' },
			{ data: 'trfKebersihan', name: 'trfKebersihan', class : 'text-center' },
			{ data: 'dis_kebersihan', name: 'dis_kebersihan', class : 'text-center' },
			{ data: 'trfArkot', name: 'trfArkot', class : 'text-center' },
			{ data: 'trfLain', name: 'trfLain', class : 'text-center' },
			{ data: 'stt_tempat', name: 'stt_tempat', class : 'text-center' },
			{ data: 'ket_tempat', name: 'ket_tempat', class : 'text-center' },
			{ data: 'pemilik', name: 'pemilik', class : 'text-center' }
        ],
        stateSave: true,
        scrollX: true,
        deferRender: true,
        pageLength: 8,
        fixedColumns:   {
            "leftColumns": 3,
            "rightColumns": 1,
        }
    });

    $('#tabelRekap').DataTable({
        "processing": true,
        "bProcessing": true,
        "language": {
            'loadingRecords': '&nbsp;',
            'processing': '<i class="fas fa-spinner"></i>'
        },
        "deferRender": true,
        "fixedColumns":   {
            "leftColumns": 2,
            "rightColumns": 3,
        },
        "scrollX": true,
        "scrollCollapse": true,
        "pageLength": 8,
        "order": [ 0, "asc" ]
    });

    $('#tabelDiskon').DataTable({
        "processing": true,
        "bProcessing": true,
        "language": {
            'loadingRecords': '&nbsp;',
            'processing': '<i class="fas fa-spinner"></i>'
        },
        "deferRender": true,
        "fixedColumns":   {
            "leftColumns": 3,
            "rightColumns": 1,
        },
        "scrollX": true,
        "scrollCollapse": true,
        "pageLength": 8,
        "order": [ 0, "asc" ]
    });

    $('#tabelFasilitas').DataTable({
        "processing": true,
        "bProcessing": true,
        "language": {
            'loadingRecords': '&nbsp;',
            'processing': '<i class="fas fa-spinner"></i>'
        },
        "deferRender": true,
        "fixedColumns":   {
            "leftColumns": 3,
            "rightColumns": 1,
        },
        "scrollX": true,
        "scrollCollapse": true,
        "pageLength": 8,
        "order": [ 0, "asc" ]
    });

    //Search
    $('#meterAir').select2();
    
    $('#meterListrik').select2();

    $('#blok').select2();

    $('#pemilik').select2();

    $('#pengguna').select2();

    $('#add_tempat').click(function(){
		$('.modal-title').text('Tambah Tempat Usaha');
        
        $('#action_btn').val('Tambah');
        $('#action').val('Add');
        
		$('#form_result').html('');
        
        $('#form_tempat')[0].reset();
        
        $('#displayAir').hide();
        $('#diskonBayarAir').hide();
        $('#hanyaBayarAir').hide();
        $('#displayCharge').hide();
        $("#persenDiskonAir").val();
        
        $('#displayListrik').hide();
        $('#displayListrikDiskon').hide();
        $("#persenDiskonListrik").val();

        $('#displayKeamananIpk').hide();
        $('#displayKeamananIpkDiskon').hide();
        
        $('#displayKebersihan').hide();
        $('#displayKebersihanDiskon').hide();
        
        $('#displayAirKotor').hide();
        $('#displayLain').hide();
        
        $('#ketStatus').hide();
        
        $("#myDiv3 option:selected" ).text('--- Pilih Tarif ---').val('');
        $("#diskonKeamananIpk").val();
        
        $("#myDiv4 option:selected" ).text('--- Pilih Tarif ---').val('');
        $("#diskonKebersihan").val();
        
        $("#myDiv5 option:selected" ).text('--- Pilih Tarif ---').val('');
        $("#myDiv6 option:selected" ).text('--- Pilih Tarif ---').val('');

        $('#myDiv1').prop('required',false);
        $('#myDiv2').prop('required',false);
        $('#myDiv3').prop('required',false);
        $('#myDiv4').prop('required',false);
        $('#myDiv5').prop('required',false);
        $('#myDiv6').prop('required',false);
        $('#ket_tempat').prop('required',false);
        
        $('#meterAir').val('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatair",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir + ')',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
        $('#meterListrik').val('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatlistrik",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir +  ' - ' + alat.daya + ' W)',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
        $('#blok').val('').select2({
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
        $('#pemilik').val('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

        $('#pengguna').val('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

		$('#myModal').modal('show');
    });
    
    var id='';
    $(document).on('click', '.edit', function(){
        id = $(this).attr('id');
        
        $('#form_result').html('');

        $('#meterAir').val('').html('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatair",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir + ')',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
        $('#meterListrik').val('').html('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatlistrik",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir +  ' - ' + alat.daya + ' W)',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
		$('#blok').val('').html('').select2({
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
		$('#pemilik').val('').html('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
		$('#pengguna').val('').html('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

        $('#displayAir').hide();
        $('#diskonBayarAir').hide();
        $('#hanyaBayarAir').hide();
        $('#displayCharge').hide();
        $("#persenDiskonAir").val();

        $('#displayListrik').hide();
        $('#displayListrikDiskon').hide();
        $("#persenDiskonListrik").val();
        
        $('#displayKeamananIpk').hide();
        $('#displayKeamananIpkDiskon').hide();
        $("#myDiv3 option:selected" ).val();
        $("#diskonKeamananIpk").val();

        $('#displayKebersihan').hide();
        $('#displayKebersihanDiskon').hide();
        $("#myDiv4 option:selected" ).val();
        $("#diskonKebersihan").val();
        
        $('#displayAirKotor').hide();
        $("#myDiv5 option:selected" ).val();
        
        $('#displayLain').hide();
        $("#myDiv6 option:selected" ).val();
        
        $('#ketStatus').hide();
        
        $('#myDiv1').prop('required',false);
        $('#myDiv2').prop('required',false);
        $('#myDiv3').prop('required',false);
        $('#myDiv4').prop('required',false);
        $('#myDiv5').prop('required',false);
        $('#myDiv6').prop('required',false);
        $('#ket_tempat').prop('required',false);

        $('#form_tempat')[0].reset();
		$.ajax({
			url :"/tempatusaha/" + id + "/edit",
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#los').val(data.result.no_alamat.replace(/\s/g,''));
                
                if(data.result.id_pemilik != null){
                    var pemilik = new Option(data.result.pemilik, data.result.id_pemilik, false, false);
                    $('.pemilik').append(pemilik).trigger('change');
                }
                
                if(data.result.id_pengguna != null){
                    var pengguna = new Option(data.result.pengguna, data.result.id_pengguna, false, false);
                    $('#pengguna').append(pengguna).trigger('change');
                }

                var blok = new Option(data.result.blok, data.result.blok, false, false);
                $('#blok').append(blok).trigger('change');
                
                $('#lokasi').val(data.result.lok_tempat);
                
                $('#usaha').val(data.result.bentuk_usaha);

                if(data.result.stt_tempat == 2){
                    $("#myStatus2").prop("checked", true);
                    $('#ketStatus').show();
                    $('#ket_tempat').val(data.result.ket_tempat);
                }

                if(data.result.trf_airbersih !== null || data.result.meterAirId !== null){
                    if(data.result.trf_airbersih !== null){
                        $("#myCheck1").prop("checked", true);
                        $('#displayAir').show();
                    }

                    if(data.result.meterAirId != null){
                        var meter = new Option(data.result.meterAir, data.result.meterAirId, false, false);
                        $('#meterAir').append(meter).trigger('change');
                    }

                    if(data.result.dis_airbersih != null){ 
                        if(data.result.bebasAir == 'diskon'){
                            $('#dis_airbersih').prop('checked',true);
                            $('#diskonBayarAir').show();
                            $("#persenDiskonAir").val(data.result.diskonAir);
                        }
                        else{
                            $('#hanya_airbersih').prop('checked',true);
                            $('#hanyaBayarAir').show();
                            if(jQuery.inArray("byr", data.result.diskonAir) !== -1){
                                $('#hanyaPemakaianAir').prop('checked',true);
                            }  
                            if(jQuery.inArray("beban", data.result.diskonAir) !== -1){
                                $('#hanyaBebanAir').prop('checked',true);
                            }  
                            if(jQuery.inArray("pemeliharaan", data.result.diskonAir) !== -1){
                                $('#hanyaPemeliharaanAir').prop('checked',true);
                            }  
                            if(jQuery.inArray("arkot", data.result.diskonAir) !== -1){
                                $('#hanyaArkotAir').prop('checked',true);
                            }  
                            if(typeof data.result.diskonAir[data.result.diskonAir.length - 1] === 'object'){
                                $('#hanyaChargeAir').prop('checked',true);
                                $('#displayCharge').show();
                                var charge = data.result.diskonAir[data.result.diskonAir.length - 1]['charge'];
                                charge = charge.split(',');
                                $("#persenChargeAir").val(charge[0]);
                                if(charge[1] === 'ttl'){
                                    $("#dariChargeAir").val('ttl');
                                }
                                if(charge[1] === 'byr'){
                                    $("#dariChargeAir").val('byr');
                                }
                            }
                        }
                    }
                    else{
                        $('#semua_airbersih').prop('checked',true);
                    }
                }

                if(data.result.trf_listrik !== null || data.result.meterListrikId !== null){   
                    if(data.result.trf_listrik !== null){
                        $("#myCheck2").prop("checked", true);
                        $('#displayListrik').show();
                    }
                    if(data.result.meterListrikId != null){
                        var meter = new Option(data.result.meterListrik, data.result.meterListrikId, false, false);
                        $('#meterListrik').append(meter).trigger('change');
                    }

                    if(data.result.dis_listrik != null){ 
                        $("#dis_listrik").prop("checked", true);
                        $("#persenDiskonListrik").val(data.result.dis_listrik);
                        $('#displayListrikDiskon').show();
                    }
                }
                if(data.result.trf_keamananipk != null){    
                    $("#myCheck3").prop("checked", true);
                    $('#displayKeamananIpk').show();
                    $("#myDiv3 option:selected" ).text(data.result.tarifKeamananIpk).val(data.result.tarifKeamananIpkId);
                    if(data.result.dis_keamananipk != null){ 
                        $("#dis_keamananipk").prop("checked", true);
                        $("#diskonKeamananIpk").val(data.result.dis_keamananipk.toLocaleString("en-US"));
                        $('#displayKeamananIpkDiskon').show();
                        if(data.result.dis_keamananipk == 0){
                            $("#dis_keamananipk").prop("checked", false);
                            $('#displayKeamananIpkDiskon').hide();
                        }
                    }
                }
                if(data.result.trf_kebersihan != null){    
                    $("#myCheck4").prop("checked", true);
                    $('#displayKebersihan').show();
                    $("#myDiv4 option:selected" ).text(data.result.tarifKebersihan).val(data.result.tarifKebersihanId);
                    if(data.result.dis_kebersihan != null){ 
                        $("#dis_kebersihan").prop("checked", true);
                        $("#diskonKebersihan").val(data.result.dis_kebersihan.toLocaleString("en-US"));
                        $('#displayKebersihanDiskon').show();
                        if(data.result.dis_kebersihan == 0){
                            $("#dis_kebersihan").prop("checked", false);
                            $('#displayKebersihanDiskon').hide();
                        }
                    }
                }
                if(data.result.trf_airkotor != null){    
                    $("#myCheck5").prop("checked", true);
                    $('#displayAirKotor').show();
                    $("#myDiv5 option:selected" ).text(data.result.tarifAirKotor).val(data.result.tarifAirKotorId);
                }
                if(data.result.trf_lain != null){    
                    $("#myCheck6").prop("checked", true);
                    $('#displayLain').show();
                    $("#myDiv6 option:selected" ).text(data.result.tarifLain).val(data.result.tarifLainId);
                }

                $('#hidden_id').val(id);
				$('.modal-title').text('Edit Tempat Usaha');
				$('#action_btn').val('Update');
                $('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_tempat').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/tempatusaha";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/tempatusaha/update";
		}

		$.ajax({
			url: action_url,
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
                    // console.log(data.errors);
				}
				if(data.success)
				{
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
					
                    setTimeout(function(){
                        $('#myModal').modal('hide');
                        $('#tabelTempat').DataTable().ajax.reload(function(){}, false);
                    }, 4000);
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

    var user_id;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/tempatusaha/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $('#form_result').hide();
				setTimeout(function(){
                    $('#confirmModal').modal('hide');
					$('#tabelTempat').DataTable().ajax.reload(function(){}, false);
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

    $('#los').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9,]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });

    $("#los").on("input", function() {
    if (/^,/.test(this.value)) {
        this.value = this.value.replace(/^,/, "")
    }
    else if (/^0/.test(this.value)) {
        this.value = this.value.replace(/^0/, "")
    }
    })

    document
        .getElementById('diskonKeamananIpk')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('diskonKebersihan')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );

    // Radio Bebas Bayar
    function radioAir() {
        if ($('#hanya_airbersih').is(':checked')) {
            document
                .getElementById('hanyaBayarAir')
                .style
                .display = 'block';
            document
                .getElementById('diskonBayarAir')
                .style
                .display = 'none';
        }
        else if ($('#dis_airbersih').is(':checked')) {
            document
                .getElementById('hanyaBayarAir')
                .style
                .display = 'none';
            document
                .getElementById('diskonBayarAir')
                .style
                .display = 'block';
        }
        else {
            document
                .getElementById('hanyaBayarAir')
                .style
                .display = 'none';
            document
                .getElementById('diskonBayarAir')
                .style
                .display = 'none';
        }
    }
    $('input[type="radio"]')
        .click(radioAir)
        .each(radioAir);

    // Status Button
    function statusTempat() {
        if ($('#myStatus2').is(':checked')) {
            document
                .getElementById('ketStatus')
                .style
                .display = 'block';
            document
                .getElementById('ket_tempat')
                .required = true;
        }
        else {
            document
                .getElementById('ketStatus')
                .style
                .display = 'none';
            document
                .getElementById('ket_tempat')
                .required = false;
        }
    }
    $('input[type="radio"]')
        .click(statusTempat)
        .each(statusTempat);

    // Metode Pembayaran
    // function pembayaran() {
    //     if ($('#cicilan2').is(':checked')) {
    //         document
    //             .getElementById('ketCicil')
    //             .style
    //             .display = 'block';
    //         document
    //             .getElementById('ket_cicil')
    //             .required = true;
    //     }
    //     else {
    //         document
    //             .getElementById('ketCicil')
    //             .style
    //             .display = 'none';
    //         document
    //             .getElementById('ket_cicil')
    //             .required = false;
    //     }
    // }
    // $('input[type="radio"]')
    //     .click(pembayaran)
    //     .each(pembayaran);

    // Fasilitas Button
    function evaluate() {
        var item = $(this);
        var relatedItem = $("#" + item.attr("data-related-item")).parent();

        if (item.is(":checked")) {
            relatedItem.fadeIn();
        } else {
            relatedItem.fadeOut();
        }
    }
    $('input[type="checkbox"]')
        .click(evaluate)
        .each(evaluate);

    // Checking
    function checkAir() {
        if ($('#myCheck1').is(':checked')) {
            document
                .getElementById('myDiv1')
                .required = true;
        } else {
            document
                .getElementById('myDiv1')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkAir)
        .each(checkAir);

    function checkListrik() {
        if ($('#myCheck2').is(':checked')) {
            document
                .getElementById('myDiv2')
                .required = true;
        } else {
            document
                .getElementById('myDiv2')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkListrik)
        .each(checkListrik);

    function checkKeamananIpk() {
        if ($('#myCheck3').is(':checked')) {
            document
                .getElementById('myDiv3')
                .required = true;
        } else {
            document
                .getElementById('myDiv3')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKeamananIpk)
        .each(checkKeamananIpk);


    function checkKebersihan() {
        if ($('#myCheck4').is(':checked')) {
            document
                .getElementById('myDiv4')
                .required = true;
        } else {
            document
                .getElementById('myDiv4')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKebersihan)
        .each(checkKebersihan);

    function checkAirKotor() {
        if ($('#myCheck5').is(':checked')) {
            document
                .getElementById('myDiv5')
                .required = true;
        } else {
            document
                .getElementById('myDiv5')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkAirKotor)
        .each(checkAirKotor);

    function checkLain() {
        if ($('#myCheck6').is(':checked')) {
            document
                .getElementById('myDiv6')
                .required = true;
        } else {
            document
                .getElementById('myDiv6')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkLain)
        .each(checkLain);

    $(document).on('click', '.qr', function(){
        var id = $(this).attr('id');
        window.open(
            '/tempatusaha/qr/' + id,
            '_blank'
        );
    });
});