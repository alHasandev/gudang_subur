<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$query = "SELECT mutasi_barang.*, barang.nama_barang, cabang.nama_cabang, kategori.nama_kategori FROM mutasi_barang INNER JOIN barang ON mutasi_barang.id_barang = barang.id LEFT JOIN cabang ON mutasi_barang.id_cabang = cabang.id LEFT JOIN kategori ON barang.id_kategori = kategori.id WHERE mutasi_barang.id > 0";

$filter_cabang = @$_POST['filter_cabang'];
$filter_kategori = @$_POST['filter_kategori'];
$tanggal_awal = @$_POST['tanggal_awal'];
$tanggal_akhir = @$_POST['tanggal_akhir'];

if($filter_cabang) {
  $query .= " AND id_cabang = '$filter_cabang'";
}

if($filter_kategori) {
  $query .= " AND id_kategori = '$filter_kategori'";
}

if($tanggal_awal) {
  $query .= " AND tanggal_awal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

?>
<div class="row">
  <div class="col-12">
    <form action="" method="POST" class="card">
      <div class="card-body row">
        <div class="col-md-3">
          <select name="filter_kategori" id="filter_kategori" class="form-control">
            <option value="">Filter Kategori</option>
            <?php

            $kategori = $conn->query("SELECT * FROM kategori");

            while ($data = $kategori->fetch_assoc()) :
            ?>
              <option value="<?= $data['id'] ?>" <?= ($data['id'] == $filter_kategori) ? 'selected' : '' ?>>
                <?= $data['nama_kategori'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-3">
          <select name="filter_cabang" id="filter_cabang" class="form-control">
            <option value="">Filter Cabang</option>
            <?php

            $cabang = $conn->query("SELECT * FROM cabang");

            while ($data = $cabang->fetch_assoc()) :
            ?>
              <option value="<?= $data['id'] ?>" <?= ($data['id'] == $filter_cabang) ? 'selected' : '' ?>>
                <?= $data['nama_cabang'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-4">
          <input type="hidden" name="tanggal_awal" id="tanggal_awal" value="<?= $tanggal_awal ?>">
          <input type="hidden" name="tanggal_akhir" id="tanggal_akhir" value="<?= $tanggal_akhir ?>">
          <button type="button" class="btn btn-default" name="filter_tanggal" id="filter_tanggal">
            <i class="fa fa-calendar"></i>
            <span>
              Filter Tanggal
            </span>
            <i class="fa fa-caret-down"></i>
          </button>
        </div>
        <div class="col-md-2 text-right">
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
            <h4>Laporan Mutasi Barang</h4>
          </div>
          <div class="col-md-6 text-right">
            <?php if ($filter_kategori || $filter_cabang || $tanggal_awal) : ?>
              <a href="" class="btn btn-warning">REFRESH</a>
            <?php endif; ?>
            <a href="laporan/cetak_mutasi_barang.php?kategori=<?= $filter_kategori ?>&cabang=<?= $filter_cabang ?>&tanggal_awal=<?= $tanggal_awal ?>&tanggal_akhir=<?= $tanggal_akhir ?>" class="btn btn-primary" target="_blank">PRINT</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="datatable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Tanggal</th>
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th class="text-center">Jumlah</th>
              <th>Cabang</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $barang_masuk = $conn->query($query);

            $total = 0;
            while ($data = $barang_masuk->fetch_assoc()) :

            ?>
              <tr>
                <td class="text-center"><?= $no; ?></td>
                <td><?= $data['tgl_mutasi'] ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td><?= $data['nama_kategori'] ?></td>
                <td class="text-center"><?= $data['jumlah'] ?></td>
                <td><?= $data['nama_cabang'] ?></td>
              </tr>
              <?php $no++; ?>
              <?php $total += $data['jumlah'] ?>
            <?php endwhile; ?>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center" colspan="3">Jumlah Total:</th>
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


<!-- scripts & libraries javascript -->
<script type="text/javascript" src="plugins/moment/moment.min.js"></script>
<script type="text/javascript" src="plugins/daterangepicker/daterangepicker.min.js"></script>
<script>
  $(function() {
    // datatable
    // $("#datatable").DataTable({
    //   "responsive": true,
    //   "lengthChange": false,
    //   "pageLength": 5,
    //   // "scrollY": 500,
    //   // "scrollX": true,
    //   "scrollCollapse": true,
    //   "autoWidth": false,
    //   "ordering": false,
    //   "info": false
    // });

    // date range picker
    const tanggal_awal = moment(new Date($('#tanggal_awal').val()));
    const tanggal_akhir = moment(new Date($('#tanggal_akhir').val()));
    // console.log(tanggal_awal.isValid())

    if (tanggal_awal.isValid() && tanggal_akhir.isValid()) {
      $('#filter_tanggal span').html(`${tanggal_awal.format('DD/MM/YYYY')} - ${tanggal_akhir.format('DD/MM/YYYY')}`);
    } else
    if (tanggal_awal.isValid()) {
      $('#filter_tanggal span').html(`${tanggal_awal.format('DD/MM/YYYY')} - ${$('#tanggal_awal').val()}`);
    }

    $('#filter_tanggal').daterangepicker({
      opens: 'left'
    }, (start, end, label) => {
      $('#filter_tanggal span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))
      $('#tanggal_awal').val(start.format('YYYY-MM-DD'));
      $('#tanggal_akhir').val(end.format('YYYY-MM-DD'));
    });
  });
</script>

<?php require_once "layouts/footer.php" ?>