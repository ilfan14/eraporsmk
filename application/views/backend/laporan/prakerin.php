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
			$loggeduser = $this->ion_auth->user()->row();
			$find_akses = get_akses($loggeduser->id);
			$guru_id = $find_akses['id'][0];
			$rombel = $this->rombongan_belajar->find("guru_id = $guru_id AND semester_id = $ajaran->id");
			$data_siswa = get_siswa_by_rombel($rombel->id);
			?>
              <div class="box-body">
			  	<input type="hidden" name="query" id="query" value="prakerin" />
				<input type="hidden" name="ajaran_id" value="<?php echo $ajaran->id; ?>" />
				<input type="hidden" name="rombel_id" value="<?php echo $rombel->id; ?>" />
				<div class="form-group">
                  <label for="siswa_id" class="col-sm-2 control-label">Nama Siswa</label>
				  <div class="col-sm-5">
                    <select name="siswa_id" class="select2 form-control" id="siswa" required>
						<option value="">== Pilih Nama Siswa ==</option>
						<?php foreach($data_siswa as $siswa){ ?>
						<option value="<?php echo $siswa->siswa->id; ?>"><?php echo $siswa->siswa->nama; ?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
			<div class="box-footer">
				<div id="result"></div>
			</div>
            <?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>