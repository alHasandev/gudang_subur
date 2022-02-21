<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$id_barang = @$_POST['barang'] or "";

?>
<div class="row">
  <div class="col-12">
    <form action="" method="POST" class="card">
      <div class="card-body row">
        <div class="col-md-6">
          <select name="barang" id="barang" class="form-control">
            <option value="">Filter Barang</option>
            <?php

            $data_bar = $conn->query("SELECT * FROM barang WHERE stok > 0");
            while ($bar = $data_bar->fetch_assoc()) :
            ?>
              <option value="<?= $bar['id'] ?>" <?= ($id_barang == $bar['id']) ? 'selected' : '' ?>>
                <?= $bar['nama_barang'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-6 text-right">
          <input type="submit" name="filter" value="FILTER" class="btn btn-primary">
        </div>
      </div>
    </form>
  </div>
  <!-- /.col-12 -->
</div>
<!-- /.row -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <!-- tombah tambah data -->
        <div class="row">
          <div class="col-md-6">
            <!-- Judul Halaman -->
            <h4>Laporan Kondisi Barang</h4>
          </div>
          <div class="col-md-6 text-right">
            <?php if ($id_barang) : ?>
              <a href="" class="btn btn-warning">REFRESH</a>
            <?php endif; ?>
            <a href="laporan/cetak_kondisi_barang.php?barang=<?= $id_barang ?>" class="btn btn-primary" target="_blank">PRINT</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <?php if (@$id_barang) : ?>
          <table id="datatable-2" class="table table-bordered table-striped">
            <?php
            $query = "SELECT barang.*, kategori.nama_kategori FROM barang LEFT JOIN kategori ON barang.id_kategori = kategori.id WHERE barang.id = '$id_barang'";
            // echo $query;
            $barang = $conn->query($query)->fetch_assoc();

            ?>
            <tbody>
              <tr>
                <th style="width: 250px;">Nama Barang</th>
                <td><?= $barang['nama_barang'] ?></td>
              </tr>
              <tr>
                <th style="width: 250px;">Kategori</th>
                <td><?= $barang['nama_kategori'] ?></td>
              </tr>
              <tr>
                <th style="width: 250px;">Stok</th>
                <td><?= $barang['stok'] ?></td>
              </tr>
            </tbody>
          </table>
        <?php endif; ?>
        <table id="datatable" class="table table-bordered table-striped">
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
            $query = "SELECT kondisi_barang.*, kondisi.nama as nama_kondisi FROM kondisi_barang RIGHT JOIN kondisi ON kondisi_barang.id_kondisi = kondisi.id WHERE id_barang = '$id_barang' AND jumlah > 0";
            $stok_barang = $conn->query($query);

            while ($data = $stok_barang->fetch_assoc()) :

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
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->


<!-- coding untuk form edit -->
<script>
  $(function() {
    // datatable
    $("#datatable").DataTable({
      "responsive": true,
      "lengthChange": false,
      "pageLength": 5,
      // "scrollY": 500,
      // "scrollX": true,
      "scrollCollapse": true,
      "autoWidth": false,
      "ordering": false,
      "info": false,
      "bFilter": false,
      "language": {
        "emptyTable": "Silahkan Pilih Filter Barang untuk menampilkan data"
      }
    });
  });
</script>

<?php require_once "layouts/footer.php" ?>