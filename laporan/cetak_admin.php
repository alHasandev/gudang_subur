<?php

require_once '../vendor/autoload.php';
require_once "../app/helpers.php";
require_once '../app/MyMPDF.php';
require_once "../app/koneksi.php";

$query = "SELECT * FROM admin";

$hak_akses = @$_POST['hak_akses'] or "";

if ($hak_akses) {
  $query .= " WHERE hak_akses = '$hak_akses'";
} else {
  $hak_akses = "SEMUA";
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
  <div class="report-header">Laporan Daftar Admin</div>
  <br />
  <table width="100%" style="border-collapse: collapse;" cellpadding="8">
    <thead>
      <tr>
        <td width="23%" class="text-bold">Hak Akses</td>
        <td width="2%" class="text-center">:</td>
        <td width="75%"><?= $hak_akses; ?></td>
      </tr>
    </thead>
  </table>
  <br>
  <table width="100%" style="font-size: 9pt;" cellpadding="8">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th class="text-center">Foto</th>
        <th>Username</th>
        <th>Nama</th>
        <th>Kontak</th>
        <th>Alamat</th>
      </tr>
    </thead>
    <tbody>
      <?php

      $no = 1;
      $admin = $conn->query($query);
      while ($data = $admin->fetch_assoc()) :

      ?>
        <tr>
          <td class="text-center"><?= $no; ?></td>
          <td class="text-center">
            <img src="../<?= $data['foto'] ?>" alt="" class="img-thumbnail" style="width: 5rem;">
          </td>
          <td><?= $data['username'] ?></td>
          <td><?= $data['nama'] ?></td>
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
