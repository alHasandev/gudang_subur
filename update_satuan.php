<?php

require_once 'app/koneksi.php';

$query = "UPDATE satuan SET kode = '$_POST[kode]', nama_satuan = '$_POST[nama_satuan]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_satuan.php");
} else {
  die("Gagal mengupdate satuan: " . $conn->error);
}
