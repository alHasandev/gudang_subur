<?php

require_once '../vendor/autoload.php';
require_once '../app/MyMPDF.php';
require_once '../app/helpers.php';
require_once "../app/koneksi.php";


$query = "SELECT stok_barang.id, stok_barang.stok_fisik, stok_barang.keterangan, barang.id as id_barang, barang.nama_barang, barang.stok, kategori.nama_kategori, kategori.satuan FROM stok_barang RIGHT JOIN barang ON stok_barang.id_barang = barang.id LEFT JOIN kategori ON barang.id_kategori = kategori.id";

if (@$_GET['kategori'] && $_GET['kategori'] !== '') {
  $query .= " WHERE id_kategori = '$_GET[kategori]'";
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
  <div class="report-header">Laporan Pengecekan Stok Barang</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>
        <td width="10%" class="text-bold">Kategori</td>
        <td width="2%" class="text-center">:</td>
        <td width="33%"><?= $nama_kategori ?></td>
        <td width="20%" class="text-bold">Tanggal Cetak</td>
        <td width="2%" class="text-center">:</td>
        <td width="33%"><?= date('d-m-Y') ?></td>
      </tr>
    </thead>
  </table>
  <br>
  <table width="100%" style="font-size: 9pt;" cellpadding="8">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>Nama Barang</th>
        <th class="text-center">Kategori</th>
        <th class="text-center">Stok Gudang</th>
        <th class="text-center">Stok Akumulasi</th>
        <th class="text-center">Stok Bergerak</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php

      $no = 1;
      $barang_masuk = $conn->query($query);
      $total = 0;
      $total_fisik = 0;
      while ($data = $barang_masuk->fetch_assoc()) :
      ?>
        <tr>
          <td class="text-center"><?= $no; ?></td>
          <td><?= $data['nama_barang'] ?></td>
          <td class="text-center"><?= $data['nama_kategori'] ?></td>
          <td class="text-center"><?= $data['stok'] ?></td>
          <td class="text-center"><?= $data['stok_fisik'] ?></td>
          <td class="text-center"><?= $data['stok_fisik'] - $data['stok'] ?> <?= $data['satuan'] ?></td>
          <td><?= $data['keterangan'] ?></td>
        </tr>
        <?php $no++; ?>
        <?php $total += $data['stok'] ?>
        <?php $total_fisik += $data['stok_fisik'] ?>
      <?php endwhile; ?>
      <tr>
        <th class="text-center">#</th>
        <th class="text-center" colspan="2">Stok Total:</th>
        <th class="text-center"><?= $total ?></th>
        <th class="text-center"><?= $total_fisik ?></th>
        <th class="text-center"><?= $total_fisik - $total ?></th>
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
