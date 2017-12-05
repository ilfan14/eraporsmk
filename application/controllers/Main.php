<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main extends CI_Controller {
     
    public function __construct(){
		parent::__construct();
	}                              
	public function index(){
		//if(!$this->ion_auth->logged_in()){
			redirect('admin/dashboard/');
		//}
		//$this->load->view('progress');
		// updated file
	}
	public function template($id){
		$this->load->library('user_agent');
		$newdata = array(
			'template_depan'  => $id
				);
		$this->session->set_userdata($newdata);
		redirect($this->agent->referrer());
	}
	public function backup(){
		$this->load->database();
		$tables = $this->db->list_tables();
		$settings = $this->settings->get(1);
		if($settings->site_title){
			$backup_name = $settings->site_title.'_'.date('d-m-Y');
		} else {
			$backup_name = 'Backup App_'.date('d-m-Y');
		}
		$backup_name = str_replace(' ','_',$backup_name);
		$backup_name = strtolower($backup_name);
		$this->load->dbutil();
		$prefs = array(
				'tables'      => $tables,  // Array of tables to backup.
				'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => $backup_name.'.sql',    // File name - NEEDED ONLY WITH ZIP FILES
				//'newline'      => '',
              );
		$backup =& $this->dbutil->backup($prefs); 
		$this->load->helper('file');
		write_file('./'.$prefs['filename'], $backup);
		$this->load->library('zip');
		$this->zip->read_file($prefs['filename']);
		$this->zip->archive('/files/'.$prefs['filename'].'.zip'); 
		unlink(FCPATH .'/'.$prefs['filename']);
		$this->zip->download($prefs['filename'].'.zip');
	}
	public function download($f){
		$this->load->helper('download');
		$data = file_get_contents('files/backups/'.$f); // Read the file's contents
		force_download($f, $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */