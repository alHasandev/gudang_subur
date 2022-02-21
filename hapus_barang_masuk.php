<?php

require_once 'app/koneksi.php';

$barang_masuk = $conn->query("SELECT * FROM barang_masuk WHERE id = '$_GET[id]'")->fetch_assoc();

$query = "DELETE FROM barang_masuk WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  // kembalikan stok seperti semula
  $query = "UPDATE barang SET stok = stok - '$barang_masuk[jumlah]' WHERE id = '$barang_masuk[id_barang]'";

  $update_stok = $conn->query($query);

  header("Location: barang_masuk.php");
} else {
  die("Gagal menghapus barang_masuk: " . $conn->error);
}
