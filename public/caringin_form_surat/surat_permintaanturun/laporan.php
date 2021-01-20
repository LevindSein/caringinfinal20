<?php
// membaca data dari form
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$nokontrol = $_POST['nokontrol'];
$nokontrak = $_POST['nokontrak'];
$tarif = $_POST['tarif'];
$dayaawal = $_POST['dayaawal'];
$dayabaru = $_POST['dayabaru'];
$keperluan = $_POST['keperluan'];
$tanggal = $_POST['tanggal'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
// memanggil dan membaca template dokumen yang telah kita buat
$document = file_get_contents("surat_mohonturundaya.rtf");
// isi dokumen dinyatakan dalam bentuk string
$document = str_replace("#NAMA", $nama, $document);
$document = str_replace("#ALAMAT", $alamat, $document);
$document = str_replace("#NOKONTROL", $nokontrol, $document);
$document = str_replace("#NOKONTRAK", $nokontrak, $document);
$document = str_replace("#TARIF", $tarif, $document);
$document = str_replace("#DAYAAWAL", $dayaawal, $document);
$document = str_replace("#DAYABARU", $dayabaru, $document);
$document = str_replace("#KEPERLUAN", $keperluan, $document);
$document = str_replace("#TANGGAL", $tanggal, $document);
$document = str_replace("#BULAN", $bulan, $document);
$document = str_replace("#TAHUN", $tahun, $document);
// header untuk membuka file output RTF dengan MS. Word
header("Content-type: application/msword");
header("Content-disposition: inline; filename=surat_permintaan_penurunan_daya.doc");
header("Content-length: ".strlen($document));
echo $document;
?>