<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sinkronisasi extends Backend_Controller { 
	protected $activemenu = 'sinkronisasi';
	public function __construct() {
		parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1'); 
		$this->load->helper('dapodik');
		$this->load->model('dapodik');
		$this->template->set('activemenu', $this->activemenu);
	}
	public function index(){
		$this->load->library('curl');
		$connected = 0;
		$extension = load_extensions();
		$remote_version = check_is_dapodik();
		if($remote_version == '200' || $remote_version == '404'){
			$connected = 1;
			if(!$extension){
				$this->_database = $this->load->database('dapodik', TRUE);
			}
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Sinkronisasi Erapor dengan Dapodik')
		->set('connected', $connected)
		->set('extension', $extension)
		->build($this->sinkronisasi_folder.'/index');
	}
	public function jurusan(){
		$this->_database = $this->load->database('dapodik', TRUE);
		$jumlah_data = $this->dapodik->jumlah_data_jurusan();
		//echo $jumlah_data;
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/jurusan');
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 10;
		$from = $this->uri->segment(4);
		//$form = 0;
		$this->pagination->initialize($config);		
		$data['dapodik'] = $this->dapodik->data_jurusan($config['per_page'],$from);
		$data['inserted'] = $this->kurikulum->count_all();
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Jurusan Dapodik')
		->build($this->sinkronisasi_folder.'/jurusan', $data);
	}
	public function gelar(){
		$this->load->model('gelar_model', 'gelar');
		$this->_database = $this->load->database('dapodik', TRUE);
		$jumlah_data = $this->dapodik->jumlah_data_gelar();
		//echo $jumlah_data;
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/gelar');
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 10;
		$from = $this->uri->segment(4);
		//$form = 0;
		$this->pagination->initialize($config);		
		$data['dapodik'] = $this->dapodik->data_gelar($config['per_page'],$from);
		$data['inserted'] = $this->gelar->count_all();
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Gelar Dapodik')
		->build($this->sinkronisasi_folder.'/gelar', $data);
	}
	public function sekolah($id){
		$this->_database = $this->load->database('dapodik', TRUE);
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->semester;
		$tahun = substr($tahun, 0,4); // returns "d"
		$semester_id = $tahun.$smt;
		$settings 	= $this->settings->get(1);
		$sekolah 	= $this->sekolah->get($loggeduser->sekolah_id);
		$this->_database->select('*,ref.mst_wilayah.nama AS desa, sekolah.nama AS nama_sekolah');
		$this->_database->from('sekolah');
		$this->_database->join('ref.mst_wilayah', 'ref.mst_wilayah.kode_wilayah = sekolah.kode_wilayah');
		$this->_database->where('sekolah.sekolah_id', $id);
		$query = $this->_database->get();
		$data_sekolah = $query->row();
		$query = $this->_database->get_where('ref.mst_wilayah', array('kode_wilayah' => trim($data_sekolah->kode_wilayah)));
		$desa = $query->row();
		$query = $this->_database->get_where('ref.mst_wilayah', array('kode_wilayah' => trim($desa->mst_kode_wilayah)));
		$kecamatan = $query->row();
		$query = $this->_database->get_where('ref.mst_wilayah', array('kode_wilayah' => trim($kecamatan->mst_kode_wilayah)));
		$kabupaten = $query->row();
		$query = $this->_database->get_where('ref.mst_wilayah', array('kode_wilayah' => trim($kabupaten->mst_kode_wilayah)));
		$provinsi = $query->row();
		$query = $this->_database->get_where('tugas_tambahan', array('sekolah_id' => $id, 'jabatan_ptk_id' => 2, 'tst_tambahan' => NULL, 'soft_delete' => 0));
		$get_kasek = $query->row();
		$kasek_id = ($get_kasek) ? $get_kasek->ptk_id : gen_uuid();
		$query = $this->_database->get_where('ptk', array('ptk_id' => $kasek_id));
		$ptk = $query->row();
		if($ptk){
			$nama_ptk = addslashes($ptk->nama);
			if($ptk->nuptk && is_numeric($ptk->nuptk) && strlen($ptk->nuptk) > 10){
				$data_guru = $this->guru->find("nama = '$nama_ptk' AND nuptk = '$ptk->nuptk' AND tanggal_lahir = '$ptk->tanggal_lahir'");
			} else {
				$data_guru = $this->guru->find("nama = '$nama_ptk' AND tanggal_lahir = '$ptk->tanggal_lahir'");
			}
			$ptk->email = ($ptk->email) ? $ptk->email : GenerateEmail().'@eraporsmk.net';
			$ptk->nuptk = ($ptk->nuptk) ? $ptk->nuptk : GenerateID();
			if($ptk->email == $loggeduser->email){
				$ptk->email = GenerateEmail().'@eraporsmk.net';
			}
			$query = $this->_database->get_where('ref.mst_wilayah', array('kode_wilayah' => $ptk->kode_wilayah));
			$kecamatan_kepsek = $query->row();
			$additional_data = array(
				"sekolah_id"=> $loggeduser->sekolah_id,
				"nuptk"		=> $ptk->nuptk,
			);
			$password = 12345678;
			if($data_guru){
				$guru_id = $data_guru->id;
				$this->guru->update($data_guru->id, array('guru_id_dapodik' => $ptk->ptk_id));
				$this->user->update($data_guru->user_id, array('guru_id' => $data_guru->id));
				$find_guru_aktif = $this->guru_terdaftar->find("guru_id = $data_guru->id and semester_id = $ajaran->id");
				if($find_guru_aktif){
					$update_guru_aktif = array(
						'status' => 1
					);
					$this->guru_terdaftar->update($find_guru_aktif->id, $update_guru_aktif);
				} else {
					$attributes = array('semester_id' => $ajaran->id, 'guru_id' => $data_guru->id, 'status' => 1);
					$guru_aktif = $this->guru_terdaftar->insert($attributes);
				}
			} else {
				$group = array('3');
				$user_id = $this->ion_auth->register($ptk->nama, $password, $ptk->email, $additional_data, $group);
				if($user_id){
					$insert_guru = array(
						'sekolah_id' 			=> $loggeduser->sekolah_id,
						'user_id' 				=> $user_id,
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
						'kecamatan' 			=> $kecamatan_kepsek->nama,
						'kode_pos'				=> $ptk->kode_pos,
						'no_hp'					=> $ptk->no_hp,
						'email' 				=> $ptk->email,
						'photo' 				=> '',
						'active' 				=> 1,
						'password' 				=> $password,
						'petugas' 				=> $loggeduser->username,
						'guru_id_dapodik' 		=> $ptk->ptk_id,
					);
					$guru_id = $this->guru->insert($insert_guru);
					$find_guru_aktif = $this->guru_terdaftar->find("guru_id = $guru_id and semester_id = $ajaran->id");
					if($find_guru_aktif){
						$update_guru_aktif = array(
							'status' => 1
						);
						$this->guru_terdaftar->update($find_guru_aktif->id, $update_guru_aktif);
					} else {
						$attributes = array('semester_id' => $ajaran->id, 'guru_id' => $guru_id, 'status' => 1);			
						$guru_aktif = $this->guru_terdaftar->insert($attributes);
					}
					$this->user->update($user_id, array('guru_id' => $guru_id));
				}
			}
		}
		$data_sekolah_dapodik = array(
			'nss' 					=> $data_sekolah->nss,
			'nama' 					=> $data_sekolah->nama_sekolah,
			'alamat' 				=> $data_sekolah->alamat_jalan,
			'desa_kelurahan'		=> $desa->nama,
			'kecamatan' 			=> $kecamatan->nama,
			'kabupaten' 			=> $kabupaten->nama,
			'provinsi' 				=> $provinsi->nama,
			'kode_pos' 				=> $data_sekolah->kode_pos,
			'lintang' 				=> $data_sekolah->lintang,
			'bujur' 				=> $data_sekolah->bujur,
			'no_telp' 				=> $data_sekolah->nomor_telepon,
			'no_fax' 				=> $data_sekolah->nomor_fax,
			'email' 				=> $data_sekolah->email,
			'website' 				=> $data_sekolah->website,
			'sekolah_id_dapodik'	=> $data_sekolah->sekolah_id,
			'user_id' 				=> $loggeduser->id,
			'guru_id' 				=> $guru_id
		);
		$query = $this->_database->get_where('jurusan_sp', array('sekolah_id' => $data_sekolah->sekolah_id, 'soft_delete' => 0));
		$jurusan_sp_dapodik = $query->result();
		if($this->sekolah->update($sekolah->id, $data_sekolah_dapodik)){
			foreach($jurusan_sp_dapodik as $jur_sp_dapo){
				find_jurusan($jur_sp_dapo->jurusan_id);
				$insert_jur_sp = array(
					'sekolah_id'	=> $loggeduser->sekolah_id,
					'kurikulum_id'	=> $jur_sp_dapo->jurusan_id,
					'semester_id'	=> $ajaran->id,
					'jurusan_sp_id' => $jur_sp_dapo->jurusan_sp_id,
				);
				$get_jurusan_sp = $this->jurusan_sp->find_by_kurikulum_id($jur_sp_dapo->jurusan_id);
				if($get_jurusan_sp){
					$this->jurusan_sp->update($get_jurusan_sp->id, array('jurusan_sp_id' => $jur_sp_dapo->jurusan_sp_id));
				} else {
					$this->jurusan_sp->insert($insert_jur_sp);
				}
			}
			$this->session->set_flashdata('success', 'Data Sekolah berhasil di sinkronisasi');
		} else {
			$this->session->set_flashdata('error', 'Data Sekolah berhasil di sinkronisasi');
		}
		redirect('admin/sinkronisasi/');
	}
	//10 bidang
	//11 program
	//12 kompetensi
	public function guru($id){
		$this->load->model('gelar_ptk_model', 'gelar_ptk');
		$loggeduser = $this->ion_auth->user()->row();
		$this->_database = $this->load->database('dapodik', TRUE);
		$jumlah_data = $this->dapodik->jumlah_data_guru($id);
		//echo $jumlah_data;
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/guru/'.$id);
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 10;
		$from = $this->uri->segment(5);
		//$form = 0;
		$this->pagination->initialize($config);		
		$data['dapodik'] = $this->dapodik->data_guru($id,$config['per_page'],$from);
		$data['inserted'] = $this->guru->find_count("guru_id_dapodik IS NOT NULL");
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Guru Dapodik')
		->set('loggeduser', $loggeduser)
		->set('ajaran', get_ta())
		->build($this->sinkronisasi_folder.'/guru', $data);
	}
	public function rombongan_belajar($semester_id,$id){
		$loggeduser = $this->ion_auth->user()->row();
		$this->_database = $this->load->database('dapodik', TRUE);
		$jumlah_data = $this->dapodik->jumlah_data_rombel($id, $semester_id);
		//echo $jumlah_data;
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/rombongan_belajar/'.$semester_id.'/'.$id);
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 10;
		$from = $this->uri->segment(6);
		//$form = 0;
		$this->pagination->initialize($config);		
		$data['dapodik'] = $this->dapodik->data_rombel($id, $semester_id, $config['per_page'], $from);
		$data['inserted'] = $this->rombongan_belajar->find_count("rombel_id_dapodik IS NOT NULL");
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Rombongan Belajar Dapodik')
		->set('loggeduser', $loggeduser)
		->set('ajaran', get_ta())
		->build($this->sinkronisasi_folder.'/rombongan_belajar', $data);
	}
	public function siswa($id){
		$loggeduser = $this->ion_auth->user()->row();
		$this->_database = $this->load->database('dapodik', TRUE);
		$jumlah_data = $this->dapodik->jumlah_data_siswa($id);
		//echo $jumlah_data;
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/siswa/'.$id);
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 10;
		$from = $this->uri->segment(5);
		//$form = 0;
		$this->pagination->initialize($config);		
		$data['dapodik'] = $this->dapodik->data_siswa($id,$config['per_page'],$from);
		$data['inserted'] = $this->siswa->find_count("siswa_id_dapodik IS NOT NULL");
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Siswa Dapodik')
		->set('loggeduser', $loggeduser)
		->set('ajaran', get_ta())
		->build($this->sinkronisasi_folder.'/siswa', $data);
	}
	public function query_pembelajaran($id){
		$loggeduser = $this->ion_auth->user()->row();
		$this->_database = $this->load->database('dapodik', TRUE);
		$jumlah_data = $this->dapodik->jumlah_data_pembelajaran($id);
		//echo $jumlah_data;
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/query_pembelajaran/'.$id);
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 10;
		$from = $this->uri->segment(5);
		//$form = 0;
		$this->pagination->initialize($config);		
		$data['dapodik'] = $this->dapodik->data_pembelajaran($id,$config['per_page'],$from);
		$data['inserted'] = $this->pembelajaran->find_count("is_dapodik = 1");
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Pembelajaran Dapodik')
		->set('loggeduser', $loggeduser)
		->set('ajaran', get_ta())
		->build($this->sinkronisasi_folder.'/pembelajaran', $data);
	}
	public function nilai_rapor($id){
		$this->_database = $this->load->database('dapodik', TRUE);
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->smt;
		$tahun = substr($tahun, 0,4); // returns "d"
		$semester_id = $tahun.$smt;
		$this->_database->select('*,ptk.nama AS nama_guru,ptk.ptk_id as guru_id_dapodik');
		$this->_database->from('nilai.matev_rapor');
		$this->_database->join('pembelajaran', 'pembelajaran.pembelajaran_id = nilai.matev_rapor.pembelajaran_id');
		$this->_database->join('ptk_terdaftar', 'ptk_terdaftar.ptk_terdaftar_id = pembelajaran.ptk_terdaftar_id');
		$this->_database->join('ptk', 'ptk.ptk_id = ptk_terdaftar.ptk_id');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = nilai.matev_rapor.rombongan_belajar_id');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.sekolah_id', $id);
		$this->_database->where('nilai.matev_rapor.soft_delete', 0);
		$this->_database->where('nilai.matev_rapor.a_dari_template', 1);
		//$this->_database->order_by('rombongan_belajar.rombongan_belajar_id', 'ASC');
		$query = $this->_database->get();
		$matev_rapor = $query->result();
		$query = $this->_database->get_where('sekolah', array('sekolah_id' => $id));
		$sekolah = $query->row();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Sinkronisasi Erapor dengan Dapodik')
		->set('matev_rapor', $matev_rapor)
		->set('ajaran', $ajaran)
		->set('sekolah', $sekolah)
		->build($this->sinkronisasi_folder.'/matev_rapor');
	}
	public function mapel_komp(){
		$this->_database = $this->load->database('dapodik', TRUE);
		$jumlah_data = $this->dapodik->jumlah_data_mapel_komp();
		//echo $jumlah_data;
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/mapel_komp/');
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 50;
		$from = $this->uri->segment(4);
		//$form = 0;
		$this->pagination->initialize($config);		
		$data['dapodik'] = $this->dapodik->data_mapel_komp($config['per_page'],$from);
		$data['inserted'] = $this->mata_pelajaran_kurikulum->find_count("kurikulum_id IS NOT NULL");
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Mata Pelajaran Kompetensi Dapodik')
		->build($this->sinkronisasi_folder.'/matpel_komps', $data);
	}
	public function kd(){
		//$this->_database = $this->load->database('dapodik', TRUE);
		$this->_panongan = $this->load->database('panongan', TRUE);
		$jumlah_data = $this->kompetensi_dasar->with('mata_pelajaran')->count_all();
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/sinkronisasi/kd');
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 20;
		$from = $this->uri->segment(4);
		$this->pagination->initialize($config);
		$data['dapodik'] = //$this->kompetensi_dasar->with('mata_pelajaran')->find_all("id_kompetensi IS NOT NULL", '*','id ASC', $from, $config['per_page']);
		$this->kompetensi_dasar->with('mata_pelajaran')->find_all("mata_pelajaran_id NOT IN (SELECT id FROM ref_mata_pelajaran)", '*','id ASC', $from, $config['per_page']);
		//$this->dapodik->data_mata_pelajaran($config['per_page'],$from);
		$data['inserted'] = $this->kompetensi_dasar->with('mata_pelajaran')->find_count("mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran)");
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		//$this->load->view('dapodik',$data);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Kompetensi Dasar')
		->build($this->sinkronisasi_folder.'/kd', $data);
	}
}
