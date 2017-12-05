<?php
class Import_nilai extends Backend_Controller {
	function __construct()  {
         parent::__construct();
            $this->load->library('excel');
    }
	public function index(){
		$result=array();
		$fileName = $_FILES['import']['name'];
		$config['upload_path'] = './assets/files/';
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'xls|xlsx';
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('import')){
			$status['type'] = 'error';
			$status['text'] = $this->upload->display_errors();
			$status['title'] = 'Import Data Gagal!';
			echo json_encode($status);
			exit;
		}
		$media = $this->upload->data();
		$inputFileName = './assets/files/'.$media['file_name'];
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$result['highestColumn'] = $highestColumn;
		for ($row = 9; $row <= $highestRow; $row++){ //  Read a row of data into an array
			$cellValues = $objPHPExcel->getActiveSheet()->rangeToArray('D' . $row . ':'. $highestColumn . $row);
			$record = array();
			$record['nilai'] = $cellValues[0];
			$record['rerata'] = number_format( array_sum($cellValues[0]) / count($cellValues[0]), 0);
			$result[] = $record;
			//$result[] = $cellValues[0];
		}
		echo json_encode($result);
		$this->load->helper('file');
		delete_files($media['file_path']);
	}
}