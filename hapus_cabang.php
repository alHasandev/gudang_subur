<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM cabang WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_cabang.php");
} else {
  die("Gagal menghapus cabang: " . $conn->error);
}
