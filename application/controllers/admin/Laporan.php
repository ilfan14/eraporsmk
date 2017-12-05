<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends Backend_Controller {
	protected $activemenu = 'laporan';
	public function __construct() {
		parent::__construct();
		$this->template->set('activemenu', $this->activemenu);
	}
	public function index(){
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Laporan')
		->build($this->admin_folder.'/perbaikan');
	}
	public function catatan_wali_kelas(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		//test($find_akses);
		//test($nama_group);
		if($find_akses['name'] === 'siswa'){
			$siswa = $this->siswa->get($loggeduser->siswa_id);
			//Datasiswa::find_by_id($loggeduser->data_siswa_id);
			$data['ajaran']	= $ajaran->tahun.' Semester '.$ajaran->semester;
			$data['data'] = $this->catatan_wali->find("semester_id = $ajara->id AND siswa_id = $siswa->id");
			//Catatanwali::find_by_ajaran_id_and_siswa_id($ajaran->id, $siswa->id);
			$this->template->title('Administrator Panel')
			->set_layout($this->admin_tpl)
			->set('page_title', 'Catatan Wali Kelas')
			->build($this->admin_folder.'/laporan/catatan_siswa',$data);
		} else {
			if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
				$file = 'add_catatan_wali';
				$pilih_rombel = '';
				//$pilih_rombel = '<a href="'.site_url('admin/laporan/add_catatan_wali_kelas').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
			} else {
				$pilih_rombel = '';
				//$pilih_rombel = '<a href="'.site_url('admin/laporan/add_catatan_wali_kelas').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
				$file = 'list_deskripsi_antar_mapel';
			}
			$this->template->title('Administrator Panel')
			->set_layout($this->admin_tpl)
			->set('page_title', 'Catatan Wali Kelas')
			->set('guru_id', $loggeduser->guru_id)
			->set('pilih_rombel', $pilih_rombel)
			->set('form_action', 'admin/laporan/simpan_catatan_wali')
			->build($this->admin_folder.'/laporan/'.$file);
		}
	}
	public function add_catatan_wali_kelas(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/laporan/simpan_catatan_wali')
		->set('page_title', 'Tambah Data Catatan Wali Kelas')
		->set('guru_id', $loggeduser->guru_id)
		->build($this->admin_folder.'/laporan/add_catatan_wali');
	}
	public function deskripsi_sikap(){
		$file = 'list_deskripsi_sikap';
		$admin_group = array(3);
		hak_akses($admin_group);
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$file = 'deskripsi_sikap';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/laporan/simpan_deskripsi_sikap')
		->set('page_title', 'Deskripsi Sikap')
		->build($this->admin_folder.'/laporan/'.$file);
	}
	public function simpan_deskripsi_sikap(){
		$ajaran_id 						= $this->input->post('ajaran_id');
		$rombel_id 						= $this->input->post('rombel_id');
		$siswa_id 						= $this->input->post('siswa_id');
		$uraian_deskripsi_spiritual 	= $this->input->post('uraian_deskripsi_spiritual');
		$uraian_deskripsi_sosial 		= $this->input->post('uraian_deskripsi_sosial');
		foreach($siswa_id as $k=>$siswa){
			$find = $this->deskripsi_sikap->find("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND siswa_id = $siswa");
			//Deskripsisikap::find_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$siswa);
			if($find){
				$deskripsi_sikap_update = array(
					'uraian_deskripsi_spiritual' 	=> $uraian_deskripsi_spiritual[$k],
					'uraian_deskripsi_sosial' 		=> $uraian_deskripsi_sosial[$k]
				);
				$this->deskripsi_sikap->update($find->id, $deskripsi_sikap_update);
				$this->session->set_flashdata('success', 'Berhasil memperbaharui data deskripsi sikap');
			} else {
				if($uraian_deskripsi_spiritual[$k]){
					$deskripsi_sikap_insert = array(
						'semester_id'					=> $ajaran_id,
						'rombongan_belajar_id'			=> $rombel_id,
						'siswa_id' 						=> $siswa,
						'uraian_deskripsi_spiritual' 	=> $uraian_deskripsi_spiritual[$k],
						'uraian_deskripsi_sosial' 		=> $uraian_deskripsi_sosial[$k]
					);
					//test($deskripsi_sikap_insert);
					$this->deskripsi_sikap->insert($deskripsi_sikap_insert);
				}
				$this->session->set_flashdata('success', 'Berhasil menambah data deskripsi sikap');
			}
		}
		redirect('admin/laporan/deskripsi_sikap');
	}
	public function absensi(){
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$file = 'add_absensi';
		} else {
			$file = 'list_absensi';
		}
		$data['tingkat_pendidikan'] = $this->tingkat_pendidikan->get_all();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/laporan/simpan_absensi')
		->set('page_title', 'Data Absensi Siswa')
		->build($this->admin_folder.'/laporan/'.$file,$data);
	}
	public function ekstrakurikuler(){
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$file = 'ekstrakurikuler';
		} else {
			$file = 'list_ekstrakurikuler';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/laporan/simpan_ekstrakurikuler')
		->set('page_title', 'Ekstrakurikuler')
		->build($this->admin_folder.'/laporan/'.$file);
	}
	public function prakerin(){
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$file = 'prakerin';
		} else {
			$file = 'list_prakerin';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/laporan/simpan_prakerin')
		->set('page_title', 'Praktik Kerja Lapangan')
		->build($this->admin_folder.'/laporan/'.$file);
	}
	public function prestasi(){
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$file = 'prestasi';
		} else {
			$file = 'list_prestasi';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('form_action', 'admin/laporan/simpan_prestasi')
		->set('page_title', 'Prestasi Siswa')
		->build($this->admin_folder.'/laporan/'.$file);
	}
	public function rapor(){
		$ajaran = get_ta();
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		unset($data);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $find_akses['id'][0];
			$rombel = $rombel = $this->rombongan_belajar->find("guru_id = $guru_id AND semester_id = $ajaran->id");
			//Datarombel::find_by_guru_id_and_ajaran_id($find_akses['id'], $ajaran->id);
			$rombel_id = isset($rombel->id) ? $rombel->id : 0;
			$kurikulum_id = isset($rombel->kurikulum_id) ? $rombel->kurikulum_id : 0;
			$ajaran = get_ta();
			$data['query'] = 'wali';
			$data['rombel_id'] = $rombel_id;
			$data['ajaran_id'] = $ajaran->id;
			$nama_kompetensi = get_kurikulum($kurikulum_id);
			if (strpos($nama_kompetensi, 'REV') !== false) {
				$data['nama_kompetensi'] = 2017;
			} elseif (strpos($nama_kompetensi, 'SMK KTSP') !== false) {
				$data['nama_kompetensi'] = 'ktsp';
			} else {
				$data['nama_kompetensi'] = 2013;
			}
			$folder = '/cetak/';
		} else {
			$data['query'] = 'waka';
			//$data['ajarans'] = Ajaran::all();
			//$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
			$folder = '/laporan/';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Cetak Rapor')
		->build($this->admin_folder.$folder.'rapor',$data);
		//->build($this->admin_folder.'/cetak/'.$file,$data);
	}
	public function legger(){
		$ajaran = get_ta();
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		unset($data);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $find_akses['id'][0];
			$rombel = $this->rombongan_belajar->find("guru_id = $guru_id AND semester_id = $ajaran->id");
			//Datarombel::find_by_guru_id_and_ajaran_id($guru_id,$ajaran->id);
			$rombel_id = isset($rombel->id) ? $rombel->id : 0;
			$kurikulum_id = isset($rombel->kurikulum_id) ? $rombel->kurikulum_id : 0;
			$data['query'] = 'wali';
			$data['rombel_id'] = $rombel_id;
			$data['ajaran_id'] = $ajaran->id;
			$kompetensi = get_kurikulum($kurikulum_id);			
			//Datakurikulum::find_by_kurikulum_id($kurikulum_id);
			$nama_kompetensi = isset($kompetensi->nama_kurikulum) ? $kompetensi->nama_kurikulum : 0;
			if (strpos($nama_kompetensi, 'REV') !== false) {
				$data['nama_kompetensi'] = 2017;
			} elseif (strpos($nama_kompetensi, 'SMK KTSP') !== false) {
				$data['nama_kompetensi'] = 'ktsp';
			} else {
				$data['nama_kompetensi'] = 2013;
			}
			$folder = '/cetak/';
		} else {
			$folder = '/laporan/';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Cetak Legger')
		->build($this->admin_folder.$folder.'legger',$data);
	}
	public function list_catatan_wali_kelas(){
		$ajaran = get_ta();
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
		$where = "(rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE semester_id = $ajaran->id AND nama LIKE '%$search%') OR siswa_id IN (SELECT id FROM ref_siswa WHERE nama LIKE '%$search%') OR uraian_deskripsi LIKE '%$search%')";
		$query = $this->catatan_wali->find_all("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC', $start, $rows);
		$filter = $this->catatan_wali->find_count("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC');
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$record = array();
            $tombol_aktif = '';
			$rombel = $this->rombongan_belajar->get($temp->rombongan_belajar_id);
			if($temp->uraian_deskripsi){
				$uraian_deskripsi = limit_words($temp->id,$temp->uraian_deskripsi,10);
			} else {
				$uraian_deskripsi = $temp->uraian_deskripsi;
			}
			if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
				$kur = 2017;
			} elseif (strpos(get_kurikulum($rombel->kurikulum_id), '2013') !== false) {
				$kur = 2013;
			} else {
				$kur = 'ktsp';
			}
			$record[] = '<div class="text-center">'.get_nama_rombel($temp->rombongan_belajar_id).'</div>';
			$record[] = get_wali_kelas($temp->rombongan_belajar_id);
			$record[] = get_nama_siswa($temp->siswa_id);
            $record[] = $uraian_deskripsi;
            $record[] = '<div class="text-center"><a href="'.site_url('admin/laporan/review_rapor/'.$kur.'/'.$ajaran->id.'/'.$temp->rombongan_belajar_id.'/'.$temp->siswa_id).'" class="btn btn-info btn-sm">Pratintau Rapor</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_absensi(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
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
		$where = "(rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE semester_id = $ajaran->id AND nama LIKE '%$search%') OR siswa_id IN (SELECT id FROM ref_siswa WHERE nama LIKE '%$search%'))";
		$query = $this->absen->find_all("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC', $start, $rows);
		$filter = $this->absen->find_count("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC');
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$rombel = $this->rombongan_belajar->get($temp->rombongan_belajar_id);
			if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
				$kur = 2017;
			} elseif (strpos(get_kurikulum($rombel->kurikulum_id), '2013') !== false) {
				$kur = 2013;
			} else {
				$kur = 'ktsp';
			}
			$record = array();
			$record[] = '<div class="text-center">'.get_nama_rombel($temp->rombongan_belajar_id).'</div>';
			$record[] = get_nama_siswa($temp->siswa_id);
            $record[] = '<div class="text-center">'.$temp->sakit.'</div>';
            $record[] = '<div class="text-center">'.$temp->izin.'</div>';
            $record[] = '<div class="text-center">'.$temp->alpa.'</div>';
            $record[] = '<div class="text-center"><a href="'.site_url('admin/laporan/review_rapor/'.$kur.'/'.$ajaran->id.'/'.$temp->rombongan_belajar_id.'/'.$temp->siswa_id).'" class="btn btn-info btn-sm">Pratintau Rapor</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_ekstrakurikuler(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
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
		$where = "(rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE semester_id = $ajaran->id AND nama LIKE '%$search%') OR siswa_id IN (SELECT id FROM ref_siswa WHERE nama LIKE '%$search%'))";
		$query = $this->nilai_ekstrakurikuler->find_all("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC', $start, $rows);
		$filter = $this->nilai_ekstrakurikuler->find_count("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC');
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$rombel = $this->rombongan_belajar->get($temp->rombongan_belajar_id);
			if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
				$kur = 2017;
			} elseif (strpos(get_kurikulum($rombel->kurikulum_id), '2013') !== false) {
				$kur = 2013;
			} else {
				$kur = 'ktsp';
			}
			$record = array();
            $tombol_aktif = '';
			$record[] = get_nama_rombel($temp->rombongan_belajar_id);
			$record[] = get_nama_siswa($temp->siswa_id);
            $record[] = get_nama_ekskul($temp->ekstrakurikuler_id);
            $record[] = get_nilai_ekskul($temp->nilai);
            $record[] = $temp->deskripsi_ekskul;
            $record[] = '<div class="text-center"><a href="'.site_url('admin/laporan/review_rapor/'.$kur.'/'.$ajaran->id.'/'.$temp->rombongan_belajar_id.'/'.$temp->siswa_id).'" class="btn btn-info btn-sm">Pratintau Rapor</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_prakerin(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
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
		$where = "(rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE semester_id = $ajaran->id AND nama LIKE '%$search%') OR siswa_id IN (SELECT id FROM ref_siswa WHERE nama LIKE '%$search%') OR mitra_prakerin LIKE '%$search%' OR lokasi_prakerin LIKE '%$search%')";
		$query = $this->prakerin->find_all("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC', $start, $rows);
		$filter = $this->prakerin->find_count("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC');
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$rombel = $this->rombongan_belajar->get($temp->rombongan_belajar_id);
			if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
				$kur = 2017;
			} elseif (strpos(get_kurikulum($rombel->kurikulum_id), '2013') !== false) {
				$kur = 2013;
			} else {
				$kur = 'ktsp';
			}
			$record = array();
            $tombol_aktif = '';
			$record[] = get_nama_rombel($temp->rombongan_belajar_id);
			$record[] = get_nama_siswa($temp->siswa_id);
            $record[] = $temp->mitra_prakerin;
            $record[] = $temp->lokasi_prakerin;
            $record[] = $temp->lama_prakerin;
			$record[] = $temp->keterangan_prakerin;
            $record[] = '<div class="text-center"><a href="'.site_url('admin/laporan/review_rapor/'.$kur.'/'.$ajaran->id.'/'.$temp->rombongan_belajar_id.'/'.$temp->siswa_id).'" class="btn btn-info btn-sm">Pratintau Rapor</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_prestasi(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
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
		$where = "(rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE semester_id = $ajaran->id AND nama LIKE '%$search%') OR siswa_id IN (SELECT id FROM ref_siswa WHERE nama LIKE '%$search%') OR jenis_prestasi LIKE '%$search%' OR keterangan_prestasi LIKE '%$search%')";
		$query = $this->prestasi->find_all("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC', $start, $rows);
		$filter = $this->prestasi->find_count("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC');
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$rombel = $this->rombongan_belajar->get($temp->rombongan_belajar_id);
			if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
				$kur = 2017;
			} elseif (strpos(get_kurikulum($rombel->kurikulum_id), '2013') !== false) {
				$kur = 2013;
			} else {
				$kur = 'ktsp';
			}
			$record = array();
            $tombol_aktif = '';
			$record[] = get_nama_rombel($temp->rombongan_belajar_id);
			$record[] = get_nama_siswa($temp->siswa_id);
            $record[] = $temp->jenis_prestasi;
			$record[] = $temp->keterangan_prestasi;
            $record[] = '<div class="text-center"><a href="'.site_url('admin/laporan/review_rapor/'.$kur.'/'.$ajaran->id.'/'.$temp->rombongan_belajar_id.'/'.$temp->siswa_id).'" class="btn btn-info btn-sm">Pratintau Rapor</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_prestasi_old(){
		$loggeduser = $this->ion_auth->user()->row();
		$find_akses = get_akses($loggeduser->id);
		$cari_rombel = '';
		if($find_akses['name'] === 'guru'){
			$cari_rombel = Datarombel::find_by_guru_id($find_akses['id']);
		}
		$wali_kelas = '';
		if($cari_rombel){
			$wali_kelas = "AND rombel_id = $cari_rombel->id";
		}
		$ajaran = get_ta();
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
		$query = Prestasi::find('all', array('conditions' => "ajaran_id = $ajaran->id $wali_kelas AND (rombel_id LIKE '%$search%' OR siswa_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id ASC'));
		$filter = Prestasi::find('all', array('conditions' => "ajaran_id = $ajaran->id $wali_kelas AND (rombel_id LIKE '%$search%' OR siswa_id LIKE '%$search%')",'order'=>'id ASC'));
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
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$ajaran = Ajaran::find($temp->ajaran_id);
			$rombel = Datarombel::find($temp->rombel_id);
			$kompetensi = Datakurikulum::find_by_kurikulum_id($rombel->kurikulum_id);
			$nama_kompetensi = isset($kompetensi->nama_kurikulum) ? $kompetensi->nama_kurikulum : 0;
			if (strpos($nama_kompetensi, 'SMK 2013') !== false) {
				$kur = 2013;
			} elseif (strpos($nama_kompetensi, 'SMK KTSP') !== false) {
				$kur = 'ktsp';
			} else {
				$kur = 0;
			}
			$siswa = Datasiswa::find($temp->siswa_id);
			$record = array();
            $tombol_aktif = '';
			$record[] = $rombel->nama;
			$record[] = $siswa->nama;
            $record[] = $temp->jenis_prestasi;
            $record[] = $temp->keterangan_prestasi;
            $record[] = '<div class="text-center"><a href="'.site_url('admin/laporan/review_rapor/'.$kur.'/'.$ajaran->id.'/'.$temp->rombel_id.'/'.$temp->siswa_id).'" class="btn btn-info btn-sm">Pratintau Rapor</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function view_deskripsi_antar_mapel($id){
		$deskripsi = $this->catatan_wali->get($id);
		$siswa = $this->siswa->get($deskripsi->siswa_id);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Deskripsi Antar Mata Pelajaran '.$siswa->nama)
		->set('data', $deskripsi->uraian_deskripsi)
		->build($this->admin_folder.'/laporan/view_deskripsi_antar_mapel');
	}
	public function review_rapor($kur,$ajaran_id,$rombel_id,$siswa_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum_id'] = $kur;
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Pratinjau Rapor')
		->build($this->admin_folder.'/laporan/review_rapor',$data);
	}
	public function review_desc($kur, $ajaran_id,$rombel_id,$siswa_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum'] = $kur;
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Pratinjau Deskripsi')
		->build($this->admin_folder.'/laporan/review_desc',$data);
	}
	public function review_nilai($kur,$ajaran_id,$rombel_id,$siswa_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum'] = $kur;
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Pratinjau Nilai')
		->build($this->admin_folder.'/laporan/rapor_nilai',$data);
	}
	public function simpan_catatan_wali(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$siswa_id = $_POST['siswa_id'];
		foreach($siswa_id as $key=>$siswa){
			$catatan_wali = $this->catatan_wali->find("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND siswa_id = $siswa");
			//Catatanwali::find_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$siswa);
			if($catatan_wali){
				$catatan_wali_update = array(
					'uraian_deskripsi' => $_POST['uraian_deskripsi'][$key]
				);
				$this->catatan_wali->update($catatan_wali->id, $catatan_wali_update);
				$this->session->set_flashdata('success', 'Berhasil memperbaharui deskripsi antar mata pelajaran');
			} else {
				$new_catatan_wali = array(
					'semester_id'			=> $ajaran_id,
					'rombongan_belajar_id'	=> $rombel_id,
					'siswa_id'				=> $siswa,
					'uraian_deskripsi' 		=> $_POST['uraian_deskripsi'][$key],
				);
				$this->catatan_wali->insert($new_catatan_wali);
				$this->session->set_flashdata('success', 'Berhasil menambah deskripsi antar mata pelajaran');
			}
		}
		redirect('admin/laporan/catatan_wali_kelas');
	}
	public function simpan_absensi(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$siswa_id = $_POST['siswa_id'];
		foreach($siswa_id as $key=>$siswa){
			array_walk($_POST['sakit'], 'check_numeric','laporan/absensi');
			array_walk($_POST['izin'], 'check_numeric','laporan/absensi');
			array_walk($_POST['alpa'], 'check_numeric','laporan/absensi');
			$absen = $this->absen->find("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND siswa_id = $siswa");
			//Absen::find_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$siswa);
			if($absen){
				$absen_update = array(
					'sakit' => $_POST['sakit'][$key],
					'izin' 	=> $_POST['izin'][$key],
					'alpa'	=> $_POST['alpa'][$key],
				);
				$this->absen->update($absen->id, $absen_update);
				$this->session->set_flashdata('success', 'Berhasil memperbaharui data absensi');
			} else {
				$new_absen = array(
					'semester_id'			=> $ajaran_id,
					'rombongan_belajar_id'	=> $rombel_id,
					'siswa_id'				=> $siswa,
					'sakit'					=> $_POST['sakit'][$key],
					'izin'					=> $_POST['izin'][$key],
					'alpa'					=> $_POST['alpa'][$key],
				);
				$this->absen->insert($new_absen);
				$this->session->set_flashdata('success', 'Berhasil menambah data absensi');
			}
		}
		redirect('admin/laporan/absensi');
	}
	public function simpan_ekstrakurikuler(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$ekskul_id = $_POST['ekskul_id'];
		$siswa_id = $_POST['siswa_id'];
		foreach($siswa_id as $key=>$siswa){
			$nilai_ekskul = $this->nilai_ekstrakurikuler->find("semester_id = $ajaran_id AND ekstrakurikuler_id = $ekskul_id AND rombongan_belajar_id = $rombel_id AND siswa_id = $siswa");
			//Nilaiekskul::find_by_ajaran_id_and_ekskul_id_and_rombel_id_and_siswa_id($ajaran_id,$ekskul_id,$rombel_id,$siswa);
			if($nilai_ekskul){
				$nilai_ekskul_update = array(
					'nilai' => $_POST['nilai'][$key],
					'deskripsi_ekskul' 	=> $_POST['deskripsi_ekskul'][$key],
				);
				$this->nilai_ekstrakurikuler->update($nilai_ekskul->id, $nilai_ekskul_update);
				$this->session->set_flashdata('success', 'Berhasil memperbaharui nilai ekstrakurikuler');
			} else {
				$new_ekskul= array(
					'semester_id'			=> $ajaran_id,
					'ekstrakurikuler_id' 	=> $ekskul_id,
					'rombongan_belajar_id'	=> $rombel_id,
					'siswa_id'				=> $siswa,
					'nilai' 				=> $_POST['nilai'][$key],
					'deskripsi_ekskul'		=> $_POST['deskripsi_ekskul'][$key],
				);
				$this->nilai_ekstrakurikuler->insert($new_ekskul);
				$this->session->set_flashdata('success', 'Berhasil menambah nilai ekstrakurikuler');
			}
		}
		redirect('admin/laporan/ekstrakurikuler');
	}
	public function simpan_prakerin(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$siswa_id = $_POST['siswa_id'];
		$mitra_prakerin = $_POST['mitra_prakerin'];
		$lokasi_prakerin = $_POST['lokasi_prakerin'];
		$lama_prakerin = $_POST['lama_prakerin'];
		$keterangan_prakerin = $_POST['keterangan_prakerin'];
		$post_lama_prakerin = array($lama_prakerin);
		array_walk($post_lama_prakerin, 'check_numeric','laporan/prakerin');
		$new_prakerin = array(
			'semester_id'			=> $ajaran_id,
			'rombongan_belajar_id'	=> $rombel_id,
			'siswa_id'				=> $siswa_id,
			'mitra_prakerin'		=> $mitra_prakerin,
			'lokasi_prakerin'		=> $lokasi_prakerin,
			'lama_prakerin'			=> $lama_prakerin,
			'keterangan_prakerin'	=> $keterangan_prakerin,
		);
		$this->prakerin->insert($new_prakerin);
		$this->session->set_flashdata('success', 'Berhasil menambah data prakerin');
		redirect('admin/laporan/prakerin');
	}
	public function edit_prakerin($id){
		if($_POST){
			$prakerin = $this->prakerin->get($id);
			if($prakerin){
				$prakerin_update = array(
					'mitra_prakerin' => $_POST['mitra_prakerin'], 
					'lokasi_prakerin' => $_POST['lokasi_prakerin'], 
					'lama_prakerin' => $_POST['lama_prakerin'],
					'keterangan_prakerin' => $_POST['keterangan_prakerin']
				);
				$this->prakerin->update($id, $prakerin_update);
			}
			$ajaran_id = $_POST['ajaran_id'];
			$rombel_id = $_POST['rombel_id'];
			$siswa_id = $_POST['siswa_id'];
			$this->form_prakerin($ajaran_id,$rombel_id,$siswa_id);
		} else {
			$prakerin = $this->prakerin->get($id);
			$this->template->title('Administrator Panel : Edit Sikap')
			->set_layout($this->modal_tpl)
			->set('page_title', 'Edit Sikap')
			->set('prakerin', $prakerin)
			->set('modal_footer', '<a class="btn btn-primary" id="button_form" href="javascript:void(0);">Update</a>')
			->build($this->admin_folder.'/laporan/edit_prakerin');
		}
	}
	function form_prakerin($ajaran_id,$rombel_id,$siswa_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$this->load->view('backend/laporan/add_prakerin', $data);
	}
	public function delete_prakerin($id){
		$prakerin = $this->prakerin->delete($id);
	}
	public function simpan_prestasi(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$siswa_id = $_POST['siswa_id'];
		$jenis_prestasi = $_POST['jenis_prestasi'];
		$keterangan_prestasi = $_POST['keterangan_prestasi'];
		$new_prestasi = array(
			'semester_id'			=> $ajaran_id,
			'rombongan_belajar_id'	=> $rombel_id,
			'siswa_id'				=> $siswa_id,
			'jenis_prestasi'		=> $jenis_prestasi,
			'keterangan_prestasi'	=> $keterangan_prestasi,
		);
		$this->prestasi->insert($new_prestasi);
		$this->session->set_flashdata('success', 'Berhasil menambah data prestasi siswa');
		redirect('admin/laporan/prestasi');
	}
	public function edit_prestasi($id){
		$prestasi = $this->prestasi->get($id);
		if($_POST){
			$prestasi_update = array(
				'jenis_prestasi' 		=> $_POST['jenis_prestasi'], 
				'keterangan_prestasi' 	=> $_POST['keterangan_prestasi']
			);
			$this->prestasi->update($id, $prestasi_update);
			$ajaran_id = $_POST['ajaran_id'];
			$rombel_id = $_POST['rombel_id'];
			$siswa_id = $_POST['siswa_id'];
			$this->form_prestasi($ajaran_id,$rombel_id,$siswa_id);
		} else {
			$this->template->title('Administrator Panel : Edit Prestasi')
			->set_layout($this->modal_tpl)
			->set('page_title', 'Edit Prestasi')
			->set('prestasi', $prestasi)
			->set('modal_footer', '<a class="btn btn-primary" id="button_form" href="javascript:void(0);">Update</a>')
			->build($this->admin_folder.'/laporan/edit_prestasi');
		}
	}
	function form_prestasi($ajaran_id,$rombel_id,$siswa_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$this->load->view('backend/laporan/add_prestasi', $data);
	}
	public function delete_prestasi($id){
		$prakerin = $this->prestasi->delete($id);
	}
	public function list_deskripsi_sikap(){
		$ajaran = get_ta();
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
		$where = "(rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE semester_id = $ajaran->id AND nama LIKE '%$search%') OR siswa_id IN (SELECT id FROM ref_siswa WHERE nama LIKE '%$search%') OR uraian_deskripsi LIKE '%$search%')";
		$query = $this->catatan_wali->find_all("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC', $start, $rows);
		$filter = $this->catatan_wali->find_count("semester_id = $ajaran->id AND ($where)", '*','rombongan_belajar_id ASC');
		$iFilteredTotal = count($query);
		
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$record = array();
            $tombol_aktif = '';
			$rombel = $this->rombongan_belajar->get($temp->rombongan_belajar_id);
			if($temp->uraian_deskripsi){
				$uraian_deskripsi = limit_words($temp->id,$temp->uraian_deskripsi,10);
			} else {
				$uraian_deskripsi = $temp->uraian_deskripsi;
			}
			if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
				$kur = 2017;
			} elseif (strpos(get_kurikulum($rombel->kurikulum_id), '2013') !== false) {
				$kur = 2013;
			} else {
				$kur = 'ktsp';
			}
			$record[] = '<div class="text-center">'.get_nama_rombel($temp->rombongan_belajar_id).'</div>';
			$record[] = get_wali_kelas($temp->rombongan_belajar_id);
			$record[] = get_nama_siswa($temp->siswa_id);
            $record[] = $uraian_deskripsi;
            //$record[] = '<div class="text-center"><a href="'.site_url('admin/laporan/review_rapor/'.$kur.'/'.$ajaran->id.'/'.$temp->rombongan_belajar_id.'/'.$temp->siswa_id).'" class="btn btn-info btn-sm">Pratintau Rapor</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
}