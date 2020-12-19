$(document).ready(function(){
	$('#tabelLog').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/log",
		},
		columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
			{ data: 'username', name: 'username', class : 'text-center' },
			{ data: 'nama', name: 'nama', class : 'text-center' },
			{ data: 'ktp', name: 'ktp', class : 'text-center' },
			{ data: 'hp', name: 'hp', class : 'text-center' },
			{ data: 'role', name: 'role', class : 'text-center' },
			{ data: 'platform', name: 'platform', class : 'text-center' },
			{ name: 'created_at', data: { '_': 'created_at.display', 'sort': 'created_at.timestamp' }, class : 'text-center'  }
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
                title: 'Data Riwayat Login',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                },
                titleAttr: 'Download Excel'
            }
        ],
        fixedColumns:   {
            "leftColumns": 3,
            "rightColumns": 1,
        },
    });
});