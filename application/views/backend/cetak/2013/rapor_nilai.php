<?php
$uri = $this->uri->segment_array();
if(isset($uri[3])){
    if($uri[3] == 'review_rapor'){
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
$mapel_a = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 1)");
$mapel_b = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 2)");
$mapel_c1 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 3)");
$mapel_c2 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 4)");
$mapel_c3 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 5)");
$mapel_tambahan = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = 99)");
?>
<div class="strong">C.&nbsp;&nbsp;Pengetahuan dan Keterampilan</div>
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
    <th align="center" style="width:150px;" class="text-center">Deskripsi</th>
    <th align="center" style="width:10px;" class="text-center">KKM</th>
    <th align="center" style="width:10px;" class="text-center">Angka</th>
    <th align="center" style="width:10px;" class="text-center">Predikat</th>
    <th align="center" style="width:150px;" class="text-center">Deskripsi</th>
  </tr>
    </thead>
    <tbody>
    	<tr>
            <td colspan="10" class="strong">Kelompok A</td>
        </tr>
    <?php
        if($mapel_a){
		$i=1;
        foreach($mapel_a as $mapela){
			//filter_agama_mapel($ajaran_id,$rombel_id,$get_id_mapel, $mapel_tambahan, $s->agama);
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapela->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapela->mata_pelajaran_id, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapela->mata_pelajaran_id, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapela->mata_pelajaran_id, $s->id,2);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapela->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
    <?php
    $i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Kelompok A (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } ?>
		<tr>
			<td colspan="10" class="strong">Kelompok B</td>
		</tr>
	<?php
		if($mapel_b){
		$i=isset($i) ? $i : 1;
		foreach($mapel_b as $mapelb){
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelb->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelb->mata_pelajaran_id, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelb->mata_pelajaran_id, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelb->mata_pelajaran_id, $s->id,2);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelb->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
    <?php
    $i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Kelompok B (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } ?>
	<tr>
			<td colspan="10" class="strong"><strong>Kelompok C (Peminatan)</strong></td>
		</tr>
        <tr>
            <td colspan="10" class="strong"><b>C1. Dasar Bidang Keahlian</b></td>
        </tr>
    <?php 
		if($mapel_c1){
		$i=isset($i) ? $i : 1;
		foreach($mapel_c1 as $mapelc1) {
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id,2);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelc1->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc1->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
            <?php
            $i++;
        }
	} else {
	?>
		<tr>
			<td colspan="10">C1. Dasar Bidang Keahlian (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } ?>
        <tr>
            <td colspan="10" class="strong"><b>C2. Dasar Program Keahlian</b></td>
        </tr>
    <?php
		if($mapel_c2){
		$i=isset($i) ? $i : 1;
		foreach($mapel_c2 as $mapelc2) {
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id,2);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelc2->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc2->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
            <?php
            $i++;
        }
	} else {
	?>
		<tr>
			<td colspan="10">C2. Dasar Program Keahlian (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php } if($rombel->tingkat != 10){?>
        <tr>
            <td colspan="10" class="strong"><b>C3. Paket Keahlian</b></td>
        </tr>
    <?php 
		if($mapel_c3){
		$i=isset($i) ? $i : 1;
		foreach($mapel_c3 as $mapelc3) {
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id,2);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($mapelc3->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc3->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
            <?php
            $i++;
        }
	} else {
	?>
		<tr>
			<td colspan="10">C3. Paket Keahlian (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
		</tr>
	<?php }
	}?>
		<tr>
			<td colspan="10" class="strong">Muatan Lokal</td>
		</tr>
	<?php
	if($mapel_tambahan){
	$i=isset($i) ? $i : 1;
	foreach($mapel_tambahan as $tambahan){
		$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $tambahan->mata_pelajaran_id, $s->id);
		$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $tambahan->mata_pelajaran_id, $s->id);
		$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $tambahan->mata_pelajaran_id, $s->id,1);
		$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $tambahan->mata_pelajaran_id, $s->id,2);
	?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($tambahan->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$tambahan->mata_pelajaran_id),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
		</tr>
	<?php
	$i++;
	}
	}
?>
    </tbody>
</table>