<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<!-- form start -->
            <?php
			$loggeduser = $this->ion_auth->user()->row();
			$readonly = '';
			$kurikulum_id = '';
			$kelas_10 = 0;
			$kelas_11 = 0;
			$kelas_12 = 0;
			$kelas_13 = 0;
			$jurusan_id = '';
			$kurikulum_id = '';
			$kelompok_id = '';
			if(isset($data)){
				$mata_pelajaran_id = $data->id;
				$mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->find_all_by_mata_pelajaran_id($mata_pelajaran_id);
				foreach($mata_pelajaran_kurikulum as $matpel_kur){
					$kurikulum_id = $matpel_kur->kurikulum_id;
					$kelompok_id = $matpel_kur->kelompok_id;
					$tingkat_pendidikan_id[] = $matpel_kur->tingkat_pendidikan_id;
				}
				$nama_mapel = get_nama_mapel($mata_pelajaran_id);
				if(in_array(10,$tingkat_pendidikan_id)){
					$kelas_10 = 10;
				}
				if(in_array(11,$tingkat_pendidikan_id)){
					$kelas_11 = 11;
				}
				if(in_array(12,$tingkat_pendidikan_id)){
					$kelas_12 = 12;
				}
				if(in_array(13,$tingkat_pendidikan_id)){
					$kelas_13 = 13;
				}
				$find_keahlian = $this->kurikulum->find_by_kurikulum_id($kurikulum_id);
				$jurusan_id = ($find_keahlian) ? $find_keahlian->jurusan_id : 0;
				//$kurikulum_id = $data->jurusan_id;
			}
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open($form_action,$attributes);
			$bidang_keahlian = $this->jurusan_sp->find_all("sekolah_id = $sekolah_id");
			$all_rombel = $this->rombongan_belajar->find_all("sekolah_id = $loggeduser->sekolah_id AND semester_id = $ajaran->id");
			if($all_rombel){
				foreach($all_rombel as $rombel){
					$get_kurikulum_id[] = $rombel->kurikulum_id;
				}
			}
			?>
			<input type="hidden" name="query" class="form-control" value="mata_pelajaran_kompetensi" />
            <input type="hidden" name="action" class="form-control" value="<?php echo isset($data) ? 'edit' : 'add'; ?>" />
			<input type="hidden" name="id" class="form-control" value="<?php echo isset($data) ? $data->id : 0; ?>" />
              <div class="box-body">
			  	<div class="form-group">
                  <label for="jurusan_id" class="col-sm-3 control-label">Kompetensi Keahlian</label>
				  <div class="col-sm-6">
				  <?php
					$extra = 'class="select2 form-control required" id="jurusan_id"';
					$set_keahlian[''] = '== Pilih Kompetensi Keahlian ==';
					foreach($bidang_keahlian as $keahlian){
						$set_keahlian[$keahlian->kurikulum_id] = get_jurusan($keahlian->kurikulum_id).'('.$keahlian->kurikulum_id.')';
					}
					/*foreach ($bidang_keahlian as $keahlian){
						$set_keahlian[$keahlian->kurikulum_id] = get_jurusan($keahlian->kurikulum_id);
					}*/
					echo form_dropdown('jurusan_id', $set_keahlian, $jurusan_id, $extra);
					?>
                  </div>
                </div>
				<div class="form-group">
                  <label for="jurusan_id" class="col-sm-3 control-label">Kurikulum</label>
				  <div class="col-sm-6">
				  <?php
					$extra1 = 'class="select2 form-control required" id="kurikulum_id"';
					$set_kurikulum[''] = '== Pilih Kurikulum ==';
					if(isset($get_kurikulum_id)){
						$get_kurikulum_id = array_unique($get_kurikulum_id);
						foreach($get_kurikulum_id as $kurikulum){
							$set_kurikulum[$kurikulum] = get_kurikulum($kurikulum);
						}
					}
					/*foreach ($bidang_keahlian as $keahlian){
						$set_keahlian[$keahlian->kurikulum_id] = get_jurusan($keahlian->kurikulum_id);
					}*/
					echo form_dropdown('kurikulum_id', $set_kurikulum, $kurikulum_id, $extra1);
					?>
                  </div>
                </div>
				<div class="form-group">
                  <label for="kelompok_id" class="col-sm-3 control-label">Kelompok Mata Pelajaran</label>
				  <div class="col-sm-6">
                    <select name="kelompok_id" id="kelompok_id" class="select2 form-control" required>
						<option value="">== Pilih Kelompok Mata Pelajaran ==</option>
						<?php 
						if(isset($data)){
							foreach($get_kelompok as $kelompok){?>
						<option value="<?php echo $kelompok->id; ?>"<?php echo ($kelompok_id == $kelompok->id) ? ' selected="selected"' : ''; ?>><?php echo $kelompok->nama_kelompok; ?></option>
						<?php }
						}
						?>
					</select>
                  </div>
                </div>
                <div class="form-group">
					<label class="col-sm-3 control-label">Tingkat Kelas</label>
					<div class="col-sm-6">
						<input type="checkbox" class="icheck" name="tingkat_pendidikan_id[]" value="10"<?php echo isset($data) && $kelas_10 == 10 ? ' checked="checked"' : ''; ?> /> Tingkat 10<br style="margin-bottom:5px;" />
						<input type="checkbox" class="icheck" name="tingkat_pendidikan_id[]" value="11"<?php echo isset($data) && $kelas_11 == 11 ? ' checked="checked"' : ''; ?> /> Tingkat 11<br style="margin-bottom:5px;" />
						<input type="checkbox" class="icheck" name="tingkat_pendidikan_id[]" value="12"<?php echo isset($data) && $kelas_12 == 12 ? ' checked="checked"' : ''; ?> /> Tingkat 12<br style="margin-bottom:5px;" />
						<input type="checkbox" class="icheck" name="tingkat_pendidikan_id[]" value="13"<?php echo isset($data) && $kelas_13 == 13 ? ' checked="checked"' : ''; ?> /> Tingkat 13
					</div>
                </div>
				<div class="form-group">
                  <label for="kelas" class="col-sm-3 control-label">Nama Mata Pelajaran</label>
				  <div class="col-sm-6">
                    <input name="nama_mata_pelajaran" class="form-control" value="<?php echo isset($data) ? $data->nama_mata_pelajaran : ''; ?>" required />
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
			<div class="box-footer">
				<div id="result_alt"></div>
				<button type="submit" class="btn btn-success">Simpan</button>
			</div>
            <?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>
