<?php

require_once 'app/koneksi.php';

$konbar = $conn->query("SELECT * FROM kondisi_barang WHERE id = '$_GET[id]'")->fetch_assoc();

$query = "DELETE FROM kondisi_barang WHERE id = '$_GET[id]'";

$hasil = $conn->query($query);

if ($hasil) {
  header("Location: kondisi_barang.php?id_barang=" . $konbar['id_barang']);
} else {
  die("Gagal menambah kondisi barang: " . $conn->error);
}
