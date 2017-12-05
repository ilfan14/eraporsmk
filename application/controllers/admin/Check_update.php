<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Check_update extends Backend_Controller {
	protected $activemenu = 'update';
	public function __construct() {
		parent::__construct(); 
		$this->template->set('activemenu', $this->activemenu);
		$this->load->library('curl');
		$this->load->library('unzip');
		$this->load->helper('update');
	}
	public function index(){
		$current_version = check_update();
		$get_url = 'http://updater.erapor-smk.net/index.php';
		$remote_version = $this->curl->simple_get($get_url, array('versi'=>$current_version), array(CURLOPT_USERAGENT => true));
		$respon = json_decode($remote_version);
		$versi = isset($respon->versi) ? $respon->versi : 'off';
		if($versi !== 'off'){
			$data['set_status'] = 1;
		} else {
			$data['set_status'] = 0;
		}
		$data['versi'] = $versi;
		if (version_compare($current_version, $versi, '<')) {
			$data['data'] = 1;
		} else {
			$data['data'] = 0;
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Cek Pembaharuan')
		->build($this->admin_folder.'/pembaharuan', $data);
	}
	public function download($versi){
		$respon = download($versi);
		echo json_encode($respon);
	}
	public function extract_to($versi){
		$respon = extract_to($versi);
		echo json_encode($respon);
	}
	public function update_versi($versi){
		$respon = update_versi($versi);
		echo json_encode($respon);
	}
}
