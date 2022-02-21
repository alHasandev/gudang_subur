<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO barang (id_kategori, nama_barang, harga, stok, keterangan) VALUE ('$_POST[id_kategori]', '$_POST[nama_barang]', '$_POST[harga]', '$_POST[stok]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_barang.php");
} else {
  die("Gagal menambah barang: " . $conn->error);
}
