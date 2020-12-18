document.getElementById("cetakEdaran").onclick = function edaran() {
    var blok = document.getElementById("blok").value;
    var btn = document.getElementById("cetakEdaran");
    ajax_print('/tagihan/print/edaran/' + blok,btn);
}

//Print Via Bluetooth atau USB
function pc_print(data){
    var socket = new WebSocket("ws://127.0.0.1:40213/");
    socket.bufferType = "arraybuffer";
    socket.onerror = function(error) {
      alert("Plugin Printer RawBT belum aktif");
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
    b.attr('data-old', b.text());
    b.text('Proses');
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
    });
}