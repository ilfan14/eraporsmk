<?php
$ajaran = get_ta();
$siswa_id = $user->siswa_id;
$siswa = $this->siswa->get($siswa_id);
$anggota_rombel = $this->anggota_rombel->find("semester_id = $ajaran->id AND siswa_id = $siswa->id");
//Anggotarombel::find_by_ajaran_id_and_datasiswa_id($ajaran->id, $siswa_id);
//$nama_siswa = isset($siswa->nama) ? $siswa->nama : '-';
//$rombel_id = isset($anggota_rombel->rombel_id) ? $anggota_rombel->rombel_id : 0;
//$rombel = Datarombel::find_by_id($rombel_id);
//$mata_pelajaran = Kurikulum::find_all_by_ajaran_id_and_data_rombel_id($ajaran->id,$rombel_id);
$mata_pelajaran = $this->pembelajaran->find_all("rombongan_belajar_id = $anggota_rombel->rombongan_belajar_id AND semester_id = $ajaran->id");
foreach($mata_pelajaran as $allmapel){
	$get_id_mapel[] = $allmapel->mata_pelajaran_id;
}
if(isset($get_id_mapel)){
//$mata_pelajaran = filter_agama_mapel($ajaran->id, $anggota_rombel->rombongan_belajar_id, $get_id_mapel, $get_id_mapel, $siswa->agama);
$mata_pelajaran = filter_agama_mapel($ajaran->id, $get_id_mapel, $get_id_mapel, $siswa->agama);
}
?>
<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<!-- form start -->
		<div class="col-sm-5">
		<table class="table table-bordered table-hover">
			<tr>
				<td width="45%">Nama Siswa</td>
				<td width="5%" class="text-center">:</td>
				<td width="50%"><?php echo get_nama_siswa($siswa->id); ?></td>
			</tr>
			<tr>
				<td>Kelas</td>
				<td width="5%" class="text-center">:</td>
				<td width="50%"><?php echo get_nama_rombel($anggota_rombel->rombongan_belajar_id); ?></td>
			</tr>
		</table>
		</div>
		<div class="clearfix"></div>
			<div class="form-group" style="margin-top:10px;">
				<label for="ajaran_id" class="col-sm-2 control-label">Mata Pelajaran</label>
				<div class="col-sm-5">
					<form>
					<input type="hidden" name="query" id="query" value="analisis_individu/<?php echo $siswa_id; ?>" />
					<select name="id_mapel" class="select2 form-control" id="mapel">
						<option value="">== Pilih Mata Pelajaran ==</option>
						<?php 
						if($mata_pelajaran){
							foreach($mata_pelajaran as $mapel){
								//$get_mapel = Kurikulum::find_by_id_mapel($mapel);
						?>
						<option value="<?php echo $mapel; ?>"><?php echo get_nama_mapel($mapel); ?></option>
						<?php
							} 
						}
						?>
					</select>
					</form>
                  </div>
                </div>
			</div>
              <!-- /.box-body -->
			<div class="box-footer">
				<div id="result" style="margin-top:20px;"></div>
			</div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>