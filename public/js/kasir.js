//Show Tagihan
$(document).ready(function () {
    $('#tabelKasir').DataTable({
        processing: true,
		serverSide: true,
		ajax: {
            url: "/kasir",
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center', width: '25%' },
			{ data: 'tagihan', name: 'tagihan', class : 'text-center', width: '25%' },
			{ data: 'pengguna', name: 'pengguna', class : 'text-center', width: '20%' },
			{ data: 'lokasi', name: 'lokasi', class : 'text-center', width: '20%', orderable: false },
			{ data: 'action', name: 'action', class : 'text-center', width: '5%', orderable: false, searchable: false },
			{ data: 'prabayar', name: 'prabayar', class : 'text-center', width: '5%', orderable: false, searchable: false },
        ],
        pageLength: 3,
        stateSave: true,
        scrollX: true,
        deferRender: true,
        // dom : "r<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        responsive : true,
    }).columns.adjust().draw();

    var kode_kontrol = '';
    $(document).on('click', '.bayar', function(){
        kontrol = $(this).attr('id');
        $('#form_rincian')[0].reset();
        var total = 0;
        $.ajax({
			url :"/kasir/rincian/" + kontrol,
			dataType:"json",
            cache:false,
			success:function(data)
			{
                $("#tempatId").val(kontrol);
                $("#judulRincian").html(kontrol);
                total = 0;

                //Listrik
                listrik = data.result.listrik;
                $("#nominalListrik").html(data.result.listrik.toLocaleString());
                total = total + listrik;

                tunglistrik = data.result.tunglistrik;
                $("#tungnominalListrik").html(data.result.tunglistrik.toLocaleString());
                total = total + tunglistrik;

                denlistrik = data.result.denlistrik;
                $("#dennominalListrik").html(data.result.denlistrik.toLocaleString());
                total = total + denlistrik;

                if(listrik == 0)
                    $("#divListrik").hide();
                else
                    $("#divListrik").show();
                if(tunglistrik == 0)
                    $("#tungdivListrik").hide();
                else
                    $("#tungdivListrik").show();
                if(denlistrik == 0)
                    $("#dendivListrik").hide();
                else
                    $("#dendivListrik").show();
                if(listrik == 0 && tunglistrik == 0 && denlistrik == 0){
                    $("#fasListrik").hide();
                    $("#checkListrik").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasListrik").show();
                    $("#checkListrik").prop("checked", true).prop("disabled", false);
                }

                $('#checkListrik').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - listrik - tunglistrik - denlistrik;
                        $("#fasListrik").hide();
                    }
                    else{
                        total = total + listrik + tunglistrik + denlistrik;
                        $("#fasListrik").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    $("#totalTagihan").val(total);
                });

                //Air Bersih
                airbersih = data.result.airbersih;
                $("#nominalAirBersih").html(data.result.airbersih.toLocaleString());
                total = total + airbersih;

                tungairbersih = data.result.tungairbersih;
                $("#tungnominalAirBersih").html(data.result.tungairbersih.toLocaleString());
                total = total + tungairbersih;

                denairbersih = data.result.denairbersih;
                $("#dennominalAirBersih").html(data.result.denairbersih.toLocaleString());
                total = total + denairbersih;

                if(airbersih == 0)
                    $("#divAirBersih").hide();
                else
                    $("#divAirBersih").show();
                if(tungairbersih == 0)
                    $("#tungdivAirBersih").hide();
                else
                    $("#tungdivAirBersih").show();
                if(denairbersih == 0)
                    $("#dendivAirBersih").hide();
                else
                    $("#dendivAirBersih").show();
                if(airbersih == 0 && tungairbersih == 0 && denairbersih == 0){
                    $("#fasAirBersih").hide();
                    $("#checkAirBersih").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasAirBersih").show();
                    $("#checkAirBersih").prop("checked", true).prop("disabled", false);
                }

                $('#checkAirBersih').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - airbersih - tungairbersih - denairbersih;
                        $("#fasAirBersih").hide();
                    }
                    else{
                        total = total + airbersih + tungairbersih + denairbersih;
                        $("#fasAirBersih").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    $("#totalTagihan").val(total);
                });

                //Keamanan IPK
                keamananipk = data.result.keamananipk;
                $("#nominalKeamananIpk").html(data.result.keamananipk.toLocaleString());
                total = total + keamananipk;

                tungkeamananipk = data.result.tungkeamananipk;
                $("#tungnominalKeamananIpk").html(data.result.tungkeamananipk.toLocaleString());
                total = total + tungkeamananipk;

                if(keamananipk == 0)
                    $("#divKeamananIpk").hide();
                else
                    $("#divKeamananIpk").show();
                if(tungkeamananipk == 0)
                    $("#tungdivKeamananIpk").hide();
                else
                    $("#tungdivKeamananIpk").show();
                if(keamananipk == 0 && tungkeamananipk == 0){
                    $("#fasKeamananIpk").hide();
                    $("#checkKeamananIpk").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasKeamananIpk").show();
                    $("#checkKeamananIpk").prop("checked", true).prop("disabled", false);
                }

                $('#checkKeamananIpk').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - keamananipk - tungkeamananipk;
                        $("#fasKeamananIpk").hide();
                    }
                    else{
                        total = total + keamananipk + tungkeamananipk;
                        $("#fasKeamananIpk").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    $("#totalTagihan").val(total);
                });

                //Kebersihan
                kebersihan = data.result.kebersihan;
                $("#nominalKebersihan").html(data.result.kebersihan.toLocaleString());
                total = total + kebersihan;

                tungkebersihan = data.result.tungkebersihan;
                $("#tungnominalKebersihan").html(data.result.tungkebersihan.toLocaleString());
                total = total + tungkebersihan;

                if(kebersihan == 0)
                    $("#divKebersihan").hide();
                else
                    $("#divKebersihan").show();
                if(tungkebersihan == 0)
                    $("#tungdivKebersihan").hide();
                else
                    $("#tungdivKebersihan").show();
                if(kebersihan == 0 && tungkebersihan == 0){
                    $("#fasKebersihan").hide();
                    $("#checkKebersihan").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasKebersihan").show();
                    $("#checkKebersihan").prop("checked", true).prop("disabled", false);
                }

                $('#checkKebersihan').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - kebersihan - tungkebersihan;
                        $("#fasKebersihan").hide();
                    }
                    else{
                        total = total + kebersihan + tungkebersihan;
                        $("#fasKebersihan").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    $("#totalTagihan").val(total);
                });

                //Air Kotor
                airkotor = data.result.airkotor;
                $("#nominalAirKotor").html(data.result.airkotor.toLocaleString());
                total = total + airkotor;

                tungairkotor = data.result.tungairkotor;
                $("#tungnominalAirKotor").html(data.result.tungairkotor.toLocaleString());
                total = total + tungairkotor;

                if(airkotor == 0)
                    $("#divAirKotor").hide();
                else
                    $("#divAirKotor").show();
                if(tungairkotor == 0)
                    $("#tungdivAirKotor").hide();
                else
                    $("#tungdivAirKotor").show();
                if(airkotor == 0 && tungairkotor == 0){
                    $("#fasAirKotor").hide();
                    $("#checkAirKotor").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasAirKotor").show();
                    $("#checkAirKotor").prop("checked", true).prop("disabled", false);
                }

                $('#checkAirKotor').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - airkotor - tungairkotor;
                        $("#fasAirKotor").hide();
                    }
                    else{
                        total = total + airkotor + tungairkotor;
                        $("#fasAirKotor").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    $("#totalTagihan").val(total);
                });

                //Lain
                lain = data.result.lain;
                $("#nominalLain").html(data.result.lain.toLocaleString());
                total = total + lain;

                tunglain = data.result.tunglain;
                $("#tungnominalLain").html(data.result.tunglain.toLocaleString());
                total = total + tunglain;

                if(lain == 0)
                    $("#divLain").hide();
                else
                    $("#divLain").show();
                if(tunglain == 0)
                    $("#tungdivLain").hide();
                else
                    $("#tungdivLain").show();
                if(lain == 0 && tunglain == 0){
                    $("#fasLain").hide();
                    $("#checkLain").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasLain").show();
                    $("#checkLain").prop("checked", true).prop("disabled", false);
                }

                $('#checkLain').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - lain - tunglain;
                        $("#fasLain").hide();
                    }
                    else{
                        total = total + lain + tunglain;
                        $("#fasLain").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString());
                    $("#totalTagihan").val(total);
                });

                //Total
                $("#nominalTotal").html("Rp. " + total.toLocaleString());
                
                $("#totalTagihan").val(total);

                $('#myRincian').modal('show');
			}
        })
        kode_kontrol = kontrol;
    });

    $('#form_rincian').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/kasir',
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
                    ajax_print('/kasir/bayar/' + kode_kontrol);
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

    $(document).on('click', '.prabayar', function(){
        var kontrol = $(this).attr('id');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
			url :"/kasir/prabayar/"+kontrol,
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
                if(data.errors){
                    alert(data.errors);
                }
                if(data.success){
                    $('#tabelKasir').DataTable().ajax.reload(function(){}, false);
                }
            },
            error:function(data){
                console.log(data);
            }
        });
    });

    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#kode').trigger('focus');
    // });

    // $('#kode').on('keypress', function (event) {
    //     var regex = new RegExp("^[0-9]+$");
    //     var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    //     if (!regex.test(key)) {
    //     event.preventDefault();
    //     return false;
    //     }
    // });

    //Print Via Bluetooth atau USB
    function pc_print(data){
        var socket = new WebSocket("ws://127.0.0.1:40213/");
        socket.bufferType = "arraybuffer";
        socket.onerror = function(error) {  
            console.log("Transaksi Berhasil Tanpa Print Struk");
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
        }).fail(function (data) {
            console.log(data);
        });
    }

    window.setInterval(function(){
        if(localStorage["update"] == "1"){
            localStorage["update"] = "0";
            $('#tabelKasir').DataTable().ajax.reload(function(){}, false);
        }
    }, 500);
});