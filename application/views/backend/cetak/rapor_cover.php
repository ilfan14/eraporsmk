<?php
$s = $this->siswa->get($siswa_id);
$sekolah = $this->sekolah->get(1);
$setting = $this->settings->get(1);
?>
<div class="text-center" id="cover_utama">
<br>
<br>
<br>
	<?php /*img src="<?php echo site_url('assets/img/logo.png'); ?>" border="0" width="200" */ ?>
	<img src="<?php echo (isset($sekolah->logo_sekolah) && $sekolah->logo_sekolah != '') ? base_url().PROFILEPHOTOSTHUMBS.$sekolah->logo_sekolah: base_url().'assets/img/logo.png'; ?>" height="200" border="0" />
<br>
<br>
<br>
<br>
<br>
<br>
<h3>RAPOR SISWA</h3>
<h3>SEKOLAH MENENGAH KEJURUAN</h3>
<h3>(SMK)</h3><br>
<br>
<br>
<br>
<br>
<br>
<div style="width:25%; float:left;">&nbsp;</div>
<div style="width:47%; float:left; padding:7px;">Nama Siswa:</div>
<div style="width:25%; float:left;">&nbsp;</div>
<div style="width:25%; float:left;">&nbsp;</div>
<div style="border:#000000 1px solid; width:47%; float:left; padding:7px;"><?php echo strtoupper($s->nama); ?></div>
<div style="width:25%; float:left;">&nbsp;</div>
<br>
<br>
<br>
<br>
<br>
<div style="width:25%; float:left;">&nbsp;</div>
<div style="width:47%; float:left; padding:7px;">NISN:</div>
<div style="width:25%; float:left;">&nbsp;</div>
<div style="width:25%; float:left;">&nbsp;</div>
<div style="border:#000000 1px solid; width:47%; float:left; padding:7px;"><?php echo $s->nisn; ?></div>
<div style="width:25%; float:left;">&nbsp;</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h3>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN<br>REPUBLIK INDONESIA</h3>
</div>