<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<!-- form start -->
            <?php 
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open($form_action,$attributes);
			$ajaran = get_ta();
			$tahun_ajaran = $ajaran->tahun. ' (SMT '. $ajaran->semester.')';
			$tingkat_pendidikan = $this->tingkat_pendidikan->get_all();
			?>
			<div class="box-body">
				<div class="col-sm-6">
				<div class="form-group">
					<label for="ajaran_id" class="col-sm-5 control-label">Tahun Ajaran</label>
					<div class="col-sm-7">
						<input type="hidden" name="query" id="query" value="<?php echo $query; ?>" />
						<input type="hidden" name="kompetensi_id" id="kompetensi_id" value="<?php echo $kompetensi_id; ?>" />
						<input type="hidden" name="ajaran_id" id="ajaran_id" value="<?php echo $ajaran->id; ?>" />
						<input type="text" class="form-control" value="<?php echo $tahun_ajaran; ?>" readonly />
					</div>
                </div>
                <div class="form-group">
                  <label for="kelas" class="col-sm-5 control-label">Tingkat Kelas</label>
				  <div class="col-sm-7">
                    <select name="kelas" class="select2 form-control" id="kelas">
						<option value="">== Pilih Tingkat Kelas ==</option>
						<?php foreach($tingkat_pendidikan as $tingkat){?>
						<option value="<?php echo $tingkat->id; ?>"><?php echo $tingkat->nama; ?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="rombel" class="col-sm-5 control-label">Rombongan Belajar</label>
				  <div class="col-sm-7">
                    <select name="rombel_id" class="select2 form-control" id="rombel">
						<option value="">== Pilih Rombongan Belajar ==</option>
					</select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="mapel" class="col-sm-5 control-label">Mata Pelajaran</label>
				  <div class="col-sm-7">
                    <select name="id_mapel" class="select2 form-control" id="mapel">
						<option value="">== Pilih Mata Pelajaran ==</option>
					</select>
                  </div>
                </div>
				</div>
				<div class="col-sm-3" id="generate_rencana" style="display:none;">
					<a class="generate_rencana btn btn-block btn-success btn-lg active" href="#">Generate Format <br />Perencanaan</a>
				</div>
				<div class="col-sm-3" id="import_rencana" style="display:none;">
					<p class="text-center"><span class="btn btn-block btn-lg btn-primary btn-file">Upload<br />Perencanaan  <input type="file" id="fileupload" name="import" /></span></p>
					<div id="progress" class="progress" style="margin-top:10px; display:none;">
						<div class="progress-bar progress-bar-success"></div>
					</div>
				</div>
              </div>
              <!-- /.box-body -->
			<div class="box-footer table-responsive no-padding" id="result">
			</div>
			<button type="submit" class="btn btn-success simpan" style="display:none;float:right;">Simpan</button>
            <?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>