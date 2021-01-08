//Show Tagihan
$(document).ready(function () {
    $('#tabelKasir').DataTable({
        processing: true,
		serverSide: true,
		ajax: {
            url: "/kasir/restore",
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

    localStorage.setItem("update", "0");
    $(document).on('click', '.restore', function(){
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({       
			url: "/kasir/restore/"+id,
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				if(data.errors)
				{
                    alert(data.errors);
                    console.log(data.errors);
                    // location.reload();
				}
				if(data.success)
				{
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
});