<?php
//$data_ppk = $this->ppk->find_all("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND posisi = 1");
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
$sekolah = $this->sekolah->get($sekolah_id);
$s = $this->siswa->get($siswa_id);
$rombel = $this->rombongan_belajar->get($rombel_id);
$ajaran = $this->semester->get($ajaran_id);
$catatan_ppk = $this->catatan_ppk->with('ppk')->find("semester_id = $ajaran_id AND siswa_id = $siswa_id");
$semester = 'GANJIL';
if($ajaran->semester == 2){
	$semester = 'GENAP';
}
?>
<style>
#font_kecil{font-size:80% !important;}
body{font-family:tahoma;}
table {font-size:12px;}
strong, b{font-weight:bold !important;}
</style>
<table<?php echo $atribute; ?> style="margin-bottom:10px;">
	<tr>
		<td width="15%" style="text-align:left;"><img src="<?php echo base_url().'assets/img/logo.png'; ?>" width="100" align="left" style="float:left;" /></td>
		<td width="70%" style="text-align:center;"><h4>LAPORAN</h4> 
<h4>PERKEMBANGAN KARAKTER SISWA SEMESTER <?php echo $semester; ?></h4>
<h4>TAHUN PELAJARAN <?php echo $ajaran->tahun; ?></h4>
<h4><?php echo strtoupper($sekolah->nama); ?></h4></td>
		<td width="15%" style="text-align:right;"><?php if($sekolah->logo_sekolah){?><img src="<?php echo base_url().PROFILEPHOTOSTHUMBS.$sekolah->logo_sekolah; ?>" width="100"  align="right" style="float:right;" /><?php } ?></td>
	</tr>
</table>
<strong><?php echo $s->nama; ?></strong><br />
<strong><?php echo $s->nisn; ?></strong>
<div style="clear:both; margin-bottom:10px;"></div>
<?php
if($catatan_ppk){
?>
	<img src="<?php echo ($catatan_ppk->foto_1) ? base_url().MEDIAFOLDER.$catatan_ppk->foto_1: base_url().'assets/img/login-page.png'; ?>" width="224" />
	<img src="<?php echo ($catatan_ppk->foto_2) ? base_url().MEDIAFOLDER.$catatan_ppk->foto_2: base_url().'assets/img/login-page.png'; ?>" width="224" />
	<img src="<?php echo ($catatan_ppk->foto_3) ? base_url().MEDIAFOLDER.$catatan_ppk->foto_3: base_url().'assets/img/login-page.png'; ?>" width="224" />
<?php
}
?>
<table width="100%" id="alamat" style="margin-top:15px;">
	<thead>
		<tr>
			<th style="width: 65%;padding:10px 10px 0px 10px; border:2px solid #FF7F50; border-bottom:none;"><i>(Perkembangan Karakter)</i></th>
			<th width="1%"></th>
			<th style="width: 35%; border:2px solid #7CFC00;padding:10px 10px 0px 10px; border-bottom:none;">(Prestasi)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($catatan_ppk){
		?>
			<tr>
				<td style="text-align:justify; padding:10px; vertical-align:top;border:2px solid #FF7F50; border-top:none;"><?php echo ($sekolah->pengantar_ppk) ? $sekolah->pengantar_ppk : 'Kata pengantar PPK belum di isi.'; ?></td>
				<td></td>
				<td style="text-align:justify;padding:10px;border:2px solid #7CFC00;border-top:none;"><?php echo $catatan_ppk->capaian; ?></td>
			</tr>
		<?php
		} else {
		?>
		<tr>
			<td style="text-align:justify; padding:10px; vertical-align:top;border:2px solid #FF7F50; border-top:none;">-</td>
			<td></td>
			<td style="text-align:justify;padding:10px;border:2px solid #7CFC00;border-top:none;">-</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<table width="100%" style="margin-top:10px;">
  <tr>
    <td style="width:40%;text-align:center;">
		<p>Mengetahui,<br>Kepala Sekolah</p>
	<br>
<br>
<br>
<br>
<br>
<br>
<p><u><?php echo get_nama_guru($sekolah->guru_id); ?></u><br>
NIP. <?php echo get_nip_guru($sekolah->guru_id); ?>
</p>
	</td>
	<td style="width:20%"></td>
    <td style="width:40%;text-align:center;"><p><?php echo $sekolah->kabupaten; ?>, .................................,20....<br>Wali Kelas</p><br>
<br>
<br>
<br>
<br>
<br>
<p>
<u><?php echo get_nama_guru($rombel->guru_id); ?></u><br>
NIP. <?php echo get_nip_guru($rombel->guru_id); ?></p>
</td>
  </tr>
</table>