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
$s = Datasiswa::find($siswa_id);
$sekolah = Datasekolah::first();
$setting = Setting::first();
$rombel = Datarombel::find($rombel_id);
$ajaran = Ajaran::find($ajaran_id);
$kelompok_a = preg_quote('A0', '~'); // don't forget to quote input string!
$kelompok_b = preg_quote('B0', '~'); // don't forget to quote input string!
$kelompok_c = preg_quote('C', '~'); // don't forget to quote input string!
//$attr_mapel = array('order'=>'LEFT(id_mapel,1) ASC, MID(id_mapel,2,2) ASC'); // special thanks to Ibu Erika From Pasuruan ^_^
//$all_mapel = Kurikulum::find_all_by_ajaran_id_and_rombel_id($ajaran_id,$rombel_id,$attr_mapel);
$all_mapel = Kurikulum::find('all', array("conditions" => "ajaran_id =  $ajaran_id AND  data_rombel_id = $rombel_id AND guru_id !=0", 'order'=>'LEFT(id_mapel,1) ASC, MID(id_mapel,2,2) ASC'));
?>
<div class="strong">B.&nbsp;&nbsp;Pengetahuan dan Keterampilan</div>
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
    <?php
    foreach($all_mapel as $allmapel){
        $get_id_mapel[] = $allmapel->id_mapel;
    }
	$mapel_a = preg_grep('~' . $kelompok_a . '~', $get_id_mapel);
	$mapel_b = preg_grep('~' . $kelompok_b . '~', $get_id_mapel);
	$mapel_c = preg_grep('~' . $kelompok_c . '~', $get_id_mapel);
	$mapel_a = filter_agama_mapel($ajaran_id,$rombel_id,$get_id_mapel, $mapel_a, $s->agama);
	$mapel_group = $mapel_a + $mapel_b + $mapel_c;
	$mapel_tambahan = array_diff($get_id_mapel, $mapel_group);
    if($kurikulum_id == 2013){
        if($mapel_a){
    ?>
        <tr>
            <td colspan="10" class="strong">Kelompok A</td>
        </tr>
    <?php
        $i=1;
        foreach($mapel_a as $mapela){
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapela, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapela, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapela, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapela, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapela); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
    <?php
    $i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Kelompok A (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } 
    if($mapel_b){
	?>
		<tr>
			<td colspan="10" class="strong">Kelompok B</td>
		</tr>
	<?php
		$i=isset($i) ? $i : 1;
		foreach($mapel_b as $mapelb){
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelb, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelb, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelb, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelb, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelb); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
    <?php
    $i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Kelompok B (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } 
    if($mapel_c){
        $found_c1 = false;$found_c2 = false;$found_c3 = false;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '1') $found_c1 = true;
            if (substr($mapelc,1,1) == '2') $found_c2 = true;
            if (substr($mapelc,1,1) == '3') $found_c3 = true;
        }
    ?>
        <tr>
            <td colspan="10" class="strong">Kelompok C (Peminatan)</td>
        </tr>
        <?php if ($found_c1) { ?>
        <tr>
            <td colspan="10" class="strong">C1. Dasar Bidang Keahlian</td>
        </tr>
    <?php }
        $i=isset($i) ? $i : 1;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '1') {
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
            </tr>
            <?php
            $i++;
        } // endif c1
        } // endforeach mapelc
        if ($found_c2) {
        ?>
        <tr>
            <td colspan="10" class="strong">C2. Dasar Program Keahlian</td>
        </tr>
        <?php }
        $i=isset($i) ? $i : 1;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '2') {
				$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc, $s->id);
				$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc, $s->id);
				$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
				$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
                </tr>
                <?php
				$i++;
            } // endif c2
        } // endforeach mapelc
        if ($found_c3) {
        ?>
            <tr>
                <td colspan="10" class="strong">C3. Paket Keahlian</td>
            </tr>
        <?php
        } $i=isset($i) ? $i : 1;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '3') {
				$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc, $s->id);
				$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc, $s->id);
				$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
				$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
		</tr>
                <?php
				$i++;
            } // endif c3
        } // endforeach mapelc
        ?>
        <?php
    } else {
    ?>
        <tr>
            <td colspan="10">Kelompok C (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } ?>
	<?php
	if($mapel_tambahan){
	?>
		<tr>
			<td colspan="10" class="strong">Muatan Lokal</td>
		</tr>
	<?php
	$i=isset($i) ? $i : 1;
	foreach($mapel_tambahan as $tambahan){
		$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $tambahan, $s->id);
		$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $tambahan, $s->id);
		$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $tambahan, $s->id,1);
		$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $tambahan, $s->id,1);
	?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$tambahan); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$tambahan); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$tambahan),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$tambahan); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$tambahan),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
		</tr>
	<?php
	$i++;
	}
	} 
} elseif($all_mapel){	
    foreach($all_mapel as $allmapel){
        $get_id_mapel[] = $allmapel->id_mapel;
    }
    foreach($get_id_mapel as $abc){
        $get_id_2006[$abc] = substr($abc,0,2);
    }
    $normatif_1 = preg_quote(10, '~'); // don't forget to quote input string!
    $normatif_2 = preg_quote(20, '~'); // don't forget to quote input string!
    $normatif_3 = preg_quote(30, '~'); // don't forget to quote input string!
    $normatif_4 = preg_quote(50, '~'); // don't forget to quote input string!
    $normatif_5 = preg_quote(84, '~'); // don't forget to quote input string!
    $adaptif_1 = preg_quote(40, '~'); // don't forget to quote input string!
    $adaptif_2 = preg_quote(80, '~'); // don't forget to quote input string!
    $produktif = preg_quote('P', '~'); // don't forget to quote input string!
    $cari_mulok = preg_quote(85, '~'); // don't forget to quote input string!
    $mapel_normatif_1 = preg_grep('~' . $normatif_1 . '~', $get_id_2006);
    $mapel_normatif_2 = preg_grep('~' . $normatif_2 . '~', $get_id_2006);
    $mapel_normatif_3 = preg_grep('~' . $normatif_3 . '~', $get_id_2006);
    $mapel_normatif_4 = preg_grep('~' . $normatif_4 . '~', $get_id_2006);
    $mapel_normatif_5 = preg_grep('~' . $normatif_5 . '~', $get_id_2006);
    $mapel_adaptif_1 = preg_grep('~' . $adaptif_1 . '~', $get_id_2006);
    $mapel_adaptif_2 = preg_grep('~' . $adaptif_2 . '~', $get_id_2006);
    foreach($mapel_normatif_1 as $agama => $value){
        $mapel_agama[$agama] = get_nama_mapel($ajaran_id,$rombel_id,$agama);
    }
    if(isset($mapel_agama)){
        foreach($mapel_agama as $key=>$m_agama){
            if (strpos($m_agama,get_agama($s->agama)) !== false) {
                $mapel_normatif_1_alias[$key] = substr($key,0,2);
            }
        }
    }
    if(isset($mapel_normatif_1_alias)){
        $mapel_normatif_1 = $mapel_normatif_1_alias;
    }
    $mapel_normatif = $mapel_normatif_1 + $mapel_normatif_2 + $mapel_normatif_3 + $mapel_normatif_4 + $mapel_normatif_5;
    $mapel_adaptif = $mapel_adaptif_1 + $mapel_adaptif_2;
    $mapel_produktif1 = preg_grep('~' . $produktif . '~', $get_id_2006);
    $mapel_produktif = $mapel_produktif1;
    $all_mulok1 = preg_grep('~' . $cari_mulok . '~', $get_id_2006);
    $all_mulok = $all_mulok1;
    $mapel_group = $mapel_normatif + $mapel_adaptif + $mapel_produktif + $all_mulok;
    $mapel_tambahan = array_diff($get_id_2006, $mapel_group);
    if($mapel_tambahan){
        $mapel_adaptif = $mapel_adaptif + $mapel_tambahan;
    }
    $i=1;
    if($mapel_normatif && $mapel_adaptif && $mapel_produktif){
	if($mapel_normatif){
?>
        <tr>
            <td colspan="10">Normatif</td>
        </tr>
        <?php
        $i=1;
        foreach($mapel_normatif as $normatif => $value){
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $normatif, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $normatif, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $normatif, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $normatif, $s->id,1);
        ?>
        <tr>
            <td align="center" valign="top"><?php echo $i; ?></td>
            <td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$normatif); ?></td>
            <td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$normatif); ?></td>
            <td valign="top" align="center"><?php echo $nilai_pengetahuan_normatif_value; ?></td>
            <td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$normatif),$nilai_pengetahuan_normatif_value); ?></td>
            <td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
            <td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$normatif); ?></td>
            <td valign="top" align="center"><?php echo $nilai_keterampilan_normatif_value; ?></td>
            <td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$normatif),$nilai_keterampilan_normatif_value); ?></td>
            <td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
        <?php        
            $i++;
            }
        } else {
        ?>
        <tr>
            <td colspan="10">Normatif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
        <?php
        }
    if($mapel_adaptif){
        $a=isset($i) ? $i : 1;
    ?>
        <tr>
            <td colspan="10">Adaptif</td>
        </tr>
    <?php
        foreach($mapel_adaptif as $adaptif => $value){
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $adaptif, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $adaptif, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $adaptif, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $adaptif, $s->id,1);
    ?>
        <tr>
            <td align="center" valign="top"><?php echo $a; ?></td>
            <td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$adaptif); ?></td>
            <td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$adaptif); ?></td>
            <td valign="top" align="center"><?php echo $nilai_pengetahuan_adaptif_value; ?></td>
            <td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$adaptif),$nilai_pengetahuan_adaptif_value); ?></td>
            <td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
            <td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$adaptif); ?></td>
            <td valign="top" align="center"><?php echo $nilai_keterampilan_adaptif_value; ?></td>
            <td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$adaptif),$nilai_keterampilan_adaptif_value); ?></td>
            <td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
    <?php 
            $a++;
        }
    } else { ?>
        <tr>
            <td colspan="10" class="text-center">Mata Pelajaran Adaptif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php }
    $b=isset($a) ? $a : 1;
    if($mapel_produktif){
    ?>
        <tr>
            <td colspan="10">Produktif</td>
        </tr>
    <?php
        foreach($mapel_produktif as $produktif => $value){
            $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $produktif, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $produktif, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $produktif, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $produktif, $s->id,1);
    ?>
        <tr>
            <td align="center" valign="top"><?php echo $b; ?></td>
            <td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$produktif); ?></td>
            <td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$produktif); ?></td>
            <td valign="top" align="center"><?php echo $nilai_pengetahuan_produktif_value; ?></td>
            <td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$produktif),$nilai_pengetahuan_produktif_value); ?></td>
            <td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
            <td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$produktif); ?></td>
            <td valign="top" align="center"><?php echo $nilai_keterampilan_produktif_value; ?></td>
            <td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$produktif),$nilai_keterampilan_produktif_value); ?></td>
            <td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
            
        <?php 
        $b++;
        }
    } else { ?>
        <tr>
            <td colspan="10" class="text-center">
                Mata Pelajaran Produktif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)
            </td>
        </tr>
    <?php }
	} else {
	if($mapel_a){
    ?>
        <tr>
            <td colspan="10"><b style="font-size: 13px;">Normatif</b></td>
        </tr>
    <?php
        $i=1;
        foreach($mapel_a as $mapela){
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapela, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapela, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapela, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapela, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapela); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapela); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapela),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
    <?php
    $i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Normatif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } 
    if($mapel_b){
	$mapel_group = $mapel_a + $mapel_b + $mapel_c;
	$mapel_tambahan = array_diff($get_id_mapel, $mapel_group);
	if($mapel_tambahan){
		$mapel_b = $mapel_b + $mapel_tambahan;
	}
	?>
		<tr>
			<td colspan="10">Adaptif</td>
		</tr>
	<?php
		$i=isset($i) ? $i : 1;
		foreach($mapel_b as $mapelb){
			$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelb, $s->id);
			$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelb, $s->id);
			$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelb, $s->id,1);
			$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelb, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelb); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelb); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelb),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
        </tr>
    <?php
    $i++; }
    } else {
    ?>
        <tr>
            <td colspan="10">Adaptif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php } 
    if($mapel_c){
        $found_c1 = false;$found_c2 = false;$found_c3 = false;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '1') $found_c1 = true;
            if (substr($mapelc,1,1) == '2') $found_c2 = true;
            if (substr($mapelc,1,1) == '3') $found_c3 = true;
        }
    ?>
        <tr>
            <td colspan="10"><b style="font-size: 13px;">Produktif (Peminatan)</b></td>
        </tr>
        <?php if ($found_c1) { ?>
        <tr>
            <td colspan="10"><b>Produktif (Dasar Bidang Keahlian)</b></td>
        </tr>
    <?php }
        $i=isset($i) ? $i : 1;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '1') {
				$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc, $s->id);
				$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc, $s->id);
				$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
				$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
            </tr>
            <?php
			$i++;
        	} // endif c1
        } // endforeach mapelc
        if ($found_c2) {
        ?>
        <tr>
            <td colspan="10"><b>Produktif (Dasar Program Keahlian)</b></td>
        </tr>
        <?php }
        $i=isset($i) ? $i : 1;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '2') {
                $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc, $s->id);
				$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc, $s->id);
				$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
				$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
                </tr>
                <?php
				$i++;
			} // endif c2
        } // endforeach mapelc
        if ($found_c3) {
        ?>
            <tr>
                <td colspan="10"><b>Produktif (Paket Keahlian) <?php echo $i; ?></b></td>
            </tr>
        <?php
        } $i=isset($i) ? $i : 1;
        foreach($mapel_c as $mapelc) {
            if (substr($mapelc,1,1) == '3') {
                $nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc, $s->id);
				$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc, $s->id);
				$deskripsi_pengetahuan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
				$deskripsi_keterampilan		= get_deskripsi_nilai($ajaran_id, $rombel_id, $mapelc, $s->id,1);
		?>
		<tr>
			<td align="center" valign="top"><?php echo $i; ?></td>
			<td valign="top"><?php echo get_nama_mapel($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_pengetahuan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_pengetahuan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_pengetahuan; ?></td>
			<td valign="top" align="center"><?php echo get_kkm($ajaran_id,$rombel_id,$mapelc); ?></td>
			<td valign="top" align="center"><?php echo $nilai_keterampilan_value; ?></td>
			<td valign="top" align="center"><?php echo konversi_huruf(get_kkm($ajaran_id,$rombel_id,$mapelc),$nilai_keterampilan_value); ?></td>
			<td valign="top"><?php echo $deskripsi_keterampilan; ?></td>
                </tr>
                <?php
				$i++;
            } // endif c3
        } // endforeach mapelc
        ?>
        <?php
    } else {
    ?>
        <tr>
            <td colspan="10">Produktif (Belum ada mata pelajaran di rombongan belajar <?php echo $rombel->nama; ?>)</td>
        </tr>
    <?php }
	} 
} 
?>
    </tbody>
</table>