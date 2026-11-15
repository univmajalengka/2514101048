-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Nov 2026 pada 11.19
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_diskon`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `total_belanja` bigint(20) NOT NULL,
  `diskon` decimal(5,2) NOT NULL,
  `nominal_diskon` bigint(20) NOT NULL,
  `total_bayar` bigint(20) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `total_belanja`, `diskon`, `nominal_diskon`, `total_bayar`, `tanggal`) VALUES
(1, 160000, 10.00, 16000, 144000, '2026-11-13 11:41:54'),
(2, 200000, 10.00, 20000, 180000, '2026-11-13 12:17:32'),
(3, 600000, 10.00, 60000, 540000, '2026-11-13 12:17:53'),
(4, 50000, 5.00, 2500, 47500, '2026-11-13 12:19:24'),
(5, 99900, 5.00, 4995, 94905, '2026-11-13 12:22:27'),
(8, 120000, 10.00, 12000, 108000, '2026-11-13 12:48:01'),
(9, 100000, 10.00, 10000, 90000, '2026-11-13 20:29:45'),
(10, 130000, 10.00, 13000, 117000, '2026-11-13 20:48:35');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