<script>
$('#jurusan_id').change(function(){
	$("#kelompok_id").trigger('change.select2');
	var ini = $(this).val();
	if(ini == ''){
		return false;
	}
	$.ajax({
		url: '<?php echo site_url('admin/ajax/get_kurikulum_new'); ?>',
		type: 'post',
		data: $("form").serialize(),
		success: function(response){
			var data = $.parseJSON(response);
			$('#kurikulum_id').html('<option value="">== Pilih Kurikulum ==</option>');
			if($.isEmptyObject(data.result)){
			} else {
				$.each(data.result, function (i, item) {
					$('#kurikulum_id').append($('<option>', { 
						value: item.value,
						text : item.text
					}));
				});
			}
		}
	});
});
$('#kurikulum_id').change(function(){
	$("#kelompok_id").trigger('change.select2');
	var ini = $(this).val();
	if(ini == ''){
		return false;
	}
	$.ajax({
		url: '<?php echo site_url('admin/ajax/get_kelompok'); ?>',
		type: 'post',
		data: $("form").serialize(),
		success: function(response){
			var data = $.parseJSON(response);
			$('#kelompok_id').html('<option value="">== Pilih Kelompok Mata Pelajaran ==</option>');
			if($.isEmptyObject(data.result)){
			} else {
				$.each(data.result, function (i, item) {
					$('#kelompok_id').append($('<option>', { 
						value: item.value,
						text : item.text
					}));
				});
			}
		}
	});
});
</script>