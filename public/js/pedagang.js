$(document).ready(function(){
	$('#tabelPedagang').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/pedagang",
            cache:false,
		},
		columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
			{ data: 'nama', name: 'nama', class : 'text-center' },
			{ data: 'anggota', name: 'anggota', class : 'text-center' },
			{ data: 'ktp', name: 'ktp', class : 'text-center' },
			{ data: 'email', name: 'email', class : 'text-center' },
			{ data: 'hp', name: 'hp', class : 'text-center' },
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
            { "bSortable": false, "aTargets": [2,3,4,5,6] }, 
            { "bSearchable": false, "aTargets": [6] }
        ]
    });

    var id;

    $('#add_pedagang').click(function(){
		$('.modal-title').text('Tambah Pedagang');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_pedagang')[0].reset();
        $('#displayPemilik').hide();
        $('#displayPengguna').hide();
        $('#alamatPemilik').select2("destroy").select2({
            placeholder: '--- Pilih Kepemilikan ---',
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

        $('#alamatPengguna').select2("destroy").select2({
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

		$('#myModal').modal('show');
	});

    $('#form_pedagang').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/pedagang";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/pedagang/update";
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
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
					$('#form_pedagang')[0].reset();
                    $('#tabelPedagang').DataTable().ajax.reload(function(){}, false);
                    $('#displayPemilik').hide();
                    $('#displayPengguna').hide();
                    $('#alamatPemilik').select2("destroy").select2({
                        placeholder: '--- Pilih Kepemilikan ---',
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

                    $('#alamatPengguna').select2("destroy").select2({
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
				}
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
                $('#myModal').modal('hide');
			}
		});
    });

    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
		$('#form_result').html('');
		$('#alamatPemilik').select2('val','');
		$('#alamatPemilik').html('');
		$('#alamatPengguna').select2('val','');
		$('#alamatPengguna').html('');
		$.ajax({
			url :"/pedagang/"+id+"/edit",
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

                if(data.result.checkPemilik == 'checked'){
                    $("#pemilik").prop("checked", true);
                    $("#displayPemilik").show();
                    $('#alamatPemilik').select2({
                        placeholder: '--- Pilih Kepemilikan ---',
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

                    var s1 = $("#alamatPemilik").select2({
                        placeholder: '--- Pilih Kepemilikan ---',
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
                    var valPemilik = data.result.pemilik;

                    valPemilik.forEach(function(e){
                        if(!s1.find('option:contains(' + e + ')').length) 
                            s1.append($('<option>').text(e));
                    });
                    s1.val(valPemilik).trigger("change"); 
                }
                else{
                    $("#pemilik").prop("checked", false);
                    $("#displayPemilik").hide();
                }
                
                if(data.result.checkPengguna == 'checked'){
                    $("#pengguna").prop("checked", true);
                    $("#displayPengguna").show();
                    var s2 = $("#alamatPengguna").select2({
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
                    var valPengguna = data.result.pengguna;
                    
                    valPengguna.forEach(function(e){
                        if(!s2.find('option:contains(' + e + ')').length) 
                            s2.append($('<option>').text(e));
                    });
                    s2.val(valPengguna).trigger("change"); 
                }
                else{
                    $('#alamatPengguna').select2("destroy").select2({
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
                    $("#pengguna").prop("checked", false);
                    $("#displayPengguna").hide();
                }
                
				$('#hidden_id').val(id);
				$('.modal-title').text('Edit Pedagang');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
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
    
    $('#nama').on('input',function(){
        var nama = $(this).val();
        var username = nama.replace(/\s/g,'');
        var username = username.slice(0, 7);
        var number = Math.floor(1000 + Math.random() * 9000);
        document.getElementById("username").value = username + number;

        var anggota = 'BP3C' + Math.floor(100000 + Math.random() * 982718);
        var anggota = anggota.slice(0, 10);
        document.getElementById("anggota").value = anggota;

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
			url:"/pedagang/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $('#form_result').hide();
				setTimeout(function(){
                    $('#confirmModal').modal('hide');
					$('#tabelPedagang').DataTable().ajax.reload(function(){}, false);
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

    $('.alamatPemilik').select2({
        placeholder: '--- Pilih Kepemilikan ---',
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

    $('.alamatPengguna').select2({
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
    
    function checkPemilik() {
        if ($('#pemilik').is(':checked')) {
            document
                .getElementById('alamatPemilik')
                .required = true;
        } else {
            document
                .getElementById('alamatPemilik')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkPemilik)
        .each(checkPemilik);

    function checkPengguna() {
        if ($('#pengguna').is(':checked')) {
            document
                .getElementById('alamatPengguna')
                .required = true;
        } else {
            document
                .getElementById('alamatPengguna')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkPengguna)
        .each(checkPengguna);

    $('[type=tel]').on('change', function(e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    });

    $('[type=tel]').on('keypress', function(e) {
        keys = ['0','1','2','3','4','5','6','7','8','9']
        return keys.indexOf(e.key) > -1
    });
    
});