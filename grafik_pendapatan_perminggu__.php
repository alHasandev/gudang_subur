<?php

require_once "app/helpers.php";
require_once "app/koneksi.php";

$filter = @$_POST['filter'];
$filter_tahun = @$_POST['filter_tahun'];
$filter_bulan = @$_POST['filter_bulan'];
$filter_cabang = @$_POST['filter_cabang'];

$query = "SELECT pendapatan_perhari.*, cabang.nama_cabang, cabang.warna FROM pendapatan_perhari INNER JOIN cabang ON pendapatan_perhari.id_cabang = cabang.id WHERE pendapatan_perhari.id > 0";

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
  $query .= " AND YEAR(tanggal) = $filter_tahun AND MONTH(tanggal) = $filter_bulan + 1";

  $nama_bulan = $list_bulan[$filter_bulan];
  $labels = [];
  $j = 1;

  $tgl_awal = 1;
  $pendapatan_perminggu = [];
  for ($i = $no_minggu; $i <= $minggu_terakhir; $i++) {
    $hari_ke = tanggal("$filter_tahun-$m_bulan-" . str_pad($tgl_awal, 2, "0", STR_PAD_LEFT), 'w');
    $tgl_akhir = $tgl_awal + (6 - $hari_ke);

    $hasil = $conn->query($query . " AND WEEK(tanggal) = $no_minggu + $j - 1")->fetch_all(MYSQLI_ASSOC);
    $pendapatan_perminggu[$j] = $hasil;

    $j++;
    $tgl_awal = $tgl_akhir + 1;
  }
  echo json_encode($pendapatan_perminggu);
}


die;

// if ($filter_bulan && $filter_tahun) {
//   $no_minggu = tanggal("$filter_tahun-" . str_pad($filter_bulan + 1, 2, "0", STR_PAD_LEFT) . "-01", 'W');

//   $query .= " AND YEAR(tanggal) = $filter_tahun AND MONTH(tanggal) = $filter_bulan + 1";
//   $pendapatan_minggu_1 = $conn->query($query . " AND WEEK(tanggal) = $no_minggu")->fetch_all(MYSQLI_ASSOC);
//   $pendapatan_minggu_2 = $conn->query($query . " AND WEEK(tanggal) = $no_minggu + 1")->fetch_all(MYSQLI_ASSOC);
//   $pendapatan_minggu_3 = $conn->query($query . " AND WEEK(tanggal) = $no_minggu + 2")->fetch_all(MYSQLI_ASSOC);
//   $pendapatan_minggu_4 = $conn->query($query . " AND WEEK(tanggal) = $no_minggu + 3")->fetch_all(MYSQLI_ASSOC);
//   $pendapatan_minggu_5 = $conn->query($query . " AND WEEK(tanggal) = $no_minggu + 4")->fetch_all(MYSQLI_ASSOC);
// }

// echo $query;
// $pendapatan_cabang = $conn->query($query)->fetch_all(MYSQLI_ASSOC);


