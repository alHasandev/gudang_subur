<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO satuan (kode, nama_satuan, keterangan) VALUE ('$_POST[kode]', '$_POST[nama_satuan]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_satuan.php");
} else {
  die("Gagal menambah satuan: " . $conn->error);
}
