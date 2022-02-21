-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2021 at 11:41 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.0.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gudang_subur`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(6) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` text,
  `nama` varchar(100) DEFAULT NULL,
  `kontak` char(13) DEFAULT NULL,
  `foto` text,
  `alamat` text,
  `hak_akses` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama`, `kontak`, `foto`, `alamat`, `hak_akses`) VALUES
(1, 'admin_gudang', 'admin_gudang', 'Admin Gudang', '082149259289', 'assets/img/profiles/admin.png', 'Jl. Admin', 'ADMIN_GUDANG'),
(2, 'master', 'master', 'Master', '082149259820', 'assets/img/profiles/default.png', 'Jl. Master', 'MASTER'),
(3, 'admin_stok', 'admin_stok', 'Admin Stok', '082149259829', 'assets/img/profiles/admin_stok.png', 'Dimana ada kemauan, disitu ada jalan', 'ADMIN_STOK');

-- --------------------------------------------------------

--
-- Table structure for table `arsip_barang`
--

CREATE TABLE `arsip_barang` (
  `id` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `tgl_arsip` date DEFAULT NULL,
  `kondisi` varchar(50) DEFAULT NULL,
  `jumlah` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `arsip_barang`
--

INSERT INTO `arsip_barang` (`id`, `id_barang`, `tgl_arsip`, `kondisi`, `jumlah`, `keterangan`) VALUES
(2, 14, '2021-07-29', 'Rusak', 10, 'Dibuang');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(6) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `stok` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `id_kategori`, `nama_barang`, `harga`, `stok`, `keterangan`) VALUES
(14, 2, 'Tepung Terigu', 5500, 160, '');

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` int(6) NOT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `id_barang` int(6) DEFAULT NULL,
  `id_supplier` int(6) DEFAULT NULL,
  `jumlah` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`id`, `tgl_masuk`, `id_barang`, `id_supplier`, `jumlah`, `keterangan`) VALUES
(20, '2021-07-25', 14, 7, 200, '');

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `id` int(6) NOT NULL,
  `nama_cabang` varchar(100) DEFAULT NULL,
  `kontak` char(13) DEFAULT NULL,
  `alamat` text,
  `warna` char(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`id`, `nama_cabang`, `kontak`, `alamat`, `warna`) VALUES
