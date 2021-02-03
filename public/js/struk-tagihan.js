$(document).ready(function () {
    var scrollPosition;
    var rowIndex;
    var dtable = $('#tabelKasir').DataTable({
        processing: true,
		serverSide: true,
		ajax: {
            url: "/kasir/struk/tagihan",
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center', width: '25%' },
			{ data: 'bln_bayar', name: 'bln_bayar', class : 'text-center', width: '25%'},
			{ data: 'pedagang', name: 'pedagang', class : 'text-center', width: '20%' },
			{ data: 'lokasi', name: 'lokasi', class : 'text-center', width: '20%' },
			{ data: 'action', name: 'action', class : 'text-center', width: '5%' }
        ],
        pageLength: 10,
        stateSave: true,
        scrollX: true,
        scrollY: "35vh",
        ordering: false,
        lengthMenu: [[10,25,50,100,-1], [10,25,50,100,"All"]],
        deferRender: true,
        // dom : "r<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        responsive : true,
        "preDrawCallback": function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        "drawCallback": function( settings ) {
            $(".dataTables_scrollBody").scrollTop(scrollPosition);
            if(typeof rowIndex != 'undefined') {
                dtable.row(rowIndex).nodes().to$().addClass('row_selected');                       
            }
        },
    }).columns.adjust().draw();

    $(document).on('click', '.cetak', function(event){
        id = $(this).attr('id');
        ajax_print('/kasir/struk/tagihan/' + id);
        console.log('Printing . . .');
    });

    //Print Via Bluetooth atau USB
    function pc_print(data){
        var socket = new WebSocket("ws://127.0.0.1:40213/");
        socket.bufferType = "arraybuffer";
        socket.onerror = function(error) {  
            alert("Printer Not Ready");
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
        if(localStorage["struk"] == "1"){
            localStorage["struk"] = "0";
            $('#tabelKasir').DataTable().ajax.reload(function(){}, false);
        }
    }, 500);
});