<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sync extends CI_Controller { 
	protected $activemenu = 'sinkronisasi';
	public function __construct() {
		parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1'); 
		$this->template->set('activemenu', $this->activemenu);
	}
	public function index(){
		$this->_database = $this->load->database('dapodik', TRUE);
		$username_dapo 		= $this->input->post('username_dapo');//isian manual username di laman sync eRaporSMK
		$password_dapo 		= $this->input->post('password_dapo');//isian manual passwors di laman sync eRaporSMK
		$npsn 				= $this->input->post('npsn');//isian otomatis sesuai NPSN yang dientry di eRaporSMK
		$tahun_ajaran_id 	= $this->input->post('tahun_ajaran_id');//isian otomatis sesuai periode aktif yang dipilih di eRaporSMK
		$semester_id 		= $this->input->post('semester_id');//isian otomatis sesuai periode aktif yang dipilih di eRaporSMK
		//validasi login
		if (empty($username_dapo) || empty($password_dapo)){
			$msg['post_login'] = 0;
			$msg['message'] = 'Username atau password tidak boleh kosong';
			echo json_encode($msg);
			return false;
		}
		$this->_database->select('a.username, a.password, a.aktif, a.sekolah_id, b.bentuk_pendidikan_id,b.*');
		$this->_database->from('pengguna as a');
		$this->_database->join('sekolah as b', 'a.sekolah_id = b.sekolah_id');
		$this->_database->where('a.username', $username_dapo);
		//$this->_database->where('b.bentuk_pendidikan_id', 15);//dimatikan untuk multi validasi
		//$this->_database->where('sekolah.npsn', $npsn);//dimatikan untuk multi validasi
		$query = $this->_database->get();
		if ($query->num_rows() === 1){//ditemukan username
			$result = $query->row();
			if($result->password != md5($password_dapo)){//validasi pertama = password
				$msg['post_login'] = 0;
				$msg['message'] = 'Password salah';
				echo json_encode($msg);
				return false;
			}
			if ($result->aktif == 0){//validasi kedua = status keaktifan pengguna
				$msg['post_login'] = 0;
				$msg['message'] = 'Pengguna tidak aktif';
				echo json_encode($msg);
				return false;
			}
			if ($result->bentuk_pendidikan_id != 15){//validasi ketiga jenjang sekolah dan jenis sekolah
				$msg['post_login'] = 0;
				$msg['message'] = 'Sinkronisasi hanya untuk Sekolah Menengah Kejuruan (SMK)';
				echo json_encode($msg);
				return false;
			}
			if ($result->npsn != $npsn){//validasi keempat npsn user login di database pusat dan npsn yang di entry di eRaporSMK
				$msg['post_login'] = 0;
				$msg['message'] = 'NPSN eRapor tidak sama dengan NPSN Dapodik';
				echo json_encode($msg);
				return false;
			}
			$sekolah_id = $result->sekolah_id;
		} else {//pengguna tidak ditemukan di database
			$msg['post_login'] = 0;
			$msg['message'] = 'Pengguna tidak terdaftar';
			echo json_encode($msg);
			return false;
		}
		$query = $this->_database->get_where('tugas_tambahan', array('sekolah_id' => $sekolah_id, 'jabatan_ptk_id' => 2, 'tst_tambahan' => NULL, 'soft_delete' => 0));
		$get_kasek = $query->row();
		$kasek_id = ($get_kasek) ? $get_kasek->ptk_id : $this->generate_uuid();
		$query = $this->_database->get_where('ptk', array('ptk_id' => $kasek_id));
		$ptk = $query->row();
		if($ptk){
			$data['sekolah'] = array(
				'nss' 					=> $result->nss,
				'nama' 					=> $result->nama,
				'alamat' 				=> $result->alamat_jalan,
				'desa_kelurahan'		=> $result->desa_kelurahan,
				'kode_wilayah' 			=> $result->kode_wilayah,
				'kode_pos' 				=> $result->kode_pos,
				'lintang' 				=> $result->lintang,
				'bujur' 				=> $result->bujur,
				'no_telp' 				=> $result->nomor_telepon,
				'no_fax' 				=> $result->nomor_fax,
				'email' 				=> $result->email,
				'website' 				=> $result->website,
				'sekolah_id_dapodik'	=> $sekolah_id,
				'message'				=> 'Data Sekolah',
				'percent'				=> 10,
			);
			$data['kepsek'] = array(
				'nama' 					=> $ptk->nama,
				'nuptk' 				=> $ptk->nuptk,
				'nip' 					=> $ptk->nip,
				'nik' 					=> $ptk->nik,
				'jenis_kelamin' 		=> $ptk->jenis_kelamin,
				'tempat_lahir' 			=> $ptk->tempat_lahir,
				'tanggal_lahir' 		=> $ptk->tanggal_lahir,
				'status_kepegawaian_id'	=> $ptk->status_kepegawaian_id,
				'jenis_ptk' 			=> $ptk->jenis_ptk_id,
				'agama_id' 				=> $ptk->agama_id,
				'alamat' 				=> $ptk->alamat_jalan,
				'rt' 					=> $ptk->rt,
				'rw' 					=> $ptk->rw,
				'desa_kelurahan' 		=> $ptk->desa_kelurahan,
				'kode_wilayah' 			=> $ptk->kode_wilayah,
				'kode_pos'				=> $ptk->kode_pos,
				'no_hp'					=> $ptk->no_hp,
				'email' 				=> $ptk->email,
				'guru_id_dapodik' 		=> $ptk->ptk_id,
				'message'				=> 'Kepala Sekolah',
				'percent'				=> 20,
			);
			$msg['data'] = $data;
		} else {
			$msg['data'] 	= 'sekolah kosong';
			$msg['message'] = 'Sekolah';
			$msg['percent'] = 0;
			$msg['status'] 	= 0;
		}
		$msg['data']['guru'] 				= $this->guru($sekolah_id, $tahun_ajaran_id);
		$msg['data']['rombongan_belajar'] 	= $this->rombongan_belajar($sekolah_id, $semester_id);
		$msg['data']['siswa'] 				= $this->siswa($sekolah_id, $semester_id);
		$msg['data']['pembelajaran'] 		= $this->pembelajaran($sekolah_id, $tahun_ajaran_id, $semester_id);
		$msg['post_login'] = 1;
		echo json_encode($msg);
	}
	private function guru($sekolah_id, $tahun_ajaran_id){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('ptk');
		$this->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_id = ptk.ptk_id');
		$this->_database->where('ptk_terdaftar.tahun_ajaran_id', $tahun_ajaran_id);
		$this->_database->where('ptk_terdaftar.sekolah_id', $sekolah_id);
		$this->_database->where('ptk_terdaftar.soft_delete', 0);
		$this->_database->where('ptk.soft_delete', 0);
		$this->_database->where('ptk.jenis_ptk_id != 11');
		$this->_database->where('ptk_terdaftar.jenis_keluar_id', NULL);
		$this->_database->where('ptk_terdaftar.ptk_terdaftar_id IS NOT NULL');
		$this->_database->order_by('ptk.nama', 'ASC');
		//$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		if($query->num_rows()>0){
			$msg['data'] = $query->result();
			$msg['message'] = 'Guru';
			$msg['percent'] = 30;
			$msg['status'] = 1;
		} else {
			$msg['data'] = NULL;
			$msg['message'] = 'Guru';
			$msg['percent'] = 0;
			$msg['status'] = 0;
		}
		return $msg;
	}
	private function rombongan_belajar($sekolah_id, $semester_id){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*');
		$this->_database->from('rombongan_belajar');
		$this->_database->where('semester_id', $semester_id);
		$this->_database->where('sekolah_id', $sekolah_id);
		$this->_database->where('soft_delete', 0);
		$this->_database->where('jenis_rombel', 1);
		$this->_database->order_by('nama', 'ASC');
		//$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		if($query->num_rows()>0){
			$msg['data'] = $query->result();
			$msg['message'] = 'Rombongan Belajar';
			$msg['percent'] = 60;
			$msg['status'] = 1;
		} else {
			$msg['data'] = NULL;
			$msg['message'] = 'Rombongan Belajar';
			$msg['percent'] = 30;
			$msg['status'] = 0;
		}
		return $msg;
	}
	private function siswa($sekolah_id, $semester_id){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*,peserta_didik.nama AS nama_siswa, rombongan_belajar.nama AS nama_rombongan_belajar, ref.mst_wilayah.nama AS kecamatan');
		$this->_database->from('peserta_didik');
		$this->_database->join('registrasi_peserta_didik', 'registrasi_peserta_didik.peserta_didik_id = peserta_didik.peserta_didik_id');
		$this->_database->join('ref.mst_wilayah', 'ref.mst_wilayah.kode_wilayah = peserta_didik.kode_wilayah');
		$this->_database->join('anggota_rombel', 'anggota_rombel.peserta_didik_id = peserta_didik.peserta_didik_id');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = anggota_rombel.rombongan_belajar_id');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.jenis_rombel', 1);
		$this->_database->where('registrasi_peserta_didik.sekolah_id', $sekolah_id);
		$this->_database->where('registrasi_peserta_didik.jenis_keluar_id', NULL);
		$this->_database->where('registrasi_peserta_didik.soft_delete', 0);
		$this->_database->where('peserta_didik.soft_delete', 0);
		$this->_database->where('anggota_rombel.soft_delete', 0);
		$this->_database->order_by('peserta_didik.nama', 'ASC');
		//$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		if($query->num_rows()>0){
			$msg['data'] = $query->result();
			$msg['message'] = 'Siswa';
			$msg['percent'] = 70;
			$msg['status'] = 1;
		} else {
			$msg['data'] = NULL;
			$msg['message'] = 'Siswa';
			$msg['percent'] = 70;
			$msg['status'] = 0;
		}
		return $msg;
	}
	private function pembelajaran($sekolah_id, $tahun_ajaran_id, $semester_id){
		$this->_database = $this->load->database('dapodik', TRUE);
		$this->_database->select('*,ptk_terdaftar.ptk_id AS set_ptk_id');
		$this->_database->from('pembelajaran');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = pembelajaran.rombongan_belajar_id');
		$this->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_terdaftar_id = pembelajaran.ptk_terdaftar_id');
		//$this->_database->join('ref.mata_pelajaran_kurikulum', 'ref.mata_pelajaran_kurikulum.mata_pelajaran_id = pembelajaran.mata_pelajaran_id AND ref.mata_pelajaran_kurikulum.kurikulum_id = rombongan_belajar.kurikulum_id');
		$this->_database->where('ptk_terdaftar.tahun_ajaran_id', $tahun_ajaran_id);
		$this->_database->where('ptk_terdaftar.sekolah_id', $sekolah_id);
		$this->_database->where('ptk_terdaftar.soft_delete', 0);
		$this->_database->where('ptk_terdaftar.jenis_keluar_id', NULL);
		$this->_database->where('ptk_terdaftar.ptk_terdaftar_id IS NOT NULL');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.sekolah_id', $sekolah_id);
		$this->_database->where('rombongan_belajar.jenis_rombel', 1);
		$this->_database->where('pembelajaran.soft_delete', 0);
		//$this->_database->where('pembelajaran.mata_pelajaran_id != 500050000');
		$this->_database->order_by('pembelajaran.mata_pelajaran_id', 'ASC');
		$this->_database->order_by('pembelajaran.rombongan_belajar_id', 'DESC');
		//$this->_database->limit($limit, $offset);
		$query = $this->_database->get();
		if($query->num_rows()>0){
			$msg['data'] = $query->result();
			$msg['message'] = 'Pembelajaran';
			$msg['percent'] = 90;
			$msg['status'] = 1;
		} else {
			$msg['data'] = NULL;
			$msg['message'] = 'Pembelajaran';
			$msg['percent'] = 90;
			$msg['status'] = 0;
		}
		return $msg;
	}
	private function generate_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	
			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),
	
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,
	
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,
	
			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
}
