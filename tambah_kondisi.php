<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO kondisi (nama, keterangan) VALUE ('$_POST[nama]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_kondisi.php");
} else {
  die("Gagal menambah kondisi: " . $conn->error);
}
