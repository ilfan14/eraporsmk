<div class="row">
	<div class="col-xs-12">
	<div class="callout callout-danger" style="display:none;">
		<h4>Error!</h4>
		<p class="error" style="display:none;">Permintaan tidak bisa di proses. Silahkan ulangi lagi.</p>
		<p class="guru" style="display:none;">Guru Mata Pelajaran Tidak Boleh Kosong.</p>
	</div>
	<form id="mapel_diampu">
	<input type="hidden" name="guru_id" value="<?php echo $guru_id; ?>" />
	<input type="hidden" name="ajaran_id" value="<?php echo $ajaran_id; ?>" />
	<table id="clone" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th width="5%" class="text-center">No.</th>
				<th class="text-center">Tingkat</th>
				<th class="text-center">Rombongan Belajar</th>
				<th class="text-center">Mata Pelajaran</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if($all_mapel){
				$i=1;
				foreach($all_mapel as $mapel){
					$rombel = Datarombel::find_by_id($mapel->rombel_id);
					$tingkat = isset($rombel->tingkat) ? $rombel->tingkat : 0;
					$kurikulum_id = isset($rombel->kurikulum_id) ? $rombel->kurikulum_id : 0;
					$nama_rombel = isset($rombel->nama) ? $rombel->nama : 0;
			?>
				<tr>
					<td class="text-center"><span class="nomor"><?php echo $i; ?></span></td>
					<td>
						<select id="tingkat" class="select2 form-control required" name="tingkat[]" style="width:100%">
						<option value="">==Pilih Tingkat==</option>
							<?php foreach($kelas as $k){?>
							<option value="<?php echo $k->tingkat; ?>"<?php echo ($tingkat == $k->tingkat) ? ' selected="selected"' : '';?>>Kelas <?php echo $k->tingkat; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						<select id="rombel_id" class="select2 form-control required" name="rombel_id[]" style="width:100%">
						<option value="<?php echo $kurikulum_id; ?>#<?php echo $mapel->rombel_id; ?>"><?php echo $nama_rombel; ?></option>
						</select>
					</td>
					<td>
						<select id="mapel_id" class="select2 form-control required" name="mapel_id[]" style="width:100%">
						<option value="<?php echo $mapel->id_mapel; ?>"><?php echo get_nama_mapel($ajaran_id, $mapel->rombel_id, $mapel->id_mapel); ?></option>
						</select>
					</td>
				</tr>
			<?php
					$i++;
				}
			} else {
				for($i = 1; $i <= 5; $i++){
				?>
				<tr>
					<td class="text-center"><span class="nomor"><?php echo $i; ?></span></td>
					<td>
						<select id="tingkat" class="select2 form-control required" name="tingkat[]" style="width:100%">
						<option value="">==Pilih Tingkat==</option>
							<?php foreach($kelas as $k){?>
							<option value="<?php echo $k->tingkat; ?>">Kelas <?php echo $k->tingkat; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						<select id="rombel_id" class="select2 form-control required" name="rombel_id[]" style="width:100%">
						<option value="">== Pilih Rombongan Belajar ==</option>
						</select>
					</td>
					<td>
						<select id="mapel_id" class="select2 form-control required" name="mapel_id[]" style="width:100%">
						<option value="">== Pilih Mata Pelajaran ==</option>
						</select>
					</td>
				</tr>
			<?php 
				}
			} ?>
		</tbody>
	</table>
	</form>
	<a class="clone btn btn-danger pull-left">Tambah</a>
	</div>
</div>
<?php 
$uri = $this->uri->segment_array();
?>
<script type="text/javascript">
var ajaran_id = $('input[name=ajaran_id]').val();
function select_rombel(){
	$('select#rombel_id').each(function(){
		$(this).change(function(){
			var rombel_id = $(this).val();
			var $mapel_id = $(this).closest('td').next('td').find('#mapel_id');
			$.ajax({
				url: '<?php echo site_url('admin/ajax/get_kurikulum_alt');?>',
				type: 'post',
				data: {query:1,ajaran_id:ajaran_id,rombel_id:rombel_id},
				success: function(response){
					$mapel_id.html('<option value="">== Pilih Mata Pelajaran ==</option>');
					var data = $.parseJSON(response);
					if($.isEmptyObject(data.mapel)){
					} else {
						$.each(data.mapel, function (i, item) {
							$mapel_id.append($('<option>', { 
								value: item.value,
								text : item.text
							}));
						});
					}
				}
			});
		});
	});
}
function select_tingkat(){
	$('select#tingkat').each(function(){
		$(this).change(function(){
			var kelas = $(this).val();
			var $rombel_id = $(this).closest('td').next('td').find('#rombel_id');
			$.ajax({
				url: '<?php echo site_url('admin/ajax/get_rombel_alt');?>',
				type: 'post',
				data: {query:1,kelas:kelas},
				success: function(response){
					$rombel_id.html('<option value="">== Pilih Rombongan Belajar ==</option>');
					var data = $.parseJSON(response);
					if($.isEmptyObject(data.result)){
					} else {
						$.each(data.result, function (i, item) {
							$rombel_id.append($('<option>', { 
								value: item.value,
								text : item.text
							}));
						});
					}
				}
			});
		});
	});
}
$(function() {
	select_rombel();
	select_tingkat();
	var i = <?php echo isset($i) ? $i : 0; ?>;
	$("a.clone").click(function() {
		$('select.select2').select2('destroy');
		$("table#clone tbody tr:last").clone().find("td").each(function() {
			$(this).find('.nomor').text(i);
			$(this).find('select').select2();
			$(this).find('select').val('').trigger('change');
			//$(this).find('select').remove();
			//$(this).find('input[type=checkbox]').attr('name', 'kd_'+i+'[]');
		}).end().appendTo("table#clone");
		$('select.select2').select2();
		select_rombel();
		select_tingkat();
		i++;
	});
	$('.select2').select2();
	$('.simpan_mapel').click(function(){
		var guru_id = $('#guru_id').val();
		if(guru_id == ''){
			$('.callout').show();
			$('p.guru').show();
		} else {
			$.ajax({
				url: '<?php echo site_url('admin/rombel/simpan_mapel/')?>',
				type: 'post',
				data: $("form#mapel_diampu").serialize(),
				success: function(response){
					$('.callout').show();
					$('.callout').html(response);
					/*swal({title:'sukses', html:'Berhasil mengupdate data', type:'success'}).then(function() {
						$('#modal_content').modal('hide');
					}).done();*/
				}
			});
		}
	});
});
</script>	