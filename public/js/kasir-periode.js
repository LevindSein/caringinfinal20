//Show Tagihan
$(document).ready(function () {
    $('#tabelKasir').DataTable({
        processing: true,
		serverSide: true,
		ajax: {
            url: "/kasir/periode?" + 
                 "bulan="          + document.getElementById('bln_periode').value + 
                 "&tahun="         + document.getElementById('thn_periode').value,
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center', width: '25%' },
			{ data: 'tagihan', name: 'tagihan', class : 'text-center', width: '25%' },
			{ data: 'pengguna', name: 'pengguna', class : 'text-center', width: '20%' },
			{ data: 'lokasi', name: 'lokasi', class : 'text-center', width: '20%', orderable: false },
			{ data: 'action', name: 'action', class : 'text-center', width: '10%', orderable: false, searchable: false },
        ],
        pageLength: 3,
        stateSave: true,
        scrollX: true,
        deferRender: true,
        dom : "r<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        responsive : true,
    }).columns.adjust().draw();

    var kode_kontrol = '';
    $(document).on('click', '.bayar', function(){
        kontrol = $(this).attr('id');
        $("#divListrik").hide();
        $("#divAirBersih").hide();
        $("#divKeamananIpk").hide();
        $("#divKebersihan").hide();
        $("#divAirKotor").hide();
        $("#divDenda").hide();
        $("#divLain").hide();

        $("#checkListrik").prop("checked", false).prop("disabled", true);
        $("#checkAirBersih").prop("checked", false).prop("disabled", true);
        $("#checkKeamananIpk").prop("checked", false).prop("disabled", true);
        $("#checkKebersihan").prop("checked", false).prop("disabled", true);
        $("#checkAirKotor").prop("checked", false).prop("disabled", true);
        $("#checkLain").prop("checked", false).prop("disabled", true);
        
		$.ajax({
            url : "/kasir/rincian/periode/" + kontrol +
                  "?bulan="          + document.getElementById('bln_periode').value + 
                  "&tahun="         + document.getElementById('thn_periode').value,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $("#tempatId").val(kontrol);
                $("#judulRincian").html(kontrol);
                var total = 0;
                if(Number(data.result.listrik) != 0){
                    $("#checkListrik").prop("checked", true).prop("disabled", false);
                    $("#divListrik").show();
                    $("#nominalListrik").html(Number(data.result.listrik).toLocaleString());
                    total = total + Number(data.result.listrik);
                
                    $('#checkListrik').click(function() {
                        if(!$(this).is(':checked')){
                            total = total - Number(data.result.listrik);
                            $("#divListrik").hide();
                        }
                        else{
                            total = total + Number(data.result.listrik);
                            $("#divListrik").show();
                        }
                        $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    });
                }
                if(Number(data.result.airbersih) != 0){
                    $("#checkAirBersih").prop("checked", true).prop("disabled", false);
                    $("#divAirBersih").show();
                    $("#nominalAirBersih").html(Number(data.result.airbersih).toLocaleString());
                    total = total + Number(data.result.airbersih);
                    
                    $('#checkAirBersih').click(function() {
                        if(!$(this).is(':checked')){
                            total = total - Number(data.result.airbersih);
                            $("#divAirBersih").hide();
                        }
                        else{
                            total = total + Number(data.result.airbersih);
                            $("#divAirBersih").show();
                        }
                        $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    });
                }
                if(Number(data.result.keamananipk) != 0){
                    $("#checkKeamananIpk").prop("checked", true).prop("disabled", false);
                    $("#divKeamananIpk").show();
                    $("#nominalKeamananIpk").html(Number(data.result.keamananipk).toLocaleString());
                    total = total + Number(data.result.keamananipk);
                    
                    $('#checkKeamananIpk').click(function() {
                        if(!$(this).is(':checked')){
                            total = total - Number(data.result.keamananipk);
                            $("#divKeamananIpk").hide();
                        }
                        else{
                            total = total + Number(data.result.keamananipk);
                            $("#divKeamananIpk").show();
                        }
                        $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    });
                }
                if(Number(data.result.kebersihan) != 0){
                    $("#checkKebersihan").prop("checked", true).prop("disabled", false);
                    $("#divKebersihan").show();
                    $("#nominalKebersihan").html(Number(data.result.kebersihan).toLocaleString());
                    total = total + Number(data.result.kebersihan);
                    
                    $('#checkKebersihan').click(function() {
                        if(!$(this).is(':checked')){
                            total = total - Number(data.result.kebersihan);
                            $("#divKebersihan").hide();
                        }
                        else{
                            total = total + Number(data.result.kebersihan);
                            $("#divKebersihan").show();
                        }
                        $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    });
                }
                if(Number(data.result.airkotor) != 0){
                    $("#checkAirKotor").prop("checked", true).prop("disabled", false);
                    $("#divAirKotor").show();
                    $("#nominalAirKotor").html(Number(data.result.airkotor).toLocaleString());
                    total = total + Number(data.result.airkotor);

                    $('#checkAirKotor').click(function() {
                        if(!$(this).is(':checked')){
                            total = total - Number(data.result.airkotor);
                            $("#divAirKotor").hide();
                        }
                        else{
                            total = total + Number(data.result.airkotor);
                            $("#divAirKotor").show();
                        }
                        $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    });
                }
                if(Number(data.result.denda) != 0){
                    $("#divDenda").show();
                    $("#nominalDenda").html(Number(data.result.denda).toLocaleString());
                    total = total + Number(data.result.denda);
                }
                if(Number(data.result.lain) != 0){
                    $("#checkLain").prop("checked", true).prop("disabled", false);
                    $("#divLain").show();
                    $("#nominalLain").html(Number(data.result.lain).toLocaleString());
                    total = total + Number(data.result.lain);
                    
                    $('#checkLain').click(function() {
                        if(!$(this).is(':checked')){
                            total = total - Number(data.result.lain);
                            $("#divLain").hide();
                        }
                        else{
                            total = total + Number(data.result.lain);
                            $("#divLain").show();
                        }
                        $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    });
                }
                $('#nominalTotal').html('Rp. ' + total.toLocaleString());

                $('#myRincian').modal('show');
			}
        })
        kode_kontrol = kontrol;
    });

    $('#form_rincian').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/kasir/periode',
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
				var html = '';
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                    // ajax_print('/kasir/bayar/periode' + kode_kontrol);
					$('#tabelKasir').DataTable().ajax.reload(function(){}, false);
				}
                $('#myRincian').modal('hide');
                $('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
			}
		});
    });

    $('#myModal').on('shown.bs.modal', function () {
        $('#kode').trigger('focus');
    });

    $('#kode').on('keypress', function (event) {
        var regex = new RegExp("^[0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });

    //Print Via Bluetooth atau USB
    function pc_print(data){
        var socket = new WebSocket("ws://127.0.0.1:40213/");
        socket.bufferType = "arraybuffer";
        socket.onerror = function(error) {  
            alert("Transaksi Berhasil Tanpa Print Struk");
        };			
        socket.onopen = function() {
            socket.send(data);
            socket.close(1000, "Work complete");
        };
    }	

    function android_print(data){
        window.location.href = data;  
    }

    function ajax_print(url) {
        $.get(url, function (data) {
            var ua = navigator.userAgent.toLowerCase();
            var isAndroid = ua.indexOf("android") > -1; 
            if(isAndroid) {
                android_print(data);
            }else{
                pc_print(data);
            }
        }).fail(function () {
            alert("Gagal Melakukan Print");
        });
    }
});