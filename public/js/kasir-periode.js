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
        $('#form_rincian')[0].reset();
        var total = 0;
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
                total = 0;

                //Listrik
                listrik = data.result.listrik;
                $("#nominalListrik").html(data.result.listrik.toLocaleString("en-US"));
                total = total + listrik;

                denlistrik = data.result.denlistrik;
                $("#dennominalListrik").html(data.result.denlistrik.toLocaleString("en-US"));
                total = total + denlistrik;

                if(listrik == 0)
                    $("#divListrik").hide();
                else
                    $("#divListrik").show();
                if(denlistrik == 0)
                    $("#dendivListrik").hide();
                else
                    $("#dendivListrik").show();
                if(listrik == 0 && denlistrik == 0){
                    $("#fasListrik").hide();
                    $("#checkListrik").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasListrik").show();
                    $("#checkListrik").prop("checked", true).prop("disabled", false);
                }

                $('#checkListrik').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - listrik - denlistrik;
                        $("#fasListrik").hide();
                    }
                    else{
                        total = total + listrik + denlistrik;
                        $("#fasListrik").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Air Bersih
                airbersih = data.result.airbersih;
                $("#nominalAirBersih").html(data.result.airbersih.toLocaleString("en-US"));
                total = total + airbersih;

                denairbersih = data.result.denairbersih;
                $("#dennominalAirBersih").html(data.result.denairbersih.toLocaleString("en-US"));
                total = total + denairbersih;

                if(airbersih == 0)
                    $("#divAirBersih").hide();
                else
                    $("#divAirBersih").show();
                if(denairbersih == 0)
                    $("#dendivAirBersih").hide();
                else
                    $("#dendivAirBersih").show();
                if(airbersih == 0 && denairbersih == 0){
                    $("#fasAirBersih").hide();
                    $("#checkAirBersih").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasAirBersih").show();
                    $("#checkAirBersih").prop("checked", true).prop("disabled", false);
                }

                $('#checkAirBersih').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - airbersih - denairbersih;
                        $("#fasAirBersih").hide();
                    }
                    else{
                        total = total + airbersih + denairbersih;
                        $("#fasAirBersih").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Keamanan IPK
                keamananipk = data.result.keamananipk;
                $("#nominalKeamananIpk").html(data.result.keamananipk.toLocaleString("en-US"));
                total = total + keamananipk;

                if(keamananipk == 0)
                    $("#divKeamananIpk").hide();
                else
                    $("#divKeamananIpk").show();
                if(keamananipk == 0){
                    $("#fasKeamananIpk").hide();
                    $("#checkKeamananIpk").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasKeamananIpk").show();
                    $("#checkKeamananIpk").prop("checked", true).prop("disabled", false);
                }

                $('#checkKeamananIpk').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - keamananipk;
                        $("#fasKeamananIpk").hide();
                    }
                    else{
                        total = total + keamananipk;
                        $("#fasKeamananIpk").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Kebersihan
                kebersihan = data.result.kebersihan;
                $("#nominalKebersihan").html(data.result.kebersihan.toLocaleString("en-US"));
                total = total + kebersihan;

                if(kebersihan == 0)
                    $("#divKebersihan").hide();
                else
                    $("#divKebersihan").show();
                if(kebersihan == 0){
                    $("#fasKebersihan").hide();
                    $("#checkKebersihan").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasKebersihan").show();
                    $("#checkKebersihan").prop("checked", true).prop("disabled", false);
                }

                $('#checkKebersihan').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - kebersihan;
                        $("#fasKebersihan").hide();
                    }
                    else{
                        total = total + kebersihan;
                        $("#fasKebersihan").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Air Kotor
                airkotor = data.result.airkotor;
                $("#nominalAirKotor").html(data.result.airkotor.toLocaleString("en-US"));
                total = total + airkotor;

                if(airkotor == 0)
                    $("#divAirKotor").hide();
                else
                    $("#divAirKotor").show();
                if(airkotor == 0){
                    $("#fasAirKotor").hide();
                    $("#checkAirKotor").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasAirKotor").show();
                    $("#checkAirKotor").prop("checked", true).prop("disabled", false);
                }

                $('#checkAirKotor').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - airkotor;
                        $("#fasAirKotor").hide();
                    }
                    else{
                        total = total + airkotor;
                        $("#fasAirKotor").show();
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    $("#totalTagihan").val(total);
                });

                //Lain
                lain = data.result.lain;
                $("#nominalLain").html(data.result.lain.toLocaleString("en-US"));
                total = total + lain;

                if(lain == 0)
                    $("#divLain").hide();
                else
                    $("#divLain").show();
                if(lain == 0){
                    $("#fasLain").hide();
                    $("#checkLain").prop("checked", false).prop("disabled", true);
                }
                else{
                    $("#fasLain").show();
                    $("#checkLain").prop("checked", true).prop("disabled", false);
                }

                $('#checkLain').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - lain;
                        $("#fasLain").hide();
                    }
                    else{
                        total = total + lain;
                        $("#fasLain").show();
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
});