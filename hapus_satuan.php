<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM satuan WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_satuan.php");
} else {
  die("Gagal menghapus satuan: " . $conn->error);
}
