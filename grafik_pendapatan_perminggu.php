<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$filter = @$_POST['filter'];
$filter_tahun = @$_POST['filter_tahun'];
$filter_bulan = @$_POST['filter_bulan'];
$filter_cabang = @$_POST['filter_cabang'];
$pendapatan_perminggu = [];

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
  $list_minggu = [];
  $j = 1;

  $tgl_awal = 1;
  for ($i = $no_minggu; $i <= $minggu_terakhir; $i++) {
    $hari_ke = tanggal("$filter_tahun-$m_bulan-" . str_pad($tgl_awal, 2, "0", STR_PAD_LEFT), 'w');
    $tgl_akhir = $tgl_awal + (6 - $hari_ke);
    $list_minggu[$j] = intval($i);

    $hasil = $conn->query($query . " AND WEEK(tanggal) = $no_minggu + $j - 1")->fetch_all(MYSQLI_ASSOC);
    $pendapatan_perminggu[$j] = $hasil;

    $j++;
    $tgl_awal = $tgl_akhir + 1;
  }
  // echo json_encode($list_minggu);
  // die;
}


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
                <option value="<?= $i ?>" <?= "$i" == $filter_bulan ? 'selected' : '' ?>>
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
    <small class="alert alert-light">Note: jika grafik tidak muncul berarti data grafik belum tersedia</small>
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


  <!-- scripts & libraries javascript -->
  <script type="text/javascript" src="plugins/moment/moment.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Custom Script -->
  <script src="assets/js/script.js"></script>
  <script>
    const pendapatan_perminggu = JSON.parse('<?= json_encode($pendapatan_perminggu) ?>');
    const list_minggu = JSON.parse('<?= json_encode($list_minggu) ?>');
    console.log('list_minggu', list_minggu);

    Object.keys(pendapatan_perminggu).map(i => {
      const perminggu = pendapatan_perminggu[i];
      console.log(i, perminggu);
      // Grafik Bar Minggu ke-i
      if (document.getElementById(`grafik_minggu_${i}`) && perminggu.length) {
        console.log('minggu ke-' + i, list_minggu[i]);
        const weekDates = getDateRangeOfWeek(list_minggu[i], 2021);
        const labels = namaHari.map((hari, i) => [`${hari}`, `(${weekDates[i]})`]);

        let formatDataGrafik = {};

        perminggu.map(pendapatan => {
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

        const chartData = {
          labels: labels,
          datasets: datasets
        }

        const canvas = $(`#grafik_minggu_${i}`).get(0).getContext('2d')
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
        document.getElementById(`row_minggu_${i}`).classList.add('d-none');
      }
    })
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