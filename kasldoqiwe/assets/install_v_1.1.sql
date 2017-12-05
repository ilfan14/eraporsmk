-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2016 at 08:23 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_erapor_temp`
--

-- --------------------------------------------------------

--
-- Table structure for table `absens`
--

CREATE TABLE `absens` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `sakit` int(11) NOT NULL,
  `izin` int(11) NOT NULL,
  `alpa` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ajarans`
--

CREATE TABLE `ajarans` (
  `id` int(11) UNSIGNED NOT NULL,
  `tahun` varchar(255) NOT NULL,
  `smt` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `anggota_rombels`
--

CREATE TABLE `anggota_rombels` (
  `id` int(11) NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `catatan_walis`
--

CREATE TABLE `catatan_walis` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `uraian_deskripsi` longtext CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `datakurikulums`
--

CREATE TABLE `datakurikulums` (
  `id` int(11) UNSIGNED NOT NULL,
  `kurikulum_id` int(11) NOT NULL,
  `nama_kurikulum` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `data_gurus`
--

CREATE TABLE `data_gurus` (
  `id` int(11) NOT NULL,
  `data_sekolah_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nuptk` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `status_kepegawaian` varchar(255) DEFAULT NULL,
  `jenis_ptk` varchar(255) DEFAULT NULL,
  `agama` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `rt` varchar(255) DEFAULT NULL,
  `rw` varchar(255) DEFAULT NULL,
  `desa_kelurahan` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `password` varchar(80) DEFAULT NULL,
  `petugas` varchar(50) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_mapels`
--

CREATE TABLE `data_mapels` (
  `id` int(11) NOT NULL,
  `id_mapel` varchar(255) NOT NULL,
  `id_mapel_nas` varchar(255) DEFAULT NULL,
  `nama_mapel` varchar(255) NOT NULL,
  `k1` int(11) DEFAULT '0',
  `k2` int(11) DEFAULT '0',
  `k3` int(11) DEFAULT '0',
  `kur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_portofolios`
--

CREATE TABLE `data_portofolios` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `id_mapel` varchar(255) NOT NULL,
  `penilaian_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nama_portofolio` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `data_rombels`
--

CREATE TABLE `data_rombels` (
  `id` int(11) NOT NULL,
  `data_sekolah_id` varchar(255) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tingkat` int(11) NOT NULL,
  `kurikulum_id` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `petugas` varchar(255) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_sekolahs`
--

CREATE TABLE `data_sekolahs` (
  `id` int(11) NOT NULL,
  `npsn` varchar(255) DEFAULT NULL,
  `nss` varchar(255) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `desa_kelurahan` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  `provinsi` varchar(255) DEFAULT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `lintang` varchar(255) DEFAULT NULL,
  `bujur` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `no_fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo_sekolah` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_sikaps`
--

CREATE TABLE `data_sikaps` (
  `id` int(11) UNSIGNED NOT NULL,
  `butir_sikap` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_siswas`
--

CREATE TABLE `data_siswas` (
  `id` int(11) NOT NULL,
  `data_sekolah_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `data_rombel_id` int(11) DEFAULT '0',
  `nama` varchar(255) DEFAULT NULL,
  `no_induk` varchar(255) DEFAULT NULL,
  `nisn` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `anak_ke` int(11) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `rt` varchar(255) DEFAULT NULL,
  `rw` varchar(255) DEFAULT NULL,
  `desa_kelurahan` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `sekolah_asal` varchar(255) DEFAULT NULL,
  `diterima_kelas` varchar(255) DEFAULT NULL,
  `diterima` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nama_ayah` varchar(255) DEFAULT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `kerja_ayah` varchar(255) DEFAULT NULL,
  `kerja_ibu` varchar(255) DEFAULT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `alamat_wali` varchar(255) DEFAULT NULL,
  `telp_wali` varchar(255) DEFAULT NULL,
  `kerja_wali` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `password` varchar(80) DEFAULT NULL,
  `petugas` varchar(50) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deskripsis`
--

CREATE TABLE `deskripsis` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `mapel_id` varchar(255) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `deskripsi_pengetahuan` text NOT NULL,
  `deskripsi_keterampilan` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_sikaps`
--

CREATE TABLE `deskripsi_sikaps` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `uraian_deskripsi` longtext CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ekskuls`
--

CREATE TABLE `ekskuls` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nama_ekskul` varchar(255) NOT NULL,
  `nama_ketua` varchar(255) NOT NULL,
  `nomor_kontak` varchar(255) NOT NULL,
  `alamat_ekskul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'tu', 'Tata Usaha'),
(3, 'guru', 'Guru'),
(4, 'siswa', 'Siswa'),
(5, 'user', 'General User'),
(6, 'waka', 'Waka Kurikulum');

-- --------------------------------------------------------

--
-- Table structure for table `kds`
--

CREATE TABLE `kds` (
  `id` int(11) NOT NULL,
  `id_kompetensi` varchar(255) NOT NULL,
  `aspek` varchar(255) NOT NULL,
  `id_mapel` varchar(255) NOT NULL,
  `kelas` int(11) DEFAULT '0',
  `id_kompetensi_nas` varchar(255) DEFAULT NULL,
  `kompetensi_dasar` longtext NOT NULL,
  `kompetensi_dasar_alias` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `keahlians`
--

CREATE TABLE `keahlians` (
  `id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  `kurikulum_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kompetensis`
--

CREATE TABLE `kompetensis` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kurikulums`
--

CREATE TABLE `kurikulums` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `id_mapel` varchar(255) NOT NULL,
  `nama_mapel_alias` varchar(255) DEFAULT NULL,
  `guru_id` int(11) NOT NULL,
  `keahlian_id` varchar(100) NOT NULL,
  `kkm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kurikulum_aliases`
--

CREATE TABLE `kurikulum_aliases` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `id_mapel` varchar(255) CHARACTER SET utf8 NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nama_kur` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `matpel_komps`
--

CREATE TABLE `matpel_komps` (
  `id` int(11) NOT NULL,
  `kurikulum_id` varchar(255) NOT NULL,
  `id_mapel` varchar(255) NOT NULL,
  `kelas_X` int(11) DEFAULT '0',
  `kelas_XI` int(11) DEFAULT '0',
  `kelas_XII` int(11) NOT NULL DEFAULT '0',
  `kelas_XIII` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `metodes`
--

CREATE TABLE `metodes` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `kompetensi_id` int(2) NOT NULL,
  `nama_metode` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nilaiakhirs`
--

CREATE TABLE `nilaiakhirs` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `mapel_id` varchar(255) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `rencana_penilaian_id` int(11) NOT NULL,
  `rerata_nilai` varchar(255) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nilais`
--

CREATE TABLE `nilais` (
  `id` int(11) NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `mapel_id` varchar(255) NOT NULL,
  `data_siswa_id` int(11) NOT NULL,
  `rencana_penilaian_id` varchar(255) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `rerata` varchar(11) NOT NULL,
  `rerata_jadi` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_ekskuls`
--

CREATE TABLE `nilai_ekskuls` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `ekskul_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `deskripsi_ekskul` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prakerins`
--

CREATE TABLE `prakerins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `mitra_prakerin` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lokasi_prakerin` varchar(255) NOT NULL,
  `lama_prakerin` varchar(255) NOT NULL,
  `keterangan_prakerin` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prestasis`
--

CREATE TABLE `prestasis` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jenis_prestasi` varchar(255) NOT NULL,
  `keterangan_prestasi` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rencanas`
--

CREATE TABLE `rencanas` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `id_mapel` varchar(255) CHARACTER SET utf8 NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rencana_penilaians`
--

CREATE TABLE `rencana_penilaians` (
  `id` int(11) UNSIGNED NOT NULL,
  `rencana_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL,
  `nama_penilaian` varchar(255) NOT NULL,
  `bentuk_penilaian` int(11) NOT NULL,
  `bobot_penilaian` int(11) NOT NULL,
  `keterangan_penilaian` varchar(255) NOT NULL,
  `kd_id` int(11) NOT NULL,
  `kd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `session_id` varchar(45) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL,
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `site_title` varchar(100) NOT NULL,
  `version` varchar(100) NOT NULL,
  `periode` text NOT NULL,
  `sinkronisasi` int(1) NOT NULL DEFAULT '0',
  `rumus` int(2) NOT NULL DEFAULT '0',
  `import` int(2) NOT NULL DEFAULT '0',
  `desc` int(2) NOT NULL DEFAULT '0',
  `zona` int(2) NOT NULL DEFAULT '1',
  `kepsek` varchar(100) NOT NULL,
  `nip_kepsek` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sikaps`
--

CREATE TABLE `sikaps` (
  `id` int(11) UNSIGNED NOT NULL,
  `ajaran_id` int(11) NOT NULL,
  `rombel_id` int(11) NOT NULL,
  `mapel_id` varchar(255) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal_sikap` date NOT NULL,
  `butir_sikap` varchar(255) NOT NULL,
  `opsi_sikap` int(11) NOT NULL,
  `uraian_sikap` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `data_sekolah_id` int(11) DEFAULT NULL,
  `nisn` varchar(255) DEFAULT NULL,
  `nipd` varchar(11) DEFAULT NULL,
  `nuptk` varchar(255) DEFAULT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `data_siswa_id` int(11) NOT NULL,
  `data_guru_id` int(11) NOT NULL,
  `login_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absens`
--
ALTER TABLE `absens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ajarans`
--
ALTER TABLE `ajarans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anggota_rombels`
--
ALTER TABLE `anggota_rombels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catatan_walis`
--
ALTER TABLE `catatan_walis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datakurikulums`
--
ALTER TABLE `datakurikulums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_gurus`
--
ALTER TABLE `data_gurus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_mapels`
--
ALTER TABLE `data_mapels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_portofolios`
--
ALTER TABLE `data_portofolios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_rombels`
--
ALTER TABLE `data_rombels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_sekolahs`
--
ALTER TABLE `data_sekolahs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_sikaps`
--
ALTER TABLE `data_sikaps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_siswas`
--
ALTER TABLE `data_siswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `deskripsis`
--
ALTER TABLE `deskripsis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_sikaps`
--
ALTER TABLE `deskripsi_sikaps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ekskuls`
--
ALTER TABLE `ekskuls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kds`
--
ALTER TABLE `kds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keahlians`
--
ALTER TABLE `keahlians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kompetensis`
--
ALTER TABLE `kompetensis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kurikulums`
--
ALTER TABLE `kurikulums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kurikulum_aliases`
--
ALTER TABLE `kurikulum_aliases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matpel_komps`
--
ALTER TABLE `matpel_komps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metodes`
--
ALTER TABLE `metodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilaiakhirs`
--
ALTER TABLE `nilaiakhirs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilais`
--
ALTER TABLE `nilais`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_ekskuls`
--
ALTER TABLE `nilai_ekskuls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prakerins`
--
ALTER TABLE `prakerins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prestasis`
--
ALTER TABLE `prestasis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rencanas`
--
ALTER TABLE `rencanas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rencana_penilaians`
--
ALTER TABLE `rencana_penilaians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sikaps`
--
ALTER TABLE `sikaps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absens`
--
ALTER TABLE `absens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ajarans`
--
ALTER TABLE `ajarans`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `anggota_rombels`
--
ALTER TABLE `anggota_rombels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `catatan_walis`
--
ALTER TABLE `catatan_walis`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `datakurikulums`
--
ALTER TABLE `datakurikulums`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;
--
-- AUTO_INCREMENT for table `data_gurus`
--
ALTER TABLE `data_gurus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `data_mapels`
--
ALTER TABLE `data_mapels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=874;
--
-- AUTO_INCREMENT for table `data_portofolios`
--
ALTER TABLE `data_portofolios`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `data_rombels`
--
ALTER TABLE `data_rombels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `data_sekolahs`
--
ALTER TABLE `data_sekolahs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `data_sikaps`
--
ALTER TABLE `data_sikaps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `data_siswas`
--
ALTER TABLE `data_siswas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deskripsis`
--
ALTER TABLE `deskripsis`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deskripsi_sikaps`
--
ALTER TABLE `deskripsi_sikaps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ekskuls`
--
ALTER TABLE `ekskuls`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `kds`
--
ALTER TABLE `kds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19108;
--
-- AUTO_INCREMENT for table `keahlians`
--
ALTER TABLE `keahlians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kompetensis`
--
ALTER TABLE `kompetensis`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kurikulums`
--
ALTER TABLE `kurikulums`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kurikulum_aliases`
--
ALTER TABLE `kurikulum_aliases`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `matpel_komps`
--
ALTER TABLE `matpel_komps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5451;
--
-- AUTO_INCREMENT for table `metodes`
--
ALTER TABLE `metodes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nilaiakhirs`
--
ALTER TABLE `nilaiakhirs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nilais`
--
ALTER TABLE `nilais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nilai_ekskuls`
--
ALTER TABLE `nilai_ekskuls`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prakerins`
--
ALTER TABLE `prakerins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prestasis`
--
ALTER TABLE `prestasis`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rencanas`
--
ALTER TABLE `rencanas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rencana_penilaians`
--
ALTER TABLE `rencana_penilaians`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sikaps`
--
ALTER TABLE `sikaps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
