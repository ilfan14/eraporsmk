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
			$ajaran = get_ta();
			$tahun_ajaran = $ajaran->tahun. ' (SMT '. $ajaran->smt.')';
			$data_kompetensi = Keahlian::all();
			?>
              <div class="box-body">
			  	<div class="form-group">
				<label for="ajaran_id" class="col-sm-2 control-label">Tahun Pelajaran</label>
				  <div class="col-sm-5">
				  <input type="text" class="form-control" value="<?php echo $tahun_ajaran; ?>" readonly />
				  <?php if(isset($data)){
				  $readonly = 'disabled';
				  ?>
				  	<input type="hidden" name="action" value="edit" />
				  	<input type="hidden" name="id" value="<?php echo $data->id; ?>" />
				  <?php } ?>
				  	<input type="hidden" name="query" value="mulok" />
                    <input type="hidden" name="ajaran_id" value="<?php echo $ajaran->id; ?>" />
                  </div>
                </div>
				<?php
				$super_admin = array(1,2,5);
				if($this->ion_auth->in_group($super_admin)){
				?>
				<!--div class="form-group">
                  <label for="jurusan" class="col-sm-2 control-label">Kompetensi Keahlian</label>
				  <div class="col-sm-5">
                    <select name="jurusan" class="select2 form-control" id="jurusan" required>
						<option value="">== Pilih Kompentensi Keahlian ==</option>
						<?php foreach($data_kompetensi as $kompetensi){?>
						<option value="<?php echo $kompetensi->kurikulum_id; ?>"><?php echo get_kurikulum($kompetensi->kurikulum_id); ?></option>
						<?php } ?>
					</select>
                  </div>
                </div-->
				<?php } ?>
				<div class="form-group">
                  <label for="kelas" class="col-sm-2 control-label">Kelas</label>
				  <div class="col-sm-5">
                    <select name="kelas" class="select2 form-control" id="kelas" required>
						<option value="">== Pilih Kelas ==</option>
						<?php foreach($rombels as $rombel){?>
						<option value="<?php echo $rombel->tingkat; ?>"<?php echo (isset($data)) ? ($data_rombel->tingkat == $rombel->tingkat ? ' selected="selected"' : '') : ''; ?>>Kelas <?php echo $rombel->tingkat; ?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="rombel" class="col-sm-2 control-label">Rombongan Belajar</label>
				  <div class="col-sm-5">
                    <select name="rombel_id" class="select2 form-control" id="rombel" required>
						<option value="">== Pilih Rombongan Belajar ==</option>
						<?php if(isset($data)){ ?>
						<?php foreach($all_rombel as $r){?>
						<option value="<?php echo $r->id; ?>"<?php echo (isset($data)) ? ($data->rombel_id == $r->id ? ' selected="selected"' : '') : ''; ?>><?php echo $r->nama; ?></option>						
						<?php } ?>
						<?php } ?>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="guru" class="col-sm-2 control-label">Nama Guru</label>
				  <div class="col-sm-5">
                    <select name="guru_id" class="select2 form-control" id="guru" required>
						<option value="">== Pilih Nama Guru ==</option>
						<?php foreach($data_guru as $guru){ ?>
						<option value="<?php echo $guru->id;?>"<?php echo (isset($data)) ? ($data->guru_id == $guru->id ? ' selected="selected"' : '') : '';?>><?php echo $guru->nama; ?></option>
						<?php
						}
						?>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="mapel" class="col-sm-2 control-label">Mata Pelajaran</label>
				  <div class="col-sm-5">
                    <input type="text" name="nama_mulok" class="form-control" value="<?php echo (isset($data)) ? $data->nama_mulok : ''; ?>" />
                  </div>
                </div>
				<div class="form-group">
                  <label for="nama_kur" class="col-sm-2 control-label">KKM</label>
				  <div class="col-sm-1">
                    <input type="text" name="kkm" class="form-control" value="<?php echo (isset($data)) ? $data->kkm : ''; ?>" />
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
			<div class="box-footer">
				<div id="result"></div>
				<button type="submit" class="btn btn-success"><?php echo isset($data) ? 'Update' : 'Simpan'; ?></button>
			</div>
            <?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>