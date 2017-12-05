-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 04 Okt 2017 pada 01.08
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `absen`
--

CREATE TABLE `absen` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `sakit` int(11) NOT NULL,
  `izin` int(11) NOT NULL,
  `alpa` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_rombel`
--

CREATE TABLE `anggota_rombel` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `anggota_rombel_id_dapodik` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_ppk`
--

CREATE TABLE `catatan_ppk` (
  `id` int(11) UNSIGNED NOT NULL,
  `ppk_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `ref_ppk_id` varchar(255) NOT NULL,
  `catatan` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_wali`
--

CREATE TABLE `catatan_wali` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `uraian_deskripsi` longtext CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_excel`
--

CREATE TABLE `data_excel` (
  `id` int(11) UNSIGNED NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `deskripsi_mata_pelajaran`
--

CREATE TABLE `deskripsi_mata_pelajaran` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `deskripsi_pengetahuan` text NOT NULL,
  `deskripsi_keterampilan` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `deskripsi_sikap`
--

CREATE TABLE `deskripsi_sikap` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `uraian_deskripsi` longtext CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ekstrakurikuler`
--

CREATE TABLE `ekstrakurikuler` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nama_ekskul` varchar(255) NOT NULL,
  `nama_ketua` varchar(255) NOT NULL,
  `nomor_kontak` varchar(255) NOT NULL,
  `alamat_ekskul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `groups`
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
-- Struktur dari tabel `guru_terdaftar`
--

CREATE TABLE `guru_terdaftar` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan_sp`
--

CREATE TABLE `jurusan_sp` (
  `id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  `kurikulum_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kd_nilai`
--

CREATE TABLE `kd_nilai` (
  `id` int(11) UNSIGNED NOT NULL,
  `rencana_penilaian_id` int(11) NOT NULL,
  `kd_id` int(11) NOT NULL,
  `id_kompetensi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_pelajaran_kurikulum`
--

CREATE TABLE `mata_pelajaran_kurikulum` (
  `id` int(11) UNSIGNED NOT NULL,
  `kurikulum_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `tingkat_pendidikan_id` int(11) NOT NULL,
  `a_peminatan` varchar(10) DEFAULT NULL,
  `area_kompetensi` varchar(10) DEFAULT NULL,
  `kelompok_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `rencana_penilaian_id` varchar(255) NOT NULL,
  `kompetensi_dasar_id` int(11) NOT NULL,
  `kd_nilai_id` int(11) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `rerata` varchar(11) NOT NULL,
  `rerata_jadi` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_ekstrakurikuler`
--

CREATE TABLE `nilai_ekstrakurikuler` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `ekstrakurikuler_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `deskripsi_ekskul` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_sikap`
--

CREATE TABLE `nilai_sikap` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
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
-- Struktur dari tabel `pembelajaran`
--

CREATE TABLE `pembelajaran` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `kkm` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_dapodik` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ppk`
--

CREATE TABLE `ppk` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `id_kegiatan` int(11) DEFAULT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `guru_id` int(11) NOT NULL DEFAULT '0',
  `penanggung_jawab` varchar(255) DEFAULT NULL,
  `rombongan_belajar_id` int(11) NOT NULL DEFAULT '0',
  `posisi` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prakerin`
--

CREATE TABLE `prakerin` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
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
-- Struktur dari tabel `prestasi`
--

CREATE TABLE `prestasi` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jenis_prestasi` varchar(255) NOT NULL,
  `keterangan_prestasi` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_agama`
--

CREATE TABLE `ref_agama` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_guru`
--

CREATE TABLE `ref_guru` (
  `id` int(11) NOT NULL,
  `sekolah_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nuptk` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `status_kepegawaian_id` int(11) DEFAULT NULL,
  `jenis_ptk` varchar(255) DEFAULT NULL,
  `agama_id` int(11) DEFAULT NULL,
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
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `guru_id_dapodik` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_jenis_ptk`
--

CREATE TABLE `ref_jenis_ptk` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_jurusan`
--

CREATE TABLE `ref_jurusan` (
  `id` int(11) UNSIGNED NOT NULL,
  `jurusan_id` varchar(255) NOT NULL,
  `jurusan_induk` varchar(255) NOT NULL,
  `nama_jurusan` varchar(255) CHARACTER SET utf8 NOT NULL,
  `level_bidang_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_kelompok`
--

CREATE TABLE `ref_kelompok` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_kelompok` varchar(255) NOT NULL,
  `kurikulum` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_kompetensi_dasar`
--

CREATE TABLE `ref_kompetensi_dasar` (
  `id` int(11) NOT NULL,
  `id_kompetensi` varchar(255) NOT NULL,
  `aspek` varchar(255) NOT NULL,
  `mata_pelajaran_id` varchar(255) NOT NULL,
  `kelas` int(11) DEFAULT '0',
  `id_kompetensi_nas` varchar(255) DEFAULT NULL,
  `kompetensi_dasar` longtext NOT NULL,
  `kompetensi_dasar_alias` longtext,
  `excel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_kurikulum`
--

CREATE TABLE `ref_kurikulum` (
  `id` int(11) UNSIGNED NOT NULL,
  `kurikulum_id` int(11) NOT NULL,
  `nama_kurikulum` text CHARACTER SET utf8 NOT NULL,
  `jurusan_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_mata_pelajaran`
--

CREATE TABLE `ref_mata_pelajaran` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_nasional` varchar(255) DEFAULT NULL,
  `jurusan_id` int(11) NOT NULL,
  `nama_mata_pelajaran` varchar(255) NOT NULL,
  `nama_mata_pelajaran_alias` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_ppk`
--

CREATE TABLE `ref_ppk` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_sekolah`
--

CREATE TABLE `ref_sekolah` (
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
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sekolah_id_dapodik` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_semester`
--

CREATE TABLE `ref_semester` (
  `id` int(11) UNSIGNED NOT NULL,
  `tahun` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_sikap`
--

CREATE TABLE `ref_sikap` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL DEFAULT '0',
  `butir_sikap` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_siswa`
--

CREATE TABLE `ref_siswa` (
  `id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
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
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `siswa_id_dapodik` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_status_kepegawaian`
--

CREATE TABLE `ref_status_kepegawaian` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_tingkat_pendidikan`
--

CREATE TABLE `ref_tingkat_pendidikan` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `remedial`
--

CREATE TABLE `remedial` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nilai` text NOT NULL,
  `rerata_akhir` varchar(255) NOT NULL,
  `rerata_remedial` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rencana_penilaian`
--

CREATE TABLE `rencana_penilaian` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL,
  `nama_penilaian` varchar(255) NOT NULL,
  `metode_id` int(11) NOT NULL,
  `bobot` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rombongan_belajar`
--

CREATE TABLE `rombongan_belajar` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL DEFAULT '0',
  `sekolah_id` int(11) NOT NULL,
  `jurusan_sp_id` int(255) DEFAULT NULL,
  `kurikulum_id` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tingkat` int(11) NOT NULL,
  `guru_id_dapodik` varchar(255) DEFAULT NULL,
  `rombel_id_dapodik` varchar(255) DEFAULT NULL,
  `petugas` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `session`
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
-- Struktur dari tabel `settings`
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
-- Struktur dari tabel `teknik_penilaian`
--

CREATE TABLE `teknik_penilaian` (
  `id` int(11) UNSIGNED NOT NULL,
  `kompetensi_id` int(2) NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `sekolah_id` int(11) DEFAULT NULL,
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
  `siswa_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `login_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_groups`
--

CREATE TABLE `users_groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--
CREATE TABLE `import_rencana` (
  `id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) NOT NULL,
  `kompetensi_id` int(11) NOT NULL,
  `rombongan_belajar_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `import_rencana`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `import_rencana`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
CREATE TABLE `ref_gelar` (
  `gelar_akademik_id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `posisi_gelar` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ref_gelar`
--
ALTER TABLE `ref_gelar`
  ADD PRIMARY KEY (`gelar_akademik_id`);
--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anggota_rombel`
--
ALTER TABLE `anggota_rombel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catatan_ppk`
--
ALTER TABLE `catatan_ppk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catatan_wali`
--
ALTER TABLE `catatan_wali`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_excel`
--
ALTER TABLE `data_excel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_mata_pelajaran`
--
ALTER TABLE `deskripsi_mata_pelajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_sikap`
--
ALTER TABLE `deskripsi_sikap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ekstrakurikuler`
--
ALTER TABLE `ekstrakurikuler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guru_terdaftar`
--
ALTER TABLE `guru_terdaftar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurusan_sp`
--
ALTER TABLE `jurusan_sp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kd_nilai`
--
ALTER TABLE `kd_nilai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mata_pelajaran_kurikulum`
--
ALTER TABLE `mata_pelajaran_kurikulum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_ekstrakurikuler`
--
ALTER TABLE `nilai_ekstrakurikuler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_sikap`
--
ALTER TABLE `nilai_sikap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelajaran`
--
ALTER TABLE `pembelajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppk`
--
ALTER TABLE `ppk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prakerin`
--
ALTER TABLE `prakerin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prestasi`
--
ALTER TABLE `prestasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_agama`
--
ALTER TABLE `ref_agama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_guru`
--
ALTER TABLE `ref_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_jenis_ptk`
--
ALTER TABLE `ref_jenis_ptk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_jurusan`
--
ALTER TABLE `ref_jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_kelompok`
--
ALTER TABLE `ref_kelompok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_kompetensi_dasar`
--
ALTER TABLE `ref_kompetensi_dasar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_kurikulum`
--
ALTER TABLE `ref_kurikulum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_mata_pelajaran`
--
ALTER TABLE `ref_mata_pelajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_ppk`
--
ALTER TABLE `ref_ppk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_sekolah`
--
ALTER TABLE `ref_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_semester`
--
ALTER TABLE `ref_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_sikap`
--
ALTER TABLE `ref_sikap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_siswa`
--
ALTER TABLE `ref_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ref_status_kepegawaian`
--
ALTER TABLE `ref_status_kepegawaian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_tingkat_pendidikan`
--
ALTER TABLE `ref_tingkat_pendidikan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remedial`
--
ALTER TABLE `remedial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rencana_penilaian`
--
ALTER TABLE `rencana_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rombongan_belajar`
--
ALTER TABLE `rombongan_belajar`
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
-- Indexes for table `teknik_penilaian`
--
ALTER TABLE `teknik_penilaian`
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
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `anggota_rombel`
--
ALTER TABLE `anggota_rombel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `catatan_ppk`
--
ALTER TABLE `catatan_ppk`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `catatan_wali`
--
ALTER TABLE `catatan_wali`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `data_excel`
--
ALTER TABLE `data_excel`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deskripsi_mata_pelajaran`
--
ALTER TABLE `deskripsi_mata_pelajaran`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deskripsi_sikap`
--
ALTER TABLE `deskripsi_sikap`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ekstrakurikuler`
--
ALTER TABLE `ekstrakurikuler`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `guru_terdaftar`
--
ALTER TABLE `guru_terdaftar`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jurusan_sp`
--
ALTER TABLE `jurusan_sp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_nilai`
--
ALTER TABLE `kd_nilai`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mata_pelajaran_kurikulum`
--
ALTER TABLE `mata_pelajaran_kurikulum`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nilai_ekstrakurikuler`
--
ALTER TABLE `nilai_ekstrakurikuler`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nilai_sikap`
--
ALTER TABLE `nilai_sikap`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pembelajaran`
--
ALTER TABLE `pembelajaran`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ppk`
--
ALTER TABLE `ppk`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prakerin`
--
ALTER TABLE `prakerin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prestasi`
--
ALTER TABLE `prestasi`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_agama`
--
ALTER TABLE `ref_agama`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_guru`
--
ALTER TABLE `ref_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_jenis_ptk`
--
ALTER TABLE `ref_jenis_ptk`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_jurusan`
--
ALTER TABLE `ref_jurusan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_kelompok`
--
ALTER TABLE `ref_kelompok`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_kompetensi_dasar`
--
ALTER TABLE `ref_kompetensi_dasar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_kurikulum`
--
ALTER TABLE `ref_kurikulum`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_mata_pelajaran`
--
ALTER TABLE `ref_mata_pelajaran`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_ppk`
--
ALTER TABLE `ref_ppk`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_sekolah`
--
ALTER TABLE `ref_sekolah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_semester`
--
ALTER TABLE `ref_semester`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_sikap`
--
ALTER TABLE `ref_sikap`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_siswa`
--
ALTER TABLE `ref_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_status_kepegawaian`
--
ALTER TABLE `ref_status_kepegawaian`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ref_tingkat_pendidikan`
--
ALTER TABLE `ref_tingkat_pendidikan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `remedial`
--
ALTER TABLE `remedial`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rencana_penilaian`
--
ALTER TABLE `rencana_penilaian`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rombongan_belajar`
--
ALTER TABLE `rombongan_belajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `teknik_penilaian`
--
ALTER TABLE `teknik_penilaian`
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
