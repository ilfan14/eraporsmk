<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<!-- form start -->
            <?php
			$readonly = '';
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open($form_action,$attributes);
			//$ajaran = get_ta();
			//$tahun_ajaran = $ajaran->tahun. ' (SMT '. $ajaran->smt.')';
			?>
              <div class="box-body">
			  	<?php 
				if(isset($data)){
				$readonly = 'disabled';
				?>
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="id" value="<?php echo $data->id; ?>" />
				  <?php } ?>
			  	<input type="hidden" name="query" id="query" value="metode" />
				<div class="form-group">
                  <label for="mapel" class="col-sm-2 control-label">Kompetensi Penilaian</label>
				  <div class="col-sm-5">
                    <select name="kompetensi_id" class="select2 form-control" required>
						<option value="1">Pengetahuan</option>
						<option value="2">Keterampilan</option>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="nama_metode" class="col-sm-2 control-label">Nama Metode Penilaian</label>
				  <div class="col-sm-5">
                    <input type="text" name="nama_metode" class="form-control" value="<?php echo (isset($data)) ? $data->nama : ''; ?>" />
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
			<div class="box-footer">
				<div id="result_alt"></div>
				<button type="submit" class="btn btn-success simpan">Simpan</button>
			</div>
            <?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>