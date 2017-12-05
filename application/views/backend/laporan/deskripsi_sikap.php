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
			$find_akses = get_akses($loggeduser->id);
			$guru_id = $find_akses['id'][0];
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open($form_action,$attributes);
			$ajaran = get_ta();
			$loggeduser = $this->ion_auth->user()->row();
			$data_rombel = $this->rombongan_belajar->find("guru_id = $guru_id AND semester_id = $ajaran->id");
			//Datarombel::find_by_guru_id_and_ajaran_id($find_akses['id'], $ajaran->id);
			$data_siswa = get_siswa_by_rombel($data_rombel->id);
			?>
			<input type="hidden" name="ajaran_id" value="<?php echo $ajaran->id; ?>" />
			<input type="hidden" name="rombel_id" value="<?php echo $data_rombel->id; ?>" />
			<div class="table-responsive no-padding">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th width="15%">Nama Siswa</th>
							<th width="25%">Catatan Sikap</th>
							<th width="30%">Deskripsi Sikap Spiritual</th>
							<th width="30%">Deskripsi Sikap Sosial</th>
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
					$deskripsi_sikap = $this->deskripsi_sikap->find("semester_id = $ajaran->id AND rombongan_belajar_id = $data_rombel->id AND siswa_id = $siswa_id");
					if($deskripsi_sikap){
						$uraian_deskripsi_spiritual = $deskripsi_sikap->uraian_deskripsi_spiritual;
						$uraian_deskripsi_sosial 	= $deskripsi_sikap->uraian_deskripsi_sosial;
					} else {
						$uraian_deskripsi_spiritual = '';
						$uraian_deskripsi_sosial 	= '';
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
						<?php
						$all_sikap = $this->nilai_sikap->find_all("semester_id = $ajaran->id AND rombongan_belajar_id = $data_rombel->id AND siswa_id = $siswa_id");
						//Sikap::find('all', array('conditions' => "ajaran_id = $ajaran->id AND rombel_id = $data_rombel->id AND siswa_id = $siswa->id", 'group' => 'mapel_id',));
						//$all_sikap = Sikap::find_all_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran->id,$data_rombel->id,$siswa->id);
						if($all_sikap){
							foreach($all_sikap as $sikap){
								$a[$sikap->mata_pelajaran_id][] = butir_sikap($sikap->butir_sikap).' = '.$sikap->uraian_sikap.' ('.opsi_sikap($sikap->opsi_sikap,1).')';
								$ajaran_id[$sikap->mata_pelajaran_id] = $ajaran->id;
								$rombel_id[$sikap->mata_pelajaran_id] = $data_rombel->id;
							}
							//test($a);
							foreach($a as $b=>$c){
								echo 'Guru Mata Pelajaran : '.get_guru_mapel($ajaran_id[$b],$rombel_id[$b],$b, 'nama').'<br />';
								echo '<ul>';
								foreach($c as $d){
									echo '<li>'.$d.'</li>';
								}
								echo '</ul>';
							}
						} else {
							echo 'Tidak ada catatan sikap berdasarkan observasi guru';
						}
						?>
						</td>
						<td>
							<textarea name="uraian_deskripsi_spiritual[]" class="editor1" style="width: 100%; height: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" rows="8"><?php echo $uraian_deskripsi_spiritual; ?></textarea>
						</td>
						<td>
							<textarea name="uraian_deskripsi_sosial[]" class="editor1" style="width: 100%; height: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" rows="8"><?php echo $uraian_deskripsi_sosial; ?></textarea>
						</td>
					</tr>
				<?php } } else { 
						$style = ' style="display:none;"';
				?>
					<tr>
						<td colspan="3">Belum ada anggota rombel di kelas <?php echo get_nama_rombel($data_rombel->id); ?></td>
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