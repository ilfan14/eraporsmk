<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Asesmen extends Backend_Controller {
	protected $activemenu = 'penilaian';
	public function __construct() {
		parent::__construct();
		$this->template->set('activemenu', $this->activemenu);
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
	}
	public function index(){
		redirect('admin/asesmen/sikap');
	}
	public function sikap(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '<a href="'.site_url('admin/asesmen/add_sikap').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('pilih_rombel', $pilih_rombel)
		->set('page_title', 'Tambah Data Penilaian Sikap')
		->set('form_action', 'admin/asesmen/simpan_sikap')
		->set('ajaran', $ajaran)
		->set('sekolah_id', $loggeduser->sekolah_id)
		->build($this->admin_folder.'/asesmen/list_sikap');
	}
	public function add_sikap(){
		//$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Jurnal Sikap')
		->set('form_action', 'admin/asesmen/simpan_sikap')
		->build($this->admin_folder.'/asesmen/sikap');
	}
	public function pengetahuan(){
		//$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Penilaian Pengetahuan')
		->set('form_action', 'admin/asesmen/simpan_nilai')
		->set('query', 'rencana_penilaian')
		->set('kompetensi_id', 1)
		->build($this->admin_folder.'/asesmen/form_asesmen');
	}
	public function keterampilan(){
		//$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Penilaian Keterampilan')
		->set('form_action', 'admin/asesmen/simpan_nilai')
		->set('query', 'rencana_penilaian')
		->set('kompetensi_id', 2)
		->build($this->admin_folder.'/asesmen/form_asesmen');
	}
	public function remedial(){
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Penilaian Remedial')
		->set('form_action', 'admin/asesmen/simpan_remedial')
		->set('query', 'remedial')
		->set('kompetensi_id', 1)
		->build($this->admin_folder.'/asesmen/form_remedial');
	}
	public function ppk(){
		$loggeduser = $this->ion_auth->user()->row();
		$pilih_rombel = '';
		$find_akses = get_akses($loggeduser->id);
		$nama_group = get_jabatan($loggeduser->id);
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$pilih_rombel = '<a href="'.site_url('admin/asesmen/add_ppk').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data</a>';
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('pilih_rombel', $pilih_rombel)
		->set('page_title', 'Penguatan Pendidikan Karakter')
		->build($this->admin_folder.'/asesmen/list_ppk');
	}
	public function get_ppk(){
	}
	public function add_ppk(){
		//test($_POST);
		$this->load->library('form_validation');
		//<a href="http://localhost/eraporsmk/admin/siswa/view/2" class="toggle-modal"><i class="fa fa-eye"></i>Detil</a>
		$this->form_validation->set_rules('siswa', 'Siswa', 'required');
		/*for($i=1; $i<=3; $i++){
			if (empty($_FILES['foto_'.$i]['name'])){
				$this->form_validation->set_rules('foto_'.$i, 'Foto '.$i, 'required');
			} else {
			//if(!empty($_FILES['foto_'.$i]['name'])){
				$upload_response = $this->upload_photo('foto_'.$i);
				if($upload_response['success']){
					$data['foto_'.$i]  = $upload_response['upload_data']['file_name'];
				} else {
					$this->session->set_flashdata('error', $upload_response['msg']);
				}
			}
		}*/
		if ($this->form_validation->run() == true){
			$semester_id = $this->input->post('semester_id');
			$siswa_id = $this->input->post('siswa');
			//$ref_ppk_id = serialize($this->input->post('ppk_id'));
			$capaian = $this->input->post('copy_editor');
			$capaian = find_img($capaian);
			$data = array(
				'semester_id' 		=> $semester_id,
				'siswa_id' 			=> $siswa_id,
				//'kegiatan' 			=> $this->input->post('kegiatan'),
				////'tempat' 			=> $this->input->post('tempat'),
				//'penanggung_jawab' 	=> $this->input->post('penanggung_jawab'),
				//'waktu' 			=> $this->input->post('waktu'),
				//'ref_ppk_id' 		=> $ref_ppk_id,
				'capaian' 			=> $capaian
			);
			$find_ppk = $this->catatan_ppk->find("semester_id = $semester_id AND siswa_id = $siswa_id");
			if($find_ppk){
				$nama_siswa = get_nama_siswa($data['siswa_id']);
				$this->session->set_flashdata('error', "Gagal menambah capaian PPK. Catatan PPK untuk siswa $nama_siswa sudah ada");
			} else {
				if($this->catatan_ppk->insert($data)){
					$this->session->set_flashdata('success', 'Tambah capaian PPK berhasil disimpan');
				} else {
					$this->session->set_flashdata('error', 'Gagal menambah capaian PPK');
				}
			}
			redirect('admin/asesmen/ppk', 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
			$this->data['siswa'] = $this->form_validation->set_value('siswa');
			/*$this->data['kegiatan'] = array(
				'name'  => 'kegiatan',
				'id'    => 'kegiatan',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('kegiatan'),
			);
			$this->data['tempat'] = array(
				'name'  => 'tempat',
				'id'    => 'tempat',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('tempat'),
			);
			$this->data['penanggung_jawab'] = array(
				'name'  => 'penanggung_jawab',
				'id'    => 'penanggung_jawab',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('penanggung_jawab'),
			);
			$this->data['waktu'] = array(
				'name'  => 'waktu',
				'id'    => 'waktu',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('waktu'),
			);
			$this->data['ppk_id'] = $this->form_validation->set_value('ppk_id');*/
			//$this->data['ref_ppk_id'] = $this->form_validation->set_value('ref_ppk_id');;
			$this->data['capaian'] = $this->form_validation->set_value('capaian');
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Tambah Data Penilaian Penguatan Pendidikan Karakter')
		->build($this->admin_folder.'/asesmen/form_ppk', $this->data);
	}
	public function edit_ppk($id){
		$loggeduser = $this->ion_auth->user()->row();
		$catatan_ppk = $this->catatan_ppk->get($id);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('siswa', 'Siswa', 'required');
		/*for($i=1; $i<=3; $i++){
			if (empty($_FILES['foto_'.$i]['name'])){
				$this->form_validation->set_rules('foto_'.$i, 'Foto '.$i, 'required');
			} else {
			//if(!empty($_FILES['foto_'.$i]['name'])){
				$upload_response = $this->upload_photo('foto_'.$i);
				if($upload_response['success']){
					$foto = 'foto_'.$i;
					$data['foto_'.$i]  = $upload_response['upload_data']['file_name'];
					if(is_file(MEDIAFOLDER.$catatan_ppk->$foto)){
						unlink(MEDIAFOLDER.$catatan_ppk->$foto);
					}
				}
				else{
					$this->session->set_flashdata('error', $upload_response['msg']);
				}
			}
		}*/
		if ($this->form_validation->run() == true){
			$semester_id = $this->input->post('semester_id');
			$siswa_id = $this->input->post('siswa');
			//$ref_ppk_id = serialize($this->input->post('ppk_id'));
			$capaian = $this->input->post('copy_editor');
			$capaian = find_img($capaian);
			$data = array(
				'semester_id' 		=> $semester_id,
				'siswa_id' 			=> $siswa_id,
				//'kegiatan' 			=> $this->input->post('kegiatan'),
				////'tempat' 			=> $this->input->post('tempat'),
				//'penanggung_jawab' 	=> $this->input->post('penanggung_jawab'),
				//'waktu' 			=> $this->input->post('waktu'),
				//'ref_ppk_id' 		=> $ref_ppk_id,
				'capaian' 			=> $capaian
			);
			if($this->catatan_ppk->update($id,$data)){
				$this->session->set_flashdata('success', 'Tambah capaian PPK berhasil disimpan');
			} else {
				$this->session->set_flashdata('error', 'Gagal menambah capaian PPK');
			}
			redirect('admin/asesmen/ppk', 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
			$this->data['siswa'] = $this->form_validation->set_value('siswa', $catatan_ppk->siswa_id);
			$this->data['capaian'] = $this->form_validation->set_value('capaian', $catatan_ppk->capaian);
			//$this->data['ref_ppk_id'] = unserialize($catatan_ppk->ref_ppk_id);
			/*$this->data['kegiatan'] = array(
				'name'  => 'kegiatan',
				'id'    => 'kegiatan',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('kegiatan', $catatan_ppk->kegiatan),
			);
			$this->data['tempat'] = array(
				'name'  => 'tempat',
				'id'    => 'tempat',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('tempat', $catatan_ppk->tempat),
			);
			$this->data['penanggung_jawab'] = array(
				'name'  => 'penanggung_jawab',
				'id'    => 'penanggung_jawab',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('penanggung_jawab', $catatan_ppk->penanggung_jawab),
			);
			$this->data['waktu'] = array(
				'name'  => 'waktu',
				'id'    => 'waktu',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('waktu', $catatan_ppk->waktu),
			);
			$this->data['ppk_id'] = $this->form_validation->set_value('ppk_id', $catatan_ppk->ref_ppk_id);*/
		}
		$this->template->title('Administrator Panel')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Edit Data Penilaian Penguatan Pendidikan Karakter')
		->build($this->admin_folder.'/asesmen/form_ppk', $this->data);
	}
	public function hapus_ppk($id){
		if($id){
			$catatan_ppk = $this->catatan_ppk->get($id);
			if(is_file(MEDIAFOLDER.$catatan_ppk->foto_1)){
				unlink(MEDIAFOLDER.$catatan_ppk->foto_1);
			}
			if(is_file(MEDIAFOLDER.$catatan_ppk->foto_2)){
				unlink(MEDIAFOLDER.$catatan_ppk->foto_2);
			}
			if(is_file(MEDIAFOLDER.$catatan_ppk->foto_3)){
				unlink(MEDIAFOLDER.$catatan_ppk->foto_3);
			}
			if($this->catatan_ppk->delete($id)){
				$output['title'] = 'Sukses';
				$output['text'] = 'Berhasil menghapus data PPK';
				$output['type'] = 'success';
			} else {
				$output['title'] = 'Gagal';
				$output['text'] = 'Gagal menghapus data PPK';
				$output['type'] = 'error';
			}
		} else {
			$output['title'] = 'Gagal';
			$output['text'] = 'Gagal menghapus data PPK';
			$output['type'] = 'error';
		}
		echo json_encode($output);
	}
	public function simpan_remedial(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$id_mapel = $_POST['id_mapel'];
		$data_siswa = $_POST['siswa_id'];
		$rerata = $_POST['rerata'];
		$rerata_akhir = $_POST['rerata_akhir'];
		$rerata_remedial = $_POST['rerata_remedial'];
		foreach($data_siswa as $k=>$siswa){
			foreach($rerata as $rata){
				array_walk($rata, 'check_great_than_one_fn','remedial');
				array_walk($rata, 'check_numeric','asesmen/remedial');
			}
			$remedial = $this->remedial->find("semester_id = $ajaran_id AND kompetensi_id = $kompetensi_id AND rombongan_belajar_id = $rombel_id AND mata_pelajaran_id = $id_mapel AND siswa_id = $siswa");
			//Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id();
			if($remedial){
				$data_update = array(
					'nilai' 			=> serialize($rerata[$siswa]),
					'rerata_akhir' 		=> $rerata_akhir[$k],
					'rerata_remedial' 	=> $rerata_remedial[$k]
				);
				$this->remedial->update($remedial->id, $data_update);
				$this->session->set_flashdata('success', 'Berhasil memperbaharui data nilai remedial.');
			} else {
				$data_insert = array(
					'semester_id'			=> $ajaran_id,
					'kompetensi_id'			=> $kompetensi_id,
					'rombongan_belajar_id'	=> $rombel_id,
					'mata_pelajaran_id'		=> $id_mapel,
					'siswa_id'				=> $siswa,
					'nilai'					=> serialize($rerata[$siswa]),
					'rerata_akhir'			=> $rerata_akhir[$k],
					'rerata_remedial'		=> $rerata_remedial[$k],
				);
				$this->remedial->insert($data_insert);
				$this->session->set_flashdata('success', 'Tambah data nilai remedial berhasil.');
			}
		}
		redirect('admin/asesmen/remedial');
	}
	public function get_remedial(){
		$html = '';
		$settings 	= $this->settings->get(1);
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id	= $_POST['rombel_id'];
		$id_mapel = $_POST['id_mapel'];
		$kelas = $_POST['kelas'];
		$aspek = $_POST['aspek'];
		$kompetensi_id = ($aspek == 'P') ? 1 : 2;
		$nama_mapel = get_nama_mapel($id_mapel);
		$data_siswa = filter_agama_siswa($nama_mapel,$rombel_id);
		$get_all_kd = $this->kompetensi_dasar->find_all("mata_pelajaran_id = $id_mapel AND kelas = $kelas AND aspek = '$aspek'");
		//Kd::find_all_by_id_mapel_and_kelas_and_aspek($id_mapel, $kelas, $aspek);
		if(!$get_all_kd){
			$get_all_kd = $this->kompetensi_dasar->find_all("mata_pelajaran_id = $id_mapel AND kelas = $kelas AND aspek = 'PK'");
			$id_kds[] = '';

			//Kd::find_all_by_id_mapel_and_kelas_and_aspek($id_mapel, $kelas, 'PK');
		}
		$count_get_all_kd = count($get_all_kd);
		$kkm = get_kkm($ajaran_id, $rombel_id, $id_mapel);
		$html .= '<input type="hidden" name="kompetensi_id" value="'.$kompetensi_id.'" />';
		$html .= '<input type="hidden" id="get_kkm" value="'.$kkm.'" />';
		$html .= '<div class="table-responsive no-padding">';
		$html .= '<div class="row"><div class="col-md-6">';
		$html .= '<table class="table table-bordered">';
		$html .= '<tr>';
		$html .= '<td colspan="2" class="text-center">';
		$html .= '<strong>Keterangan</strong>';
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td width="30%">';
		$html .= 'KKM';
		$html .= '</td>';
		$html .= '<td>';
		$html .= $kkm;
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>';
		$html .= '<input type="text" class="bg-red form-control input-sm" />';
		$html .= '</td>';
		$html .= '<td>';
		$html .= 'Tidak tuntas (input aktif)';
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>';
		$html .= '<input type="text" class="bg-green form-control input-sm" />';
		$html .= '</td>';
		$html .= '<td>';
		$html .= 'Tuntas (input non aktif)';
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '</table>';
		$html .= '</div></div>';
		$html .= '<table class="table table-bordered table-hover">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th rowspan="2" style="vertical-align: middle;">Nama Siswa</th>';
		$html .= '<th class="text-center" colspan="'.count($get_all_kd).'">Kompetensi Dasar</th>';
		$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Rerata Akhir</th>';
		$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Rerata Remedial</th>';
		$html .= '</tr>';
		$html .= '<tr>';
		$get_all_kd_finish = count($get_all_kd);
		foreach($get_all_kd as $all_kd){
			//$kd = Kd::find_by_id($allpengetahuan->kd_id);
			$id_kd = $all_kd->id_kompetensi;
			$id_kds[] = $all_kd->id;
			$html .= '<th><a href="javacript:void(0)" class="tooltip-left" title="'.$all_kd->kompetensi_dasar.'">&nbsp;&nbsp;&nbsp;'.$id_kd.'&nbsp;&nbsp;&nbsp;</a></th>';
		}
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		$no=0;
		$pembagi = ($id_kds) ? count($id_kds) : 1;
		foreach($data_siswa as $siswa){
			$siswa_id = $siswa->siswa->id;
			$remedial = $this->remedial->find("semester_id = $ajaran_id and 
			kompetensi_id = $kompetensi_id and 
			rombongan_belajar_id = $rombel_id and 
			mata_pelajaran_id = $id_mapel and 
			siswa_id = $siswa_id");
			//Remedial::find_by_($ajaran_id, $kompetensi_id, $rombel_id, , $siswa->id);
			$html .= '<input type="hidden" name="siswa_id[]" value="'.$siswa->siswa->id.'" />';
			$html .= '<tr>';
			$html .= '<td>';
			$html .= $siswa->siswa->nama;
			$html .= '</td>';
			if($remedial){
				$all_nilai = unserialize($remedial->nilai);
				$set_nilai = 0;
				foreach($all_nilai as $key=>$nilai){
					$set_nilai += $nilai;
					if($kkm > number_format($nilai)){
						$aktif = '';
						$bg = 'bg-red';
					} else {
						$aktif = 'readonly';
						$bg = 'bg-green';
					}
					$html .= '<td class="text-center">';
					if($nilai){
						//$pembagi++;
						$html .= '<input type="text" name="rerata['.$siswa->siswa->id.'][]" size="10" class="'.$bg.' form-control input-sm" value="'.number_format($nilai,0).'" '.$aktif.' />';
					} else {
						$html .= '<input type="hidden" name="rerata['.$siswa->siswa->id.'][]" value="0" />-';
					}
					$html .= '</td>';
					//$no++;
				}
				if($kkm > $remedial->rerata_akhir){
					$bg_rerata_akhir = 'text-red';
				} else {
					$bg_rerata_akhir = 'text-green';
				}
				if($kkm > $remedial->rerata_remedial){
					$bg_rerata_remedial = 'text-red';
				} else {
					$bg_rerata_remedial = 'text-green';
				}
				$html .= '<td id="rerata_akhir" class="text-center '.$bg_rerata_akhir.'"><strong>';
				$html .= '<input type="hidden" id="rerata_akhir_input" name="rerata_akhir[]" value="'.$remedial->rerata_akhir.'" />';
				$html .= $remedial->rerata_akhir;
				$html .= '</strong></td>';
				$html .= '<td id="rerata_remedial" class="text-center '.$bg_rerata_remedial.'"><strong>';
				$html .= '<input type="hidden" id="rerata_remedial_input" name="rerata_remedial[]" value="'.$remedial->rerata_remedial.'" />';
				$html .= $remedial->rerata_remedial;
				$html .= '</strong></td>';
			} elseif($id_kds){
				$set_rerata = 0;
				$pembagi = 0;
				foreach($id_kds as $id_kd){
					unset($nilai_siswa);

					$all_nilai = $this->nilai->with('rencana_penilaian')->find_all("semester_id = $ajaran_id AND kompetensi_id = $kompetensi_id AND siswa_id = $siswa_id AND kompetensi_dasar_id = $id_kd");
					if($all_nilai){
						$pembagi++;
						if($kompetensi_id == 1){
							$n_s_final = 0;
							foreach($all_nilai as $set_nilai){
								$nilai_siswa[] = $set_nilai->nilai;
							}
							//$nilai_remedial = $n_s_final;
							$nilai_remedial = array_sum($nilai_siswa) / count($nilai_siswa); 
						} else {
							$nilai_siswa = array();
							foreach($all_nilai as $set_nilai){
								$nilai_siswa[$set_nilai->kompetensi_dasar_id.'_'.$set_nilai->rencana_penilaian->metode_id][] = $set_nilai->nilai;
							}
							ksort($nilai_siswa, SORT_NUMERIC);
							$n_s_final = 0;
							foreach($nilai_siswa as $n_s){
								if(count($n_s) > 1){
									$n_s_final += max($n_s) / count($nilai_siswa);
								} else {
									$n_s_final += array_sum($n_s) / count($nilai_siswa); 
								}
							}
							$nilai_remedial = $n_s_final;
						}
						if($kkm > number_format($nilai_remedial,0)){
							$aktif = '';
							$bg = 'bg-red';
						} else {
							$aktif = 'readonly';
							$bg = 'bg-green';
						}
						$set_rerata += $nilai_remedial;
						$nilai = '<input type="text" name="rerata['.$siswa->siswa->id.'][]" size="10" class="'.$bg.' form-control input-sm" value="'.number_format($nilai_remedial,0).'" '.$aktif.' />';
					} else {
						$nilai = '<input type="hidden" name="rerata['.$siswa->siswa->id.'][]" value="0" />-';
					}
					$html .= '<td class="text-center">';
					$html .= $nilai;
					$html .= '</td>';
				}
				if($set_rerata){
					//echo $set_rerata;
					$rerata_akhir = number_format($set_rerata / $pembagi,0);
					$rerata_remedial = number_format($set_rerata / $pembagi,0);
				} else {
					$rerata_akhir = '';
					$rerata_remedial = '';
				}
				//$bg_rerata_akhir = 'text-red';
				//$bg_rerata_remedial = 'text-red';
				if($kkm > $rerata_akhir){
					$bg_rerata_akhir = 'text-red';
				} else {
					$bg_rerata_akhir = 'text-green';
				}
				if($kkm > $rerata_remedial){
					$bg_rerata_remedial = 'text-red';
				} else {
					$bg_rerata_remedial = 'text-green';
				}
				$html .= '<td id="rerata_akhir" class="text-center '.$bg_rerata_akhir.'"><strong>';
				$html .= '<input type="hidden" id="rerata_akhir_input" name="rerata_akhir[]" value="'.$rerata_akhir.'" />';
				$html .= $rerata_akhir;
				$html .= '</strong></td>';
				$html .= '<td id="rerata_remedial" class="text-center '.$bg_rerata_remedial.'"><strong>';
				$html .= '<input type="hidden" id="rerata_remedial_input" name="rerata_remedial[]" value="'.$rerata_remedial.'" />';
				$html .= $rerata_remedial;
				$html .= '</strong></td>';
			}
			$html .= '</tr>';
		}
		$html .= '<input type="hidden" id="get_all_kd" value="'.$pembagi.'" />';
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';
		$html .= link_tag('assets/css/tooltip-viewport.css', 'stylesheet', 'text/css');
		$html .= '<script src="'.base_url().'assets/js/tooltip-viewport.js"></script>';
		$html .= '<script src="'.base_url().'assets/js/remedial.js"></script>';
		echo $html;
	}
	public function deskripsi_mapel(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$siswa = $this->siswa->get($loggeduser->siswa_id);
		if($siswa){
			$data['ajaran']	= $ajaran->tahun.' Semester '.$ajaran->smt;
			$data['deskripsi'] = Deskripsi::find_all_by_ajaran_id_and_siswa_id($ajaran->id, $siswa->id);
			$this->template->title('Administrator Panel')
			->set_layout($this->admin_tpl)
			->set('page_title', 'Deskripsi per Mata Pelajaran')
			->build($this->admin_folder.'/asesmen/catatan_siswa',$data);
		} else {
			//$data['ajarans'] = Ajaran::all();
			//$data['rombels'] = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
			$this->template->title('Administrator Panel')
			->set_layout($this->admin_tpl)
			->set('page_title', 'Deskripsi per Mata Pelajaran')
			->set('form_action', 'admin/asesmen/simpan_deskripsi_mapel')
			->build($this->admin_folder.'/asesmen/deskripsi_mapel');
		}
	}
	public function simpan_deskripsi_mapel(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$mapel_id = $_POST['id_mapel'];
		$siswa_id = $_POST['siswa_id'];
		foreach($siswa_id as $key=>$siswa){
			//$deskripsi_pengetahuan = Deskripsi::find_by_ajaran_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran_id,$rombel_id,$mapel_id,$siswa);
			$deskripsi_mapel = $this->deskripsi_mata_pelajaran->find("semester_id = $ajaran_id and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $mapel_id and siswa_id = $siswa");
			if($deskripsi_mapel){
				$data_update = array(
					'deskripsi_pengetahuan' => $_POST['deskripsi_pengetahuan'][$key],
					'deskripsi_keterampilan' => $_POST['deskripsi_keterampilan'][$key],
				);
				$this->deskripsi_mata_pelajaran->update($deskripsi_mapel->id, $data_update);
				//$this->session->set_flashdata('error', 'Terdeteksi data existing. Data di update!');
				$this->session->set_flashdata('success', 'Berhasil memperbaharui deskripsi per mata pelajaran');
			} else {
				$data_insert = array(
					'semester_id' 				=> $ajaran_id,
					'rombongan_belajar_id'		=> $rombel_id,
					'mata_pelajaran_id'			=> $mapel_id,
					'siswa_id'					=> $siswa,
					'deskripsi_pengetahuan'		=> $_POST['deskripsi_pengetahuan'][$key],
					'deskripsi_keterampilan'	=> $_POST['deskripsi_keterampilan'][$key],
				);
				$this->deskripsi_mata_pelajaran->insert($data_insert);
				$this->session->set_flashdata('success', 'Berhasil menambah deskripsi per mata pelajaran');
			}
		}
		redirect('admin/asesmen/deskripsi_mapel');
	}
	public function get_siswa(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$all_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id);
		$all_mapel = Kurikulum::find_all_by_data_rombel_id($rombel_id);
		$all_mulok = Mulok::find_all_by_ajaran_id_and_rombel_id($ajaran_id,$rombel_id);
		if($all_mapel){
			foreach($all_mapel as $mapel){
				$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
				$record= array();
				$record['value'] 	= $mapel->id_mapel;
				$record['text'] 	= $data_mapel->nama_mapel.' ('.$data_mapel->id_mapel.')';
				$output['mapel'][] = $record;
			}
		} else {
			$record['value'] 	= '0';
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
			$record['value'] 	= '0';
			$record['text'] 	= 'Tidak ditemukan siswa di rombel terpilih';
			$output['result'][] = $record;
		}
		if($all_mulok){
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
		}
		echo json_encode($output);
	}
	public function get_prakerin(){
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['rombel_id'] = $_POST['rombel_id'];
		$data['siswa_id'] = $_POST['siswa_id'];
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', 'Entry Absensi')
		->build($this->admin_folder.'/laporan/add_prakerin',$data);
	}
	public function get_prestasi(){
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['rombel_id'] = $_POST['rombel_id'];
		$data['siswa_id'] = $_POST['siswa_id'];
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', 'Entry Absensi')
		->build($this->admin_folder.'/laporan/add_prestasi',$data);
	}
	function form_sikap($ajaran_id,$rombel_id,$mapel_id,$siswa_id){
		$ajaran = get_ta();
		$data['all_sikap'] = $this->nilai_sikap->find_all("semester_id = $ajaran_id and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $mapel_id and siswa_id = $siswa_id");
		//Sikap::find_all_by_ajaran_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran_id,$rombel_id,$mapel_id,$siswa_id);
		//$data['data_sikap'] = Datasikap::find_all_by_ajaran_id($ajaran->id);
		$data['data_sikap'] = $this->sikap->get_all();
		$data['mapel_id'] = $mapel_id;
		$this->template->title('')
		->set_layout($this->blank_tpl)
		->set('page_title', '')
		->build($this->admin_folder.'/asesmen/form_sikap',$data);
	}
	public function get_sikap(){
		$ajaran_id = $_POST['ajaran_id'];
		$rombel_id = $_POST['rombel_id'];
		$mapel_id = $_POST['id_mapel'];
		$siswa_id = $_POST['siswa_id'];
		$this->form_sikap($ajaran_id,$rombel_id,$mapel_id,$siswa_id);
	}
	public function edit_sikap($id){
		if($_POST){
			$update_attributes = array(
				'butir_sikap' => $_POST['butir_sikap'], 
				'opsi_sikap' => $_POST['opsi_sikap'], 
				'uraian_sikap' => $_POST['uraian_sikap']
			);
			$sikap = $this->nilai_sikap->update($id, $update_attributes);
			//Sikap::find_by_id($id);
			$ajaran_id = $_POST['ajaran_id'];
			$rombel_id = $_POST['rombel_id'];
			$siswa_id = $_POST['siswa_id'];
			$mapel_id = $_POST['mapel_id'];
			//$ajaran_id,$rombel_id,$mapel_id,$siswa_id
			$this->form_sikap($ajaran_id,$rombel_id,$mapel_id, $siswa_id);
		} else {
			$ajaran = get_ta();
			$sikap = $this->nilai_sikap->get($id);
			//Sikap::find_by_id($id);
			$data_sikap = $this->sikap->get_all();
			//Datasikap::all();
			$this->template->title('Administrator Panel : Edit Sikap')
			->set_layout($this->modal_tpl)
			->set('page_title', 'Edit Sikap')
			->set('sikap', $sikap)
			->set('data_sikap', $data_sikap)
			->set('modal_footer', '<a class="btn btn-primary" id="button_form" href="javascript:void(0);">Update</a>')
			->build($this->admin_folder.'/asesmen/edit_sikap');
		}
	}
	public function simpan_sikap(){
		$ajaran_id		= $_POST['ajaran_id'];
		$rombel_id		= $_POST['rombel_id'];
		$mapel_id		= $_POST['mapel_id'];
		$siswa_id		= $_POST['siswa_id'];
		$tanggal_sikap	= $_POST['tanggal_sikap'];
		//$tanggal_sikap	= str_replace('/','-',$tanggal_sikap);
		$tanggal_sikap	= date('Y-m-d', strtotime($tanggal_sikap));
		$butir_sikap	= $_POST['butir_sikap'];
		$opsi_sikap		= $_POST['opsi_sikap'];
		$uraian_sikap	= $_POST['uraian_sikap'];
		$sikap = $this->nilai_sikap->find_all("semester_id = $ajaran_id and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $mapel_id and siswa_id = $siswa_id AND opsi_sikap = $opsi_sikap");
		//Sikap::find_by_ajaran_id_and_rombel_id_and_mapel_id_and_siswa_id_and_butir_sikap($ajaran_id,$rombel_id, $mapel_id, $siswa_id,$butir_sikap);
		if($sikap){
			/*$sikap->ajaran_id		= $ajaran_id;
			$sikap->rombel_id		= $rombel_id;
			$sikap->siswa_id		= $siswa_id;
			$sikap->tanggal_sikap	= $tanggal_sikap;
			$sikap->butir_sikap		= $butir_sikap;
			$sikap->uraian_sikap	= $uraian_sikap;
			$sikap->save();*/
			$this->session->set_flashdata('error', 'Terdeteksi data existing');
		} else {
			$attributes = array(
								'semester_id'	=> $ajaran_id,
								'rombongan_belajar_id'	=> $rombel_id,
								'mata_pelajaran_id'	=> $mapel_id,
								'siswa_id'	=> $siswa_id,
								'tanggal_sikap' => $tanggal_sikap,
								'butir_sikap'	=> $butir_sikap,
								'opsi_sikap'		=> $opsi_sikap,
								'uraian_sikap' => $uraian_sikap,
							);
			$this->nilai_sikap->insert($attributes);
			$this->session->set_flashdata('success', 'Berhasil menambah data sikap');
		}
		redirect('admin/asesmen/sikap');
	}
	public function get_deskripsi_mapel(){
		$ajaran_id	= $_POST['ajaran_id'];
    	$rombel_id	= $_POST['rombel_id'];
    	$id_mapel	= $_POST['id_mapel'];
		$rombel = Datarombel::find_by_id($rombel_id);
		$all_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id);
		$mapel = Datamapel::find($id_mapel);
		$html = '';
		if($all_siswa){
			$html .= '<div class="table-responsive no-padding">';
			$html .= '<table class="table table-bordered table-hover">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th width="20%">Nama Siswa</th>';
			$html .= '<th width="40%">Deskripsi Pengetahuan</th>';
			$html .= '<th width="40%">Deskripsi Keterampilan</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$result_kd_pengetahuan_tertinggi = 'Belum dilakukan penilaian';
			$result_kd_pengetahuan_terendah = 'Belum dilakukan penilaian';
			$result_kd_keterampilan_tertinggi = 'Belum dilakukan penilaian';
			$result_kd_keterampilan_terendah = 'Belum dilakukan penilaian';
			foreach($all_siswa as $key=>$siswa){
				$nilai_pengetahuan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran_id, 1, $rombel_id, $id_mapel, $siswa->id);
				if($nilai_pengetahuan){
					foreach($nilai_pengetahuan as $nilaipengetahuan){
						$rencana_pengetahuan_id[$key] = $nilaipengetahuan->rencana_penilaian_id;
						$get_nilai_pengetahuan[] = $nilaipengetahuan->nilai;
					}
					$nilai_pengetahuan_tertinggi = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 1, $rombel_id, $id_mapel, $siswa->id, bilanganBesar($get_nilai_pengetahuan));
					if($nilai_pengetahuan_tertinggi){
						$rencana_penilaian_pengetahuan_tertinggi = Rencanapenilaian::find($nilai_pengetahuan_tertinggi->rencana_penilaian_id);
						$get_kd_pengetahuan_tertinggi = Kd::find($rencana_penilaian_pengetahuan_tertinggi->kd_id);
						$result_kd_pengetahuan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($get_kd_pengetahuan_tertinggi->kompetensi_dasar);
					}
					//space tinggi rendah
					$nilai_pengetahuan_terendah = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 1, $rombel_id, $id_mapel, $siswa->id, bilanganKecil($get_nilai_pengetahuan));
					if($nilai_pengetahuan_terendah){
						$rencana_penilaian_pengetahuan_terendah = Rencanapenilaian::find($nilai_pengetahuan_terendah->rencana_penilaian_id);
						$get_kd_pengetahuan_terendah = Kd::find($rencana_penilaian_pengetahuan_terendah->kd_id);
						$result_kd_pengetahuan_terendah = ' dan perlu meningkatkan kompetensi '.strtolower($get_kd_pengetahuan_terendah->kompetensi_dasar);
					}
				}
				$nilai_keterampilan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran_id, 2, $rombel_id, $id_mapel, $siswa->id);
				if($nilai_keterampilan){
					foreach($nilai_keterampilan as $nilaiketerampilan){
						$rencana_keterampilan_id[$key] = $nilaiketerampilan->rencana_penilaian_id;
						$get_nilai_keterampilan[] = $nilaiketerampilan->nilai;
					}
					$nilai_keterampilan_tertinggi = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 2, $rombel_id, $id_mapel, $siswa->id, bilanganBesar($get_nilai_keterampilan));
					if($nilai_keterampilan_tertinggi){
						$rencana_penilaian_keterampilan_tertinggi = Rencanapenilaian::find_by_id($nilai_keterampilan_tertinggi->rencana_penilaian_id);
						if($rencana_penilaian_keterampilan_tertinggi){
							$get_kd_keterampilan_tertinggi = Kd::find($rencana_penilaian_keterampilan_tertinggi->kd_id);
							$result_kd_keterampilan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($get_kd_keterampilan_tertinggi->kompetensi_dasar);
						}
					}
					//space tinggi rendah
					$nilai_keterampilan_terendah = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_nilai($ajaran_id, 2, $rombel_id, $id_mapel, $siswa->id, bilanganKecil($get_nilai_keterampilan));
					if($nilai_keterampilan_terendah){
						$rencana_penilaian_keterampilan_terendah = Rencanapenilaian::find_by_id($nilai_keterampilan_terendah->rencana_penilaian_id);
						if($rencana_penilaian_keterampilan_terendah){
							$get_kd_keterampilan_terendah = Kd::find_by_id($rencana_penilaian_keterampilan_terendah->kd_id);
							$result_kd_keterampilan_terendah = ' dan perlu meningkatkan kompetensi '.strtolower($get_kd_keterampilan_terendah->kompetensi_dasar);
						}
					}
				}
				$html .= '<input type="hidden" name="siswa_id[]" value="'.$siswa->id.'" />';
				$html .= '<tr>';
				$html .= '<td>';
				$html .= $siswa->nama;
				$html .= '</td>';
				$html .= '<td>';
				$html .= '<textarea name="deskripsi_pengetahuan[]" class="editor" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">';
				//$html .= bilanganKecil($get_nilai_pengetahuan).'<br />';
				//$html .= bilanganBesar($get_nilai_pengetahuan).'<br />';
				$html .= $result_kd_pengetahuan_tertinggi.'<br />'.$result_kd_pengetahuan_terendah;
				$html .= '</textarea>';
				$html .= '</td>';
				$html .= '<td>';
				$html .= '<textarea name="deskripsi_keterampilan[]" class="editor" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">';
				$html .= $result_kd_keterampilan_tertinggi.'<br />'.$result_kd_keterampilan_terendah;
				$html .= '</textarea>';
				$html .= '</td>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';
		} else {
			$html .= '<h4>Belum ada siswa di kelas terpilih</h4>';
		}
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
	public function get_rerata(){
		$siswa_id = $_POST['siswa_id'];
		$jumlah_kd = $_POST['jumlah_kd'];
		$bobot = $_POST['bobot_kd'];
		$all_bobot = $_POST['all_bobot'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$kds = $_POST['kd'];
		/*$rencana_id = $_POST['rencana'];
		$get_all_bobot = Rencanapenilaian::find('all', array('conditions' => "rencana_id = $rencana->id", 'group' => 'nama_penilaian',));
		foreach($get_all_bobot as $getbobot){
			$html .= '<input type="hidden" name="all_bobot[]" value="'.$getbobot->bobot_penilaian.'" />';
		}*/
		//if($kompetensi_id == 1){
			$total_bobot = 0;
			foreach($all_bobot as $allbobot){
				$total_bobot += $allbobot;
			}
			$total_bobot = ($total_bobot > 0) ? $total_bobot : 1;
			$bobot = ($bobot > 0) ? $bobot : 1;
			$output['jumlah_form'] = count($siswa_id);
			foreach($siswa_id as $k=>$siswa){
				$hitung = 0;
				foreach($kds as $kd){
					$hitung += $kd[$k];
				}
				$hasil = $hitung/$jumlah_kd;
				$rerata_nilai = $hasil*$bobot;//($hasil*$bobot)/$total_bobot;
				$rerata_jadi = number_format($rerata_nilai/$total_bobot,2);
				$record['value'] 	= number_format($hitung/$jumlah_kd,0);
				//=F6*(C4/(C4+G4+J4+M4))
				$record['rerata_text'] 	= 'x '.$bobot.' / '.$total_bobot.' =';
				$record['rerata_jadi'] 	= $rerata_jadi;
				$output['rerata'][] = $record;
			}
			$settings 	= $this->settings->get(1);
			$html = '';
			if($settings->rumus == 1){
				$html .= '<p><strong>Rumus nilai akhir per penilaian: <br />Rerata * Bobot Penilaian / Total bobot penilaian per mapel</strong></p>';
				$html .= '<p>Keterangan: <br />Bobot : '.$bobot.'<br />Total Bobot : '.$total_bobot.'</p>';
			}
			$output['rumus'] = '';//$html;
		/*} else {
			$output['jumlah_form'] = count($siswa_id);
			foreach($siswa_id as $k=>$siswa){
				$hitung=0;
				for ($i = 1; $i <= $jumlah_kd; $i++) {
					if($_POST['kd_'.$i][$k]){
						$hitung += $_POST['kd_'.$i][$k];//
					} else {
						$hitung = 0;
					}
				}
				$hitung_nilai = $hitung / $jumlah_kd;
				$record['value'] 	= number_format($hitung_nilai,0);
				//=F6*(C4/(C4+G4+J4+M4))
				$record['rerata_text'] 	= '';
				$record['rerata_jadi'] 	= $hitung_nilai;
				$output['rerata'][] = $record;
			}
			$output['rumus'] = '';
		}*/
		echo json_encode($output);
	}
	public function simpan_nilai(){
		unset($_POST['query'], $_POST['query_2'], $_POST['kelas'], $_POST['rencana_id'], $_POST['import']);
		$ajaran_id = $_POST['ajaran_id'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$rombel_id = $_POST['rombel_id'];
		$mapel_id = $_POST['id_mapel'];
		$bobot_kd = $_POST['bobot_kd'];
		$rencana_penilaian_id = $_POST['rencana_penilaian_id'];
		$all_bobot = $_POST['all_bobot'];
		$all_bobot = array_sum($all_bobot);
		$jumlah_kd = $_POST['jumlah_kd'];
		$siswa_id = $_POST['siswa_id'];
		$kds = $_POST['kd'];
		$redirect = '';
		$title = '';
		if($kompetensi_id == 1){
			$redirect = 'pengetahuan';
			$title = 'pengetahuan';
		} else {
			$redirect = 'keterampilan';
			$title = 'keterampilan';
		}
		//array_walk($kds, 'check_great_than_one_fn',$redirect);
		//array_walk($kds, 'check_numeric','asesmen/'.$redirect);
		foreach($siswa_id as $k=>$siswa){
			foreach($kds as $key=>$kd) {
				array_walk($kd, 'check_great_than_one_fn',$redirect);
				array_walk($kd, 'check_numeric','asesmen/'.$redirect);
				$get_kd_nilai_id = $this->kd_nilai->find("rencana_penilaian_id = $rencana_penilaian_id AND kd_id = $key");
				if($get_kd_nilai_id){
					$get_nilai = $this->nilai->find("semester_id = $ajaran_id AND rencana_penilaian_id = $rencana_penilaian_id AND siswa_id = $siswa AND kompetensi_dasar_id = $key AND kd_nilai_id = $get_kd_nilai_id->id");
					$insert_nilai = array(
						'semester_id' 			=> $ajaran_id, 
						'kompetensi_id' 		=> $kompetensi_id, 
						'rombongan_belajar_id'	=> $rombel_id, 
						'mata_pelajaran_id' 	=> $mapel_id, 
						'siswa_id' 				=> $siswa,
						'rencana_penilaian_id'	=> $rencana_penilaian_id,
						'kompetensi_dasar_id' 	=> $key,
						'kd_nilai_id'			=> $get_kd_nilai_id->id,
						'nilai'					=> $kd[$k],
						'rerata'				=> $_POST['rerata'][$k],
						'rerata_jadi'			=> $_POST['rerata_jadi'][$k],
					);
					if($get_nilai){
						$this->nilai->update($get_nilai->id, $insert_nilai);
						$this->session->set_flashdata('success', 'Berhasil memperbaharui data nilai '.$title);
					} else {
						$this->nilai->insert($insert_nilai);
						$this->session->set_flashdata('success', 'Berhasil menambah data nilai '.$title);
					}
				} else {
					$this->session->set_flashdata('error', 'Gagal menambah nilai '.$title.'. Periksa kembali isian Anda');
				}
			}
		}
		redirect('admin/asesmen/'.$redirect);
	}
	public function delete_file($id,$file){
		$portofolio = Dataportofolio::find($id);
		$portofolio->delete();
		$filename = './uploads/'.$file;
		if (file_exists($filename)) {
			unlink($filename);
		}
	}
	public function get_nilai(){
		$query = $_POST['query'];
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['id_mapel'] = $_POST['id_mapel'];
		$data['rombel_id'] = $_POST['rombel_id'];
		$data['kompetensi_id'] = isset($_POST['kompetensi_id']) ? $_POST['kompetensi_id'] : '';
		if($query == 'nilai'){
			$query = 'portofolio';
		}
		$this->template->title('')
		->set_layout($this->blank_tpl)
		->set('page_title', '')
		->build($this->admin_folder.'/asesmen/form_'.$query,$data);
	}
	public function get_kd_penilaian(){
		$html = '';
		$settings 	= $this->settings->get(1);
		$ajaran_id = $_POST['ajaran_id'];
		$post	= $_POST['rencana_id'];
		$post = explode("#", $post);
		$rencana_penilaian_id = $post[0];
		$nama_penilaian = $post[1];
		$bobot_kd = $post[2];
		$bobot_kd = ($bobot_kd > 0) ? $bobot_kd : 1;
		$rombel_id	= $_POST['rombel_id'];
		$id_mapel = $_POST['id_mapel'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$kkm = get_kkm($ajaran_id, $rombel_id, $id_mapel);
		$html .= '<input type="hidden" name="bobot_kd" value="'.$bobot_kd.'" />';
		$html .= '<div class="row">';
		$html .= '<div class="col-md-6">';
		//$html .= '<a class="btn btn-success btn-lg btn-block" href="'.site_url('admin/get_excel/nilai/'.$ajaran_id.'/'.$rencana_penilaian_id.'/'.$nama_penilaian.'/'.$rombel_id.'/'.$id_mapel.'/'.$kompetensi_id).'">Download Template Nilai</a>';
		$html .= '<a class="btn btn-success btn-lg btn-block" href="'.site_url('admin/get_excel/nilai/'.$rencana_penilaian_id).'">Download Template Nilai</a>';
		$html .= '</div>';
		$html .= '<div class="col-md-6">';
		$html .= '<p class="text-center"><span class="btn btn-danger btn-file btn-lg btn-block"> Import Template Nilai<input type="file" id="fileupload" name="import" /></span></p>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div style="clear:both; margin-bottom:10px;"></div>';
		$html .= '<input type="hidden" name="rencana_penilaian_id" value="'.$rencana_penilaian_id.'" />';
		$rencana_penilaian = $this->rencana_penilaian->with('mata_pelajaran')->get($rencana_penilaian_id);
		$all_kd_nilai = $this->kd_nilai->find_all_by_rencana_penilaian_id($rencana_penilaian_id);
		$data_siswa = filter_agama_siswa($rencana_penilaian->mata_pelajaran->nama_mata_pelajaran, $rombel_id);
		$get_all_bobot = $this->rencana_penilaian->find_all("semester_id = $ajaran_id AND mata_pelajaran_id = $id_mapel AND rombongan_belajar_id = $rombel_id AND kompetensi_id = $kompetensi_id");
		foreach($get_all_bobot as $getbobot){
			$set_bobot = ($getbobot->bobot > 0) ? $getbobot->bobot : 1;
			$html .= '<input type="hidden" name="all_bobot[]" value="'.$set_bobot.'" />';
			$html .= '<input type="hidden" name="rencana_penilaian_id[]" value="'.$getbobot->id.'" />';
		}
		if($all_kd_nilai){
			$jumlah_kd = count($all_kd_nilai);
			$html .= '<input type="hidden" name="jumlah_kd" id="jumlah_kd" value="'.$jumlah_kd.'" />';
			$html .= '<input type="hidden" name="kkm" id="kkm" value="'.$kkm.'" />';
			$html .= '<input type="hidden" name="rencana_penilaian_id" value="'.$rencana_penilaian_id.'" />';
			$html .= '<div class="table-responsive no-padding">';
			$html .= '<table class="table table-bordered table-hover">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th rowspan="2" style="vertical-align: middle;">Nama Siswa</th>';
			$html .= '<th class="text-center" colspan="'.$jumlah_kd.'">Kompetensi Dasar</th>';
			if($rencana_penilaian->kompetensi_id == 1){
				$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Rerata Nilai</th>';
				if($settings->rumus == 1){
					//$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Rumus</th>';
					//$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Nilai Akhir<br />Per Penilaian</th>';
				}
			}
			$html .= '</tr>';
			$html .= '<tr>';
			foreach($all_kd_nilai as $kd_nilai){
				$kd = $this->kompetensi_dasar->get($kd_nilai->kd_id);
				//$html .= '<input type="text" name="bobot_kd" value="'.$allpengetahuan->bobot_penilaian.'" />';
				$id_kd = isset($kd->id_kompetensi) ? $kd->id_kompetensi : 0;
				$kompetensi_dasar = isset($kd->kompetensi_dasar) ? $kd->kompetensi_dasar : 0;
				$html .= '<th><a href="javacript:void(0)" class="tooltip-left" title="'.$kompetensi_dasar.'">'.$id_kd.'</a></th>';
			}
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$no=0;
			foreach($data_siswa as $siswa){
				$siswa_id = ($siswa->siswa) ? $siswa->siswa->id : '0';
				$nama_siswa = ($siswa->siswa) ? $siswa->siswa->nama : '';
				$html .= '<input class="set_nilai" type="hidden" name="siswa_id[]" value="'.$siswa_id.'" />';
				$html .= '<tr>';
				$html .= '<td>';
				$html .= $nama_siswa;
				$html .= '</td>';
				$i=1;
				foreach($all_kd_nilai as $kd_nilai){
					$nilai = $this->nilai->find("siswa_id = $siswa_id and rencana_penilaian_id = $rencana_penilaian->id AND kompetensi_dasar_id = $kd_nilai->kd_id");
					$nilai_value 	= ($nilai) ? $nilai->nilai : '';
					$rerata 		= ($nilai) ? $nilai->rerata : '';
					$rerata_jadi 	= ($nilai) ? $nilai->rerata_jadi : '';
					if ($nilai_value == '') {
						$html .= '<td><input type="number" name="kd['.$kd_nilai->kd_id.'][]" size="10" class="form-control" value="0" autocomplete="off" maxlength="3" required /></td>';
					}else {
						$html .= '<td><input type="number" name="kd['.$kd_nilai->kd_id.'][]" size="10" class="form-control" value="'.$nilai_value.'" autocomplete="off" maxlength="3" required /></td>';
					}
					$i++;
				}
				if($rencana_penilaian->kompetensi_id == 1){
					$html .= '<td><input type="text" name="rerata[]" id="rerata_'.$no.'" size="10" class="form-control" value="'.$rerata.'" readonly /></td>';
					$html .= '<input type="hidden" name="rerata_jadi[]" id="rerata_jadi_'.$no.'" size="10" class="form-control" value="'.$rerata_jadi.'" />';
					//disini rumus rerata_jadi
					if($settings->rumus == 1){
						//$html .= '<td class="text-center"><strong><span id="rerata_text_'.$no.'"></span></strong></td>';
						$html .= '<td><input type="hidden" name="rerata_jadi[]" id="rerata_jadi_'.$no.'" size="10" class="form-control" value="'.$rerata_jadi.'" readonly /></td>';
					}
				} else {
					$html .= '<input type="hidden" name="rerata[]" id="rerata_'.$no.'" size="10" class="form-control" value="'.$rerata.'" />';
					$html .= '<input type="hidden" name="rerata_jadi[]" id="rerata_jadi_'.$no.'" size="10" class="form-control" value="'.$rerata_jadi.'" />';
				}
				$html .= '</tr>';
				$no++;
			}
			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';
		} else {
			$html .= '<h4>Tidak ada KD terpilih di Perencanaan Penilaian</h4>';
		}
		$html .= link_tag('assets/css/tooltip-viewport.css', 'stylesheet', 'text/css');
		$html .= '<script src="'.base_url().'assets/js/tooltip-viewport.js"></script>';
		$html .= '<script>';
		$html .= 'var SET_URL = "'.site_url('import/import_nilai').'";';
		$html .= '</script>';
		$html .= '<script src="'.base_url().'assets/js/file_upload.js"></script>';
		echo $html;
	}
	public function get_kd_penilaian_old(){
		$html = '';
		$settings 	= Setting::first();
		$ajaran_id = $_POST['ajaran_id'];
		$post	= $_POST['rencana_id'];
		$post = explode("#", $post);
		$rencana_id = $post[0];
		$nama_penilaian = $post[1];
		$bobot_kd = $post[3];
		$bobot_kd = ($bobot_kd > 0) ? $bobot_kd : 1;
		$rombel_id	= $_POST['rombel_id'];
		$id_mapel = $_POST['id_mapel'];
		$kompetensi_id = $_POST['kompetensi_id'];
		$rencana = Rencana::find_by_id($rencana_id);
		$html .= '<input type="hidden" name="bobot_kd" value="'.$bobot_kd.'" />';
		$html .= '<div class="row"><div class="col-md-6">';
		$html .= '<a class="btn btn-success btn-lg btn-block" href="'.site_url('admin/get_excel/nilai/'.$ajaran_id.'/'.$rencana_id.'/'.$nama_penilaian.'/'.$rombel_id.'/'.$id_mapel.'/'.$kompetensi_id).'">Download Template Nilai</a>';
		$html .= '</div>';
		$html .= '<div class="col-md-6">';
		$html .= '<p class="text-center"><span class="btn btn-danger btn-file btn-lg btn-block"> Import Template Nilai<input type="file" id="fileupload" name="import" /></span></p>';
		$html .= '</div></div>';
		$html .= '<div style="clear:both; margin-bottom:10px;"></div>';
		$all_rencana = Rencana::find_all_by_ajaran_id_and_rombel_id_and_id_mapel_and_kompetensi_id($ajaran_id,$rombel_id,$id_mapel,$kompetensi_id);
		foreach($all_rencana as $ren){
				$id_rencana[] = $ren->id;
		} 
		$get_all_bobot_new = Rencanapenilaian::find('all', array('group' => 'nama_penilaian','order'=>'id ASC', 'conditions' => array("rencana_id IN(?)",$id_rencana)));
		$data_mapel = Datamapel::find_by_id_mapel($id_mapel);
		if($data_mapel){
			$nama_mapel = $data_mapel->nama_mapel;
		} else {
			$nama_mapel = '';
		}
		$get_all_bobot = Rencanapenilaian::find('all', array('conditions' => "rencana_id = $rencana->id", 'group' => 'nama_penilaian',));
		$all_pengetahuan = Rencanapenilaian::find_all_by_rencana_id_and_nama_penilaian($rencana_id,$nama_penilaian);
		$data_siswa = filter_agama_siswa($nama_mapel,$rencana->rombel_id);
		foreach($get_all_bobot_new as $getbobot){
			$set_bobot = ($getbobot->bobot_penilaian > 0) ? $getbobot->bobot_penilaian : 1;
			$html .= '<input type="hidden" name="all_bobot[]" value="'.$set_bobot.'" />';
			$html .= '<input type="hidden" name="rencana_penilaian_id[]" value="'.$getbobot->id.'" />';
		}
		if($all_pengetahuan){
			$jumlah_kd = count($all_pengetahuan);
			$html .= '<input type="hidden" name="jumlah_kd" value="'.$jumlah_kd.'" />';
			$html .= '<input type="hidden" name="rencana" value="'.$rencana_id.'" />';
			$html .= '<div class="table-responsive no-padding">';
			$html .= '<table class="table table-bordered table-hover">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th rowspan="2" style="vertical-align: middle;">Nama Siswa</th>';
			$html .= '<th class="text-center" colspan="'.$jumlah_kd.'">Kompetensi Dasar</th>';
			if($rencana->kompetensi_id == 1){
				$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Rerata Nilai</th>';
				if($settings->rumus == 1){
					//$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Rumus</th>';
					//$html .= '<th rowspan="2" style="vertical-align: middle;" class="text-center">Nilai Akhir<br />Per Penilaian</th>';
				}
			}
			$html .= '</tr>';
			$html .= '<tr>';
			foreach($all_pengetahuan as $allpengetahuan){
				$kd = Kd::find_by_id($allpengetahuan->kd_id);
				//$html .= '<input type="text" name="bobot_kd" value="'.$allpengetahuan->bobot_penilaian.'" />';
				$id_kd = isset($kd->id_kompetensi) ? $kd->id_kompetensi : 0;
				$kompetensi_dasar = isset($kd->kompetensi_dasar) ? $kd->kompetensi_dasar : 0;
				$html .= '<th><a href="javacript:void(0)" class="tooltip-left" title="'.$kompetensi_dasar.'">'.$id_kd.'</a></th>';
			}
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$no=0;
			foreach($data_siswa as $siswa){
				$html .= '<input class="set_nilai" type="hidden" name="siswa_id[]" value="'.$siswa->id.'" />';
				$html .= '<tr>';
				$html .= '<td>';
				$html .= $siswa->nama;
				$html .= '</td>';
				$i=1;
				foreach($all_pengetahuan as $allpengetahuan){
					$nilai = Nilai::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id_and_rencana_penilaian_id($ajaran_id, $rencana->kompetensi_id, $rencana->rombel_id, $rencana->id_mapel, $siswa->id, $allpengetahuan->id);
					$nilai_value = isset($nilai) ? $nilai->nilai : '';
					$rerata = isset($nilai) ? $nilai->rerata : '';
					$rerata_jadi = isset($nilai) ? $nilai->rerata_jadi : '';
					$html .= '<input type="hidden" name="rencana_penilaian_id_'.$i.'[]" value="'.$allpengetahuan->id.'" />';
					$html .= '<td><input type="text" name="kd_'.$i.'[]" size="10" class="form-control" value="'.$nilai_value.'" autocomplete="off" maxlength="3" required /></td>';
					$i++;
				}
				if($rencana->kompetensi_id == 1){
					$html .= '<td><input type="text" name="rerata[]" id="rerata_'.$no.'" size="10" class="form-control" value="'.$rerata.'" readonly /></td>';
					if($settings->rumus == 1){
						//$html .= '<td class="text-center"><strong><span id="rerata_text_'.$no.'"></span></strong></td>';
						$html .= '<td><input type="hidden" name="rerata_jadi[]" id="rerata_jadi_'.$no.'" size="10" class="form-control" value="'.$rerata_jadi.'" readonly /></td>';
					}
				} else {
					$html .= '<input type="hidden" name="rerata[]" id="rerata_'.$no.'" size="10" class="form-control" value="'.$rerata.'" />';
					$html .= '<input type="hidden" name="rerata_jadi[]" id="rerata_jadi_'.$no.'" size="10" class="form-control" value="'.$rerata_jadi.'" readonly />';
				}
				$html .= '</tr>';
				$no++;
			}
			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';
		} else {
			$html .= '<h4>Tidak ada KD terpilih di Perencanaan Penilaian</h4>';
		}
		$html .= link_tag('assets/css/tooltip-viewport.css', 'stylesheet', 'text/css');
		$html .= '<script src="'.base_url().'assets/js/tooltip-viewport.js"></script>';
		$html .= '<script>';
		$html .= 'var SET_URL = "'.site_url('import/import_nilai').'";';
		$html .= '</script>';
		$html .= '<script src="'.base_url().'assets/js/file_upload.js"></script>';
		//echo $jumlah_kd;
		echo $html;
	}
	public function get_analisis_penilaian(){
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['rombel_id'] = $_POST['rombel_id'];
		$data['mapel_id'] = $_POST['id_mapel'];
		$post	= $_POST['penilaian'];
		$post = explode("#", $post);
		$data['rencana_id'] = $post[0];
		if(!isset($post[1])){
			exit;
		}
		$data['nama_penilaian'] = $post[1];
		$data['kompetensi_id'] = $post[2];
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', 'Analisis Hasil Penilaian')
		->build($this->admin_folder.'/monitoring/analisis_penilaian',$data);
	}
	public function get_analisis_kompetensi(){
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$post	= $_POST['penilaian'];
		$post = explode("#", $post);
		if(!isset($post[1])){
			exit;
		}
		$rencana_id = $post[0];
		$nama_penilaian = $post[1];
		$rencana_penilaian = Rencanapenilaian::find_all_by_rencana_id_and_nama_penilaian($rencana_id,$nama_penilaian);
		if($rencana_penilaian){
			ksort($rencana_penilaian);
			foreach($rencana_penilaian as $rp){
				$record= array();
				$record['value'] 	= $rp->kd_id.'#'.$rp->id;
				$record['text'] 	= $rp->kd;
				$output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan KD di rencana penilaian terpilih';
			$output['result'][] = $record;
		}
		echo json_encode($output);
	}
	public function get_analisis_individu_old($id = NULL){
		$html = '';
		if($id){
			$ajaran = get_ta();
			$siswa = Datasiswa::find_by_id($id);
			$mapel = Datamapel::find_by_id_mapel($_POST['id_mapel']);
			$data_rombel = Datarombel::find_by_id($siswa->data_rombel_id);
			$nilai_pengetahuan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 1, $siswa->data_rombel_id, $mapel->id_mapel, $siswa->id);
			$nilai_keterampilan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 2, $siswa->data_rombel_id, $mapel->id_mapel, $siswa->id);
			$html .= '<table class="table table-bordered table-hover">';
			$html .= '<tr>';
			$html .= '<th width="10%" class="text-center">ID KD</th>';
			$html .= '<th width="80%">Kompetensi Dasar Pengetahuan</th>';
			$html .= '<th width="10%" class="text-center">Rerata Nilai</th>';
			$html .= '</tr>';
			foreach($nilai_pengetahuan as $np){
				$rencana_penilaian_pengetahuan = Rencanapenilaian::find_by_id($np->rencana_penilaian_id);
				$get_pengetahun[$rencana_penilaian_pengetahuan->kd][] = $np->nilai;
			}
			ksort($get_pengetahun);
			foreach($get_pengetahun as $key=>$gp){
				$get_kompetensi = Kd::find_by_id_kompetensi_and_id_mapel_and_kelas($key,$mapel->id_mapel,$data_rombel->tingkat);
				$jumlah_kd = array_sum($gp);
				$rerata_nilai = number_format(($jumlah_kd / count($gp)),0);
				$html .= '<tr>';
				$html .= '<td class="text-center">'.$key.'</td>';
				$html .= '<td>'.$get_kompetensi->kompetensi_dasar.'</td>';
				$html .= '<td class="text-center">'.$rerata_nilai.'</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
			$html .= '<table class="table table-bordered table-hover" style="margin-top:10px;">';
			$html .= '<tr>';
			$html .= '<th width="10%" class="text-center">ID KD</th>';
			$html .= '<th width="80%">Kompetensi Dasar Keterampilan</th>';
			$html .= '<th width="10%" class="text-center">Rerata Nilai</th>';
			$html .= '</tr>';
			foreach($nilai_keterampilan as $nk){
				$rencana_penilaian_keterampilan = Rencanapenilaian::find_by_id($nk->rencana_penilaian_id);
				$get_keterampilan[$rencana_penilaian_keterampilan->kd][] = $nk->nilai;
			}
			ksort($get_keterampilan);
			foreach($get_keterampilan as $key=>$gk){
				$get_kompetensi = Kd::find_by_id_kompetensi_and_id_mapel_and_kelas($key,$mapel->id_mapel,$data_rombel->tingkat);
				$jumlah_kd = array_sum($gk);
				$rerata_nilai = number_format(($jumlah_kd / count($gk)),0);
				$html .= '<tr>';
				$html .= '<td class="text-center">'.$key.'</td>';
				$html .= '<td>'.$get_kompetensi->kompetensi_dasar.'</td>';
				$html .= '<td class="text-center">'.$rerata_nilai.'</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
			echo $html;
		} else {
			$ajaran = get_ta();
			$data['ajaran_id'] = isset($_POST['ajaran_id']) ? $_POST['ajaran_id'] : $ajaran->id;
			$data['id_mapel'] = $_POST['id_mapel'];
			$data['rombel_id'] = $_POST['rombel_id'];
			$data['siswa_id'] = $_POST['siswa_id'];
			$this->template->title('Administrator Panel')
			->set_layout($this->blank_tpl)
			->set('page_title', '')
			->build($this->admin_folder.'/monitoring/analisis_individu',$data);
		}
	}
	public function get_analisis_individu($id = NULL){
		$html = '';
		if($id){
			test($_POST);
			die();
			$ajaran = get_ta();
			$siswa = Datasiswa::find_by_id($id);
			$mapel = Datamapel::find_by_id_mapel($_POST['id_mapel']);
			$data_rombel = Datarombel::find_by_id($siswa->data_rombel_id);
			$nilai_pengetahuan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 1, $siswa->data_rombel_id, $mapel->id_mapel, $siswa->id);
			$nilai_keterampilan = Nilai::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 2, $siswa->data_rombel_id, $mapel->id_mapel, $siswa->id);
			$html .= '<table class="table table-bordered table-hover">';
			$html .= '<tr>';
			$html .= '<th width="10%" class="text-center">ID KD</th>';
			$html .= '<th width="80%">Kompetensi Dasar Pengetahuan</th>';
			$html .= '<th width="10%" class="text-center">Rerata Nilai</th>';
			$html .= '</tr>';
			foreach($nilai_pengetahuan as $np){
				$rencana_penilaian_pengetahuan = Rencanapenilaian::find_by_id($np->rencana_penilaian_id);
				$get_pengetahun[$rencana_penilaian_pengetahuan->kd][] = $np->nilai;
			}
			ksort($get_pengetahun);
			foreach($get_pengetahun as $key=>$gp){
				$get_kompetensi = Kd::find_by_id_kompetensi_and_id_mapel_and_kelas($key,$mapel->id_mapel,$data_rombel->tingkat);
				$jumlah_kd = array_sum($gp);
				$rerata_nilai = number_format(($jumlah_kd / count($gp)),0);
				$html .= '<tr>';
				$html .= '<td class="text-center">'.$key.'</td>';
				$html .= '<td>'.$get_kompetensi->kompetensi_dasar.'</td>';
				$html .= '<td class="text-center">'.$rerata_nilai.'</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
			$html .= '<table class="table table-bordered table-hover" style="margin-top:10px;">';
			$html .= '<tr>';
			$html .= '<th width="10%" class="text-center">ID KD</th>';
			$html .= '<th width="80%">Kompetensi Dasar Keterampilan</th>';
			$html .= '<th width="10%" class="text-center">Rerata Nilai</th>';
			$html .= '</tr>';
			foreach($nilai_keterampilan as $nk){
				$rencana_penilaian_keterampilan = Rencanapenilaian::find_by_id($nk->rencana_penilaian_id);
				$get_keterampilan[$rencana_penilaian_keterampilan->kd][] = $nk->nilai;
			}
			ksort($get_keterampilan);
			foreach($get_keterampilan as $key=>$gk){
				$get_kompetensi = Kd::find_by_id_kompetensi_and_id_mapel_and_kelas($key,$mapel->id_mapel,$data_rombel->tingkat);
				$jumlah_kd = array_sum($gk);
				$rerata_nilai = number_format(($jumlah_kd / count($gk)),0);
				$html .= '<tr>';
				$html .= '<td class="text-center">'.$key.'</td>';
				$html .= '<td>'.$get_kompetensi->kompetensi_dasar.'</td>';
				$html .= '<td class="text-center">'.$rerata_nilai.'</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
			echo $html;
		} else {
			$data['data'] = $_POST;
			$this->template->title('Administrator Panel')
			->set_layout($this->blank_tpl)
			->set('page_title', '')
			->build($this->admin_folder.'/monitoring/analisis_individu',$data);
		}
	}
	public function get_ekstrakurikuler(){
		//$admin_group = array(1,2,3,5,6);
		//hak_akses($admin_group);
		$data['ajaran_id'] = $_POST['ajaran_id'];
		$data['ekskul_id'] = $_POST['ekskul_id'];
		$data['rombel_id'] = $_POST['rombel_id'];
		$this->template->title('Administrator Panel')
		->set_layout($this->blank_tpl)
		->set('page_title', 'Entry Nilai Ekstrakurikuler')
		->build($this->admin_folder.'/laporan/add_ekstrakurikuler',$data);
	}
	public function list_sikap($jurusan = NULL, $tingkat = NULL, $rombel = NULL){
		
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 10;
		$ajaran = get_ta();
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		$join = joint($jurusan, $tingkat, $rombel);
		$where = where($find_akses['name'], $nama_group, $loggeduser->guru_id);
		$search_form = "siswa_id IN(SELECT id FROM ref_siswa WHERE nama LIKE '%$search%') OR siswa_id IN(SELECT id FROM ref_siswa WHERE nisn LIKE '%$search%')";
		$query = $this->nilai_sikap->with('siswa')->with('rombongan_belajar')->with('mata_pelajaran')->find_all("semester_id =  $ajaran->id $join $where AND ($search_form)", '*','id desc', $start, $rows);
		$filter = $this->nilai_sikap->with('siswa')->with('rombongan_belajar')->with('mata_pelajaran')->find_all("semester_id =  $ajaran->id $join $where AND ($search_form)", '*','id desc');
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
			$record = array();
            $record[] = $temp->siswa->nama;
			$record[] = $temp->rombongan_belajar->nama.'/'.$temp->rombongan_belajar->tingkat;
			$record[] = $temp->mata_pelajaran->nama_mata_pelajaran;
			$record[] = butir_sikap($temp->butir_sikap);
			$record[] = '<div class="text-center">'.opsi_sikap($temp->opsi_sikap).'</div>';
			$record[] = $temp->uraian_sikap;
			$output['aaData'][] = $record;
		}
		$filter_table = filter_table($jurusan, $tingkat, $find_akses['name'], $nama_group);
		$output['rombel'] = $filter_table;
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
    public function list_pengetahuan($jurusan = NULL, $tingkat = NULL, $rombel = NULL){
		$loggeduser = $this->ion_auth->user()->row();
		$get_guru_login = Dataguru::find_by_user_id($loggeduser->id);
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
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
		$siswa_guru_joint = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $find_akses['id'][0];
			$data_mapel = Kurikulum::find('all', array('conditions' => "guru_id = $guru_id", 'group' => 'data_rombel_id','order'=>'data_rombel_id ASC'));
			foreach($data_mapel as $datamapel){
				$id_rombel[] = $datamapel->rombel_id;
				$id_mapel[] = $datamapel->id_mapel;
			}
			if(isset($id_rombel)){
				$id_rombel = implode(",", $id_rombel);
			} else {
				$id_rombel = 0;
			}
			if(isset($id_mapel)){
				$id_mapel = "'" . implode("','", $id_mapel) . "'";//implode(",", $id_mapel);
			} else {
				$id_mapel = 0;
			}
		$siswa_guru_joint = "AND a.id IN ($id_rombel) AND rombel_id IN ($id_rombel) AND id_mapel IN ($id_mapel)";
		}
		if($jurusan && $tingkat == NULL && $rombel == NULL){
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan $siswa_guru_joint)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
		}elseif($jurusan && $tingkat && $rombel == NULL){
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan AND a.tingkat = $tingkat $siswa_guru_joint)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
		} elseif($jurusan && $tingkat && $rombel){
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan AND a.tingkat = $tingkat AND a.id = $rombel $siswa_guru_joint)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
		} else {
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 1 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
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
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$ajaran = Ajaran::find($temp->ajaran_id);
			$rombel = Datarombel::find($temp->rombel_id);
			$mapel = Datamapel::find($temp->id_mapel);
			$get_kurikulum = Kurikulum::find_by_data_rombel_id_and_id_mapel($temp->rombel_id,$temp->id_mapel);
			if ($get_kurikulum) {
				$get_nama_guru = Dataguru::find($get_kurikulum->guru_id);
			}
			$nama_guru = isset($get_nama_guru->nama) ? $get_nama_guru->nama : '-';
			$nama_guru_login = isset($get_guru_login->nama) ? $get_guru_login->nama : '';
			$rencana_penilaian_group = Rencanapenilaian::find('all', array('conditions' => "rencana_id = $temp->id",'group' => 'nama_penilaian','order'=>'bentuk_penilaian ASC'));
			foreach($rencana_penilaian_group as $rpg){
				$rpg_id[] = $rpg->id;
			}
			if(isset($rpg_id)){
				$rpg_id_result = implode(',',$rpg_id);
			} else {
				$rpg_id_result = 0;
			}
			$nilai = Nilai::find('all', array('conditions' => "rencana_penilaian_id IN ($rpg_id_result)", 'limit'=>1));
			if(!in_array('waka',$nama_group)){ //murni guru
				if($nilai){
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				} else {
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/edit/1/'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
					$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				}
			} else { // guru plus waka
				$admin_akses = '<li><a href="'.site_url('admin/perencanaan/view/'.$temp->id).'" class="toggle-modal"><i class="fa fa-eye"></i> Detil</a></li>';
				if($nama_guru_login == $nama_guru){
					if($nilai){
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					} else {
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/edit/1'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					}
				}
			}
			$jumlah_rencana_penilaian = count($temp->rencanapenilaian);
			$record = array();
            $tombol_aktif = '';
			$record[] = $ajaran->tahun;
			$record[] = $rombel->nama;
			$record[] = $mapel->nama_mapel.' ('.$mapel->id_mapel.') ';
            $record[] = $nama_guru;
            $record[] = '<div class="text-center">'.count($rencana_penilaian_group).'</div>';
            $record[] = '<div class="text-center">'.$jumlah_rencana_penilaian.'</div>';
			//$record[] = '<div class="text-center">'.$admin_akses.'</div>';
            //$record[] = '<a class="tooltip-left" title="'.$get_kd->kompetensi_dasar.'">'.$temp->kd.'</a>';
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
                                <!--li><a href="javascript:void(0)" class="toggle-modal"><i class="fa fa-eye"></i>Detil</a></li-->
								 <!--li><a href="'.site_url('admin/perencanaan/edit/1/'.$temp->id).'"><i class="fa fa-pencil"></i>Edit</a></li-->
								 '.$admin_akses.'
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		if($jurusan && $tingkat){
			if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
				$guru_id = $find_akses['id'][0];
				$data_mapel = Kurikulum::find('all', array('conditions' => "guru_id = $guru_id", 'group' => 'data_rombel_id','order'=>'data_rombel_id ASC'));
				foreach($data_mapel as $datamapel){
					$rombel_id[] = $datamapel->rombel_id;
				}
				$get_all_rombel = Datarombel::find('all', array('conditions' => array('id IN (?) AND kurikulum_id = ? AND tingkat = ?', $jurusan, $rombel_id, $tingkat)));
			} else {
				$get_all_rombel = Datarombel::find_all_by_kurikulum_id_and_tingkat($jurusan,$tingkat);
			}
			foreach($get_all_rombel as $allrombel){
				$all_rombel= array();
				$all_rombel['value'] = $allrombel->id;
				$all_rombel['text'] = $allrombel->nama;
				$output['rombel'][] = $all_rombel;
			}
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_keterampilan($jurusan = NULL, $tingkat = NULL, $rombel = NULL){
		$loggeduser = $this->ion_auth->user()->row();
		$get_guru_login = Dataguru::find_by_user_id($loggeduser->id);
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
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
		$siswa_guru_joint = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $find_akses['id'][0];
			$data_mapel = Kurikulum::find('all', array('conditions' => "guru_id = $guru_id", 'group' => 'data_rombel_id','order'=>'data_rombel_id ASC'));
			foreach($data_mapel as $datamapel){
				$id_rombel[] = $datamapel->rombel_id;
				$id_mapel[] = $datamapel->id_mapel;
			}
			if(isset($id_rombel)){
				$id_rombel = implode(",", $id_rombel);
			} else {
				$id_rombel = 0;
			}
			if(isset($id_mapel)){
				$id_mapel = "'" . implode("','", $id_mapel) . "'";//implode(",", $id_mapel);
			} else {
				$id_mapel = 0;
			}
		$siswa_guru_joint = "AND a.id IN ($id_rombel) AND rombel_id IN ($id_rombel) AND id_mapel IN ($id_mapel)";
		}
		if($jurusan && $tingkat == NULL && $rombel == NULL){
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan $siswa_guru_joint)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
		}elseif($jurusan && $tingkat && $rombel == NULL){
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan AND a.tingkat = $tingkat $siswa_guru_joint)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
		} elseif($jurusan && $tingkat && $rombel){
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id AND a.kurikulum_id = $jurusan AND a.tingkat = $tingkat AND a.id = $rombel $siswa_guru_joint)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
		} else {
			$join = "INNER JOIN data_rombels a ON(rencanas.rombel_id = a.id)";
			$query = Rencana::find('all', array('include'=>array('rencanapenilaian'), 'conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
			$filter = Rencana::find('all', array('conditions' => "ajaran_id = $ajaran->id AND kompetensi_id = 2 $siswa_guru_joint AND (id_mapel LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'id_mapel ASC, id DESC', 'joins'=> $join));
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
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$ajaran = Ajaran::find($temp->ajaran_id);
			$rombel = Datarombel::find($temp->rombel_id);
			$mapel = Datamapel::find($temp->id_mapel);
			$get_kurikulum = Kurikulum::find_by_data_rombel_id_and_id_mapel($temp->rombel_id,$temp->id_mapel);
			if ($get_kurikulum) {
				$get_nama_guru = Dataguru::find($get_kurikulum->guru_id);
			}
			$nama_guru = isset($get_nama_guru->nama) ? $get_nama_guru->nama : '-';
			$nama_guru_login = isset($get_guru_login->nama) ? $get_guru_login->nama : '';
			$rencana_penilaian_group = Rencanapenilaian::find('all', array('conditions' => "rencana_id = $temp->id",'group' => 'nama_penilaian','order'=>'bentuk_penilaian ASC'));
			foreach($rencana_penilaian_group as $rpg){
				$rpg_id[] = $rpg->id;
			}
			if(isset($rpg_id)){
				$rpg_id_result = implode(',',$rpg_id);
			} else {
				$rpg_id_result = 0;
			}
			$nilai = Nilai::find('all', array('conditions' => "rencana_penilaian_id IN ($rpg_id_result)", 'limit'=>1));
			if(!in_array('waka',$nama_group)){ //murni guru
				if($nilai){
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				} else {
					$admin_akses = '<li><a href="'.site_url('admin/perencanaan/edit/2/'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
					$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
				}
			} else { // guru plus waka
				$admin_akses = '<li><a href="'.site_url('admin/perencanaan/view/'.$temp->id).'" class="toggle-modal"><i class="fa fa-eye"></i> Detil</a></li>';
				if($nama_guru_login == $nama_guru){
					if($nilai){
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					} else {
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/edit/2'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>';
						$admin_akses .= '<li><a href="'.site_url('admin/perencanaan/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i> Hapus</a></li>';
					}
				}
			}
			$jumlah_rencana_penilaian = count($temp->rencanapenilaian);
			$record = array();
            $tombol_aktif = '';
			$record[] = $ajaran->tahun;
			$record[] = $rombel->nama;
			$record[] = $mapel->nama_mapel.' ('.$mapel->id_mapel.') ';
            $record[] = $nama_guru;
            $record[] = '<div class="text-center">'.count($rencana_penilaian_group).'</div>';
            $record[] = '<div class="text-center">'.$jumlah_rencana_penilaian.'</div>';
			//$record[] = '<div class="text-center">'.$admin_akses.'</div>';
            //$record[] = '<a class="tooltip-left" title="'.$get_kd->kompetensi_dasar.'">'.$temp->kd.'</a>';
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
                                <!--li><a href="javascript:void(0)" class="toggle-modal"><i class="fa fa-eye"></i>Detil</a></li-->
								 <!--li><a href="'.site_url('admin/perencanaan/edit/2/'.$temp->id).'"><i class="fa fa-pencil"></i>Edit</a></li-->
								 '.$admin_akses.'
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		if($jurusan && $tingkat){
			if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
				$guru_id = $find_akses['id'][0];
				$data_mapel = Kurikulum::find('all', array('conditions' => "guru_id = $guru_id", 'group' => 'data_rombel_id','order'=>'data_rombel_id ASC'));
				foreach($data_mapel as $datamapel){
					$rombel_id[] = $datamapel->rombel_id;
				}
				$get_all_rombel = Datarombel::find('all', array('conditions' => array('id IN (?) AND kurikulum_id = ? AND tingkat = ?', $jurusan, $rombel_id, $tingkat)));
			} else {
				$get_all_rombel = Datarombel::find_all_by_kurikulum_id_and_tingkat($jurusan,$tingkat);
			}
			foreach($get_all_rombel as $allrombel){
				$all_rombel= array();
				$all_rombel['value'] = $allrombel->id;
				$all_rombel['text'] = $allrombel->nama;
				$output['rombel'][] = $all_rombel;
			}
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function list_ppk(){
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
		$guru_id = $loggeduser->guru_id;
		//$guru_id = ($guru_id) ? $guru_id->id : 0;
		$cari_rombel = $this->rombongan_belajar->find("semester_id = $ajaran->id and guru_id = $guru_id");
		//Datarombel::find_by_guru_id_and_ajaran_id($guru_id, $ajaran->id);
		$id_rombel = ($cari_rombel) ? $cari_rombel->id : 0;
		$search = "";
		$start = 0;
		$rows = 25;
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		//$query = Datappk::find('all', array('conditions' => "ajaran_id = $ajaran->id AND rombel_id = $id_rombel AND (ajaran_id LIKE '%$search%' OR rombel_id LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'rombel_id ASC, id DESC'));
		//$filter = Datappk::find('all', array('conditions' => "ajaran_id = $ajaran->id AND rombel_id = $id_rombel AND (ajaran_id LIKE '%$search%' OR rombel_id LIKE '%$search%')",'order'=>'rombel_id ASC, id DESC'));
		$search_form = "siswa_id IN(SELECT id FROM ref_siswa WHERE nama LIKE '%$search%') OR kegiatan LIKE '%$search%'";
		$query = $this->catatan_ppk->with('siswa')->find_all("semester_id =  $ajaran->id AND siswa_id IN(SELECT siswa_id FROM anggota_rombel WHERE rombongan_belajar_id = $id_rombel) AND ($search_form)", '*','id desc', $start, $rows);
		//$this->ppk->with('guru')->with('rombongan_belajar')->find_all("semester_id =  $ajaran->id AND ($search_form)", '*','id desc', $start, $rows);
		$filter = $this->catatan_ppk->with('siswa')->find_count("semester_id =  $ajaran->id AND siswa_id IN(SELECT siswa_id FROM anggota_rombel WHERE rombongan_belajar_id = $id_rombel) AND ($search_form)");
		$iFilteredTotal = count($query);
		$iTotal= $filter;
	    
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
			//$jenis_ppk = ($temp->posisi == 1) ? 'Di dalam Sekolah' : 'Di luar Sekolah';
			//$nama_kegiatan = ($temp->id_kegiatan) ? get_ekskul($temp->id_kegiatan) : $temp->nama_kegiatan;
			//$penanggung_jawab = ($temp->guru_id) ? get_nama_guru($temp->guru_id) : $temp->penanggung_jawab;
			$record = array();
			$record[] = $temp->siswa->nama;
			$record[] = $temp->capaian;
			//$record[] = $temp->waktu;
			//$record[] = $temp->penanggung_jawab;
			//$record[] = '<div class="text-center"><a href="'.site_url('admin/asesmen/edit_ppk/'.$temp->id).'" class="btn btn-primary btn-sm toggle-modal"><i class="fa fa-pencil"></i> Edit</a></a>';
			//$record[] = '<div class="text-center"><a href="'.site_url('admin/asesmen/hapus_ppk/'.$temp->id).'" class="btn btn-danger btn-sm confirm"><i class="fa fa-trash"></i> Hapus</a></a>';
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left" role="menu">
                                <!--li><a href="'.site_url('admin/asesmen/edit_ppk/'.$temp->id).'" class="toggle-modal"><i class="fa fa-eye"></i>Detil</a></li-->
								 <li><a href="'.site_url('admin/asesmen/edit_ppk/'.$temp->id).'"><i class="fa fa-pencil"></i> Edit</a></li>
								 <li><a href="'.site_url('admin/asesmen/hapus_ppk/'.$temp->id).'" class="confirm"><i class="fa fa-trash"></i> Hapus</a></li>
                            </ul>
                        </div></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
/*-----------------------------------------------------------------------------------------------------------------------
	function to upload user photos
-------------------------------------------------------------------------------------------------------------------------*/
	public function upload_photo($fieldname) {
		//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
		$config['upload_path'] = MEDIAFOLDER;
		// set the filter image types
		$config['allowed_types'] = 'png|gif|jpeg|jpg';
		//$config['max_width'] = '500'; 
		$this->load->helper('string');
		$config['file_name']	 = random_string('alnum', 32);
		//load the upload library
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
	
		//if not successful, set the error message
		if (!$this->upload->do_upload($fieldname)) {
			$data = array('success' => false, 'msg' => $this->upload->display_errors());
		} else { 
			$upload_details = $this->upload->data(); //uploading
			$data = array('success' => true, 'upload_data' => $upload_details, 'msg' => "Upload success!");
		}
		return $data;
	}
}