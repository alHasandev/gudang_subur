<?php

require_once 'app/koneksi.php';

$barang_masuk = $conn->query("SELECT * FROM barang_masuk WHERE id = '$_POST[id]'")->fetch_assoc();

// coding tambah ke tabel transaksi
$query = "UPDATE barang_masuk SET tgl_masuk = '$_POST[tgl_masuk]', id_barang = '$_POST[id_barang]', id_supplier = '$_POST[id_supplier]', jumlah = '$_POST[jumlah]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {

  // kembalikan nilai stok barang lalu kurangi berdasarkan update
  $query = "UPDATE barang SET stok = (stok - '$barang_masuk[jumlah]') + '$_POST[jumlah]' WHERE id = '$_POST[id_barang]'";

  $update_stok = $conn->query($query);

  header('Location: barang_masuk.php');
} else {
  die("Gagal mengupdate barang masuk: " . $conn->error);
}
