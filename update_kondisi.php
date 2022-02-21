<?php

require_once 'app/koneksi.php';

$query = "UPDATE kondisi SET nama = '$_POST[nama]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_kondisi.php");
} else {
  die("Gagal mengupdate kondisi: " . $conn->error);
}
