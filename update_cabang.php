<?php

require_once 'app/koneksi.php';

$query = "UPDATE cabang SET nama_cabang = '$_POST[nama_cabang]', kontak = '$_POST[kontak]', alamat = '$_POST[alamat]', warna = '$_POST[warna]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_cabang.php");
} else {
  die("Gagal mengupdate cabang: " . $conn->error);
}
