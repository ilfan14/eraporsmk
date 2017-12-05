<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
<?php
$ajaran = get_ta();
$loggeduser = $this->ion_auth->user()->row();
$find_akses = get_akses($loggeduser->id);
$guru_id = $find_akses['id'][0];
$rombel = $this->rombongan_belajar->find("guru_id = $guru_id AND semester_id = $ajaran->id");
//Datarombel::find_by_guru_id_and_ajaran_id($find_akses['id'], $ajaran->id);
$data_siswa = get_siswa_by_rombel($rombel->id);
$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
echo form_open($form_action,$attributes);
?>
<input type="hidden" name="ajaran_id" value="<?php echo $ajaran->id; ?>" />
<input type="hidden" name="rombel_id" value="<?php echo $rombel->id; ?>" />
<div class="table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="70%">Nama Siswa</th>
				<th width="10%">Sakit</th>
				<th width="10%">Izin</th>
				<th width="10%">Tanpa Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if($data_siswa){
				foreach($data_siswa as $siswa){
				$siswa_id = $siswa->siswa->id;
				$absen = $this->absen->find("semester_id = $ajaran->id AND rombongan_belajar_id = $rombel->id AND siswa_id = $siswa_id");
				//Absen::find_by_ajaran_id_and_rombel_id_and_siswa_id($ajaran->id,$rombel->id,$siswa->id);
			?>
			<tr>
				<td>
					<input type="hidden" name="siswa_id[]" value="<?php echo $siswa->siswa->id; ?>" />
					<?php echo $siswa->siswa->nama; ?>
				</td>
				<td><input type="text" class="form-control" name="sakit[]" value="<?php echo ($absen) ? $absen->sakit: ''; ?>" /></td>
				<td><input type="text" class="form-control" name="izin[]" value="<?php echo ($absen) ? $absen->izin : ''; ?>" /></td>
				<td><input type="text" class="form-control" name="alpa[]" value="<?php echo ($absen) ? $absen->alpa : ''; ?>" /></td>
			</tr>
			<?php
				}
			} else {
			?>
			<tr>
				<td colspan="4" class="text-center">Tidak ada data siswa di rombongan belajar terpilih</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-success">Simpan</button>
			</div>
            <?php echo form_close();  ?>
</div>
</div>
</div>