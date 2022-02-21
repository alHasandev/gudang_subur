<?php

require_once 'app/koneksi.php';

// coding tambah ke tabel transaksi
$query = "INSERT INTO mutasi_barang (tgl_mutasi, id_barang, id_cabang, jumlah, keterangan) VALUE ('$_POST[tgl_mutasi]', '$_POST[id_barang]', '$_POST[id_cabang]', '$_POST[jumlah]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {
  // update stok barang 
  $query = "UPDATE barang SET stok = stok - '$_POST[jumlah]' WHERE id = '$_POST[id_barang]'";
  $update_stok = $conn->query($query);

  // cek apakah data stok barang sesuai cabang sudah ada
  $stok_cabang = $conn->query("SELECT * FROM stok_cabang WHERE id_cabang = '$_POST[id_cabang]' AND id_barang = '$_POST[id_barang]'");

  if ($stok_cabang->num_rows > 0) {
    // update stok pada cabang
    $query = "UPDATE stok_cabang SET stok = stok + '$_POST[jumlah]' WHERE id_cabang = '$_POST[id_cabang]' AND id_barang = '$_POST[id_barang]'";
  } else {
    // tambahkan data baru
    $query = "INSERT INTO stok_cabang (id_cabang, id_barang, stok) VALUE ('$_POST[id_cabang]', '$_POST[id_barang]', '$_POST[jumlah]')";
  }

  $update_stok = $conn->query($query);

  header('Location: mutasi_barang.php');
} else {
  die("Gagal menambah transaksi: " . $conn->error);
}
