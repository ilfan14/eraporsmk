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
			$nilai_pengetahuan = $this->nilai->find_all("semester_id = $ajaran->id AND kompetensi_id = 1 AND rombongan_belajar_id = $anggota->rombongan_belajar_id AND mata_pelajaran_id = $mapel_id AND siswa_id = $siswa->id");
			if($nilai_pengetahuan){
				$set_rerata_nilai = 0;
				$set_rerata_akhir = 0;
				foreach($nilai_pengetahuan as $np){
					$set_rerata_nilai = 0;
					$set_rerata_akhir += $np->nilai;
					//$set_rerata_akhir = number_format($rerata_akhir / $jumlah_penilaian,0);
					$class_rerata_nilai = ($set_rerata_nilai >= $kkm) ? 'text-green' : 'text-red';
					$class_rerata_akhir = ($set_rerata_akhir >= $kkm) ? 'text-green' : 'text-red';
			?>
			<tr>
				<td class="text-center"><?php echo get_kompetensi_dasar($np->kompetensi_dasar_id, 'id'); ?></td>
				<td><?php echo get_kompetensi_dasar($np->kompetensi_dasar_id, 'nama'); ?></td>
				<td class="text-center <?php echo $class_rerata_nilai; ?>"><strong><?php echo $np->nilai; ?></strong></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="2" class="text-right"><strong>Rerata Akhir = </strong></td>
				<td class="text-center <?php echo $class_rerata_akhir; ?>"><strong><?php echo number_format($set_rerata_akhir / count($nilai_pengetahuan),0); ?></strong></td>
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
			$nilai_keterampilan = $this->nilai->find_all("semester_id = $ajaran->id AND kompetensi_id = 2 AND rombongan_belajar_id = $anggota->rombongan_belajar_id AND mata_pelajaran_id = $mapel_id AND siswa_id = $siswa->id");
			if($nilai_keterampilan){
				$set_rerata_nilai = 0;
				$set_rerata_akhir = 0;
				foreach($nilai_keterampilan as $nk){
					$set_rerata_nilai = 0;
					$set_rerata_akhir += $nk->nilai;
					//$set_rerata_akhir = number_format($rerata_akhir / $jumlah_penilaian,0);
					$class_rerata_nilai = ($set_rerata_nilai >= $kkm) ? 'text-green' : 'text-red';
					$class_rerata_akhir = ($set_rerata_akhir >= $kkm) ? 'text-green' : 'text-red';
			?>
			<tr>
				<td class="text-center"><?php echo get_kompetensi_dasar($nk->kompetensi_dasar_id, 'id'); ?></td>
				<td><?php echo get_kompetensi_dasar($nk->kompetensi_dasar_id, 'nama'); ?></td>
				<td class="text-center <?php echo $class_rerata_nilai; ?>"><strong><?php echo $nk->nilai; ?></strong></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="2" class="text-right"><strong>Rerata Akhir = </strong></td>
				<td class="text-center <?php echo $class_rerata_akhir; ?>"><strong><?php echo number_format($set_rerata_akhir / count($nilai_keterampilan),0); ?></strong></td>
			</tr>
			<?php
			} else { ?>
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