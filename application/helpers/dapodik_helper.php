<?php
function get_ptk_terdaftar($tahun_ajaran_id, $id_sekolah_dapodik){
	$CI = & get_instance();
	$CI->_database->select('*');
	$CI->_database->from('ptk');
	$CI->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_id = ptk.ptk_id');
	$CI->_database->where('ptk_terdaftar.tahun_ajaran_id', $tahun_ajaran_id);
	$CI->_database->where('ptk_terdaftar.sekolah_id', $id_sekolah_dapodik);
	$CI->_database->where('ptk_terdaftar.soft_delete', 0);
	$CI->_database->where('ptk.soft_delete', 0);
	$CI->_database->where('ptk_terdaftar.jenis_keluar_id', NULL);
	$CI->_database->where('ptk.jenis_ptk_id != 11');
	$CI->_database->where('ptk_terdaftar.ptk_terdaftar_id IS NOT NULL');
	$CI->_database->order_by('ptk.nama', 'ASC');
	$query = $CI->_database->get();
	$ptk_terdaftar = $query->result();
	return $ptk_terdaftar;
}
function get_rombongan_belajar($semester_id, $id_sekolah_dapodik){
	$CI = & get_instance();
	$CI->_database->select('*');
	$CI->_database->from('rombongan_belajar');
	$CI->_database->where('rombongan_belajar.semester_id', $semester_id);
	$CI->_database->where('rombongan_belajar.sekolah_id', $id_sekolah_dapodik);
	$CI->_database->where('rombongan_belajar.soft_delete', 0);
	$CI->_database->where('rombongan_belajar.jenis_rombel', 1);
	$query = $CI->_database->get();
	$rombongan_belajar = $query->result();
	return $rombongan_belajar;
}
function get_registrasi_peserta_didik($semester_id, $id_sekolah_dapodik){
	$CI = & get_instance();
	$CI->_database->select('*');
	$CI->_database->from('peserta_didik');
	$CI->_database->join('registrasi_peserta_didik', 'registrasi_peserta_didik.peserta_didik_id = peserta_didik.peserta_didik_id');
	$CI->_database->join('anggota_rombel', 'anggota_rombel.peserta_didik_id = peserta_didik.peserta_didik_id');
	$CI->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = anggota_rombel.rombongan_belajar_id');
	$CI->_database->where('rombongan_belajar.semester_id', $semester_id);
	$CI->_database->where('registrasi_peserta_didik.sekolah_id', $id_sekolah_dapodik);
	$CI->_database->where('registrasi_peserta_didik.jenis_keluar_id', NULL);
	$CI->_database->where('registrasi_peserta_didik.soft_delete', 0);
	$CI->_database->where('peserta_didik.soft_delete', 0);
	$CI->_database->where('rombongan_belajar.soft_delete', 0);
	$CI->_database->where('rombongan_belajar.jenis_rombel', 1);
	$CI->_database->where('anggota_rombel.soft_delete', 0);
	$query = $CI->_database->get();
	$registrasi_peserta_didik = $query->result();
	return $registrasi_peserta_didik;
}
function get_pembelajaran_dapodik($semester_id, $id_sekolah_dapodik){
	$CI = & get_instance();
	$CI->_database->select('*, pembelajaran.ptk_terdaftar_id as ptk_new');
	$CI->_database->from('pembelajaran');
	$CI->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = pembelajaran.rombongan_belajar_id');
	$CI->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_terdaftar_id = pembelajaran.ptk_terdaftar_id');
	$CI->_database->where('rombongan_belajar.semester_id', $semester_id);
	$CI->_database->where('rombongan_belajar.sekolah_id', $id_sekolah_dapodik);
	$CI->_database->where('rombongan_belajar.soft_delete', 0);
	$CI->_database->where('rombongan_belajar.jenis_rombel', 1);
	$CI->_database->where('pembelajaran.soft_delete', 0);
	$CI->_database->where('pembelajaran.mata_pelajaran_id != 500050000');
	//$CI->_database->order_by('rombongan_belajar.rombongan_belajar_id', 'ASC');
	//$CI->_database->group_by("mata_pelajaran_id");
	$CI->_database->distinct();
	$query = $CI->_database->get();
	$pembelajaran_dapodik = $query->result();
	return $pembelajaran_dapodik;
}
?>