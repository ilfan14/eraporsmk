<?php
$kompetensi_dasar_alias = ($kd->kompetensi_dasar_alias) ? $kd->kompetensi_dasar_alias : $kd->kompetensi_dasar;
if($user_id == $kd->user_id){
	$form_id = '<input type="text" class="form-control" id="id_kompetensi" value="'.$kd->id_kompetensi.'" />';
	$form_kd = '<textarea id="kompetensi_dasar" class="editor form-control" rows="10" required placeholder="Kompetensi Dasar">'.$kd->kompetensi_dasar.'</textarea>';
	$form_alias = '';
} else {
	$form_id = $kd->id_kompetensi;
	$form_kd = $kd->kompetensi_dasar;
	$form_alias = $kompetensi_dasar_alias;
}
?>
<input type="hidden" id="mapel_id" value="<?php echo $kd->mata_pelajaran_id; ?>" />
<input type="hidden" id="kelas" value="<?php echo $kd->kelas; ?>" />
<input type="hidden" id="aspek" value="<?php echo $kd->aspek; ?>" />
<h4><strong>Kompetensi Dasar</strong></h4>
<table class="table">
	<tr>
		<td>Mata Pelajaran</td>
		<td><?php echo get_nama_mapel($kd->mata_pelajaran_id); ?></td>
	</tr>
	<tr>
		<td>ID KD</td>
		<td><?php echo $form_id; ?></td>
	</tr>
	<tr>
		<td>Kompetensi Dasar</td>
		<td><?php echo $form_kd; ?></td>
	</tr>
</table>
<h4><strong>Ringkasan Kompetensi</strong></h4>
<textarea id="kompetensi_dasar_alias" class="editor form-control" rows="10" required placeholder="Kompetensi Dasar Alias"><?php echo $form_alias; ?></textarea>
<script type="text/javascript">
<?php if($user_id == $kd->user_id){ ?>
$('#button_form').click(function(){
	var mapel_id = $('#mapel_id').val();
	var kelas = $('#kelas').val();
	var aspek = $('#aspek').val();
	var id_kompetensi = $('#id_kompetensi').val();
	var kompetensi_dasar = $('#kompetensi_dasar').val();
	var kompetensi_dasar_alias = $('#kompetensi_dasar_alias').val();
	$.ajax({
		url: '<?php echo site_url('admin/referensi/edit_kd/'.$kd->id); ?>',
		type: 'post',
		data: {mapel_id:mapel_id, kelas:kelas, aspek:aspek, id_kompetensi:id_kompetensi, kompetensi_dasar:kompetensi_dasar, kompetensi_dasar_alias:kompetensi_dasar_alias},
		success: function(response){
			var data = $.parseJSON(response);
			//swal({title:"Sukses!",text:"Data berhasil diupdate.",type:"success"}).then(function(e) {
			swal({title:data.title, html:data.text, type:data.type}).then(function(e) {
				$('#modal_content').modal('hide');
				$('#result').html(response);
				$('#datatable').dataTable().fnReloadAjax();
			});
		}
	});
});
<?php } else { ?>
$('#button_form').click(function(){
	var kompetensi_dasar_alias = $('#kompetensi_dasar_alias').val();
	$.ajax({
		url: '<?php echo site_url('admin/referensi/edit_kd/'.$kd->id); ?>',
		type: 'post',
		data: {kompetensi_dasar_alias:kompetensi_dasar_alias},
		success: function(response){
			swal({title:"Sukses!",text:"Data berhasil diupdate.",type:"success"}).then(function(e) {
				$('#modal_content').modal('hide');
				$('#result').html(response);
				$('#datatable').dataTable().fnReloadAjax();
			});
		}
	});
});
<?php } ?>
$(".editor").wysihtml5({
	"font-styles": false,
	"emphasis": true,
	"lists": false,
	"html": false,
	"link": false,
	"image": false,
	"color": false
});
</script>