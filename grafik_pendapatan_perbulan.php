<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$filter = @$_POST['filter'];
$filter_tahun = @$_POST['filter_tahun'];
$filter_bulan = @$_POST['filter_bulan'];
$filter_cabang = @$_POST['filter_cabang'];

$query = "SELECT pendapatan_perbulan.*, cabang.nama_cabang, cabang.warna FROM pendapatan_perbulan INNER JOIN cabang ON pendapatan_perbulan.id_cabang = cabang.id WHERE pendapatan_perbulan.id > 0";

if ($filter_cabang) {
  $list_id_cabang = implode(',', $filter_cabang);
  $query .= " AND id_cabang IN($list_id_cabang)";
} else {
  $filter_cabang = [];
}

if ($filter_bulan && $filter_tahun) {
  $m_bulan = str_pad($filter_bulan + 1, 2, "0", STR_PAD_LEFT);
  $tgl = "$filter_tahun-$m_bulan-01";
  $no_minggu = tanggal($tgl, 'W');

  $tgl_terakhir = tanggal($tgl, 'Y-m-t');
  $minggu_terakhir = tanggal($tgl_terakhir, 'W');


  // siapkan data untuk grafik
  $query .= " AND tahun = $filter_tahun AND no_minggu BETWEEN $no_minggu AND $minggu_terakhir";
  $pendapatan_perbulan = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

  $nama_bulan = $list_bulan[$filter_bulan];
  $labels = [];
  $list_minggu = [];
  $j = 1;

  $tgl_awal = 1;
  for ($i = $no_minggu; $i <= $minggu_terakhir; $i++) {
    $hari_ke = tanggal("$filter_tahun-$m_bulan-" . str_pad($tgl_awal, 2, "0", STR_PAD_LEFT), 'w');
    $tgl_akhir = $tgl_awal + (6 - $hari_ke);
    array_push($labels, ["Minggu Ke-$j", "($tgl_awal - $tgl_akhir)"]);
    array_push($list_minggu, $i);
    $j++;
    $tgl_awal = $tgl_akhir + 1;
  }
}
// $pendapatan_cabang = $conn->query($query)->fetch_all(MYSQLI_ASSOC);


