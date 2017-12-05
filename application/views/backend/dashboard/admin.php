<?php 
$sekolah = $this->sekolah->get($user->sekolah_id);
?>
<div class="row">
<div class="col-lg-6 col-xs-12">
	<table class="table table-condensed">
		<tr>
			<td colspan="2" class="text-center" style="border-top:none;"><strong>Identitas Sekolah</strong></td>
		</tr>
		<tr>
			<td width="30%">Nama Sekolah</td>
			<td width="70%">: <?php echo ($sekolah) ? $sekolah->nama : '-'; ?></td>
		</tr>
		<tr>
			<td>NPSN</td>
			<td>: <?php echo ($sekolah) ? $sekolah->npsn : '-'; ?></td>
		</tr>
		<tr>
			<td>Desa/Kelurahan</td>
			<td>: <?php echo ($sekolah) ? $sekolah->desa_kelurahan : '-'; ?></td>
		</tr>
		<tr>
			<td>Kecamatan</td>
			<td>: <?php echo ($sekolah) ? $sekolah->kecamatan : '-'; ?></td>
		</tr>
		<tr>
			<td>Kabupaten/Kota</td>
			<td>: <?php echo ($sekolah) ? $sekolah->kabupaten : '-'; ?></td>
		</tr>
		<tr>
			<td>Provinsi</td>
			<td>: <?php echo ($sekolah) ? $sekolah->provinsi : '-'; ?></td>
		</tr>
		<tr>
			<td>Kepala Sekolah</td>
			<td>: <?php echo ($sekolah) ? get_nama_guru($sekolah->guru_id) : '-'; ?></td>
		</tr>
	</table>
</div>
<div class="col-lg-6 col-xs-12">
	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-green disabled color-palette">
			<div class="inner">
				<h3><?php echo $guru; ?></h3>
				<p>Guru</p>
			</div>
			<div class="icon"><i class="ion ion-person-add"></i></div>
			<a href="<?php echo site_url('admin/data_guru'); ?>" class="small-box-footer">
				Selengkapnya <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div><!-- ./col -->
	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-yellow disabled color-palette">
			<div class="inner">
				<h3><?php echo $siswa; ?></h3>
				<p>Siswa</p>
			</div>
			<div class="icon"><i class="ion ion-android-contacts"></i></div>
			<a href="<?php echo site_url('admin/data_siswa'); ?>" class="small-box-footer">
				Selengkapnya <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div><!-- ./col -->
	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-red disabled color-palette">
			<div class="inner">
				<h3><?php echo $rencana_penilaian; ?></h3>
				<p>Rencana Penilaian (P&amp;K)</p>
			</div>
			<div class="icon"><i class="ion ion-android-checkbox-outline"></i></div>
		</div>
	</div><!-- ./col -->
	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-maroon disabled color-palette">
			<div class="inner">
				<h3><?php echo $nilai; ?></h3>
				<p>Penilaian Per KD (P&amp;K)</p>
			</div>
			<div class="icon"><i class="ion ion-arrow-graph-up-right"></i></div>
		</div>
	</div><!-- ./col -->
</div>
</div>
<!--div class="col-lg-12 col-xs-12">
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Bar Chart</h3>
		</div>
		<div class="box-body">
			<div class="chart">
				<canvas id="barChart" style="height:230px"></canvas>
			</div>
		</div>
	</div>
</div-->