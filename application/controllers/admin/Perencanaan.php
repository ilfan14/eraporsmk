<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Perencanaan extends Backend_Controller {
	protected $activemenu = 'perencanaan';
	public function __construct() {
		parent::__construct();
		$this->template->set('activemenu', $this->activemenu);
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
	}
	public function pengetahuan(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/perencanaan/add_pengetahuan').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Perencanaan Penilaian Pengetahuan')
		->set('pilih_rombel', $pilih_rombel)
		->set('ajaran', $ajaran)
		->set('sekolah_id', $loggeduser->sekolah_id)
		->build($this->admin_folder.'/perencanaan/list_rencana');
	}
	public function add_pengetahuan(){
		$loggeduser = $this->ion_auth->user()->row();
		if($loggeduser->guru_id){
			$data_mapel = $this->pembelajaran->find_all_by_guru_id($loggeduser->guru_id);
			foreach($data_mapel as $datamapel){
				$rombel_id[] = $datamapel->rombongan_belajar_id;
			}
			if(isset($rombel_id)){
				$id_rombel = $rombel_id;
			} else {
				$id_rombel = array(0);
			}
			$id_rombel = array_unique($id_rombel);
			$id_rombel = implode(',',$id_rombel);
			$data['rombels'] = $this->rombongan_belajar->find_all("id IN ($id_rombel)");
		} else {
			$data['rombels'] = $this->rombongan_belajar->get_all();
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/perencanaan/simpan_perencanaan')
		->set('page_title', 'Perencanaan Penilaian Pengetahuan')
		->set('query', 'kd')
		->set('kompetensi_id', 1)
		->build($this->admin_folder.'/perencanaan/add_perencanaan',$data);
	}
	public function keterampilan(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/perencanaan/add_keterampilan').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Perencanaan Penilaian Keterampilan')
		->set('pilih_rombel', $pilih_rombel)
		->set('ajaran', $ajaran)
		->set('sekolah_id', $loggeduser->sekolah_id)
		->build($this->admin_folder.'/perencanaan/list_rencana');
	}
	public function add_keterampilan(){
		$loggeduser = $this->ion_auth->user()->row();
		if($loggeduser->guru_id){
			$data_mapel = $this->pembelajaran->find_all_by_guru_id($loggeduser->guru_id);
			foreach($data_mapel as $datamapel){
				$rombel_id[] = $datamapel->rombongan_belajar_id;
			}
			if(isset($rombel_id)){
				$id_rombel = $rombel_id;
			} else {
				$id_rombel = array(0);
			}
			$id_rombel = array_unique($id_rombel);
			$id_rombel = implode(',',$id_rombel);
			$data['rombels'] = $this->rombongan_belajar->find_all("id IN ($id_rombel)");
		} else {
			$data['rombels'] = $this->rombongan_belajar->get_all();
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/perencanaan/simpan_perencanaan')
		->set('page_title', 'Perencanaan Penilaian Keterampilan')
		->set('query', 'kd')
		->set('kompetensi_id', 2)
		->build($this->admin_folder.'/perencanaan/add_perencanaan',$data);
	}
	public function simpan_perencanaan(){
		if($_POST){
			//test($_POST);
			//die();
			$kompetensi_id		= $_POST['kompetensi_id'];
			$ajaran_id			= $_POST['ajaran_id'];
			$rombel_id			= $_POST['rombel_id'];
			$id_mapel			= $_POST['id_mapel'];
			$nama_penilaian		= $_POST['nama_penilaian'];
			$bentuk_penilaian	= $_POST['bentuk_penilaian'];
			$bobot_penilaian	= isset($_POST['bobot_penilaian']) ? $_POST['bobot_penilaian'] : '';
			$keterangan_penilaian	= $_POST['keterangan_penilaian'];
			if($kompetensi_id == 1){
				$redirect = 'pengetahuan';
			} else {
				$redirect = 'keterampilan';
			}
			$nama_penilaian = array_filter($nama_penilaian);
			foreach($nama_penilaian as $k=>$v) {
				$i = $k + 1;
				$kds		= $_POST['kd_'.$i];
				$data_insert_rencana = array(
					'semester_id'			=> $ajaran_id,
					'mata_pelajaran_id' 	=> $id_mapel,
					'rombongan_belajar_id'	=> $rombel_id,
					'kompetensi_id'			=> $kompetensi_id,
					'nama_penilaian'		=> $nama_penilaian[$k],
					'metode_id'				=> $bentuk_penilaian[$k],
					'bobot'					=> $bobot_penilaian[$k],
					'keterangan'			=> $keterangan_penilaian[$k],
				);
				//test($data_insert_rencana);
				$insert_rencana = $this->rencana_penilaian->insert($data_insert_rencana);
				if($insert_rencana){
					foreach($kds as $kd){
						$get_post_kd = explode("|", $kd);
						$insert_kd_nilai = array(
							'rencana_penilaian_id' 	=> $insert_rencana,
							'id_kompetensi' 		=> $get_post_kd[0],
							'kd_id' 				=> $get_post_kd[1],
						);
						$this->kd_nilai->insert($insert_kd_nilai);
					}
				}
			}
			$this->session->set_flashdata('success', 'Berhasil menambah rencana penilaian '.$redirect);
			redirect('admin/perencanaan/'.$redirect);
		}
	}
	public function update_perencanaan(){
		if($_POST){
			$rencana_id			= $_POST['rencana_id'];
			$update_rencana_penilaian = array(
				'semester_id'		=> $_POST['ajaran_id'],
				'mata_pelajaran_id' => $_POST['id_mapel'],
				'rombel_id'			=> $_POST['rombel_id'],
				'kompetensi_id'		=> $_POST['kompetensi_id'],
				'nama_penilaian'	=> $_POST['nama_penilaian'],
				'metode_id'			=> $_POST['bentuk_penilaian'],
				'bobot'				=> $_POST['bobot_penilaian'],
				'keterangan'		=> $_POST['keterangan_penilaian'],
			);
			if($_POST['kompetensi_id'] == 1){
				$redirect = 'pengetahuan';
			} else {
				$redirect = 'keterampilan';
			}
			$find_rencana_penilaian = $this->rencana_penilaian->get($rencana_id);
			if($find_rencana_penilaian){
				//$this->rencana_penilaian->update($rencana_id, $update_rencana_penilaian);
			}
			//test($_POST);
			$kds = $_POST['kd'];
			//test($kds);
			$kds_implode = implode(',',$kds);
			$find_kd_nilai = $this->kd_nilai->find_all("rencana_penilaian_id = $rencana_id");
			if($find_kd_nilai){
				foreach($find_kd_nilai as $k => $del_kd){
					$post_kd = isset($kds[$k]) ? $kds[$k] : 0;
					if($del_kd->kd_id != $post_kd){
						//echo 'delete:'.$del_kd->id;
						$nilai = $this->nilai->get_by_kd_nilai_id($del_kd->id);
						if($nilai){	
							$this->nilai->delete($nilai->id);
						}
						$this->kd_nilai->delete($del_kd->id);
					}
				}
			}
			foreach($kds as $key => $kd){
				$find_kd_nilai = $this->kd_nilai->find("rencana_penilaian_id = $rencana_id AND kd_id = $kd");
				if(!$find_kd_nilai){
					$get_kd = $this->kompetensi_dasar->get($kd);
					$insert_kd_nilai = array(
						'rencana_penilaian_id' 	=> $rencana_id,
						'id_kompetensi' 		=> $get_kd->id_kompetensi,
						'kd_id' 				=> $kd,
					);
					$this->kd_nilai->insert($insert_kd_nilai);
					//echo 'insert kd_nilai kd_id = '.$kd.' id_kompetensi = '.$get_kd->id_kompetensi;
				}
			}
			//die();
			$this->session->set_flashdata('success', 'Berhasil mengupdate rencana penilaian '.$redirect);
			redirect('admin/perencanaan/'.$redirect);
		}
	}
    public function list_pengetahuan($jurusan = NULL, $tingkat = NULL, $rombel = NULL){
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 25;
		$ajaran = get_ta();
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		$where = '';
		$join = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $loggeduser->guru_id;
			$where = "AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM pembelajaran WHERE guru_id = $guru_id)";
		}
		if($jurusan && $tingkat == NULL && $rombel == NULL){
			$join = "AND rombongan_belajar_id IN (SELECT id FROM ref_rombongan_belajar WHERE kurikulum_id = $jurusan)";
		}elseif($jurusan && $tingkat && $rombel == NULL){
			$join = "AND rombongan_belajar_id IN (SELECT id FROM ref_rombongan_belajar WHERE kurikulum_id = $jurusan) AND rombongan_belajar_id IN (SELECT id FROM ref_rombongan_belajar WHERE tingkat = $tingkat)";
		} elseif($jurusan && $tingkat && $rombel){
			$join = "AND rombongan_belajar_id = $rombel";
		}
		$query = $this->rencana_penilaian->with('mata_pelajaran')->with('rombongan_belajar')->find_all("semester_id = $ajaran->id AND kompetensi_id = 1 $join $where AND (mata_pelajaran_id LIKE '%$search%' OR rombongan_belajar_id LIKE '%$search%' OR nama_penilaian LIKE '%$search%')", '*','id desc', $start, $rows);
		$filter = $this->rencana_penilaian->with('mata_pelajaran')->with('rombongan_belajar')->find_all("semester_id = $ajaran->id AND kompetensi_id = 1 $join $where AND (mata_pelajaran_id LIKE '%$search%' OR rombongan_belajar_id LIKE '%$search%' OR nama_penilaian LIKE '%$search%')", '*','id desc');
		$iFilteredTotal = count($query);
		
		$iTotal=count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
	    // get result after running query and put it in array
		$i=$start;
	    foreach ($query as $temp) {
			$nilai = $this->nilai->find("rencana_penilaian_id = $temp->id");
			if(!in_array('waka',$nama_group)){ //murni guru
				if($nilai){
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				} else {
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/edit/1/'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
					$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				}
			} else { // guru plus waka
				$admin_akses = '<li><a href="'.site_url('admin/perencanaan/view/'.$temp->id).'" class="toggle-modal"><i class="fa fa-eye"></i> Detil</a></li>';
				if($loggeduser->guru_id == get_guru_mapel($temp->semester_id, $temp->rombongan_belajar_id, $temp->mata_pelajaran_id, 'id')){
					if($nilai){
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					} else {
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/edit/1/'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					}
				}
			}
			$jumlah_kd_nilai = $this->kd_nilai->find_count("rencana_penilaian_id = $temp->id");
			//count($temp->rencanapenilaian);
			$record = array();
            $tombol_aktif = '';
			$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			$record[] = $temp->mata_pelajaran->nama_mata_pelajaran;
			$record[] = get_nama_rombel($temp->rombongan_belajar_id);
			$record[] = $temp->nama_penilaian;
            $record[] = '<div class="text-center">'.get_teknik_penilaian($temp->metode_id).'</div>';
			$record[] = $temp->bobot;
            $record[] = '<div class="text-center">'.$jumlah_kd_nilai.'</div>';
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
								 '.$admin_akses.'
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		if($jurusan && $tingkat){
			if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
				$guru_id = $loggeduser->guru_id;
				//$join = "INNER JOIN kurikulums a ON(data_rombels.id = a.data_rombel_id AND a.guru_id = $guru_id)";
				$get_all_rombel = $this->rombongan_belajar->find_all("kurikulum_id = $jurusan AND tingkat = $tingkat AND ajaran_id = $ajaran->id");
				//Datarombel::find('all', array('conditions' => "kurikulum_id = $jurusan AND tingkat = $tingkat AND data_rombels.ajaran_id = $ajaran->id", 'joins'=> $join));
			} else {
				$get_all_rombel = $this->rombongan_belajar->find_all("kurikulum_id = $jurusan AND tingkat = $tingkat AND ajaran_id = $ajaran->id");
				//Datarombel::find_all_by_kurikulum_id_and_tingkat_and_ajaran_id($jurusan,$tingkat,$ajaran->id);
			}
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
	public function list_keterampilan($jurusan = NULL, $tingkat = NULL, $rombel = NULL){
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 25;
		$ajaran = get_ta();
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		$join_rombel = '';
		$join_mapel = '';
		$id_rombel = '';
		$id_mapel = '';
		$where = '';
		$join = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $loggeduser->guru_id;
			//$join_mapel = "AND b.ajaran_id = $ajaran->id";
			$where = "AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM pembelajaran WHERE guru_id = $guru_id)";
		}
		if($jurusan && $tingkat == NULL && $rombel == NULL){
			//$join = "JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan)";
			//$join .= "JOIN kurikulums b ON(b.id_mapel = rencanas.id_mapel)";
			//$sel = 'rencanas.*, b.guru_id AS guru_id';
			$join = "AND rombongan_belajar_id IN (SELECT id FROM ref_rombongan_belajar WHERE kurikulum_id = $jurusan)";
		}elseif($jurusan && $tingkat && $rombel == NULL){
			//$join = "JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan AND a.tingkat = '$tingkat')";
			//$join .= "JOIN kurikulums b ON(b.id_mapel = rencanas.id_mapel)";
			//$sel = 'rencanas.*, b.guru_id AS guru_id';
			$join = "AND rombongan_belajar_id IN (SELECT id FROM ref_rombongan_belajar WHERE kurikulum_id = $jurusan) AND rombongan_belajar_id IN (SELECT id FROM ref_rombongan_belajar WHERE tingkat = $tingkat)";
		} elseif($jurusan && $tingkat && $rombel){
			//$join = "JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan AND a.tingkat = '$tingkat' AND a.id = $rombel)";
			//$join .= "JOIN kurikulums b ON(b.id_mapel = rencanas.id_mapel)";
			//$sel = 'rencanas.*, b.guru_id AS guru_id';
			$join = "AND rombongan_belajar_id = $rombel";
		}
		//$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "rencanas.ajaran_id = $ajaran->id AND kompetensi_id = 1 $where AND (b.id_mapel LIKE '%$search%' OR b.data_rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'b.id_mapel ASC, id DESC', 'joins'=> $join, 'select'=>$sel/*, 'group'=> 'rencanas.id'*/));
		//$filter = Rencana::find('all', array('conditions' => "rencanas.ajaran_id = $ajaran->id AND kompetensi_id = 1 $where AND (b.id_mapel LIKE '%$search%' OR b.data_rombel_id LIKE '%$search%')",'order'=>'b.id_mapel ASC, id DESC', 'joins'=> $join/*, 'group'=> 'rencanas.id'*/));
		$query = $this->rencana_penilaian->with('mata_pelajaran')->with('rombongan_belajar')->find_all("semester_id = $ajaran->id AND kompetensi_id = 2 $join $where AND (mata_pelajaran_id LIKE '%$search%' OR rombongan_belajar_id LIKE '%$search%' OR nama_penilaian LIKE '%$search%')", '*','id desc', $start, $rows);
		$filter = $this->rencana_penilaian->with('mata_pelajaran')->with('rombongan_belajar')->find_all("semester_id = $ajaran->id AND kompetensi_id = 2 $join $where AND (mata_pelajaran_id LIKE '%$search%' OR rombongan_belajar_id LIKE '%$search%' OR nama_penilaian LIKE '%$search%')", '*','id desc');
		$iFilteredTotal = count($query);
		
		$iTotal=count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
	    foreach ($query as $temp) {
			$nilai = $this->nilai->find("rencana_penilaian_id = $temp->id");
			if(!in_array('waka',$nama_group)){ //murni guru
				if($nilai){
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				} else {
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/edit/2/'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
					$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				}
			} else { // guru plus waka
				$admin_akses = '<li><a href="'.site_url('admin/perencanaan/view/'.$temp->id).'" class="toggle-modal"><i class="fa fa-eye"></i> Detil</a></li>';
				if($loggeduser->guru_id == $temp->guru_id){
					if($nilai){
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					} else {
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/edit/2/'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					}
				}
			}
			$jumlah_kd_nilai = $this->kd_nilai->find_count("rencana_penilaian_id = $temp->id");
			//count($temp->rencanapenilaian);
			$record = array();
            $tombol_aktif = '';
			$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			$record[] = $temp->mata_pelajaran->nama_mata_pelajaran;
			$record[] = get_nama_rombel($temp->rombongan_belajar_id);
			$record[] = $temp->nama_penilaian;
            $record[] = '<div class="text-center">'.get_teknik_penilaian($temp->metode_id).'</div>';
			$record[] = $temp->bobot;
            $record[] = '<div class="text-center">'.$jumlah_kd_nilai.'</div>';
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
								 '.$admin_akses.'
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		if($jurusan && $tingkat){
			if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
				$guru_id = $loggeduser->guru_id;
				//$join = "INNER JOIN kurikulums a ON(data_rombels.id = a.data_rombel_id AND a.guru_id = $guru_id)";
				$get_all_rombel = $this->rombongan_belajar->find_all("kurikulum_id = $jurusan AND tingkat = $tingkat AND ajaran_id = $ajaran->id");
				//Datarombel::find('all', array('conditions' => "kurikulum_id = $jurusan AND tingkat = $tingkat AND data_rombels.ajaran_id = $ajaran->id", 'joins'=> $join));
			} else {
				$get_all_rombel = $this->rombongan_belajar->find_all("kurikulum_id = $jurusan AND tingkat = $tingkat AND ajaran_id = $ajaran->id");
				//Datarombel::find_all_by_kurikulum_id_and_tingkat_and_ajaran_id($jurusan,$tingkat,$ajaran->id);
			}
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
	public function edit($kompetensi_id,$id){
		$data['kompetensi_id'] = $kompetensi_id;
		$data['rencana_penilaian'] = $this->rencana_penilaian->get($id);
		$data['ajarans'] = $this->semester->get_all();
		$data['rombels'] = $this->rombongan_belajar->get_all();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/perencanaan/')
		->set('page_title', 'Edit Perencanaan Penilaian')
		->build($this->admin_folder.'/perencanaan/edit',$data);
	}
	public function delete($id){
		$super_admin = array('admin','tu','guru');
		if($this->ion_auth->in_group($super_admin)){
			$rencana = $this->rencana_penilaian->get($id);
			if($rencana){
				$kd_nilai = $this->kd_nilai->find_all_by_rencana_penilaian_id($id);
				foreach($kd_nilai as $kd){
					$this->kd_nilai->delete($kd->id);
				}
				$this->rencana_penilaian->delete($id);
			}
			$status['type'] = 'success';
			$status['text'] = 'Data berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
	public function view($id){
		$rencana = Rencana::find($id, array('include'=>array('rencanapenilaian')));
		$this->template->title('Administrator Panel : Detil Perencanaan Penilaian')
        ->set_layout($this->modal_tpl)
        ->set('page_title', 'Detil Perencanaan Penilaian')
        ->set('rencana', $rencana)
		->set('modal_footer', '')		
        ->build($this->admin_folder.'/perencanaan/view');
	}
	public function multidelete(){
		$ids = $_POST['id'];
		$super_admin = array('admin','tu','guru');
		if($this->ion_auth->in_group($super_admin)){
			//$ids = $_POST['id'];
			//Rencana::table()->delete(array('id' => $ids));
			foreach($ids as $id){
				$rencana = $this->rencana_penilaian->get($id);
				if($rencana){
					$kd_nilai = $this->kd_nilai->find_all_by_rencana_penilaian_id($id);
					foreach($kd_nilai as $kd){
						$this->kd_nilai->delete($kd->id);
					}
					$this->rencana_penilaian->delete($id);
				}
			}
			$status['type'] = 'success';
			$status['text'] = 'Data berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
}