?>
<div class="row">
  <div class="col-md-12">
    <form action="" method="POST" class="card <?= $filter ? 'collapsed-card' : '' ?>" onsubmit="return checkFilter(this)">
      <div class="card-header">
        <h3 class="card-title">Grafik Pendapatan Cabang Perminggu</h3>
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
                <th class="text-center">Pilih</th>
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
                <option value="<?= $i ?>" <?= $i == $filter_bulan ? 'selected' : '' ?>>
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
  <div class="mb-3">
    <small class="alert alert-light">Note: bila grafik tidak muncul berarti data grafik belum tersedia</small>
    <small class="alert alert-light">Note: nilai grafik berdasarkan Rupiah (Rp.) dan Pecahan sejuta (/Jt)</small>
  </div>

  <?php foreach ($pendapatan_perminggu as $i => $pendapatan) : ?>
    <div class="row" id="row_minggu_<?= $i ?>">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Grafik Minggu Ke-<?= $i ?>:</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="chart">
              <canvas id="grafik_minggu_<?= $i ?>" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    
  <?php endforeach; ?>

  <div class="row" id="row_minggu_1">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Grafik Minggu Ke-1:</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="chart">
            <canvas id="grafik_minggu_1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row" id="row_minggu_2">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Grafik Minggu Ke-2:</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="chart">
            <canvas id="grafik_minggu_2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row" id="row_minggu_3">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Grafik Minggu Ke-3:</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="chart">
            <canvas id="grafik_minggu_3" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row" id="row_minggu_4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Grafik Minggu Ke-4:</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="chart">
            <canvas id="grafik_minggu_4" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row" id="row_minggu_5">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Grafik Minggu Ke-5:</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="chart">
            <canvas id="grafik_minggu_5" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
    const pendapatan_minggu_1 = JSON.parse('<?= json_encode($pendapatan_minggu_1) ?>');
    const pendapatan_minggu_2 = JSON.parse('<?= json_encode($pendapatan_minggu_2) ?>');
    const pendapatan_minggu_3 = JSON.parse('<?= json_encode($pendapatan_minggu_3) ?>');
    const pendapatan_minggu_4 = JSON.parse('<?= json_encode($pendapatan_minggu_4) ?>');
    const pendapatan_minggu_5 = JSON.parse('<?= json_encode($pendapatan_minggu_5) ?>');


    // Grafik Bar Minggu ke-1
    if (document.getElementById('grafik_minggu_1') && pendapatan_minggu_1.length) {

      const weekDates = getDateRangeOfWeek(<?= $no_minggu ?>, 2021);
      const labels = namaHari.map((hari, i) => `${hari} (${weekDates[i]})`);

      const pendapatan_minggu_1 = JSON.parse('<?= json_encode($pendapatan_minggu_1) ?>');
      let formatDataGrafik = {};
      console.log('pendapatan_minggu_1', pendapatan_minggu_1);

      pendapatan_minggu_1.map(pendapatan => {
        if (!!formatDataGrafik[`${pendapatan['nama_cabang']}`]) {
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        } else {
          formatDataGrafik[`${pendapatan['nama_cabang']}`] = {};
          formatDataGrafik[`${pendapatan['nama_cabang']}`]['warna'] = pendapatan['warna'];
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        }
      });

      console.log('formatDataGrafik', formatDataGrafik);

      // const nilaiGrafik1 = weekDates.map(date => {
      //   return formatDataGrafik[date] || 0;
      // });

      const datasets = Object.keys(formatDataGrafik).map(nama_cabang => {
        let nilaiGrafik = weekDates.map(date => {
          return formatDataGrafik[nama_cabang][date] || 0;
        });

        return {
          label: nama_cabang,
          backgroundColor: formatDataGrafik[nama_cabang]['warna'],
          borderColor: formatDataGrafik[nama_cabang]['warna'],
          data: nilaiGrafik
        }
      });

      console.log('datasets', datasets);

      const chartData_1 = {
        labels: labels,
        datasets: datasets
      }

      const canvas_1 = $('#grafik_minggu_1').get(0).getContext('2d')
      const dataGrafik_1 = jQuery.extend(true, {}, chartData_1)
      const temp0 = chartData_1.datasets[0]
      dataGrafik_1.datasets[0] = temp0

      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      const grafik_1 = new Chart(canvas_1, {
        type: 'bar',
        data: chartData_1,
        options: chartOptions
      });
    } else {
      document.getElementById('row_minggu_1').classList.add('d-none');
    }

    // Grafik Bar Minggu ke-2
    if (document.getElementById('grafik_minggu_2') && pendapatan_minggu_2.length) {

      const weekDates = getDateRangeOfWeek(<?= $no_minggu ?>, 2021);
      const labels = namaHari.map((hari, i) => `${hari} (${weekDates[i]})`);

      const pendapatan_minggu_2 = JSON.parse('<?= json_encode($pendapatan_minggu_2) ?>');
      let formatDataGrafik = {};
      console.log('pendapatan_minggu_2', pendapatan_minggu_2);

      pendapatan_minggu_2.map(pendapatan => {
        if (!!formatDataGrafik[`${pendapatan['nama_cabang']}`]) {
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        } else {
          formatDataGrafik[`${pendapatan['nama_cabang']}`] = {};
          formatDataGrafik[`${pendapatan['nama_cabang']}`]['warna'] = pendapatan['warna'];
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        }
      });

      console.log('formatDataGrafik', formatDataGrafik);

      // const nilaiGrafik1 = weekDates.map(date => {
      //   return formatDataGrafik[date] || 0;
      // });

      const datasets = Object.keys(formatDataGrafik).map(nama_cabang => {
        let nilaiGrafik = weekDates.map(date => {
          return formatDataGrafik[nama_cabang][date] || 0;
        });

        return {
          label: nama_cabang,
          backgroundColor: formatDataGrafik[nama_cabang]['warna'],
          borderColor: formatDataGrafik[nama_cabang]['warna'],
          data: nilaiGrafik
        }
      });

      console.log('datasets', datasets);

      const chartData_2 = {
        labels: labels,
        datasets: datasets
      }

      const canvas_2 = $('#grafik_minggu_2').get(0).getContext('2d')
      const dataGrafik_2 = jQuery.extend(true, {}, chartData_2)
      const temp0 = chartData_2.datasets[0]
      dataGrafik_2.datasets[0] = temp0

      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      const grafik_2 = new Chart(canvas_2, {
        type: 'bar',
        data: chartData_2,
        options: chartOptions
      });
    } else {
      document.getElementById('row_minggu_2').classList.add('d-none');
    }

    // Grafik Bar Minggu ke-3
    if (document.getElementById('grafik_minggu_3') && pendapatan_minggu_3.length) {

      const weekDates = getDateRangeOfWeek(<?= $no_minggu ?>, 2021);
      const labels = namaHari.map((hari, i) => `${hari} (${weekDates[i]})`);

      const pendapatan_minggu_3 = JSON.parse('<?= json_encode($pendapatan_minggu_3) ?>');
      let formatDataGrafik = {};
      console.log('pendapatan_minggu_3', pendapatan_minggu_3);

      pendapatan_minggu_3.map(pendapatan => {
        if (!!formatDataGrafik[`${pendapatan['nama_cabang']}`]) {
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        } else {
          formatDataGrafik[`${pendapatan['nama_cabang']}`] = {};
          formatDataGrafik[`${pendapatan['nama_cabang']}`]['warna'] = pendapatan['warna'];
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        }
      });

      console.log('formatDataGrafik', formatDataGrafik);

      // const nilaiGrafik1 = weekDates.map(date => {
      //   return formatDataGrafik[date] || 0;
      // });

      const datasets = Object.keys(formatDataGrafik).map(nama_cabang => {
        let nilaiGrafik = weekDates.map(date => {
          return formatDataGrafik[nama_cabang][date] || 0;
        });

        return {
          label: nama_cabang,
          backgroundColor: formatDataGrafik[nama_cabang]['warna'],
          borderColor: formatDataGrafik[nama_cabang]['warna'],
          data: nilaiGrafik
        }
      });

      console.log('datasets', datasets);

      const chartData_3 = {
        labels: labels,
        datasets: datasets
      }

      const canvas_3 = $('#grafik_minggu_3').get(0).getContext('2d')
      const dataGrafik_3 = jQuery.extend(true, {}, chartData_3)
      const temp0 = chartData_3.datasets[0]
      dataGrafik_3.datasets[0] = temp0

      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      const grafik_3 = new Chart(canvas_3, {
        type: 'bar',
        data: chartData_3,
        options: chartOptions
      });
    } else {
      document.getElementById('row_minggu_3').classList.add('d-none');
    }

    // Grafik Bar Minggu ke-4
    if (document.getElementById('grafik_minggu_4') && pendapatan_minggu_4.length) {

      const weekDates = getDateRangeOfWeek(<?= $no_minggu ?>, 2021);
      const labels = namaHari.map((hari, i) => `${hari} (${weekDates[i]})`);

      const pendapatan_minggu_4 = JSON.parse('<?= json_encode($pendapatan_minggu_4) ?>');
      let formatDataGrafik = {};
      console.log('pendapatan_minggu_4', pendapatan_minggu_4);

      pendapatan_minggu_4.map(pendapatan => {
        if (!!formatDataGrafik[`${pendapatan['nama_cabang']}`]) {
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        } else {
          formatDataGrafik[`${pendapatan['nama_cabang']}`] = {};
          formatDataGrafik[`${pendapatan['nama_cabang']}`]['warna'] = pendapatan['warna'];
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        }
      });

      console.log('formatDataGrafik', formatDataGrafik);

      // const nilaiGrafik1 = weekDates.map(date => {
      //   return formatDataGrafik[date] || 0;
      // });

      const datasets = Object.keys(formatDataGrafik).map(nama_cabang => {
        let nilaiGrafik = weekDates.map(date => {
          return formatDataGrafik[nama_cabang][date] || 0;
        });

        return {
          label: nama_cabang,
          backgroundColor: formatDataGrafik[nama_cabang]['warna'],
          borderColor: formatDataGrafik[nama_cabang]['warna'],
          data: nilaiGrafik
        }
      });

      console.log('datasets', datasets);

      const chartData_4 = {
        labels: labels,
        datasets: datasets
      }

      const canvas_4 = $('#grafik_minggu_4').get(0).getContext('2d')
      const dataGrafik_4 = jQuery.extend(true, {}, chartData_4)
      const temp0 = chartData_4.datasets[0]
      dataGrafik_4.datasets[0] = temp0

      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      const grafik_4 = new Chart(canvas_4, {
        type: 'bar',
        data: chartData_4,
        options: chartOptions
      });
    } else {
      document.getElementById('row_minggu_4').classList.add('d-none');
    }

    // Grafik Bar Minggu ke-5
    if (document.getElementById('grafik_minggu_5') && pendapatan_minggu_5.length) {

      const weekDates = getDateRangeOfWeek(<?= $no_minggu + 4 ?>, 2021);
      const labels = namaHari.map((hari, i) => `${hari} (${weekDates[i]})`);

      const pendapatan_minggu_5 = JSON.parse('<?= json_encode($pendapatan_minggu_5) ?>');
      let formatDataGrafik = {};
      console.log('pendapatan_minggu_5', pendapatan_minggu_5);

      pendapatan_minggu_5.map(pendapatan => {
        // console.log('pendapatan', pendapatan);
        if (!!formatDataGrafik[`${pendapatan['nama_cabang']}`]) {
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        } else {
          formatDataGrafik[`${pendapatan['nama_cabang']}`] = {};
          formatDataGrafik[`${pendapatan['nama_cabang']}`]['warna'] = pendapatan['warna'];
          formatDataGrafik[`${pendapatan['nama_cabang']}`][`${pendapatan['tanggal']}`] = pendapatan['jumlah'] / 1000000;
        }
      });

      const datasets = Object.keys(formatDataGrafik).map(nama_cabang => {
        let nilaiGrafik = weekDates.map(date => {
          return formatDataGrafik[nama_cabang][date] || 0;
        });

        return {
          label: nama_cabang,
          backgroundColor: formatDataGrafik[nama_cabang]['warna'],
          borderColor: formatDataGrafik[nama_cabang]['warna'],
          data: nilaiGrafik
        }
      });

      const chartData_5 = {
        labels: labels,
        datasets: datasets
      }

      const canvas_5 = $('#grafik_minggu_5').get(0).getContext('2d')
      const dataGrafik_5 = jQuery.extend(true, {}, chartData_5)
      const temp0 = chartData_5.datasets[0]
      dataGrafik_5.datasets[0] = temp0

      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      const grafik_5 = new Chart(canvas_5, {
        type: 'bar',
        data: chartData_5,
        options: chartOptions
      });
    } else {
      document.getElementById('row_minggu_5').classList.add('d-none');
    }
  </script>
<?php else : ?>
  <div class="alert bg-color">Silahkan filter tanggal dan cabang untuk menampilkan grafik !</div>
<?php endif; ?>

<?php require_once "layouts/footer.php" ?>