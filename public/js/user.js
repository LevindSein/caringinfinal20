$(document).ready(function(){
    $('#userAdmin').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/user",
            cache:false,
		},
		columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
			{ data: 'username', name: 'username', class : 'text-center' },
			{ data: 'nama', name: 'nama', class : 'text-center' },
			{ data: 'ktp', name: 'ktp', class : 'text-center' },
			{ data: 'hp', name: 'hp', class : 'text-center' },
			{ data: 'email', name: 'email', class : 'text-center' },
			{ data: 'otoritas', name: 'otoritas', class : 'text-center' },
			{ data: 'action', name: 'action', class : 'text-center' },
        ],
        stateSave: true,
        scrollX: true,
        deferRender: true,
        pageLength: 8,
        fixedColumns:   {
            "leftColumns": 2,
            "rightColumns": 2,
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [1,3,4,5,6,7] }, 
            { "bSearchable": false, "aTargets": [7] }
        ]
    });

    $("#tab-c-1").click(function(){
        if (!$.fn.dataTable.isDataTable('#userManajer')) {
            $('#userManajer').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/user/manajer',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'ktp', name: 'ktp', class : 'text-center' },
                    { data: 'hp', name: 'hp', class : 'text-center' },
                    { data: 'email', name: 'email', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 2,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [1,3,4,5,6] }, 
                    { "bSearchable": false, "aTargets": [6] }
                ]
            });
        }
    });

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#userKeuangan')) {
            $('#userKeuangan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/user/keuangan',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'ktp', name: 'ktp', class : 'text-center' },
                    { data: 'hp', name: 'hp', class : 'text-center' },
                    { data: 'email', name: 'email', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 2,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [1,3,4,5,6] }, 
                    { "bSearchable": false, "aTargets": [6] }
                ]
            });
        }
    });

    $("#tab-c-3").click(function(){
        if (!$.fn.dataTable.isDataTable('#userKasir')) {
            $('#userKasir').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/user/kasir',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'ktp', name: 'ktp', class : 'text-center' },
                    { data: 'hp', name: 'hp', class : 'text-center' },
                    { data: 'email', name: 'email', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 2,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [1,3,4,5,6] }, 
                    { "bSearchable": false, "aTargets": [6] }
                ]
            });
        }
    });

    $("#tab-c-4").click(function(){
        if (!$.fn.dataTable.isDataTable('#userNasabah')) {
            $('#userNasabah').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/user/nasabah',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'ktp', name: 'ktp', class : 'text-center' },
                    { data: 'hp', name: 'hp', class : 'text-center' },
                    { data: 'email', name: 'email', class : 'text-center' },
                    { data: 'anggota', name: 'anggota', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 2,
                    "rightColumns": 1,
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [1,3,4,5,6,7] }, 
                    { "bSearchable": false, "aTargets": [7] }
                ]
            });
        }
    });

    var id;

    $('#add_user').click(function(){
		$('.modal-title').text('Tambah User');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_user')[0].reset();
		$('#myModal').modal('show');
    });

    $('#form_user').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/user";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/user/update";
		}

		$.ajax({
			url: action_url,
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
				var html = '';
				if(data.result.status == 'error')
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.result.message + '</div>';
				}
				if(data.result.status == 'success')
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.result.message + '</div>';
                    $('#form_user')[0].reset();
                    try{
                        if(data.result.role == 'admin'){
                            $('#tab-c-0').trigger('click');
                        }
                        if(data.result.role == 'manajer'){
                            $('#tab-c-1').trigger('click');
                        }
                        if(data.result.role == 'keuangan'){
                            $('#tab-c-2').trigger('click');
                        }
                        if(data.result.role == 'kasir'){
                            $('#tab-c-3').trigger('click');
                        }
                    } catch(err){}
                    finally{
                        if(data.result.role == 'admin'){
                            $('#userAdmin').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'manajer'){
                            $('#userManajer').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'keuangan'){
                            $('#userKeuangan').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'kasir'){
                            $('#userKasir').DataTable().ajax.reload(function(){}, false);
                        }
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
    
    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
		$('#form_result').html('');
		$.ajax({
			url :"/user/"+id+"/edit",
            cache:false,
			dataType:"json",
			success:function(data)
			{
				$('#ktp').val(data.result.ktp);
                $('#nama').val(data.result.nama);
                $('#username').val(data.result.username);
                $('#password').val();
                $('#anggota').val(data.result.anggota);
                $('#email').val(data.result.email);
                $('#hp').val(data.result.hp);

                if(data.result.role == 'admin'){
                    $("#roleAdmin").prop("checked", true);
                }

                if(data.result.role == 'manajer'){
                    $("#roleManajer").prop("checked", true);
                }

                if(data.result.role == 'keuangan'){
                    $("#roleKeuangan").prop("checked", true);
                }

                if(data.result.role == 'kasir'){
                    $("#roleKasir").prop("checked", true);
                }
                
				$('#hidden_id').val(id);
				$('.modal-title').text('Edit Pedagang');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#nama').on('input',function(){
        var nama = $(this).val();
        var username = nama.replace(/\s/g,'');
        var username = username.slice(0, 7);
        var number = Math.floor(1000 + Math.random() * 9000);
        document.getElementById("username").value = username + number;

        var pass = shuffle('abcdefghjkmnpqrstuvwxyz123456789');
        var pass = pass.slice(0, 7);
        document.getElementById("password").value = pass;
    });

    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    function shuffle(string) {
        var parts = string.split('');
        for (var i = parts.length; i > 0;) {
            var random = parseInt(Math.random() * i);
            var temp = parts[--i];
            parts[i] = parts[random];
            parts[random] = temp;
        }
        return parts.join('');
    }

    var user_id;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/user/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
				setTimeout(function(){
                    $('#confirmModal').modal('hide');
                    if(data.result.role == 'admin'){
                        $('#userAdmin').DataTable().ajax.reload(function(){}, false);
                    }
                    if(data.result.role == 'manajer'){
                        $('#userManajer').DataTable().ajax.reload(function(){}, false);
                    }
                    if(data.result.role == 'keuangan'){
                        $('#userKeuangan').DataTable().ajax.reload(function(){}, false);
                    }
                    if(data.result.role == 'kasir'){
                        $('#userKasir').DataTable().ajax.reload(function(){}, false);
                    }
                    if(data.result.role == 'nasabah'){
                        $('#userNasabah').DataTable().ajax.reload(function(){}, false);
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

    $('.blokOtoritas').select2({
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
    
    $(document).on('click', '.otoritas', function(){
		id = $(this).attr('id');
		$('#form_result').html('');
		$('#blokOtoritas').select2('val','');
		$('#blokOtoritas').html('');
        $("#pedagang").prop("checked", false);
        $("#tempatusaha").prop("checked", false);
        $("#tagihan").prop("checked", false);
        $("#blok").prop("checked", false);
        $("#pemakaian").prop("checked", false);
        $("#pendapatan").prop("checked", false);
        $("#datausaha").prop("checked", false);
        $("#publish").prop("checked", false);
        $("#alatmeter").prop("checked", false);
        $("#tarif").prop("checked", false);
        $("#harilibur").prop("checked", false);
		$.ajax({
			url :"/user/"+id+"/otoritas",
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#username_otoritas').val(data.result.username);
                $('#nama_otoritas').val(data.result.nama);

				$('#hidden_id_otoritas').val(id);
				$('.modal-title').text('Otoritas Admin');
				$('#action_btn_otoritas').val('Update');
                $('#myOtoritas').modal('show');

                if(data.result.blok != null){
                    var s1 = $("#blokOtoritas").select2();
                    var valBlok = data.result.bloks;
                    valBlok.forEach(function(e){
                        if(!s1.find('option:contains(' + e + ')').length) 
                            s1.append($('<option>').text(e));
                    });
                    s1.val(valBlok).trigger("change");

                    $('.blokOtoritas').select2({
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

                    if(data.result.pedagang == true) $("#pedagang").prop("checked", true);

                    if(data.result.tempatusaha == true) $("#tempatusaha").prop("checked", true);
                    
                    if(data.result.tagihan == true) $("#tagihan").prop("checked", true);

                    if(data.result.blok == true) $("#blok").prop("checked", true);

                    if(data.result.pemakaian == true) $("#pemakaian").prop("checked", true);

                    if(data.result.pendapatan == true) $("#pendapatan").prop("checked", true);

                    if(data.result.datausaha == true) $("#datausaha").prop("checked", true);
                    
                    if(data.result.publish == true) $("#publish").prop("checked", true);

                    if(data.result.alatmeter == true) $("#alatmeter").prop("checked", true);
                    
                    if(data.result.tarif == true) $("#tarif").prop("checked", true);
                    
                    if(data.result.harilibur == true) $("#harilibur").prop("checked", true);
                }
                else{
                    $('.blokOtoritas').select2({
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
                }
			}
		})
    });

    $('#form_otoritas').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/user/otoritas',
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
                    $('#userAdmin').DataTable().ajax.reload(function(){}, false);
                    setTimeout(function(){
                        $('#myOtoritas').modal('hide');
                    }, 4000);
                }
				$('#result_otoritas').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
            }
		});
    });

    $(document).on('click', '.reset', function(){
        id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({       
			url: '/user/reset/'+id,
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                $('#resetModal').modal('show');
				var html = '';
				if(data.errors)
				{
                    html = data.errors;
				}
				if(data.success)
				{
					html = data.success;
                }
				$('#password_baru').html(html);
            }
		});
    });

    $('[type=tel]').on('change', function(e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    });

    $('[type=tel]').on('keypress', function(e) {
        keys = ['0','1','2','3','4','5','6','7','8','9']
        return keys.indexOf(e.key) > -1
    });
});