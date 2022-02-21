<?php

session_start();
require_once 'app/koneksi.php';

// siapkan data statik
$tgl_arsip = date('Y-m-d');
$username = $_SESSION['user']['username'];

$stok_barang = $conn->query("SELECT barang.nama_barang, barang.stok, stok_barang.stok_fisik, kategori.satuan, stok_barang.keterangan FROM stok_barang RIGHT JOIN barang ON stok_barang.id_barang = barang.id LEFT JOIN kategori ON barang.id_kategori = kategori.id");

// cek jika arsip stok pada tanggal yang sama sudah ada
$arsip_stok = $conn->query("SELECT * FROM arsip_stok WHERE tgl_arsip = '$tgl_arsip'");

// jika sudah ada, maka
if ($arsip_stok->num_rows) {
  // buat no_arsip yang baru
  $arsip = $arsip_stok->fetch_assoc();
  $no_arsip = $arsip['no_arsip'] + 1;
} else {
  // jika tidak ada, maka
  // reset no_arsip menjadi 1
  $no_arsip = 1;
}

// buat query values berdasarkan dataset stok_barang
$dataset_stok = $stok_barang->fetch_all(MYSQLI_NUM);
$values = [];
foreach ($dataset_stok as $i => $data) {
  $value = implode("', '", $data);
  array_push($values, "('$tgl_arsip', '$no_arsip', '$value', '$username')");
}

$values = implode(',', $values);

// echo json_encode($dataset_stok);
// echo $values;
// die;

// buat query untuk menambahkan arsip stok
$query = "INSERT INTO arsip_stok (tgl_arsip, no_arsip, nama_barang, stok, stok_fisik, satuan, keterangan, user) VALUES $values";

// echo $query;
// die;

$hasil = $conn->query($query);

if ($hasil) {
  // tambah data meta arsip stok untuk keperluan filter arsip stok
  $meta_arsip = $conn->query("INSERT INTO meta_arsip_stok (tgl_arsip, no_arsip, user) VALUE ('$tgl_arsip', '$no_arsip', '$username')");

  header('Location: stok_barang.php');
} else {
  die("Gagal menambah transaksi: " . $conn->error);
}
