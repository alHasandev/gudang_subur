<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM kategori WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_kategori.php");
} else {
  die("Gagal menghapus kategori: " . $conn->error);
}
