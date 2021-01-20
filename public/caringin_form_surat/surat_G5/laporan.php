<?php
// membaca data dari form
$kopnomor = $_POST['kopnomor'];
$nobln = $_POST['nobln'];
$hari = $_POST['hari'];
$tanggal = $_POST['tanggal'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$bongkar = $_POST['bongkar'];
$lokasi = $_POST['lokasi'];
$pemilik = $_POST['pemilik'];
$pelanggan = $_POST['pelanggan'];
$ume = $_POST['ume'];
// memanggil dan membaca template dokumen yang telah kita buat
$document = file_get_contents("G5.rtf");
// isi dokumen dinyatakan dalam bentuk string
$document = str_replace("#KOPNOMOR", $kopnomor, $document);
$document = str_replace("#NOBLN", $nobln, $document);
$document = str_replace("#HARI", $hari, $document);
$document = str_replace("#TANGGAL", $tanggal, $document);
$document = str_replace("#BULAN", $bulan, $document);
$document = str_replace("#TAHUN", $tahun, $document);
$document = str_replace("#BONGKAR", $bongkar, $document);
$document = str_replace("#LOKASI", $lokasi, $document);
$document = str_replace("#PEMILIK", $pemilik, $document);
$document = str_replace("#PELANGGAN", $pelanggan, $document);
$document = str_replace("#UME", $ume, $document);
// header untuk membuka file output RTF dengan MS. Word
header("Content-type: application/msword");
header("Content-disposition: attachment; filename=surat_pembongkaran.doc");
header("Content-length: ".strlen($document));
echo $document;
?>