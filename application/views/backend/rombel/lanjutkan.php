<div class="row">
		<div class="col-xs-12">
		<div class="text-center" style="margin-bottom:10px;"><a href='#' class="btn btn-success" id='select-all'>Pilih Semua</a>
		<a href='#' class="btn btn-success" id='deselect-all'>Lepas Semua</a>
		<a id="refresh" class="btn btn-success" href="#">Segarkan</a>
		</div>
		<select id='pilih_siswa' multiple='multiple' class="form-control">
				<?php foreach($anggota as $a){ 
				//$siswa = Datasiswa::find_by_id($a->siswa_id);?>
				<option value="<?php echo $a->siswa->id; ?>"><?php echo $a->siswa->nama; ?></option>
				<?php } ?>
		</select>
		<input type="hidden" class="form-control" name="rombel_id" id="rombel_id" value="<?php echo $rombel_id; ?>" />
     </div>
</div>
<?php echo link_tag('assets/css/multi-select.css', 'stylesheet', 'text/css'); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.multi-select.js" type="text/javascript"></script>
<script type="text/javascript">
$('#pilih_siswa').multiSelect();
$('#select-all').click(function(){
  $('#pilih_siswa').multiSelect('select_all');
  return false;
});
$('#deselect-all').click(function(){
  $('#pilih_siswa').multiSelect('deselect_all');
  return false;
});
$('#refresh').on('click', function(){
  $('#pilih_siswa').multiSelect('refresh');
  return false;
});
$('.proses_kenaikan').click(function(){
	var id_siswa = $('#pilih_siswa').val();
	var id_rombel = $('#rombel_id').val();
	if($.isEmptyObject(id_siswa)){
		swal({title:"Error",text:"Silahkan pilih siswa terlebih dahulu.",type:"error"});
		return false;
	}
	if(id_rombel == ''){
		swal({title:"Error",text:"Silahkan pilih rombongan belajar terlebih dahulu.",type:"error"});
		return false;
	}
	$.ajax({
		url: '<?php echo site_url('admin/rombel/proses_lanjutkan/'); ?>',
		type: 'post',
		data: {id_siswa:id_siswa,id_rombel:id_rombel},
		success: function(response){
			var data = $.parseJSON(response);
			swal({title:data.title,text:data.text,type:data.type}).then(function() {
				if(data.status == 1){
					$('#modal_content').modal('hide');
					$('#datatable').dataTable().fnReloadAjax();
				}
			}).done();
		}
	});
});
</script>	