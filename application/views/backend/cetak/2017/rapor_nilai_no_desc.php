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
$mapel_a = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 6)");
$mapel_b = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 7)");
$mapel_c1 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 8)");
$mapel_c2 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 9)");
$mapel_c3 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 10)");
$mapel_tambahan = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 99)");
$nama_kelompok_a = $this->kelompok->get(6);
$nama_kelompok_b = $this->kelompok->get(7);
$nama_kelompok_c1 = $this->kelompok->get(8);
$nama_kelompok_c2 = $this->kelompok->get(9);
$nama_kelompok_c3 = $this->kelompok->get(10);
?>
<table <?php echo $border; ?> class="<?php echo $class; ?>">
	<thead>
  <tr>
    <th style="vertical-align:middle;width: 2px;" align="center" rowspan="2">No</th>
    <th style="vertical-align:middle;width: 200px;" rowspan="2">Mata Pelajaran</th>
    <th colspan="3" align="center" class="text-center">Pengetahuan</th>
	<th colspan="3" align="center" class="text-center">Keterampilan</th>
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
			<td colspan="10"><b style="font-size: 13px;"><?php echo $nama_kelompok_a->nama_kelompok; ?></b></td>
		</tr>
<?php
	$i=1;
	if($mapel_a){
	?>
		
<?php
	foreach($mapel_a as $mapela){
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
			<td colspan="10"><?php echo $nama_kelompok_a->nama_kelompok; ?> (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } 
	?>
		<tr>
			<td colspan="10"><b style="font-size: 13px;"><?php echo $nama_kelompok_b->nama_kelompok; ?></b></td>
		</tr>
	<?php
	$i=isset($i) ? $i : 1;
	if($mapel_b){
	foreach($mapel_b as $mapelb){
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
			<td colspan="10"><?php echo $nama_kelompok_b->nama_kelompok; ?> (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } ?>
        <tr>
            <td colspan="10"><b style="font-size: 13px;">Kelompok C (Peminatan)</b></td>
        </tr>
		<tr>
            <td colspan="10"><b><?php echo trim(str_replace('Kelompok','',$nama_kelompok_c1->nama_kelompok)); ?></b></td>
        </tr>
	<?php if($mapel_c1){ ?>
    <?php 
		$i=isset($i) ? $i : 1;
		foreach($mapel_c1 as $mapelc1) {
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelc1->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
            </tr>
            <?php
            $i++;
        }
	} else {
	?>
		<tr>
			<td colspan="10"><?php echo trim(str_replace('Kelompok','',$nama_kelompok_c1->nama_kelompok)); ?> (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } ?>
		<tr>
            <td colspan="10"><b><?php echo trim(str_replace('Kelompok','',$nama_kelompok_c2->nama_kelompok)); ?></b></td>
        </tr>
	<?php if($mapel_c2){ ?>
    <?php 
		$i=isset($i) ? $i : 1;
        foreach($mapel_c2 as $mapelc2) {
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelc2->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
            </tr>
            <?php
            $i++;
        }
	} else {
	?>
		<tr>
			<td colspan="10"><?php echo trim(str_replace('Kelompok','',$nama_kelompok_c2->nama_kelompok)); ?> (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } if($rombel->tingkat != 10){?>
        <tr>
            <td colspan="10"><b><?php echo trim(str_replace('Kelompok','',$nama_kelompok_c3->nama_kelompok)); ?></b></td>
        </tr>
    <?php 
		if($mapel_c3){
		$i=isset($i) ? $i : 1;
        foreach($mapel_c3 as $mapelc3) {
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelc3->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
            </tr>
            <?php
            $i++;
        }
	} else {
	?>
		<tr>
			<td colspan="10"><?php echo trim(str_replace('Kelompok','',$nama_kelompok_c3->nama_kelompok)); ?> (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } 
	}?>
		<tr>
			<td colspan="10"><b style="font-size: 13px;">Muatan Lokal</b></td>
		</tr>
	<?php
	if($mapel_tambahan){
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
	} else {
	?>
		<tr>
			<td colspan="10">Muatan Lokal (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } ?>
	</tbody>
</table>