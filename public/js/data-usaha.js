$(document).ready(function(){
    $('#tagihan').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/datausaha",
            cache:false,
		},
		columns: [
            { data: 'bln_tagihan', name: 'bln_tagihan', class : 'text-center' },
            { data: 'ttl_listrik', name: 'ttl_listrik', class : 'text-center' },
            { data: 'dis_listrik', name: 'dis_listrik', class : 'text-center' },
            { data: 'rea_listrik', name: 'rea_listrik', class : 'text-center' },
            { data: 'sel_listrik', name: 'sel_listrik', class : 'text-center' },
            { data: 'ttl_airbersih', name: 'ttl_airbersih', class : 'text-center' },
            { data: 'dis_airbersih', name: 'dis_airbersih', class : 'text-center' },
            { data: 'rea_airbersih', name: 'rea_airbersih', class : 'text-center' },
            { data: 'sel_airbersih', name: 'sel_airbersih', class : 'text-center' },
            { data: 'ttl_keamananipk', name: 'ttl_keamananipk', class : 'text-center' },
            { data: 'dis_keamananipk', name: 'dis_keamananipk', class : 'text-center' },
            { data: 'rea_keamananipk', name: 'rea_keamananipk', class : 'text-center' },
            { data: 'sel_keamananipk', name: 'sel_keamananipk', class : 'text-center' },
            { data: 'ttl_kebersihan', name: 'ttl_kebersihan', class : 'text-center' },
            { data: 'dis_kebersihan', name: 'dis_kebersihan', class : 'text-center' },
            { data: 'rea_kebersihan', name: 'rea_kebersihan', class : 'text-center' },
            { data: 'sel_kebersihan', name: 'sel_kebersihan', class : 'text-center' },
            { data: 'ttl_airkotor', name: 'ttl_airkotor', class : 'text-center' },
            { data: 'rea_airkotor', name: 'rea_airkotor', class : 'text-center' },
            { data: 'sel_airkotor', name: 'sel_airkotor', class : 'text-center' },
            { data: 'ttl_lain', name: 'ttl_lain', class : 'text-center' },
            { data: 'rea_lain', name: 'rea_lain', class : 'text-center' },
            { data: 'sel_lain', name: 'sel_lain', class : 'text-center' },
            { data: 'ttl_tagihan', name: 'ttl_tagihan', class : 'text-center' },
            { data: 'dis_tagihan', name: 'dis_tagihan', class : 'text-center' },
            { data: 'rea_tagihan', name: 'rea_tagihan', class : 'text-center' },
            { data: 'sel_tagihan', name: 'sel_tagihan', class : 'text-center' },
        ],
        stateSave: true,
        scrollX: true,
        deferRender: true,
        pageLength: 8,
        fixedColumns:   {
            "leftColumns": 1,
            "rightColumns": 4,
        },
        ordering:false
    });

    $("#tab-c-1").click(function(){
        if (!$.fn.dataTable.isDataTable('#tunggakan')) {
            $('#tunggakan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/datausaha/tunggakan",
                    cache:false,
                },
                columns: [
                    { data: 'bln_tagihan', name: 'bln_tagihan', class : 'text-center' },
                    { data: 'sel_listrik', name: 'sel_listrik', class : 'text-center' },
                    { data: 'sel_airbersih', name: 'sel_airbersih', class : 'text-center' },
                    { data: 'sel_keamananipk', name: 'sel_keamananipk', class : 'text-center' },
                    { data: 'sel_kebersihan', name: 'sel_kebersihan', class : 'text-center' },
                    { data: 'sel_airkotor', name: 'sel_airkotor', class : 'text-center' },
                    { data: 'sel_lain', name: 'sel_lain', class : 'text-center' },
                    { data: 'sel_tagihan', name: 'sel_tagihan', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
                ordering:false
            });
        }
    });

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#bongkaran')) {
            $('#bongkaran').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/datausaha/bongkaran",
                    cache:false,
                },
                columns: [
                    { data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
                    { data: 'dendasatu', name: 'dendasatu', class : 'text-center' },
                    { data: 'dendadua', name: 'dendadua', class : 'text-center' },
                    { data: 'dendatiga', name: 'dendatiga', class : 'text-center' },
                    { data: 'dendaempat', name: 'dendaempat', class : 'text-center' },
                    { data: 'tunggakan', name: 'tunggakan', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' }
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
                ordering:false
            });
        }
    });

    $("#tab-c-3").click(function(){
        if (!$.fn.dataTable.isDataTable('#penghapusan')) {
            $('#penghapusan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/datausaha/penghapusan",
                    cache:false,
                },
                columns: [
                    { data: 'bln_tagihan', name: 'bln_tagihan', class : 'text-center' },
                    { data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'sel_listrik', name: 'sel_listrik', class : 'text-center' },
                    { data: 'sel_airbersih', name: 'sel_airbersih', class : 'text-center' },
                    { data: 'sel_keamananipk', name: 'sel_keamananipk', class : 'text-center' },
                    { data: 'sel_kebersihan', name: 'sel_kebersihan', class : 'text-center' },
                    { data: 'sel_airkotor', name: 'sel_airkotor', class : 'text-center' },
                    { data: 'sel_lain', name: 'sel_lain', class : 'text-center' },
                    { data: 'sel_tagihan', name: 'sel_tagihan', class : 'text-center' },
                    { data: 'via_hapus', name: 'via_hapus', class : 'text-center' }
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
                ordering:false
            });
        }
    });
});