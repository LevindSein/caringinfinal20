$(document).ready(function () {
    $('#tabelPublish').DataTable({
        "iDisplayLength": -1,
        "bPaginate": true,
        "iCookieDuration": 60,
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
        "aLengthMenu": [[-1], ["All"]],
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

    $(document).on('click', '#review', function(){
        $('#process').show();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({       
			url: "/tagihan/review",
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				if(data.errors)
				{
                    $('#process').hide();
                    alert(data.errors);
                    // console.log(data.errors);
                    location.reload();
				}
				if(data.success)
				{
                    $('#process').hide();
                    alert(data.success);
                    location.reload();
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