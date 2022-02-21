<?php

require_once 'app/koneksi.php';

$reture_barang = $conn->query("SELECT * FROM reture_barang WHERE id = '$_POST[id]'")->fetch_assoc();

$query = "UPDATE reture_barang SET id_barang = '$_POST[id_barang]', id_supplier = '$_POST[id_supplier]', id_kondisi = '$_POST[id_kondisi]', tgl_reture = '$_POST[tgl_reture]', jumlah = '$_POST[jumlah]', keterangan = '$_POST[keterangan]' WHERE id = '$_POST[id]'";

$hasil = $conn->query($query);

if ($hasil) {

  // kurangi stok barang
  $conn->query("UPDATE barang SET stok = (stok + '$reture_barang[jumlah]') - '$_POST[jumlah]' WHERE id = '$_POST[id_barang]'");

  // kurangi kondisi barang jika ada
  $conn->query("UPDATE kondisi_barang SET jumlah = (jumlah + '$reture_barang[jumlah]') - '$_POST[jumlah]' WHERE id_barang = '$_POST[id_barang]' AND id_kondisi = '$_POST[id_kondisi]'");

  header("Location: reture_barang.php");
} else {
  die("Gagal mengupdate reture barang: " . $conn->error);
}
