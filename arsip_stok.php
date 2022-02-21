<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$filter_arsip = @$_GET['filter_arsip'];

?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <!-- tombah tambah data -->
        <!-- <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#formModal">Tambah Data Barang</a> -->
        <div class="row">
          <div class="col-md-8">
            <h4>Cek Stok yang Diarsip</h4>
          </div>
          <div class="col-md-4 text-right">
            <!-- <a href="laporan/cetak_arsip_stok.php" class="btn btn-info" target="_blank">ARSIP</a> -->
            <select name="filter_arsip" id="filter_arsip" class="form-control" onchange="location.href = `?filter_arsip=${this.value}`">
              <option value="">Pilih Dataset</option>
              <?php
              $meta_arsip = $conn->query("SELECT * FROM meta_arsip_stok");
              while ($data = $meta_arsip->fetch_assoc()) : ?>
                <?php $id = "$data[no_arsip],$data[tgl_arsip],$data[user]" ?>
                <option value="<?= $id ?>" <?= $id === $filter_arsip ? 'selected' : '' ?>>
                  [<?= $data['no_arsip'] ?>] <?= $data['tgl_arsip'] ?> - <?= $data['user'] ?>
                </option>
              <?php endwhile; ?>
            </select>
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
              <th class="text-center">Stok Gudang</th>
              <th class="text-center">Stok Akumulasi</th>
              <th class="text-center">Stok Bergerak</th>
              <th>Keterangan</th>
              <!-- <th class="text-center">Operasi</th> -->
            </tr>
          </thead>
          <tbody>
            <?php if ($filter_arsip) : ?>
              <?php
              $arsip = explode(',', $filter_arsip);
              $no_arsip = $arsip[0];
              $tgl_arsip = $arsip[1];
              $user = $arsip[2];
              $no = 1;
              $query = "SELECT * FROM arsip_stok WHERE no_arsip = '$no_arsip' AND tgl_arsip = '$tgl_arsip' AND user = '$user'";
              // echo $query;
              $dt = $conn->query($query);
              while ($data = $dt->fetch_assoc()) :

              ?>
                <tr>
                  <td class="text-center"><?= $no; ?></td>
                  <td><?= $data['nama_barang'] ?></td>
                  <td class="text-center"><?= $data['stok'] ?> <?= $data['satuan'] ?></td>
                  <td class="text-center"><?= $data['stok_fisik'] ? $data['stok_fisik'] : 0 ?> <?= $data['satuan'] ?></td>
                  <td class="text-center"><?= $data['stok_fisik'] - $data['stok'] ?> <?= $data['satuan'] ?></td>
                  <td><?= $data['keterangan'] ?></td>
                </tr>
                <?php $no++; ?>
              <?php endwhile; ?>
            <?php else : ?>
              <td colspan="6" class="text-center">
                Silahkan pilih dataset untuk menampilkan data arsip
              </td>
            <?php endif; ?>
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
        <input type="submit" class="btn btn-primary" value="Verifikasi">
      </div>
    </form>
  </div>
</div>

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
      "ordering": false,
      "info": false
    });
  });
</script>

<?php require_once "layouts/footer.php" ?>