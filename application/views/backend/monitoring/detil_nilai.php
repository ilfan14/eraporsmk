<?php
$loggeduser = $this->ion_auth->user()->row();
$ajaran = get_ta();
$siswa = $this->siswa->get($loggeduser->siswa_id);
$anggota = $this->anggota_rombel->find("semester_id = $ajaran->id AND siswa_id = $siswa->id");
$pembelajaran = $this->pembelajaran->find_by_mata_pelajaran_id($mapel_id);
$mapel = $this->mata_pelajaran->get($mapel_id);
$data_rombel = $this->rombongan_belajar->get($anggota->rombongan_belajar_id);
$kkm = get_kkm($ajaran->id,$anggota->rombongan_belajar_id,$mapel_id);
?>
<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
	<div class="box-header">
		<i class="fa fa-hand-o-right"></i>
		<h3 class="box-title">Detil Nilai Pengetahuan Mata Pelajaran <?php echo get_nama_mapel($mapel_id); ?></h3>
	</div>
    <div class="box-body">
		<table class="table table-bordered table-hover">
			<tr>
				<th width="5%" class="text-center">ID KD</th>
				<th width="85%">Kompetensi Dasar</th>
				<th width="10%" class="text-center">Rerata Nilai</th>
			</tr>
			<?php
			$get_nilai_satu = $this->nilai->find_all("semester_id = $ajaran->id AND kompetensi_id = 1 AND rombongan_belajar_id = $anggota->rombongan_belajar_id AND mata_pelajaran_id = $mapel_id AND siswa_id = $siswa->id");
			if($get_nilai_satu){
				foreach($get_nilai_satu as $satu){
					$result_satu[$satu->kompetensi_dasar_id][] = $satu->nilai;
				}
				$jumlah_nilai_satu = 0;
				foreach($result_satu as $kd_id_satu => $nilai_satu){
					$rerata_nilai_satu = array_sum($nilai_satu) / count($nilai_satu);
					$jumlah_nilai_satu += $rerata_nilai_satu;
					$class_rerata_satu = ($rerata_nilai_satu >= $kkm) ? 'text-green' : 'text-red';
			?>
			<tr>
				<td class="text-center"><?php echo get_kompetensi_dasar($kd_id_satu, 'id'); ?></td>
				<td><?php echo get_kompetensi_dasar($kd_id_satu, 'nama'); ?></td>
				<td class="text-center <?php echo $class_rerata_satu; ?>"><strong><?php echo number_format($rerata_nilai_satu,0); ?></strong></td>
			</tr>
			<?php } 
			$rerata_akhir_satu = number_format($jumlah_nilai_satu / count($result_satu),0);
			$class_rerata_satu = ($rerata_akhir_satu >= $kkm) ? 'text-green' : 'text-red';
			?>
			<tr>
				<td colspan="2" class="text-right"><strong>Rerata Akhir = </strong></td>
				<td class="text-center <?php echo $class_rerata_satu; ?>"><strong><?php echo $rerata_akhir_satu; ?></strong></td>
			</tr>
			<?php
			} else { ?>
			<tr>
				<td colspan="3" class="text-center">Belum ada penilaian</td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<div class="box-header">
		<i class="fa fa-hand-o-right"></i>
		<h3 class="box-title">Detil Nilai Keterampilan Mata Pelajaran <?php echo get_nama_mapel($mapel_id); ?></h3>
	</div>
    <div class="box-body">
		<table class="table table-bordered table-hover">
			<tr>
				<th width="5%" class="text-center">ID KD</th>
				<th width="85%">Kompetensi Dasar</th>
				<th width="10%" class="text-center">Rerata Nilai</th>
			</tr>
			<?php
			$get_all_kd = $this->kompetensi_dasar->find_all("mata_pelajaran_id = $mapel_id AND kelas = $data_rombel->tingkat AND aspek = 'K'");
			if(!$get_all_kd){
				$get_all_kd = $this->kompetensi_dasar->find_all("mata_pelajaran_id = $mapel_id AND kelas = $data_rombel->tingkat AND aspek = 'PK'");
			}
			foreach($get_all_kd as $all_kd){
				$id_kds[] = $all_kd->id;
			}
			if(isset($id_kds)){
			$jumlah_nilai_dua = 0;
			foreach($id_kds as $id_kd){
				$all_nilai = $this->nilai->with('rencana_penilaian')->find_all("semester_id = $ajaran->id AND kompetensi_id = 2 AND siswa_id = $siswa->id AND kompetensi_dasar_id = $id_kd");
				if($all_nilai){
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
					$class_rerata_dua = ($n_s_final >= $kkm) ? 'text-green' : 'text-red';
					$jumlah_nilai_dua += $n_s_final;
			?>
			<tr>
				<td class="text-center"><?php echo get_kompetensi_dasar($id_kd, 'id'); ?></td>
				<td><?php echo get_kompetensi_dasar($id_kd, 'nama'); ?></td>
				<td class="text-center <?php echo $class_rerata_dua; ?>"><strong><?php echo number_format($n_s_final,0); ?></strong></td>
			</tr>
			<?php } ?>
			<?php } ?>
			<?php
			$rerata_akhir_dua = number_format($jumlah_nilai_dua / count($nilai_siswa),0);
			$class_rerata_dua = ($rerata_akhir_dua >= $kkm) ? 'text-green' : 'text-red';
			?>
			<tr>
				<td colspan="2" class="text-right"><strong>Rerata Akhir = </strong></td>
				<td class="text-center <?php echo $class_rerata_dua; ?>"><strong><?php echo $rerata_akhir_dua; ?></strong></td>
			</tr>
			<?php } else { ?>
				<tr>
					<td colspan="3" class="text-center">Belum ada penilaian</td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	</div>
</div><!-- /.box-body -->
</div><!-- /.box -->
</div>