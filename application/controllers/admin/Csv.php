<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Csv extends Backend_Controller { 
	protected $activemenu = 'calon'; 
	public function __construct() {
		parent::__construct();
		//$this->load->model('kompetensi_keahlian_model','kompetensi_keahlian');
	}
	public function index(){
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Excel File')
		->build($this->admin_folder.'/excel/list');
	}
	public function list_csv(){
		$this->load->helper('directory');
		$user = $this->ion_auth->user()->row();
		$search = "";
		$start = 0;
		$rows = 10;

		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		$user = $this->ion_auth->user()->row();
		$group = array(1,2,3,4,5);
		if($this->ion_auth->in_group($group)){
			$data = directory_map('./files/excel/');
			//test($data);
			foreach($data as $d){
				
				$maps[] = $d;
			}
		} else {
			$maps = array();
		}
		sort($maps);
		$data = array_slice($maps, $start, $rows, true);
		if($search){
			$input = preg_quote($search, '~'); // don't forget to quote input string!
			$query = preg_grep('~' . $input . '~', $maps);
			$filter = $query;
		} else {
			$query = $data;
			$filter = $maps;
		}
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
		//test();
			$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $temp);
			$find_data_excel = $this->data_excel->find_by_file($withoutExt);
			if($find_data_excel){
				$btn = '<a class="btn btn-sm btn-danger" href="'.site_url('admin/csv/proses/'.$withoutExt).'">Perbaharui</a>';
				$btn = '<a class="btn btn-sm btn-danger" href="'.site_url('admin/csv/data_calon/'.$withoutExt).'">Perbaharui</a>';
				$status = 'Sudah';
				$jumlah_data = $this->kompetensi_dasar->find_count("excel_id = $find_data_excel->id");
				//$jumlah_data = count($data_calon);
			} else {
				$status = 'Belum';
				$btn = '<a class="btn btn-sm btn-success" href="'.site_url('admin/csv/proses/'.$withoutExt).'">Ekstrak Data</a>';
				$btn = '<a class="btn btn-sm btn-success" href="'.site_url('admin/csv/data_calon/'.$withoutExt).'">Ekstrak Data</a>';
				$btn = '<a class="btn btn-sm btn-success" href="'.site_url('admin/csv/mapel_kur/'.$withoutExt).'">Ekstrak Data</a>';
				$jumlah_data = 0;
			}
			$record = array();
            $record[] = $temp;
            $record[] = '<div class="text-center">'.$jumlah_data.'</div>';
            $record[] = '<div class="text-center">'.$status.'</div>';
            $record[] = '<div class="text-center">'.$btn.'</div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function data_calon($file, $page = 1){
		$per_page = 10;
		$offset = ($page - 1) * $per_page;
		$find_csv = $this->data_excel->find_by_file($file);
		$id_csv = ($find_csv) ? $find_csv->id : '';
		$inserted = $this->kompetensi_dasar->find_count("excel_id = $id_csv");
		$result['inserted'] = $inserted;
		require_once APPPATH."/third_party/simplexlsx.class.php"; 
		if ( $xlsx = SimpleXLSX::parse('./files/excel/'.$file.'.xlsx')) {
			//echo '<h1>$xlsx->rows()</h1>';
			//echo '<pre>';
			//print_r( $xlsx->rows() );
			//echo '</pre>';
		
			//echo '<h1>$xlsx->rowsEx()</h1>';
			//echo '<pre>';
			//print_r( $xlsx->rowsEx() );
			//echo '</pre>';
			$get_rows = $xlsx->rows();
			unset($get_rows[0]);
			foreach($get_rows as $data){
				$output['id_kd'] 				= trim($data[0]);
				$output['aspek'] 				= trim($data[1]);
				$output['nama_mata_pelajaran'] 	= trim($data[2]);
				$output['mata_pelajaran_id'] 	= trim($data[3]);
				$output['kompetensi_dasar'] 	= clean($data[4]);
				$result['csv'][]				= $output;
			}
			$count = count($result['csv']);
			$result['show'] = array_slice($result['csv'], $offset, $per_page, true);
		} else {
			$result['error'] = SimpleXLSX::parse_error();
			$count = 0;
		}
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/csv/data_calon/'.$file);
		$config['total_rows'] = $count;
		$config['per_page'] = $per_page;
		$config['num_links'] = 5;
		$config['uri_segment'] = 5;
		$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next &raquo;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$result['pagination'] = $this->pagination->create_links();
		$result['offset'] = $offset;
		$result['total_rows'] = $count;
		$result['file'] = $file;
		//test($this->dbutil, TRUE);
		$this->load->view('backend/ref_csv',$result);
	}
	public function mapel_kur($file, $page = 1){
		$per_page = 50;
		$offset = ($page - 1) * $per_page;
		$find_csv = $this->data_excel->find_by_file($file);
		$id_csv = ($find_csv) ? $find_csv->id : '';
		//$inserted = $this->kompetensi_dasar->find_count("excel_id = $id_csv");
		$result['inserted'] = $this->mata_pelajaran_kurikulum->find_count("kurikulum_id IS NOT NULL");
		require_once APPPATH."/third_party/simplexlsx.class.php"; 
		if ( $xlsx = SimpleXLSX::parse('./files/excel/'.$file.'.xlsx')) {
			//echo '<h1>$xlsx->rows()</h1>';
			//echo '<pre>';
			//print_r( $xlsx->rows() );
			//echo '</pre>';
		
			//echo '<h1>$xlsx->rowsEx()</h1>';
			//echo '<pre>';
			//print_r( $xlsx->rowsEx() );
			//echo '</pre>';
			$get_rows = $xlsx->rows();
			unset($get_rows[0]);
			foreach($get_rows as $data){
				//test($data);
				//die();
				$output['nama_kurikulum']			= trim($data[1]);
				$output['nama_mata_pelajaran']		= trim($data[2]);
				$output['kurikulum_id']				= trim($data[3]);
				$output['mata_pelajaran_id'] 		= trim($data[4]);
				$output['tingkat_pendidikan_id']	= trim($data[5]);
				$output['a_peminatan']				= trim($data[6]);
				$output['area_kompetensi']			= trim($data[7]);
				$result['csv'][]				= $output;
			}
			$count = count($result['csv']);
			$result['show'] = array_slice($result['csv'], $offset, $per_page, true);
		} else {
			$result['error'] = SimpleXLSX::parse_error();
			$count = 0;
		}
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/csv/mapel_kur/'.$file);
		$config['total_rows'] = $count;
		$config['per_page'] = $per_page;
		$config['num_links'] = 5;
		$config['uri_segment'] = 5;
		$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next &raquo;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$result['pagination'] = $this->pagination->create_links();
		$result['offset'] = $offset;
		$result['total_rows'] = $count;
		$result['file'] = $file;
		//test($this->dbutil, TRUE);
		$this->load->view('backend/mapel_kur',$result);
	}
	public function data_calon_old($file, $page = 1){
		$find_csv = $this->data_excel->find_by_file($file);
		//DataCsv::find_by_file($file);
		$id_csv = ($find_csv) ? $find_csv->id : '';
		$inserted = $this->kompetensi_dasar->find_count("excel_id = $id_csv");
		//RefCalon::count_by_id_csv($id_csv);
		$result['inserted'] = $inserted;
		$this->load->library('excel');
		$inputFileName = './files/excel/'.$file.'.xlsx';
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle = $worksheet->getTitle();
			$highestRow = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		}
		$nrColumns = ord($highestColumn) - 64;
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		foreach ($cellIterator as $k=>$cell) {
			$key[] = $cell->getValue();
		}
		for ($row = 2; $row <= $highestRow; ++ $row) {
			$val = array();
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
				$val[] = $cell->getValue();
			}
			$i=0;
			foreach($val as $k=>$v){
				$InsertData[] = array(
					"$key[$i]"=> $v
					);
				$i++;
			}
			$csvData[] = call_user_func_array('array_merge', $InsertData);
		}
		$per_page = 10;
		$offset = ($page - 1) * $per_page;
		if($inserted){
			//$offset = $inserted;
		}
		if($csvData){
			//die();
			//foreach($csvData as $data){
				/*$start_row = 2; // Mulai dari row 2
				for ($i = 2; $i <= $row; $i++){
				$output['id_kompetensi'] 		= $csvData->val($i, 1); // Mapping Header Colom B ke field name
				$output['id_kd'] 				= $csvData->val($i, 2); // Mapping Header Colom B ke field name
				$output['aspek'] 				= $csvData->val($i, 3); // Mapping Header Colom B ke field name
				$output['kelompok_mapel'] 		= $csvData->val($i, 4); // Mapping Header Colom B ke field name
				$output['nama_mata_pelajaran'] 	= $csvData->val($i, 5); // Mapping Header Colom B ke field name
				$output['kd'] 					= $csvData->val($i, 6); // Mapping Header Colom B ke field name*/
			foreach($csvData as $data){
				$output['id_kompetensi'] 		= trim($data['id_kompetensi']);
				$output['jurusan_id'] 			= trim($data['jurusan_id']);
				$output['kurikulum_id'] 		= trim($data['kurikulum_id']);
				$output['id_kd'] 				= trim($data['id_kd']);
				$output['aspek'] 				= trim($data['aspek']);
				$output['nama_mata_pelajaran'] 	= trim($data['nama_mata_pelajaran']);
				$output['mata_pelajaran_id'] 	= trim($data['mata_pelajaran_id']);
				$output['kompetensi_dasar'] 	= trim($data['kompetensi_dasar']);
				$result['csv'][]				= $output;
			}
			$count = count($result['csv']);
			$result['show'] = array_slice($result['csv'], $offset, $per_page, true);
			//array_slice($result['dbf'], $offset, $per_page, true);
		} else {
			$count = 0;
		}
		//echo $row;
		//test($result['csv']);
		//test($result['show']);
		//die();
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/csv/data_calon/'.$file);
		$config['total_rows'] = $count;
		$config['per_page'] = $per_page;
		$config['num_links'] = 5;
		$config['uri_segment'] = 5;
		$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next &raquo;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$result['pagination'] = $this->pagination->create_links();
		$result['offset'] = $offset;
		$result['total_rows'] = $count;
		$result['file'] = $file;
		//test($this->dbutil, TRUE);
		$this->load->view('backend/ref_csv',$result);
	}
}
