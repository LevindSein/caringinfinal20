$(document).ready(function(){
    $('#tabelTagihan').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/tagihan",
            cache:false,
		},
		columns: [
            { data: 'kd_kontrol'     , name: 'kd_kontrol'     , class : 'text-center' },
            { data: 'nama'           , name: 'nama'           , class : 'text-center' },
            { data: 'daya_listrik'   , name: 'daya_listrik'   , class : 'text-center' },
            { data: 'awal_listrik'   , name: 'awal_listrik'   , class : 'text-center' },
            { data: 'akhir_listrik'  , name: 'akhir_listrik'  , class : 'text-center' },
            { data: 'ttl_listrik'    , name: 'ttl_listrik'    , class : 'text-center' },
            { data: 'awal_airbersih' , name: 'awal_airbersih' , class : 'text-center' },
            { data: 'akhir_airbersih', name: 'akhir_airbersih', class : 'text-center' },
            { data: 'ttl_airbersih'  , name: 'ttl_airbersih'  , class : 'text-center' },
            { data: 'ttl_keamananipk', name: 'ttl_keamananipk', class : 'text-center' },
            { data: 'ttl_kebersihan' , name: 'ttl_kebersihan' , class : 'text-center' },
            { data: 'ttl_airkotor'   , name: 'ttl_airkotor'   , class : 'text-center' },
            { data: 'ttl_lain'       , name: 'ttl_lain'       , class : 'text-center' },
            { data: 'ttl_tagihan'    , name: 'ttl_tagihan'    , class : 'text-center' },
            { data: 'action'         , name: 'action'         , class : 'text-center' },
        ],
        order: [[ 0, "asc" ]],
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
                title: 'Data Tagihan',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                },
                titleAttr: 'Download Excel'
            }
        ],
        fixedColumns:   {
            "leftColumns": 2,
            "rightColumns": 2,
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [14] }, 
            { "bSearchable": false, "aTargets": [14] }
        ]
    });
});