<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Referensi extends Backend_Controller {
	protected $activemenu = 'referensi';
	public function __construct() {
		parent::__construct();
		$this->template->set('activemenu', $this->activemenu);
		$admin_group = array(1,2,3,5,6);
		//hak_akses($admin_group);
		if(!$this->ion_auth->in_group($admin_group)){
			return show_error('Akses ditolak');
		}
	}
	public function index(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/referensi/tambah_mapel').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel : Referensi Mata Pelajaran')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Referensi Mata Pelajaran')
		->set('pilih_rombel', $pilih_rombel)
		->set('ajaran', $ajaran)
		->set('sekolah_id', $loggeduser->sekolah_id)
        ->build($this->admin_folder.'/referensi/kompetensi_keahlian');
	}
	public function mata_pelajaran(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/referensi/tambah_mapel').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel : Referensi Mata Pelajaran')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Referensi Mata Pelajaran')
		->set('pilih_rombel', $pilih_rombel)
		->set('ajaran', $ajaran)
		->set('sekolah_id', $loggeduser->sekolah_id)
        ->build($this->admin_folder.'/referensi/mata_pelajaran');
	}
	public function kkm(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<span class="btn btn-danger btn-file" style="float:right;">Import Template<input type="file" id="fileupload" name="import" /></span><a href="'.site_url('admin/referensi/excel_kkm').'" class="btn btn-success" style="float:right; margin-right:5px;"><i class="fa fa-download"></i> Download Template KKM</a>';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi KB (KKM)')
		//->set('pilih_rombel', $pilih_rombel)
		->set('ajaran', $ajaran)
		->set('sekolah_id', $loggeduser->sekolah_id)
		->build($this->admin_folder.'/referensi/kkm');
	}
	public function metode(){
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/referensi/add_metode').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel : Referensi Metode Penilaian')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Referensi Metode Penilaian')
		->set('pilih_rombel', $pilih_rombel)
        ->build($this->admin_folder.'/referensi/metode');
	}
	public function add_metode(){
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data Teknik Penilaian')
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_metode');
	}
	public function sikap(){
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/referensi/add_sikap').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel : Referensi Sikap')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Acuan Sikap')
		->set('pilih_rombel', $pilih_rombel)
        ->build($this->admin_folder.'/referensi/sikap');
	}
	public function add_sikap(){
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data Acuan Sikap')
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_sikap');
	}
	public function tambah_mapel(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data Kurikulum')
		->set('form_action', 'admin/referensi/simpan')
		->set('user', $loggeduser)
		->set('ajaran', $ajaran)
		->set('sekolah_id', $loggeduser->sekolah_id)
		//->set('get_kelompok', $get_kelompok)
		->build($this->admin_folder.'/referensi/_mata_pelajaran');
	}
	public function edit_kurikulum($id){
		$kurikulum = Kurikulum::find($id);
		$data_rombel = Datarombel::find($kurikulum->data_rombel_id);
		$tingkat = $data_rombel->tingkat;
		$kurikulum_id = $data_rombel->kurikulum_id;
		$kelas_X	= 0;
		$kelas_XI	= 0;
		$kelas_XII	= 0;
		$kelas_XIII	= 0;
		$k1 = 0;
		$k2 = 0;
		$k3 = 0;
		if($tingkat == 10){
			$kelas_X	= 1;
			$k1			= 1;
		} elseif($tingkat == 11){
			$kelas_X	= 1;
			$kelas_XI	= 1;
			$k1			= 1;
			$k2			= 1;
		} elseif($tingkat == 12){
			$kelas_X	= 1;
			$kelas_XI	= 1;
			$kelas_XII	= 1;
			$k3			= 1;
			$k1			= 1;
			$k2			= 1;
		} elseif($tingkat == 13){
			$kelas_X	= 1;
			$kelas_XI	= 1;
			$kelas_XII	= 1;
			$kelas_XIII	= 1;
		}
		//$all_mapel = Matpelkomp::find_all_by_kelas_X_and_kelas_XI_and_kelas_XII_and_kelas_XIII($kelas_X,$kelas_XI,$kelas_XII,$kelas_XIII);
		$matpel_komp = Matpelkomp::find('all', array('conditions' => array('kelas_X = ? AND kelas_XI = ? AND kelas_XII = ? AND kelas_XIII = ? AND kurikulum_id = ?', $kelas_X,$kelas_XI,$kelas_XII,$kelas_XIII, $kurikulum_id)));
		$matpel_umum = Datamapel::find('all', array('conditions' => array('k1 = ? AND k2 = ? AND k3 = ? AND kur = ?', $k1, $k2, $k3, 2013)));
		if(is_array($matpel_komp) && is_array($matpel_umum)){
			$all_mapel = array_merge($matpel_komp,$matpel_umum);
		} elseif(is_array($matpel_komp) && !is_array($matpel_umum)){
			$all_mapel = $matpel_komp;
		} elseif(is_array($matpel_komp) && !is_array($matpel_umum)){
			$all_mapel = $matpel_umum;
		}
		$data['ajarans'] = Ajaran::all();
		$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$data['data_guru'] = Dataguru::all();
		$all_rombel = Datarombel::all();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('data', $kurikulum)
		->set('data_rombel', $data_rombel)
		->set('all_mapel', $all_mapel)
		->set('all_rombel', $all_rombel)
		->set('page_title', 'Edit Referensi Kurikulum')
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_kurikulum',$data);
	}
	public function simpan(){
		$query = $_POST['query'];
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		$semester_id = isset($_POST['ajaran_id']) ? $_POST['ajaran_id'] : '';
		$kelas = isset($_POST['kelas']) ? $_POST['kelas'] : '';
		$rombel_id = isset($_POST['rombel_id']) ? $_POST['rombel_id'] : '';
		$mapel_id =  isset($_POST['mapel_id']) ? $_POST['mapel_id'] : '';
		$guru_id = isset($_POST['guru_id']) ? $_POST['guru_id'] : '';
		$nama_kur = isset($_POST['nama_kur']) ? $_POST['nama_kur'] : '';
		$nama_mulok = isset($_POST['nama_mulok']) ? $_POST['nama_mulok'] : '';
		$kkm = isset($_POST['kkm']) ? $_POST['kkm'] : '';
		$nama_ekskul = isset($_POST['nama_ekskul']) ? $_POST['nama_ekskul'] : '';
		$nama_ketua = isset($_POST['nama_ketua']) ? $_POST['nama_ketua'] : '';
		$nomor_kontak = isset($_POST['nomor_kontak']) ? $_POST['nomor_kontak'] : '';
		$alamat_ekskul = isset($_POST['alamat_ekskul']) ? $_POST['alamat_ekskul'] : '';
		$kompetensi_id = isset($_POST['kompetensi_id']) ? $_POST['kompetensi_id'] : '';
		$nama_metode = isset($_POST['nama_metode']) ? $_POST['nama_metode'] : '';
		$kd_id = isset($_POST['kd_id']) ? $_POST['kd_id'] : '';
		$kd_uraian = isset($_POST['kd_uraian']) ? $_POST['kd_uraian'] : '';
		$butir_sikap = isset($_POST['butir_sikap']) ? $_POST['butir_sikap'] : '';
		$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
		if($query == 'metode'){
			$data = array(
				'kompetensi_id' => $kompetensi_id,
				'nama'			=> $nama_metode,
			);
			if($action == 'edit'){
				$teknik_penilaian = $this->teknik_penilaian->get($id);
				if($teknik_penilaian){
					$this->teknik_penilaian->update($id, $data);
					$this->session->set_flashdata('success', 'Berhasil mengupdate metode penilaian');
				}
			} else {
				$find_teknik_penilaian = $this->teknik_penilaian->find("kompetensi_id = $kompetensi_id AND nama = '$nama_metode'");
				//Metode::find_by_ajaran_id_and_kompetensi_id_and_nama_metode($ajaran_id, $kompetensi_id, $nama_metode);
				if($find_teknik_penilaian){
					$this->session->set_flashdata('error', 'Terdeteksi data metode penilaian dengan data existing');
				} else {
					$this->teknik_penilaian->insert($data);
					$this->session->set_flashdata('success', 'Berhasil menambah data metode penilaian');
				}
			}
			redirect('admin/referensi/metode');
		} elseif($query == 'sikap'){
			$data = array(
				'semester_id' 	=> $semester_id,
				'butir_sikap' 	=> $butir_sikap,
			);
			if($action == 'edit'){
				$get_sikap = $this->sikap->get($id);
				if($get_sikap){
					$this->sikap->update($id, $data);
					$this->session->set_flashdata('success', 'Berhasil mengupdate butir sikap');
				}
			} else {
				$find_sikap = $this->sikap->find("semester_id = $semester_id and butir_sikap = '$butir_sikap'");
				//Datasikap::find_by_;
				if($find){
					$this->session->set_flashdata('error', 'Terdeteksi data sikap dengan data existing');
				} else {
					$this->sikap->insert($data);
					$this->session->set_flashdata('success', 'Berhasil menambah data butir sikap penilaian');
				}
			}
			redirect('admin/referensi/sikap');
		} elseif($query == 'ekskul'){
			$data = array(
				'semester_id' => $semester_id,
				'guru_id' => $guru_id,
				'nama_ekskul' => $nama_ekskul,
				'nama_ketua' => $nama_ketua, 
				'nomor_kontak' => $nomor_kontak, 
				'alamat_ekskul' => $alamat_ekskul, 
			);
			if($action == 'edit'){
				$find_ekstrakurikuler = $this->ekstrakurikuler->get($id);
				if($find_ekstrakurikuler){
					$this->ekstrakurikuler->update($id, $data);
					$this->session->set_flashdata('success', 'Berhasil mengupdate Ekstrakurikuler');
				}
			} else {
				$find = $this->ekstrakurikuler->find("semester_id = $semester_id and guru_id = $guru_id and nama_ekskul = '$nama_ekskul'");
				//Ekskul::find_by_ajaran_id_and_guru_id_and_nama_ekskul($ajaran_id,$guru_id,$nama_ekskul);
				if($find){
					$this->session->set_flashdata('error', 'Terdeteksi Ekstrakurikuler dengan data existing');
				} else {
					$this->ekstrakurikuler->insert($data);
					$this->session->set_flashdata('success', 'Berhasil menambah Ekstrakurikuler');
				}
			}
			redirect('admin/referensi/ekskul');
		} elseif($query == 'add_kd'){
			if($kompetensi_id == 1){
				$aspek = 'P';
			} else {
				$aspek = 'K';
			}
			$find = $this->kompetensi_dasar->find("id_kompetensi = '$kd_id' AND mata_pelajaran_id = $mapel_id AND kelas = $kelas AND aspek = '$aspek'");
			//Kd::find_by_id_kompetensi_and_id_mapel_and_kelas($kd_id,$mapel_id,$kelas);
			if($find){
				$this->session->set_flashdata('error', 'Terdeteksi data Kompetensi Dasar dengan data existing');
			} else {
				$insert_kd = array(
					'id_kompetensi'			=> $kd_id,
					'aspek'					=> $aspek,
					'mata_pelajaran_id'		=> $mapel_id,
					'kelas'					=> $kelas,
					'id_kompetensi_nas'		=> 0,
					'kompetensi_dasar'		=> $kd_uraian,
					'kompetensi_dasar_alias'=> '',
					'user_id'				=> $user_id,
				);
				$this->kompetensi_dasar->insert($insert_kd);
				$this->session->set_flashdata('success', 'Berhasil menambah Kompetensi Dasar');
			}
			redirect('admin/referensi/kd');
		} elseif($query == 'mata_pelajaran_kompetensi'){
			$id = $this->input->post('id');
			$nama_mata_pelajaran = $_POST['nama_mata_pelajaran'];
			$kelompok_id = $this->input->post('kelompok_id');
			$kurikulum_id = $this->input->post('kurikulum_id');
			$tingkat_pendidikan_id = $this->input->post('tingkat_pendidikan_id');
			if($action == 'edit'){
				foreach($tingkat_pendidikan_id as $tingkat_id){
					$get_mata_pelajaran = $this->mata_pelajaran->get($id);
					$update_mata_pelajaran = array(
						'nama_mata_pelajaran'	=> $nama_mata_pelajaran,
						'jurusan_id'			=> $kurikulum_id
					);
					if($this->mata_pelajaran->update($get_mata_pelajaran->id, $update_mata_pelajaran)){
						$get_mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->find_by_mata_pelajaran_id($get_mata_pelajaran->id);
						$attributes_mata_pelajaran_kurikulum = array(
							'kurikulum_id'			=> $kurikulum_id,
							'mata_pelajaran_id'		=> $get_mata_pelajaran->id,
							'tingkat_pendidikan_id'	=> $tingkat_id,
							'kelompok_id'			=> $kelompok_id,
						);
						if($get_mata_pelajaran_kurikulum){
							$this->mata_pelajaran_kurikulum->update($get_mata_pelajaran_kurikulum->id, $attributes_mata_pelajaran_kurikulum);
						}
					}
				}
				$this->session->set_flashdata('success', 'Berhasil memperbaharui referensi mata pelajaran');
			} else {
				$data_mapels = $this->mata_pelajaran->find("nama_mata_pelajaran = '$nama_mata_pelajaran'");
				if($data_mapels){
					$update_mata_pelajaran = array(
						'nama_mata_pelajaran'	=> $nama_mata_pelajaran,
						//'jurusan_id'			=> $kurikulum_id
					);
					$this->mata_pelajaran->update($data_mapels->id, $update_mata_pelajaran);
					foreach($tingkat_pendidikan_id as $tingkat_id){
						$find_mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->find("kurikulum_id = $kurikulum_id and mata_pelajaran_id = $data_mapels->id AND tingkat_pendidikan_id = $tingkat_id");
						$attributes_mata_pelajaran_kurikulum = array(
							'kurikulum_id'			=> $kurikulum_id,
							'mata_pelajaran_id'		=> $data_mapels->id,
							'tingkat_pendidikan_id'	=> $tingkat_id,
							'kelompok_id'			=> $kelompok_id,
						);
						if($find_mata_pelajaran_kurikulum){
							$this->mata_pelajaran_kurikulum->update($find_mata_pelajaran_kurikulum->id, $attributes_mata_pelajaran_kurikulum);
						} else {
							$new_matpel = $this->mata_pelajaran_kurikulum->insert($attributes_mata_pelajaran_kurikulum);
						}
						$this->session->set_flashdata('success', 'Berhasil memperbaharui referensi mata pelajaran');
					}
				} else {
					$attributes_mata_pelajaran= array(
						//'id_mapel'	=> 0,
						'id_nasional'			=> 0,
						'jurusan_id'			=> 0,//$kurikulum_id,
						'nama_mata_pelajaran'	=> $nama_mata_pelajaran,
					);
					$new_mapel = $this->mata_pelajaran->insert($attributes_mata_pelajaran);
					if($new_mapel){
						foreach($tingkat_pendidikan_id as $tingkat_id){
							$attributes_mata_pelajaran_kurikulum = array(
								'kurikulum_id'			=> $kurikulum_id,
								'mata_pelajaran_id'		=> $new_mapel,
								'tingkat_pendidikan_id'	=> $tingkat_id,
								'kelompok_id'			=> $kelompok_id,
							);
							$new_matpel = $this->mata_pelajaran_kurikulum->insert($attributes_mata_pelajaran_kurikulum);
						}
						$this->session->set_flashdata('success', 'Berhasil menambah referensi mata pelajaran');
					} else {
						$this->session->set_flashdata('error', 'Gagal menyimpan mata pelajaran, silahkan coba beberapa saat lagi');
					}
				}
			}
			redirect('admin/referensi/mata_pelajaran');
		} else {
			test($_POST);
		}
	}
	public function list_mata_pelajaran($jurusan = NULL, $tingkat = NULL){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
		//test($jurusan_sp);
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		$where = "AND kurikulum_id IN(SELECT kurikulum_id FROM ref_kurikulum WHERE jurusan_id IN (SELECT jurusan_id FROM jurusan_sp))";
		if($jurusan && $tingkat == NULL){
			//$where = "AND kurikulum_id IN($kurikulum_id)";
			$where = "AND kurikulum_id IN(SELECT kurikulum_id FROM ref_kurikulum WHERE jurusan_id = $jurusan)";
		} elseif($jurusan && $tingkat){
			//$where = "AND kurikulum_id IN($kurikulum_id) AND tingkat_pendidikan_id = $tingkat";
			$where = "AND kurikulum_id IN(SELECT kurikulum_id FROM ref_kurikulum WHERE jurusan_id = $jurusan) AND tingkat_pendidikan_id = $tingkat";
		}
		$cari = "";
		$query = $this->mata_pelajaran_kurikulum->with('mata_pelajaran')->find_all("mata_pelajaran_id IS NOT NULL $where AND (kurikulum_id LIKE '%$search%' OR mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran WHERE nama_mata_pelajaran LIKE '%$search%'))", '*','kurikulum_id asc, tingkat_pendidikan_id asc', $start, $rows);
		$filter = $this->mata_pelajaran_kurikulum->with('mata_pelajaran')->find_count("mata_pelajaran_id IS NOT NULL $where AND (kurikulum_id LIKE '%$search%' OR mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran WHERE nama_mata_pelajaran LIKE '%$search%'))");
		$iFilteredTotal = count($query);
		$iTotal=$filter;
		//count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		$i=$start;
	    foreach ($query as $temp) {
			$record = array();
            $admin_akses = '<li><a href="javascript:void(0)"><i class="fa fa-power-off"></i>Non Aktif</a></li>';
			if($this->ion_auth->is_admin() && !$temp->mata_pelajaran->id_nasional){
				$admin_akses = '<li><a href="'.site_url('admin/referensi/delete/mapel/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>';
			}
			$edit_akses = '<li><a href="'.site_url('admin/referensi/edit_mapel/'.$temp->mata_pelajaran_id).'"><i class="fa fa-pencil"></i>Edit</a></li>';
			if($temp->mata_pelajaran->id_nasional){
				$edit_akses = '';
			}
			$record[] = get_kurikulum($temp->kurikulum_id);
			$record[] = $temp->mata_pelajaran->nama_mata_pelajaran;
            $record[] = $temp->tingkat_pendidikan_id;
            //$record[] = status_label($temp->kelas_11);
            //$record[] = status_label($temp->kelas_12);
            //$record[] = status_label($temp->kelas_13);
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
                                
								 '.$edit_akses.$admin_akses.'
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		echo json_encode($output);
	}
	public function edit_mapel($id){
		$ajaran = get_ta();
		$mata_pelajaran = $this->mata_pelajaran->get($id);
		$mata_pelajaran_id = ($mata_pelajaran) ? $mata_pelajaran->id : 0;
		$mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->with('mata_pelajaran')->find_by_mata_pelajaran_id($mata_pelajaran_id);
		$kurikulum_id = ($mata_pelajaran_kurikulum) ? $mata_pelajaran_kurikulum->kurikulum_id : 0;
		$loggeduser = $this->ion_auth->user()->row();
		$get_kurikulum = $this->kurikulum->find_by_kurikulum_id($kurikulum_id);
		$kurikulum = 0;
		if($get_kurikulum){
			if (strpos($get_kurikulum->nama_kurikulum, 'SMK 2013') !== false) {
				$kurikulum = 2013;
			} elseif (strpos($get_kurikulum->nama_kurikulum, 'SMK 2017') !== false) {
				$kurikulum = 2017;
			} elseif (strpos($get_kurikulum->nama_kurikulum, 'SMK KTSP') !== false) {
				$kurikulum = 2006;
			}
		}
		$get_kelompok = $this->kelompok->find_all_by_kurikulum($kurikulum);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data Kurikulum')
		->set('form_action', 'admin/referensi/simpan')
		->set('data', $mata_pelajaran)
		->set('ajaran', $ajaran)
		->set('get_kelompok', $get_kelompok)
		->set('sekolah_id', $loggeduser->sekolah_id)
		->set('kelompok_id', $mata_pelajaran_kurikulum->kelompok_id)
		//->set('jurusan_id', $mata_pelajaran->jurusan_id)
		//->set('get_kurikulum', $mata_pelajaran->jurusan_id)
		->build($this->admin_folder.'/referensi/_mata_pelajaran');
	}
	public function list_metode(){
		$loggeduser = $this->ion_auth->user()->row();
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		//$query = Metode::find('all', array('conditions' => "ajaran_id IS NOT NULL AND (ajaran_id LIKE '%$search%' OR nama_metode LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id ASC'));
		//$filter = Metode::find('all', array('conditions' => "ajaran_id IS NOT NULL AND (ajaran_id LIKE '%$search%' OR nama_metode LIKE '%$search%')",'order'=>'id ASC'));
		$query = $this->teknik_penilaian->find_all("kompetensi_id IS NOT NULL AND (kompetensi_id IN (SELECT id FROM ref_kompetensi WHERE nama LIKE '%$search%') OR nama LIKE '%$search%')", '*','id desc', $start, $rows);
		$filter = $this->teknik_penilaian->find_all("kompetensi_id IS NOT NULL AND (kompetensi_id IN (SELECT id FROM ref_kompetensi WHERE nama LIKE '%$search%') OR nama LIKE '%$search%')", '*','id desc');
		$iFilteredTotal = count($query);
		
		$iTotal=count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		$i=$start;
		//print_r($query);
	    foreach ($query as $temp) {
			$kompetensi_id = ($temp->kompetensi_id == 1) ? 'Pengetahuan' : 'Keterampilan';
			$record = array();
            $tombol_aktif = '';
			//$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			//$record[] = $temp->ajaran->tahun;
            $record[] = $kompetensi_id;
            $record[] = $temp->nama;
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
								 <li><a href="'.site_url('admin/referensi/edit_metode/'.$temp->id).'" class="toggle-modal"><i class="fa fa-pencil"></i>Edit</a></li>
								 <li><a href="'.site_url('admin/referensi/delete/metode/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		echo json_encode($output);
	}
	public function list_sikap(){
		$loggeduser = $this->ion_auth->user()->row();
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		//$query = Datasikap::find('all', array('conditions' => "id IS NOT NULL AND (butir_sikap LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id ASC'));
		//$filter = Datasikap::find('all', array('conditions' => "id IS NOT NULL AND (butir_sikap LIKE '%$search%')",'order'=>'id ASC'));
		$query = $this->sikap->with('semester')->find_all("semester_id IS NOT NULL AND (semester_id LIKE '%$search%' OR butir_sikap LIKE '%$search%')", '*','id desc', $start, $rows);
		$filter = $this->sikap->with('semester')->find_all("semester_id IS NOT NULL AND (semester_id LIKE '%$search%' OR butir_sikap LIKE '%$search%')", '*','id desc');
		$iFilteredTotal = count($query);
		
		$iTotal=count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		$i=$start;
		//print_r($query);
	    foreach ($query as $temp) {
			$record = array();
			$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			$record[] = $temp->semester->tahun;
			$record[] = $temp->butir_sikap;
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
								 <li><a href="'.site_url('admin/referensi/edit_sikap/'.$temp->id).'" class="toggle-modal"><i class="fa fa-pencil"></i>Edit</a></li>
								 <li><a href="'.site_url('admin/referensi/delete/sikap/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		echo json_encode($output);
	}
	public function edit_metode($id){
		$data['data'] = $this->teknik_penilaian->get($id);
		$this->template->title('Administrator Panel')
		->set_layout($this->modal_tpl)
		->set('page_title', 'Edit Metode')
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_metode',$data);
	}
	public function edit_sikap($id){
		$data['data'] = $this->sikap->get($id);
		$this->template->title('Administrator Panel')
		->set_layout($this->modal_tpl)
		->set('page_title', 'Edit Acuan Sikap')
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_sikap',$data);
	}
	public function excel_kkm(){
		$ajaran = get_ta();
		$join = "INNER JOIN data_gurus b ON(kurikulums.guru_id = b.id)";
		$join .= "INNER JOIN data_rombels c ON(kurikulums.data_rombel_id = c.id AND c.semester_id = $ajaran->id)";
		$join .= "INNER JOIN data_mapels d ON(kurikulums.id_mapel = d.id_mapel)";
		$kurikulum = Kurikulum::find('all', array('joins'=> $join, 'order'=>'kurikulums.data_rombel_id ASC, d.nama_mapel ASC'));
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Direktorak Pembinaan SMK")
				->setLastModifiedBy("eRaporSMK")
				->setTitle("Template KB (KKM)")
				->setSubject("Template KB (KKM)")
				->setDescription("Template KB (KKM)")
				->setKeywords("office 2007 openxml php")
				->setCategory("Template");
		$objPHPExcel->setActiveSheetIndex(0);
		// Rename sheet
		$myCustomWidth = 10;
		$objPHPExcel->getActiveSheet()->setTitle("Template KB (KKM)");
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No.');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'kkm_id');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'kurikulum_id');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'semester_id');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'rombel_id');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'mapel_id');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'guru_id');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Kelas');
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Mata Pelajaran');
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Nama Guru');
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'KKM');
		$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
		$rowIterator = 2;
		$col = 1;			
		foreach($kurikulum as $kur){
			$ajaran = Ajaran::find($kur->semester_id);
			$rombel = Datarombel::find_by_id($kur->data_rombel_id);
			$nama_rombel = isset($rombel->nama) ? $rombel->nama : '-';
			$mapel = Datamapel::find_by_id_mapel($kur->id_mapel);
			$nama_mapel = isset($mapel->nama_mapel) ? $mapel->nama_mapel : '-';
			$guru = Dataguru::find_by_id($kur->guru_id);
			$nama_guru = isset($guru->nama) ? $guru->nama : '-';
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowIterator, $col);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowIterator, $kur->id);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowIterator, $kur->keahlian_id);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowIterator, $kur->semester_id);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowIterator, $kur->data_rombel_id);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowIterator, $kur->id_mapel);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$rowIterator, $kur->guru_id);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowIterator, $nama_rombel);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowIterator, $nama_mapel);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowIterator, $nama_guru);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowIterator, $kur->kkm);
		$col++;
		$rowIterator++;
		}
		$styleArray = array(
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => '00000000'),
							),
						),
					);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.($rowIterator - 1))->applyFromArray($styleArray);
		$filename="Template KKM.xlsx";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
		$objWriter->save('php://output');
	}	
	public function add_kkm(){
		$data['ajarans'] = Ajaran::all();
		$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$data['data_guru'] = Dataguru::all();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data KB (KKM)')
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_kkm',$data);
	}
	public function list_kkm($jurusan = NULL, $tingkat = NULL, $rombel = NULL){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		$guru = '';
		$where = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $find_akses['id'][0];
			$guru = "AND b.id = $guru_id";
		}
		if($jurusan && $tingkat == NULL && $rombel == NULL){
			$where = "AND rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE jurusan_sp_id = $jurusan AND semester_id = $ajaran->id AND sekolah_id = $loggeduser->sekolah_id)";
		} elseif($jurusan && $tingkat && $rombel == NULL){
			$where = "AND rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE jurusan_sp_id = $jurusan AND semester_id = $ajaran->id AND sekolah_id = $loggeduser->sekolah_id AND tingkat = $tingkat)";
		} elseif($jurusan && $tingkat && $rombel){
			$where = "AND rombongan_belajar_id = $rombel";
		}
		$where_search = "mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran WHERE nama_mata_pelajaran LIKE '%$search%') OR rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE nama LIKE '%$search%') OR guru_id IN (SELECT id FROM ref_guru WHERE nama LIKE '%$search%')";
		$query = $this->pembelajaran->find_all("semester_id = $ajaran->id $where AND ($where_search)", '*','id desc', $start, $rows);
		$filter = $this->pembelajaran->find_count("semester_id = $ajaran->id $where AND ($where_search)", '*','id desc');
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		$i=$start;
		//print_r($query);
	    foreach ($query as $temp) {
			//$ajaran = $this->ajaran->get($temp->ajaran_id);
			//$tahun_ajaran = ($ajaran) ? $ajaran->tahun : '-';
			//$admin_akses = '<li><a href="'.site_url('admin/referensi/delete/kkm/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>';
			//if($loggeduser->guru_id){
				//$admin_akses = '';
			//}
			if($temp->kkm){
				$predikat = '<div class="btn-group">';
                      //<button type="button" class="btn btn-danger">Left</button>
                      //<button type="button" class="btn btn-danger">Middle</button>
                      //<button type="button" class="btn btn-danger">Right</button>
				$predikat .= '<a class="btn btn-success btn-sm">A: 100-'.(predikat($temp->kkm,'b') + 1).'</a>';
				$predikat .= '<a class="btn btn-primary btn-sm">B: '.predikat($temp->kkm,'b').'-'.(predikat($temp->kkm,'c') + 1).'</a>';
				$predikat .= '<a class="btn btn-warning btn-sm">C: '.predikat($temp->kkm,'c').'-'.$temp->kkm.'</a>';
				$predikat .= '<a class="btn btn-danger btn-sm">D: '.predikat($temp->kkm,'d').'-0</a>';
				$predikat .= '</div>';
			} else {
				$predikat = '<a class="btn btn-danger btn-sm">0</a>';
			}
			$record = array();
            $tombol_aktif = '';
			//$record[] = $tahun_ajaran;
			$record[] = '<div class="text-center">'.get_nama_rombel($temp->rombongan_belajar_id).'</div>';
			$record[] = get_nama_mapel($temp->mata_pelajaran_id);
            $record[] = get_nama_guru($temp->guru_id);
			$record[] = '<div class="text-center">'.$predikat.'</div>';
            $record[] = '<div class="text-center">'.$temp->kkm.'</div>';
			$record[] = '<div class="text-center"><a class="btn btn-info btn-sm toggle-swal" href="'.site_url('admin/referensi/edit_kkm/'.$temp->id).'">Edit KKM</a></div>';
			$output['aaData'][] = $record;
		}
		if($jurusan && $tingkat){
			$get_all_rombel = $this->rombongan_belajar->find_all("jurusan_sp_id = $jurusan AND semester_id = $ajaran->id AND sekolah_id = $loggeduser->sekolah_id AND tingkat = $tingkat");
			//Datarombel::find_all_by_kurikulum_id_and_tingkat_and_ajaran_id($jurusan, $tingkat, $ajaran->id);
			foreach($get_all_rombel as $allrombel){
				$all_rombel= array();
				$all_rombel['value'] = $allrombel->id;
				$all_rombel['text'] = $allrombel->nama;
				$output['rombel'][] = $all_rombel;
			}
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_mulok($jurusan = NULL, $tingkat = NULL, $rombel = NULL){
		$ajaran = get_ta();
		$ajaran_id = isset($ajaran->id) ? $ajaran->id : 0;
		$loggeduser = $this->ion_auth->user()->row();
		$nama_guru_login = Dataguru::find_by_user_id($loggeduser->id);
		$nama_guru_login = isset($nama_guru_login->nama) ? $nama_guru_login->nama : '';
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		$guru = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){

			$guru_id = $find_akses['id'][0];
			$guru = "AND b.id = $guru_id";
		}
		if($jurusan && $tingkat == NULL && $rombel == NULL){
			$join = "INNER JOIN kurikulum_aliases a ON(kurikulums.id_mapel = a.id AND a.nama_kur = 'k13_mulok' OR kurikulums.id_mapel = a.id AND a.nama_kur = 'k_mulok' OR kurikulums.id_mapel = '850010100')";
			$join .= "INNER JOIN data_gurus b ON(kurikulums.guru_id = b.id $guru)";
			$join .= "INNER JOIN data_rombels c ON(kurikulums.rombel_id = c.id AND c.kurikulum_id= $jurusan)";
			$query = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join, 'group' => 'id'));
			$filter = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')", 'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join));
		} elseif($jurusan && $tingkat && $rombel == NULL){
			$join = "INNER JOIN kurikulum_aliases a ON(kurikulums.id_mapel = a.id AND a.nama_kur = 'k13_mulok' OR kurikulums.id_mapel = a.id AND a.nama_kur = 'k_mulok' OR kurikulums.id_mapel = '850010100')";
			$join .= "INNER JOIN data_gurus b ON(kurikulums.guru_id = b.id $guru)";
			$join .= "INNER JOIN data_rombels c ON(kurikulums.rombel_id = c.id AND c.kurikulum_id= $jurusan AND c.tingkat = $tingkat)";
			$query = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join, 'group' => 'id'));
			$filter = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')", 'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join));
		} elseif($jurusan && $tingkat && $rombel){
			$join = "INNER JOIN kurikulum_aliases a ON(kurikulums.id_mapel = a.id AND a.nama_kur = 'k13_mulok' OR kurikulums.id_mapel = a.id AND a.nama_kur = 'k_mulok' OR kurikulums.id_mapel = '850010100')";
			$join .= "INNER JOIN data_gurus b ON(kurikulums.guru_id = b.id $guru)";
			$join .= "INNER JOIN data_rombels c ON(kurikulums.rombel_id = c.id AND c.kurikulum_id= $jurusan AND c.tingkat = $tingkat  AND c.id = $rombel)";
			$query = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND keahlian_id = $jurusan AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join, 'group' => 'id'));
			$filter = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND keahlian_id = $jurusan AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')", 'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join));
		} else {
			$join = "INNER JOIN kurikulum_aliases a ON(kurikulums.id_mapel = a.id AND a.nama_kur = 'k13_mulok' OR kurikulums.id_mapel = a.id AND a.nama_kur = 'k_mulok' OR kurikulums.id_mapel = '850010100')";
			$join .= "INNER JOIN data_gurus b ON(kurikulums.guru_id = b.id $guru)";
			$join .= "INNER JOIN data_rombels c ON(kurikulums.rombel_id = c.id)";
			$query = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join, 'group' => 'id'));
			$filter = Kurikulum::find('all', array("conditions" => "a.ajaran_id = $ajaran_id AND (nama_mapel_alias LIKE '%$search%' OR b.nama LIKE '%$search%' OR c.nama LIKE '%$search%')", 'order'=>'data_rombel_id ASC, id DESC', 'joins'=> $join));
		}
		$iFilteredTotal = count($query);
		
		$iTotal=count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		//print_r($get_all_rombel);
		$i=$start;
		$admin_group = array(1,2);
	    foreach ($query as $temp) {
			$ajaran = Ajaran::find_by_id($temp->ajaran_id);
			$tahun_ajaran = isset($ajaran->tahun) ? $ajaran->tahun : '-';
			$rombel = Datarombel::find_by_id($temp->rombel_id);
			$nama_rombel = isset($rombel->nama) ? $rombel->nama : '-';
			$guru = Dataguru::find_by_id($temp->guru_id);
			$nama_guru = isset($guru->nama) ? $guru->nama : '-';;
			$record = array();
            $tombol_aktif = '';
			if($this->ion_auth->in_group($admin_group)){
				$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			} else {
				//$record[] = '<div class="text-center">'. $i+1 .'</div>';
				$record[] = '<div class="text-center">'. ($i+1) .'</div>';
			}
			$record[] = $tahun_ajaran;
			$record[] = get_nama_rombel($temp->rombel_id);
			$record[] = get_nama_mapel($temp->ajaran_id, $temp->rombel_id, $temp->id_mapel);
            $record[] = get_nama_guru($temp->guru_id);
            $record[] = '<div class="text-center">'.get_kkm($temp->id).'</div>';
			$record[] = '<div class="text-center"><a class="btn btn-info btn-sm toggle-swal" href="'.site_url('admin/referensi/edit_kkm/'.$temp->id).'">Edit KKM</a></div>';
			$output['aaData'][] = $record;
		$i++;
		}
		if($jurusan && $tingkat){
			$get_all_rombel = Datarombel::find_all_by_kurikulum_id_and_tingkat_and_ajaran_id($jurusan, $tingkat, $ajaran_id);
			foreach($get_all_rombel as $allrombel){
				$all_rombel= array();
				$all_rombel['value'] = $allrombel->id;
				$all_rombel['text'] = $allrombel->nama;
				$output['rombel'][] = $all_rombel;
			}
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_ekskul(){
		$loggeduser = $this->ion_auth->user()->row();
		$semester = get_ta();
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		//$query = Ekskul::find('all', array('conditions' => "ajaran_id IS NOT NULL AND ajaran_id != 0 AND (ajaran_id LIKE '%$search%' OR guru_id LIKE '%$search%' OR nama_ekskul LIKE '%$search%' OR nama_ketua LIKE '%$search%' OR nomor_kontak LIKE '%$search%' OR alamat_ekskul LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id DESC, guru_id asc'));
		//$filter = Ekskul::find('all', array('conditions' => "ajaran_id IS NOT NULL AND ajaran_id != 0 AND (ajaran_id LIKE '%$search%' OR guru_id LIKE '%$search%' OR nama_ekskul LIKE '%$search%' OR nama_ketua LIKE '%$search%' OR nomor_kontak LIKE '%$search%' OR alamat_ekskul LIKE '%$search%')",'order'=>'id DESC, guru_id asc'));
		$query = $this->ekstrakurikuler->find_all("semester_id = $semester->id AND (semester_id LIKE '%$search%' OR guru_id LIKE '%$search%' OR nama_ekskul LIKE '%$search%' OR nama_ketua LIKE '%$search%' OR nomor_kontak LIKE '%$search%' OR alamat_ekskul LIKE '%$search%')", '*','id desc', $start, $rows);
		$filter = $this->ekstrakurikuler->find_count("semester_id = $semester->id AND (semester_id LIKE '%$search%' OR guru_id LIKE '%$search%' OR nama_ekskul LIKE '%$search%' OR  nama_ketua LIKE '%$search%' OR nomor_kontak LIKE '%$search%' OR alamat_ekskul LIKE '%$search%')");
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		$i=$start;
		//print_r($query);
	    foreach ($query as $temp) {
			$record = array();
            $tombol_aktif = '';
			$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			//$record[] = $temp->ajaran->tahun;
			$record[] = $temp->nama_ekskul;
            $record[] = get_nama_guru($temp->guru_id);
			$record[] = $temp->nama_ketua;
			$record[] = $temp->nomor_kontak;
			$record[] = $temp->alamat_ekskul;
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
								 <li><a href="'.site_url('admin/referensi/edit_ekskul/'.$temp->id).'"><i class="fa fa-pencil"></i>Edit</a></li>
								 <li><a href="'.site_url('admin/referensi/delete/ekskul/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		echo json_encode($output);
	}
	public function edit_kkm($id){
		$pembelajaran = $this->pembelajaran->get($id);
		if($_POST && $pembelajaran){
			$kkm = array('kkm'=> $_POST['kkm']);
			$this->pembelajaran->update($id, $kkm);
		} else {
			$kkm = array('kkm'=> $pembelajaran->kkm);
		}
		echo json_encode($kkm);
	}
	public function add_mulok(){
		$super_admin = array(1,2,5);
		if($this->ion_auth->in_group($super_admin)){
			$data['ajarans'] = Ajaran::all();
			$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
			$data['data_guru'] = Dataguru::all();
			$this->template->title('Administrator Panel')
			->set_layout($this->admin_tpl)
			->set('page_title', 'Tambah Data Muatan Lokal')
			->set('form_action', 'admin/referensi/simpan')
			->build($this->admin_folder.'/referensi/_mulok',$data);
		} else {
			$this->session->set_flashdata('error', 'Akses menambah data muatan lokal hanya untuk Administrator dan atau Waka Kurikulum');
			redirect('admin/referensi/mulok');
		}
	}
	public function add_ekskul(){
		$ajaran = get_ta();
		$data['data_guru'] = $this->guru->find_all('', '*', 'nama ASC');
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data Ekstrakurikuler')
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_ekstrakurikuler',$data);
	}
	public function edit_ekskul($id){
		$ekskul = $this->ekstrakurikuler->get($id);
		$data['data_guru'] = $this->guru->find_all('', '*', 'nama ASC');
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Edit Ekstrakurikuler')
		->set('data', $ekskul)
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_ekstrakurikuler',$data);
	}
	/*public function delete_kurikulum($id){
		echo 'delete_kurikulum';
	}
	public function delete_kkm($id){
		echo 'delete_kkm';
	}
	public function delete_mulok($id){
		echo 'delete_mulok';
	}
	public function delete_ekskul($id){
		echo 'delete_ekskul';
	}*/
	public function get_mapel($segment_name,$rombel_id){
		$data_rombel = Datarombel::find($rombel_id);
		$tingkat = $data_rombel->tingkat;
		$kurikulum_id = $data_rombel->kurikulum_id;
		$kelas_X	= 0;
		$kelas_XI	= 0;
		$kelas_XII	= 0;
		$kelas_XIII	= 0;
		$k1 = 0;
		$k2 = 0;
		$k3 = 0;
		if($tingkat == 10){
			$kelas_X	= 1;
			$kelas_XI	= 0;
			$kelas_XII	= 0;
			$kelas_XIII	= 0;
			$k1			= 1;
		} elseif($tingkat == 11){
			$kelas_X	= 1;
			$kelas_XI	= 1;
			$k2			= 1;
		} elseif($tingkat == 12){
			$kelas_X	= 1;
			$kelas_XI	= 1;
			$kelas_XII	= 1;
			$kelas_XIII	= 0;
			$k3			= 1;
		} elseif($tingkat == 13){
			$kelas_X	= 1;
			$kelas_XI	= 1;
			$kelas_XII	= 1;
			$kelas_XIII	= 1;
		}
		//$all_mapel = Matpelkomp::find_all_by_kelas_X_and_kelas_XI_and_kelas_XII_and_kelas_XIII($kelas_X,$kelas_XI,$kelas_XII,$kelas_XIII);
		$matpel_komp = Matpelkomp::find('all', array('conditions' => array('kelas_X = ? AND kelas_XI = ? AND kelas_XII = ? AND kelas_XIII = ? AND kurikulum_id = ?', $kelas_X,$kelas_XI,$kelas_XII,$kelas_XIII, $kurikulum_id)));
		$matpel_umum = Datamapel::find('all', array('conditions' => array('kur = ?', '2013')));
		if(is_array($matpel_komp) && is_array($matpel_umum)){
			$all_mapel = array_merge($matpel_komp,$matpel_umum);
		} elseif(is_array($matpel_komp) && !is_array($matpel_umum)){
			$all_mapel = $matpel_komp;
		} elseif(is_array($matpel_komp) && !is_array($matpel_umum)){
			$all_mapel = $matpel_umum;
		}
		//print_r($all_mapel);
		//die();
		foreach($all_mapel as $mapel){
			$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
			$record['value'] 	= $mapel->id_mapel; //array("value" => $mapel->id_mapel, "property" => $data_mapel->nama_mapel.' ('.$data_mapel->id_mapel.')');
			$record['property']	= $data_mapel->nama_mapel.' ('.$data_mapel->id_mapel.')';
			$records[] = $record;
		}
		if(isset($record)){
			$output = $records;
		} else {
			$output = array("value" => '0', "property" => 'Belum ada mata pelajaran di rombel terpilih');
		}
		echo json_encode($output);
	}
	public function get_kurikulum(){
		if($_POST){
			$id_mapel = $_POST['id_mapel'];
			$data_mapel = Datamapel::find_by_id_mapel($id_mapel);
			echo $data_mapel->kur;
		}
	}
	public function mulok(){
		$pilih_rombel = '<a href="'.site_url('admin/referensi/add_mulok').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Muatan Lokal')
		->set('pilih_rombel', $pilih_rombel)
		->build($this->admin_folder.'/referensi/mulok');
	}
	public function ekskul(){
		$pilih_rombel = '<div class="col-md-3">&nbsp;</div><div class="col-md-3">&nbsp;</div><a href="'.site_url('admin/referensi/add_ekskul').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('pilih_rombel', $pilih_rombel)
		->set('page_title', 'Referensi Ektrakurikuler')
		->build($this->admin_folder.'/referensi/ekstrakurikuler');
	}
	public function kd(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/referensi/add_kd').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Referensi Kompetensi Dasar')
		->set('pilih_rombel', $pilih_rombel)
		->set('ajaran', $ajaran)
		->set('user', $loggeduser)
		->build($this->admin_folder.'/referensi/kompetensi_dasar');
	}
	public function add_kd($kompetensi_id = NULL, $rombel_id = NULL, $mapel_id = NULL, $tingkat = NULL){
		$ajaran = get_ta();
		$ajaran_id = isset($ajaran->id) ? $ajaran->id : '';
		$loggeduser = $this->ion_auth->user()->row();
		$data_guru = $this->guru->find_by_user_id($loggeduser->id);
		if($data_guru){
			$get_all_mapel = $this->pembelajaran->find_all("guru_id = $data_guru->id AND semester_id = $ajaran_id");
			//Kurikulum::find('all', array('conditions' => array("guru_id = ?",$data_guru->id),'group' => 'id_mapel'));
		} else {
			$get_all_mapel = $this->pembelajaran->find_all("semester_id = $ajaran_id");
			//Kurikulum::find('all', array('conditions' => array("ajaran_id = ?",$ajaran_id)));
		}
		if($kompetensi_id)
		$data['kompetensi_id'] = $kompetensi_id;
		if($rombel_id)
		$data['rombel_id'] = $rombel_id;
		if($mapel_id)
		$data['mapel_id'] = $mapel_id;
		if($tingkat)
		$data['tingkat'] = $tingkat;
		$data['sekolah_id'] = $loggeduser->sekolah_id;
		//$data['ajarans'] = Ajaran::all();
		$data['rombels'] = $this->tingkat_pendidikan->get_all();
		//Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data Kompetensi Dasar')
		->set('form_action', 'admin/referensi/simpan')
		->set('all_mapel', $get_all_mapel)
		->set('user_id', $loggeduser->id)
		->build($this->admin_folder.'/referensi/_kd',$data);
	}
	public function list_kd($jurusan = NULL, $tingkat = NULL, $mapel = NULL, $kompetensi = NULL){
		$ajaran = get_ta();
		$ajaran_id = $ajaran->id;
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		$join = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			//$join = "AND guru_id = $loggeduser->guru_id";
			$join = "AND mata_pelajaran_id IN (SELECT mata_pelajaran_id FROM pembelajaran WHERE guru_id = $loggeduser->guru_id AND semester_id = $ajaran->id)";
		}
		if($jurusan && $tingkat == NULL && $mapel == NULL && $kompetensi == NULL){
			//$join = "AND mata_pelajaran_id IN (SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kurikulum_id IN(SELECT kurikulum_id FROM ref_kurikulum WHERE jurusan_id = $jurusan))";
		} elseif($jurusan && $tingkat && $mapel == NULL && $kompetensi == NULL){
			//$join = "AND mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran WHERE jurusan_id = $jurusan) AND rombel_id IN(SELECT id FORM ref_rombongan_belajar WHERE tingkat = $tingkat)";
		} elseif($jurusan == 0 && $tingkat == 0 && $mapel && $kompetensi == NULL){
			$join = "AND mata_pelajaran_id = $mapel";
		} elseif($jurusan == 0 && $tingkat == 0 && $mapel && $kompetensi){
			$join = "AND mata_pelajaran_id = $mapel AND aspek = '$kompetensi'";
			//$join = "AND mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran WHERE jurusan_id = $jurusan) AND rombel_id IN(SELECT id FORM ref_rombongan_belajar WHERE tingkat = $tingkat AND mata_pelajaran_id = $rombel) AND aspek = '$kompetensi'";
		}
		//echo $join;
		$query = $this->kompetensi_dasar->find_all("id_kompetensi IS NOT NULL $join AND (mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran WHERE nama_mata_pelajaran LIKE '%$search%') OR kompetensi_dasar LIKE '%$search%')", '*','kelas asc', $start, $rows);
		$filter = $this->kompetensi_dasar->find_count("id_kompetensi IS NOT NULL $join AND (mata_pelajaran_id IN (SELECT id FROM ref_mata_pelajaran WHERE nama_mata_pelajaran LIKE '%$search%') OR kompetensi_dasar LIKE '%$search%')", '*','kelas asc');
		$iFilteredTotal = $query;
		
		$iTotal=$filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		$i=$start;
		//print_r($query);
	    foreach ($query as $temp) {
			$kompetensi_dasar_alias = ($temp->kompetensi_dasar_alias) ? $temp->kompetensi_dasar_alias : $temp->kompetensi_dasar;
			$record = array();
            $tombol_aktif = '';
			//$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			$record[] = get_nama_mapel($temp->mata_pelajaran_id).'('.$temp->mata_pelajaran_id.')';
			$record[] = '<div class="text-center">'.$temp->id_kompetensi.'</div>';
			$record[] = '<div class="text-center">'.$temp->kelas.'</div>';
            $record[] = $temp->kompetensi_dasar;
            $record[] = $kompetensi_dasar_alias;
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
								 <li><a href="'.site_url('admin/referensi/edit_kd/'.$temp->id).'" class="toggle-modal tooltip-left" title="Tambah/Ubah Ringkasan Kompetensi"><i class="fa fa-pencil"></i>Edit</a></li>
								 <li><a href="'.site_url('admin/referensi/delete/kd/'.$temp->id).'" class="confirm tooltip-left" title="Hapus Ringkasan Kompetensi"><i class="fa fa-power-off"></i>Hapus</a></li>
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		if($jurusan && $tingkat){
			$nama_mapel = '';
			$komp = array(
							1=> array(
									'id' 	=> 'P',
									'name' 	=> 'Pengetahuan'
								),
							2=> array(
									'id'	=> 'K',
									'name'	=> 'Keterampilan'
								)
						);
			for ($i = 1; $i <= 2; $i++) {
				$result['value']	= $komp[$i]['id'];
				$result['text']		= $komp[$i]['name'];
				$output['kompetensi'][] = $result;
			}
			if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
				$get_all_mapel = $this->pembelajaran->find_all("semester_id = $ajaran->id AND guru_id = $loggeduser->guru_id AND rombongan_belajar_id IN(SELECT id FROM rombongan_belajar WHERE tingkat = $tingkat)");
			} else {
				$get_all_mapel = $this->pembelajaran->find_all("semester_id = $ajaran->id AND rombongan_belajar_id IN(SELECT id FROM rombongan_belajar WHERE tingkat = $tingkat)");
			}
			if($get_all_mapel){
				foreach($get_all_mapel as $allmapel){
					$all_mapel= array();
					$all_mapel['value'] = $allmapel->id;
					$all_mapel['text'] = get_nama_mapel($allmapel->mata_pelajaran_id);
					$output['rombel'][] = $all_mapel;
				}
			} else {
				$result['value'] = '';
				$result['text'] = 'Belum ada mata pelajaran di kelas terpilih.';
				$output['rombel'][] = $result;
			}
		}
		if($mapel){
			$komp = array(
							1=> array(
									'id' 	=> 'P',
									'name' 	=> 'Pengetahuan'
								),
							2=> array(
									'id'	=> 'K',
									'name'	=> 'Keterampilan'
								)
						);
			for ($i = 1; $i <= 2; $i++) {
				$result['value']	= $komp[$i]['id'];
				$result['text']		= $komp[$i]['name'];
				$output['kompetensi'][] = $result;
			}
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function edit_kd($id){
		$loggeduser = $this->ion_auth->user()->row();
		$kd = $this->kompetensi_dasar->get($id);
		if($_POST && $kd){
			if($loggeduser->id == $kd->user_id){
				$id_kompetensi 			= $this->input->post('id_kompetensi');
				$mapel_id 				= $this->input->post('mapel_id');
				$kelas 					= $this->input->post('kelas');
				$aspek 					= $this->input->post('aspek');
				$kompetensi_dasar 		= $this->input->post('kompetensi_dasar');
				$kompetensi_dasar_alias = $this->input->post('kompetensi_dasar_alias');
				$find = $this->kompetensi_dasar->find("id_kompetensi = '$id_kompetensi' AND mata_pelajaran_id = $mapel_id AND kelas = $kelas AND aspek = '$aspek' AND id != $id");
				if($find){
					$status['type'] = 'error';
					$status['text'] = 'Kompetensi dasar gagal disimpan dengan data existing';
					$status['title'] = 'Akses Ditolak!';
				} else {
					$update_kd = array(
						'id_kompetensi' 			=> $id_kompetensi,
						'kompetensi_dasar' 			=> $kompetensi_dasar,
						'kompetensi_dasar_alias' 	=> $kompetensi_dasar_alias,
					);
					if($this->kompetensi_dasar->update($id, $update_kd)){
						$status['type'] = 'success';
						$status['text'] = 'Kompetensi dasar berhasil diperbaharui';
						$status['title'] = 'Berhasil!';
					} else {
						$status['type'] = 'error';
						$status['text'] = 'Kompetensi dasar gagal disimpan. Silahkan coba beberapa saat lagi';
						$status['title'] = 'Gagal!';
					}
				}
				echo json_encode($status);
			} else {
				$this->kompetensi_dasar->update($id,
					array(
						'kompetensi_dasar_alias' => $_POST['kompetensi_dasar_alias'],
					)
				);
			}
		} else {
			$this->template->title('Administrator Panel : Tambah/Ubah Alias Kompetensi Dasar')
			->set_layout($this->modal_tpl)
			->set('page_title', 'Tambah/Ubah Alias Kompetensi Dasar')
			->set('kd', $kd)
			->set('user_id', $loggeduser->id)
			->set('modal_footer', '<a class="btn btn-primary" id="button_form" href="javascript:void(0);">Update</a>')
			->build($this->admin_folder.'/referensi/alias');
		}
	}
	public function edit_mulok($id){
		$mulok = Mulok::find($id);
		$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$data['data_guru'] = Dataguru::all();
		$data['data_rombel'] = Datarombel::find_by_id($mulok->rombel_id);
		$all_rombel = Datarombel::all();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Edit Muatan Lokal')
		->set('data', $mulok)
		->set('all_rombel', $all_rombel)
		->set('form_action', 'admin/referensi/simpan')
		->build($this->admin_folder.'/referensi/_mulok',$data);
	}
	public function delete_kd($id){
		$kd = Kd::find_by_id($id);
		$kd->update_attributes(
				array(
					'kompetensi_dasar_alias' => '',
				)
			);
	}
	public function delete($query,$id){
		$super_admin = array(1,2,3);
		if($this->ion_auth->in_group($super_admin)){
			if($query == 'metode'){
				$this->teknik_penilaian->delete($id);
			}elseif($query == 'kurikulum'){
				$data = Kurikulum::find($id);
				$data->delete();
			} elseif($query == 'kkm'){
				$data = Kkm::find($id);
				$data->delete();
			} elseif($query == 'kd'){
				$loggeduser = $this->ion_auth->user()->row();
				$data = $this->kompetensi_dasar->get($id);
				if($data){
					//if($loggeduser->id == $data->user_id){
						//$this->kompetensi_dasar->delete($id);
					//} else {
						$this->kompetensi_dasar->update($id,
							array(
								'kompetensi_dasar_alias' => '',
							)
						);
					//}
				}
			} elseif($query == 'mulok'){
				$data = Mulok::find($id);
				$data->delete();
			} elseif($query == 'sikap'){
				$this->sikap->delete($id);
			} elseif($query == 'ekskul'){
				$data = Ekskul::find($id);
				$data->delete();
			} elseif($query == 'mapel'){
				$find_matpel = $this->mata_pelajaran_kurikulum->get($id);
				if($find_matpel){
					//test($find_matpel);
					//$data = $this->mata_pelajaran->get($find_matpel->mata_pelajaran_id);
					$this->mata_pelajaran_kurikulum->delete($id);
					//$this->mata_pelajaran->delete($find_matpel->mata_pelajaran_id);
					//test($data);
					//if($data){
						//$data->delete();
					//}
					//$find_matpel->delete();
				}
			}
			$status['type'] = 'success';
			$status['text'] = 'Data '.$query.' berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data '.$query.' tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
	public function multidelete($q){
		$ids = $_POST['id'];
		$super_admin = array(1,2);
		if($q == 'metode'){
			$data = $q::find($ids);
			if(is_array($data)){
				foreach($data as $d){
					$d->delete();
				}
			} else {
				$data->delete();
			}
			$status['type'] = 'success';
			$status['text'] = 'Data '.$q.' berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} elseif($q == 'kkm'){
			$data = Kurikulum::find($ids);
			if(is_array($data)){
				foreach($data as $d){
					$d->update_attributes(
							array(
								'kkm' => 0,
							)
						);
				}
			} else {
				$data->update_attributes(
							array(
								'kkm' => 0,
							)
						);
			}
			$status['type'] = 'success';
			$status['text'] = 'Data '.$q.' berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} elseif($q == 'kd'){
			$data = Kd::find($ids);
			if(is_array($data)){
				foreach($data as $d){
					if($d->id_kompetensi_nas === NULL){
						$d->update_attributes(
							array(
								'kompetensi_dasar_alias' => NULL,
							)
						);
					} else {
						$d->delete();
					}
				}
			} else {
				if($data->id_kompetensi_nas === NULL){
					$data->update_attributes(
						array(
							'kompetensi_dasar_alias' => NULL,
						)
					);
				} else {
					$data->delete();
				}
			}
			$status['type'] = 'success';
			$status['text'] = 'Data '.$q.' berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} elseif($this->ion_auth->in_group($super_admin)){
			$status['type'] = 'error';
			$status['text'] = 'Data '.$q.' tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data '.$q.' tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
	/*==================== referensi kompetensi_keahlian =======================*/
	public function list_referensi(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
		//test($jurusan_sp);
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		// limit
		$start = get_start();
		$rows = get_rows();
		$query = $this->jurusan_sp->find_all("sekolah_id = $loggeduser->sekolah_id AND (kurikulum_id LIKE '%$search%')", '*','kurikulum_id asc, id asc', $start, $rows);
		//$filter = $this->mata_pelajaran_kurikulum->with('mata_pelajaran')->find_all("mata_pelajaran_id IS NOT NULL AND (kurikulum_id LIKE '%$search%' OR mata_pelajaran_id LIKE '%$search%')", '*','id desc');
		$filter = $this->jurusan_sp->find_count("sekolah_id = $loggeduser->sekolah_id AND (kurikulum_id LIKE '%$search%')");
		$iFilteredTotal = count($query);
		$iTotal=$filter;
		//count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		$i=$start;
	    foreach ($query as $temp) {
			$record = array();
            $admin_akses = '';
			if($this->ion_auth->is_admin()){
				//$admin_akses = '<li><a href="'.site_url('admin/referensi/delete/mapel/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>';
			}
			$get_kompetensi_keahlian = $this->jurusan->find_by_jurusan_id($temp->kurikulum_id);
			$kompetensi_keahlian_id = ($get_kompetensi_keahlian) ? $get_kompetensi_keahlian->jurusan_induk : 0;
			$get_program_keahlian = $this->jurusan->find_by_jurusan_id($kompetensi_keahlian_id);
			$program_keahlian_id = ($get_program_keahlian) ? $get_program_keahlian->jurusan_induk : 0;
			$get_bidang_keahlian = $this->jurusan->find_by_jurusan_id($program_keahlian_id);
			$bidang_keahlian_id = ($get_bidang_keahlian) ? $get_bidang_keahlian->jurusan_induk : 0;
			//test($get_bidang_keahlian);
			//test($kompetensi_keahlian_id);
			//test($get_bidang_keahlian);
			$record[] = get_jurusan($program_keahlian_id). ' ('.$program_keahlian_id.')';
            $record[] = get_jurusan($kompetensi_keahlian_id). ' ('.$kompetensi_keahlian_id.')';
			$record[] = get_jurusan($temp->kurikulum_id). ' ('.$temp->kurikulum_id.')';
            //$record[] = status_label($temp->kelas_11);
            //$record[] = status_label($temp->kelas_12);
            //$record[] = status_label($temp->kelas_13);
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
                                <li><a href="'.site_url('admin/referensi/edit_mapel/'.$temp->id).'"><i class="fa fa-pencil"></i>Edit</a></li>
								 '.$admin_akses.'
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		echo json_encode($output);
	}
}