$(document).ready(function(){
    $('#harian').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "/rekap/pendapatan",
            cache:false,
		},
		columns: [
            { data: 'tgl_bayar', name: 'tgl_bayar', class : 'text-center' },
            { data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
            { data: 'pengguna', name: 'pengguna', class : 'text-center' },
            { data: 'byr_listrik', name: 'byr_listrik', class : 'text-center' },
            { data: 'byr_airbersih', name: 'byr_airbersih', class : 'text-center' },
            { data: 'byr_keamananipk', name: 'byr_keamananipk', class : 'text-center' },
            { data: 'byr_kebersihan', name: 'byr_kebersihan', class : 'text-center' },
            { data: 'byr_airkotor', name: 'byr_airkotor', class : 'text-center' },
            { data: 'byr_lain', name: 'byr_lain', class : 'text-center' },
            { data: 'ttl_tagihan', name: 'ttl_tagihan', class : 'text-center' },
            { data: 'realisasi', name: 'realisasi', class : 'text-center' },
            { data: 'sel_tagihan', name: 'sel_tagihan', class : 'text-center' },
            { data: 'via_bayar', name: 'via_bayar', class : 'text-center' },
            { data: 'nama', name: 'nama', class : 'text-center' },
        ],
        stateSave: true,
        scrollX: true,
        deferRender: true,
        pageLength: 8,
        fixedColumns:   {
            "leftColumns": 3,
            "rightColumns": 2,
        },
    });

    $("#tab-c-1").click(function(){
        if (!$.fn.dataTable.isDataTable('#bulanan')) {
            $('#bulanan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/rekap/pendapatan/bulanan",
                    cache:false,
                },
                columns: [
                    { data: 'bln_bayar', name: 'bln_bayar', class : 'text-center' },
                    { data: 'listrik', name: 'listrik', class : 'text-center' },
                    { data: 'airbersih', name: 'airbersih', class : 'text-center' },
                    { data: 'keamananipk', name: 'keamananipk', class : 'text-center' },
                    { data: 'kebersihan', name: 'kebersihan', class : 'text-center' },
                    { data: 'airkotor', name: 'airkotor', class : 'text-center' },
                    { data: 'lain', name: 'realisasi', class : 'text-center' },
                    { data: 'diskon', name: 'diskon', class : 'text-center' },
                    { data: 'realisasi', name: 'realisasi', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
            });
        }
    });

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#tahunan')) {
            $('#tahunan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/rekap/pendapatan/tahunan",
                    cache:false,
                },
                columns: [
                    { data: 'thn_bayar', name: 'thn_bayar', class : 'text-center' },
                    { data: 'listrik', name: 'listrik', class : 'text-center' },
                    { data: 'airbersih', name: 'airbersih', class : 'text-center' },
                    { data: 'keamananipk', name: 'keamananipk', class : 'text-center' },
                    { data: 'kebersihan', name: 'kebersihan', class : 'text-center' },
                    { data: 'airkotor', name: 'airkotor', class : 'text-center' },
                    { data: 'lain', name: 'realisasi', class : 'text-center' },
                    { data: 'diskon', name: 'diskon', class : 'text-center' },
                    { data: 'realisasi', name: 'realisasi', class : 'text-center' },
                ],
                stateSave: true,
                scrollX: true,
                deferRender: true,
                pageLength: 8,
                fixedColumns:   {
                    "leftColumns": 1,
                    "rightColumns": 1,
                },
            });
        }
    });
});