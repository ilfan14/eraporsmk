<?php
class Config extends Backend_Controller {
	function __construct()  {
		parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1'); 
		$this->load->library('unzip');
		$this->load->database();
    }
	public function index(){
		$fileName = $_FILES['import']['name'];
		$status = array();
		$config['upload_path'] = './assets/files/';
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'zip';
		$config['overwrite'] = TRUE;
		$this->load->library('upload');
        $this->upload->initialize($config);
		if(!$this->upload->do_upload('import')){
			$status['type'] = 'error';
			$status['text'] = $this->upload->display_errors();
			$status['title'] = 'Restore database gagal!';
			echo json_encode($status);
			exit();
		}
		$media = $this->upload->data();
		$zip_file = 'assets/files/'.$media['file_name'];
		$nama_folder = str_replace('.zip','',$media['file_name']);
		$full_folder = str_replace('.zip','','files/'.$nama_folder);
		$extracts = $this->unzip->extract($zip_file, 'files/restore');
		if($extracts){
			$tujuan = $extracts[0];
			$string_query = file_get_contents($tujuan);
			$string_query = preg_replace('/\s+/', ' ', $string_query);
			$string_query = str_replace('# #', '<a href=test>', $string_query);
			$string_query = str_replace('#', '</a>', $string_query);
			$string_query = preg_replace('#(<a.*?>).*?(</a>)#', '$1$2', $string_query);
			$string_query = str_replace('<a href=test></a>', '', $string_query);
			$array_query = explode(";", $string_query);
			$asli = count($array_query);
			$pembagi = 100;
			$dibagi = $asli / $pembagi;
			$dibagi = number_format($dibagi,0);
			for ($i = 1; $i <= $dibagi; $i++) {
				$a = ($i - 1);
				$a = $a.'00';
				$result = array_slice($array_query, $a, $pembagi);   // returns "a", "b", and "c"
				$output = '';
				foreach($result as $query){
					$output .= $query.";\n";
				}
				$output = rtrim( $output, "\n;\n" );
				$output = rtrim( $output, "\n " );
				file_put_contents('assets/temp/import_'.$i.'.sql', $output);
				$persentase = (1 / $dibagi * 100);
				$set_result['persen'] = round($persentase,0);
				$set_result['jumlah'] = $dibagi;
				$set_result['result'][] = $i;
			}
		} else {
			$set_result['jumlah'] = 0;
			$set_result['result'] = array();
		}
		echo json_encode($set_result);
	}
	public function proses(){
		$this->load->helper('file');
		$total = $_POST['total'];
		$parameter = $_POST['parameter'];
		$parameter = str_replace('"','',$parameter);
		$persentase = ($parameter / $total * 100);
		$file = file_get_contents('assets/temp/import_'.$parameter.'.sql');
		$file = rtrim( $file, ";" );
		$file = explode(";", $file);
		foreach($file as $query){
			$this->db->query($query);
		}
		$output['total'] = $total;
		$output['persen'] = round($persentase,0);
		$output['parameter'] = $parameter;
		$output['type'] = 'success';
		$output['text'] = 'Sukses';
		$output['title'] = 'Restore berhasil!';
		echo json_encode($output);
		unlink('assets/temp/import_'.$parameter.'.sql');
	}
}