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
            <h4>Transaksi Reture Barang</h4>
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
              <th>Tanggal Reture</th>
              <th>Nama Barang</th>
              <th>Kondisi Barang</th>
              <th class="text-center">Jumlah</th>
              <th>Supplier</th>
              <th class="text-center">Operasi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $reture = $conn->query("SELECT reture_barang.*, barang.nama_barang, kondisi.nama as nama_kondisi, supplier.nama as nama_supplier, kategori.satuan FROM reture_barang INNER JOIN barang ON reture_barang.id_barang = barang.id LEFT JOIN kondisi ON reture_barang.id_kondisi = kondisi.id INNER JOIN supplier ON reture_barang.id_supplier = supplier.id LEFT JOIN kategori ON barang.id_kategori = kategori.id");
            while ($data = $reture->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= $data['tgl_reture'] ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td><?= $data['nama_kondisi'] ?></td>
                <td class="text-center"><?= $data['jumlah'] ?> <?= $data['satuan'] ?></td>
                <td><?= $data['nama_supplier'] ?></td>
                <td class="text-center">
                  <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#formModal" onclick='editForm(`<?= json_encode($data) ?>`)'>
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" onclick='deleteModal(`hapus_reture_barang.php?id=<?= $data["id"] ?>`, `Reture Barang: <?= $data["nama_barang"] ?>`)'>
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
    <form action="tambah_reture_barang.php" method="POST" id="form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Tambah Reture Barang</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- edit untuk mengubah isi form -->
        <input type="hidden" name="id" id="id" value="">
        <div class="form-group">
          <label for="tgl_reture">Tanggal Reture</label>
          <input type="date" name="tgl_reture" id="tgl_reture" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group">
          <label for="id_barang">Data Barang</label>
          <select class="form-control select2" name="id_barang" id="id_barang" style="width: 100%;" onchange="pilihBarang(this)">
            <option value="">Pilih Barang</option>
            <?php

            $barang = $conn->query("SELECT * FROM barang WHERE stok > 0");
            while ($data = $barang->fetch_assoc()) :

            ?>
              <option value="<?= $data['id'] ?>" data-stok="<?= $data['stok'] ?>"><?= $data['nama_barang'] ?> - Stok: <?= $data['stok'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="id_supplier">Data Supplier</label>
          <select class="form-control select2" name="id_supplier" id="id_supplier" style="width: 100%;">
            <option value="">Pilih Supplier</option>
            <?php

            $supplier = $conn->query("SELECT * FROM supplier");
            while ($data = $supplier->fetch_assoc()) :

            ?>
              <option value="<?= $data['id'] ?>"><?= $data['nama'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="id_kondisi">Kondisi Barang</label>
          <select class="form-control select2" name="id_kondisi" id="id_kondisi" style="width: 100%;">
            <option value="">Pilih Kondisi</option>
            <?php

            $kondisi = $conn->query("SELECT * FROM kondisi");
            while ($data = $kondisi->fetch_assoc()) :

            ?>
              <option value="<?= $data['id'] ?>"><?= $data['nama'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="jumlah">Jumlah</label>
          <div class="input-group my-colorpicker2">
            <input type="number" name="jumlah" id="jumlah" class="form-control" onkeyup="limitJumlah(this)" min="0">
            <div class="input-group-append">
              <span class="input-group-text" id="limit-jumlah">0</span>
            </div>
          </div>
          <!-- /.input group -->
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

<script>
  // fungsi untuk edit siswa
  function editForm(data) {
    // parse json data menjadi objek
    data = JSON.parse(data);
    // ikuti pola sesuaikan dengan id pada form modal data

    // ubah action dari form menjadi edit
    // let editAction = window.location.href + '&aksi=edit';
    let editAction = 'update_reture_barang.php';
    // console.log(window.location);
    $('#form').attr('action', editAction);

    // ubah judul form
    $('#formModalLabel').html('Edit Reture Barang');

    // ubah tombol tambah menjadi edit
    $('#form input[type=submit]').val('Edit');

    // ubah dan tambahkan sesuai form kalian
    $('#id').val(data.id);
    $('#tgl_reture').val(data.tgl_reture);
    $('#id_barang').val(data.id_barang);
    $('#id_supplier').val(data.id_supplier);
    $('#id_kondisi').val(data.id_kondisi);
    $('#jumlah').val(data.jumlah);
    $('#keterangan').val(data.keterangan);

    // tampilkan limit stok
    $('#limit-jumlah').text($('#id_barang').find(':selected').data('stok'));

  }

  function resetForm() {
    // trigger reset
    $("#formModal form").trigger("reset");

    // ubah link form
    $("#formModal form").attr("action", "tambah_reture_barang.php");

    // ubah judul form
    $("#formModalLabel").html("Tambah Reture Barang");

    // ubah tombol edit menjadi tambah
    $("#formModal form input[type=submit]").val("Tambah");

  }

  // jalankan fungsi saat memilih barang
  function pilihBarang(select) {
    $('#limit-jumlah').text($(select).find(':selected').data('stok'));
  }

  // batasi jumlah sesuai jumlah stok
  function limitJumlah(input) {
    const limit = Number($('#limit-jumlah').text());
    if (Number(input.value) > limit) {
      input.value = limit;
    }
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