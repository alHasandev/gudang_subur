<?php

require_once '../vendor/autoload.php';
require_once '../app/MyMPDF.php';
require_once '../app/helpers.php';
require_once "../app/koneksi.php";


$query = "SELECT reture_barang.*, barang.nama_barang, kondisi.nama as nama_kondisi, supplier.nama as nama_supplier FROM reture_barang INNER JOIN barang ON reture_barang.id_barang = barang.id LEFT JOIN kondisi ON reture_barang.id_kondisi = kondisi.id INNER JOIN supplier ON reture_barang.id_supplier = supplier.id";

if (@$_GET['kategori'] && @$_GET['supplier'] && @$_GET['tanggal_awal']) {
  $query .= " WHERE barang.id_kategori = '$_GET[kategori]' AND reture_barang.id_supplier = '$_GET[supplier]' AND tgl_reture BETWEEN '$_GET[tanggal_awal]' AND '$_GET[tanggal_akhir]'";

  $data_kat = $conn->query("SELECT * FROM kategori WHERE id = '$_GET[kategori]'")->fetch_assoc();
  $nama_kategori = $data_kat['nama_kategori'];

  $data_cab = $conn->query("SELECT * FROM supplier WHERE id = '$_GET[supplier]'")->fetch_assoc();
  $nama_supplier = $data_cab['nama'];
} else 
if (@$_GET['kategori']) {
  $query .= " WHERE barang.id_kategori = '$_GET[kategori]'";

  $data_cab = $conn->query("SELECT * FROM supplier WHERE id = '$_GET[supplier]'")->fetch_assoc();
  $nama_supplier = $data_cab['nama_supplier'];
} else 
if (@$_GET['supplier']) {
  $query .= " WHERE reture_barang.id_supplier = '$_GET[supplier]'";

  $data_kat = $conn->query("SELECT * FROM kategori WHERE id = '$_GET[kategori]'")->fetch_assoc();
  $nama_kategori = $data_kat['nama_kategori'];
} else
if ($tanggal_awal) {
  $query .= " WHERE tgl_reture BETWEEN '$_GET[tanggal_awal]' AND '$_GET[tanggal_akhir]'";

  $data_cab = $conn->query("SELECT * FROM supplier WHERE id = '$_GET[supplier]'")->fetch_assoc();
  $nama_supplier = $data_cab['nama'];

  $data_kat = $conn->query("SELECT * FROM kategori WHERE id = '$_GET[kategori]'")->fetch_assoc();
  $nama_kategori = $data_kat['nama_kategori'];
} else {
  $nama_kategori = "Semua Kategori";
  $nama_supplier = "Semua Supplier";
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
  <div class="report-header">Laporan Reture Barang</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>
        <td width="10%" class="text-bold">Kategori</td>
        <td width="2%" class="text-center">:</td>
        <td width="18%"><?= $nama_kategori ?></td>
        <td width="10%" class="text-bold">Supplier</td>
        <td width="2%" class="text-center">:</td>
        <td width="18%"><?= $nama_supplier ?></td>
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
        <th class="text-center">Tanggal</th>
        <th>Nama Barang</th>
        <th>Kondisi Barang</th>
        <th class="text-center">Jumlah</th>
        <th>Supplier</th>
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
          <td class="text-center"><?= $data['tgl_reture'] ?></td>
          <td><?= $data['nama_barang'] ?></td>
          <td><?= $data['nama_kondisi'] ?></td>
          <td class="text-right"><?= $data['jumlah'] ?></td>
          <td><?= $data['nama_supplier'] ?></td>
        </tr>
        <?php $no++; ?>
        <?php $total += $data['jumlah'] ?>
      <?php endwhile; ?>
      <tr>
        <th class="text-center">#</th>
        <th class="text-center" colspan="3">Jumlah Total:</th>
        <th class="text-center"><?= $total ?></th>
        <th></th>
      </tr>
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
