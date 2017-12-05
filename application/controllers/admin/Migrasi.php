<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Migrasi extends Backend_Controller { 
	public function __construct() {
		parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1');
		$this->load->model('migrasi_model');
	}
	public function index(){
		redirect(site_url());
	}
	public function rencana(){
		$loggeduser = $this->ion_auth->user()->row();
		$jumlah_data = $this->migrasi_model->jumlah_data();
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/migrasi/rencana');
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 50;
		$from = $this->uri->segment(4);
		$this->pagination->initialize($config);
		$data['result'] = $this->migrasi_model->get_data($config['per_page'], $from);
		$data['inserted'] = $this->nilai->count_all();
		$data['total_rows'] = $jumlah_data;
		$data['pagination'] = $this->pagination->create_links();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Proses Migrasi Rencana Penilaian, Referensi KD &amp; Nilai Siswa')
		->set('loggeduser', $loggeduser)
		->set('ajaran', get_ta())
		->build($this->admin_folder.'/migrasi/rencana', $data);
	}
}
