-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Nov 2026 pada 12.05
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `devakad1_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `nip`, `nama`, `password`) VALUES
(1, '19850101', 'Dr. Koordinator Akademik, M.Kom.', 'dosen123'),
(2, '19850102', 'Budi Dosen, M.T.', 'dosen123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `status` enum('AKTIF','NON-AKTIF') DEFAULT 'AKTIF',
  `password` varchar(50) DEFAULT '123456'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `prodi`, `semester`, `status`, `password`) VALUES
(1, '220510101', 'Aisha Muhammad', 'Teknik Informatika', 'Semester 5', 'AKTIF', '123456'),
(2, '220510102', 'Ibrahim Yakubu', 'Sistem Informasi', 'Semester 5', 'AKTIF', '123456'),
(3, '220510103', 'Budi Santoso', 'Teknik Informatika', 'Semester 3', 'AKTIF', '123456'),
(4, '5243151008', 'shinta aritonang', 'Teknik Informatika', 'Semester 5', 'AKTIF', '123456'),
(5, '524315006', 'keysa fransiska', 'Teknik Informatika', 'Semester 5', 'AKTIF', '123456'),
(6, '5244151008', 'surti', 'Teknik Informatika', 'Semester 5', 'AKTIF', '5244151008'),
(7, '11111111', 'putri', 'Teknik Informatika', '5', 'AKTIF', '11111111'),
(8, '12345678', 'wasti', 'Teknik Informatika', '5', 'AKTIF', '12345678');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(11) NOT NULL,
  `kode_mk` varchar(10) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `sks`) VALUES
(1, 'INF301', 'Manajemen Proyek TI (MPTI)', 2),
(2, 'INF302', 'Basis Data Lanjut', 2),
(3, 'INF303', 'Pemrograman Web Integratif', 3),
(4, 'INF304', 'Rekayasa Perangkat Lunak', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `matkul_id` int(11) NOT NULL,
  `tugas` float DEFAULT 0,
  `uts` float DEFAULT 0,
  `uas` float DEFAULT 0,
  `nilai_akhir` float DEFAULT 0,
  `grade` varchar(2) DEFAULT 'E',
  `status` varchar(20) DEFAULT 'MENGULANG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id`, `mahasiswa_id`, `matkul_id`, `tugas`, `uts`, `uas`, `nilai_akhir`, `grade`, `status`) VALUES
(1, 1, 1, 90, 90, 90, 90, 'A', 'LULUS'),
(2, 2, 1, 78, 70, 50, 61.6, 'D', 'MENGULANG'),
(3, 3, 1, 70, 70, 75, 72.5, 'C', 'LULUS'),
(4, 4, 1, 80, 70, 85, 79.5, 'B', 'LULUS'),
(5, 1, 2, 90, 80, 75, 79.5, 'B', 'LULUS'),
(6, 2, 2, 99, 80, 77, 82.3, 'B', 'LULUS'),
(7, 3, 2, 90, 88, 90, 89.4, 'A', 'LULUS'),
(8, 4, 2, 98, 89, 87, 89.8, 'A', 'LULUS'),
(9, 5, 2, 100, 90, 80, 87, 'A', 'LULUS');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `matkul_id` (`matkul_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`matkul_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
