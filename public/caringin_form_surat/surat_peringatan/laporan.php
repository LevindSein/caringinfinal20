<?php
// membaca data dari form
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$jmltagihan = $_POST['jmltagihan'];
$norekening = $_POST['norekening'];
$periode = $_POST['periode'];
$tglbatas = $_POST['tglbatas'];
$tanggal = $_POST['tanggal'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
// memanggil dan membaca template dokumen yang telah kita buat
$document = file_get_contents("SP.rtf");
// isi dokumen dinyatakan dalam bentuk string
$document = str_replace("#NAMA", $nama, $document);
$document = str_replace("#ALAMAT", $alamat, $document);
$document = str_replace("#JMLTAGIHAN", $jmltagihan, $document);
$document = str_replace("#NOREKENING", $norekening, $document);
$document = str_replace("#PERIODE", $periode, $document);
$document = str_replace("#TGLBATAS", $tglbatas, $document);
$document = str_replace("#TANGGAL", $tanggal, $document);
$document = str_replace("#BULAN", $bulan, $document);
$document = str_replace("#TAHUN", $tahun, $document);
// header untuk membuka file output RTF dengan MS. Word
header("Content-type: application/msword");
header("Content-disposition: attachment; filename=surat_peringatan.doc");
// header("Content-length: ".strlen($document));
echo $document;
?>