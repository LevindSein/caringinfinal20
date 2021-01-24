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
                //Test Print Logo
                // ajax_print('/test/data/test');

                $("#tempatId").val(kontrol);
                $("#judulRincian").html(kontrol);

                $("#pedagang").val(data.result.pedagang);
                $("#los").val(data.result.los);
                $("#lokasi").val(data.result.lokasi);
                $("#faktur").val(data.result.faktur);
                
                total = 0;

                //Listrik
                listrik = data.result.listrik;
                $("#nominalListrik").html(data.result.listrik.toLocaleString("en-US"));
                total = total + listrik;

                tunglistrik = data.result.tunglistrik;
                $("#tungnominalListrik").html(data.result.tunglistrik.toLocaleString("en-US"));
                total = total + tunglistrik;

                denlistrik = data.result.denlistrik;
                $("#dennominalListrik").html(data.result.denlistrik.toLocaleString("en-US"));
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
                
                $("#taglistrik").val(data.result.listrik);
                $("#tagtunglistrik").val(data.result.tunglistrik);
                $("#tagdenlistrik").val(data.result.denlistrik);
                
                $("#tagdylistrik").val(data.result.dylistrik);
                $("#tagawlistrik").val(data.result.awlistrik);
                $("#tagaklistrik").val(data.result.aklistrik);
                $("#tagpklistrik").val(data.result.pklistrik);

                $('#checkListrik').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - listrik - tunglistrik - denlistrik;
                        $("#fasListrik").hide();

                        $("#taglistrik").val(0);
                        $("#tagtunglistrik").val(0);
                        $("#tagdenlistrik").val(0);
                        $("#tagdylistrik").val(0);
                        $("#tagawlistrik").val(0);
                        $("#tagaklistrik").val(0);
                        $("#tagpklistrik").val(0);
                    }
                    else{
                        total = total + listrik + tunglistrik + denlistrik;
                        $("#fasListrik").show();

                        $("#taglistrik").val(data.result.listrik);
                        $("#tagtunglistrik").val(data.result.tunglistrik);
                        $("#tagdenlistrik").val(data.result.denlistrik);
                        $("#tagdylistrik").val(data.result.dylistrik);
                        $("#tagawlistrik").val(data.result.awlistrik);
                        $("#tagaklistrik").val(data.result.aklistrik);
                        $("#tagpklistrik").val(data.result.pklistrik);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Air Bersih
                airbersih = data.result.airbersih;
                $("#nominalAirBersih").html(data.result.airbersih.toLocaleString("en-US"));
                total = total + airbersih;

                tungairbersih = data.result.tungairbersih;
                $("#tungnominalAirBersih").html(data.result.tungairbersih.toLocaleString("en-US"));
                total = total + tungairbersih;

                denairbersih = data.result.denairbersih;
                $("#dennominalAirBersih").html(data.result.denairbersih.toLocaleString("en-US"));
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
                
                $("#tagairbersih").val(data.result.airbersih);
                $("#tagtungairbersih").val(data.result.tungairbersih);
                $("#tagdenairbersih").val(data.result.denairbersih);

                $("#tagawairbersih").val(data.result.awairbersih);
                $("#tagakairbersih").val(data.result.akairbersih);
                $("#tagpkairbersih").val(data.result.pkairbersih);

                $('#checkAirBersih').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - airbersih - tungairbersih - denairbersih;
                        $("#fasAirBersih").hide();
                        
                        $("#tagairbersih").val(0);
                        $("#tagtungairbersih").val(0);
                        $("#tagdenairbersih").val(0);
                        $("#tagawairbersih").val(0);
                        $("#tagakairbersih").val(0);
                        $("#tagpkairbersih").val(0);
                    }
                    else{
                        total = total + airbersih + tungairbersih + denairbersih;
                        $("#fasAirBersih").show();
                                
                        $("#tagairbersih").val(data.result.airbersih);
                        $("#tagtungairbersih").val(data.result.tungairbersih);
                        $("#tagdenairbersih").val(data.result.denairbersih);

                        $("#tagawairbersih").val(data.result.awairbersih);
                        $("#tagakairbersih").val(data.result.akairbersih);
                        $("#tagpkairbersih").val(data.result.pkairbersih);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Keamanan IPK
                keamananipk = data.result.keamananipk;
                $("#nominalKeamananIpk").html(data.result.keamananipk.toLocaleString("en-US"));
                total = total + keamananipk;

                tungkeamananipk = data.result.tungkeamananipk;
                $("#tungnominalKeamananIpk").html(data.result.tungkeamananipk.toLocaleString("en-US"));
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

                $("#tagkeamananipk").val(data.result.keamananipk);
                $("#tagtungkeamananipk").val(data.result.tungkeamananipk);
                $("#tagdenkeamananipk").val(data.result.denkeamananipk);

                $('#checkKeamananIpk').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - keamananipk - tungkeamananipk;
                        $("#fasKeamananIpk").hide();
                        
                        $("#tagkeamananipk").val(0);
                        $("#tagtungkeamananipk").val(0);
                        $("#tagdenkeamananipk").val(0);
                    }
                    else{
                        total = total + keamananipk + tungkeamananipk;
                        $("#fasKeamananIpk").show();

                        $("#tagkeamananipk").val(data.result.keamananipk);
                        $("#tagtungkeamananipk").val(data.result.tungkeamananipk);
                        $("#tagdenkeamananipk").val(data.result.denkeamananipk);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Kebersihan
                kebersihan = data.result.kebersihan;
                $("#nominalKebersihan").html(data.result.kebersihan.toLocaleString("en-US"));
                total = total + kebersihan;

                tungkebersihan = data.result.tungkebersihan;
                $("#tungnominalKebersihan").html(data.result.tungkebersihan.toLocaleString("en-US"));
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

                $("#tagkebersihan").val(data.result.kebersihan);
                $("#tagtungkebersihan").val(data.result.tungkebersihan);
                $("#tagdenkebersihan").val(data.result.denkebersihan);

                $('#checkKebersihan').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - kebersihan - tungkebersihan;
                        $("#fasKebersihan").hide();

                        $("#tagkebersihan").val(0);
                        $("#tagtungkebersihan").val(0);
                        $("#tagdenkebersihan").val(0);
                    }
                    else{
                        total = total + kebersihan + tungkebersihan;
                        $("#fasKebersihan").show();
                        
                        $("#tagkebersihan").val(data.result.kebersihan);
                        $("#tagtungkebersihan").val(data.result.tungkebersihan);
                        $("#tagdenkebersihan").val(data.result.denkebersihan);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Air Kotor
                airkotor = data.result.airkotor;
                $("#nominalAirKotor").html(data.result.airkotor.toLocaleString("en-US"));
                total = total + airkotor;

                tungairkotor = data.result.tungairkotor;
                $("#tungnominalAirKotor").html(data.result.tungairkotor.toLocaleString("en-US"));
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

                $("#tagairkotor").val(data.result.airkotor);
                $("#tagtungairkotor").val(data.result.tungairkotor);
                $("#tagdenairkotor").val(data.result.denairkotor);

                $('#checkAirKotor').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - airkotor - tungairkotor;
                        $("#fasAirKotor").hide();

                        $("#tagairkotor").val(0);
                        $("#tagtungairkotor").val(0);
                        $("#tagdenairkotor").val(0);
                    }
                    else{
                        total = total + airkotor + tungairkotor;
                        $("#fasAirKotor").show();

                        $("#tagairkotor").val(data.result.airkotor);
                        $("#tagtungairkotor").val(data.result.tungairkotor);
                        $("#tagdenairkotor").val(data.result.denairkotor);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Lain
                lain = data.result.lain;
                $("#nominalLain").html(data.result.lain.toLocaleString("en-US"));
                total = total + lain;

                tunglain = data.result.tunglain;
                $("#tungnominalLain").html(data.result.tunglain.toLocaleString("en-US"));
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

                $("#taglain").val(data.result.lain);
                $("#tagtunglain").val(data.result.tunglain);
                $("#tagdenlain").val(data.result.denlain);

                $('#checkLain').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - lain - tunglain;
                        $("#fasLain").hide();

                        $("#taglain").val(0);
                        $("#tagtunglain").val(0);
                        $("#tagdenlain").val(0);
                    }
                    else{
                        total = total + lain + tunglain;
                        $("#fasLain").show();

                        $("#taglain").val(data.result.lain);
                        $("#tagtunglain").val(data.result.tunglain);
                        $("#tagdenlain").val(data.result.denlain);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Total
                $("#nominalTotal").html("Rp. " + total.toLocaleString("en-US"));
                
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
				if(data.result.status == 'error')
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops ! </strong> Transaksi Gagal</div>';
				}
				if(data.result.status == 'success')
				{
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>Transaksi Berhasil</div>';
                    // testingdata(JSON.stringify(data.result));
                    if(data.result.totalTagihan != 0){
                        ajax_print('/kasir/bayar/' + JSON.stringify(data.result));
                    }
				}
                $('#tabelKasir').DataTable().ajax.reload(function(){}, false);
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

    function testingdata(data){
        window.open(
            '/test/data/' + data,
            '_blank'
        );
    }


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

    $(document).on('change', '#printer', function() {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
			url :"/kasir/printer",
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
                if(data.errors){
                    console.log(data.errors);
                }
                if(data.success){
                    console.log(data.success);
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

    function testingdata(data){
        window.open(
            '/test/data/' + data,
            '_blank'
        );
    }

    window.setInterval(function(){
        if(localStorage["update"] == "1"){
            localStorage["update"] = "0";
            $('#tabelKasir').DataTable().ajax.reload(function(){}, false);
        }
    }, 500);
});