<?php

require_once "layouts/header.php";
require_once "app/koneksi.php";

$filter = @$_POST['filter'];
$filter_tgl = @$_POST['filter_tanggal'];
$filter_cabang = @$_POST['filter_cabang'];

$query = "SELECT pendapatan_perhari.*, cabang.nama_cabang FROM pendapatan_perhari INNER JOIN cabang ON pendapatan_perhari.id_cabang = cabang.id WHERE pendapatan_perhari.id > 0";

if ($filter_tgl) {
  $query .= " AND tanggal = '$filter_tgl'";
} else {
  $filter_tgl = date('Y-m-d');
}

if ($filter_cabang) {
  $list_id_cabang = implode(',', $filter_cabang);
  $query .= " AND id_cabang IN($list_id_cabang)";
} else {
  $filter_cabang = [];
}

$pendapatan_cabang = $conn->query($query)->fetch_all(MYSQLI_ASSOC);


?>
<div class="row">
  <div class="col-md-12">
    <form action="" method="POST" class="card <?= $filter ? 'collapsed-card' : '' ?>" onsubmit="return checkFilter(this)">
      <div class="card-header">
        <h3 class="card-title">Grafik Pendapatan Cabang Perhari</h3>
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
<!-- checkbox -->
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
                    <div class="icheck-primary">
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
          <div class="col-md-4">
            <input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control" value="<?= $filter_tgl ?>">
          </div>
          <div class="col-md-8 text-right">
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

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Grafik Tanggal: <?= $filter_tgl ?></h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool"><i class="fas fa-print"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="chart">
            <canvas id="grafikPendapatan" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
    // styles
    const color = {
      primary: "#87ceeb",
      secondary: "#343a40",
      default: "rgba(210, 214, 222, 1)"
    }

    const pendapatan_cabang = JSON.parse('<?= json_encode($pendapatan_cabang) ?>');
    console.log(pendapatan_cabang);

    const labels = pendapatan_cabang.map(data => data['nama_cabang']);

    const nilaiGrafik = pendapatan_cabang.map(data => data['jumlah'] / 1000000);

    const chartData = {
      labels: labels,
      datasets: [{
        label: `Pendapatan Cabang Perhari (Rp. / Juta)`,
        backgroundColor: color.primary,
        borderColor: color.primary,
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: nilaiGrafik
      }]
    }

    if (document.getElementById('grafikPendapatan')) {
      //-------------
      //- BAR CHART - grafik
      //-------------
      const canvas = $('#grafikPendapatan').get(0).getContext('2d')
      const dataGrafik = jQuery.extend(true, {}, chartData)
      const temp0 = chartData.datasets[0]
      dataGrafik.datasets[0] = temp0

      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false,
        scales: {
          yAxes: [{
            ticks:{
              min:0
            }
          }]
        }
      }

      const grafik = new Chart(canvas, {
        type: 'bar',
        data: chartData,
        options: chartOptions
      });
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