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
                document.getElementById("divListrik").style.display = "flex";
                document.getElementById("nominalListrik").innerHTML = response.tagihanListrik;
            }
            else{
                document.getElementById("divListrik").style.display = "none";
            }

            if(response.tagihanAirBersih != 0){
                document.getElementById("divAirBersih").style.display = "flex";
                document.getElementById("nominalAirBersih").innerHTML = response.tagihanAirBersih;
            }
            else{
                document.getElementById("divAirBersih").style.display = "none";
            }

            if(response.tagihanKeamananIpk != 0){
                document.getElementById("divKeamananIpk").style.display = "flex";
                document.getElementById("nominalKeamananIpk").innerHTML = response.tagihanKeamananIpk;
            }
            else{
                document.getElementById("divKeamananIpk").style.display = "none";
            }

            if(response.tagihanKebersihan != 0){
                document.getElementById("divKebersihan").style.display = "flex";
                document.getElementById("nominalKebersihan").innerHTML = response.tagihanKebersihan;
            }
            else{
                document.getElementById("divKebersihan").style.display = "none";
            }

            if(response.tagihanAirKotor != 0){
                document.getElementById("divAirKotor").style.display = "flex";
                document.getElementById("nominalAirKotor").innerHTML = response.tagihanAirKotor;
            }
            else{
                document.getElementById("divAirKotor").style.display = "none";
            }

            if(response.tagihanLain != 0){
                document.getElementById("divLain").style.display = "flex";
                document.getElementById("nominalLain").innerHTML = response.tagihanLain;
            }
            else{
                document.getElementById("divLain").style.display = "none";
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