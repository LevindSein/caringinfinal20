<?php
// membaca data dari form
$nomorsurat = $_POST['nomorsurat'];
$nobulan = $_POST['nobulan'];
$hari = $_POST['hari'];
$tanggal = $_POST['tanggal'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$noserimeter = $_POST['noserimeter'];
$awalmeterair = $_POST['awalmeterair'];
$ume1 = $_POST['ume1'];
$ume2 = $_POST['ume2'];
$ume3 = $_POST['ume3'];
// memanggil dan membaca template dokumen yang telah kita buat
$document = file_get_contents("B3.rtf");
// isi dokumen dinyatakan dalam bentuk string
$document = str_replace("#KOPNOMOR", $nomorsurat, $document);
$document = str_replace("#NOBULAN", $nobulan, $document);
$document = str_replace("#HARI", $hari, $document);
$document = str_replace("#TANGGAL", $tanggal, $document);
$document = str_replace("#BULAN", $bulan, $document);
$document = str_replace("#TAHUN", $tahun, $document);
$document = str_replace("#NAMA", $nama, $document);
$document = str_replace("#ALAMAT", $alamat, $document);
$document = str_replace("#NOSERIMETER", $noserimeter, $document);
$document = str_replace("#AWALMETERAIR", $awalmeterair, $document);
$document = str_replace("#UME1", $ume1, $document);
$document = str_replace("#UME2", $ume2, $document);
$document = str_replace("#UME3", $ume3, $document);
// header untuk membuka file output RTF dengan MS. Word
header("Content-type: application/msword");
header("Content-disposition: inline; filename=pemasangan_meter_air.doc");
header("Content-length: ".strlen($document));
echo $document;
?>