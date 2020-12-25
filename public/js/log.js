$(document).ready(function(){
	$('#tabelLog').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/log",
            cache:false,
		},
		columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center', orderable: false, width: "8%", searchable: false },
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
        fixedColumns:   {
            "leftColumns": 3,
            "rightColumns": 1,
        },
    });
});