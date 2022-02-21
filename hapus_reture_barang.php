<?php

require_once 'app/koneksi.php';

$reture_barang = $conn->query("SELECT * FROM reture_barang WHERE id = '$_GET[id]'")->fetch_assoc();

$query = "DELETE FROM reture_barang WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  // kembalikan nilai stok barang
  $query = "UPDATE barang SET stok = stok + '$reture_barang[jumlah]' WHERE id = '$reture_barang[id_barang]'";

  $update_stok = $conn->query($query);

  header("Location: reture_barang.php");
} else {
  die("Gagal menghapus reture_barang: " . $conn->error);
}
