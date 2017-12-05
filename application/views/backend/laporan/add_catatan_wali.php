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
			$data_rombel = $this->rombongan_belajar->find("guru_id = $guru_id AND semester_id = $ajaran->id");
			//Datarombel::find_by_guru_id_and_ajaran_id($guru_id, $ajaran->id);
			$data_siswa = get_siswa_by_rombel($data_rombel->id);
			?>
			<input type="hidden" name="ajaran_id" value="<?php echo $ajaran->id; ?>" />
			<input type="hidden" name="rombel_id" value="<?php echo $data_rombel->id; ?>" />
			<div class="table-responsive no-padding">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th width="20%">Nama Siswa</th>
							<th width="80%">Deskripsi</th>
						</tr>
					</thead>
					<tbody
					<?php
					if($data_siswa){
						$style = '';
						foreach($data_siswa as $siswa){
						$siswa_id = $siswa->siswa->id;
					?>
					<?php
					$deskripsi_antar_mapel = $this->catatan_wali->find("semester_id = $ajaran->id AND rombongan_belajar_id = $data_rombel->id AND siswa_id = $siswa_id");
					//Catatanwali::find_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran->id,$data_rombel->id,$siswa->id);
					$data_deskripsi = '';
					if($deskripsi_antar_mapel){
						$data_deskripsi .= $deskripsi_antar_mapel->uraian_deskripsi;
					}
					?>
					<tr>
						<td>
							<input type="hidden" name="siswa_id[]" value="<?php echo $siswa->siswa->id; ?>" /> 
							<?php echo $siswa->siswa->nama.'<br />'; ?>
							<?php echo $siswa->siswa->nisn.'<br />'; ?>
							<?php $date = date_create($siswa->siswa->tanggal_lahir);
							echo date_format($date,'d/m/Y'); ?>
						</td>
						<td>
							<textarea name="uraian_deskripsi[]" class="editor" style="width: 100%; height: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $data_deskripsi; ?></textarea>
						</td>
					</tr>
				<?php } } else { 
						$style = ' style="display:none;"';
				?>
					<tr>
						<td colspan="2">Belum ada anggota rombel di kelas <?php echo get_nama_rombel($data_rombel->id); ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
              <!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-success"<?php echo $style; ?>>Simpan</button>
			</div>
            <?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>