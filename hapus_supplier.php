<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM supplier WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_supplier.php");
} else {
  die("Gagal menghapus supplier: " . $conn->error);
}
