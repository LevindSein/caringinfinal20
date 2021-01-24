$(document).ready(function () {
    $('#tabelKasir').DataTable({
        processing: true,
		serverSide: true,
		ajax: {
            url: "/kasir/harian/pendapatan?" +
                 "tgl_pendapatan=" + document.getElementById('harian_pendapatan').value,
            cache:false,
		},
		columns: [
			{ data: 'nama', name: 'nama', class : 'text-center'},
			{ data: 'keamanan_los', name: 'keamanan_los', class : 'text-center'},
			{ data: 'kebersihan_los', name: 'kebersihan_los', class : 'text-center'},
			{ data: 'kebersihan_pos', name: 'kebersihan_lebih_pos', class : 'text-center'},
			{ data: 'kebersihan_lebih_pos', name: 'kebersihan_lebih_pos', class : 'text-center'},
			{ data: 'abonemen', name: 'abonemen', class : 'text-center'},
			{ data: 'ket', name: 'ket', class : 'text-center'},
			{ data: 'total', name: 'total', class : 'text-center'},
			{ data: 'action', name: 'action', class : 'text-center'},
        ],
        pageLength: 3,
        stateSave: true,
        scrollX: true,
        deferRender: true,
        // dom : "r<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        responsive : true,
        ordering  : false
    }).columns.adjust().draw();

    var id;
    $(document).on('click', '.delete', function(){
		id = $(this).attr('id');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
            url:"/kasir/"+id,
            method:'DELETE',
            cache:false,
			dataType:"json",
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
				setTimeout(function(){
                    $('#confirmModal').modal('hide');
					$('#tabelKasir').DataTable().ajax.reload(function(){}, false);
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

    $(document).on('click', '#tambah', function(){
        $('.modal-title').text('Tambah Pendapatan Harian');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
        $('#form_manual')[0].reset();
		$('#myManual').modal('show');
    });

    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
        $('.modal-title').text('Edit Pendapatan Harian');
		$('#action_btn').val('Update');
        $('#action').val('Edit');
        $('#hidden_id').val(id);
        $('#form_manual')[0].reset();

        $.ajax({
			url :"/kasir/"+id+"/edit",
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#nama').val(data.result.nama);
                $('#keamananlos').val(data.result.keamanan_los.toLocaleString("en-US"));
                $('#kebersihanlos').val(data.result.kebersihan_los.toLocaleString("en-US"));
                $('#kebersihanpos').val(data.result.kebersihan_pos.toLocaleString("en-US"));
                $('#kebersihanlebihpos').val(data.result.kebersihan_lebih_pos.toLocaleString("en-US"));
                $('#abonemen').val(data.result.abonemen.toLocaleString("en-US"));
                $('#laporan').val(data.result.ket);
            }
        });

		$('#myManual').modal('show');
    });

    $('#form_manual').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/kasir/harian/add";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/kasir/harian/edit";
		}

		$.ajax({
			url: action_url,
            cache:false,
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
					$('#form_manual')[0].reset();
                    $('#tabelKasir').DataTable().ajax.reload(function(){}, false);
				}
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
                $('#myManual').modal('hide');
			}
		});
    });

    document
        .getElementById('keamananlos')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

    document
        .getElementById('kebersihanlos')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

    document
        .getElementById('kebersihanpos')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

    document
        .getElementById('kebersihanlebihpos')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

    document
        .getElementById('abonemen')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
});