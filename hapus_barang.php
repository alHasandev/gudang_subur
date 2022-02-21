<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM barang WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_barang.php");
} else {
  die("Gagal menghapus barang: " . $conn->error);
}
