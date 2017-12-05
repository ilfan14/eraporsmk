<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kirim_nilai extends Backend_Controller {
	protected $activemenu = 'kirim_nilai';
	public function __construct() {
		parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1'); 
		//ini_set('memory_limit', '256M');
		$this->template->set('activemenu', $this->activemenu);
		$this->connected = 0;
		$this->extension = load_extensions();
		$remote_version = check_is_dapodik();
		if($remote_version == '200' || $remote_version == '404'){
			$this->connected = 1;
			if(!$this->extension){
				$this->_database = $this->load->database('dapodik', TRUE);
			}
		}
	}
	public function index(){
		$ajaran = get_ta();
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Kirim Nilai eRapor ke Dapodik')
		->set('connected', $this->connected)
		->set('extension', $this->extension)
		->set('ajaran', $ajaran)
		->build($this->sinkronisasi_folder.'/kirim_nilai');
	}
	public function list_kirim_nilai($kompetensi = NULL, $tingkat = NULL){
		$loggeduser = $this->ion_auth->user()->row();
		$sekolah = $this->sekolah->get($loggeduser->sekolah_id);
		//Datasekolah::first();
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->semester;
		$tahun = substr($tahun, 0,4);
		$semester_id = $tahun.$smt;
		$search = "";
		$start = 0;
		$rows = 25;
		$ajaran = get_ta();
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		if($kompetensi && !$tingkat){
			$set_query = "AND jurusan_sp_id = $kompetensi";
			/*$condition_1 = array('conditions' => "ajaran_id = $ajaran->id AND kurikulum_id = $kompetensi AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')", 'limit' => $rows, 'offset' => $start, 'order' => 'nama ASC');
			$condition_2 = array('conditions' => "ajaran_id = $ajaran->id AND kurikulum_id = $kompetensi AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')");*/
		} elseif($kompetensi && $tingkat){
			/*$condition_1 = array('conditions' => "ajaran_id = $ajaran->id AND tingkat = $tingkat AND kurikulum_id = $kompetensi AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')", 'limit' => $rows, 'offset' => $start, 'order' => 'nama ASC');
			$condition_2 = array('conditions' => "ajaran_id = $ajaran->id AND tingkat = $tingkat AND kurikulum_id = $kompetensi AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')");*/
			$set_query = "AND tingkat = $tingkat AND jurusan_sp_id = $kompetensi";
		} else {
			/*$condition_1 = array('conditions' => "ajaran_id = $ajaran->id AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')", 'limit' => $rows, 'offset' => $start, 'order' => 'nama ASC');
			$condition_2 = array('conditions' => "ajaran_id = $ajaran->id AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')");*/
			$set_query = '';
		}
		//$query = Datarombel::find('all', $condition_1);
		//$filter = Datarombel::find('all', $condition_2);
		$query = $this->rombongan_belajar->with('pembelajaran')->find_all("semester_id = $ajaran->id $set_query AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')", '*','id desc', $start, $rows);
		$filter = $this->rombongan_belajar->find_all("semester_id = $ajaran->id $set_query AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')", '*','id desc');
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
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$record = array();
			$this->_database->select('*');
			$this->_database->from('pembelajaran');
			$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = pembelajaran.rombongan_belajar_id');
			$this->_database->where('rombongan_belajar.rombongan_belajar_id', $temp->rombel_id_dapodik);
			$this->_database->where('rombongan_belajar.soft_delete', 0);
			$this->_database->where('rombongan_belajar.semester_id', $semester_id);
			$this->_database->where('pembelajaran.semester_id', $semester_id);
			$this->_database->where('pembelajaran.soft_delete', 0);
			$query = $this->_database->get();
			$get_pembelajaran_dapodik = $query->result();
			$updater_id = isset($get_pembelajaran_dapodik[0]) ? $get_pembelajaran_dapodik[0]->updater_id : 0;
			$rombel_id_dapodik = ($temp->rombel_id_dapodik) ? $temp->rombel_id_dapodik : 0;
			$record[] = $temp->nama;
			$record[] = '<div class="text-center">'.count($temp->pembelajaran).'</div>';
			$record[] = '<div class="text-center">'.count($get_pembelajaran_dapodik).'</div>';
			$record[] = '<div class="text-center"><a href="'.site_url('admin/kirim-nilai/detil/'.$temp->id.'/'.$rombel_id_dapodik).'/'.$updater_id.'" class="btn btn-warning btn-sm btn-block"><i class="fa fa-search"></i> Detil Nilai</a></div>';		
			//$record[] = '<div class="text-center"><a href="'.site_url('admin/kirim-nilai/proses/'.$temp->id.'/'.$temp->rombel_id_dapodik).'" class="btn btn-success btn-sm btn-block"><i class="fa fa-cloud-upload"></i> Proses Kirim</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function detil($id,$id_dapodik,$updater_id){
		$ajaran = get_ta();
		$pembelajaran = $this->pembelajaran->find_all("semester_id = $ajaran->id AND rombongan_belajar_id = $id");
		$nama_rombel = get_nama_rombel($id);
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		//->set('page_title', 'Detil Nilai eRapor &amp; Dapodik')
		->set('page_title', 'Kirim nilai semua mata pelajaran dibawah rombongan belajar '.$nama_rombel)
		->set('rombel_id', $id)
		->set('rombel_id_dapodik', $id_dapodik)
		->set('pembelajaran', $pembelajaran)
		->set('ajaran_id', $ajaran->id)
		->set('updater_id', $updater_id)
		->build($this->sinkronisasi_folder.'/detil_nilai');
	}
	public function detil_old($id,$id_dapodik,$updater_id){
		$ajaran = get_ta();
		$condition_1 = array('include'=>array('kurikulum'), 'conditions' => "id = $id");
		$pembelajaran = Datarombel::find($condition_1);
		$nama_rombel = ($pembelajaran) ? $pembelajaran->nama : '';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		//->set('page_title', 'Detil Nilai eRapor &amp; Dapodik')
		->set('page_title', 'Kirim nilai semua mata pelajaran dibawah rombongan belajar '.$nama_rombel)
		->set('rombel_id', $id)
		->set('rombel_id_dapodik', $id_dapodik)
		->set('pembelajaran', $pembelajaran)
		->set('ajaran_id', $ajaran->id)
		->set('updater_id', $updater_id)
		->build($this->sinkronisasi_folder.'/detil_nilai');
	}
	public function proses($id_mapel,$id_mapel_dapodik,$rombel_id,$rombel_id_dapodik,$updater_id,$kkm,$no_urut){
		if($id_mapel){
			$get_pembelajaran = '';
			$pembelajaran_id = isset($get_pembelajaran_id->pembelajaran_id) ? $get_pembelajaran_id->pembelajaran_id : 0;
			$insert_matev = array(
				'id_evaluasi' 			=> $id_evaluasi,
				'nm_mata_evaluasi' 		=> $nm_mata_evaluasi,
				'a_dari_template' 		=> 1,
				'no_urut' 				=> $no_urut,
				'rombongan_belajar_id' 	=> $rombel_id_dapodik,
				'mata_pelajaran_id' 	=> $id_mapel_dapodik,
				'pembelajaran_id' 		=> $pembelajaran_id,
				'soft_delete'			=> 0,
				'updater_id' 			=> $updater_id
			);
			$result['insert_rapor'] = 0;//$insert_rapor;
			$result['gagal_rapor'] = 0;//$gagal_rapor;
			$result['update_rapor'] = 0;//$update_rapor;
			//$result['info'] = 'Berhasil mengirim nilai rapor ke Dapodik';
			$result['info'] = '';
			$result['title'] = 'Sedang dalam pengembangan';
			$result['type'] = 'error';
			//$result['type'] = 'success';
		} else {
			$result['insert_rapor'] = 0;
			$result['gagal_rapor'] = 0;
			$result['update_rapor'] = 0;
			$result['info'] = 'Gagal mengirim nilai rapor ke Dapodik';
			$result['title'] = 'Gagal';
			$result['type'] = 'error';
		}
		echo json_encode($result);
	}
	public function proses_old($id_mapel,$id_mapel_dapodik,$rombel_id,$rombel_id_dapodik,$updater_id,$kkm,$no_urut){
		$this->_database = $this->load->database('dapodik', TRUE);
		$ajaran = get_ta();
		$tahun = $ajaran->tahun;
		$smt = $ajaran->smt;
		$tahun = substr($tahun, 0,4); // returns "d"
		$semester_id = $tahun.$smt;
		$query = $this->_database->get_where('ref.mata_pelajaran', array('mata_pelajaran_id' => $id_mapel_dapodik));
		$nm_mata_evaluasi = $query->row()->nama;
		$query = $this->_database->get_where('pembelajaran', array('rombongan_belajar_id' => $rombel_id_dapodik, 'semester_id' => $semester_id, 'mata_pelajaran_id' => $id_mapel_dapodik));
		$get_pembelajaran_id = $query->row();
		$pembelajaran_id = isset($get_pembelajaran_id->pembelajaran_id) ? $get_pembelajaran_id->pembelajaran_id : 0;
		$this->_database->select('*');
		$this->_database->from('nilai.matev_rapor');
		$this->_database->join('pembelajaran', 'pembelajaran.pembelajaran_id = nilai.matev_rapor.pembelajaran_id');
		$this->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = nilai.matev_rapor.rombongan_belajar_id');
		$this->_database->where('rombongan_belajar.semester_id', $semester_id);
		$this->_database->where('rombongan_belajar.rombongan_belajar_id', $rombel_id_dapodik);
		$this->_database->where('nilai.matev_rapor.soft_delete', 0);
		$this->_database->where('nilai.matev_rapor.a_dari_template', 1);
		$this->_database->where('nilai.matev_rapor.mata_pelajaran_id', $id_mapel_dapodik);
		$this->_database->where('nilai.matev_rapor.pembelajaran_id', $pembelajaran_id);
		//$this->_database->order_by('rombongan_belajar.rombongan_belajar_id', 'ASC');
		$query = $this->_database->get();
		$matev_rapor = $query->row();
		//$find_anggota_rombel = Anggotarombel::find('all', array('conditions' => "rombel_id = $rombel_id"));
		$find_anggota_rombel = Anggotarombel::find_all_by_ajaran_id_and_rombel_id($ajaran->id, $rombel_id);
		//', array('conditions' => "rombel_id = $rombel_id"));
		if($matev_rapor){
			$id_evaluasi = $matev_rapor->id_evaluasi;
		} else {
			$id_evaluasi = gen_uuid();
			$insert_matev = array(
				'id_evaluasi' => $id_evaluasi,
				'nm_mata_evaluasi' => $nm_mata_evaluasi,
				'a_dari_template' => 1,
				'no_urut' => $no_urut,
				'rombongan_belajar_id' => $rombel_id_dapodik,
				'mata_pelajaran_id' => $id_mapel_dapodik,
				'pembelajaran_id' => $pembelajaran_id,
				'soft_delete' => 0,
				'updater_id' => $updater_id
			);
			if($pembelajaran_id){
				$this->_database->insert('nilai.matev_rapor', $insert_matev);
			}
		}
		$insert_rapor = 0;
		$gagal_rapor = 0;
		$update_rapor = 0;
		$info = 0;
		if($find_anggota_rombel){
			foreach($find_anggota_rombel as $anggota){
				$all_nilai_pengetahuan_remedial = Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 1, $rombel_id, $id_mapel, $anggota->datasiswa_id);
				if($all_nilai_pengetahuan_remedial){
					$nilai_pengetahuan_value = $all_nilai_pengetahuan_remedial->rerata_remedial;
				} else {
					$all_nilai_pengetahuan = Nilaiakhir::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran->id,1,$rombel_id,$id_mapel,$anggota->datasiswa_id);
					if($all_nilai_pengetahuan){
						$nilai_pengetahuan = 0;
						foreach($all_nilai_pengetahuan as $allnilaipengetahuan){
							$nilai_pengetahuan += $allnilaipengetahuan->nilai;
						}
						$nilai_pengetahuan_value = number_format($nilai_pengetahuan,0);
					} else {
						$nilai_pengetahuan_value = 0;
					}
				}
				$all_nilai_keterampilan_remedial = Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 2, $rombel_id, $id_mapel, $anggota->datasiswa_id);
				if($all_nilai_keterampilan_remedial){
					$nilai_keterampilan_value = $all_nilai_keterampilan_remedial->rerata_remedial;
				} else {
					$all_nilai_keterampilan = Nilaiakhir::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran->id, 2, $rombel_id, $id_mapel, $anggota->datasiswa_id);
					if($all_nilai_keterampilan){
						$nilai_keterampilan = 0;
						foreach($all_nilai_keterampilan as $allnilaiketerampilan){
							$nilai_keterampilan += $allnilaiketerampilan->nilai;
						}
						$nilai_keterampilan_value = number_format($nilai_keterampilan,0);
					} else {
						$nilai_keterampilan_value = 0;
					}
				}
				if(!$nilai_pengetahuan_value && !$nilai_keterampilan_value){
					$gagal_rapor++;
				}
				if($nilai_pengetahuan_value && !$nilai_keterampilan_value){
					$gagal_rapor++;
				} 
				if(!$nilai_pengetahuan_value && $nilai_keterampilan_value){
					$gagal_rapor++;
				}
				if($nilai_pengetahuan_value && $nilai_keterampilan_value){
					$konversi_huruf_pengetahuan = konversi_huruf($kkm,$nilai_pengetahuan_value);
					$konversi_huruf_keterampilan = konversi_huruf($kkm,$nilai_keterampilan_value);
					$insert_nilai = array(
						'nilai_id' => gen_uuid(),
						'id_evaluasi' => $id_evaluasi,
						'anggota_rombel_id' => $anggota->anggota_rombel_id_dapodik,
						'nilai_kognitif_angka' => $nilai_pengetahuan_value,
						'nilai_kognitif_huruf' => ($konversi_huruf_pengetahuan != '-') ? $konversi_huruf_pengetahuan : 'D',
						'nilai_psim_angka' => $nilai_keterampilan_value,
						'nilai_psim_huruf' => ($konversi_huruf_keterampilan != '-') ? $konversi_huruf_keterampilan : 'D',
						'nilai_afektif_angka' => $kkm,
						'nilai_afektif2_angka' => $kkm,
						'a_beku' => 0,
						'soft_delete' => 0,
						'updater_id' => $updater_id
					);
					$query = $this->_database->get_where('nilai.nilai_rapor', array('id_evaluasi' => $id_evaluasi, 'anggota_rombel_id' => $anggota->anggota_rombel_id_dapodik, 'soft_delete' => 0));
					$find_nilai_dapodik = $query->row();
					if($find_nilai_dapodik){
						$update_rapor++;
						$update_nilai = array(
							'nilai_kognitif_angka' => $nilai_pengetahuan_value,
							'nilai_kognitif_huruf' => ($konversi_huruf_pengetahuan != '-') ? $konversi_huruf_pengetahuan : 'D',
							'nilai_psim_angka' => $nilai_keterampilan_value,
							'nilai_psim_huruf' => ($konversi_huruf_keterampilan != '-') ? $konversi_huruf_keterampilan : 'D',
							'nilai_afektif_angka' => $kkm,
							'nilai_afektif2_angka' => $kkm
						);
						$this->_database->where('nilai_id', $find_nilai_dapodik->nilai_id);
						$this->_database->update('nilai.nilai_rapor', $update_nilai);
					} else {
						$insert_rapor++;
						$this->_database->insert('nilai.nilai_rapor', $insert_nilai);
					}
				}
				
			}
			$info = 'Berhasil mengirim nilai rapor ke Dapodik';
			$title = 'Sukses';
			$type = 'success';
			$result['insert_rapor'] = $insert_rapor;
			$result['gagal_rapor'] = $gagal_rapor;
			$result['update_rapor'] = $update_rapor;
			$result['info'] = $info;
			$result['title'] = $title;
			$result['type'] = $type;
		} else {
			$result['insert_rapor'] = 0;
			$result['gagal_rapor'] = 0;
			$result['update_rapor'] = 0;
			$result['info'] = $info;
			$result['title'] = 'Gagal';
			$result['type'] = 'error';
		}
		echo json_encode($result);
	}
}
