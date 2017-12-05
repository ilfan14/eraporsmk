<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends Backend_Controller {
	protected $activemenu = 'dashboard';
	public function __construct() {
		parent::__construct(); 
		$this->template->set('activemenu', $this->activemenu);
	}
	public function index(){
		if ($this->db->table_exists('rencana_penilaians')){
			redirect('admin/migrasi/rencana');
		} else {
			$get_version = get_version();
			if($get_version != '3.0'){
				$setting = array('version' => '3.0');
				$this->settings->update(1,$setting);
			}
		}
		//$query = get_last_id('ref_kompetensi_dasar');
		//test($query);
		//die();
		$loggeduser 				= $this->ion_auth->user()->row();
		$semester = get_ta();
		$jurusan = $this->jurusan_sp->get_all();
		if(!$jurusan){
			redirect('admin/profil/sekolah');
		}
		$data['detil_user']			= $this->ion_auth->user()->row();
		$data['user'] 				= $this->ion_auth->user()->row();
		$data['semester'] 			= get_ta();
		$data['siswa'] 				= $this->anggota_rombel->find_count("semester_id = $semester->id");
		$data['guru'] 				= $this->guru_terdaftar->find_count("semester_id = $semester->id");
		$data['rombongan_belajar']	= $this->rombongan_belajar->find_count("sekolah_id = $loggeduser->sekolah_id AND semester_id = $semester->id");
		$data['rencana_penilaian']	= $this->rencana_penilaian->find_count("semester_id = $semester->id AND rombongan_belajar_id IN(SELECT id FROM rombongan_belajar WHERE sekolah_id = $loggeduser->sekolah_id AND semester_id = $semester->id)");
		$data['nilai']				= $this->nilai->find_count("semester_id = $semester->id");
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Beranda')
		->build($this->admin_folder.'/dashboard', $data);
	}
	public function skin($id){
		$id = str_replace('_','-',$id);
		$this->load->library('user_agent');
		$newdata = array(
			'template'  => $id
				);
		$this->session->set_userdata($newdata);
		redirect($this->agent->referrer());
	}
}