(5, 'Panglima Batur', '0511123432', 'Jl Panglima Batur', '#FF0000'),
(6, 'Ayani KM. 29', '0511764987', 'Jl. Ayani KM 29', '#1C04FF');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `jumlah` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `satuan`, `jumlah`, `keterangan`) VALUES
(2, 'Tepung ', 'kg', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `kondisi`
--

CREATE TABLE `kondisi` (
  `id` int(6) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kondisi`
--

INSERT INTO `kondisi` (`id`, `nama`, `keterangan`) VALUES
(11, 'Baik', 'Sehat'),
(12, 'Rusak', 'Tidak terselamatkan'),
(13, 'Expired', 'Telalu lama sendiri');

-- --------------------------------------------------------

--
-- Table structure for table `kondisi_barang`
--

CREATE TABLE `kondisi_barang` (
  `id` int(6) NOT NULL,
  `id_barang` int(6) DEFAULT NULL,
  `id_kondisi` int(6) DEFAULT NULL,
  `jumlah` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kondisi_barang`
--

INSERT INTO `kondisi_barang` (`id`, `id_barang`, `id_kondisi`, `jumlah`) VALUES
(12, 14, 12, 5);

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_barang`
--

CREATE TABLE `mutasi_barang` (
  `id` int(6) NOT NULL,
  `id_barang` int(6) DEFAULT NULL,
  `id_cabang` int(6) DEFAULT NULL,
  `tgl_mutasi` date DEFAULT NULL,
  `jumlah` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mutasi_barang`
--

INSERT INTO `mutasi_barang` (`id`, `id_barang`, `id_cabang`, `tgl_mutasi`, `jumlah`, `keterangan`) VALUES
(3, 14, 5, '2021-07-26', 20, '');

-- --------------------------------------------------------

--
-- Table structure for table `pendapatan_perbulan`
--

CREATE TABLE `pendapatan_perbulan` (
  `id` int(11) NOT NULL,
  `id_cabang` int(11) DEFAULT NULL,
  `tahun` char(4) DEFAULT NULL,
  `no_minggu` int(11) DEFAULT NULL,
  `tgl_update` date DEFAULT NULL,
  `rerata` double DEFAULT NULL,
  `jumlah_hari` int(1) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pendapatan_perbulan`
--

INSERT INTO `pendapatan_perbulan` (`id`, `id_cabang`, `tahun`, `no_minggu`, `tgl_update`, `rerata`, `jumlah_hari`, `keterangan`) VALUES
(9, 5, '2021', 30, '2021-07-26', 14000000, 1, 'Input pertama'),
(10, 6, '2021', 30, '2021-07-26', 23000000, 1, 'Input pertama'),
(11, 5, '2021', 29, '2021-07-23', 25000000, 1, 'Input pertama'),
(12, 6, '2021', 29, '2021-07-23', 24000000, 1, 'Input pertama');

-- --------------------------------------------------------

--
-- Table structure for table `pendapatan_perhari`
--

CREATE TABLE `pendapatan_perhari` (
  `id` int(11) NOT NULL,
  `id_cabang` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pendapatan_perhari`
--

INSERT INTO `pendapatan_perhari` (`id`, `id_cabang`, `tanggal`, `jumlah`, `keterangan`) VALUES
(14, 5, '2021-07-26', 21000000, ''),
(15, 6, '2021-07-26', 23000000, ''),
(16, 5, '2021-07-23', 25000000, ''),
(17, 6, '2021-07-23', 24000000, '');

-- --------------------------------------------------------

--
-- Table structure for table `retail`
--

CREATE TABLE `retail` (
  `id` int(6) NOT NULL,
  `nama_retail` varchar(50) DEFAULT NULL,
  `kontak` char(13) NOT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reture_barang`
--

CREATE TABLE `reture_barang` (
  `id` int(6) NOT NULL,
  `id_barang` int(6) DEFAULT NULL,
  `id_supplier` int(6) DEFAULT NULL,
  `id_kondisi` int(6) DEFAULT NULL,
  `tgl_reture` date DEFAULT NULL,
  `jumlah` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reture_barang`
--

INSERT INTO `reture_barang` (`id`, `id_barang`, `id_supplier`, `id_kondisi`, `tgl_reture`, `jumlah`, `keterangan`) VALUES
(5, 14, 7, 12, '2021-07-26', 10, '');

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id` int(11) NOT NULL,
  `kode` varchar(10) DEFAULT NULL,
  `nama_satuan` varchar(50) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id`, `kode`, `nama_satuan`, `keterangan`) VALUES
(1, 'kg', 'Kilo Gram', 'Ya itu Jangan Tanya Saya'),
(2, 'btl', 'Botol', 'dalam bentuk botol kira kira 60 ml'),
(3, 'pcs', 'Picies', 'Picies atau Pieces ?'),
(5, 'lt', 'Liter', 'Satu Liter berarti 100 Mili Liter ?');

-- --------------------------------------------------------

--
-- Table structure for table `stok_barang`
--

CREATE TABLE `stok_barang` (
  `id` int(6) NOT NULL,
  `id_barang` int(6) DEFAULT NULL,
  `stok_fisik` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stok_barang`
--

INSERT INTO `stok_barang` (`id`, `id_barang`, `stok_fisik`, `keterangan`) VALUES
(6, 14, 160, '');

-- --------------------------------------------------------

--
-- Table structure for table `stok_cabang`
--

CREATE TABLE `stok_cabang` (
  `id` int(11) NOT NULL,
  `id_cabang` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `stok` int(3) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stok_cabang`
--

INSERT INTO `stok_cabang` (`id`, `id_cabang`, `id_barang`, `stok`, `keterangan`) VALUES
(2, 5, 14, 20, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(6) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kontak` char(13) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `kontak`, `alamat`) VALUES
(7, 'PT. Bintang Abadi', '082149259827', 'Jl. Panjaitan no. 21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arsip_barang`
--
ALTER TABLE `arsip_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kondisi`
--
ALTER TABLE `kondisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kondisi_barang`
--
ALTER TABLE `kondisi_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mutasi_barang`
--
ALTER TABLE `mutasi_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendapatan_perbulan`
--
ALTER TABLE `pendapatan_perbulan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendapatan_perhari`
--
ALTER TABLE `pendapatan_perhari`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `retail`
--
ALTER TABLE `retail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reture_barang`
--
ALTER TABLE `reture_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_cabang`
--
ALTER TABLE `stok_cabang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `arsip_barang`
--
ALTER TABLE `arsip_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cabang`
--
ALTER TABLE `cabang`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kondisi`
--
ALTER TABLE `kondisi`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kondisi_barang`
--
ALTER TABLE `kondisi_barang`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mutasi_barang`
--
ALTER TABLE `mutasi_barang`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pendapatan_perbulan`
--
ALTER TABLE `pendapatan_perbulan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pendapatan_perhari`
--
ALTER TABLE `pendapatan_perhari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `retail`
--
ALTER TABLE `retail`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reture_barang`
--
ALTER TABLE `reture_barang`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stok_barang`
--
ALTER TABLE `stok_barang`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stok_cabang`
--
ALTER TABLE `stok_cabang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
