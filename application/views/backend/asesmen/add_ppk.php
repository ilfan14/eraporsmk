<div class="row">
	<div class="col-xs-12">
		<!-- form start -->
            <?php 
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open($form_action,$attributes);
			$ajaran = get_ta();
			$tahun_ajaran = $ajaran->tahun. ' (SMT '. $ajaran->semester.')';
			?>
              <div class="box-body">
			  	<input type="hidden" id="ajaran_id" value="<?php echo $ajaran->id; ?>" />
				<input type="hidden" id="rombel_id" value="<?php echo $rombel; ?>" />
			  	<div class="form-group">
                  <label for="posisi" class="col-sm-4 control-label">Jenis Penilaian PPK</label>
				  <div class="col-sm-8">
                    <select name="posisi" class="select2 form-control" id="posisi">
						<option value="">== Pilih Jenis Penilaian ==</option>
						<option value="1">Di dalam Sekolah</option>
						<option value="2">Di luar Sekolah</option>
					</select>
                  </div>
                </div>
			  	<div class="form-group" id="nama_kegiatan_change" style="display:none;">
                  <label for="nama_kegiatan" class="col-sm-4 control-label">Nama Kegiatan Penilaian</label>
				  <div class="col-sm-8">
					<input type="text" class="form-control" value="" id="nama_kegiatan" />
                  </div>
                </div>
				<div class="form-group" id="id_kegiatan_change" style="display:none;">
                  <label for="id_kegiatan" class="col-sm-4 control-label">Nama Kegiatan Penilaian</label>
				  <div class="col-sm-8">
					<select name="id_kegiatan" class="select2 form-control" id="id_kegiatan">
						<option value="">== Pilih Kegiatan ==</option>
					</select>
                  </div>
                </div>
				<div class="form-group" id="guru_id_change" style="display:none;">
                  <label for="guru_id" class="col-sm-4 control-label">Penanggung Jawab</label>
				  <div class="col-sm-8">
                    <select name="guru_id" class="select2 form-control" id="guru_id">
						<option value="">== Pilih Guru ==</option>
						<?php foreach($data_guru as $guru){?>
						<option value="<?php echo $guru->id; ?>"><?php echo $guru->nama; ?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
				<div class="form-group" id="penanggung_jawab_change" style="display:none;">
                  <label for="penanggung_jawab" class="col-sm-4 control-label">Penanggung Jawab</label>
				  <div class="col-sm-8">
					<input type="text" class="form-control" value="" id="penanggung_jawab" />
                  </div>
                </div>
			</div>
              <!-- /.box-body -->
            <?php echo form_close();  ?>
</div><!-- /.col -->
</div><!-- /.row -->
<script>
$('#posisi').change(function(e){
	e.preventDefault();
	var posisi = $(this).val();
	if(posisi){
		if(posisi == 1){
			$.ajax({
				url: '<?php echo site_url('admin/ajax/get_ppk'); ?>',
				type: 'post',
				data: $("form").serialize(),
				success: function(response){
					var data = $.parseJSON(response);
					if($.isEmptyObject(data.result)){
					} else {
						$.each(data.result, function (i, item) {
							$('#id_kegiatan').append($('<option>', { 
								value: item.value,
								text : item.text
							}));
						});
					}
				}
			});
			$('#nama_kegiatan_change').hide();
			$('#id_kegiatan_change').show();
		} else {
			$('#nama_kegiatan_change').show();
			$('#id_kegiatan_change').hide();
			$('#penanggung_jawab_change').show();
		}
	} else {
		$('#nama_kegiatan_change').hide();
		$('#id_kegiatan_change').hide();
	}
	$('#id_kegiatan').html('<option value="">== Pilih Kegiatan ==</option>');
});
$('#id_kegiatan').change(function(e){
	e.preventDefault();
	var id_kegiatan = $(this).val();
	if(id_kegiatan){
		$.ajax({
			url: '<?php echo site_url('admin/ajax/get_guru_ekskul'); ?>',
			type: 'post',
			data: $("form").serialize(),
			success: function(response){
				//$("#guru_id").trigger('change.select2');
				$('#guru_id').val(response).trigger('change');
				$('#guru_id_change').show();
				$('#penanggung_jawab_change').hide();
			}
		});
	} else {
		$('#guru_id_change').hide();
		$('#penanggung_jawab_change').hide();
	}
	//$('#guru_id').html('<option value="">== Pilih Guru ==</option>');
});
$('.select2').select2({ width: '100%' });
$('.add').click(function(){
			var ajaran_id = $('#ajaran_id').val();
			var rombel_id = $('#rombel_id').val();
			var id_kegiatan = $('#id_kegiatan').val();
			var nama_kegiatan = $('#nama_kegiatan').val();
			var guru_id = $('#guru_id').val();
			var posisi = $('#posisi').val();
			var penanggung_jawab = $('#penanggung_jawab').val();
			if(posisi == 1){
				if(id_kegiatan == ''){
					swal({title:"Error",text:"Nama kegiatan tidak boleh kosong.",type:"error"});
					return false;
				}
			} else {
				if(nama_kegiatan == ''){
					swal({title:"Error",text:"Nama kegiatan tidak boleh kosong.",type:"error"});
					return false;
				}
			}
			if(posisi == 1){
				if(guru_id == ''){
					swal({title:"Error",text:"Penanggung jawab kegiatan tidak boleh kosong.",type:"error"});
					return false;
				}
			} else {
				if(penanggung_jawab == ''){
					swal({title:"Error",text:"Penanggung jawab kegiatan tidak boleh kosong.",type:"error"});
					return false;
				}
			}
			$.ajax({
				url: '<?php echo site_url('admin/asesmen/simpan_ppk'); ?>',
				type: 'post',
				data: {ajaran_id:ajaran_id, rombel_id:rombel_id, id_kegiatan:id_kegiatan, nama_kegiatan:nama_kegiatan, guru_id:guru_id, penanggung_jawab:penanggung_jawab, posisi:posisi},
				success: function(response){
					var data = $.parseJSON(response);
					swal({title:data.title, html:data.text, type:data.type}).then(function() {
						$('#datatable').dataTable().fnReloadAjax();
						$('#modal_content').modal('hide');
					});
				}
			});
		});
</script>