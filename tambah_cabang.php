<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO cabang (nama_cabang, kontak, alamat, warna) VALUE ('$_POST[nama_cabang]', '$_POST[kontak]', '$_POST[alamat]', '$_POST[warna]')";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_cabang.php");
} else {
  die("Gagal menambah cabang: " . $conn->error);
}
