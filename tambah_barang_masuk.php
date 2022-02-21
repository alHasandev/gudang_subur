<?php

require_once 'app/koneksi.php';

// coding tambah ke tabel transaksi
$query = "INSERT INTO barang_masuk (tgl_masuk, id_barang, id_supplier, jumlah, keterangan) VALUE ('$_POST[tgl_masuk]', '$_POST[id_barang]', '$_POST[id_supplier]', '$_POST[jumlah]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {

  $query = "UPDATE barang SET stok = stok + '$_POST[jumlah]' WHERE id = '$_POST[id_barang]'";

  echo $query;
  // die;

  $update_stok = $conn->query($query);

  header('Location: barang_masuk.php');
} else {
  die("Gagal menambah transaksi: " . $conn->error);
}
