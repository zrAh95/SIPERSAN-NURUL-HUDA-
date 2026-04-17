-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 12 Jul 2025 pada 13.48
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sipersan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kamar`
--

CREATE TABLE `tb_kamar` (
  `id_kamar` int(11) NOT NULL,
  `kamar` varchar(100) NOT NULL,
  `tingkat` enum('MTS','MA','SMK') NOT NULL,
  `id_walikamar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kamar`
--

INSERT INTO `tb_kamar` (`id_kamar`, `kamar`, `tingkat`, `id_walikamar`) VALUES
(3, 'Bilal Bin Rabah', 'MTS', 112),
(4, 'Umar Bin Khatab', 'MA', 1),
(5, 'Utsman Bin Affan', 'SMK', 2),
(6, 'Ali Bin Abi Thalib', 'MTS', 116),
(7, 'Abu Bakar Ash-Shiddiq', 'MA', 117),
(8, 'Sunan Gunung Jati', 'SMK', 118);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pengurus`
--

CREATE TABLE `tb_pengurus` (
  `id_pengurus` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pengurus`
--

INSERT INTO `tb_pengurus` (`id_pengurus`, `nama_pengguna`, `no_telp`, `password`, `last_login`, `foto`) VALUES
(5, 'Achmad choiri rojaki', '082116771829', '$2y$10$XLvKPIMoBQStFwK9RTkLTuf0SeD2eJzOfIlDMzoub0gkKzGTvk1MW', '2025-06-21 05:59:51', '1750085751_images.jpeg'),
(8, 'Superadmin1', '085157670611', '$2y$10$fkcyJ8xvcyHQcicaPO45L.rYHhLm9GbSxZaDORanhsRaVc/7VTidK', '2025-07-12 13:13:30', '1750478462_rsz_1foto_asli.png'),
(9, 'Faisal Wijaya', '089525939028', '$2y$10$5fPgGgMKbCP5IsKTRkJJvuVKyxBF1L3SSPCB/Z2bY77/.uFWQLHaa', '2025-06-21 06:04:56', '1750478667_Joe_Biden_presidential_portrait.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_perizinan`
--

CREATE TABLE `tb_perizinan` (
  `id_perizinan` int(11) NOT NULL,
  `id_pengurus` int(11) DEFAULT NULL,
  `no_kartu` varchar(20) DEFAULT NULL,
  `mode` enum('MASUK','KELUAR') DEFAULT NULL,
  `tanggal_izin` date DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `waktu_kembali` datetime DEFAULT NULL,
  `status` enum('pending','disetujui') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_perizinan`
--

INSERT INTO `tb_perizinan` (`id_perizinan`, `id_pengurus`, `no_kartu`, `mode`, `tanggal_izin`, `keperluan`, `waktu_keluar`, `waktu_kembali`, `status`) VALUES
(81, NULL, 'CA3C3D05', 'MASUK', '2025-06-07', 'membeli pakaian dalam \r\n', '2025-06-11 16:11:15', '2025-06-07 12:47:00', 'disetujui'),
(82, NULL, 'CA3C3D05', 'KELUAR', '2025-06-07', 'selesai membeli pakaian dalam', NULL, '2025-06-11 16:09:20', 'disetujui'),
(83, NULL, 'EC713C05', 'KELUAR', '2025-06-22', 'membeli sabun', '2025-06-22 16:59:30', '2025-06-22 21:30:00', 'disetujui'),
(86, NULL, 'EC713C05', 'MASUK', '2025-06-22', 'Selesai membeli sabun', NULL, '2025-06-22 17:03:12', ''),
(87, NULL, '2E892303', 'KELUAR', NULL, NULL, '2025-07-09 12:04:46', NULL, 'disetujui'),
(89, NULL, 'F1713703', 'KELUAR', NULL, NULL, '2025-07-09 12:09:05', NULL, 'disetujui'),
(90, NULL, 'F1713703', 'KELUAR', NULL, NULL, '2025-07-09 12:09:18', NULL, 'disetujui'),
(91, NULL, 'F1713703', 'KELUAR', NULL, NULL, '2025-07-09 12:10:59', NULL, 'disetujui'),
(96, NULL, 'F1713703', '', NULL, NULL, NULL, NULL, 'pending'),
(97, NULL, 'F1713703', '', NULL, NULL, NULL, NULL, 'pending'),
(98, NULL, 'F1713703', '', NULL, NULL, NULL, NULL, 'pending'),
(99, NULL, 'F1713703', '', NULL, NULL, NULL, NULL, 'pending'),
(101, NULL, 'F1713703', 'KELUAR', NULL, NULL, '2025-07-09 12:10:20', NULL, 'disetujui'),
(103, NULL, 'F1713703', '', NULL, NULL, NULL, NULL, 'pending'),
(104, NULL, 'F1713703', '', NULL, NULL, NULL, NULL, 'pending'),
(105, NULL, '2E892303', '', NULL, NULL, NULL, NULL, 'pending'),
(106, NULL, 'F1713703', '', NULL, NULL, NULL, NULL, 'pending'),
(107, NULL, 'EC713C05', '', NULL, NULL, NULL, NULL, 'pending'),
(108, NULL, 'EC713C05', '', NULL, NULL, NULL, NULL, 'pending'),
(109, NULL, 'EC713C05', 'KELUAR', NULL, NULL, '2025-07-09 12:07:41', NULL, 'disetujui'),
(115, NULL, 'EC713C05', 'KELUAR', NULL, NULL, '2025-07-09 12:08:18', NULL, 'disetujui'),
(116, NULL, 'EC713C05', 'KELUAR', NULL, NULL, '2025-07-09 12:08:21', NULL, 'disetujui'),
(118, NULL, '49014905', 'KELUAR', NULL, NULL, '2025-07-09 12:07:24', NULL, 'disetujui'),
(126, NULL, 'CA3C3D05', 'KELUAR', NULL, NULL, '2025-07-09 12:07:28', NULL, 'disetujui'),
(129, NULL, '49014905', 'MASUK', '2025-07-09', 'selesai berobat', NULL, '2025-07-09 12:13:07', ''),
(130, NULL, 'F1713703', 'KELUAR', NULL, NULL, '2025-07-12 15:25:11', NULL, 'pending'),
(131, NULL, 'F1713703', 'KELUAR', NULL, NULL, '2025-07-12 18:20:49', NULL, 'pending'),
(132, NULL, 'F1713703', 'KELUAR', NULL, NULL, '2025-07-12 18:20:54', NULL, 'pending'),
(133, NULL, '2E892303', 'KELUAR', NULL, NULL, '2025-07-12 18:22:52', NULL, 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_santri`
--

CREATE TABLE `tb_santri` (
  `no_kartu` varchar(20) NOT NULL,
  `nama_santri` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tingkat_sekolah` enum('MTS','MA','SMK') DEFAULT NULL,
  `id_walikamar` int(11) DEFAULT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_santri`
--

INSERT INTO `tb_santri` (`no_kartu`, `nama_santri`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `tingkat_sekolah`, `id_walikamar`, `id_kamar`, `foto`) VALUES
('2E892303', 'Bintang Prasetya ', 'Bogor', '2008-06-04', 'Perumahan griya alam sentosa blok Dc 5/15 RT/RW 06/16 ', 'SMK', NULL, 5, '11.png'),
('49014905', 'Jajang Nurjaman', 'Jampang Kulon', '2009-05-23', 'Cikalong wetan desa mekarwangi RT/RW 04/10 No 26', 'MTS', NULL, 3, '21.png'),
('CA3C3D05', 'Muhammad Hanafi', 'Bogor', '2006-05-11', 'Perum harvest city, cluster mongolia blok DB5/16', 'MA', NULL, 4, '31.png'),
('EC713C05', 'Arfian Adi Nugroho', 'Bogor', '2008-06-04', 'Perumahan metland cileungsi blok AB8/10 RT/RW 04/10', 'SMK', NULL, 5, '22.png'),
('F1713703', 'Salman Alfarizi', 'Bekasi', '2006-07-06', 'Perumahan taman ciketing blok AA 3 No 16 ', 'MA', NULL, 7, '111.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_walikamar`
--

CREATE TABLE `tb_walikamar` (
  `id_walikamar` int(11) NOT NULL,
  `id_pengurus` int(11) DEFAULT NULL,
  `no_walikamar` varchar(50) DEFAULT NULL,
  `nama_walikamar` varchar(100) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `waktu_kirim` datetime DEFAULT NULL,
  `status_kirim` enum('terkirim','pending','gagal') DEFAULT 'pending',
  `chat_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_walikamar`
--

INSERT INTO `tb_walikamar` (`id_walikamar`, `id_pengurus`, `no_walikamar`, `nama_walikamar`, `pesan`, `waktu_kirim`, `status_kirim`, `chat_id`) VALUES
(1, NULL, '085157670611', 'Muhammad Yunus', 'Anggota kamar anda a/n Muhammad Zidan telah kembali ke kamar', '2025-05-16 22:15:16', 'terkirim', '6417837493'),
(2, NULL, '082116771829', 'Muhammad Gufron ', '', '2025-05-30 23:45:53', 'terkirim', NULL),
(112, NULL, '089514531920', 'Ahmad Maulana', NULL, NULL, 'pending', NULL),
(116, NULL, '089525939018', 'Gaizar Muhammad', NULL, NULL, 'pending', NULL),
(117, NULL, '087770277117', 'Faisal Khoirul Hakim', NULL, NULL, 'pending', NULL),
(118, NULL, '089635336560', 'Fadly Bin Sanusi', NULL, NULL, 'pending', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_kamar`
--
ALTER TABLE `tb_kamar`
  ADD PRIMARY KEY (`id_kamar`),
  ADD KEY `id_walikamar` (`id_walikamar`);

--
-- Indeks untuk tabel `tb_pengurus`
--
ALTER TABLE `tb_pengurus`
  ADD PRIMARY KEY (`id_pengurus`);

--
-- Indeks untuk tabel `tb_perizinan`
--
ALTER TABLE `tb_perizinan`
  ADD PRIMARY KEY (`id_perizinan`),
  ADD KEY `id_pengurus` (`id_pengurus`),
  ADD KEY `no_kartu` (`no_kartu`);

--
-- Indeks untuk tabel `tb_santri`
--
ALTER TABLE `tb_santri`
  ADD PRIMARY KEY (`no_kartu`),
  ADD KEY `id_walikamar` (`id_walikamar`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indeks untuk tabel `tb_walikamar`
--
ALTER TABLE `tb_walikamar`
  ADD PRIMARY KEY (`id_walikamar`),
  ADD KEY `id_pengurus` (`id_pengurus`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_kamar`
--
ALTER TABLE `tb_kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_pengurus`
--
ALTER TABLE `tb_pengurus`
  MODIFY `id_pengurus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tb_perizinan`
--
ALTER TABLE `tb_perizinan`
  MODIFY `id_perizinan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT untuk tabel `tb_walikamar`
--
ALTER TABLE `tb_walikamar`
  MODIFY `id_walikamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_kamar`
--
ALTER TABLE `tb_kamar`
  ADD CONSTRAINT `tb_kamar_ibfk_1` FOREIGN KEY (`id_walikamar`) REFERENCES `tb_walikamar` (`id_walikamar`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `tb_perizinan`
--
ALTER TABLE `tb_perizinan`
  ADD CONSTRAINT `tb_perizinan_ibfk_1` FOREIGN KEY (`id_pengurus`) REFERENCES `tb_pengurus` (`id_pengurus`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_perizinan_ibfk_2` FOREIGN KEY (`no_kartu`) REFERENCES `tb_santri` (`no_kartu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_santri`
--
ALTER TABLE `tb_santri`
  ADD CONSTRAINT `tb_santri_ibfk_1` FOREIGN KEY (`id_walikamar`) REFERENCES `tb_walikamar` (`id_walikamar`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_santri_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `tb_kamar` (`id_kamar`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `tb_walikamar`
--
ALTER TABLE `tb_walikamar`
  ADD CONSTRAINT `tb_walikamar_ibfk_1` FOREIGN KEY (`id_pengurus`) REFERENCES `tb_pengurus` (`id_pengurus`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
