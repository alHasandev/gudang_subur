<?php

require_once 'app/helpers.php';
require_once 'app/koneksi.php';

// coding tambah ke tabel pendapatan perhari
$query = "INSERT INTO pendapatan_perhari (tanggal, id_cabang, jumlah, keterangan) VALUE ('$_POST[tanggal]', '$_POST[id_cabang]', '$_POST[jumlah]', '$_POST[keterangan]')";

$hasil = $conn->query($query);

if ($hasil) {
  // tambahkan data pendapatan perbulan (rerate perminggu)
  $no_minggu = tanggal($_POST['tanggal'], 'W');
  $tahun = tanggal($_POST['tanggal'], 'Y');
  $pendapatan_perbulan = $conn->query("SELECT * FROM pendapatan_perbulan WHERE tahun = '$tahun', no_minggu = $no_minggu AND id_cabang = '$_POST[id_cabang]'");

  // Jika sudah ada pendapatan perbulan yg sama id_cabang & no_minggu nya, maka
  if ($pendapatan_perbulan->num_rows) {
    // Update pendapatan perbulan
    $query = "UPDATE pendapatan_perbulan SET tgl_update = '$_POST[tanggal]', rerata = (rerata + '$_POST[jumlah]') / 2, jumlah_hari = jumlah_hari + 1 WHERE no_minggu = $no_minggu AND id_cabang = '$_POST[id_cabang]'";
  } else {
    // Jika tidak ada, maka
    // Tambahkan pendapatan perbulan yang baru
    $query = "INSERT INTO pendapatan_perbulan (id_cabang, tahun, no_minggu, tgl_update, rerata, jumlah_hari, keterangan) VALUE ('$_POST[id_cabang]', '$tahun', '$no_minggu', '$_POST[tanggal]', '$_POST[jumlah]', 1, 'Input pertama')";
  }

  $conn->query($query);

  header('Location: pendapatan_perhari.php');
} else {
  die("Gagal menambah transaksi: " . $conn->error);
}
