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
<div class="strong">F.&nbsp;&nbsp;Prestasi</div>
<table<?php echo $atribute; ?>>
	<thead>
		<tr>
			<th style="width: 5%;" align="center">No</th>
			<th style="width: 35%;" align="center">Jenis Prestasi</th>
			<th style="width: 60%;" align="center">Keterangan</th>
		</tr>
	</thead>
	<tbody>
	<?php $data= $this->prestasi->find_all("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND siswa_id = $siswa_id");
	//Prestasi::find_all_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$siswa_id);
	if($data){
		$i=1;
		foreach($data as $d){
		?>
		<tr>
			<td align="center"><?php echo $i; ?></td>
			<td><?php echo $d->jenis_prestasi; ?></td>
			<td><?php echo $d->keterangan_prestasi; ?></td>
		</tr>
		<?php
		$i++;
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