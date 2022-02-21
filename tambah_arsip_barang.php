<?php

require_once 'app/koneksi.php';

// cek apakah sudah ada arsip barang
$arsip = $conn->query("SELECT * FROM arsip_barang WHERE id_barang = '$_POST[id_barang]' AND kondisi = '$_POST[kondisi]'");
if ($arsip->num_rows > 0) {
  $query = "UPDATE arsip_barang SET tgl_arsip = '$_POST[tgl_arsip]', jumlah = jumlah + '$_POST[jumlah]', keterangan = '$_POST[keterangan]' WHERE id_barang = '$_POST[id_barang]' AND kondisi = '$_POST[kondisi]'";
} else {
  $query = "INSERT INTO arsip_barang (id_barang, kondisi, tgl_arsip, jumlah, keterangan) VALUE ('$_POST[id_barang]', '$_POST[kondisi]', '$_POST[tgl_arsip]', '$_POST[jumlah]', '$_POST[keterangan]')";
}

// echo json_encode($_POST);
// die;

$hasil = $conn->query($query);

if ($hasil) {
  // kurangi jumlah pada kondisi barang
  $conn->query("UPDATE kondisi_barang SET jumlah = jumlah - '$_POST[jumlah]' WHERE id_barang = '$_POST[id_barang]' AND id_kondisi = '$_POST[id_kondisi]'");

  // kurangi stok barang
  $conn->query("UPDATE barang SET stok = stok - '$_POST[jumlah]' WHERE id = '$_POST[id_barang]'");

  // kurangi kondisi barang jika ada
  // $conn->query("UPDATE kondisi_barang SET jumlah = jumlah - '$_POST[jumlah]' WHERE id_barang = '$_POST[id_barang]' AND id_kondisi = '$_POST[id_kondisi]'");

  header("Location: kondisi_barang.php?id_barang=$_POST[id_barang]");
} else {
  die("Gagal menambah arsip barang: " . $conn->error);
}
