-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Agu 2021 pada 05.25
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.3

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
-- Struktur dari tabel `arsip_stok`
--

CREATE TABLE `arsip_stok` (
  `id` int(11) NOT NULL,
  `tgl_arsip` date DEFAULT NULL,
  `no_arsip` int(3) DEFAULT NULL,
  `nama_barang` varchar(50) DEFAULT NULL,
  `stok` int(3) DEFAULT NULL,
  `stok_fisik` int(3) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `arsip_stok`
--

INSERT INTO `arsip_stok` (`id`, `tgl_arsip`, `no_arsip`, `nama_barang`, `stok`, `stok_fisik`, `satuan`, `keterangan`, `user`) VALUES
(3, '2021-08-04', 1, 'Tepung Terigu', 160, 160, 'kg', 'test', 'master'),
(4, '2021-08-04', 1, 'Tepung Beras', 40, 25, 'kg', 'test', 'master'),
(5, '2021-08-04', 2, 'Tepung Terigu', 160, 0, 'kg', '', 'master'),
(6, '2021-08-04', 2, 'Tepung Beras', 40, 0, 'kg', '', 'master');

-- --------------------------------------------------------

--
-- Struktur dari tabel `meta_arsip_stok`
--

CREATE TABLE `meta_arsip_stok` (
  `id` int(11) NOT NULL,
  `tgl_arsip` date DEFAULT NULL,
  `no_arsip` int(3) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `meta_arsip_stok`
--

INSERT INTO `meta_arsip_stok` (`id`, `tgl_arsip`, `no_arsip`, `user`) VALUES
(1, '2021-08-04', 1, 'master'),
(2, '2021-08-04', 2, 'master');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `arsip_stok`
--
ALTER TABLE `arsip_stok`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `meta_arsip_stok`
--
ALTER TABLE `meta_arsip_stok`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `arsip_stok`
--
ALTER TABLE `arsip_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `meta_arsip_stok`
--
ALTER TABLE `meta_arsip_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
