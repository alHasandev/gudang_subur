<?php

require_once '../vendor/autoload.php';
require_once '../app/MyMPDF.php';
require_once '../app/helpers.php';
require_once "../app/koneksi.php";


$query = "SELECT pendapatan_perhari.*, cabang.nama_cabang FROM pendapatan_perhari INNER JOIN cabang ON pendapatan_perhari.id_cabang = cabang.id WHERE pendapatan_perhari.id > 0";

$filter_cabang = @$_GET['cabang'];
$tanggal_awal = @$_GET['tanggal_awal'];
$tanggal_akhir = @$_GET['tanggal_akhir'];
$nama_cabang = "Semua Cabang";

if($filter_cabang) {
  $query .= " AND id_cabang = '$filter_cabang'";
  $cabang = $conn->query("SELECT * FROM cabang WHERE id = '$filter_cabang'")->fetch_assoc();
  $nama_cabang = $cabang['nama_cabang'];
}

if($tanggal_awal) {
  $query .= " AND tanggal_awal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
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
  <div class="report-header">Laporan Pendapatan Cabang</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>>
        <td width="18%" class="text-bold">Cabang</td>
        <td width="2%" class="text-center">:</td>
        <td width="30%"><?= $nama_cabang ?></td>
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
        <th>Hari</th>
        <th>Tanggal</th>
        <th>Nama Cabang</th>
        <th>Pendapatan</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php

      $no = 1;
      $dt = $conn->query($query);
      $total = 0;
      while ($data = $dt->fetch_assoc()) :
      ?>
        <tr>
          <td class="text-center"><?= $no; ?></td>
          <td><?= dayName(tanggal($data['tanggal'], 'w')) ?></td>
          <td><?= $data['tanggal'] ?></td>
          <td><?= $data['nama_cabang'] ?></td>
          <td class="text-right"><?= rupiah($data['jumlah']) ?></td>
          <td><?= $data['keterangan'] ?></td>
        </tr>
        <?php $no++; ?>
        <?php $total += $data['jumlah'] ?>
      <?php endwhile; ?>
      <tr>
        <th class="text-center">#</th>
        <th class="text-right" colspan="3">Pendapatan Total:</th>
        <th class="text-right"><?= rupiah($total) ?></th>
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
