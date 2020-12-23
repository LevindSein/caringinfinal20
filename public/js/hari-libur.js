$(document).ready(function () {
    $('#tabelHariLibur').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/utilities/harilibur",
		},
		columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center', orderable: false, width: "8%", searchable: false },
			{ data: 'tanggal', name: 'tanggal', class : 'text-center' },
			{ data: 'ket', name: 'ket', class : 'text-center' },
			{ data: 'action', name: 'action', class : 'text-center' },
        ],
        stateSave: true,
        scrollX: true,
        deferRender: true,
        pageLength: 8,
        dom: "r<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            {
                text: '<i class="fas fa-file-excel fa-lg"></i>',
                extend: 'excel',
                className: 'btn btn-success bg-gradient-success',
                title: 'Data Hari Libur',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                titleAttr: 'Download Excel'
            }
        ],
        fixedColumns:   {
            "leftColumns": 2,
            "rightColumns": 1,
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [3] }, 
            { "bSearchable": false, "aTargets": [3] }
        ]
    });

    $('#add_tanggal').click(function(){
		$('.modal-title').text('Tambah Hari Libur');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_harilibur')[0].reset();
		$('#myModal').modal('show');
    });

    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
		$('#form_result').html('');
		$.ajax({
			url :"/utilities/harilibur/edit/"+id,
			dataType:"json",
			success:function(data)
			{
                $('#tanggal').val(data.result.tanggal);
                $('#ket').val(data.result.ket);                
				$('#hidden_id').val(id);
				$('.modal-title').text('Edit Blok');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_harilibur').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/utilities/harilibur/store";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/utilities/harilibur/update";
		}

		$.ajax({
			url: action_url,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
				var html = '';
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                    $('#form_harilibur')[0].reset();
					$('#tabelHariLibur').DataTable().ajax.reload(function(){}, false);
				}
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
			}
		});
    });

    var user_id;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/utilities/harilibur/destroy/"+user_id,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
				setTimeout(function(){
                    $('#confirmModal').modal('hide');
                    $('#tabelHariLibur').DataTable().ajax.reload(function(){}, false);
				}, 4000);
                html = '<div class="alert alert-info" id="info-alert"> <strong>Info! </strong>' + data.status + '</div>';
                $('#confirm_result').html(html);     
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
            },
            complete:function(){
                $('#ok_button').text('Hapus');
            }
        })
    });
});