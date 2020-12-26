$(document).ready(function () {
    $('#tabelPublish').DataTable({
        "iDisplayLength": -1,
        "bPaginate": true,
        "iCookieDuration": 60,
        "ordering":false,
        "bStateSave": false,
        "bAutoWidth": true,
        "bScrollAutoCss": true,
        "bProcessing": true,
        "bRetrieve": true,
        "bJQueryUI": true,
        //"sDom": 't',
        "sDom": '<"H"CTrf>t<"F"lip>',
        "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
        "sScrollY": "300px",
        // "sScrollX": "300px",
        //"sScrollXInner": "110%",
        "fnInitComplete": function() {
            this.css("visibility", "visible");
        },
    });

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
            },
            error: function(data){
                alert('Oops! Kesalahan Sistem');
                $('#process').hide();
                location.reload();
            }
		});
    });
});