<?php
class Rombel extends Backend_Controller {
	function __construct()  {
         parent::__construct();
            $this->load->library('excel');
			$this->load->library('ion_auth');
			$this->load->model('ion_auth_model');
			$this->load->library('custom_fuction');
			$this->load->library('session');
    }
	public function index(){
		$loggeduser = $this->ion_auth->user()->row();
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
			exit;
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
		if($highestColumn == 'C') { // Import data siswa
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
				$masukkan[] = $flat;
			}
			$jumlah_data_import = count($masukkan);
		$disimpan = 0;
		$jml_data_sudah_ada = 0;
		$jml_gagal_insert_user = 0;
		foreach($masukkan as $k=>$v){
			//-bidang_keahlian
			//--program_keahlian
			//---kompetensi_keahlian
			$nama_bidang_keahlian		= trim($v['bidang_keahlian']);
			$nama_program_keahlian		= trim($v['program_keahlian']);
			$nama_kompetensi_keahlian	= trim($v['kompetensi_keahlian']);
			$bidang_keahlian = $this->bidang_keahlian->find_by_nama($nama_bidang_keahlian);
			if($bidang_keahlian){
				$program_keahlian = $this->program_keahlian->find("bidang_keahlian_id = $bidang_keahlian->id AND nama = '$nama_program_keahlian'");
				if($program_keahlian){
					$kompetensi_keahlian = $this->kompetensi_keahlian->find("program_keahlian_id = $program_keahlian->id AND nama = '$nama_kompetensi_keahlian'");
					if($kompetensi_keahlian){						
						$jml_data_sudah_ada++;
					} else {
						$insert_kompetensi_keahlian = $this->kompetensi_keahlian->insert(array('program_keahlian_id' => $program_keahlian->id, 'nama' => $nama_kompetensi_keahlian));
						if($insert_kompetensi_keahlian){
							$disimpan++;
						} else {
							$jml_gagal_insert_user++;
						}
					}
				} else {
					$insert_program_keahlian = $this->program_keahlian->insert(array('bidang_keahlian_id' => $bidang_keahlian->id, 'nama' => $nama_program_keahlian));
					if($insert_program_keahlian){
						$kompetensi_keahlian = $this->kompetensi_keahlian->find("program_keahlian_id = $insert_program_keahlian AND nama = '$nama_kompetensi_keahlian'");
						if($kompetensi_keahlian){						
							$jml_data_sudah_ada++;
						} else {
							$insert_kompetensi_keahlian = $this->kompetensi_keahlian->insert(array('program_keahlian_id' => $insert_program_keahlian, 'nama' => $nama_kompetensi_keahlian));
							if($insert_kompetensi_keahlian){
								$disimpan++;
							} else {
								$jml_gagal_insert_user++;
							}
						}
					} else {
						$jml_gagal_insert_user++;
					}
				}
			} else {
				$inser_bidang_keahlian = $this->bidang_keahlian->insert(array('nama' => $nama_bidang_keahlian));
				if($inser_bidang_keahlian){
					$program_keahlian = $this->program_keahlian->find("bidang_keahlian_id = $inser_bidang_keahlian AND nama = '$nama_program_keahlian'");
					if($program_keahlian){
						$kompetensi_keahlian = $this->kompetensi_keahlian->find("program_keahlian_id = $program_keahlian->id AND nama = '$nama_kompetensi_keahlian'");
						if($kompetensi_keahlian){						
							$jml_data_sudah_ada++;
						} else {
							$insert_kompetensi_keahlian = $this->kompetensi_keahlian->insert(array('program_keahlian_id' => $program_keahlian->id, 'nama' => $nama_kompetensi_keahlian));
							if($insert_kompetensi_keahlian){
								$disimpan++;
							} else {
								$jml_gagal_insert_user++;
							}
						}
					} else {
						$insert_program_keahlian = $this->program_keahlian->insert(array('bidang_keahlian_id' => $inser_bidang_keahlian, 'nama' => $nama_program_keahlian));
						if($insert_program_keahlian){
							$kompetensi_keahlian = $this->kompetensi_keahlian->find("program_keahlian_id = $insert_program_keahlian AND nama = '$nama_kompetensi_keahlian'");
							if($kompetensi_keahlian){						
								$jml_data_sudah_ada++;
							} else {
								$insert_kompetensi_keahlian = $this->kompetensi_keahlian->insert(array('program_keahlian_id' => $insert_program_keahlian, 'nama' => $nama_kompetensi_keahlian));
								if($insert_kompetensi_keahlian){
									$disimpan++;
								} else {
									$jml_gagal_insert_user++;
								}
							}
						} else {
							$jml_gagal_insert_user++;
						}
					}
				} else {
					$jml_gagal_insert_user++;
				}
			}
		}
		$status['text']	= '<table width="100%" class="table">
				<tr>
					<td class="text-center">Jumlah Data</td>
					<td class="text-center">Status</td>
				</tr>
				<tr>
					<td>'.$disimpan.'</td>
					<td>Data sukses disimpan</td>
				<tr>
					<td>'.$jml_data_sudah_ada.'</td>
					<td>Data sudah ada</td>
				</tr>
				<tr>
					<td>'.$jml_gagal_insert_user.'</td>
					<td>Gagal menambah data kompetensi dengan data existing</td>
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