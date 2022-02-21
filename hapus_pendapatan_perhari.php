<?php

require_once 'app/koneksi.php';

// coding tambah ke tabel transaksi
$query = "DELETE FROM pendapatan_perhari WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {

  header('Location: pendapatan_perhari.php');
} else {
  die("Gagal menghapus transaksi: " . $conn->error);
}