?>
<div class="row">
  <div class="col-md-12">
    <form action="" method="POST" class="card <?= $filter ? 'collapsed-card' : '' ?>" onsubmit="return checkFilter(this)">
      <div class="card-header">
        <h3 class="card-title">Grafik Pendapatan Cabang Perbulan</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas  <?= $filter ? 'fa-plus' : 'fa-minus' ?>"></i>
          </button>
          <a href="" class="btn btn-tool">
            <i class="fas fa-undo-alt"></i>
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <table id="datatable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th>Nama Cabang</th>
                <th>Kontak</th>
                <th>Alamat</th>
                <th class="text-center">
                    <div class="icheck-primary">
                      <input type="checkbox" name="pilih_semua" id="pilih_semua" onchange="pilihSemua(this)">
                      <label for="pilih_semua"></label>
                    </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php

              $no = 1;
              $cabang = $conn->query("SELECT * FROM cabang");
              while ($data = $cabang->fetch_assoc()) :

              ?>
                <tr>
                  <td class="text-center"><?= $no; ?></td>
                  <td><?= $data['nama_cabang'] ?></td>
                  <td><?= $data['kontak'] ?></td>
                  <td><?= $data['alamat'] ?></td>
                  <td class="text-center">
                    <!-- checkbox -->
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="<?= $data['id'] ?>" name="filter_cabang[]" id="filter_cabang_<?= $data['id'] ?>" <?= in_array($data['id'], $filter_cabang) ? 'checked' : '' ?>>
                      <label for="filter_cabang_<?= $data['id'] ?>"></label>
                    </div>
                  </td>
                </tr>
                <?php $no++ ?>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-3">
            <select name="filter_tahun" id="filter_tahun" class="form-control">
              <option value="">Tahun</option>
              <?php
              $tahun = getYears(2018, 2030, 1);
              foreach ($tahun as $i => $th) : ?>
                <option value="<?= $th ?>" <?= $th == $filter_tahun ? 'selected' : '' ?>>
                  <?= $th ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <select name="filter_bulan" id="filter_bulan" class="form-control">
              <option value="">Bulan</option>
              <?php foreach ($list_bulan as $i => $bulan) : ?>
                <option value="<?= $i ?>" <?= "$i" === $filter_bulan ? 'selected' : '' ?>>
                  <?= $bulan ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 text-right">
            <input type="submit" name="filter" value="TAMPILKAN GRAFIK" class="btn btn-primary">
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<?php if ($filter) : ?>
  <div class="d-flex flex-wrap">
    <small class="alert alert-light mr-1">Note: jika grafik tidak muncul berarti data grafik belum tersedia</small>
    <small class="alert alert-light">Note: nilai grafik berdasarkan Rupiah (Rp.) dan Pecahan sejuta (/Jt)</small>
  </div>

  <div class="row" id="row_perbulan">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Grafik Perbulan: <strong><?= $list_bulan[$filter_bulan] ?> <?= $filter_tahun ?></strong>
          </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="chart">
            <canvas id="grafik_perbulan" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
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
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Custom Script -->
  <script src="assets/js/script.js"></script>
  <script>
    const pendapatan_perbulan = JSON.parse('<?= json_encode($pendapatan_perbulan) ?>');
    console.log('pendapatan_perbulan', pendapatan_perbulan);


    // Grafik Bar Perbulan
    if (document.getElementById('grafik_perbulan') && pendapatan_perbulan.length) {

      const list_minggu = JSON.parse('<?= json_encode($list_minggu) ?>');
      const labels = JSON.parse('<?= json_encode($labels) ?>');

      const pendapatan_perbulan = JSON.parse('<?= json_encode($pendapatan_perbulan) ?>');
      let formatDataGrafik = {};
      console.log('pendapatan_perbulan', pendapatan_perbulan);

      pendapatan_perbulan.map(pendapatan => {
        if (!!formatDataGrafik[`${pendapatan['nama_cabang']}`]) {
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['no_minggu']}`] = pendapatan['rerata'] / 1000000;
        } else {
          formatDataGrafik[`${pendapatan['nama_cabang']}`] = {};
          formatDataGrafik[`${pendapatan['nama_cabang']}`]['warna'] = pendapatan['warna'];
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['no_minggu']}`] = pendapatan['rerata'] / 1000000;
        }
      });

      console.log('formatDataGrafik', formatDataGrafik);

      // const nilaiGrafik1 = weekDates.map(date => {
      //   return formatDataGrafik[date] || 0;
      // });

      const datasets = Object.keys(formatDataGrafik).map(nama_cabang => {
        let nilaiGrafik = list_minggu.map(no => {
          return formatDataGrafik[nama_cabang][no] || 0;
        });

        return {
          label: nama_cabang,
          backgroundColor: formatDataGrafik[nama_cabang]['warna'],
          borderColor: formatDataGrafik[nama_cabang]['warna'],
          data: nilaiGrafik,
        }
      });

      console.log('datasets', datasets);

      const chartData = {
        labels: labels,
        datasets: datasets
      }

      const canvas = $('#grafik_perbulan').get(0).getContext('2d')
      const dataGrafik = jQuery.extend(true, {}, chartData)
      const temp0 = chartData.datasets[0]
      dataGrafik.datasets[0] = temp0

      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      const grafik = new Chart(canvas, {
        type: 'bar',
        data: chartData,
        options: chartOptions
      });
    } else {
      document.getElementById('row_perbulan').classList.add('d-none');
    }
  </script>
<?php else : ?>
  <div class="alert bg-color">Silahkan filter tanggal dan cabang untuk menampilkan grafik !</div>
<?php endif; ?>

<script>
  // fungsi pilih semua
    function pilihSemua(check) {
      if($(check).is(':checked')) {
        document.querySelectorAll('[name="filter_cabang[]"]').forEach(cabang => {
          cabang.setAttribute('checked', '');
        });
      } else {
        document.querySelectorAll('[name="filter_cabang[]"]').forEach(cabang => {
          cabang.removeAttribute('checked');
        });
      }
    }
</script>

<?php require_once "layouts/footer.php" ?>