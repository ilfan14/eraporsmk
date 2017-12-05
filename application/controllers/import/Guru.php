<?php
class Guru extends Backend_Controller {
	function __construct()  {
         parent::__construct();
            $this->load->library('excel');
			$this->load->library('ion_auth');
			$this->load->model('ion_auth_model');
			$this->load->library('custom_fuction');
			$this->load->library('session');
    }
	public function index(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$sekolah = Datasekolah::find($loggeduser->data_sekolah_id);
		$status=array();
		$date   = new DateTime;
		$fileName = $_FILES['import']['name'];
		$config['upload_path'] = './assets/files/';
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'xls|xlsx';
		$config['overwrite'] = TRUE;
		$this->load->library('upload');
        $this->upload->initialize($config);
		if(!$this->upload->do_upload('import')){
			$status['type'] = 'error';
			$status['text'] = $this->upload->display_errors();
			$status['title'] = 'Import Data Gagal!';
			echo json_encode($status);
			exit();
		}
		$media = $this->upload->data();
		$inputFileName = './assets/files/'.$media['file_name'];
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
		$status['highestColumn'] = $highestColumn;
		$status['highestRow'] = $highestRow;
		$status['sheet'] = $sheet;
		$status['nrColumns'] = $nrColumns;
		if($highestColumn == 'R') { // Import data guru
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
				$flat = call_user_func_array('array_merge', $InsertData);
				$data_sekolah_id = array("data_sekolah_id"=>$loggeduser->data_sekolah_id);
				$active = array("active"=> 1);
				$photo = array("photo"=>'');
				$password	= array("password"=>12345678);
				$petugas = array("petugas"=> $loggeduser->username);
				$masukkan[] = array_merge($flat,$data_sekolah_id,$active,$photo,$password,$petugas);
			}
			$jumlah_data_import = count($masukkan);
			$sukses=0;
			$exists = 0;
			$gagal = 0;
			$status_gagal_insert_user = '';
		foreach($masukkan as $k=>$v){
		if($v['nuptk'] == ''){
			//$a = Dataguru::all(array('conditions' => array('nama = ? AND tanggal_lahir = ?', $v['nama'], $v['tanggal_lahir'])));
			$data_guru = Dataguru::find_by_nama_and_tanggal_lahir($v['nama'], $v['tanggal_lahir']);
		} else {
			$data_guru = Dataguru::find_by_nama_and_nuptk($v['nama'], $v['nuptk']);
		}
			//$sum+=count($a);
			if($data_guru){
				$exists++;
			} else {
				$GenerateNUPTK = $this->custom_fuction->GenerateID();
				if($v['nuptk'] == ''){
					$v['nuptk'] = $GenerateNUPTK;
				} else {
					$v['nuptk'] = $v['nuptk'];
				}
				$username 	= $v['nama'];
				$password 	= $v['password'];
				$email		= ($v['email'] ? $v['email'] : $this->custom_fuction->GenerateEmail().'@cybereducation.co.id');
				$additional_data = array(
					"data_sekolah_id"=> $v['data_sekolah_id'],
					"nuptk"=> $v['nuptk'],
				);
				$group = array('3');
				$user_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
				if($user_id){
					$sukses++;
					$id_guru = array('user_id'=>$user_id,'data_sekolah_id'=>$loggeduser->data_sekolah_id,'email'=>$email);
					$insert_guru = array_merge($id_guru,$v);
					$dataguru = Dataguru::create($insert_guru);
					$find_guru_aktif = GuruAktif::find_by_dataguru_id_and_ajaran_id($dataguru->id,$ajaran->id);
					if($find_guru_aktif){
						$update_guru_aktif = array(
							'status' => 1
						);
						$find_guru_aktif->update_attributes($update_guru_aktif);
					} else {
						$attributes = array('ajaran_id' => $ajaran->id, 'dataguru_id' => $dataguru->id, 'status' => 1);			
						$guru_aktif = GuruAktif::create($attributes);
					}
				} else {
					$gagal++;
					$status_gagal_insert_user = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				}
			}
		}
		$status['text']	= '<table width="100%" class="table">
				<tr>
					<td class="text-center">Jumlah Data</td>
					<td class="text-center">Status</td>
				</tr>
				<tr>
					<td>'.$sukses.'</td>
					<td>Data sukses disimpan</td>
				<tr>
					<td>'.$exists.'</td>
					<td>Data sudah ada</td>
				</tr>
				<tr>
					<td>'.$gagal.'</td>
					<td>Gagal<br />'.$status_gagal_insert_user.'</td>
				</tr>
				</table>';
		$status['type'] = 'success';
		$status['title'] = 'Import Data Sukses!';
	} else {
		$status['type'] = 'error';
		$status['text'] = 'Format Import tidak sesuai. Silahkan download template yang telah disediakan.';
		$status['title'] = 'Import Data Gagal!';
	}
	unlink($inputFileName);
	echo json_encode($status);
	}
}