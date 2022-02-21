<?php

require_once '../vendor/autoload.php';
require_once "../app/helpers.php";
require_once '../app/MyMPDF.php';
require_once "../app/koneksi.php";

$query = "SELECT * FROM cabang";

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
  <div class="report-header">Laporan Cabang Subur Group</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>
        <td width="23%" class="text-bold">Tanggal Cetak</td>
        <td width="2%" class="text-center">:</td>
        <td width="75%"><?= date('d/m/Y'); ?></td>
      </tr>
    </thead>
  </table>
  <br>
  <table width="100%" style="font-size: 9pt;" cellpadding="8">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>Nama Cabang</th>
        <th>Kontak</th>
        <th>Alamat</th>
      </tr>
    </thead>
    <tbody>
      <?php

      $no = 1;
      $dt = $conn->query($query);
      while ($data = $dt->fetch_assoc()) :

      ?>
        <tr>
          <td class="text-center"><?= $no; ?></td>
          <td><?= $data['nama_cabang'] ?></td>
          <td><?= $data['kontak'] ?></td>
          <td><?= $data['alamat'] ?></td>
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
