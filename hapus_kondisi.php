<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM kondisi WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header('Location: data_kondisi.php');
} else {
  die('Gagal menghapus data kondisi: ' . $conn->error);
}
