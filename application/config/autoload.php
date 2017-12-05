<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/
$autoload['libraries'] = array('template', 'session', 'database');
//$autoload['sparks'] = array('php-activerecord/0.0.2');
/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = array('cache');
|
| You can also supply an alternative property name to be assigned in
| the controller:
|
|	$autoload['drivers'] = array('cache' => 'cch');
|
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array('html', 'url', 'custom', 'form');
//$autoload['helper'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/
$autoload['config'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = array(
						'settings_model' 					=> 'settings',
						'sekolah_model' 					=> 'sekolah',
						'semester_model' 					=> 'semester',
						'guru_model' 						=> 'guru',
						'guru_terdaftar_model' 				=> 'guru_terdaftar',
						'siswa_model' 						=> 'siswa',
						'rombongan_belajar_model' 			=> 'rombongan_belajar',
						'anggota_rombel_model' 				=> 'anggota_rombel',
						'jurusan_sp_model' 					=> 'jurusan_sp',
						'jurusan_model' 					=> 'jurusan',
						'kurikulum_model' 					=> 'kurikulum',
						'user_model' 						=> 'user',
						'rencana_penilaian_model' 			=> 'rencana_penilaian',
						'nilai_model' 						=> 'nilai',
						'group_model' 						=> 'group',
						'pembelajaran_model' 				=> 'pembelajaran',
						'status_kepegawaian_model' 			=> 'status_kepegawaian',
						'jenis_ptk_model' 					=> 'jenis_ptk',
						'agama_model' 						=> 'agama',
						'tingkat_pendidikan_model' 			=> 'tingkat_pendidikan',
						'mata_pelajaran_model' 				=> 'mata_pelajaran',
						'mata_pelajaran_kurikulum_model'	=> 'mata_pelajaran_kurikulum',
						'kelompok_model'					=> 'kelompok',
						'ekstrakurikuler_model'				=> 'ekstrakurikuler',
						'teknik_penilaian_model'			=> 'teknik_penilaian',
						'sikap_model'						=> 'sikap',
						'nilai_sikap_model'					=> 'nilai_sikap',
						'kompetensi_dasar_model'			=> 'kompetensi_dasar',
						'kd_nilai_model'					=> 'kd_nilai',
						'remedial_model'					=> 'remedial',
						'deskripsi_mata_pelajaran_model'	=> 'deskripsi_mata_pelajaran',
						'ref_ppk_model'						=> 'ref_ppk',
						'ppk_model'							=> 'ppk',
						'catatan_ppk_model'					=> 'catatan_ppk',
						'catatan_wali_model'				=> 'catatan_wali',
						'deskripsi_sikap_model'				=> 'deskripsi_sikap',
						'absen_model'						=> 'absen',
						'nilai_ekstrakurikuler_model'		=> 'nilai_ekstrakurikuler',
						'prakerin_model'					=> 'prakerin',
						'prestasi_model'					=> 'prestasi',
						'data_excel_model'					=> 'data_excel',
						'import_rencana_model'				=> 'import_rencana',
					);
