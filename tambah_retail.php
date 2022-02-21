<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO retail (nama_retail, kontak, alamat) VALUE ('$_POST[nama_retail]', '$_POST[kontak]', '$_POST[alamat]')";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_retail.php");
} else {
  die("Gagal menambah retail: " . $conn->error);
}
