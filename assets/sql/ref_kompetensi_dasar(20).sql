-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 04 Okt 2017 pada 01.04
-- Versi Server: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_erapor_ppk`
--

--
-- Dumping data untuk tabel `ref_kompetensi_dasar`
--

INSERT INTO `ref_kompetensi_dasar` (`id`, `id_kompetensi`, `aspek`, `mata_pelajaran_id`, `kelas`, `id_kompetensi_nas`, `kompetensi_dasar`, `kompetensi_dasar_alias`, `excel_id`) VALUES
(19001, '3.5', 'PK', '9711', NULL, NULL, '13.5   Menyelesaikan disain jaringan', NULL, 0),
(19002, '4.1', 'PK', '9711', NULL, NULL, '14.1   Mengidentifikasi masalah melalui gejala yang muncul', NULL, 0),
(19003, '4.2', 'PK', '9711', NULL, NULL, '14.2   Memilah masalah berdasarkan kelompoknya', NULL, 0),
(19004, '4.3', 'PK', '9711', NULL, NULL, '14.3   Melokalisasi daerah kerusakan', NULL, 0),
(19005, '4.4', 'PK', '9711', NULL, NULL, '14.4   Mengisolasi masalah', NULL, 0),
(19006, '4.5', 'PK', '9711', NULL, NULL, '14.5   Menyelesaikan masalah yang timbul', NULL, 0),
(19007, '5.1', 'PK', '9711', NULL, NULL, '15.1   Menentukan jenis-jenis keamanan jaringan', NULL, 0),
(19008, '5.2', 'PK', '9711', NULL, NULL, '15.2   Memasang firewall', NULL, 0),
(19009, '5.3', 'PK', '9711', NULL, NULL, '15.3   Mengidentifikasi pengendalian jaringan yang diperlukan ', NULL, 0),
(19010, '5.4', 'PK', '9711', NULL, NULL, '15.4   Mendesain sistem keamanan jaringan', NULL, 0),
(19011, '6.1', 'PK', '9711', NULL, NULL, '16.1   Menjelaskan langkah persiapan untuk setting ulang koneksi jaringan', NULL, 0),
(19012, '6.2', 'PK', '9711', NULL, NULL, '16.2   Melakukan perbaikan koneksi jaringan', NULL, 0),
(19013, '6.3', 'PK', '9711', NULL, NULL, '16.3   Melakukan setting ulang koneksi jaringan', NULL, 0),
(19014, '6.4', 'PK', '9711', NULL, NULL, '16.4   Memeriksa hasil perbaikan koneksi jaringan', NULL, 0),
(19015, '7.1', 'PK', '9711', NULL, NULL, '17.1   Memilih aplikasi untuk server', NULL, 0),
(19016, '7.2', 'PK', '9711', NULL, NULL, '17.2   Memilih sistem operasi untuk jaringan', NULL, 0),
(19017, '7.3', 'PK', '9711', NULL, NULL, '17.3   Memilih komponen server', NULL, 0),
(19018, '7.4', 'PK', '9711', NULL, NULL, '17.4   Menetapkan spesifikasi server', NULL, 0),
(19019, '7.5', 'PK', '9711', NULL, NULL, '17.5   Membangun dan mengkonfigurasi server', NULL, 0),
(19020, '7.6', 'PK', '9711', NULL, NULL, '17.6   Menguji server', NULL, 0),
(19021, '7.7', 'PK', '9711', NULL, NULL, '17.7   Memonitor kinerja jaringan', NULL, 0),
(19022, '8.1', 'PK', '9711', NULL, NULL, '18.1   Mengkonfirmasi kebutuhan klien dan perangkat jaringan', NULL, 0),
(19023, '8.2', 'PK', '9711', NULL, NULL, '18.2   Meninjau masalah keamanan', NULL, 0),
(19024, '8.3', 'PK', '9711', NULL, NULL, '18.3   Memasang dan mengkonfigurasi produk dan perangkat gateway', NULL, 0),
(19025, '8.4', 'PK', '9711', NULL, NULL, '18.4   Mengkonfigurasi dan menguji titik jaringan', NULL, 0),
(19026, '8.5', 'PK', '9711', NULL, NULL, '18.5   Mengimplementasi perubahan', NULL, 0),
(19027, '9.1', 'PK', '9711', NULL, NULL, '19.1   Menentukan kebutuhan sistem', NULL, 0),
(19028, '9.2', 'PK', '9711', NULL, NULL, '19.2   Menentukan prosedur recovery ', NULL, 0),
(19029, '9.3', 'PK', '9711', NULL, NULL, '19.3   Merancang arsitektur basis data', NULL, 0),
(19030, '9.4', 'PK', '9711', NULL, NULL, '19.4   Mengklasifikasikan penggunaan basis data.', NULL, 0),
(19031, '1.1', 'PK', '9711', NULL, NULL, '1.1      Mendeskripsikan keselamatan, kesehatan kerja (K3), dan hygiene sanitasi', NULL, 0),
(19032, '1.2', 'PK', '9711', NULL, NULL, '1.2      Melaksanakan prosedur K3 dan hygiene sanitasi', NULL, 0),
(19033, '1.3', 'PK', '9711', NULL, NULL, '1.3      Melaksanakan prosedur pembersihan area kerja', NULL, 0),
(19034, '1.4', 'PK', '9711', NULL, NULL, '1.4      Menerapkan konsep lingkungan hidup', NULL, 0),
(19035, '1.5', 'PK', '9711', NULL, NULL, '1.5      Menerapkan ketentuan pertolongan pertama pada kecelakaan', NULL, 0),
(19036, '2.1', 'PK', '9711', NULL, NULL, '2.1      Menjelaskan prinsip-prinsip berkomunikasi', NULL, 0),
(19037, '2.2', 'PK', '9711', NULL, NULL, '2.2      Memilih cara berkomunikasi dengan teman kerja, kolega dan pelanggan', NULL, 0),
(19038, '2.3', 'PK', '9711', NULL, NULL, '2.3      Melakukan komunikasi dan kerjasama dalam tim atau kelompok', NULL, 0),
(19039, '2.4', 'PK', '9711', NULL, NULL, '2.4      Melakukan komunikasi dalam lingkungan yang berbeda', NULL, 0),
(19040, '2.5', 'PK', '9711', NULL, NULL, '2.5      Memberikan pelayanan untuk tamu', NULL, 0),
(19041, '2.6', 'PK', '9711', NULL, NULL, '2.6      Menjaga standar penampilan personal', NULL, 0),
(19042, '3.1', 'PK', '9711', NULL, NULL, '3.1      Menunjukkan alur kerja persiapan pengolahan', NULL, 0),
(19043, '3.2', 'PK', '9711', NULL, NULL, '3.2      Mengorganisir persiapan pengolahan', NULL, 0),
(19044, '3.3', 'PK', '9711', NULL, NULL, '3.3      Melakukan persiapan dasar pengolahan makanan', NULL, 0),
(19045, '3.4', 'PK', '9711', NULL, NULL, '3.4      Menggunakan metode dasar memasak', NULL, 0),
(19046, '3.5', 'PK', '9711', NULL, NULL, '3.5      Membuat potongan sayuran', NULL, 0),
(19047, '3.6', 'PK', '9711', NULL, NULL, '3.6      Membuat garnish dan lipatan daun.', NULL, 0),
(19048, '1.1', 'PK', '9711', NULL, NULL, '1.1      Menjelaskan prinsip pengolahan makanan kontinental', NULL, 0),
(19049, '1.2', 'PK', '9711', NULL, NULL, '1.2      Mengolah stock, soup dan sauce', NULL, 0),
(19050, '1.3', 'PK', '9711', NULL, NULL, '1.3      Mengolah cold dan hot appetizer atau salad', NULL, 0),
(19051, '1.4', 'PK', '9711', NULL, NULL, '1.4      Mengolah sandwich dan hidangan dari sayuran', NULL, 0),
(19052, '1.5', 'PK', '9711', NULL, NULL, '1.5      Mengolah hidangan berbahan terigu', NULL, 0),
(19053, '1.6', 'PK', '9711', NULL, NULL, '1.6      Mengolah hidangan dari telur, unggas, daging dan seafood', NULL, 0),
(19054, '1.7', 'PK', '9711', NULL, NULL, '1.7      Menggunakan peralatan pengolahan makanan', NULL, 0),
(19055, '2.1', 'PK', '9711', NULL, NULL, '2.1      Menjelaskan prinsip pengolahan makanan Indonesia', NULL, 0),
(19056, '2.2', 'PK', '9711', NULL, NULL, '2.2      Mengolah salad (gado-gado, urap, rujak)', NULL, 0),
(19057, '2.3', 'PK', '9711', NULL, NULL, '2.3      Mengolah sup dan soto', NULL, 0),
(19058, '2.4', 'PK', '9711', NULL, NULL, '2.4      Mengolah hidangan nasi dan mie', NULL, 0),
(19059, '2.5', 'PK', '9711', NULL, NULL, '2.5      Mengolah hidangan sate atau jenis makanan yang dipanggang', NULL, 0),
(19060, '2.6', 'PK', '9711', NULL, NULL, '2.6      Mengolah hidangan Indonesia dari unggas, daging dan seafood', NULL, 0),
(19061, '2.7', 'PK', '9711', NULL, NULL, '2.7      Mengoperasikan alat pengolahan makanan', NULL, 0),
(19062, '3.1', 'PK', '9711', NULL, NULL, '3.1      Menjelaskan ruang lingkup pelayanan makanan dan minuman', NULL, 0),
(19063, '3.2', 'PK', '9711', NULL, NULL, '3.2      Mengoperasikan peralatan layanan makanan dan minuman', NULL, 0),
(19064, '3.3', 'PK', '9711', NULL, NULL, '3.3      Menyediakan layanan makanan dan minuman di restoran', NULL, 0),
(19065, '3.4', 'PK', '9711', NULL, NULL, '3.4      Menyediakan room service', NULL, 0),
(19066, '3.5', 'PK', '9711', NULL, NULL, '3.5      Membuat minuman non alkohol', NULL, 0),
(19067, '4.1', 'PK', '9711', NULL, NULL, '4.1      Menjelaskan aturan makan atau diet', NULL, 0),
(19068, '4.2', 'PK', '9711', NULL, NULL, '4.2      Mengidentifikasi kebutuhan gizi', NULL, 0),
(19069, '4.3', 'PK', '9711', NULL, NULL, '4.3      Membuat rencana menu sesuai kebutuhan gizi', NULL, 0),
(19070, '4.4', 'PK', '9711', NULL, NULL, '4.4      Menghitung kandungan gizi bahan makanan', NULL, 0),
(19071, '4.5', 'PK', '9711', NULL, NULL, '4.5      Mengevaluasi menu dan makanan yang diolah', NULL, 0),
(19072, '5.1', 'PK', '9711', NULL, NULL, '5.1      Menjelaskan jenis – jenis kesempatan khusus', NULL, 0),
(19073, '5.2', 'PK', '9711', NULL, NULL, '5.2      Merencanakan menu kesempatan khusus', NULL, 0),
(19074, '5.3', 'PK', '9711', NULL, NULL, '5.3      Mengoperasikan peralatan pengolahan makanan', NULL, 0),
(19075, '5.4', 'PK', '9711', NULL, NULL, '5.4      Melakukan pengolahan makanan sesuai menu', NULL, 0),
(19076, '5.5', 'PK', '9711', NULL, NULL, '5.5      Menyajikan makanan menu khusus', NULL, 0),
(19077, '6.1', 'PK', '9711', NULL, NULL, '6.1      Menjelaskan sistem usaha jasa boga ', NULL, 0),
(19078, '6.2', 'PK', '9711', NULL, NULL, '6.2      Merencanakan usaha jasa boga berdasarkan menu', NULL, 0),
(19079, '6.3', 'PK', '9711', NULL, NULL, '6.3      Menghitung kalkulasi harga', NULL, 0),
(19080, '6.4', 'PK', '9711', NULL, NULL, '6.4      Menyiapkan makanan untuk buffee', NULL, 0),
(19081, '6.5', 'PK', '9711', NULL, NULL, '6.5      Mengorganisir operasi makanan dalam jumlah besar', NULL, 0),
(19082, '6.6', 'PK', '9711', NULL, NULL, '6.6      Menyediakan penghubung antara dapur dan area pelayanan.', NULL, 0),
(19083, '3.1', 'P', '1002', 10, NULL, '3.1.        Mengidentifikasi jejaring sosial pendidikan', NULL, 0),
(19084, '3.2', 'P', '1002', 10, NULL, '3.2.        Mengidentifikasi materi digital', NULL, 0),
(19085, '3.3', 'P', '1002', 10, NULL, '3.3.        Mengidentifikasi  persyaratan hardware', NULL, 0),
(19086, '3.4', 'P', '1002', 10, NULL, '3.4.        Mengidentifikasi jenis aplikasi untuk pembuatan materi bentuk digital', NULL, 0),
(19087, '3.5', 'P', '1002', 10, NULL, '3.5.        Menjelaskan interaksi online', NULL, 0),
(19088, '3.6', 'P', '1002', 10, NULL, '3.6.        Menjelaskan komunikasi online', NULL, 0),
(19089, '3.7', 'P', '1002', 10, NULL, '3.7.        Menjelaskan jenis layanan aplikasi komunikasi online', NULL, 0),
(19090, '3.8', 'P', '1002', 10, NULL, '3.8.        Menjelaskan persyaratan penggunaan layanan aplikasi ', NULL, 0),
(19091, '3.9', 'P', '1002', 10, NULL, '3.9.        Mengidentifikasi jenis materi audio visual', NULL, 0),
(19092, '3.10', 'P', '1002', 10, NULL, '3.10.    Mengidentifikasi Jenis aplikasi pembuat materi bentuk audio visual', NULL, 0),
(19093, '3.11', 'P', '1002', 10, NULL, '3.11.    Menjelaskan persyaratan kebutuhan hardware', NULL, 0),
(19094, '3.12', 'P', '1002', 10, NULL, '3.12.    Menjelaskan konsep simulasi visual', NULL, 0),
(19095, '3.14', 'P', '1002', 10, NULL, '3.14.    Mengidentifikasi jenis simulasi visual', NULL, 0),
(19096, '4.1', 'K', '1002', 10, NULL, '4.1.        Melakukan Pendaftaran ', NULL, 0),
(19097, '4.2', 'K', '1002', 10, NULL, '4.2.        Memanfaatkan Fitur', NULL, 0),
(19098, '4.3', 'K', '1002', 10, NULL, '4.3.        Melaksanakan ujian online bersama', NULL, 0),
(19099, '4.4', 'K', '1002', 10, NULL, '4.4.        Memformat materi digital', NULL, 0),
(19100, '4.5', 'K', '1002', 10, NULL, '4.5.        Menggunakan aplikasi untuk membuat materi digital', NULL, 0),
(19101, '4.6', 'K', '1002', 10, NULL, '4.6.        Membuat materi dalam bentuk digital', NULL, 0),
(19102, '4.7', 'K', '1002', 10, NULL, '4.7.        Memanfaatkan fitur layanan komunikasi online', NULL, 0),
(19103, '4.8', 'K', '1002', 10, NULL, '4.8.        Melakukan interaksi dan komunikasi secara online', NULL, 0),
(19104, '4.9', 'K', '1002', 10, NULL, '4.9.        Menggunakan aplikasi editing video', NULL, 0),
(19105, '4.10', 'K', '1002', 10, NULL, '4.10. Melakukan proses render menjadi bentuk video', NULL, 0),
(19106, '4.11', 'K', '1002', 10, NULL, '4.11.    Membuat simulasi visual', NULL, 0),
(19107, '4.12', 'K', '1002', 10, NULL, '4.12.    Mempublikasi hasil karya simulasi visual', NULL, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
