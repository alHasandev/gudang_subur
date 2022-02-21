<?php

require_once '../vendor/autoload.php';
require_once "../app/helpers.php";
require_once '../app/MyMPDF.php';
require_once "../app/koneksi.php";

$query = "SELECT penyaluran.*, barang.nama_barang, kategori.kode FROM penyaluran INNER JOIN barang ON penyaluran.id_barang = barang.id LEFT JOIN kategori ON barang.id_kategori = kategori.id";

$tahun = @$_GET['tahun'] or "";
$bulan = @$_GET['bulan'] or "";
$status = @$_GET['status'] or "";

if ($tahun && $bulan && $status) {
  $query .= " WHERE YEAR(tgl_penyaluran) = '$tahun' AND MONTH(tgl_penyaluran) = '$bulan' AND status = '$status'";
} else if ($tahun && $bulan) {
  $query .= " WHERE YEAR(tgl_penyaluran) = '$tahun' AND MONTH(tgl_penyaluran) = '$bulan'";
  $status = "Semua Status";
} else if ($tahun && $status) {
  $query .= " WHERE YEAR(tgl_penyaluran) = '$tahun' AND status = '$status'";
  $bulan = "Semua Bulan";
} else if ($bulan && $status) {
  $query .= " WHERE MONTH(tgl_penyaluran) = '$bulan' AND status = '$status'";
  $tahun = "Semua Tahun";
} else if ($tahun) {
  $query .= " WHERE YEAR(tgl_penyaluran) = '$tahun'";
  $bulan = "Semua Bulan";
  $status = "Semua Status";
} else if ($bulan) {
  $query .= " WHERE MONTH(tgl_penyaluran) = '$bulan'";
  $tahun = "Semua Tahun";
  $status = "Semua Status";
} else if ($status) {
  $query .= " WHERE status = '$status'";
  $tahun = "Semua Tahun";
  $bulan = "Semua Bulan";
} else {
  $tahun = "Semua Tahun";
  $bulan = "Semua Bulan";
  $status = "Semua Status";
}

$nama_bulan = @$_POST['bulan'] ? $list_bulan[$bulan - 1] : $bulan;

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
  <div class="report-header">Laporan Penyaluran Bantuan Sosial</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>
        <td width="10%" class="text-bold">Tahun</td>
        <td width="2%" class="text-center">:</td>
        <td width="21%"><?= $tahun ?></td>
        <td width="10%" class="text-bold">Bulan</td>
        <td width="2%" class="text-center">:</td>
        <td width="21%"><?= $nama_bulan ?></td>
        <td width="10%" class="text-bold">Status</td>
        <td width="2%" class="text-center">:</td>
        <td width="21%"><?= $status ?></td>
      </tr>
    </thead>
  </table>
  <br>
  <table width="100%" style="font-size: 9pt;" cellpadding="8">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>Tanggal</th>
        <th>Nama Barang</th>
        <th class="text-center">Kategori</th>
        <th class="text-center">Jumlah</th>
        <th>Tujuan</th>
        <th>Kontak</th>
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
          <td><?= $data['tgl_penyaluran'] ?></td>
          <td><?= $data['nama_barang'] ?></td>
          <td class="text-center"><?= $data['kode'] ?></td>
          <td class="text-center"><?= $data['jumlah'] ?></td>
          <td><?= $data['tujuan'] ?></td>
          <td><?= $data['kontak'] ?></td>
          <td><?= $data['keterangan'] ?></td>
        </tr>
        <?php $no++; ?>
        <?php $total += $data['jumlah'] ?>
      <?php endwhile; ?>
      <tr>
        <th class="text-center">#</th>
        <th class="text-center" colspan="3">Jumlah Total:</th>
        <th class="text-center"><?= $total ?></th>
        <th></th>
        <th></th>
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
