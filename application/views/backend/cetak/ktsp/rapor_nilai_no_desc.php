<?php
$uri = $this->uri->segment_array();
if(isset($uri[3])){
	if($uri[3] == 'review_nilai'){
		$border = '';
		$class = 'table table-bordered';
	} else {
		$border = 'border="1"';
		$class = 'table';
	}
}
$s = $this->siswa->get($siswa_id);
$sekolah = $this->sekolah->get(1);
$setting = $this->settings->get(1);
$rombel = $this->rombongan_belajar->get($rombel_id);
$ajaran = $this->semester->get($ajaran_id);
/*$sel = 'kurikulums.*, a.id_mapel_nas AS id_mapel_nas';
$join = "JOIN data_mapels a ON(kurikulums.id_mapel = a.id_mapel)";
$all_mapel = Kurikulum::find('all', array("conditions" => "ajaran_id =  $ajaran_id AND  data_rombel_id = $rombel_id AND guru_id !=0", 'order'=>'LEFT(kurikulums.id_mapel,1) ASC, MID(kurikulums.id_mapel,2,2) ASC', 'joins'=> $join, 'select' => $sel));
$c=1;*/
$mapel_normatif = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 11)");
$mapel_adaptif = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 12)");
$mapel_produktif = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 13)");
/*$mapel_produktif_1 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 16)");
$mapel_produktif_2 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 17)");
$mapel_produktif_3 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 18)");*/
$mapel_tambahan = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 99)");
?>
<table <?php echo $border; ?> class="<?php echo $class; ?>">
	<thead>
  <tr>
    <th style="vertical-align:middle;width: 2px;" align="center" rowspan="2">No</th>
    <th style="vertical-align:middle;width: 200px;" rowspan="2" align="center" class="text-center">Mata Pelajaran</th>
    <th colspan="4" align="center" class="text-center">Pengetahuan</th>
    <th colspan="4" align="center" class="text-center">Keterampilan</th>
  </tr>
  <tr>
    <th align="center" style="width:10px;" class="text-center">KKM</th>
    <th align="center" style="width:10px;" class="text-center">Angka</th>
    <th align="center" style="width:10px;" class="text-center">Predikat</th>
    <th align="center" style="width:10px;" class="text-center">KKM</th>
    <th align="center" style="width:10px;" class="text-center">Angka</th>
    <th align="center" style="width:10px;" class="text-center">Predikat</th>
  </tr>
	</thead>
	<tbody>
        <tr>
            <td colspan="10" class="strong"><b style="font-size: 13px;">I NORMATIF</b></td>
        </tr>
    <?php
        $i=1;
		if($mapel_normatif){
			foreach($mapel_normatif as $mapela){
				$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapela->mata_pelajaran_id, $s->id);
				$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapela->mata_pelajaran_id, $s->id);
			?>
			<tr>
				<td align="center" valign="top"><?php echo $i; ?></td>
				<td valign="top"><?php echo get_nama_mapel($mapela->mata_pelajaran_id); ?></td>
				<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id); ?></td>
				<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
				<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
				<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id); ?></td>
				<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
				<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
			</tr>
		<?php
		$i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Normatif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } ?>
		<tr>
			<td colspan="10" class="strong"><b style="font-size: 13px;">II ADAPTIF</b></td>
		</tr>
	<?php
    if($mapel_adaptif){
		$i=isset($i) ? $i : 1;
		foreach($mapel_adaptif as $mapelb){
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelb->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelb->mata_pelajaran_id, $s->id);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelb->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
        </tr>
    <?php
    	$i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Adaptif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } ?>
	<tr>
		<td colspan="10" class="strong"><b style="font-size: 13px;">III PRODUKTIF</b></td>
	</tr>
	<tr>
		<td colspan="10"><b>Produktif (Dasar Bidang Keahlian)</b></td>
	</tr>
	<?php
    if($mapel_produktif){
        $i=isset($i) ? $i : 1;
        foreach($mapel_produktif as $produktif) {
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $produktif->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $produktif->mata_pelajaran_id, $s->id);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo ucfirst(strtolower(get_nama_mapel($produktif->mata_pelajaran_id))); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$produktif->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$produktif->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$produktif->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$produktif->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
            </tr>
            <?php
			$i++;
        } // endforeach mapelc
	} else {
	?>
		<tr>
            <td colspan="10">Produktif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
	<?php }
	if($mapel_tambahan){
	?>
		<tr>
			<td colspan="10" class="strong"><b style="font-size: 13px;">Muatan Lokal</b></td>
		</tr>
	<?php
	$i=isset($i) ? $i : 1;
	foreach($mapel_tambahan as $tambahan){
		$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $tambahan->mata_pelajaran_id, $s->id);
		$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $tambahan->mata_pelajaran_id, $s->id);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($tambahan->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
		</tr>
	<?php
	$i++;
	}
	}
	?>
	</tbody>
</table>