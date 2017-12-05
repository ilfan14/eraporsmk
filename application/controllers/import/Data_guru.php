<?php
class Data_guru extends Backend_Controller {
	function __construct()  {
         parent::__construct();
            $this->load->library('excel');
			$this->load->library('ion_auth');
			$this->load->model('ion_auth_model');
			$this->load->library('session');
    }
	public function index(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$sekolah = $this->sekolah->get($loggeduser->sekolah_id);
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
				$sekolah_id = array("sekolah_id"=>$loggeduser->sekolah_id);
				$active = array("active"=> 1);
				$photo = array("photo"=>'');
				$password	= array("password"=>12345678);
				$petugas = array("petugas"=> $loggeduser->username);
				$masukkan[] = array_merge($flat,$sekolah_id,$active,$photo,$password,$petugas);
			}
			$jumlah_data_import = count($masukkan);
			$sukses=0;
			$exists = 0;
			$gagal = 0;
			$status_gagal_insert_user = '';
		foreach($masukkan as $k=>$v){
			$status_kepegawaian = $this->status_kepegawaian->find_by_nama($v['status_kepegawaian']);
			$agama = $this->agama->find_by_nama($v['agama']);
			$jenis_ptk = $this->jenis_ptk->find_by_nama($v['jenis_ptk']);
			$v['status_kepegawaian_id'] = ($status_kepegawaian) ? $status_kepegawaian->id : 0;
			$v['agama_id'] = ($agama) ? $agama->id : 0;
			$v['jenis_ptk'] = ($jenis_ptk) ? $jenis_ptk->id : 0;
			unset($v['status_kepegawaian'], $v['agama']);
			if($v['nuptk'] == ''){
				//$a = Dataguru::all(array('conditions' => array('nama = ? AND tanggal_lahir = ?', $v['nama'], $v['tanggal_lahir'])));
				$data_guru = $this->guru->find("nama = '$v[nama]' AND tanggal_lahir = '$v[tanggal_lahir]'");
				//Dataguru::find_by_nama_and_tanggal_lahir($v['nama'], $v['tanggal_lahir']);
			} else {
				$data_guru = $this->guru->find("nama = '$v[nama]' AND nuptk = '$v[nuptk]'");
				//Dataguru::find_by_nama_and_nuptk($v['nama'], $v['nuptk']);
			}
			//$sum+=count($a);
			if($data_guru){
				$exists++;
			} else {
				$GenerateNUPTK = GenerateID();
				if($v['nuptk'] == ''){
					$v['nuptk'] = $GenerateNUPTK;
				} else {
					$v['nuptk'] = $v['nuptk'];
				}
				$username 	= $v['nama'];
				$password 	= $v['password'];
				$email		= ($v['email'] ? $v['email'] : GenerateEmail().'@eraporsmk.net');
				$additional_data = array(
					"sekolah_id"=> $v['sekolah_id'],
					"nuptk"		=> $v['nuptk'],
				);
				$group = array('3');
				$user_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
				if($user_id){
					$sukses++;
					$id_guru = array('user_id'=>$user_id,'sekolah_id'=>$loggeduser->sekolah_id,'email'=>$email);
					$insert_guru = array_merge($id_guru,$v);
					$dataguru = $this->guru->insert($insert_guru);
					$find_guru_aktif = $this->guru_terdaftar->find("guru_id = $dataguru and semester_id = $ajaran->id");
					//GuruAktif::find_by_dataguru_id_and_ajaran_id($dataguru->id,$ajaran->id);
					if($find_guru_aktif){
						$update_guru_aktif = array(
							'status' => 1
						);
						$this->guru_terdaftar->update($find_guru_aktif->id, $update_guru_aktif);
					} else {
						$attributes = array('semester_id' => $ajaran->id, 'guru_id' => $dataguru, 'status' => 1);			
						$guru_aktif = $this->guru_terdaftar->insert($attributes);
					}
					$this->user->update($user_id, array('guru_id' => $dataguru));
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