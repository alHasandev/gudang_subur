<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM admin WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header('Location: data_admin.php');
} else {
  die('Gagal menghapus data admin: ' . $conn->error);
}
