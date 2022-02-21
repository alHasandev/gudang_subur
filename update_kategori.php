<?php

require_once 'app/koneksi.php';

$query = "UPDATE kategori SET nama_kategori = '$_POST[nama_kategori]', satuan = '$_POST[satuan]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_kategori.php");
} else {
  die("Gagal mengupdate kategori: " . $conn->error);
}
