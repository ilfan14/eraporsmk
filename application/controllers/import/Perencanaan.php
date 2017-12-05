<?php
class Perencanaan extends Backend_Controller {
	function __construct()  {
         parent::__construct();
    }
	public function index(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$guru_id = ($loggeduser->guru_id) ? $loggeduser->guru_id : 0;
		$config['upload_path'] = './assets/files/';
		//$config['file_name'] = $fileName;
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
		//test($media);
		require_once APPPATH."/third_party/simplexlsx.class.php"; 
		if ( $xlsx = SimpleXLSX::parse('./assets/files/'.$media['file_name'])) {
			$get_rows = $xlsx->rows();
			$get_kd = $get_rows[0];
			$akhir_kolom = count($get_rows[0]) - 1;
			$get_kd_id = $get_rows[1];
			$mata_pelajaran_id 		= $get_rows[0][0];
			$rombongan_belajar_id 	= $get_rows[0][1];
			$kompetensi_id			= $get_rows[0][2];
			$redirect = 'pengetahuan';
			if($kompetensi_id == 2){
				$redirect = 'keterampilan';
			}
			//test($get_rows);
			$find_import_rencana = $this->import_rencana->find("semester_id = $ajaran->id AND kompetensi_id = $kompetensi_id AND rombongan_belajar_id = $rombongan_belajar_id AND mata_pelajaran_id = $mata_pelajaran_id AND guru_id = $guru_id");
			if($find_import_rencana){
				$status['type'] = 'error';
				$status['text'] = 'Import perencanaan dibatasi 1 (satu) kali per semester. Jika ingin menambah perencanaan, silahkan lakukan secara manual.';
				$status['title'] = 'Import Data Gagal!';
				echo json_encode($status);
				exit;
			} else {
				$insert_import_rencana = array(
					'semester_id'			=> $ajaran->id,
					'kompetensi_id' 		=> $kompetensi_id,
					'rombongan_belajar_id' 	=> $rombongan_belajar_id,
					'mata_pelajaran_id'		=> $mata_pelajaran_id,
					'guru_id'				=> $guru_id,
				);
				$this->import_rencana->insert($insert_import_rencana);
			}
			unset($get_rows[0], $get_rows[1], $get_kd[0], $get_kd[1], $get_kd[2], $get_kd[$akhir_kolom], $get_kd_id[0], $get_kd_id[1], $get_kd_id[2], $get_kd_id[$akhir_kolom]);
			$all_kd = count($get_kd);
			$sukses = 0;
			$gagal = 0;
			foreach($get_rows as $data){
				$get_metode = $this->teknik_penilaian->find("kompetensi_id = $kompetensi_id AND nama = '$data[1]'");
				$metode_id = ($get_metode) ? $get_metode->id : 0;
				if($data[0] && $metode_id){
					$data_insert_rencana = array(
						'semester_id'			=> $ajaran->id,
						'mata_pelajaran_id' 	=> $mata_pelajaran_id,
						'rombongan_belajar_id'	=> $rombongan_belajar_id,
						'kompetensi_id'			=> $kompetensi_id,
						'nama_penilaian'		=> trim($data[0]),
						'metode_id'				=> $metode_id,
						'bobot'					=> trim($data[2]),
						'keterangan'			=> trim($data[$akhir_kolom]),
					);
					$insert_rencana = $this->rencana_penilaian->insert($data_insert_rencana);
					if($insert_rencana){
						$sukses++;
						foreach($get_kd as $k=>$kd){
							if($data[$k]){
								$insert_kd_nilai = array(
									'rencana_penilaian_id' 	=> $insert_rencana,
									'id_kompetensi' 		=> str_replace('kd_','',$get_kd_id[$k]),//$get_post_kd[1],
									'kd_id' 				=> $kd,//$get_post_kd[0],
								);
								//test($insert_kd_nilai);
								$this->kd_nilai->insert($insert_kd_nilai);
							}
						}
					} else {
						$gagal++;
					}
				} else {
					$gagal++;
				}
			}
			$status['redirect'] = site_url('admin/perencanaan/'.$redirect);
			$status['type'] = 'success';
			$status['title'] = 'Import Data Berhasil!';
			$status['text'] = $sukses.' perencanaan berhasil disimpan.';//, '.$gagal.' perencanaan gagal disimpan';
		} else {
			$status['redirect'] = '';
			$status['type'] = 'error';
			$status['text'] = 'Format Import tidak sesuai. Silahkan download template yang telah disediakan.';
			$status['title'] = 'Import Data Gagal!';
		}
		echo json_encode($status);
	}
}