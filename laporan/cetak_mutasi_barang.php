<?php

require_once '../vendor/autoload.php';
require_once '../app/MyMPDF.php';
require_once '../app/helpers.php';
require_once "../app/koneksi.php";


$query = "SELECT mutasi_barang.*, barang.nama_barang, cabang.nama_cabang, kategori.nama_kategori FROM mutasi_barang INNER JOIN barang ON mutasi_barang.id_barang = barang.id LEFT JOIN cabang ON mutasi_barang.id_cabang = cabang.id LEFT JOIN kategori ON barang.id_kategori = kategori.id WHERE mutasi_barang.id > 0";

$filter_cabang = @$_GET['cabang'];
$filter_kategori = @$_GET['kategori'];
$tanggal_awal = @$_GET['tanggal_awal'];
$tanggal_akhir = @$_GET['tanggal_akhir'];
$nama_cabang = "Semua Cabang";
$nama_kategori = "Semua Kategori";

if($filter_cabang) {
  $query .= " AND id_cabang = '$filter_cabang'";
  $cabang = $conn->query("SELECT * FROM cabang WHERE id = '$filter_cabang'")->fetch_assoc();
  $nama_cabang = $cabang['nama_cabang'];
}

if($filter_kategori) {
  $query .= " AND id_kategori = '$filter_kategori'";
  $kategori = $conn->query("SELECT * FROM kategori WHERE id = '$filter_kategori'")->fetch_assoc();
  $nama_kategori = $kategori['nama_kategori'];
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
  <div class="report-header">Laporan Mutasi Barang</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>
        <td width="10%" class="text-bold">Kategori</td>
        <td width="2%" class="text-center">:</td>
        <td width="18%"><?= $nama_kategori ?></td>
        <td width="10%" class="text-bold">Cabang</td>
        <td width="2%" class="text-center">:</td>
        <td width="18%"><?= $nama_cabang ?></td>
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
        <th>Tanggal</th>
        <th>Nama Barang</th>
        <th>Kategori</th>
        <th class="text-center">Jumlah</th>
        <th>Cabang</th>
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
          <td><?= $data['tgl_mutasi'] ?></td>
          <td><?= $data['nama_barang'] ?></td>
          <td><?= $data['nama_kategori'] ?></td>
          <td class="text-right"><?= $data['jumlah'] ?></td>
          <td><?= $data['nama_cabang'] ?></td>
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
