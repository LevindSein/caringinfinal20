$(document).ready(function(){
    $('#form_listrik').on('submit', function(event){
		event.preventDefault();
        fas = $(this).attr('fas');
        $('#listrik_result').html('');
		$.ajax({
			url :"/utilities/tarif/update",
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
				}
				$('#listrik_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
            }
        });
    });

    $('#form_air').on('submit', function(event){
		event.preventDefault();
        fas = $(this).attr('fas');
        $('#air_result').html('');
		$.ajax({
			url :"/utilities/tarif/update",
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
				}
				$('#air_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
            }
        });
    });

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#tabelKeamananIpk')) {
            $('#tabelKeamananIpk').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/utilities/tarif/keamananipk',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center', orderable: false, width: "8%", searchable: false },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2] }, 
                    { "bSearchable": false, "aTargets": [2] }
                ]
            });
        }
    });

    $("#tab-c-3").click(function(){
        if (!$.fn.dataTable.isDataTable('#tabelKebersihan')) {
            $('#tabelKebersihan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/utilities/tarif/kebersihan',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center', orderable: false, width: "8%", searchable: false },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2] }, 
                    { "bSearchable": false, "aTargets": [2] }
                ]
            });
        }
    });

    $("#tab-c-4").click(function(){
        if (!$.fn.dataTable.isDataTable('#tabelAirKotor')) {
            $('#tabelAirKotor').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/utilities/tarif/airkotor',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center', orderable: false, width: "8%", searchable: false },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2] }, 
                    { "bSearchable": false, "aTargets": [2] }
                ]
            });
        }
    });

    $("#tab-c-6").click(function(){
        if (!$.fn.dataTable.isDataTable('#tabelLain')) {
            $('#tabelLain').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/utilities/tarif/lain',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center', orderable: false, width: "8%", searchable: false },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2] }, 
                    { "bSearchable": false, "aTargets": [2] }
                ]
            });
        }
    });

    $('#add_tarif').click(function(){
		$('.modal-title').text('Tambah Tarif');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_tarif')[0].reset();
        $('#myModal').modal('show');
        
        $('input[id="myCheck1"]').attr('disabled',false);
        $('input[id="myCheck2"]').attr('disabled',false);
        $('input[id="myCheck3"]').attr('disabled',false);
        $('input[id="myCheck5"]').attr('disabled',false); 

        $("#checkKeamananIpk").prop("checked", false);
        $("#displayKeamananIpk").hide();
        $("#checkKebersihan").prop("checked", false);
        $("#displayKebersihan").hide();
        $("#checkAirKotor").prop("checked", false);
        $("#displayAirKotor").hide();
        $("#checkLain").prop("checked", false);
        $("#displayLain").hide();
    });

    $(document).on('click', '.edit', function(){
        id = $(this).attr('id');
        fas = $(this).attr('fas');
		$('#form_result').html('');
		$.ajax({
			url :"/utilities/tarif/edit/" + fas + "/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(fas == 'keamananipk'){
                    $('input[id="myCheck1"]').attr('disabled',false).prop("checked", true);
                    $('input[id="myCheck2"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck3"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck5"]').attr('disabled',true).prop("checked", false);  
                    
                    $("#displayKeamananIpk").show();
                    $("#displayKebersihan").hide();
                    $("#displayAirKotor").hide();
                    $("#displayLain").hide();

                    $('#keamananIpk').val(data.result);
                }

                if(fas == 'kebersihan'){
                    $('input[id="myCheck1"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck2"]').attr('disabled',false).prop("checked", true);
                    $('input[id="myCheck3"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck5"]').attr('disabled',true).prop("checked", false);  
                    
                    $("#displayKeamananIpk").hide();
                    $("#displayKebersihan").show();
                    $("#displayAirKotor").hide();
                    $("#displayLain").hide();
                    
                    $('#kebersihan').val(data.result);
                }

                if(fas == 'airkotor'){
                    $('input[id="myCheck1"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck2"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck3"]').attr('disabled',false).prop("checked", true);
                    $('input[id="myCheck5"]').attr('disabled',true).prop("checked", false);  
                    
                    $("#displayKeamananIpk").hide();
                    $("#displayKebersihan").hide();
                    $("#displayAirKotor").show();
                    $("#displayLain").hide();

                    $('#airkotor').val(data.result);
                }

                if(fas == 'lain'){
                    $('input[id="myCheck1"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck2"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck3"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck5"]').attr('disabled',false).prop("checked", true);  
                    
                    $("#displayKeamananIpk").hide();
                    $("#displayKebersihan").hide();
                    $("#displayAirKotor").hide();
                    $("#displayLain").show();
                    
                    $('#lain').val(data.result);
                }
                
				$('#hidden_id').val(id);
				$('.modal-title').text('Edit Tarif');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_tarif').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/utilities/tarif/store";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/utilities/tarif/update";
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
				if(data.result.status == 'success')
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.result.message + '</div>';
                    $('#form_tarif')[0].reset();
                    $("#checkKeamananIpk").prop("checked", false);
                    $("#displayKeamananIpk").hide();
                    $("#checkKebersihan").prop("checked", false);
                    $("#displayKebersihan").hide();
                    $("#checkAirKotor").prop("checked", false);
                    $("#displayAirKotor").hide();
                    $("#checkLain").prop("checked", false);
                    $("#displayLain").hide();
                    try{
                        if(data.result.role == 'keamananipk'){
                            $('#tab-c-2').trigger('click');
                        }
                        if(data.result.role == 'kebersihan'){
                            $('#tab-c-3').trigger('click');
                        }
                        if(data.result.role == 'airkotor'){
                            $('#tab-c-4').trigger('click');
                        }
                        if(data.result.role == 'lain'){
                            $('#tab-c-6').trigger('click');
                        }
                    } catch(err){}
                    finally{
                        if(data.result.role == 'keamananipk'){
                            $('#tabelKeamananIpk').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'kebersihan'){
                            $('#tabelKebersihan').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'airkotor'){
                            $('#tabelAirKotor').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'lain'){
                            $('#tabelLain').DataTable().ajax.reload(function(){}, false);
                        }
                    }
                }
				if(data.result.status == 'error')
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.result.message + '</div>';
				}
				$('#form_result').html(html);
                $('input[id="myCheck1"]').attr('disabled',false);
                $('input[id="myCheck2"]').attr('disabled',false);
                $('input[id="myCheck3"]').attr('disabled',false);
                $('input[id="myCheck5"]').attr('disabled',false);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
			}
		});
    });

    var user_id;
    var fas;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		fas = $(this).attr('fas');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/utilities/tarif/destroy/"+ fas + "/" + user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $('#form_result').hide();
				setTimeout(function(){
                    $('#confirmModal').modal('hide');
                    if(data.result.role == 'keamananipk'){
                        $('#tabelKeamananIpk').DataTable().ajax.reload(function(){}, false);
                    }
                    if(data.result.role == 'kebersihan'){
                        $('#tabelKebersihan').DataTable().ajax.reload(function(){}, false);
                    }
                    if(data.result.role == 'airkotor'){
                        $('#tabelAirKotor').DataTable().ajax.reload(function(){}, false);
                    }
                    if(data.result.role == 'lain'){
                        $('#tabelLain').DataTable().ajax.reload(function(){}, false);
                    }
				}, 4000);
                html = '<div class="alert alert-info" id="info-alert"> <strong>Info! </strong>' + data.result.status + '</div>';
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
    
    document
        .getElementById('keamananIpk')
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
    function checkKeamananIpk() {
        if ($('#myCheck1').is(':checked')) {
            document
                .getElementById('keamananIpk')
                .required = true;
        } else {
            document
                .getElementById('keamananIpk')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKeamananIpk)
        .each(checkKeamananIpk);

    
    function checkKebersihan() {
        if ($('#myCheck2').is(':checked')) {
            document
                .getElementById('kebersihan')
                .required = true;
        } else {
            document
                .getElementById('kebersihan')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKebersihan)
        .each(checkKebersihan);

    function checkAirKotor() {
        if ($('#myCheck3').is(':checked')) {
            document
                .getElementById('airkotor')
                .required = true;
        } else {
            document
                .getElementById('airkotor')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkAirKotor)
        .each(checkAirKotor);

    function checkLain() {
        if ($('#myCheck5').is(':checked')) {
            document
                .getElementById('lain')
                .required = true;
        } else {
            document
                .getElementById('lain')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkLain)
        .each(checkLain);

    document
    .getElementById('tarif1')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('tarif2')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('pemeliharaan')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('bebanAir')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('dendaAir')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('pasangAir')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

    
    document
    .getElementById('blok1')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('blok2')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('bebanListrik')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('denda1')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('pasangListrik')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
});