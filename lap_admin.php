<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$query = "SELECT * FROM admin";

$hak_akses = @$_POST['hak_akses'] or "";

if ($hak_akses) {
  $query .= " WHERE hak_akses = '$hak_akses'";
}

?>
<div class="row">
  <div class="col-12">
    <form action="" method="POST" class="card">
      <div class="card-body row">
        <div class="col-md-6">
          <select name="hak_akses" id="hak_akses" class="form-control">
            <option value="">Hak Akses</option>
            <option value="MASTER" <?= ($hak_akses == "MASTER") ? 'selected' : '' ?>>MASTER</option>
            <option value="ADMIN_GUDANG" <?= ($hak_akses == "ADMIN_GUDANG") ? 'selected' : '' ?>>ADMIN GUDANG</option>
            <option value="ADMIN_STOK" <?= ($hak_akses == "ADMIN_STOK") ? 'selected' : '' ?>>ADMIN STOK</option>
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
            <h4>Laporan Data Admin</h4>
          </div>
          <div class="col-md-6 text-right">
            <?php if ($hak_akses) : ?>
              <a href="" class="btn btn-warning">REFRESH</a>
            <?php endif; ?>
            <a href="laporan/cetak_admin.php?hak_akses=<?= $hak_akses ?>" class="btn btn-primary" target="_blank">PRINT</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="datatable" class="table table-bordered table-striped">
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
                  <img src="<?= $data['foto'] ?>" alt="" class="img-thumbnail" style="width: 5rem;">
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