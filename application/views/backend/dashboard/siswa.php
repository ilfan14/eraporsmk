<?php
$loggeduser = $this->ion_auth->user()->row();
$ajaran = get_ta();
$siswa = $this->siswa->get($loggeduser->siswa_id);
$anggota = $this->anggota_rombel->find("semester_id = $ajaran->id AND siswa_id = $siswa->id");
if($anggota){
$rombel = $this->rombongan_belajar->get($anggota->rombongan_belajar_id);
$wali = get_nama_guru($rombel->guru_id);
$mata_pelajaran = $this->pembelajaran->find_all("rombongan_belajar_id = $rombel->id AND semester_id = $ajaran->id");
$mapel_agama = array();
foreach($mata_pelajaran as $allmapel){
	//echo get_nama_mapel($allmapel->mata_pelajaran_id).'('.$allmapel->mata_pelajaran_id.')<br />';
	$get_id_mapel[] = $allmapel->mata_pelajaran_id;
}
if(isset($get_id_mapel)){
	$mata_pelajaran = $get_id_mapel;//filter_agama_mapel($ajaran->id, $get_id_mapel, $get_id_mapel, $siswa->agama);
}
?>
<h4 class="box-title">Anda sedang berada di Rombongan Belajar <span class="btn btn-success"><?php echo (isset($rombel)) ? $rombel->nama: ''; ?></span> Wali Kelas <span class="btn btn-success"><?php echo $wali; ?></span></h4>
<section id="mata-pelajaran">
	<h4 class="page-header">Daftar Mata Pelajaran</h4>
	<div class="row">
		<div class="col-lg-12 col-xs-12" style="margin-bottom:20px;">
			<table class="table table-bordered table-striped table-hover datatable">
				<thead>
					<tr>
						<th style="width: 10px; vertical-align:middle;" class="text-center" rowspan="2">#</th>
						<th style="vertical-align:middle;" rowspan="2">Mata Pelajaran</th>
						<th style="vertical-align:middle;" rowspan="2">Guru Mata Pelajaran</th>
						<th class="text-center" colspan="2">Nilai Pengetahuan</th>
						<th class="text-center" colspan="2">Nilai Keterampilan</th>
						<th style="vertical-align:middle;" class="text-center" rowspan="2">Detil Nilai</th>
					</tr>
					<tr>
						<td class="text-center">Angka</td>
						<td class="text-center">Predikat</td>
						<td class="text-center">Angka</td>
						<td class="text-center">Predikat</td>
					</tr>
				</thead>
				<?php 
				if(isset($mata_pelajaran)){
					$count = 1;
					foreach ($mata_pelajaran as $mapel) {
						$nilai_pengetahuan_value	= get_nilai_siswa($ajaran->id, 1, $anggota->rombongan_belajar_id, $mapel, $siswa->id);
						$nilai_keterampilan_value	= get_nilai_siswa($ajaran->id, 2, $anggota->rombongan_belajar_id, $mapel, $siswa->id);
						$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran->id, $anggota->rombongan_belajar_id, $mapel, $siswa->id,1);
						$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran->id, $anggota->rombongan_belajar_id, $mapel, $siswa->id,2);
				?>
					<tr>
						<td class="text-center"><?php echo $count; ?></td> 
						<td><?php echo get_nama_mapel($mapel); ?></td>  
						<td><?php echo get_guru_mapel($ajaran->id, $anggota->rombongan_belajar_id, $mapel, 'nama'); ?></td>  
						<td class="text-center" class="text-center"><?php echo $nilai_pengetahuan_value; ?></td>
						<td class="text-center" class="text-center"><?php echo konversi_huruf(get_kkm($ajaran->id, $anggota->rombongan_belajar_id,$mapel),$nilai_pengetahuan_value); ?></td>
						<td class="text-center" class="text-center"><?php echo $nilai_keterampilan_value; ?></td>
						<td class="text-center" class="text-center"><?php echo konversi_huruf(get_kkm($ajaran->id, $anggota->rombongan_belajar_id,$mapel), $nilai_keterampilan_value); ?></td> 
						<td class="text-center" class="text-center">
							<a href="<?php echo site_url('admin/monitoring/prestasi/'.$mapel); ?>" class="btn btn-block btn-xs btn-success">Detil Nilai</a>
						</td>
					</tr>
				<?php
						$count++;
						//die();
					}
				} ?>
			</table>
		</div>
	</div>
</section>
<?php } else { ?>
<h4 class="box-title">Rombongan belajar belum di update!</h4>
<?php } ?>