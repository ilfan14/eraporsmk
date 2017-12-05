<div class="row">
	<div class="col-xs-12">
		<!-- form start -->
            <?php 
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open($form_action,$attributes);
			?>
              <div class="box-body">
			  	<div class="form-group">
                  <label for="nama_kegiatan" class="col-sm-4 control-label">Nama Kegiatan Penilaian</label>
				  <div class="col-sm-8">
					<input type="hidden" id="ppk_id" value="<?php echo $ppk_id; ?>" />
					<input type="hidden" id="ajaran_id" value="<?php echo $ajaran_id; ?>" />
					<input type="hidden" id="rombel_id" value="<?php echo $rombel_id; ?>" />
                    <input type="text" class="form-control" value="<?php echo $nama_kegiatan; ?>" id="nama_kegiatan" />
                  </div>
                </div>
				<div class="form-group">
                  <label for="posisi" class="col-sm-4 control-label">Jenis Penilaian PPK</label>
				  <div class="col-sm-8">
                    <select name="posisi" class="select2 form-control" id="posisi">
						<option value="1"<?php echo ($posisi == 1) ? ' selected="selected"' : '';?>>Di dalam Sekolah</option>
						<option value="2"<?php echo ($posisi == 2) ? ' selected="selected"' : '';?>>Di luar Sekolah</option>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="guru_id" class="col-sm-4 control-label">Penanggung Jawab</label>
				  <div class="col-sm-8">
                    <select name="guru_id" class="select2 form-control" id="guru_id">
						<option value="">== Pilih Guru ==</option>
						<?php foreach($data_guru as $guru){?>
						<option value="<?php echo $guru->id; ?>"<?php echo ($guru->id == $guru_id) ? ' selected="selected"' : '';?>><?php echo $guru->nama; ?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
			</div>
              <!-- /.box-body -->
			
            <?php echo form_close();  ?>
</div><!-- /.col -->
</div><!-- /.row -->
<script>
$('.select2').select2({ width: '100%' });
$('.add').click(function(){
	var ppk_id = $('#ppk_id').val();
			var ajaran_id = $('#ajaran_id').val();
			var rombel_id = $('#rombel_id').val();
			var nama_kegiatan = $('#nama_kegiatan').val();
			var guru_id = $('#guru_id').val();
			var posisi = $('#posisi').val();
			if(nama_kegiatan == ''){
				swal({title:"Error",text:"Nama kegiatan tidak boleh kosong.",type:"error"});
				return false;
			}
			if(guru_id == ''){
				swal({title:"Error",text:"Penanggung jawab kegiatan tidak boleh kosong.",type:"error"});
				return false;
			}
			$.ajax({
				url: '<?php echo site_url('admin/asesmen/update_ppk'); ?>',
				type: 'post',
				data: {ppk_id:ppk_id, ajaran_id:ajaran_id, rombel_id:rombel_id, nama_kegiatan:nama_kegiatan, guru_id:guru_id, posisi:posisi},
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