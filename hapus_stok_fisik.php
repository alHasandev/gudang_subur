<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM stok_barang WHERE stok_fisik > 0";


$hasil = $conn->query($query);

if ($hasil) {
  header("Location: stok_barang.php");
} else {
  die("Gagal mencek stok barang: " . $conn->error);
}
