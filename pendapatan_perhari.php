<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <!-- tombah tambah data -->
        <div class="row">
          <div class="col-md-6">
            <!-- Judul Halaman -->
            <h4>Pendapatan Cabang Perhari</h4>
          </div>
          <div class="col-md-6 text-right">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#formModal">TAMBAH</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="datatable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Hari</th>
              <th>Tanggal</th>
              <th>Nama Cabang</th>
              <th>Pendapatan</th>
              <th>Keterangan</th>
              <th>Operasi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $barang_masuk = $conn->query("SELECT pendapatan_perhari.*, cabang.nama_cabang FROM pendapatan_perhari INNER JOIN cabang ON pendapatan_perhari.id_cabang = cabang.id");
            echo $conn->error;
            while ($data = $barang_masuk->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= dayName(tanggal($data['tanggal'], 'w')) ?></td>
                <td><?= $data['tanggal'] ?></td>
                <td><?= $data['nama_cabang'] ?></td>
                <td class="text-right"><?= rupiah($data['jumlah']) ?></td>
                <td><?= $data['keterangan'] ?></td>
                <td class="text-center">
                  <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#formModal" onclick='editForm(`<?= json_encode($data) ?>`)'>
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" onclick='deleteModal(`hapus_pendapatan_perhari.php?id=<?= $data["id"] ?>`, `Pendapatan Perhari <?= $data["nama_cabang"] ?>, tanggal: <?= $data["tanggal"] ?> ?`)'>
                    <i class="fas fa-trash"></i>
                  </a>
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

<!-- Modal -->

<!-- Form Modal untuk tambah dan Edit Data -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <!-- atur form disini -->
    <form action="tambah_pendapatan_perhari.php" method="POST" id="form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Tambah Barang Masuk</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- edit untuk mengubah isi form -->
        <input type="hidden" name="id" id="id" value="">
        <div class="form-group">
          <label for="tanggal">Tanggal Pendapatan</label>
          <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>

        <div class="form-group">
          <label for="id_cabang">Data Cabang</label>
          <select class="form-control select2" name="id_cabang" id="id_cabang" style="width: 100%;">
            <option value="">Pilih cabang</option>
            <?php

            $cabang = $conn->query("SELECT * FROM cabang");
            while ($data = $cabang->fetch_assoc()) :

            ?>
              <option value="<?= $data['id'] ?>"><?= $data['nama_cabang'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="jumlah">Jumlah Pendapatan</label>
          <input type="number" name="jumlah" id="jumlah" class="form-control">
        </div>
        <div class="form-group">
          <label for="keterangan">Keterangan</label>
          <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <!-- ubah tombol form -->
        <button class="btn btn-secondary" type="reset" data-dismiss="modal" onclick="resetForm()">Cancel</button>
        <input type="submit" class="btn btn-primary" value="Tambah">
      </div>
    </form>
  </div>
</div>


<!-- coding untuk form edit -->
<script>
  // fungsi untuk edit siswa
  function editForm(data) {
    // parse json data menjadi objek
    data = JSON.parse(data);
    // ikuti pola sesuaikan dengan id pada form modal data

    // ubah action dari form menjadi edit
    // let editAction = window.location.href + '&aksi=edit';
    let editAction = 'update_pendapatan_perhari.php';
    // console.log(window.location);
    $('#form').attr('action', editAction);

    // ubah judul form
    $('#formModalLabel').html('Edit Barang Masuk');

    // ubah tombol tambah menjadi edit
    $('#form input[type=submit]').val('Edit');

    // ubah dan tambahkan sesuai form kalian
    $('#id').val(data.id);
    $('#tanggal').val(data.tanggal);
    $('#id_cabang').val(data.id_cabang);
    $('#jumlah').val(data.jumlah);
    $('#keterangan').val(data.keterangan);

  }

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