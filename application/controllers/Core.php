<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Core extends CI_Controller {
	public function index(){
		$ajaran = get_ta();
		$data['title'] = $this->session->userdata('title');
		$data['table'] = $this->session->userdata('table');
		$data['show_percent'] = $this->session->userdata('show_percent');
		$this->load->view('install', $data);
	}
	public function checker($file){
		header('Content-Type: application/json');
		//$file = str_replace(".", "", $id);
		$file = "assets/temp/" . $file . ".txt";
		if (file_exists($file)) {
			$text = file_get_contents($file);
			echo $text;
			$obj = json_decode($text);
			if ($obj->percent == 100) {
				unlink($file);
			}
		} else {
			$arr_content['percent'] = 100;
		  	$arr_content['message'] = "100% processed.";
			file_put_contents("assets/temp/" . session_id() . ".txt", json_encode($arr_content));
			echo json_encode($arr_content);
		}
	}
	public function process(){
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED & ~E_WARNING);
		$this->load->database();
		$id = $_POST['id'];
		$file = file_get_contents('assets/sql/'.$_POST['id'].'.sql');
		$string_query = rtrim( $file, "\n;" );
		$array_query = explode(";", $string_query);
		foreach($array_query as $query){
			$this->db->query($query);
		}
	}
	public function set_config() {
		$session = $this->session->userdata();
		$hostname = $session['hostname'];
		$username = $session['username'];
		$password = $session['password'];
		$database = $session['database'];
		$template_path 	= 'assets/sql/config.php';
		$output_path 	= 'application/config/config.php';
		$config_file = file_get_contents($template_path);
		$handle = fopen($output_path,'w+');
		// Chmod the file, in case the user forgot
		@chmod($output_path,0755);

		// Verify file permissions
		if(is_writable($output_path)) {
			// Write the file
			if(fwrite($handle,$config_file)) {
				//return true;
				$this->session->set_userdata('hostname', $hostname);
				$this->session->set_userdata('username', $username);
				$this->session->set_userdata('password', $password);
				$this->session->set_userdata('database', $database);
				redirect('core/database');
			} else {
				//return false;
				echo 'The database configuration file could not be written, please chmod application/config/config.php file to 777';
			}

		} else {
			//return false;
			echo 'The database configuration file could not be written, please chmod application/config/config.php file to 777';
		}
	}
	public function database() {
		$session = $this->session->userdata();
		$hostname = $session['hostname'];
		$username = $session['username'];
		$password = $session['password'];
		$database = $session['database'];
		// Config path
		$template_path 	= 'assets/sql/database.php';
		$output_path 	= 'application/config/database.php';
		// Open the file
		$database_dapodik = file_get_contents($template_path);
		if($password == 0){
			$password = '';
		}
		$new  = str_replace("%HOSTNAME%",$hostname,$database_dapodik);
		$new  = str_replace("%USERNAME%",$username,$new);
		$new  = str_replace("%PASSWORD%",$password,$new);
		$new  = str_replace("%DATABASE%",$database,$new);
		// Write the new database.php file
		$handle = fopen($output_path,'w+');

		// Chmod the file, in case the user forgot
		@chmod($output_path,0755);

		// Verify file permissions
		if(is_writable($output_path)) {
			// Write the file
			if(fwrite($handle,$new)) {
				//return true;
				redirect(site_url());
				/*if($this->ion_auth->logged_in()){
					redirect('admin/auth/logout');
				} else {
					redirect('admin/auth/');
				}*/
				//redirect('admin/dashboard');
			} else {
				//return false;
				echo 'The database configuration file could not be written, please chmod application/config/database.php file to 777';
			}

		} else {
			//return false;
			echo 'The database configuration file could not be written, please chmod application/config/database.php file to 777';
		}
	}
	function write_htaccess(){
		// Config path
		$template_path 	= 'assets/sql/.htaccess';
		$output_path 	= '.htaccess';
		// Open the file
		$config_file = file_get_contents($template_path);
		$root = $_SERVER["REQUEST_URI"];
		$parse = parse_url($root);
		$subfolder = str_replace('core/write_htaccess','',$parse['path']);
		$new  = str_replace("%rewrite_base%","RewriteBase ".$subfolder, $config_file);
		// Write the new index.php file
		$handle = fopen($output_path,'w+');
		// Chmod the file, in case the user forgot
		@chmod($output_path,0755);
		// Verify file permissions
		if(is_writable($output_path)) {
			// Write the file
			if(fwrite($handle,$new)) {
				redirect(site_url());
			} else {
				echo "The htaccess file could not be written, please chmod install/config/.htaccess file to 777";
			}
		} else {
			echo "The htaccess file could not be written, please chmod install/config/.htaccess file to 777";
		}	
	}
	function write_autoload(){
		// Config path
		$template_path 	= 'assets/sql/autoload.php';
		$output_path 	= 'application/config/autoload.php';
		// Open the file
		$config_file = file_get_contents($template_path);
		$subfolder = str_replace('/','',substr($_SERVER["REQUEST_URI"], 0, -18)).'/';
		$subfolder = substr($_SERVER["REQUEST_URI"], 1, -18).'/';
		$new  = str_replace("%rewrite_base%","RewriteBase /".$subfolder, $config_file);
		// Write the new index.php file
		$handle = fopen($output_path,'w+');
		// Chmod the file, in case the user forgot
		@chmod($output_path,0755);
		// Verify file permissions
		if(is_writable($output_path)) {
			// Write the file
			if(fwrite($handle,$new)) {
				redirect(site_url());
			} else {
				echo "The autoload file could not be written, please chmod install/config/autoload.php file to 777";
			}
		} else {
			echo "The autoload file could not be written, please chmod install/config/autoload.php file to 777";
		}
	}
}
