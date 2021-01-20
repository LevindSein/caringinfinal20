<?php
// membaca data dari form
$nomorsurat = $_POST['nomorsurat'];
$nobln = $_POST['nobln'];
$hari = $_POST['hari'];
$tanggal = $_POST['tanggal'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$nama = $_POST['nama'];
$lokasi = $_POST['lokasi'];
$dayaawal = $_POST['dayaawal'];
$dayabaru = $_POST['dayabaru'];
$ume = $_POST['ume'];
// memanggil dan membaca template dokumen yang telah kita buat
$document = file_get_contents("G4.rtf");
// isi dokumen dinyatakan dalam bentuk string
$document = str_replace("#KOPNOMOR", $nomorsurat, $document);
$document = str_replace("#NOBLN", $nobln, $document);
$document = str_replace("#HARI", $hari, $document);
$document = str_replace("#TANGGAL", $tanggal, $document);
$document = str_replace("#BULAN", $bulan, $document);
$document = str_replace("#TAHUN", $tahun, $document);
$document = str_replace("#NAMA", $nama, $document);
$document = str_replace("#LOKASI", $lokasi, $document);
$document = str_replace("#DAYAAWAL", $dayaawal, $document);
$document = str_replace("#DAYABARU", $dayabaru, $document);
$document = str_replace("#UME", $ume, $document);
// header untuk membuka file output RTF dengan MS. Word
header("Content-type: application/msword");
header("Content-disposition: inline; filename=penurunan_daya_listrik.doc");
header("Content-length: ".strlen($document));
echo $document;
?>