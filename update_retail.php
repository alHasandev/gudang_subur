<?php

require_once 'app/koneksi.php';

$query = "UPDATE retail SET nama_retail = '$_POST[nama_retail]', kontak = '$_POST[kontak]', alamat = '$_POST[alamat]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_retail.php");
} else {
  die("Gagal mengupdate retail: " . $conn->error);
}
