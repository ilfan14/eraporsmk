<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Ajax extends Backend_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function index(){
		show_error('Akses tidak sah');
	}
	public function get_kurikulum(){
		$jurusan_sp_id = $this->input->post('jurusan_sp_id');
		$get_kurikulum = $this->kurikulum->find_all_by_jurusan_id($jurusan_sp_id);
		if($get_kurikulum){
			foreach($get_kurikulum as $kurikulum){
				$record= array();
				$record['value'] 	= $kurikulum->kurikulum_id;
				$record['text'] 	= $kurikulum->nama_kurikulum;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan kurikulum dibawah jurusan terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_kurikulum_new(){
		//test($_POST);
		$jurusan_sp_id = $this->input->post('jurusan_id');
		$get_kurikulum = $this->kurikulum->find_all_by_jurusan_id($jurusan_sp_id);
		if($get_kurikulum){
			foreach($get_kurikulum as $kurikulum){
				$record= array();
				$record['value'] 	= $kurikulum->kurikulum_id;
				$record['text'] 	= $kurikulum->nama_kurikulum;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan kurikulum dibawah jurusan terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_ppk(){
		$posisi = $this->input->post('posisi');
		if($posisi == 1){
			$get_ekskul= $this->ekstrakurikuler->get_all();
			if($get_ekskul){
				foreach($get_ekskul as $ekskul){
					$record= array();
					$record['value'] 	= $ekskul->id;
					$record['text'] 	= $ekskul->nama_ekskul;
					//$record['value_guru'] 	= $ekskul->guru_id;
					//$record['text_guru'] 	= get_nama_guru($ekskul->guru_id);
					$output['result'][] = $record;
				}
			} else {
				$record['value'] 	= '';
				$record['text'] 	= 'Tidak ditemukan jenis PPK';
				$output['result'][] = $record;
			}
			echo json_encode($output);
		}
	}
	public function get_guru_ekskul(){
		$id = $this->input->post('id_kegiatan');
		$get_ekskul= $this->ekstrakurikuler->get($id);
		$guru_id = ($get_ekskul) ? $get_ekskul->guru_id : '';
		echo $guru_id;
	}
	public function get_kurikulum_old(){
		$rombel_id = $_POST['rombel_id'];
		$data_rombel = Datarombel::find($rombel_id);
		$tingkat = $data_rombel->tingkat;
		$kurikulum_id = $data_rombel->kurikulum_id;
		$kelas_X	= 0;
		$kelas_XI	= 0;
		$kelas_XII	= 0;
		$kelas_XIII	= 0;
		$k1 = 0;
		$k2 = 0;
		$k3 = 0;
		if($tingkat == 10){
			$query_kelas = 'kelas_X';
		} elseif($tingkat == 11){
			$query_kelas = 'kelas_XI';
		} elseif($tingkat == 12){
			$query_kelas = 'kelas_XII';
		} elseif($tingkat == 13){
			$query_kelas = 'kelas_XIII';
		}
		$all_mapel = Matpelkomp::find('all', array("conditions" => "kurikulum_id = $kurikulum_id AND  $query_kelas = 1"));
		if($all_mapel){
			foreach($all_mapel as $mapel){
				$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
				$record= array();
				$record['value'] 	= $mapel->id_mapel;
				$record['text'] 	= $data_mapel->nama_mapel.' ('.$data_mapel->id_mapel.')';
				$record['nama_kur'] = $data_mapel->kur;
				$output['mapel'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan mata pelajaran di kelas terpilih';
			$output['mapel'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_nama_kur_kurikulum(){
		if($_POST){
			$id_mapel = $_POST['mapel_id'];
			$data_mapel = Datamapel::find_by_id_mapel($id_mapel);
			echo $data_mapel->kur;
		}
	}
	public function get_rapor(){
		$data['ajaran_id'] = $this->input->post('ajaran_id');
		$data['rombel_id'] = $this->input->post('rombel_id');
		$rombel = $this->rombongan_belajar->get($this->input->post('rombel_id'));
		if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
			$data['nama_kompetensi'] = 2017;
		} elseif (strpos(get_kurikulum($rombel->kurikulum_id), '2013') !== false) {
			$data['nama_kompetensi'] = 2013;
		} else {
			$data['nama_kompetensi'] = 'ktsp';
		}
		if($this->ion_auth->in_group('siswa')){
			$data['siswa_id'] = $_POST['siswa_id'];
			$file = 'rapor_siswa';
		} else {
			$file = 'rapor';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', 'Cetak Rapor')
		->build($this->admin_folder.'/cetak/'.$file,$data);
	}
	public function get_legger(){
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['rombel_id'] = $_POST['rombel_id'];
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', 'Cetak Legger')
		->build($this->admin_folder.'/cetak/legger',$data);
	}
	public function get_absensi(){
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['rombel_id'] = $_POST['rombel_id'];
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', 'Entry Absensi')
		->build($this->admin_folder.'/laporan/add_absensi',$data);
	}
	public function get_ekstrakurikuler(){
		$ajaran_id = $_POST['ajaran_id'];
		$all_ekskul = Ekskul::find_all_by_ajaran_id($ajaran_id);
		if($all_ekskul){
			foreach($all_ekskul as $ekskul){
				$record= array();
				$record['value'] 	= $ekskul->id;
				$record['text'] 	= $ekskul->nama_ekskul;
				$output['ekskul'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan ekstrakurikuler di rombel terpilih';
			$output['ekskul'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_prakerin(){
		$rombel_id = $_POST['rombel_id'];
		$all_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id);
		if($all_siswa){
			foreach($all_siswa as $siswa){
				$record= array();
				$record['value'] 	= $siswa->id;
				$record['text'] 	= $siswa->nama;
				$output['siswa'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan siswa di rombel terpilih';
			$output['siswa'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_prestasi(){
		$rombel_id = $_POST['rombel_id'];
		$all_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id);
		if($all_siswa){
			foreach($all_siswa as $siswa){
				$record= array();
				$record['value'] 	= $siswa->id;
				$record['text'] 	= $siswa->nama;
				$output['siswa'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan siswa di rombel terpilih';
			$output['siswa'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_analisis_individu($id = NULL){
		$html = '';
		if($id){
			$ajaran = get_ta();
			$siswa = $this->siswa->get($id);
			$mata_pelajaran_id = $_POST['id_mapel'];
			$anggota_rombel = $this->anggota_rombel->find("semester_id = $ajaran->id AND siswa_id = $siswa->id");
			//Anggotarombel::find_by_ajaran_id_and_datasiswa_id($ajaran->id, $id);
			//$mapel = Datamapel::find_by_id_mapel($_POST['id_mapel']);
			//$rombel_id = isset($anggota_rombel->rombel_id) ? $anggota_rombel->rombel_id : 0;
			//$data_rombel = Datarombel::find_by_id($rombel_id);
			$nilai_pengetahuan = $this->nilai->find_all("semester_id = $ajaran->id AND kompetensi_id = 1 AND rombongan_belajar_id = $anggota_rombel->rombongan_belajar_id AND mata_pelajaran_id = $mata_pelajaran_id AND siswa_id = $id");
			//Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 1, $rombel_id, $mapel->id_mapel, $siswa->id);
			$nilai_keterampilan = $this->nilai->find_all("semester_id = $ajaran->id AND kompetensi_id = 2 AND rombongan_belajar_id = $anggota_rombel->rombongan_belajar_id AND mata_pelajaran_id = $mata_pelajaran_id AND siswa_id = $id");
			//Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 2, $rombel_id, $mapel->id_mapel, $siswa->id);
			$html .= '<table class="table table-bordered table-hover">';
			$html .= '<tr>';
			$html .= '<th width="5%" class="text-center">ID KD</th>';
			$html .= '<th width="85%">Kompetensi Dasar Pengetahuan</th>';
			$html .= '<th width="10%" class="text-center">Rerata Nilai</th>';
			$html .= '</tr>';
			foreach($nilai_pengetahuan as $np){
				//$rencana_penilaian_pengetahuan = Rencanapenilaian::find_by_id($np->rencana_penilaian_id);
				$get_pengetahuan[$np->kompetensi_id][] = $np->nilai;
			}
			if(isset($get_pengetahuan)){
				ksort($get_pengetahuan);
				$rerata_akhir_p = 0;
				$jumlah_penilaian_p = 0;
				foreach($get_pengetahuan as $key=>$gp){
				//test($get_pengetahuan);
				//die();
					$get_kompetensi_p = $this->kompetensi_dasar->get($key);
					//Kd::find_by_id_kompetensi_and_id_mapel_and_kelas($key,$mapel->id_mapel,$data_rombel->tingkat);
					$jumlah_kd_p = array_sum($gp);
					$rerata_nilai_p = number_format(($jumlah_kd_p / count($gp)),2);
					$rerata_akhir_p += $jumlah_kd_p;
					$jumlah_penilaian_p += count($gp);
					$html .= '<tr>';
					$html .= '<td class="text-center">'.$key.'</td>';
					$html .= '<td>'.$get_kompetensi_p->kompetensi_dasar.'</td>';
					$html .= '<td class="text-center">'.number_format($rerata_nilai_p).'</td>';
					$html .= '</tr>';
				}
			}
			$html .= '<tr>';
			$html .= '<td colspan="2" class="text-right"><strong>Rerata Akhir = </strong></td>';
			if(isset($jumlah_penilaian_p)){
				$html .= '<td class="text-center">'.number_format($rerata_akhir_p / $jumlah_penilaian_p,0).'</td>';
			} else {
				$html .= '<td class="text-center"></td>';
			}
			$html .= '</tr>';
			$html .= '</table>';
			$html .= '<table class="table table-bordered table-hover" style="margin-top:10px;">';
			$html .= '<tr>';
			$html .= '<th width="5%" class="text-center">ID KD</th>';
			$html .= '<th width="85%">Kompetensi Dasar Keterampilan</th>';
			$html .= '<th width="10%" class="text-center">Rerata Nilai</th>';
			$html .= '</tr>';
			foreach($nilai_keterampilan as $nk){
				$get_keterampilan[$nk->kompetensi_id][] = $np->nilai;
			}
			if(isset($get_keterampilan)){
				ksort($get_keterampilan);
				$rerata_akhir_k = 0;
				$jumlah_penilaian_k = 0;
				foreach($get_keterampilan as $key=>$gk){
					$get_kompetensi_k = $this->kompetensi_dasar->get($key);
					$jumlah_kd_k = array_sum($gk);
					$rerata_nilai_k = number_format(($jumlah_kd_k / count($gk)),2);
					$rerata_akhir_k += $jumlah_kd_k;
					$jumlah_penilaian_k += count($gk);
					$html .= '<tr>';
					$html .= '<td class="text-center">'.$key.'</td>';
					$html .= '<td>'.$get_kompetensi_k->kompetensi_dasar.'</td>';
					$html .= '<td class="text-center">'.number_format($rerata_nilai_k).'</td>';
					$html .= '</tr>';
				}
			}
			$html .= '<tr>';
			$html .= '<td colspan="2" class="text-right"><strong>Rerata Akhir = </strong></td>';
			if(isset($jumlah_penilaian_k)){
				$html .= '<td class="text-center">'.number_format($rerata_akhir_k / $jumlah_penilaian_k,0).'</td>';
			} else {
				$html .= '<td class="text-center"></td>';
			}
			$html .= '</tr>';
			$html .= '</table>';
			echo $html;
		} else {
			//exit;
			$ajaran_id = $_POST['ajaran_id'];
			$id_mapel = $_POST['id_mapel'];
			$rombel_id = $_POST['rombel_id'];
			$siswa_id = $_POST['siswa_id'];
			if($siswa_id){
				$siswa = Datasiswa::find_by_id($siswa_id);
				$mapel = Datamapel::find_by_id_mapel($id_mapel);
				$data_rombel = Datarombel::find_by_id($siswa->data_rombel_id);
				$all_kd = Kd::find_all_by_id_mapel_and_kelas($id_mapel,$data_rombel->tingkat);
				foreach($all_kd as $kd){
					$get_kd[$kd->id] = $kd->id_kompetensi;
					$get_kd_alternatif[str_replace(' ','_',$kd->kompetensi_dasar)] = $kd->id_kompetensi;
				}
				if(!isset($get_kd)){
					echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Error!</h4>
					Pilih Mata Pelajaran terlebih dahulu sebelum memilih nama siswa!
				  </div>';
				exit;
				}
			} else {
				//exit;
				$data_siswa = get_siswa_by_rombel($rombel_id);
				if($data_siswa){
			//sort($anggotarombel);
					foreach($data_siswa as $siswa){
						$record= array();
						$record['value'] 	= $siswa->siswa->id;
						$record['text'] 	= $siswa->siswa->nama;
						$output['siswa'][] = $record;
					}
				} else {
					$record['value'] 	= '';
					$record['text'] 	= 'Tidak ditemukan siswa di rombel terpilih';
					$output['siswa'][] = $record;
				}
				echo json_encode($output);
			}
			//exit;
			/*$input_pengetahuan = preg_quote('KD-03', '~'); // don't forget to quote input string!
			$input_alt_pengetahuan = preg_quote('3.', '~'); // don't forget to quote input string!
			$input_keterampilan = preg_quote('KD-04', '~'); // don't forget to quote input string!
			$input_alt_keterampilan = preg_quote('4.', '~'); // don't forget to quote input string!
			$input_all = preg_quote('', '~'); // don't forget to quote input string!
			$result_pengetahuan = preg_grep('~' . $input_pengetahuan . '~', $get_kd);
			if(!$result_pengetahuan){
				$result_pengetahuan = preg_grep('~' . $input_alt_pengetahuan . '~', $get_kd_alternatif);
			}
			$rencana_pengetahuan = Rencana::find_all_by_ajaran_id_and_id_mapel_and_rombel_id_and_kompetensi_id($ajaran_id, $mapel->id_mapel, $rombel_id, 1);
			if($rencana_pengetahuan){
				foreach($rencana_pengetahuan as $rp){
					$get_kd_rencana = Rencanapenilaian::find_all_by_rencana_id($rp->id);
					$get_rencana_penilaian_pengetahuan = Rencanapenilaian::find('all', array('conditions' => "rencana_id = $rp->id",'group' => 'nama_penilaian','order'=>'id ASC'));
				}
				foreach($get_kd_rencana as $a=>$gkr){
					$get_kd_id[$gkr->id] = $gkr->kd_id;
				}
			}
			$html .= '<table class="table table-bordered table-hover">';
			$html .= '<tr>';
			$html .= '<th width="5%" class="text-center">ID KD</th>';
			$html .= '<th>Kompetensi Dasar</th>';
			$nilai_value = '';
			if(isset($get_rencana_penilaian_pengetahuan)){
				foreach($get_rencana_penilaian_pengetahuan as $grpp){
					$html .= '<th class="text-center">'.$grpp->nama_penilaian.'</th>';
				}
				foreach($result_pengetahuan as $key=>$rp){
					$get_kompetensi = Kd::find_by_id($key);
					$html .= '<tr>';
					$html .= '<td class="text-center">'.$rp.'</td>';
					$html .= '<td>'.$get_kompetensi->kompetensi_dasar.'</td>';
					if(in_array($key,$get_kd_id)){
						foreach($get_rencana_penilaian_pengetahuan as $grpp){
							$rencana_satuan = Rencanapenilaian::find_by_kd_id_and_nama_penilaian($get_kompetensi->id,$grpp->nama_penilaian);
							if($rencana_satuan){
							$nilai = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_rencana_penilaian_id( $ajaran_id, 1, $rombel_id, $id_mapel, $siswa_id, $rencana_satuan->id);
							if($nilai){
								$nilai_value = $nilai->nilai;
							}
							} else {
								$nilai_value = '';
							}
							$html .= '<td class="text-center">'.$nilai_value.'</td>';
						}
					} else {
						foreach($get_rencana_penilaian_pengetahuan as $grpp){
							$html .= '<td class="text-center"></td>';
						}
					}
					$html .= '</tr>';
				}
			} else {
				$html .= '<th>Belum ada penilaian</th>';
			}
			$html .= '</tr>';
			$html .= '</table>';

			$result_keterampilan = preg_grep('~' . $input_keterampilan . '~', $get_kd);
			if(!$result_keterampilan){
				$result_keterampilan = preg_grep('~' . $input_alt_keterampilan . '~', $get_kd_alternatif);
			}
			$rencana_keterampilan = Rencana::find_all_by_ajaran_id_and_id_mapel_and_rombel_id_and_kompetensi_id($ajaran_id, $mapel->id_mapel, $rombel_id, 2);
			if($rencana_keterampilan){
				foreach($rencana_keterampilan as $rp){
					$get_kd_rencana = Rencanapenilaian::find_all_by_rencana_id($rp->id);
					$get_rencana_penilaian_keterampilan = Rencanapenilaian::find('all', array('conditions' => "rencana_id = $rp->id",'group' => 'nama_penilaian','order'=>'id ASC'));
				}
				foreach($get_kd_rencana as $a=>$gkr){
					$get_kd_id[$gkr->id] = $gkr->kd_id;
				}
			}
			//foreach($get_rencana_penilaian_keterampilan as $grpp){
				//$cari_nama_penilaian[] = $grpp->nama_penilaian;
			//}
			$html .= '<table class="table table-bordered table-hover" style="margin-top:10px;">';
			$html .= '<tr>';
			$html .= '<th width="5%" class="text-center">ID KD</th>';
			$html .= '<th>Kompetensi Dasar</th>';
			if(isset($get_rencana_penilaian_keterampilan)){
				foreach($get_rencana_penilaian_keterampilan as $grpp){
					$html .= '<th class="text-center">'.$grpp->nama_penilaian.'</th>';
				}
				foreach($result_keterampilan as $key=>$rp){
					$get_kompetensi = Kd::find_by_id($key);
					$html .= '<tr>';
					$html .= '<td class="text-center">'.$rp.'</td>';
					$html .= '<td>'.$get_kompetensi->kompetensi_dasar.'</td>';
					if(in_array($key,$get_kd_id)){
						foreach($get_rencana_penilaian_keterampilan as $grpp){

							$rencana_satuan = Rencanapenilaian::find_by_kd_id_and_nama_penilaian($get_kompetensi->id,$grpp->nama_penilaian);
							if($rencana_satuan){
							$nilai = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_rencana_penilaian_id( $ajaran_id, 2, $rombel_id, $id_mapel, $siswa_id, $rencana_satuan->id);
							if($nilai){
								$nilai_value = $nilai->nilai;
							}
							} else {
								$nilai_value = '';
							}
							$html .= '<td class="text-center">'.$nilai_value.'</td>';
						}
					} else {
						foreach($get_rencana_penilaian_keterampilan as $grpp){
							$html .= '<td class="text-center"></td>';
						}
					}
					$html .= '</tr>';
				}
			} else {
				$html .= '<th>Belum ada penilaian</th>';
			}
			$html .= '</tr>';
			$html .= '</table>';*/		
		}
		//echo $html;
	}
	public function get_kkm(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$query = isset($_POST['query']) ? $_POST['query'] : '';
		$mapel_id = isset($_POST['mapel_id']) ? $_POST['mapel_id'] : '';
		if($mapel_id){
			if($query == 'kkm'){
				$kkm = Kkm::find_by_ajaran_id_and_rombel_id_and_id_mapel($ajaran_id, $rombel_id, $mapel_id);
				echo isset($kkm->kkm) ? $kkm->kkm : '';
			}
		} else {
			$get_mapel = Kurikulum::find_all_by_ajaran_id_and_data_rombel_id($ajaran_id,$rombel_id);
			if($get_mapel){
				foreach($get_mapel as $mapel){
					$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
					$record= array();
					$record['value'] 	= $mapel->id_mapel;
					$record['text'] 	= $data_mapel->nama_mapel.' ('.$data_mapel->id_mapel.')';
					$output['mapel'][] = $record;
				}
			} else {
				$record['value'] 	= '';
				$record['text'] 	= 'Tidak ditemukan mata pelajaran di kelas terpilih';
				$output['mapel'][] = $record;
			}
			echo json_encode($output);
		}
	}
	public function get_undefined(){
		$data_siswa = filter_agama_siswa(get_nama_mapel($_POST['id_mapel']),$_POST['rombel_id']);
		if($data_siswa){
			foreach($data_siswa as $siswa){
				$record= array();
				$record['value'] 	= $siswa->siswa->id;
				$record['text'] 	= $siswa->siswa->nama;
				$output['siswa'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan siswa di rombel terpilih';
			$output['siswa'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_add_kd(){
	}
	public function get_nilai(){
		echo '<style>.simpan{display:none !important;}</style>';
	}
	public function get_remedial(){
		$record[0]['value'] 	= 'P';
		$record[0]['text'] 	= 'Pengetahuan';
		$record[1]['value'] 	= 'K';
		$record[1]['text'] 	= 'Keterampilan';
		$output['result'] = $record;
		echo json_encode($output);
	}
	public function get_sikap(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$siswa_id = $_POST['siswa_id'];
		$this->form_sikap($ajaran_id,$rombel_id,$siswa_id);
	}
	public function get_siswa(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		//,'order'=>'nama ASC'
		$attr_siswa = array('order'=>'nama ASC');
		$all_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id,$attr_siswa);
		$all_mapel = Kurikulum::find_all_by_data_rombel_id($rombel_id);
		//$all_mulok = Mulok1::find_all_by_ajaran_id_and_rombel_id($ajaran_id,$rombel_id);
		if($all_mapel){
			foreach($all_mapel as $mapel){
				$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
				$record= array();
				$record['value'] 	= $mapel->id_mapel;
				$record['text'] 	= $data_mapel->nama_mapel.' ('.$data_mapel->id_mapel.')';
				$output['mapel'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan mata pelajaran di kelas terpilih';
			$output['mapel'][] = $record;
		}
		if($all_siswa){
			foreach($all_siswa as $siswa){
				$record= array();
				$record['value'] 	= $siswa->id;
				$record['text'] 	= $siswa->nama;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan siswa di rombel terpilih';
			$output['result'][] = $record;
		}
		/*if($all_mulok){
			foreach($all_mulok as $mulok){
				$record= array();
				$record['value'] 	= $mulok->id;
				$record['text'] 	= $mulok->nama_mulok;
				$output['mulok'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan mata pelajaran mulok di rombel terpilih';
			$output['mulok'][] = $record;
		}*/
		echo json_encode($output);
	}
	public function get_all_siswa(){
		$search = $_POST['siswa'];
		$all_siswa = $this->siswa->find_all("nama LIKE '%$search%'", '*','nama ASC');
		//Datasiswa::find('all', array('conditions' => "nama LIKE '%$search%'",'order'=>'nama ASC'));
		if($all_siswa){
			foreach($all_siswa as $siswa){
				$record= array();
				$record['value'] 	= $siswa->id;
				$record['name'] 	= $siswa->nama;
				$output[] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['name'] 	= 'Tidak ditemukan siswa di rombel terpilih';
			$output[] = $record;
		}
		echo json_encode($output);
	}
	public function get_deskripsi_antar_mapel(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		//$data_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id);
		$data_siswa = get_siswa_by_rombel($rombel_id);
		$html = '';
		$html .= '<div class="table-responsive no-padding">';
		$html .= '<table class="table table-bordered table-hover">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th width="20%">Nama Siswa</th>';
		$html .= '<th width="80%">Deskripsi</th>';
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		if($data_siswa){
			foreach($data_siswa as $siswa){
				$html .= '<input type="hidden" name="siswa_id[]" value="'.$siswa->id.'" />';
				$deskripsi_antar_mapel = Deskripsimapel::find_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$siswa->id);
				$data_deskripsi = '';
				if($deskripsi_antar_mapel){
					$data_deskripsi .= $deskripsi_antar_mapel->uraian_deskripsi;
				} else {
					$get_deskripsi_per_mapel = Deskripsi::find_all_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$siswa->id);
					if($get_deskripsi_per_mapel){
						foreach($get_deskripsi_per_mapel as $gdpm){
							$data_deskripsi .= 'Pengetahuan : '.$gdpm->deskripsi_pengetahuan;
							$data_deskripsi .= '<br />Keterampilan : '.$gdpm->deskripsi_keterampilan;
						}
					} else {
						$data_deskripsi .= '';//'Belum dilakukan penilaian';
					}
				}
				$html .= '<tr>';
				$html .= '<td>';
				$html .= $siswa->nama.'<br />';
				$html .= $siswa->nisn.'<br />';
				$date = date_create($siswa->tanggal_lahir);
				$html .= date_format($date,'d/m/Y');
				$html .= '</td>';
				$html .= '<td>';
				$html .= '<textarea name="uraian_deskripsi[]" class="editor" style="width: 100%; height: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">';
				$html .= $data_deskripsi;
				$html .= '</textarea>';
				$html .= '</td>';
				$html .= '</tr>';
			}
		} else {
			$html .= '<tr>';
			$html .= '<td colspan="2">Tidak ada data untuk ditampilkan</th>';
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';
		$html .= '<script>';
		$html .= '$(".editor").wysihtml5({';
		$html .= '"font-styles": false,';
		$html .= '"emphasis": true,';
		$html .= '"lists": false,';
		$html .= '"html": false,';
		$html .= '"link": false,';
		$html .= '"image": false,';
		$html .= '"color": false';
		$html .= '});';
		$html .= '</script>';
		echo $html;
	}
	public function get_desc($kd_pengetahuan_tertinggi, $kd_pengetahuan_terendah, $kd_keterampilan_tertinggi, $kd_keterampilan_terendah){
		$result_kd_pengetahuan_tertinggi = '';
		$result_kd_pengetahuan_terendah = '';
		$result_akhir_pengetahuan = '';
		if($kd_pengetahuan_tertinggi || $kd_pengetahuan_terendah){
			$get_kd_pengetahuan_tertinggi	= $this->kompetensi_dasar->get($kd_pengetahuan_tertinggi);
			$get_kd_pengetahuan_terendah	= $this->kompetensi_dasar->get($kd_pengetahuan_terendah);
			if($get_kd_pengetahuan_tertinggi){
				$kd_pengetahuan_tertinggi_value = $get_kd_pengetahuan_tertinggi->kompetensi_dasar;
				if($get_kd_pengetahuan_tertinggi && $get_kd_pengetahuan_tertinggi->kompetensi_dasar_alias){
					$kd_pengetahuan_tertinggi_value = $get_kd_pengetahuan_tertinggi->kompetensi_dasar_alias;
				}
				$result_kd_pengetahuan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($kd_pengetahuan_tertinggi_value);
			}
			if($get_kd_pengetahuan_terendah){
				$kd_pengetahuan_terendah_value = $get_kd_pengetahuan_terendah->kompetensi_dasar;
				if($get_kd_pengetahuan_terendah && $get_kd_pengetahuan_terendah->kompetensi_dasar_alias){
					$kd_pengetahuan_terendah_value = $get_kd_pengetahuan_terendah->kompetensi_dasar_alias;
				}
				$result_kd_pengetahuan_terendah = strtolower($kd_pengetahuan_terendah_value);
			}
			if($kd_pengetahuan_tertinggi != $kd_pengetahuan_terendah){
				if($result_kd_pengetahuan_tertinggi){
					$result_akhir_pengetahuan = ' dan perlu meningkatkan kompetensi '.strtolower($kd_pengetahuan_terendah_value);
				} else {
					$result_akhir_pengetahuan = 'Perlu meningkatkan kompetensi '.strtolower($kd_pengetahuan_terendah_value);
				}
			}
			$result_kd_pengetahuan_terendah = $result_akhir_pengetahuan;
			//($kd_pengetahuan_tertinggi != $kd_pengetahuan_terendah && $result_kd_pengetahuan_tertinggi) ? ' dan perlu meningkatkan kompetensi '.strtolower($kd_pengetahuan_terendah_value) : 'Perlu meningkatkan kompetensi '.strtolower($kd_pengetahuan_terendah_value);
			$record[0] 	= str_replace('<br>','',"$result_kd_pengetahuan_tertinggi $result_kd_pengetahuan_terendah");
		} else {
			$record[0] = 'Belum dilakukan penilaian';
		}
		$result_kd_keterampilan_tertinggi = '';
		$result_kd_keterampilan_terendah = '';
		$result_akhir_keterampilan = '';
		if($kd_keterampilan_tertinggi || $kd_keterampilan_terendah){
			$get_kd_keterampilan_tertinggi	= $this->kompetensi_dasar->get($kd_keterampilan_tertinggi);
			$get_kd_keterampilan_terendah	= $this->kompetensi_dasar->get($kd_keterampilan_terendah);
			if($get_kd_keterampilan_tertinggi){
				$kd_keterampilan_tertinggi_value = $get_kd_keterampilan_tertinggi->kompetensi_dasar;
				if($get_kd_keterampilan_tertinggi && $get_kd_keterampilan_tertinggi->kompetensi_dasar_alias){
					$kd_keterampilan_tertinggi_value = $get_kd_keterampilan_tertinggi->kompetensi_dasar_alias;
				}
				$result_kd_keterampilan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($kd_keterampilan_tertinggi_value);
			}
			if($get_kd_keterampilan_terendah){
				$kd_keterampilan_terendah_value = $get_kd_keterampilan_terendah->kompetensi_dasar;
				if($get_kd_keterampilan_terendah && $get_kd_keterampilan_terendah->kompetensi_dasar_alias){
					$kd_keterampilan_terendah_value = $get_kd_keterampilan_terendah->kompetensi_dasar_alias;
				}
				$result_kd_keterampilan_terendah = strtolower($kd_keterampilan_terendah_value);
			}
			if($kd_keterampilan_tertinggi != $kd_keterampilan_terendah){
				if($result_kd_keterampilan_tertinggi){
					$result_akhir_keterampilan = ' dan perlu meningkatkan kompetensi '.strtolower($kd_keterampilan_terendah_value);
				} else {
					$result_akhir_keterampilan = 'Perlu meningkatkan kompetensi '.strtolower($kd_keterampilan_terendah_value);
				}
			}
			$result_kd_keterampilan_terendah = $result_akhir_keterampilan;
			//($kd_keterampilan_tertinggi != $kd_keterampilan_terendah && $result_kd_keterampilan_tertinggi) ? ' dan perlu meningkatkan kompetensi '.strtolower($kd_keterampilan_terendah_value) : 'Perlu meningkatkan kompetensi '.strtolower($kd_keterampilan_terendah_value);
			$record[1] 	= str_replace('<br>','',"$result_kd_keterampilan_tertinggi $result_kd_keterampilan_terendah");
		} else {
			$record[1] = 'Belum dilakukan penilaian';
		}
		$output['result'] = $record;
		echo json_encode($output);
	}
	public function get_desc_old($ajaran_id,$rombel_id,$link,$id_mapel,$siswa_id){
		$result_kd_pengetahuan_tertinggi = '';
		$result_kd_pengetahuan_terendah = '';
		$result_kd_keterampilan_tertinggi = '';
		$result_kd_keterampilan_terendah = '';
		if($link == 'umum'){
			$nilai_pengetahuan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran_id, 1, $rombel_id, $id_mapel, $siswa_id);
			if($nilai_pengetahuan){
				foreach($nilai_pengetahuan as $nilaipengetahuan){
					$rencana_pengetahuan_id[$siswa_id] = $nilaipengetahuan->rencana_penilaian_id;
					$get_nilai_pengetahuan[] = $nilaipengetahuan->nilai;
				}
				$nilai_pengetahuan_tertinggi = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 1, $rombel_id, $id_mapel, $siswa_id, bilanganBesar($get_nilai_pengetahuan));
				if($nilai_pengetahuan_tertinggi){
					$rencana_penilaian_pengetahuan_tertinggi = Rencanapenilaian::find($nilai_pengetahuan_tertinggi->rencana_penilaian_id);
					$get_kd_pengetahuan_tertinggi = Kd::find_by_id($rencana_penilaian_pengetahuan_tertinggi->kd_id);
					$kd_pengetahuan_tertinggi_value = ($get_kd_pengetahuan_tertinggi) ? $get_kd_pengetahuan_tertinggi->kompetensi_dasar : '';
					if($get_kd_pengetahuan_tertinggi && $get_kd_pengetahuan_tertinggi->kompetensi_dasar_alias){
						$kd_pengetahuan_tertinggi_value = $get_kd_pengetahuan_tertinggi->kompetensi_dasar_alias;
					}
					$result_kd_pengetahuan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($kd_pengetahuan_tertinggi_value);
				}
				$nilai_pengetahuan_terendah = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 1, $rombel_id, $id_mapel, $siswa_id, bilanganKecil($get_nilai_pengetahuan));
				if($nilai_pengetahuan_terendah){
					$rencana_penilaian_pengetahuan_terendah = Rencanapenilaian::find($nilai_pengetahuan_terendah->rencana_penilaian_id);
					$get_kd_pengetahuan_terendah = Kd::find_by_id($rencana_penilaian_pengetahuan_terendah->kd_id);
					$kd_pengetahuan_terendah_value = ($get_kd_pengetahuan_terendah) ? $get_kd_pengetahuan_terendah->kompetensi_dasar : '';
					if($get_kd_pengetahuan_terendah && $get_kd_pengetahuan_terendah->kompetensi_dasar_alias){
						$kd_pengetahuan_terendah_value = $get_kd_pengetahuan_terendah->kompetensi_dasar_alias;
					}
					if(isset($get_kd_pengetahuan_tertinggi->id_kompetensi) && $get_kd_pengetahuan_tertinggi->id_kompetensi == $get_kd_pengetahuan_tertinggi->id_kompetensi){
						$result_kd_pengetahuan_terendah = '';
					} else {
						$result_kd_pengetahuan_terendah = ' Perlu meningkatkan kompetensi '.strtolower($kd_pengetahuan_terendah_value);
						if($result_kd_pengetahuan_tertinggi){
							//echo $result_kd_pengetahuan_tertinggi;
							$result_kd_pengetahuan_terendah = ' dan perlu meningkatkan kompetensi '.strtolower($kd_pengetahuan_terendah_value);
						}
					}
				}
				$record[0] 	= str_replace('<br>','',"$result_kd_pengetahuan_tertinggi $result_kd_pengetahuan_terendah");
				//$record[0] 	= "$get_kd_pengetahuan_tertinggi->id_kompetensi $get_kd_pengetahuan_tertinggi->id_kompetensi";
			} else {
				$record[0] = 'Belum dilakukan penilaian';
			}
			$nilai_keterampilan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran_id, 2, $rombel_id, $id_mapel, $siswa_id);
			if($nilai_keterampilan){
				foreach($nilai_keterampilan as $nilaiketerampilan){
					$rencana_keterampilan_id[$siswa_id] = $nilaiketerampilan->rencana_penilaian_id;
					$get_nilai_keterampilan[] = $nilaiketerampilan->nilai;
				}
				$nilai_keterampilan_tertinggi = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 2, $rombel_id, $id_mapel, $siswa_id, bilanganBesar($get_nilai_keterampilan));
				if($nilai_keterampilan_tertinggi){
					$rencana_penilaian_keterampilan_tertinggi = Rencanapenilaian::find_by_id($nilai_keterampilan_tertinggi->rencana_penilaian_id);
					if($rencana_penilaian_keterampilan_tertinggi){
						$get_kd_keterampilan_tertinggi = Kd::find_by_id($rencana_penilaian_keterampilan_tertinggi->kd_id);
						$kd_keterampilan_tertinggi_value = ($get_kd_keterampilan_tertinggi) ? $get_kd_keterampilan_tertinggi->kompetensi_dasar : '';
						if($get_kd_keterampilan_tertinggi && $get_kd_keterampilan_tertinggi->kompetensi_dasar_alias){
							$kd_keterampilan_tertinggi_value = $get_kd_keterampilan_tertinggi->kompetensi_dasar_alias;
						}
						$result_kd_keterampilan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($kd_keterampilan_tertinggi_value);
					}
				}
				$nilai_keterampilan_terendah = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 2, $rombel_id, $id_mapel, $siswa_id, bilanganKecil($get_nilai_keterampilan));
				if($nilai_keterampilan_terendah){
					$rencana_penilaian_keterampilan_terendah = Rencanapenilaian::find_by_id($nilai_keterampilan_terendah->rencana_penilaian_id);
					if($rencana_penilaian_keterampilan_terendah){
						$get_kd_keterampilan_terendah = Kd::find_by_id($rencana_penilaian_keterampilan_terendah->kd_id);
						$keterampilan_terendah_value = ($get_kd_keterampilan_terendah) ? $get_kd_keterampilan_terendah->kompetensi_dasar : '';
						if($get_kd_keterampilan_terendah && $get_kd_keterampilan_terendah->kompetensi_dasar_alias){
							$keterampilan_terendah_value = $get_kd_keterampilan_terendah->kompetensi_dasar_alias;
						}
						if(isset($get_kd_keterampilan_tertinggi->id_kompetensi) && $get_kd_keterampilan_tertinggi->id_kompetensi == $get_kd_keterampilan_terendah->id_kompetensi){
							$result_kd_keterampilan_terendah = '';
						} else {
							$result_kd_keterampilan_terendah = ' Perlu meningkatkan kompetensi '.strtolower($keterampilan_terendah_value);
							if($result_kd_keterampilan_tertinggi){
								//echo $result_kd_keterampilan_tertinggi;
								$result_kd_keterampilan_terendah = ' dan perlu meningkatkan kompetensi '.strtolower($keterampilan_terendah_value);
							}
						}
					}
				}
				$record[1] 	= str_replace('<br>','',"$result_kd_keterampilan_tertinggi $result_kd_keterampilan_terendah");
			} else {
				$record[1] = 'Belum dilakukan penilaian';
			}
		} else {
			$deskripsi_mulok_value = 'Belum ada deskripsi tersimpan didatabase';
			$deskripsi_mulok = Deskripsi::find_by_ajaran_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran_id,$rombel_id,$id_mapel,$siswa_id);
			if($deskripsi_mulok){
				$deskripsi_mulok_value = $deskripsi_mulok->deskripsi_mulok;
			}
			$record[0] = str_replace('<br>','',$deskripsi_mulok_value);
		}
		$output['result'] = $record;
		echo json_encode($output);
		/*$this->template->title('')
		->set_layout($this->modal_tpl)
		->set('page_title', '<i class="fa fa-lightbulb-o"></i>')
		->build($this->admin_folder.'/asesmen/get_desc',$output);*/
	}
	public function get_deskripsi_mapel(){
		$data['ajaran_id']	= $_POST['ajaran_id'];
    	$data['rombel_id']	= $_POST['rombel_id'];
    	$data['id_mapel']	= $_POST['id_mapel'];
		$data['query']		= $_POST['query'];
		$data['rombel'] = $this->rombongan_belajar->get($data['rombel_id']);
		$data['all_siswa'] = filter_agama_siswa(get_nama_mapel($data['id_mapel']),$data['rombel_id']);
		//Datasiswa::find_all_by_data_rombel_id($data['rombel_id']);
		$data['mapel'] = $this->mata_pelajaran->get($data['id_mapel']);
		$this->template->title('')
		->set_layout($this->blank_tpl)
		->set('page_title', '')
		->build($this->admin_folder.'/asesmen/get_deskripsi_mapel',$data);
	}
	public function get_rombel(){
		$query = $_POST['query'];
		$tingkat = $_POST['kelas'];
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		//$data_mapel = $this->pembelajaran->find_all("semester_id = $ajaran->id AND guru_id = $loggeduser->guru_id");
		//Kurikulum::find('all', array('conditions' => "ajaran_id = $ajaran->id AND guru_id = $guru_id", 'group' => 'data_rombel_id','order'=>'data_rombel_id ASC'));
		//foreach($data_mapel as $datamapel){
			//$rombel_id[] = $datamapel->rombongan_belajar_id;
		//}
		//if(isset($rombel_id)){
			//$set_rombel_id = $rombel_id;
		//} else {
			//$set_rombel_id = array(0);
		//}
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			//$set_rombel_id = array_unique($set_rombel_id);
			//$set_rombel_id = implode(',',$set_rombel_id);
			$data_rombel = $this->rombongan_belajar->find_all("id IN(SELECT rombongan_belajar_id FROM pembelajaran WHERE semester_id = $ajaran->id AND guru_id = $loggeduser->guru_id) AND tingkat = $tingkat AND semester_id = $ajaran->id");
			//Datarombel::find('all', array('conditions' => array('id IN (?) AND tingkat = ? AND ajaran_id = ?', $set_rombel_id, $tingkat, $ajaran->id),'order'=>'nama ASC'));
		} else  {
			if($query == 'sikap' || $query == 'rencana_penilaian' || $query == 'kd'){
				//$data_rombel = Datarombel::find('all', array('conditions' => array('id IN (?) AND tingkat = ? AND semester_id = ?', $set_rombel_id, $tingkat, $ajaran->id),'order'=>'nama ASC'));
				$data_rombel = $this->rombongan_belajar->find_all("id IN(SELECT rombongan_belajar_id FROM pembelajaran WHERE semester_id = $ajaran->id AND guru_id = $loggeduser->guru_id) AND tingkat = $tingkat AND semester_id = $ajaran->id");
			} else {
				//$data_rombel = Datarombel::find_all_by_tingkat_and_ajaran_id($tingkat, $ajaran->id, array('order'=>'nama ASC'));
				$data_rombel = $this->rombongan_belajar->find_all("tingkat = $tingkat AND semester_id = $ajaran->id");
			}
		}
		if($data_rombel){
			foreach($data_rombel as $rombel){
				$record= array();
				$record['value'] 	= $rombel->id;
				$record['text'] 	= $rombel->nama;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan rombongan belajar di kelas terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_kurikulum_alt(){
		$set_rombel_id = $_POST['rombel_id'];
		$post = explode("#", $set_rombel_id);
		$rombel_id = $post[1];;
		$data_rombel = Datarombel::find($rombel_id);
		$tingkat = $data_rombel->tingkat;
		$kurikulum_id = $data_rombel->kurikulum_id;
		$kelas_X	= 0;
		$kelas_XI	= 0;
		$kelas_XII	= 0;
		$kelas_XIII	= 0;
		$k1 = 0;
		$k2 = 0;
		$k3 = 0;
		if($tingkat == 10){
			$query_kelas = 'kelas_X';
		} elseif($tingkat == 11){
			$query_kelas = 'kelas_XI';
		} elseif($tingkat == 12){
			$query_kelas = 'kelas_XII';
		} elseif($tingkat == 13){
			$query_kelas = 'kelas_XIII';
		}
		$all_mapel = Matpelkomp::find('all', array("conditions" => "kurikulum_id = $kurikulum_id AND  $query_kelas = 1"));
		if($all_mapel){
			foreach($all_mapel as $mapel){
				$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
				$record= array();
				$record['value'] 	= $mapel->id_mapel;
				$record['text'] 	= $data_mapel->nama_mapel.' ('.$data_mapel->id_mapel.')';
				$record['nama_kur'] = $data_mapel->kur;
				$output['mapel'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan mata pelajaran di kelas terpilih';
			$output['mapel'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_rombel_alt(){
		$query = $_POST['query'];
		$tingkat = $_POST['kelas'];
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$data_mapel = Kurikulum::find('all', array('conditions' => "guru_id = $loggeduser->data_guru_id", 'group' => 'data_rombel_id','order'=>'data_rombel_id ASC'));
		foreach($data_mapel as $datamapel){
			$rombel_id[] = $datamapel->rombel_id;
		}
		if(isset($rombel_id)){
			$set_rombel_id = $rombel_id;
		} else {
			$set_rombel_id = array(0);
		}
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$data_rombel = Datarombel::find('all', array('conditions' => array('id IN (?) AND tingkat = ?', $set_rombel_id, $tingkat)));
		} else  {
			if($query == 'sikap'){
				$data_rombel = Datarombel::find('all', array('conditions' => array('id IN (?) AND tingkat = ?', $set_rombel_id, $tingkat)));
			} else {
				$data_rombel = Datarombel::find_all_by_tingkat($tingkat);
			}
		}
		if($data_rombel){
			foreach($data_rombel as $rombel){
				$record= array();
				$record['value'] 	= $rombel->kurikulum_id.'#'.$rombel->id;
				$record['text'] 	= $rombel->nama;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan rombongan belajar di kelas terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_mapel(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$query		= $_POST['query'];
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$data_siswa = get_siswa_by_rombel($rombel_id);
		$guru_id = $find_akses['id'][0];
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$cond = "semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND guru_id = $loggeduser->guru_id";
		} else {
			if($query == 'rencana_penilaian' || $query == 'kd' || $query == 'kkm' || $query == 'add_kd_salah'){
				$cond = "semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND guru_id = $loggeduser->guru_id";
			} else {
				$cond = "semester_id = $ajaran_id";
			}
		}
		$all_mapel = $this->pembelajaran->find_all($cond);
		$all_ekskul = $this->ekstrakurikuler->find_all_by_semester_id($ajaran_id);
		if($all_mapel){
			foreach($all_mapel as $mapel){
				//$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
				$record= array();
				$record['value'] 	= $mapel->mata_pelajaran_id;
				$record['text'] 	= get_nama_mapel($mapel->mata_pelajaran_id).' ('.$mapel->mata_pelajaran_id.')';
				$output['mapel'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan mata pelajaran di kelas terpilih';
			$output['mapel'][] = $record;
		}
		/*if($all_mapel_alias){
			foreach($all_mapel_alias as $mapel_alias){
				$record= array();
				$record['value'] 	= $mapel_alias->id;
				$record['text'] 	= get_nama_mapel($mapel_alias->id).' ('.$mapel_alias->id.')';
				$output['mapel_alias'][] = $record;
			}
		} else {
			$output['mapel_alias'][] = array();
		}*/
		if($data_siswa){
			//sort($anggotarombel);
			foreach($data_siswa as $siswa){
				$record= array();
				$record['value'] 	= ($siswa->siswa) ? $siswa->siswa->id : '';
				$record['text'] 	= ($siswa->siswa) ? $siswa->siswa->nama : '';
				$output['siswa'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan siswa di rombel terpilih';
			$output['siswa'][] = $record;
		}
		/*if($all_mulok){
			foreach($all_mulok as $mulok){
				$record= array();
				$record['value'] 	= $mulok->id;
				$record['text'] 	= $mulok->nama_mulok;
				$output['mulok'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan mata pelajaran mulok di rombel terpilih';
			$output['mulok'][] = array();
		}*/
		if($all_ekskul){
			foreach($all_ekskul as $ekskul){
				$record= array();
				$record['value'] 	= $ekskul->id;
				$record['text'] 	= $ekskul->nama_ekskul;
				$output['ekskul'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan ekstrakurikuler di rombel terpilih';
			$output['ekskul'][] = $record;
		}
		/*if($query == 'deskripsi_mapel' || $query == 'nilai' || $query == 'analisis_individu' || $query == 'analisis_penilaian'){
			$output['mapel'] = array_merge($output['mulok'],$output['mapel']);
		}
		if($query == 'rencana_penilaian' || $query == 'add_kd' || $query == 'kkm' || $query == 'kd'){
			$output['mapel'] = array_merge($output['mapel_alias'],$output['mapel']);
		}*/
		echo json_encode($output);
	}
	public function get_kd(){
		//test($_POST);
		$loggeduser = $this->ion_auth->user()->row();
		if($loggeduser->guru_id){
			$data['guru_id'] = $loggeduser->guru_id;
		} else {
			$data['guru_id'] = 0;
		}
		$data['kelas'] = $_POST['kelas'];
		$data['kompetensi_id'] = $_POST['kompetensi_id'];
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['id_mapel'] = $_POST['id_mapel'];
		$data['id_rombel'] = $_POST['rombel_id'];
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', '')
		->build($this->admin_folder.'/perencanaan/form_perencanaan',$data);
	}
	public function get_rencana_id(){
		$all_rencana = array(
							array(
								'id'	=> 1,
								'nama'	=> 'Pengetahuan'
							),
							array(
								'id'	=> 2,
								'nama'	=>'Keterampilan'
							)
						);
		foreach($all_rencana as $rencana){
				$record= array();
				$record['value'] 	= $rencana['id'];
				$record['text'] 	= $rencana['nama'];
				$output['result'][] = $record;
			}
		echo json_encode($output);
	}
	public function get_analisis_remedial(){
		$kompetensi_id = $_POST['kompetensi_id'];
		if($kompetensi_id){
			$data['ajaran_id'] = $_POST['ajaran_id'];
			$data['rombel_id'] = $_POST['rombel_id'];
			$data['id_mapel'] = $_POST['id_mapel'];
			$data['kelas'] = $_POST['kelas'];
			$data['aspek'] = ($kompetensi_id == 1) ? 'P' : 'K';
			$data['kompetensi_id'] = $kompetensi_id;
			$this->template->title('Administrator Panel')
			->set_layout($this->blank_tpl)
			->set('page_title', 'Analisis Hasil Penilaian')
			->build($this->admin_folder.'/monitoring/analisis_remedial',$data);
		} else {
			$all_rencana = array(
								array(
									'id'	=> 1,
									'nama'	=> 'Pengetahuan'
								),
								array(
									'id'	=> 2,
									'nama'	=>'Keterampilan'
								)
							);
			foreach($all_rencana as $rencana){
					$record= array();
					$record['value'] 	= $rencana['id'];
					$record['text'] 	= $rencana['nama'];
					$output['result'][] = $record;
				}
			echo json_encode($output);
		}
	}
	public function get_rencana(){
		$all_rencana = array(
			array(
				'id'	=> 1,
				'nama'	=> 'Pengetahuan'
			),
			array(
				'id'	=> 2,
				'nama'	=>'Keterampilan'
			)
		);
		foreach($all_rencana as $rencana){
			$record= array();
			$record['value'] 	= $rencana['id'];
			$record['text'] 	= $rencana['nama'];
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_analisis_kompetensi(){
		$ajaran_id	= $_POST['ajaran_id'];
    	$rombel_id	= $_POST['rombel_id'];
    	$id_mapel	= $_POST['id_mapel'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$rencana_penilaian = $this->rencana_penilaian->find_all("semester_id = $ajaran_id AND kompetensi_id = $kompetensi_id AND rombongan_belajar_id = $rombel_id AND mata_pelajaran_id = $id_mapel");
		//test($rencana_penilaian);
		//Rencanapenilaian::find('all', array('group' => 'kd_id', 'conditions' => array('rencana_id IN (?)', $r_id)));
		if($rencana_penilaian){
			//$all_kd = $this->kompetensi_dasar->find_all();
			foreach($rencana_penilaian as $rp){
				//$this->db->select('*');
				$kd_id[] = $rp->id;
				//$kd = $this->kompetensi_dasar->get($rp->kd_id);
			}
			$this->db->where_in('rencana_penilaian_id', $kd_id); 
			$this->db->group_by("kd_id");
			$query = $this->db->get('kd_nilai');
			$all_kd = $query->result();
			if($all_kd){
				foreach($all_kd as $kd){
					$record= array();
					$record['value'] 	= $kd->kd_id;
					$record['text'] 	= $kd->id_kompetensi;
					$output['result'][] = $record;
				}
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan penilaian di mata pelajaran terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_all_kd(){
		$ajaran_id	= $_POST['ajaran_id'];
    	$rombel_id	= $_POST['rombel_id'];
    	$id_mapel	= $_POST['id_mapel'];
		$rencana_penilaian = $this->rencana_penilaian->find_all("ajaran_id = $ajaran_id and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel");
		test($rencana_penilaian);
		//Rencanapenilaian::find('all', array('group' => 'kd_id', 'conditions' => array('rencana_id IN (?)', $r_id)));
		if($rencana_penilaian){
			foreach($rencana_penilaian as $rp){
				//$kd = $this->kompetensi_dasar->get($rp->kd_id);
				$record= array();
				$record['value'] 	= $rp->kd_id.'#'.$rp->id.'#'.$rp->rencana_id;
				$record['text'] 	= isset($kd->id_kompetensi_alias) && ($kd->id_kompetensi_alias) ? $kd->id_kompetensi_alias : $kd->id_kompetensi;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan penilaian di mata pelajaran terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_rencana_penilaian(){
		$ajaran_id	= $_POST['ajaran_id'];
    	$rombel_id	= $_POST['rombel_id'];
    	$id_mapel	= $_POST['id_mapel'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$rencana_penilaian = $this->rencana_penilaian->find_all("semester_id = $ajaran_id and mata_pelajaran_id = $id_mapel and rombongan_belajar_id = $rombel_id and kompetensi_id = $kompetensi_id");
		if($rencana_penilaian){
			$i=1;
			foreach($rencana_penilaian as $rencana){
				$record= array();
				$record['value'] 	= $rencana->id.'#'.$rencana->nama_penilaian.'#'.$rencana->bobot;
				$record['text'] 	= 'Penilaian '.$i.' ('.$rencana->nama_penilaian.') || Bobot = '.$rencana->bobot;
				$output['result'][] = $record;
				$i++;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan rencana penilaian di mata pelajaran terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
		//test($rencana);
	}
	public function get_rencana_penilaian_old(){
		$ajaran_id	= $_POST['ajaran_id'];
    	$rombel_id	= $_POST['rombel_id'];
    	$id_mapel	= $_POST['id_mapel'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$rencana = $this->rencana_penilaian->find_all("ajaran_id = $ajaran_id and mata_pelajaran_id = $id_mapel and rombel_id = $rombel_id and kompetensi_id = $kompetensi_id");
		//Rencana::find_all_by_;
		if($rencana){
			foreach($rencana as $ren){
				$id_rencana[] = $ren->id;
			}
			$all_pengetahuan = Rencanapenilaian::find('all', array('group' => 'nama_penilaian','order'=>'id ASC', 'conditions' => array("rencana_id IN(?)",$id_rencana)));
			$i=1;
			ksort($all_pengetahuan);
			if($all_pengetahuan){
				foreach($all_pengetahuan as $allpengetahuan){
					$record= array();
					$record['value'] 	= $allpengetahuan->rencana_id.'#'.$allpengetahuan->nama_penilaian.'#'.$allpengetahuan->id.'#'.$allpengetahuan->bobot_penilaian;
					if($kompetensi_id == 1){
					$record['text'] 	= 'Penilaian '.$i.' ('.$allpengetahuan->nama_penilaian.') || Bobot = '.$allpengetahuan->bobot_penilaian;
					} else {
					$record['text'] 	= 'Penilaian '.$i.' ('.$allpengetahuan->nama_penilaian.')';
					}
					$output['result'][] = $record;
					$i++;
				}
			} else {
				$record['value'] 	= '';
				$record['text'] 	= 'Tidak ditemukan rencana penilaian di mata pelajaran terpilih';
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan rencana penilaian di mata pelajaran terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_analisis_kompetensi_old(){
		$ajaran_id	= $_POST['ajaran_id'];
    	$rombel_id	= $_POST['rombel_id'];
    	$id_mapel	= $_POST['id_mapel'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$rencana = Rencana::find_all_by_ajaran_id_and_id_mapel_and_rombel_id_and_kompetensi_id($ajaran_id,$id_mapel,$rombel_id,$kompetensi_id);
		if($rencana){
			foreach($rencana as $ren){
				$id_rencana[] = $ren->id;
			}
			$all_pengetahuan = Rencanapenilaian::find('all', array('group' => 'nama_penilaian','order'=>'id ASC', 'conditions' => array("rencana_id IN(?)",$id_rencana)));
			$i=1;
			ksort($all_pengetahuan);
			if($all_pengetahuan){
				foreach($all_pengetahuan as $allpengetahuan){
					$record= array();
					$record['value'] 	= $allpengetahuan->rencana_id.'#'.$allpengetahuan->nama_penilaian.'#'.$allpengetahuan->id.'#'.$allpengetahuan->bobot_penilaian;
					$record['text'] 	= 'Penilaian '.$i.' ('.$allpengetahuan->nama_penilaian.') || Bobot = '.$allpengetahuan->bobot_penilaian;
					$output['result'][] = $record;
					$i++;
				}
			} else {
				$record['value'] 	= '';
				$record['text'] 	= 'Tidak ditemukan rencana penilaian di mata pelajaran terpilih';
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan rencana penilaian di mata pelajaran terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_chart(){
	}
	public function get_chart_old(){
		$semester = get_ta();
		$this->db->select('*');
		$this->db->from('pembelajaran');
		$this->db->where('semester_id', $semester->id);
		$this->db->order_by('mata_pelajaran_id asc');
		$this->db->group_by('mata_pelajaran_id');
		$query = $this->db->get();
		$result = $query->result();
		if($result){
			foreach($result as $data){
				//$record= array();
				$set_data[1][] = $this->nilai->find_count("semester_id = $semester->id AND mata_pelajaran_id = $data->mata_pelajaran_id AND kompetensi_id = 1");
				$set_data[2][] = $this->nilai->find_count("semester_id = $semester->id AND mata_pelajaran_id = $data->mata_pelajaran_id AND kompetensi_id = 2");
				//$record['color'] 	= "#".random_color();
				//$record['highlight'] = "#".random_color();
				//$record['label'] = get_nama_mapel($data->mata_pelajaran_id);
				//$output['result'][] = $record;
				$output['labels'][] = $data->mata_pelajaran_id;//get_nama_mapel($data->mata_pelajaran_id);
			}
		} else {
			//$record['value'] = '1e-10';
        	//$record['color'] = "#f56954";
    	    //$record['highlight'] = "#f56954";
	        //$record['label'] = "Belum ada penilaian";
			$set_data['1'][] = '';
			$set_data['2'][] = '';
			$output['labels'][] = '';//$record;
		}
		$kompetensi = array(1=> 'Pengetahuan', 2=> 'Keterampilan');
		foreach($kompetensi as $key=>$data){
			$fillColor = random_color();
			$pointStrokeColor = random_color();
			$pointHighlightStroke = random_color();
			$new_record= array();
			$new_record['data'] = $set_data[$key];
			$new_record['label'] = $data;
			$new_record['fillColor'] = $fillColor;
			$new_record['strokeColor'] = $fillColor;
			$new_record['pointColor'] = $fillColor;
			$new_record['pointStrokeColor'] = $pointStrokeColor;
			$new_record['pointHighlightFill'] = "#fff";
			$new_record['pointHighlightStroke'] = $pointHighlightStroke;
			$output['datasets'][] = $new_record;
		}
		echo json_encode($output);
	}
	public function get_kelompok(){
		$kur = $this->input->post('kur');
		if (strpos($kur, 'REV') !== false) {
			$kurikulum = 2017;
		} elseif (strpos($kur, 'KTSP') !== false) {
			$kurikulum = 2006;
		} else {
			$kurikulum = 2013;
		}
		$get_kelompok = $this->kelompok->find_all("kurikulum = $kurikulum OR kurikulum = 0");
		if($get_kelompok){
			foreach($get_kelompok as $kelompok){
				$record= array();
				$record['value'] 	= $kelompok->id;
				$record['text'] 	= $kelompok->nama_kelompok;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan kelompok mata pelajaran';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	function get_program_keahlian(){
		$bidang_keahlian_id = $this->input->post('bidang_keahlian_id');
		if(!$bidang_keahlian_id){
			$bidang_keahlian_id = $this->input->post('kur');
		}
		$get_program_keahlian = $this->program_keahlian->find_all_by_bidang_keahlian_id($bidang_keahlian_id);
		if($get_program_keahlian){
			foreach($get_program_keahlian as $program){
				$record= array();
				$record['value'] 	= $program->id;
				$record['text'] 	= $program->nama;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan program keahlian';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	function get_kompetensi_keahlian(){
		$program_keahlian_id = $this->input->post('program_keahlian_id');
		$get_kompetensi_keahlian = $this->kompetensi_keahlian->find_all_by_program_keahlian_id($program_keahlian_id);
		if($get_kompetensi_keahlian){
			foreach($get_kompetensi_keahlian as $kompetensi){
				$record= array();
				$record['value'] 	= $kompetensi->id;
				$record['text'] 	= $kompetensi->nama;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan program keahlian';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
}