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
			  <div class="col-sm-8">
                <div class="form-group">
                  <label for="ajaran_id" class="col-sm-3 control-label">Tahun Ajaran</label>
				  <div class="col-sm-9">
					<input type="hidden" name="query" id="query" value="<?php echo $query; ?>" />
					<input type="hidden" name="query_2" id="query_2" value="remedial" />
                    <input type="hidden" name="ajaran_id" value="<?php echo $ajaran->id; ?>" />
                    <input type="text" class="form-control" value="<?php echo $tahun_ajaran; ?>" readonly />
                  </div>
                </div>
                <div class="form-group">
                  <label for="rombel_id_perencanaan" class="col-sm-3 control-label">Tingkat Kelas</label>
				  <div class="col-sm-9">
                    <select name="kelas" class="select2 form-control" id="kelas">
						<option value="">== Pilih Tingkat Kelas ==</option>
						<?php foreach($tingkat_pendidikan as $tingkat){?>
						<option value="<?php echo $tingkat->id; ?>"><?php echo $tingkat->nama; ?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="rombel" class="col-sm-3 control-label">Rombongan Belajar</label>
				  <div class="col-sm-9">
                    <select name="rombel_id" class="select2 form-control" id="rombel">
						<option value="">== Pilih Rombongan Belajar ==</option>
					</select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="id_mapel_penilaian" class="col-sm-3 control-label">Mata Pelajaran</label>
				  <div class="col-sm-9">
                    <select name="id_mapel" class="select2 form-control" id="mapel">
						<option value="">== Pilih Mata Pelajaran ==</option>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="penilaian" class="col-sm-3 control-label">Aspek Penilaian</label>
				  <div class="col-sm-9">
                    <select name="aspek" class="select2 form-control" id="penilaian">
						<option value="">== Pilih Penilaian ==</option>
					</select>
                  </div>
                </div>
				</div>
				<div class="col-sm-4">
					<div id="rumus"></div>
				</div>
              </div>
              <!-- /.box-body -->
			<div class="box-footer table-responsive no-padding">
				<div id="result"></div>
				<button type="submit" class="btn btn-success simpan" style="display:none;">Simpan</button>
				<a href="javascript:void(0)" class="btn btn-success" id="rerata_remedial" style="display:none;">Simpan</a>
			</div>
            <?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>