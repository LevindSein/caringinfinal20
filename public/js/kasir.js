//Show Tagihan
$(document).ready(function () {
    var table = $(
        '#tableTest'
    ).DataTable({
        "processing": true,
        "bProcessing": true,
        "language": {
            'loadingRecords': '&nbsp;',
            'processing': '<i class="fas fa-spinner"></i>'
        },
        "dom": "r<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'" +
                "row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "scrollX": true,
        "deferRender": true,
        "responsive": true,
        pageLength: 3,
        order: [],
        columnDefs: [ {
            'targets': [0], /* column index [0,1,2,3]*/
            'orderable': false, /* true or false */
        }],
    });
    new $.fn.dataTable.FixedHeader(table);
});


$(document).ready(function () {
    $('#myModal').on('shown.bs.modal', function () {
        $('#kode').trigger('focus');
    });
});

$('#kode').on('keypress', function (event) {
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
    event.preventDefault();
    return false;
    }
});

function ajax_tagihan(id,url){
    document.getElementById("tempatId").value = id;
    jQuery.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function(response) {
            if(response.tagihanListrik != 0){
                $("#testListrik").fadeIn();
                document.getElementById("checkListrik").disabled = false;
                document.getElementById("checkListrik").checked = true;
                document.getElementById("testListrik").hidden = false;
                document.getElementById("divListrik").hidden = false;
                document.getElementById("nominalListrik").innerHTML = response.tagihanListrik;
            }
            else{
                document.getElementById("checkListrik").disabled = true;
                document.getElementById("checkListrik").checked = false;
                document.getElementById("testListrik").hidden = true;
                document.getElementById("divListrik").hidden = true;
            }

            if(response.tagihanAirBersih != 0){
                $("#testAirBersih").fadeIn();
                document.getElementById("checkAirBersih").disabled = false;
                document.getElementById("checkAirBersih").checked = true;
                document.getElementById("testAirBersih").hidden = false;
                document.getElementById("divAirBersih").hidden = false;
                document.getElementById("nominalAirBersih").innerHTML = response.tagihanAirBersih;
            }
            else{
                document.getElementById("checkAirBersih").disabled = true;
                document.getElementById("checkAirBersih").checked = false;
                document.getElementById("testAirBersih").hidden = true;
                document.getElementById("divAirBersih").hidden = true;
            }

            if(response.tagihanKeamananIpk != 0){
                $("#testKeamananIpk").fadeIn();
                document.getElementById("checkKeamananIpk").disabled = false;
                document.getElementById("checkKeamananIpk").checked = true;
                document.getElementById("testKeamananIpk").hidden = false;
                document.getElementById("divKeamananIpk").hidden = false;
                document.getElementById("nominalKeamananIpk").innerHTML = response.tagihanKeamananIpk;
            }
            else{
                document.getElementById("checkKeamananIpk").disabled = true;
                document.getElementById("checkKeamananIpk").checked = false;
                document.getElementById("testKeamananIpk").hidden = true;
                document.getElementById("divKeamananIpk").hidden = true;
            }

            if(response.tagihanKebersihan != 0){
                $("#testKebersihan").fadeIn();
                document.getElementById("checkKebersihan").disabled = false;
                document.getElementById("checkKebersihan").checked = true;
                document.getElementById("testKebersihan").hidden = false;
                document.getElementById("divKebersihan").hidden = false;
                document.getElementById("nominalKebersihan").innerHTML = response.tagihanKebersihan;
            }
            else{
                document.getElementById("checkKebersihan").disabled = true;
                document.getElementById("checkKebersihan").checked = false;
                document.getElementById("testKebersihan").hidden = true;
                document.getElementById("divKebersihan").hidden = true;
            }

            if(response.tagihanAirKotor != 0){
                $("#testAirKotor").fadeIn();
                document.getElementById("checkAirKotor").disabled = false;
                document.getElementById("checkAirKotor").checked = true;
                document.getElementById("testAirKotor").hidden = false;
                document.getElementById("divAirKotor").hidden = false;
                document.getElementById("nominalAirKotor").innerHTML = response.tagihanAirKotor;
            }
            else{
                document.getElementById("checkAirKotor").disabled = true;
                document.getElementById("checkAirKotor").checked = false;
                document.getElementById("testAirKotor").hidden = true;
                document.getElementById("divAirKotor").hidden = true;
            }

            if(response.tagihanLain != 0){
                $("#testLain").fadeIn();
                document.getElementById("checkLain").disabled = false;
                document.getElementById("checkLain").checked = true;
                document.getElementById("testLain").hidden = false;
                document.getElementById("divLain").hidden = false;
                document.getElementById("nominalLain").innerHTML = response.tagihanLain;
            }
            else{
                document.getElementById("checkLain").disabled = true;
                document.getElementById("checkLain").checked = false;
                document.getElementById("testLain").hidden = true;
                document.getElementById("divLain").hidden = true;
            }
            
            if(response.tagihanTunggakan != 0){
                document.getElementById("divTunggakan").style.display = "flex";
                document.getElementById("nominalTunggakan").innerHTML = response.tagihanTunggakan;
            }
            else{
                document.getElementById("divTunggakan").style.display = "none";
            }

            if(response.tagihanDenda != 0){
                document.getElementById("divDenda").style.display = "flex";
                document.getElementById("nominalDenda").innerHTML = response.tagihanDenda;
            }
            else{
                document.getElementById("divDenda").style.display = "none";
            }
            
            document.getElementById("nominalTotal").innerHTML = response.tagihanTotal;
        }
    }).fail(function () {
        alert("Oops! Terjadi Kesalahan Sistem");
    });
    $('#rincianTagihan').modal('show');
}



