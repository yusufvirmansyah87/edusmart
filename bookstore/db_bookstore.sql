-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Bulan Mei 2025 pada 09.06
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
-- Database: `db_bookstore`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_buku`
--

CREATE TABLE `tb_buku` (
  `id_buku` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `judul_buku` varchar(100) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `id_penerbit` int(11) DEFAULT NULL,
  `tahun` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `foto` text NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_buku`
--

INSERT INTO `tb_buku` (`id_buku`, `id_kategori`, `judul_buku`, `pengarang`, `id_penerbit`, `tahun`, `harga`, `foto`, `stok`) VALUES
(1, 2, 'DASAR-DASAR PENGEMBANGAN PERANGKAT LUNAK & GIM Fase E Vol.2', 'Okta Purnawirawan | Nuning Minarsih | Bayu Andoro', 1, 2023, 88000, 'C0050050160.png', 1000),
(3, 2, 'Rekayasa Perangkat Lunak SMK/MAK Kelas XI (K-Merdeka)', 'Pungki Indra Permana', 5, 2023, 74000, '13.-Rekayasa-Perangkat-Lunak-SMKMAK-Kelas-11-K-Merdeka-scaled.jpg', 1000),
(4, 3, 'Bahasa dan Sastra Indonesia untuk SMA/MA/SMK/MAK Kelas 10', 'Tim MGMP Bahasa dan Sastra Nasional', 2, 2023, 154000, 'bhs_dan_sastra_indonesia..jpg', 1000),
(5, 4, 'PENDIDIKAN AGAMA ISLAM dan BUDI PEKERTI 1 untuk SMK/MAK Kelas X (K-MERDEKA)', 'H. A. Sholeh Dimyathi | Munawir AM | Hj. Iim Halim', 1, 2022, 99000, 'C0052970340.png', 1000),
(6, 5, 'IPA untuk SMK/MAK Kelas X', 'Dewi Septiana Budiyati, S.Pt. dan Puji Widayati, S.Si.', 5, 2020, 79000, 'BLK_IUSKX2020950227.jpg', 1000),
(7, 6, 'Seni Rupa untuk SMA/SMK Kelas 10 Kurikulum Merdeka', '---', 4, 2023, 78000, 'brwicir26tamwrrbdpyfue.jpg', 1000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_detail_pesanan`
--

CREATE TABLE `tb_detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nama_kategori`) VALUES
(2, 'Teknologi Informasi dan Komunikasi'),
(3, 'Bahasa dan Satra'),
(4, 'Pendidikan Agama Islam dan Budi Pekerti'),
(5, 'Ilmu Pengetahuan Alam'),
(6, 'Seni Budaya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_keranjang`
--

CREATE TABLE `tb_keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penerbit`
--

CREATE TABLE `tb_penerbit` (
  `id_penerbit` int(11) NOT NULL,
  `nama_penerbit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_penerbit`
--

INSERT INTO `tb_penerbit` (`id_penerbit`, `nama_penerbit`) VALUES
(1, 'Erlangga'),
(2, 'Bumi Aksara'),
(3, 'Grafindo'),
(4, 'Yudhistira'),
(5, 'Gramedia Edukasi'),
(7, 'Andi Publisher');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `status` enum('pending','diproses','dikirim','selesai') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `nama_user` varchar(100) DEFAULT NULL,
  `level_akses` varchar(100) DEFAULT NULL,
  `no_telpon` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `nama_user`, `level_akses`, `no_telpon`, `alamat`) VALUES
(1, 'yusufvirmansyah', '$2y$10$6QNXPCL2LMTK9Lbuui8nAe4XFvQv67l3oagh1RtxbI5/r8yv1wDOG', 'Yusuf Virmansyah', 'Customer', '6285210720275', 'Jalan B Lagoa Terusan Gang 2 C @ No. 4 RT. 010/001 Kel. Lagoa Kec. Koja Jakarta Utara 14270'),
(2, 'admin', '$2y$10$eew5tSiFOZfzodz1BKXS/.eGUvi0sXjCRII6i1imQQUO32QVcG2Iu', 'Admin BookStore', 'Admin', '6289637087638', 'Official'),
(3, 'lutfisakhivirmansyah', '$2y$10$wEd/JRQkWpDA5Bd5DcsSa.VswSbD0C7OTebYudJMT0Y31gzg8fUhO', 'Lutfi Sakhi Virmansyah', 'Customer', '', 'Jalan B Lagoa Terusan Gang 2 C2 No. 4 RT. 010/001 Kel. Lagoa Kec. Koja Jakarta Utara 14270');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indeks untuk tabel `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indeks untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  ADD PRIMARY KEY (`id_keranjang`);

--
-- Indeks untuk tabel `tb_penerbit`
--
ALTER TABLE `tb_penerbit`
  ADD PRIMARY KEY (`id_penerbit`);

--
-- Indeks untuk tabel `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_buku`
--
ALTER TABLE `tb_buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_penerbit`
--
ALTER TABLE `tb_penerbit`
  MODIFY `id_penerbit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
