<?php

require_once 'app/koneksi.php';

$konbar = $conn->query("SELECT * FROM kondisi_barang WHERE id_barang = '$_POST[id_barang]' AND id_kondisi = '$_POST[id_kondisi]'");

if ($konbar->num_rows > 0) {
  $query = "UPDATE kondisi_barang SET jumlah = jumlah + '$_POST[jumlah]' WHERE id_barang = '$_POST[id_barang]' AND id_kondisi = '$_POST[id_kondisi]'";
} else {
  $query = "INSERT INTO kondisi_barang (id_barang, id_kondisi, jumlah) VALUE ('$_POST[id_barang]', '$_POST[id_kondisi]', '$_POST[jumlah]')";
}


$hasil = $conn->query($query);

if ($hasil) {
  header("Location: kondisi_barang.php?id_barang=" . $_POST['id_barang']);
} else {
  die("Gagal menambah kondisi barang: " . $conn->error);
}
