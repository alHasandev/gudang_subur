<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO kategori (nama_kategori, satuan, keterangan) VALUE ('$_POST[nama_kategori]', '$_POST[satuan]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_kategori.php");
} else {
  die("Gagal menambah kategori: " . $conn->error);
}
