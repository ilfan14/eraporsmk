<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dapodik extends CI_Model {
	public function data_jurusan($limit, $offset){
		$this->_database = $this->load->database('dapodik', TRUE);
/*
select a.jurusan_id, a.nama_jurusan, a.jurusan_induk, a.level_bidang_id, b.kurikulum_id, b.nama_kurikulum, b.jurusan_id as id_jurusan
from ref.jurusan a
join ref.kurikulum b on a.jurusan_id = b.jurusan_id
where a.untuk_smk = 1
and a.expired_date is null
and b.expired_date is null
*/
		$this->_database->select('*');
		$this->_database->from('ref.jurusan as a');
		$this->_database->join('ref.kurikulum as b', 'a.jurusan_id = b.jurusan_id');
		$this->_database->where('a.untuk_smk', '1');
		$this->_database->where('a.expired_date IS NULL');
		$this->_database->where('b.expired_date IS NULL');
		//$this->_database->where_in('b.kurikulum_id', array(226,315,535,566));
		$this->_database->order_by('b.jurusan_id', 'ASC');
		$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		return $query->result();
	}
	public function jumlah_data_jurusan(){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('ref.jurusan as a');
		$this->_database->join('ref.kurikulum as b', 'a.jurusan_id = b.jurusan_id');
		$this->_database->where('a.untuk_smk', '1');
		$this->_database->where('a.expired_date IS NULL');
		$this->_database->where('b.expired_date IS NULL');
		//$this->_database->where_in('b.kurikulum_id', array(226,315,535,566));
		$this->_database->order_by('b.jurusan_id', 'ASC');
		$query = $this->_database->count_all_results();
		return $query;
	}
	public function data_guru($id, $limit, $offset){
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->semester;
		$tahun_ajaran_id = substr($tahun, 0,4); // returns "d"
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('ptk');
		$this->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_id = ptk.ptk_id');
		$this->_database->where('ptk_terdaftar.tahun_ajaran_id', $tahun_ajaran_id);
		$this->_database->where('ptk_terdaftar.sekolah_id', $id);
		$this->_database->where('ptk_terdaftar.soft_delete', 0);
		$this->_database->where('ptk.soft_delete', 0);
		$this->_database->where('ptk.jenis_ptk_id != 11');
		$this->_database->where('ptk_terdaftar.jenis_keluar_id', NULL);
		$this->_database->where('ptk_terdaftar.ptk_terdaftar_id IS NOT NULL');
		$this->_database->order_by('ptk.nama', 'ASC');
		$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		return $query->result();
	}
	public function jumlah_data_guru($id){
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->semester;
		$tahun_ajaran_id = substr($tahun, 0,4); // returns "d"
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('ptk');
		$this->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_id = ptk.ptk_id');
		$this->_database->where('ptk_terdaftar.tahun_ajaran_id', $tahun_ajaran_id);
		$this->_database->where('ptk_terdaftar.sekolah_id', $id);
		$this->_database->where('ptk_terdaftar.soft_delete', 0);
		$this->_database->where('ptk.soft_delete', 0);
		$this->_database->where('ptk.jenis_ptk_id != 11');
		$this->_database->where('ptk_terdaftar.jenis_keluar_id', NULL);
		$this->_database->where('ptk_terdaftar.ptk_terdaftar_id IS NOT NULL');
		$query = $this->_database->count_all_results();
		return $query;
	}
	public function data_rombel($id, $tahun_ajaran_id, $limit, $offset){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('rombongan_belajar');
		$this->_database->where('semester_id', $tahun_ajaran_id);
		$this->_database->where('sekolah_id', $id);
		$this->_database->where('soft_delete', 0);
		$this->_database->where('jenis_rombel', 1);
		$this->_database->order_by('nama', 'ASC');
		$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		return $query->result();
	}
	public function jumlah_data_rombel($id, $tahun_ajaran_id){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('rombongan_belajar');
		$this->_database->where('semester_id', $tahun_ajaran_id);
		$this->_database->where('sekolah_id', $id);
		$this->_database->where('soft_delete', 0);
		$this->_database->where('jenis_rombel', 1);
		$this->_database->order_by('nama', 'ASC');
		$query = $this->_database->count_all_results();
		return $query;
	}
	public function data_siswa($id, $limit, $offset){
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->semester;
		$tahun_ajaran_id = substr($tahun, 0,4); // returns "d"
		$semester_id = $tahun_ajaran_id.$smt;
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('peserta_didik.*,peserta_didik.nama AS nama_siswa, rombongan_belajar.nama AS nama_rombongan_belajar, ref.mst_wilayah.nama AS kecamatan, registrasi_peserta_didik.registrasi_id, registrasi_peserta_didik.jenis_pendaftaran_id, registrasi_peserta_didik.nipd, registrasi_peserta_didik.tanggal_masuk_sekolah, registrasi_peserta_didik.sekolah_asal,rombongan_belajar.rombongan_belajar_id, rombongan_belajar.tingkat_pendidikan_id, rombongan_belajar.jurusan_sp_id, kurikulum_id, rombongan_belajar.ptk_id, anggota_rombel.anggota_rombel_id');
		$this->_database->from('peserta_didik');
		$this->_database->join('registrasi_peserta_didik', 'registrasi_peserta_didik.peserta_didik_id = peserta_didik.peserta_didik_id');
		$this->_database->join('ref.mst_wilayah', 'ref.mst_wilayah.kode_wilayah = peserta_didik.kode_wilayah');
		$this->_database->join('anggota_rombel', 'anggota_rombel.peserta_didik_id = peserta_didik.peserta_didik_id');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = anggota_rombel.rombongan_belajar_id');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.jenis_rombel', 1);
		//$this->_database->where('rombongan_belajar.tingkat_pendidikan_id', 11);
		$this->_database->where('registrasi_peserta_didik.sekolah_id', $id);
		$this->_database->where('registrasi_peserta_didik.jenis_keluar_id', NULL);
		$this->_database->where('registrasi_peserta_didik.soft_delete', 0);
		$this->_database->where('peserta_didik.soft_delete', 0);
		$this->_database->where('anggota_rombel.soft_delete', 0);
		$this->_database->order_by('peserta_didik.nama', 'ASC');
		//$this->_database->order_by('peserta_didik.nama', 'ASC');
		//$this->_database->order_by('rombongan_belajar.tingkat_pendidikan_id', 'ASC');
		$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		return $query->result();
	}
	public function jumlah_data_siswa($id){
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->semester;
		$tahun_ajaran_id = substr($tahun, 0,4); // returns "d"
		$semester_id = $tahun_ajaran_id.$smt;
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*,peserta_didik.nama AS nama_siswa');
		$this->_database->from('peserta_didik');
		$this->_database->join('registrasi_peserta_didik', 'registrasi_peserta_didik.peserta_didik_id = peserta_didik.peserta_didik_id');
		$this->_database->join('ref.mst_wilayah', 'ref.mst_wilayah.kode_wilayah = peserta_didik.kode_wilayah');
		$this->_database->join('anggota_rombel', 'anggota_rombel.peserta_didik_id = peserta_didik.peserta_didik_id');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = anggota_rombel.rombongan_belajar_id');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.jenis_rombel', 1);
		$this->_database->where('registrasi_peserta_didik.sekolah_id', $id);
		$this->_database->where('registrasi_peserta_didik.jenis_keluar_id', NULL);
		$this->_database->where('registrasi_peserta_didik.soft_delete', 0);
		$this->_database->where('peserta_didik.soft_delete', 0);
		$this->_database->where('anggota_rombel.soft_delete', 0);
		$this->_database->order_by('peserta_didik.nama', 'ASC');
		$query = $this->_database->count_all_results();
		return $query;
	}
	public function jumlah_data_pembelajaran($id){
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$tahun = substr($tahun, 0,4); // returns "d"
		$smt = $ajaran->semester;
		$semester_id = $tahun.$smt;
		$this->_database->select('*,ptk_terdaftar.ptk_id AS set_ptk_id');
		$this->_database->from('pembelajaran');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = pembelajaran.rombongan_belajar_id');
		$this->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_terdaftar_id = pembelajaran.ptk_terdaftar_id');
		$this->_database->where('ptk_terdaftar.tahun_ajaran_id', $tahun);
		$this->_database->where('ptk_terdaftar.sekolah_id', $id);
		$this->_database->where('ptk_terdaftar.soft_delete', 0);
		$this->_database->where('ptk_terdaftar.jenis_keluar_id', NULL);
		$this->_database->where('ptk_terdaftar.ptk_terdaftar_id IS NOT NULL');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.sekolah_id', $id);
		$this->_database->where('rombongan_belajar.jenis_rombel', 1);
		$this->_database->where('pembelajaran.soft_delete', 0);
		//$this->_database->where('pembelajaran.mata_pelajaran_id != 500050000');
		$this->_database->order_by('rombongan_belajar.rombongan_belajar_id', 'ASC');
		$query = $this->_database->count_all_results();
		return $query;
	}
	public function data_pembelajaran($id,$limit, $offset){
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$tahun = substr($tahun, 0,4); // returns "d"
		$smt = $ajaran->semester;
		$semester_id = $tahun.$smt;
		$this->_database->select('*,ptk_terdaftar.ptk_id AS set_ptk_id');
		$this->_database->from('pembelajaran');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = pembelajaran.rombongan_belajar_id');
		$this->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_terdaftar_id = pembelajaran.ptk_terdaftar_id');
		//$this->_database->join('ref.mata_pelajaran_kurikulum', 'ref.mata_pelajaran_kurikulum.mata_pelajaran_id = pembelajaran.mata_pelajaran_id AND ref.mata_pelajaran_kurikulum.kurikulum_id = rombongan_belajar.kurikulum_id');
		$this->_database->where('ptk_terdaftar.tahun_ajaran_id', $tahun);
		$this->_database->where('ptk_terdaftar.sekolah_id', $id);
		$this->_database->where('ptk_terdaftar.soft_delete', 0);
		$this->_database->where('ptk_terdaftar.jenis_keluar_id', NULL);
		$this->_database->where('ptk_terdaftar.ptk_terdaftar_id IS NOT NULL');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.sekolah_id', $id);
		$this->_database->where('rombongan_belajar.jenis_rombel', 1);
		$this->_database->where('pembelajaran.soft_delete', 0);
		//$this->_database->where('pembelajaran.mata_pelajaran_id != 500050000');
		$this->_database->order_by('pembelajaran.mata_pelajaran_id', 'ASC');
		$this->_database->order_by('pembelajaran.rombongan_belajar_id', 'DESC');
		$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		return $query->result();
	}
	public function data_mapel_komp($limit, $offset){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('b.nama_kurikulum, p.nama as nama_mata_pelajaran, a.*');
		$this->_database->from('ref.mata_pelajaran_kurikulum as a');
		$this->_database->join('ref.kurikulum as b', 'a.kurikulum_id = b.kurikulum_id');
		$this->_database->join('ref.mata_pelajaran as p', 'a.mata_pelajaran_id = p.mata_pelajaran_id');
		$this->_database->where('a.expired_date is null');
		$this->_database->where('b.expired_date is null');
		$this->_database->where('p.expired_date is null');
		$this->_database->where("b.nama_kurikulum like '%SMK%'");
		//$this->_database->where("a.kurikulum_id", 307);
		$this->_database->order_by('a.kurikulum_id DESC, a.mata_pelajaran_id ASC');
		$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		return $query->result();
	}
	public function jumlah_data_mapel_komp(){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('b.nama_kurikulum, p.nama as nama_mata_pelajaran, a.*');
		$this->_database->from('ref.mata_pelajaran_kurikulum as a');
		$this->_database->join('ref.kurikulum as b', 'a.kurikulum_id = b.kurikulum_id');
		$this->_database->join('ref.mata_pelajaran as p', 'a.mata_pelajaran_id = p.mata_pelajaran_id');
		$this->_database->where('a.expired_date is null');
		$this->_database->where('b.expired_date is null');
		$this->_database->where('p.expired_date is null');
		$this->_database->where("b.nama_kurikulum like '%SMK%'");
		//$this->_database->where("a.kurikulum_id", 307);
		$this->_database->order_by('a.kurikulum_id DESC, a.mata_pelajaran_id ASC');
		$query = $this->_database->count_all_results();
		return $query;
	}
	public function jumlah_data_gelar(){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('ref.gelar_akademik');
		$this->_database->where('expired_date IS NULL');
		$query = $this->_database->count_all_results();
		return $query;
	}
	public function data_gelar($limit, $offset){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('ref.gelar_akademik');
		$this->_database->where('expired_date IS NULL');
		$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		return $query->result();
	}
}