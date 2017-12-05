<?php
//$siswa = Datasiswa::find($siswa_id);
$ajaran = $this->semester->get($ajaran_id);
?>
<style>
.strong {font-weight:bold;}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="box box-info">
			<div class="box-body">
			<h3><?php echo $page_title; ?> Untuk Siswa <strong><?php echo get_nama_siswa($siswa_id); ?></strong> Tahun Pelajaran <?php echo $ajaran->tahun; ?> Semester <?php echo $ajaran->semester; ?></h3>
			</div>
			<div class="box-footer">
				<?php 
				$this->load->view('backend/cetak/rapor_sikap');
				$this->load->view('backend/cetak/'.$kurikulum_id.'/rapor_nilai'); 
				$this->load->view('backend/cetak/rapor_prakerin');
				$this->load->view('backend/cetak/rapor_ekskul');
				$this->load->view('backend/cetak/rapor_prestasi');
				$this->load->view('backend/cetak/rapor_absen');
				?>
			</div>
		</div>
	</div>
</div>