function jumlah(total,harga,operation) {
    var explode = harga.split(",");
    var implode = explode.join("");
    var harga = parseInt(implode);
    if(operation == 'add'){
        var total = total + harga;
    }
    else{
        var total = total - harga;
    }
    return total;
}
function rincian(data) {
    var total = document.getElementById("nominalTotal").innerHTML;
    var explode = total.split(",");
    var implode = explode.join("");
    var total = parseInt(implode);

    if(data == 'listrik'){
        if(document.getElementById("checkListrik").checked){
            $("#testListrik").fadeIn();
            var harga = document.getElementById("nominalListrik").innerHTML;
            var total = jumlah(total,harga,'add');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
        else{
            $("#testListrik").fadeOut();
            var harga = document.getElementById("nominalListrik").innerHTML;
            var total = jumlah(total,harga,'sub');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
    }

    if(data == 'airbersih'){
        if(document.getElementById("checkAirBersih").checked){
            $("#testAirBersih").fadeIn();
            var harga = document.getElementById("nominalAirBersih").innerHTML;
            var total = jumlah(total,harga,'add');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
        else{
            $("#testAirBersih").fadeOut();
            var harga = document.getElementById("nominalAirBersih").innerHTML;
            var total = jumlah(total,harga,'sub');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
    }

    if(data == 'keamananipk'){
        if(document.getElementById("checkKeamananIpk").checked){
            $("#testKeamananIpk").fadeIn();
            var harga = document.getElementById("nominalKeamananIpk").innerHTML;
            var total = jumlah(total,harga,'add');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
        else{
            $("#testKeamananIpk").fadeOut();
            var harga = document.getElementById("nominalKeamananIpk").innerHTML;
            var total = jumlah(total,harga,'sub');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
    }

    if(data == 'kebersihan'){
        if(document.getElementById("checkKebersihan").checked){
            $("#testKebersihan").fadeIn();
            var harga = document.getElementById("nominalKebersihan").innerHTML;
            var total = jumlah(total,harga,'add');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
        else{
            $("#testKebersihan").fadeOut();
            var harga = document.getElementById("nominalKebersihan").innerHTML;
            var total = jumlah(total,harga,'sub');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
    }
    
    if(data == 'airkotor'){
        if(document.getElementById("checkAirKotor").checked){
            $("#testAirKotor").fadeIn();
            var harga = document.getElementById("nominalAirKotor").innerHTML;
            var total = jumlah(total,harga,'add');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
        else{
            $("#testAirKotor").fadeOut();
            var harga = document.getElementById("nominalAirKotor").innerHTML;
            var total = jumlah(total,harga,'sub');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
    }

    if(data == 'lain'){
        if(document.getElementById("checkLain").checked){
            $("#testLain").fadeIn();
            var harga = document.getElementById("nominalLain").innerHTML;
            var total = jumlah(total,harga,'add');
            document.getElementById("nominalLain").innerHTML = total.toLocaleString();
        }
        else{
            $("#testLain").fadeOut();
            var harga = document.getElementById("nominalLain").innerHTML;
            var total = jumlah(total,harga,'sub');
            document.getElementById("nominalTotal").innerHTML = total.toLocaleString();
        }
    }
}

document.getElementById("printStruk").onclick = function strukPembayaran() {
    var id = document.getElementById("tempatId").value;
    var btn = document.getElementById("printStruk");
    ajax_print('/kasir/bayar/' + id,btn);
}

//Print Via Bluetooth atau USB
function pc_print(data){
    var socket = new WebSocket("ws://127.0.0.1:40213/");
    socket.bufferType = "arraybuffer";
    socket.onerror = function(error) {  
        alert("Aktifkan Plugin RawBT untuk Cetak Struk");
    };			
    socket.onopen = function() {
        socket.send(data);
        socket.close(1000, "Work complete");
    };
}		
function android_print(data){
    window.location.href = data;  
}
function ajax_print(url, btn) {
    b = $(btn);
    b.attr('data-old', 'Sedang Diproses');
    b.text('Tunggu');
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
    }).always(function () {
        b.text(b.attr('data-old'));
        const button = document.getElementById('printStruk');
        button.disabled = true;
    });
}

function printerChoose(){
    jQuery.ajax({
        type: "GET",
        url: '/kasir/printer/choose/' + document.getElementById('printer').value
    });
}