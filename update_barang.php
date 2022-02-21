<?php

require_once 'app/koneksi.php';

$query = "UPDATE barang SET nama_barang = '$_POST[nama_barang]', harga = '$_POST[harga]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_barang.php");
} else {
  die("Gagal mengupdate barang: " . $conn->error);
}
