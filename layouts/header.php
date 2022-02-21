<?php


session_start();
$user = @$_SESSION['user'];
if (!$user) {
  header('Location: login.php');
  die;
}

require_once "./app/helpers.php";

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gudang Subur</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="./plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="./plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- daterangepicker -->
  <link rel="stylesheet" type="text/css" href="plugins/daterangepicker/daterangepicker.css" />
  <!-- Theme style -->
  <link rel="stylesheet" href="./assets/css/adminlte.min.css">
  <!-- Custom style -->
  <link rel="stylesheet" href="./assets/css/style.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- jQuery -->
  <script src="./plugins/jquery/jquery.min.js"></script>
  <script src="./assets/js/form-modal.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index.php" class="nav-link">Home</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link text-uppercase font-weight-bold" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <!-- <i class="fas fa-th-large"></i> -->
            <?= $user['nama'] ?>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->


    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #87CEEB;">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link" style="border-bottom: 1px solid rgba(255,255,255,.8);">
        <img src="assets/img/wbb.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Gudang Subur</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="border-bottom: 1px solid rgba(255,255,255,.8);">
          <div class="image">
            <img src="<?= $user['foto'] ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?= $user['username'] ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="index.php" class="nav-link <?= activeLink('index') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <?php

            switch ($user['hak_akses']) {
              case 'MASTER':
              case 'ADMIN_GUDANG':

            ?>
                <li class="nav-header">TRANSAKSI GUDANG</li>
                <li class="nav-item">
                  <a href="barang_masuk.php" class="nav-link <?= activeLink('barang_masuk') ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-cart-arrow-down"></i>
                    <p>
                      Barang Masuk
                    </p>
                  </a>
                <li class="nav-item">
                  <a href="mutasi_barang.php" class="nav-link <?= activeLink('mutasi_barang') ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-truck-moving"></i>
                    <p>
                      Mutasi Barang
                    </p>
                  </a>
                </li>
                </li>
                <li class="nav-item">
                  <a href="reture_barang.php" class="nav-link <?= activeLink('reture_barang') ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-undo-alt"></i>
                    <p>
                      Reture Barang
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="kondisi_barang.php" class="nav-link <?= activeLink('kondisi_barang') ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-notes-medical"></i>
                    <p>
                      Kondisi Barang
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="arsip_barang.php" class="nav-link <?= activeLink('arsip_barang') ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-lock"></i>
                    <p>
                      Arsip Barang
                    </p>
                  </a>
                </li>
            <?php
                break;
            }

            ?>

            <?php
            switch ($user['hak_akses']) {
              case 'MASTER':
              case 'ADMIN_STOK':
            ?>
                <li class="nav-header">PENGENDALIAN STOK</li>
                <li class="nav-item">
                  <a href="stok_barang.php" class="nav-link <?= activeLink('stok_barang') ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>
                      Stok Barang
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="arsip_stok.php" class="nav-link <?= activeLink('arsip_stok') ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-file-archive"></i>
                    <p>
                      Stok Diarsip
                    </p>
                  </a>
                </li>
            <?php
                break;
            }
            ?>


            <?php if ($user['hak_akses'] === 'MASTER') : ?>
              <li class="nav-header">PENDAPATAN CABANG</li>
              <li class="nav-item">
                <a href="pendapatan_perhari.php" class="nav-link <?= activeLink('pendapatan_perhari') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-hand-holding-usd"></i>
                  <p>
                    Pendapatan Perhari
                  </p>
                </a>
              </li>

              <li class="nav-header">DATA MASTER</li>
              <li class="nav-item">
                <a href="data_satuan.php" class="nav-link <?= activeLink('data_satuan') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-weight"></i>
                  <p>
                    Data Satuan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="data_kategori.php" class="nav-link <?= activeLink('data_kategori') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-clipboard-list"></i>
                  <p>
                    Data Kategori
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="data_barang.php" class="nav-link <?= activeLink('data_barang') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-warehouse"></i>
                  <p>
                    Data Barang
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="data_cabang.php" class="nav-link <?= activeLink('data_cabang') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-laptop-house"></i>
                  <p>
                    Data Cabang
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="data_kondisi.php" class="nav-link <?= activeLink('data_kondisi') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-notes-medical"></i>
                  <p>
                    Data Kondisi
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="data_supplier.php" class="nav-link <?= activeLink('data_supplier') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-building"></i>
                  <p>
                    Data Supplier
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="data_admin.php" class="nav-link <?= activeLink('data_admin') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Data Admin
                  </p>
                </a>
              </li>

            <?php endif; ?>

            <li class="nav-header">LAPORAN</li>
            <?php switch ($user['hak_akses']) {
              case "MASTER": ?>
                <li class="nav-item">
                  <a href="lap_admin.php" class="nav-link <?= activeLink('lap_admin') ? 'active' : '' ?>">
                    <i class="nav-icon far fa-file-pdf"></i>
                    <p>
                      Laporan Admin
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="lap_pemasok.php" class="nav-link <?= activeLink('lap_pemasok') ? 'active' : '' ?>">
                    <i class="nav-icon far fa-file-pdf"></i>
                    <p>
                      Laporan Pemasok
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="lap_cabang.php" class="nav-link <?= activeLink('lap_cabang') ? 'active' : '' ?>">
                    <i class="nav-icon far fa-file-pdf"></i>
                    <p>
                      Laporan Cabang
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="lap_pendapatan_cabang.php" class="nav-link <?= activeLink('lap_pendapatan_cabang') ? 'active' : '' ?>">
                    <i class="nav-icon far fa-file-pdf"></i>
                    <p>
                      Lap. Pendapatan Cabang
                    </p>
                  </a>
                </li>
                <li class="nav-item has-treeview <?= activeLink(['grafik_pendapatan_perhari', 'grafik_pendapatan_perminggu', 'grafik_pendapatan_perbulan']) ? 'menu-open' : '' ?>">
                  <a href="#" class="nav-link <?= activeLink(['grafik_pendapatan_perhari', 'grafik_pendapatan_perminggu', 'grafik_pendapatan_perbulan']) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-file-pdf"></i>
                    Grafik Pendapatan
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="grafik_pendapatan_perhari.php" class="nav-link <?= activeLink('grafik_pendapatan_perhari') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Perhari
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="grafik_pendapatan_perminggu.php" class="nav-link <?= activeLink('grafik_pendapatan_perminggu') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Perminggu
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="grafik_pendapatan_perbulan.php" class="nav-link <?= activeLink('grafik_pendapatan_perbulan') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Perbulan
                        </p>
                      </a>
                    </li>
                  </ul>
                </li>
            <?php break;
            } ?>

            <?php switch ($user['hak_akses']) {
              case "MASTER":
              case "ADMIN_GUDANG": ?>
                <li class="nav-item has-treeview <?= activeLink(['lap_stok_barang', 'lap_barang_masuk', 'lap_mutasi_barang', 'lap_kondisi_barang', 'lap_reture_barang', 'lap_arsip_barang']) ? 'menu-open' : '' ?>">
                  <a href="#" class="nav-link <?= activeLink(['lap_stok_barang', 'lap_barang_masuk', 'lap_mutasi_barang', 'lap_kondisi_barang', 'lap_reture_barang', 'lap_arsip_barang']) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-file-pdf"></i>
                    Lap. Admin Gudang
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="lap_stok_barang.php" class="nav-link <?= activeLink('lap_stok_barang') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Stok Barang
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="lap_barang_masuk.php" class="nav-link <?= activeLink('lap_barang_masuk') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Barang Masuk
                        </p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="lap_mutasi_barang.php" class="nav-link <?= activeLink('lap_mutasi_barang') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Mutasi Barang
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="lap_kondisi_barang.php" class="nav-link <?= activeLink('lap_kondisi_barang') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Kondisi Barang
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="lap_reture_barang.php" class="nav-link <?= activeLink('lap_reture_barang') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Reture Barang
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="lap_arsip_barang.php" class="nav-link <?= activeLink('lap_arsip_barang') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Arsip Barang
                        </p>
                      </a>
                    </li>
                  </ul>
                </li>
            <?php break;
            } ?>
            <?php switch ($user['hak_akses']) {
              case "MASTER":
              case "ADMIN_STOK": ?>
                <li class="nav-item has-treeview <?= activeLink(['lap_cek_stok']) ? 'menu-open' : '' ?>">
                  <a href="#" class="nav-link <?= activeLink(['lap_cek_stok']) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-file-pdf"></i>
                    Lap. Admin Stok
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="lap_cek_stok.php" class="nav-link <?= activeLink('lap_cek_stok') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                          Pengecekan Stok Barang
                        </p>
                      </a>
                    </li>
                  </ul>
                </li>
            <?php break;
            } ?>


            <li class="nav-header">KELUAR APLIKASI</li>
            <li class="nav-item">
              <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt nav-icon"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid pt-3">