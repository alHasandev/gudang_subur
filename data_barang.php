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
            <h4>Data Barang</h4>
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
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th>Harga</th>
              <th class="text-center">Stok</th>
              <th>Keterangan</th>
              <th class="text-center">Operasi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $barang = $conn->query("SELECT barang.*, kategori.nama_kategori, kategori.satuan FROM barang INNER JOIN kategori ON barang.id_kategori = kategori.id");
            while ($data = $barang->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td><?= $data['nama_kategori'] ?></td>
                <td><?= rupiah($data['harga']) ?></td>
                <td class="text-center"><?= $data['stok'] ?> <?= $data['satuan'] ?></td>
                <td><?= $data['keterangan'] ?></td>
                <td class="text-center">
                  <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#formModal" onclick='editForm(`<?= json_encode($data) ?>`)'>
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" onclick='deleteModal(`hapus_barang.php?id=<?= $data["id"] ?>`, `Barang: <?= $data["nama_barang"] ?>`)'>
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
    <form action="tambah_barang.php" method="POST" id="form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Tambah Data Barang</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- edit untuk mengubah isi form -->
        <input type="hidden" name="id" id="id" value="">
        <div class="form-group">
          <label for="id_kategori">Kategori Barang</label>
          <select class="form-control select2" name="id_kategori" id="id_kategori" style="width: 100%;">
            <option value="">Pilih Kategori</option>
            <?php

            $kategori = $conn->query("SELECT * FROM kategori");
            while ($data = $kategori->fetch_assoc()) :

            ?>
              <option value="<?= $data['id'] ?>"><?= $data['nama_kategori'] ?> (<?= $data['satuan'] ?>)</option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="nama_barang">Nama Barang</label>
          <input type="text" name="nama_barang" id="nama_barang" class="form-control">
        </div>
        <div class="form-group">
          <label for="harga">Harga Barang</label>
          <input type="number" name="harga" id="harga" class="form-control">
        </div>
        <div class="form-group">
          <label for="stok">Stok</label>
          <input type="number" name="stok" id="stok" class="form-control" value="0" readonly>
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
    let editAction = 'update_barang.php';
    // console.log(window.location);
    $('#form').attr('action', editAction);

    // ubah judul form
    $('#formModalLabel').html('Edit Data Barang');

    // ubah tombol tambah menjadi edit
    $('#form input[type=submit]').val('Edit');

    // ubah dan tambahkan sesuai form kalian
    $('#id').val(data.id);
    $('#id_kategori').val(data.id_kategori);
    $('#nama_barang').val(data.nama_barang);
    $('#harga').val(data.harga);
    $('#stok').val(data.stok);
    $('#keterangan').val(data.keterangan);

  }

  // reset form
  // function resetForm() {
  //   $('#stok').attr('disabled', true);
  // }

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