<?php

require_once '../vendor/autoload.php';
require_once '../app/MyMPDF.php';
require_once '../app/helpers.php';
require_once "../app/koneksi.php";


$id_barang = @$_GET['barang'] or "";

$query = "SELECT kondisi_barang.*, kondisi.nama as nama_kondisi FROM kondisi_barang RIGHT JOIN kondisi ON kondisi_barang.id_kondisi = kondisi.id WHERE id_barang = '$id_barang' AND jumlah > 0";

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
  <div class="report-header">Laporan Kondisi Barang</div>
  <br />
  <?php if (@$id_barang) : ?>
    <table width="100%" style="border-collapse: collapse;" cellpadding="8">
      <?php

      $barang = $conn->query("SELECT barang.*, kategori.nama_kategori FROM barang LEFT JOIN kategori ON barang.id_kategori = kategori.id WHERE barang.id = '$id_barang'")->fetch_assoc();

      ?>
      <tbody>
        <tr>
          <td width="25%" class="text-bold bg-gray">Nama Barang</td>
          <td><?= $barang['nama_barang'] ?></td>
        </tr>
        <tr>
          <td width="25%" class="text-bold bg-gray">Kategori</td>
          <td><?= $barang['nama_kategori'] ?></td>
        </tr>
        <tr>
          <td width="25%" class="text-bold bg-gray">Stok</td>
          <td><?= $barang['stok'] ?></td>
        </tr>
        <tr>
          <td width="25%" class="text-bold bg-gray">Tanggal Cetak</td>
          <td><?= date('d-m-Y') ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>
  <br>
  <table width="100%" style="font-size: 9pt;" cellpadding="8">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>Kondisi</th>
        <th class="text-center">Jumlah</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php

      $no = 1;
      $barang_masuk = $conn->query($query);
      while ($data = $barang_masuk->fetch_assoc()) :
      ?>
        <tr>
          <td class="text-center"><?= $no; ?></td>
          <td><?= $data['nama_kondisi'] ?></td>
          <td class="text-right"><?= $data['jumlah'] ?></td>
          <td>
          </td>
        </tr>
        <?php $no++; ?>
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
