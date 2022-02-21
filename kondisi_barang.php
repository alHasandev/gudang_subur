<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$col = "col-md-12";

$query = "SELECT barang.*, kategori.nama_kategori, kategori.satuan FROM barang LEFT JOIN kategori ON barang.id_kategori = kategori.id WHERE stok > 0";
if (isset($_GET['id_barang'])) {
  $col = "col-md-6";
  $query = "SELECT barang.*, kategori.nama_kategori, kategori.satuan FROM barang LEFT JOIN kategori ON barang.id_kategori = kategori.id WHERE barang.id = '$_GET[id_barang]'";
}

?>
<div class="row">
  <div class="<?= $col ?>">
    <div class="card">
      <div class="card-header">
        <!-- tombah tambah data -->
        <div class="row">
          <div class="col-md-6">
            <!-- Judul Halaman -->
            <h4>Data Barang</h4>
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
              <th>Nama Barang</th>
              <th class="text-center">Stok</th>
              <th class="text-center">Operasi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $barang = $conn->query($query);
            while ($data = $barang->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td class="text-center"><?= $data['stok'] ?> <?= $data['satuan'] ?></td>
                <td class="text-center">
                  <a href="kondisi_barang.php?id_barang=<?= $data['id'] ?>" class="btn btn-success btn-xs">
                    <i class="fas fa-search"></i>
                  </a>
                  <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#formModal" onclick='editForm(`<?= json_encode($data) ?>`)'>
                    <i class="fas fa-plus"></i>
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
  <?php if (isset($_GET['id_barang'])) : ?>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-6">
              <!-- Judul Halaman -->
              <h4>Kondisi Barang</h4>
            </div>
            <div class="col-6 text-right">
              <a href="kondisi_barang.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="datatable-2" class="table table-bordered">
            <thead>
              <tr>
                <th>Kondisi</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Operasi</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $konbar = $conn->query("SELECT kondisi_barang.*, kondisi.nama as nama_kondisi FROM kondisi_barang RIGHT JOIN kondisi ON kondisi_barang.id_kondisi = kondisi.id WHERE id_barang = '$_GET[id_barang]' AND jumlah > 0");
              while ($data = $konbar->fetch_assoc()) :

              ?>
                <tr>
                  <td><?= $data['nama_kondisi'] ?></td>
                  <td class="text-center"><?= $data['jumlah'] ?></td>
                  <td class="text-center">
                    <a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#formModal2" onclick='arsipForm(`<?= json_encode($data) ?>`)'>
                      <i class="fas fa-lock"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" onclick='deleteModal(`hapus_kondisi_barang.php?id=<?= $data["id"] ?>`, `Status Barang: <?= $data["nama_kondisi"] ?>`)'>
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- /.col -->
  <?php endif; ?>
</div>
<!-- /.row -->

<!-- Modal -->

<!-- Form Modal untuk tambah dan Edit Data -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <!-- atur form disini -->
    <form action="update_kondisi_barang.php" method="POST" id="form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Tambah Kondisi Barang</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
          <span aria-hidden="true" class="text-light">×</span>
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
          <label for="id_kondisi">Data Kondisi</label>
          <select name="id_kondisi" id="id_kondisi" class="form-control">
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
          <input type="number" name="jumlah" id="jumlah" class="form-control">
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

<!-- Modal -->

<!-- Form Modal untuk tambah dan Edit Data -->
<div class="modal fade" id="formModal2" tabindex="-1" role="dialog" aria-labelledby="formModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <!-- atur form disini -->
    <form action="tambah_arsip_barang.php" method="POST" id="form2" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Arsip Barang</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
          <span aria-hidden="true" class="text-light">×</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- edit untuk mengubah isi form -->
        <!-- <input type="hidden" name="id" id="id" value=""> -->
        <input type="hidden" name="id_barang" value="">
        <input type="hidden" name="id_kondisi" value="">
        <input type="hidden" name="kondisi" class="kondisi_barang">
        <input type="hidden" name="tgl_arsip" value="<?= date('Y-m-d') ?>">
        <p>Arsip barang dengan kondisi <strong class="kondisi_barang"></strong> pada tanggal <strong><?= date('d/m/Y') ?></strong> ?</p>
        <div class="form-group">
          <label for="jumlah">Jumlah</label>
          <div class="input-group">
            <input type="number" name="jumlah" min="0" class="form-control" onkeyup="cekJumlah(this)">
            <div class="input-group-append">
              <div class="input-group-text">
                <span id="label_jumlah">0</span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="keterangan">Keterangan</label>
          <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <!-- ubah tombol form -->
        <button class="btn btn-secondary" type="reset" data-dismiss="modal" onclick="resetForm()">Cancel</button>
        <input type="submit" class="btn btn-warning" value="Arsip">
      </div>
    </form>
  </div>
</div>

<script>
  // simpan data barang
  let barang;


  // fungsi untuk edit form
  function editForm(data) {
    // parse json data menjadi objek
    data = JSON.parse(data);
    // ikuti pola sesuaikan dengan id pada form modal data

    // ubah action dari form menjadi edit
    // let editAction = window.location.href + '&aksi=edit';
    let editAction = 'update_kondisi_barang.php';
    // console.log(window.location);
    $('#form').attr('action', editAction);

    // ubah judul form
    $('#formModalLabel').html('Tambah Data Kondisi');

    // ubah tombol tambah menjadi edit
    $('#form input[type=submit]').val('Tambah');

    // ubah dan tambahkan sesuai form kalian
    $('#id').val(data.id);
    $('#id_barang').val(data.id);
    $('#nama_barang').val(data.nama_barang);
    // $('#id_kondisi').val(data.id_kondisi);
    // $('#jumlah').val(data.jumlah);

  }

  function arsipForm(data) {
    data = JSON.parse(data);

    $('[name="id_barang"]').val(data.id_barang);
    $('[name="id_kondisi"]').val(data.id_kondisi);
    $('.kondisi_barang').html(data.nama_kondisi);
    $('.kondisi_barang').val(data.nama_kondisi);
    $('#label_jumlah').html(data.jumlah);
    console.log($('[name="id_barang"]'));
  }

  // jalankan fungsi saat user menginput unit inv rusak
  function cekJumlah(input) {
    let total_jumlah = Number($('#label_jumlah').html());
    let input_jumlah = Number(input.value);

    if (input_jumlah > total_jumlah) {
      input.value = total_jumlah;
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

  // datatable 2
  // $(function() {
  //   $("#datatable-2").DataTable({
  //     "responsive": true,
  //     "lengthChange": false,
  //     "pageLength": 5,
  //     // "scrollY": 500,
  //     // "scrollX": true,
  //     "scrollCollapse": true,
  //     // "autoWidth": false,
  //     "ordering": false,
  //     "info": false
  //   });
  // });
</script>

<?php require_once "layouts/footer.php" ?>