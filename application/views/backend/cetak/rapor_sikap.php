<?php
$uri = $this->uri->segment_array();
if(isset($uri[3])){
	if($uri[3] == 'review_rapor'){
		$atribute = ' class="table table-bordered"';
		$atribute_2 = ' class="table table-bordered"';
	} else {
		$atribute = ' border="0" width="100%"';
		$atribute_2 = ' width="100%" border="1" style="margin-left:20px;"';
	}
}
$sekolah = $this->sekolah->get(1);
$setting = $this->settings->get(1);
$s = $this->siswa->get($siswa_id);
$rombel = $this->rombongan_belajar->get($rombel_id);
$ajaran = $this->semester->get($ajaran_id);
?>
<table<?php echo $atribute; ?>>
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nama Sekolah</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 35%"><?php echo $sekolah->nama; ?></td>
	<td style="width: 20%;">Kelas</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 25%"><?php echo $rombel->nama; ?></td>
  </tr>
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Alamat</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 35%"><?php echo $sekolah->alamat; ?></td>
	<td style="width: 20%;">Semester</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 25%"><?php echo ($ajaran->semester == 1) ? '1 (Satu)' : '2 (Dua)'; ?></td>
  </tr>
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nama Siswa</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 35%"><?php echo $s->nama; ?></td>
	<td style="width: 20%;">Tahun Pelajaran</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 25%"><?php echo $ajaran->tahun; ?></td>
  </tr>
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nomor Induk/NISN</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 35%"><?php echo $s->no_induk.' / '.$s->nisn; ?></td>
	<td style="width: 20%;">&nbsp;</td>
    <td style="width: 1%;" class="text-center">&nbsp;</td>
    <td style="width: 25%">&nbsp;</td>
  </tr>
</table><br>
<h4>CAPAIAN HASIL BELAJAR</h4>
<div class="strong">A.&nbsp;&nbsp;Sikap Spiritual</div>
<?php
$deskripsi_sikap = $this->deskripsi_sikap->find("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND siswa_id = $s->id");
//Deskripsisikap::find_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran_id,$rombel_id,$s->id);
if($deskripsi_sikap){
	$uraian_deskripsi_spiritual = $deskripsi_sikap->uraian_deskripsi_spiritual;
	$uraian_deskripsi_sosial 	= $deskripsi_sikap->uraian_deskripsi_sosial;
} else {
	$uraian_deskripsi_spiritual = '';
	$uraian_deskripsi_sosial 	= '';
}
?>
<table <?php echo $atribute_2; ?>>
  <tr>
    <td style="padding:10px 10px 40px 10px;">Deskripsi:<br /><br /><?php echo $uraian_deskripsi_spiritual; ?>
	</td>
  </tr>
</table>
<div class="strong" style="margin-top:20px;">B.&nbsp;&nbsp;Sikap Sosial</div>
<table <?php echo $atribute_2; ?>>
  <tr>
    <td style="padding:10px 10px 40px 10px;">Deskripsi:<br /><br /><?php echo $uraian_deskripsi_sosial; ?>
	</td>
  </tr>
</table>