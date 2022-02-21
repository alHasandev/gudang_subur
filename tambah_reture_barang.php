<?php

require_once 'app/koneksi.php';

$query = "INSERT INTO reture_barang (id_barang, id_supplier, id_kondisi, tgl_reture, jumlah, keterangan) VALUE ('$_POST[id_barang]', '$_POST[id_supplier]', '$_POST[id_kondisi]', '$_POST[tgl_reture]', '$_POST[jumlah]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {

  // kurangi stok barang
  $conn->query("UPDATE barang SET stok = stok - '$_POST[jumlah]' WHERE id = '$_POST[id_barang]'");

  // kurangi kondisi barang jika ada
  // $conn->query("UPDATE kondisi_barang SET jumlah = jumlah - '$_POST[jumlah]' WHERE id_barang = '$_POST[id_barang]' AND id_kondisi = '$_POST[id_kondisi]'");

  header("Location: reture_barang.php");
} else {
  die("Gagal menambah reture barang: " . $conn->error);
}
