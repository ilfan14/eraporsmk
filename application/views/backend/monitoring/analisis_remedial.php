<?php
$html = '';
		$settings 	= $this->settings->get(1);
		//$data_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id);
		$data_siswa = get_siswa_by_rombel($rombel_id);
		$get_all_kd = $this->kompetensi_dasar->find_all("mata_pelajaran_id = $id_mapel AND kelas = $kelas AND aspek ='$aspek'");
		//Kd::find_all_by_id_mapel_and_kelas_and_aspek($id_mapel, $kelas, $aspek);
		if(!$get_all_kd){
			$get_all_kd = $this->kompetensi_dasar->find_all("mata_pelajaran_id = $id_mapel AND kelas = $kelas AND aspek ='PK'");
			//Kd::find_all_by_id_mapel_and_kelas_and_aspek($id_mapel, $kelas, 'PK');
		}
		$count_get_all_kd = count($get_all_kd);
		$kkm = get_kkm($ajaran_id, $rombel_id, $id_mapel);
		$html .= '<div class="table-responsive no-padding">';
		$html .= '<table class="table table-bordered table-hover">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th rowspan="3" style="vertical-align: middle;">Nama Siswa</th>';
		$html .= '<th class="text-center" colspan="'.( $count_get_all_kd * 2 ).'">Kompetensi Dasar</th>';
		$html .= '<th rowspan="3" style="vertical-align: middle;" class="text-center">Rerata Akhir</th>';
		$html .= '<th rowspan="3" style="vertical-align: middle;" class="text-center">Rerata Remedial</th>';
		$html .= '</tr>';
		$html .= '<tr>';
		$get_all_kd_finish = count($get_all_kd);
		foreach($get_all_kd as $all_kd){
			//$kd = Kd::find_by_id($allpengetahuan->kd_id);
			$id_kd = $all_kd->id_kompetensi;
			$id_kds[] = $all_kd->id;
			$html .= '<th colspan="2" class="text-center"><a href="javacript:void(0)" class="tooltip-left" title="'.$all_kd->kompetensi_dasar.'">&nbsp;&nbsp;&nbsp;'.$id_kd.'&nbsp;&nbsp;&nbsp;</a></th>';
		}
		$html .= '</tr>';
		$html .= '<tr>';
		foreach($get_all_kd as $all_kd){
			$html .= '<th class="text-center bg-gray disabled color-palette">A</th>';
			$html .= '<th class="text-center">R</th>';
		}
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		$no=0;
		$rencana_penilaian = $this->rencana_penilaian->find_all("semester_id = $ajaran_id and kompetensi_id = $kompetensi_id and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel");
		foreach($data_siswa as $siswa){
			$siswa_id = $siswa->siswa->id;
			$html .= '<tr>';
			$html .= '<td>';
			$html .= $siswa->siswa->nama;
			$html .= '</td>';
			$nilai_rerata_asli = 0;
			$nilai_rerata_remedial = 0;
			foreach($get_all_kd as $key=>$all_kd){
				$nilai_asli = get_nilai_siswa_by_kd($all_kd->id, $ajaran_id, $kompetensi_id, $rombel_id, $id_mapel, $siswa_id, 'asli');
				$nilai_remedial = get_nilai_siswa_by_kd($all_kd->id, $ajaran_id, $kompetensi_id, $rombel_id, $id_mapel, $siswa_id, 'remedial');
				$nilai_remedial = ($nilai_remedial) ? $nilai_remedial[$key] : 0;
				$html .= '<td class="text-center"><strong>';
				$html .= $nilai_asli;
				$html .= '</strong></td>';
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$html .= $nilai_remedial;
				$html .= '</td>';
				$nilai_rerata_asli += $nilai_asli;
				$nilai_rerata_remedial += $nilai_remedial;
			}
			$rerata_akhir = number_format($nilai_rerata_asli / count($get_all_kd) ,0);
			$rerata_remedial = number_format($nilai_rerata_remedial / count($get_all_kd) ,0);
			if($kkm > $rerata_akhir){
				$bg_nilai_asli = ' text-red';
			} else {
				$bg_nilai_asli = ' text-green';
			}
			if($kkm > $rerata_remedial){
				$bg_nilai_remedial = ' text-red';
			} else {
				$bg_nilai_remedial = ' text-green';
			}
			$html .= '<td class="text-center bg-gray disabled color-palette'.$bg_nilai_asli.'"><strong>';
			$html .= $rerata_akhir;
			$html .= '</strong></td>';
			$html .= '<td class="text-center bg-gray disabled color-palette'.$bg_nilai_remedial.'"><strong>';
			$html .= $rerata_remedial;
			$html .= '</strong></td>';
			/*if($remedial){
				$nilai_asli = get_nilai_siswa_by_kd($ajaran_id, $kompetensi_id, $rombel_id, $id_mapel, $siswa_id);
				if($kkm > $nilai_asli){
					$bg_nilai_asli = ' text-red';
				} else {
					$bg_nilai_asli = '';
				}
				$html .= '<td class="text-center bg-gray disabled color-palette'.$bg_nilai_asli.'">';
				$html .= $nilai_asli;
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$html .= $remedial->nilai;
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$html .= '-';
				$html .= '</td>';
				$html .= '<td class="text-center"><strong>';
				$html .= $remedial->rerata_akhir;
				$html .= '</strong></td>';
				$html .= '<td class="text-center"><strong>';
				$html .= $remedial->rerata_remedial;
				$html .= '</strong></td>';
			} else {
				
				$html .= '<td class="text-center" colspan="'.( $count_get_all_kd * 2 ).'">';
				$html .= 'Nilai tidak ditemukan';
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$html .= '-';
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$html .= '-';
				$html .= '</td>';
			}*/
			$html .= '</tr>';
		}
		$html .= '<input type="hidden" id="get_all_kd" value="'.$get_all_kd_finish.'" />';
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';
		$html .= link_tag('assets/css/tooltip-viewport.css', 'stylesheet', 'text/css');
		$html .= '<script src="'.base_url().'assets/js/tooltip-viewport.js"></script>';
		$html .= '<script src="'.base_url().'assets/js/remedial.js"></script>';
		echo $html;
?>