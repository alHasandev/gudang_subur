<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <!-- tombah tambah data -->
        <!-- <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#formModal">Tambah Data Barang</a> -->
        <div class="row">
          <div class="col-md-6">
            <h4>Cek Stok Barang</h4>
          </div>
          <div class="col-md-6 text-right">
            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#deleteModal" onclick='deleteModal(`hapus_stok_fisik.php`)'>RESET</a>
            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#formArsip">ARSIP</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="d-flex flex-wrap">
          <small class="alert alert-light mr-1"><span class="bg-danger" style="display: inline-block; width: 12px;height: 12px"></span> Terlalu Sedikit (kurang dari 25) atau terlalu banyak (lebih dari 250)</small>
          <small class="alert alert-light"><span class="bg-success" style="display: inline-block; width: 12px;height: 12px"></span> Stok ideal (25-250)</small>
        </div>
        <table id="datatable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama Barang</th>
              <th class="text-center">Stok Gudang</th>
              <th class="text-center">Stok Akumulasi</th>
              <th class="text-center">Stok Bergerak</th>
              <th>Keterangan</th>
              <th class="text-center">Operasi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $stok = $conn->query("SELECT stok_barang.id, stok_barang.stok_fisik, stok_barang.keterangan, barang.id as id_barang, barang.nama_barang, barang.stok, kategori.nama_kategori, kategori.satuan FROM stok_barang RIGHT JOIN barang ON stok_barang.id_barang = barang.id LEFT JOIN kategori ON barang.id_kategori = kategori.id");
            while ($data = $stok->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td class="text-center <?= ($data['stok'] < 25 || $data['stok'] > 250) ? 'text-danger' : 'text-success' ?>">
                  <?= $data['stok'] ?> <?= $data['satuan'] ?>
                </td>
                <td class="text-center"><?= $data['stok_fisik'] ? $data['stok_fisik'] : 0 ?> <?= $data['satuan'] ?></td>
                <td class="text-center"><?= $data['stok_fisik'] - $data['stok'] ?> <?= $data['satuan'] ?></td>
                <td><?= $data['keterangan'] ?></td>
                <td class="text-center">
                  <a href="#" class="btn bg-primary btn-xs" data-toggle="modal" data-target="#formModal" onclick='editForm(`<?= json_encode($data) ?>`)'>
                    <i class="fas fa-check"></i>
                  </a>
                  <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" onclick='deleteModal(`hapus_stok_barang.php?id=<?= $data["id"] ?>`, `Stok Barang: <?= $data["nama_barang"] ?>`)'>
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
    <form action="" method="POST" id="form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Cek Stok Barang</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- edit untuk mengubah isi form -->
        <input type="hidden" name="id" id="id" value="">
        <input type="hidden" name="id_barang" id="id_barang" value="">
        <div class="form-group">
          <label for="nama_barang">Nama Barang</label>
          <input type="text" name="nama_barang" id="nama_barang" class="form-control" readonly>
        </div>

        <div class="form-group">
          <label for="harga">Stok Barang</label>
          <input type="number" name="stok" id="stok" class="form-control" value="0" readonly>
        </div>
        <div class="form-group">
          <label for="stok_fisik">Stok Fisik</label>
          <input type="number" name="stok_fisik" id="stok_fisik" class="form-control" value="0">
        </div>
        <div class="form-group">
          <label for="keterangan">Keterangan</label>
          <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <!-- ubah tombol form -->
        <button class="btn btn-secondary" type="reset" data-dismiss="modal" onclick="resetForm()">Cancel</button>
        <input type="submit" class="btn btn-info" value="Verifikasi">
      </div>
    </form>
  </div>
</div>
<!-- /.modal edit -->

<!-- Form Modal untuk konfirmasi arsip -->
<div class="modal fade" id="formArsip" tabindex="-1" role="dialog" aria-labelledby="formArsipLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <!-- atur form disini -->
    <form action="tambah_arsip_stok.php" method="POST" id="form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formArsipLabel">Arsip Stok Barang</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Apakah anda yakin akan mengarsip Hasil Pengecekan Stok Barang, pada tanggal: <strong><?= dayName(date('w')) ?>, <?= date('d/m/Y') ?></strong>, oleh User: <strong><?= $user['username'] ?></strong> ?</p>
      </div>
      <div class="modal-footer">
        <!-- ubah tombol form -->
        <button class="btn btn-secondary" type="reset" data-dismiss="modal">Cancel</button>
        <input type="submit" class="btn btn-info" value="Arsip Stok">
      </div>
    </form>
  </div>
</div>
<!-- /.modal edit -->

<script>
  // fungsi untuk edit siswa
  function editForm(data) {
    // parse json data menjadi objek
    data = JSON.parse(data);
    console.log(data);
    // ikuti pola sesuaikan dengan id pada form modal data

    // ubah action dari form menjadi edit
    // let editAction = window.location.href + '&aksi=edit';
    let editAction = 'update_stok_barang.php';
    // console.log(window.location);
    $('#form').attr('action', editAction);

    // ubah judul form
    $('#formModalLabel').html('Cek Stok Barang');

    // ubah tombol tambah menjadi edit
    $('#form input[type=submit]').val('Konfirmasi');

    // ubah dan tambahkan sesuai form kalian
    $('#id').val(data.id);
    $('#id_barang').val(data.id_barang);
    $('#nama_barang').val(data.nama_barang);
    $('#stok').val(data.stok);
    $('#stok_fisik').val(data.stok_fisik);
    $('#keterangan').val(data.keterangan);
  }

  // fungsi untuk menampilkan nilai stok barang pilihan
  function stokBarang(option) {
    console.log(option);
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
      "bFilter": false,
      "ordering": false,
      "info": false
    });
  });
</script>

<?php require_once "layouts/footer.php" ?>