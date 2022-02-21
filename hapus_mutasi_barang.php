<?php

require_once 'app/koneksi.php';

$mutasi_barang = $conn->query("SELECT * FROM mutasi_barang WHERE id = '$_GET[id]'")->fetch_assoc();

$query = "DELETE FROM mutasi_barang WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  // kembalikan nilai stok barang
  $query = "UPDATE barang SET stok = stok + '$mutasi_barang[jumlah]' WHERE id = '$mutasi_barang[id_barang]'";

  $update_stok = $conn->query($query);

  // update nilai stok cabang
  $query = "UPDATE stok_cabang SET stok = stok_cabang - '$mutasi_barang[jumlah]' WHERE id_cabang = '$mutasi_barang[id_cabang]' AND id_barang = '$mutasi_barang[id_barang]'";

  $update_stok = $conn->query($query);

  header("Location: mutasi_barang.php");
} else {
  die("Gagal menghapus mutasi_barang: " . $conn->error);
}
