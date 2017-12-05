<?php
class Data_siswa extends Backend_Controller {
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
		$importdata = $_REQUEST['data'];
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
		if($highestColumn == 'AD') { // Import data siswa
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
				$masukkan[] = array_merge($sekolah_id,$flat,$active,$photo,$password,$petugas);
			}
			$jumlah_data_import = count($masukkan);
			$insert=0;
			$exists = 0;
			$failed = 0;
			$gagal_insert_user = '';
		foreach($masukkan as $k=>$v){
			$nama_siswa 		= addslashes($v['nama']);
			$a = $this->siswa->find("nisn = '$v[nisn]' AND nama = '$nama_siswa'");
			//Datasiswa::all(array('conditions' => array('nisn = ? AND nama = ?', $v['nisn'], $v['nama'])));
			if(!$a){
				$GenerateNISN = GenerateNISN();
				if($v['nisn'] == ''){
					$v['nisn'] = $GenerateNISN;
				} else {
					$v['nisn'] = $v['nisn'];
				}
				$username 	= $nama_siswa;
				$password 	= $v['password'];
				$email		= ($v['email'] ? $v['email'] : GenerateEmail().'@eraporsmk.net');
				$additional_data = array(
					"sekolah_id"	=> $v['sekolah_id'],
					"nisn"			=> $v['nisn'],
				);
				$group = array('4');
				$user_id = $this->ion_auth->register($username, $password, $email, $additional_data,$group);
				if($user_id){
					$id_siswa = array('user_id'=>$user_id,'sekolah_id'=>$loggeduser->sekolah_id);
					$v['nama'] = stripslashes($nama_siswa);
					$insert_siswa = $v;
					unset($insert_siswa['rombel']);
					unset($insert_siswa['tingkat']);
					$insert_siswa = array_merge($id_siswa,$insert_siswa);
					$datasiswa = $this->siswa->insert($insert_siswa);
					//Datasiswa::create($insert_siswa);
					$rombel = $this->rombongan_belajar->find("tingkat = $v[tingkat] AND nama = '$v[rombel]'");
					//Datarombel::find_all_by_tingkat_and_nama($v['tingkat'],$v['rombel']);
					if($rombel){
						$attributes = array(
							'semester_id' => $ajaran->id, 
							'rombongan_belajar_id' => $rombel->id, 
							'siswa_id' => $datasiswa,
						);
						$anggota = $this->anggota_rombel->insert($attributes);
					} else {
						$datarombelbawah = array(
							'semester_id' => $ajaran->id, 
							'sekolah_id'	=> $loggeduser->sekolah_id,
							'petugas'		=> $loggeduser->username,
							'tingkat'		=> $v['tingkat'],
							'kurikulum_id'	=> 0,
							'jurusan_sp_id'	=> 0,
							'nama'			=> $v['rombel']
						);
						$rombel_id = $this->rombongan_belajar->insert($datarombelbawah);
						$attributes = array('semester_id' => $ajaran->id, 'rombongan_belajar_id' => $rombel_id, 'siswa_id' => $datasiswa);
						$anggota = $this->anggota_rombel->insert($attributes);
					}
					$updatedata = array('siswa_id'=>$datasiswa);
					$this->db->where('id', $user_id);
					$this->db->update('users', $updatedata);
					$insert++;
				} else {
					$failed++;
					$gagal_insert_user = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				}
			} else {
				$exists++;
			}
		}
		$status['text']	= '<table width="100%" class="table">
				<tr>
					<td class="text-center">Jumlah Data</td>
					<td class="text-center">Status</td>
				</tr>
				<tr>
					<td>'.$insert.'</td>
					<td>Data sukses disimpan</td>
				<tr>
					<td>'.$exists.'</td>
					<td>Data sudah ada</td>
				</tr>
				<tr>
					<td>'.$failed.'</td>
					<td>Gagal<br />'.$gagal_insert_user.'</td>
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