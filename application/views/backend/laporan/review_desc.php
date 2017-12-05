<?php
$siswa = $this->siswa->get($siswa_id);
$ajaran = $this->semester->get($ajaran_id);
?>
<style>
.strong {font-weight:bold;}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="box box-info">
			<div class="box-body">
			<h3><?php echo $page_title; ?> Untuk Siswa <strong><?php echo $siswa->nama; ?></strong> Tahun Pelajaran <?php echo $ajaran->tahun; ?> Semester <?php echo $ajaran->semester; ?></h3>
			</div>
			<div class="box-footer">
				<?php 
				$this->load->view('backend/cetak/'.$kurikulum.'/rapor_nilai_desc'); 
				?>
			</div>
		</div>
	</div>
</div>