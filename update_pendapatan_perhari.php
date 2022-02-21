<?php

require_once 'app/koneksi.php';

// coding tambah ke tabel transaksi
$query = "UPDATE pendapatan_perhari SET tanggal = '$_POST[tanggal]', id_cabang = '$_POST[id_cabang]', jumlah = '$_POST[jumlah]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {

  header('Location: pendapatan_perhari.php');
} else {
  die("Gagal mengupdate transaksi: " . $conn->error);
}
