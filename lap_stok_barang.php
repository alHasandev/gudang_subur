<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$query = "SELECT stok_barang.id, stok_barang.stok_fisik, stok_barang.keterangan, barang.id as id_barang, barang.nama_barang, barang.stok, kategori.nama_kategori FROM stok_barang RIGHT JOIN barang ON stok_barang.id_barang = barang.id LEFT JOIN kategori ON barang.id_kategori = kategori.id";

$kategori = @$_POST['kategori'] or "";

if ($kategori) {
  $query .= " WHERE id_kategori = '$kategori'";
}

?>
<div class="row">
  <div class="col-12">
    <form action="" method="POST" class="card">
      <div class="card-body row">
        <div class="col-md-6">
          <select name="kategori" id="kategori" class="form-control">
            <option value="">Filter Kategori</option>
            <?php

            $data_kat = $conn->query("SELECT * FROM kategori");
            while ($kat = $data_kat->fetch_assoc()) :
            ?>
              <option value="<?= $kat['id'] ?>" <?= ($kategori == $kat['id']) ? 'selected' : '' ?>>
                <?= $kat['nama_kategori'] ?>
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
            <h4>Laporan Stok Barang</h4>
          </div>
          <div class="col-md-6 text-right">
            <?php if ($kategori) : ?>
              <a href="" class="btn btn-warning">REFRESH</a>
            <?php endif; ?>
            <a href="laporan/cetak_stok_barang.php?kategori=<?= $kategori ?>" class="btn btn-primary" target="_blank">PRINT</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="datatable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama Barang</th>
              <th class="text-center">Kategori</th>
              <th class="text-center">Stok</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $stok_barang = $conn->query($query);
            $total = 0;
            $total_fisik = 0;
            while ($data = $stok_barang->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td class="text-center"><?= $data['nama_kategori'] ?></td>
                <td class="text-center"><?= $data['stok'] ?></td>
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
              <th></th>
            </tr>
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
      "info": false
    });
  });
</script>

<?php require_once "layouts/footer.php" ?>