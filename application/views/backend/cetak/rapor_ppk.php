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
$setting = $this->settings->get(1);
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
<h4 class="text-center">LAPORAN</h4> 
<h4 class="text-center">PERKEMBANGAN KARAKTER</h4>
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
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nama Peserta Didik</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 35%"><?php echo $s->nama; ?></td>
	<td style="width: 20%;">Semester</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 25%"><?php echo ($ajaran->semester == 1) ? '1 (Satu)' : '2 (Dua)'; ?></td>
  </tr>
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nomor Induk/NISN</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 35%"><?php echo $s->no_induk.' / '.$s->nisn; ?></td>
	<td style="width: 20%;">Tahun Pelajaran</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 25%"><?php echo $ajaran->tahun; ?></td>
  </tr>
</table>
<div style="clear:both; margin:20px 0px 20px 0px;">
<?php
/*if($catatan_ppk){
?>
	<img src="<?php echo ($catatan_ppk->foto_1) ? base_url().MEDIAFOLDER.$catatan_ppk->foto_1: base_url().'assets/img/login-page.png'; ?>" width="224" />
	<img src="<?php echo ($catatan_ppk->foto_2) ? base_url().MEDIAFOLDER.$catatan_ppk->foto_2: base_url().'assets/img/login-page.png'; ?>" width="224" />
	<img src="<?php echo ($catatan_ppk->foto_3) ? base_url().MEDIAFOLDER.$catatan_ppk->foto_3: base_url().'assets/img/login-page.png'; ?>" width="224" />
<?php
}*/
?>
<?php 
echo ($catatan_ppk->capaian) ? $catatan_ppk->capaian : 'Tidak ada data untuk ditampilkan';
?>
</div>
<table width="100%" style="margin-top:10px;">
  <tr>
    <td style="width:40%;text-align:center;">
		<!--p>Mengetahui,<br>Orangtua/Wali</p>
	<br>
<br>
<br>
<p><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p-->
	</td>
	<td style="width:20%"></td>
    <td style="width:40%;text-align:center;"><p><?php echo $sekolah->kabupaten; ?>, <?php echo TanggalIndo($setting->tanggal_rapor); ?><br>Wali Kelas</p><br>
<br>
<br>
<br>
<p>
<u><?php echo get_nama_guru($rombel->guru_id); ?></u><br>
NIP. <?php echo get_nip_guru($rombel->guru_id); ?></p>
</td>
  </tr>
</table>
<!--table width="100%" style="margin-top:10px;">
  <tr>
    <td style="width:100%;text-align:center;">
		<p>Mengetahui,<br>Kepala Sekolah</p>
	<br>
<br>
<br>
<p><u><?php echo get_nama_guru($sekolah->guru_id); ?></u><br>
NIP. <?php echo get_nip_guru($sekolah->guru_id); ?>
</p>
	</td>
  </tr>
</table-->