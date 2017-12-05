<div class="text-center">
<?php 
$nama_siswa = str_replace(' ','_',get_nama_siswa($siswa_id));
$nama_siswa = strtolower($nama_siswa);
$nama_siswa = $nama_siswa.'_'.$ajaran_id.'_'.$rombel_id;
$barcode = base_url().'files/barcode/'.$nama_siswa.'.png';
?>
<img src="<?php echo $barcode; ?>" width="200" />
</div>