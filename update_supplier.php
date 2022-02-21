<?php

require_once 'app/koneksi.php';

$query = "UPDATE supplier SET nama = '$_POST[nama]', kontak = '$_POST[kontak]', alamat = '$_POST[alamat]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_supplier.php");
} else {
  die("Gagal mengupdate supplier: " . $conn->error);
}
