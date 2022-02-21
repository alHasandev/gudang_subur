<?php

require_once 'app/koneksi.php';

if ($_POST['id'] !== '') {
  $query = "UPDATE stok_barang SET stok_fisik = '$_POST[stok_fisik]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";
} else {
  $query = "INSERT INTO stok_barang (id_barang, stok_fisik, keterangan) VALUE ('$_POST[id_barang]', '$_POST[stok_fisik]', '$_POST[keterangan]')";
}


$hasil = $conn->query($query);

if ($hasil) {
  header("Location: stok_barang.php");
} else {
  die("Gagal mencek stok barang: " . $conn->error);
}
