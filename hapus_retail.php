<?php

require_once 'app/koneksi.php';

$query = "DELETE FROM retail WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: data_retail.php");
} else {
  die("Gagal menghapus retail: " . $conn->error);
}
