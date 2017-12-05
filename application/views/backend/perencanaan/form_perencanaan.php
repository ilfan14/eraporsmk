<?php echo link_tag('assets/css/tooltip-viewport.css', 'stylesheet', 'text/css'); ?>
<script src="<?php echo base_url()?>assets/js/tooltip-viewport.js"></script>
<?php
$data_rombel = $this->rombongan_belajar->get($id_rombel);
if (strpos(get_kurikulum($data_rombel->kurikulum_id), 'REV') !== false) {
	//$kelas = 0;		
}
if($kompetensi_id == 1){
	$aspek = 'P';
	$placeholder = 'UH/UTS/UAS dll...';
} else {
	$aspek = 'K';
	$placeholder = 'Kinerja/Proyek/Portofolio';
}
//echo $kelas;
$mata_pelajaran = $this->mata_pelajaran->get($id_mapel);
$get_id_nasional = ($mata_pelajaran) ? $mata_pelajaran->id_nasional : 0;
$all_kd = $this->kompetensi_dasar->find_all("aspek = '$aspek' AND mata_pelajaran_id = $id_mapel AND kelas = $kelas");
if(!$all_kd){
	$all_kd = $this->kompetensi_dasar->find_all("aspek = 'PK' AND mata_pelajaran_id = $id_mapel AND kelas = $kelas");
	//$all_kd = Kd::find('all', array('conditions' => "kds.id_mapel  = '$id_mapel' AND kelas = $data_rombel->tingkat AND aspek = 'PK'"));
}
foreach($all_kd as $kd){
	$get_kd[$kd->id] = $kd->id_kompetensi;
	//$get_kd_alternatif[$kd->id] = $kd->id_kompetensi;
}
$bentuk_penilaian = $this->teknik_penilaian->find_all_by_kompetensi_id($kompetensi_id);
//1Metode::find_all_by_ajaran_id_and_kompetensi_id($ajaran_id, $kompetensi_id);
//$bentuk_penilaian = Metode::find_all_by_kompetensi_id($kompetensi_id);
if($all_kd){
?>
<table class="table table-striped table-bordered" id="clone">
	<thead>
		<tr>
			<th class="text-center" style="min-width:110px">Aktifitas Penilaian</th>
			<th class="text-center" style="min-width:110px;">Teknik</th>
			<th class="text-center" width="10">Bobot</th>
			<?php
			foreach($all_kd as $kd){
			?>
			<th class="text-center"><a href="javascript:void(0)" class="tooltip-top" title="<?php echo $kd->kompetensi_dasar; ?>"><?php echo $kd->id_kompetensi; ?></a></th>
			<?php
			} 
			?>
			<th class="text-center">Keterangan</th>
		</tr>
	</thead>
	<tbody>
		<?php for ($i = 1; $i <= 5; $i++) {?>
		<tr>
			<td>
				<input class="form-control input-sm" type="text" name="nama_penilaian[]" value="" placeholder="<?php echo $placeholder; ?>">
			</td>
			<td>
				<select class="form-control input-sm" name="bentuk_penilaian[]">
					<option value="">- Pilih -</option>
					<?php 
					if($bentuk_penilaian){
						foreach($bentuk_penilaian as $value){ ?>
					<option value="<?php echo $value->id; ?>"><?php echo $value->nama; ?></option>
					<?php } 
					} else {
					?>
					<option value="">Belum ada</option>
					<?php } ?>
				</select>
			</td>
			<td>
				<input class="form-control input-sm" type="text" name="bobot_penilaian[]" value="">
			</td>
			<?php
			foreach($all_kd as $kd){
			?>
			<td style="vertical-align:middle;">
				<input type="hidden" name="kd_id_<?php echo $i; ?>[]" value="<?php echo $kd->id; ?>" />
				<div class="text-center"><input type="checkbox" name="kd_<?php echo $i; ?>[]" value="<?php echo $kd->id_kompetensi; ?>|<?php echo $kd->id; ?>" /></div>
			</td>
			<?php } ?>
			<td><input class="form-control input-sm" type="text" name="keterangan_penilaian[]" value=""></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<a class="clone btn btn-danger pull-left">Tambah Aktivitas Penilaian</a>
<button type="submit" class="btn btn-success pull-right">Simpan</button>
<script>
$('#generate_rencana').show();
$('#import_rencana').show();
</script>
<?php } else { ?>
<script>
$('#generate_rencana').hide();
$('#import_rencana').hide();
</script>
<h3 class="text-center">Kompetensi Dasar belum tersedia <br />
<a class="btn btn-sm btn-success" href="<?php echo site_url('admin/referensi/add_kd/'.$kompetensi_id.'/'.$id_rombel.'/'.$id_mapel.'/'.$kelas); ?>" target="_blank">Tambah Data Kompetensi Dasar</a></h3>
<?php } ?>
<script>
$('a.generate_rencana').attr('href', '<?php echo site_url('admin/get_excel/perencanaan/'.$kompetensi_id.'/'.$id_rombel.'/'.$id_mapel); ?>')
$('button.simpan').remove();
var i = <?php echo isset($i) ? $i : 0; ?>;
$("a.clone").click(function() {
	$("table#clone tbody tr:last").clone().find("td").each(function() {
		$(this).find('input[type=hidden]').attr('name', 'kd_id_'+i+'[]');
		$(this).find('input[type=checkbox]').attr('name', 'kd_'+i+'[]');
	}).end().appendTo("table#clone");
	i++;
});
</script>
<?php echo link_tag('assets/css/tooltip-viewport.css', 'stylesheet', 'text/css'); ?>
<script src="<?php echo base_url()?>assets/js/tooltip-viewport.js"></script>