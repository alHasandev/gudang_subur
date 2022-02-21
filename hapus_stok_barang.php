<?php

require_once 'app/koneksi.php';

$query = "UPDATE stok_barang SET stok_fisik = '', keterangan = '' WHERE id = '$_GET[id]'";


$hasil = $conn->query($query);

if ($hasil) {
  header("Location: stok_barang.php");
} else {
  die("Gagal mencek stok barang: " . $conn->error);
}
