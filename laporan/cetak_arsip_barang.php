<?php

require_once '../vendor/autoload.php';
require_once '../app/MyMPDF.php';
require_once '../app/helpers.php';
require_once "../app/koneksi.php";


$query = "SELECT arsip_barang.*, barang.nama_barang FROM arsip_barang INNER JOIN barang ON arsip_barang.id_barang = barang.id";

if (@$_GET['kategori'] && @$_GET['tanggal_awal']) {
  $query .= " WHERE barang.id_kategori = '$_GET[kategori]' AND tgl_arsip BETWEEN '$_GET[tanggal_awal]' AND '$_GET[tanggal_akhir]'";

  $data_kat = $conn->query("SELECT * FROM kategori WHERE id = '$_GET[kategori]'")->fetch_assoc();
  $nama_kategori = $data_kat['nama_kategori'];
} else 
if (@$_GET['kategori']) {
  $query .= " WHERE barang.id_kategori = '$_GET[kategori]'";
} else 
if ($tanggal_awal) {
  $query .= " WHERE tgl_arsip BETWEEN '$_GET[tanggal_awal]' AND '$_GET[tanggal_akhir]'";

  $data_kat = $conn->query("SELECT * FROM kategori WHERE id = '$_GET[kategori]'")->fetch_assoc();
  $nama_kategori = $data_kat['nama_kategori'];
} else {
  $nama_kategori = "Semua Kategori";
}

// capturing html template
ob_start();

?>

<html>

<head>
  <?php require_once 'styles.php' ?>
</head>

<body>
  <!--mpdf
  <htmlpagefooter name="myfooter">
  <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
  Halaman {PAGENO} dari {nb}
  </div>
  <div class="text-right">
    <small class="text-italic"><?= __DIR__ ?></small>
  </div>
  </htmlpagefooter>
  
  <sethtmlpagefooter name="myfooter" value="on" />
  mpdf-->

  <div>
    <img src="../assets/img/banners/cop-surat.jpg" alt="">
  </div>
  <div class="report-header">Laporan Arsip Barang (Rusak, Exp, Dll.)</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>
        <td width="18%" class="text-bold">Kategori</td>
        <td width="2%" class="text-center">:</td>
        <td width="30%"><?= $nama_kategori ?></td>
        <td width="18%" class="text-bold">Range Tanggal</td>
        <td width="2%" class="text-center">:</td>
        <td width="30%"><?= $_GET['tanggal_awal'] ?> - <?= $_GET['tanggal_akhir'] ?></td>
      </tr>
    </thead>
  </table>
  <br>
  <table width="100%" style="font-size: 9pt;" cellpadding="8">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>Tanggal Arsip</th>
        <th>Nama Barang</th>
        <th>Kondisi Barang</th>
        <th class="text-center">Jumlah</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php
      echo $query;
      $no = 1;
      $dt = $conn->query($query);
      $total = 0;
      while ($data = $dt->fetch_assoc()) :

      ?>
        <tr>
          <td class="text-center"><?= $no; ?></td>
          <td><?= $data['tgl_arsip'] ?></td>
          <td><?= $data['nama_barang'] ?></td>
          <td><?= $data['kondisi'] ?></td>
          <td class="text-right"><?= $data['jumlah'] ?></td>
          <td><?= $data['keterangan'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <br>
  <br>
  <div class="text-right">
    <p class="">Mengetahui, Banjarmasin <?= date('d') ?> <?= $list_bulan[date('m') - 1] ?> <?= date('Y') ?></p>
    <br>
    <br>
    <br>
    <br>
    <h4><?= NAMA_TTD ?>.</h4>
  </div>

</body>

</html>
<?php


$html = ob_get_contents();

ob_end_clean();

// echo $file_template;

$mpdf = new MyMPDF([
  'margin_left' => 5,
  'margin_right' => 5,
  'margin_top' => 5,
  'margin_bottom' => 25,
  'margin_header' => 0,
  'margin_footer' => 5
]);

$mpdf->template($html);

$mpdf->renderPDF();
