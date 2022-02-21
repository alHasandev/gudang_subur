<?php

require_once "layouts/header.php";
require_once "app/koneksi.php"

?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <!-- tombah tambah data -->
        <div class="row">
          <div class="col-md-6">
            <!-- Judul Halaman -->
            <h4>Daftar Barang yang Telah Di Arsipkan</h4>
          </div>
          <div class="col-md-6 text-right">
            <!-- <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#formModal">TAMBAH</a> -->
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="datatable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Tanggal Arsip</th>
              <th>Nama Barang</th>
              <th>Kondisi Barang</th>
              <th class="text-center">Jumlah</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $arsip = $conn->query("SELECT arsip_barang.*, barang.nama_barang FROM arsip_barang INNER JOIN barang ON arsip_barang.id_barang = barang.id");
            while ($data = $arsip->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= $data['tgl_arsip'] ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td><?= $data['kondisi'] ?></td>
                <td class="text-right"><?= $data['jumlah'] ?></td>
                <td><?= $data['keterangan'] ?></td>
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

<script>
  // datatable
  $(function() {
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