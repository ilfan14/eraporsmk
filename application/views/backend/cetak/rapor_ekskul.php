<?php
$uri = $this->uri->segment_array();
if(isset($uri[3])){
	if($uri[3] == 'review_rapor'){
		$atribute = ' class="table table-bordered"';
	} else {
		$atribute = ' border="1" class="table"';
	}
}
$s = $this->siswa->get($siswa_id);
$sekolah = $this->sekolah->get(1);
$setting = $this->settings->get(1);
$rombel = $this->rombongan_belajar->get($rombel_id);
$ajaran = $this->semester->get($ajaran_id);
?>
<div class="strong">E.&nbsp;&nbsp;Ekstrakurikuler</div>
<table<?php echo $atribute; ?>>
	<thead>
		<tr>
			<th style="width: 5%;" align="center">No</th>
			<th style="width: 35%;" align="center">Kegiatan Ekstrakurikuler</th>
			<th style="width: 60%;" align="center">Keterangan</th>
		</tr>
	</thead>
	<tbody>
	<?php $ekskul= $this->nilai_ekstrakurikuler->find_all("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND siswa_id = $siswa_id");
	//Nilaiekskul::find_all_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$siswa_id);
	if($ekskul){
		$i=1;
		foreach($ekskul as $eks){
			if($eks->deskripsi_ekskul){
		?>
		<tr>
			<td align="center"><?php echo $i; ?></td>
			<td><?php echo get_ekskul($eks->ekstrakurikuler_id); ?></td>
			<td><?php echo $eks->deskripsi_ekskul; ?></td>
		</tr>
		<?php
		$i++;
		}
		}
	} else {
	?>
		<tr>
			<td colspan="3" align="center">Belum ada data untuk ditampilkan</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>