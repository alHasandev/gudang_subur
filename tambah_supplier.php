<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO supplier (nama, kontak, alamat) VALUE ('$_POST[nama]', '$_POST[kontak]', '$_POST[alamat]')";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_supplier.php");
} else {
  die("Gagal menambah supplier: " . $conn->error);
}
