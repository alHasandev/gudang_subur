<?php

const NAMA_APLIKASI = "gudang_bansos";
const HALAMAN_UTAMA = "home";
const NAMA_TTD = "Manager Subur Group";

// list bulan
$list_bulan = [
  'Januari',
  'Pebruari',
  'Maret',
  'April',
  'Mei',
  'Juni',
  'Juli',
  'Agustus',
  'September',
  'Oktober',
  'November',
  'Desember'
];

function base_url($param = '')
{
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $domainName = $_SERVER['HTTP_HOST'] .  '/';
  $baseurl = $protocol . $domainName . NAMA_APLIKASI;

  return $baseurl . '/' . $param;
}

function page($page = 'home', $aksi = false, $id = null)
{
  $halaman = '?page=' . $page;

  if ($aksi) {
    $halaman .= '&aksi=' . $aksi;

    if ($id !== null) {
      $halaman .= '&id=' . $id;
    }
  }

  return base_url($halaman);
}

function redirect($page = false)
{
  if (!$page) {
    $page = HALAMAN_UTAMA;
  }

  header('Location: ' . page($page));
}

// buat fungsi untuk menghandle link active
function activeLink($page)
{
  // $endurl = $_GET['page'];
  $endurl = getPagename();

  $output = FALSE;
  if (!is_array($page)) {
    if ($page === $endurl) $output = TRUE;
  } else {
    foreach ($page as $pg) {
      if ($pg === $endurl) $output = TRUE;
    }
  }

  return $output;
}

// buat fungsi untuk mengambil nama halaman sekarang dari url
function getPagename()
{
  $endurl = explode('/', $_SERVER['REQUEST_URI']);

  $pagename = end($endurl);

  return str_replace('.php', '', $pagename);
}

// buat fungsi untuk mendapatkan list tahun 
function getYears($from = 0, $to = 0, $interval = 10)
{
  // buat nilai default untuk range tahun
  if ($from <= 0) $from = date('Y') + $from;
  if ($to <= 0) $to = date('Y') + $to + $interval;

  // buat batas perulangan
  // $max = $from - $to;

  // init data list tahun
  $years = [];

  // isi data list tahun
  for ($from; $from <= $to; $from++) {
    $years[] = $from;
  }

  return $years;
}

// buat fungsi untuk menkonversi urutan hari (angka) menjadi nama hari
function dayName($d)
{
  $days = [
    'Minggu',
    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jum\'at',
    'Sabtu'
  ];

  return $days[$d];
}

// buat fungsi untuk menampilkan angka dalam bentuk rupiah
function rupiah($angka)
{
  $hasil_rupiah = "Rp. " . number_format($angka, 0, ',', '.');
  return $hasil_rupiah;
}

// buat fungsi untuk memformat tanggal dari database
function tanggal($date, $format = 'd/m/Y')
{
  $date = date_create($date);
  return date_format($date, $format);
}

// khusus untuk keterangan pelunasan mutasi barang
function keterangan_mutasi($total_harga, $pembayaran)
{
  $sisa = $total_harga - $pembayaran;
  if ($sisa === 0) {
    $keterangan = 'Lunas';
  } else {
    $keterangan = 'Belum Lunas';
  }

  return $keterangan;
}
