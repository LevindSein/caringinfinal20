<?php
// membaca data dari form
$nomorsurat = $_POST['nomorsurat'];
$nobln = $_POST['nobln'];
$tanggal = $_POST['tanggal'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$biaya = $_POST['biaya'];
$tunggakan = $_POST['tunggakan'];
$jumlahbayar = $_POST['jumlahbayar'];
$admin = $_POST['admin'];
// memanggil dan membaca template dokumen yang telah kita buat
$document = file_get_contents("surat_perintahbayar.rtf");
// isi dokumen dinyatakan dalam bentuk string
$document = str_replace("#KOPNOMOR", $nomorsurat, $document);
$document = str_replace("#NOBLN", $nobln, $document);
$document = str_replace("#TGL", $tanggal, $document);
$document = str_replace("#BULAN", $bulan, $document);
$document = str_replace("#TAHUN", $tahun, $document);
$document = str_replace("#BIAYA", $biaya, $document);
$document = str_replace("#TUNGGAK", $tunggakan, $document);
$document = str_replace("#BAYAR", $jumlahbayar, $document);
$document = str_replace("#ADMIN", $admin, $document);
// header untuk membuka file output RTF dengan MS. Word
header("Content-type: application/msword");
header("Content-disposition: inline; filename=surat_perintahpembayaran.doc");
header("Content-length: ".strlen($document));
echo $document;
?>