<?php

require_once 'app/koneksi.php';

$mutasi_barang = $conn->query("SELECT * FROM mutasi_barang WHERE id = '$_POST[id]'")->fetch_assoc();

// coding tambah ke tabel transaksi
$query = "UPDATE mutasi_barang SET tgl_mutasi = '$_POST[tgl_mutasi]', id_barang = '$_POST[id_barang]', id_cabang = '$_POST[id_cabang]', jumlah = '$_POST[jumlah]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {

  // kembalikan nilai stok barang lalu kurangi berdasarkan update
  $query = "UPDATE barang SET stok = (stok + '$mutasi_barang[jumlah]') - '$_POST[jumlah]' WHERE id = '$_POST[id_barang]'";

  $update_stok = $conn->query($query);

  // update nilai stok cabang
  $query = "UPDATE stok_cabang SET stok = (stok - '$mutasi_barang[jumlah]') + '$_POST[jumlah]' WHERE id_cabang = '$mutasi_barang[id_cabang]' AND id_barang = '$mutasi_barang[id_barang]'";

  $update_stok = $conn->query($query);

  header('Location: mutasi_barang.php');
} else {
  die("Gagal mengupdate barang masuk: " . $conn->error);
}
