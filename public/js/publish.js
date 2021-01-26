$(document).ready(function () {
    $('#tabelPublish').DataTable({
        processing: true,
		serverSide: true,
		ajax: {
			url: "/tagihan/publish",
            cache:false,
		},
		columns: [
            { data: 'kd_kontrol'     , name: 'kd_kontrol'     , class : 'text-center' },
            { data: 'nama'     , name: 'nama'     , class : 'text-center' },
            { data: 'daya_listrik'     , name: 'daya_listrik'     , class : 'text-center' },
            { data: 'awal_listrik'     , name: 'awal_listrik'     , class : 'text-center' },
            { data: 'akhir_listrik'     , name: 'akhir_listrik'     , class : 'text-center' },
            { data: 'pakai_listrik'     , name: 'pakai_listrik'     , class : 'text-center' },
            { data: 'ttl_listrik'     , name: 'ttl_listrik'     , class : 'text-center' },
            { data: 'awal_airbersih'     , name: 'awal_airbersih'     , class : 'text-center' },
            { data: 'akhir_airbersih'     , name: 'akhir_airbersih'     , class : 'text-center' },
            { data: 'pakai_airbersih'     , name: 'pakai_airbersih'     , class : 'text-center' },
            { data: 'ttl_airbersih'     , name: 'ttl_airbersih'     , class : 'text-center' },
            { data: 'dis_keamananipk'     , name: 'dis_keamananipk'     , class : 'text-center' },
            { data: 'ttl_keamananipk'     , name: 'ttl_keamananipk'     , class : 'text-center' },
            { data: 'dis_kebersihan'     , name: 'dis_kebersihan'     , class : 'text-center' },
            { data: 'ttl_kebersihan'     , name: 'ttl_kebersihan'     , class : 'text-center' },
            { data: 'ttl_airkotor'     , name: 'ttl_airkotor'     , class : 'text-center' },
            { data: 'ttl_lain'     , name: 'ttl_lain'     , class : 'text-center' },
            { data: 'ttl_tagihan'     , name: 'ttl_tagihan'     , class : 'text-center' },
            { data: 'ket'     , name: 'ket'     , class : 'text-center' },
            { data: 'verifikasi'     , name: 'verifikasi'     , class : 'text-center' },
        ],
        stateSave: true,
        pageLength: 100,
        "iDisplayLength": -1,
        "bPaginate": true,
        // "iCookieDuration": 60,
        // "ordering":false,
        "bStateSave": false,
        "bAutoWidth": true,
        "bScrollAutoCss": true,
        "bProcessing": true,
        "bRetrieve": true,
        "bJQueryUI": true,
        //"sDom": 't',
        "fixedColumns":   {
            "leftColumns": 2,
            "rightColumns": 3,
        },
        "sDom": '<"H"CTrf>t<"F"lip>',
        "aLengthMenu": [[100,-1], [100,"All"]],
        "sScrollY": "290px",
        "scrollX":true,
        // "sScrollX": "300px",
        //"sScrollXInner": "110%",
        "fnInitComplete": function() {
            this.css("visibility", "visible");
        },
    });

    localStorage.setItem("update", "0");
    $(document).on('click', '#publish', function(){
        $('#process').show();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({       
			url: "/tagihan/publish",
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
                    alert(data.success);
                    window.close();
                }

                localStorage.setItem("update", "1");
            },
            error: function(data){
                alert('Oops! Kesalahan Sistem');
                $('#process').hide();
                location.reload();
            }
		});
    });

    $(document).on('click', '.verification', function(){
        id = $(this).attr('id');
        $.ajax({
			url: "/tagihan/pesan/" + id,
            cache:false,
			method:"GET",
			dataType:"json",
			success:function(data)
			{
                if(data.errors){
                    alert(data.errors);
                }
                else{
                    if(data.result.status == 1){
                        $('#pesan').val(data.result.pesan);
                        $('#hidden_id').val(id);
                        $('#myChecking').modal('show');
                    }
                    else if(data.result.status == 2){
                        $.ajaxSetup({
                            headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url :"/tagihan/review/" + id,
                            cache:false,
                            method:"POST",
                            dataType:"json",
                            success:function(data)
                            {
                                if(data.errors){
                                    alert(data.errors);
                                }
                                if(data.success){
                                    $('#tabelPublish').DataTable().ajax.reload(function(){}, false);
                                }
                            }
                        });
                    }
                    else{
                        alert("Pengecekan Sedang Dilakukan, Tunggu beberapa saat lagi");
                    }
                }
            }
		});
    });

    $(document).on('submit', '#form_checking', function(event){
        event.preventDefault();
        $.ajax({
        	url :"/tagihan/review",
            cache:false,
        	method:"POST",
			data:$(this).serialize(),
        	dataType:"json",
        	success:function(data)
        	{
                if(data.errors){
                    alert(data.errors);
                }
                if(data.success){
                    alert(data.success);
                    $('#tabelPublish').DataTable().ajax.reload(function(){}, false);
                }

                $('#myChecking').modal('hide');
            }
        });
    });

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
                
                $('#myModal').modal('show');
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
                
                $('#myModal').modal('show');
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
                
                $('#myModal').modal('show');
            }
		});
    });
});