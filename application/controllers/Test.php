<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test extends CI_Controller {
     
    public function __construct(){
		parent::__construct();
		$this->load->library('unzip');
		//if (!$this->db->table_exists('ci_sessions')) {
           // $output = shell_exec("php index.php migrate");
           // if (strncmp($output, "Migration worked!", 17) != 0) {
                //exit($output);
            //}
        //}
	}                              
	public function index(){
		$file = 'update_3.0.1.zip';
		if(is_file($file)){
			$extracts = $this->unzip->extract($file); 
			if($extracts){
				echo 'sukses';
			} else {
				echo 'gagal';
			}
			$this->unzip->close();
			unlink($file);
		}
	}
}

/* End of file progress.php */
/* Location: ./application/controllers/progress.php */