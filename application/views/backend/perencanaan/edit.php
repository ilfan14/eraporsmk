<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<!-- form start -->
            <?php
			if($kompetensi_id == 1){
				$action = 'update_perencanaan';
			} else {
				$action = 'update_perencanaan';
			}
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open($form_action.$action,$attributes); 
			$bentuk_penilaian = $this->teknik_penilaian->find_all("kompetensi_id = $kompetensi_id");
			//Metode::find_all_by_ajaran_id_and_kompetensi_id($rencana->ajaran_id, $kompetensi_id);
			?>
			<div class="box-body">
				<div class="form-group">
					<label for="ajaran_id" class="col-sm-2 control-label">Tahun Ajaran</label>
					<div class="col-sm-5">
						<input type="hidden" name="rencana_id" id="rencana_id" value="<?php echo $rencana_penilaian->id; ?>" />
						<input type="hidden" name="kompetensi_id" id="kompetensi_id" value="<?php echo $kompetensi_id; ?>" />
						<select class="select2 form-control" id="ajaran_id" disabled>
							<option value="">== Pilih Tahun Ajaran ==</option>
							<?php foreach($ajarans as $ajaran){?>
							<option value="<?php echo $ajaran->id; ?>"<?php echo ($rencana_penilaian->semester_id == $ajaran->id) ? ' selected="selected"' : ''; ?>><?php echo $ajaran->tahun; ?></option>
							<?php } ?>
						</select>
						<input name="ajaran_id" type="hidden" value="<?php echo $rencana_penilaian->semester_id; ?>" />
					</div>
                </div>
                <div class="form-group">
                  <label for="rombel_id_perencanaan" class="col-sm-2 control-label">Tingkat Kelas</label>
				  <div class="col-sm-5">
                    <select class="select2 form-control" id="rombel_id_perencanaan" disabled>
						<option value="">== Pilih Tingkat Kelas ==</option>
						<?php foreach($rombels as $rombel){?>
						<option value="<?php echo $rombel->id; ?>"<?php echo ($rencana_penilaian->rombongan_belajar_id == $rombel->id) ? ' selected="selected"' : ''; ?>><?php echo $rombel->nama; ?></option>
						<?php } ?>
					</select>
					<input name="rombel_id" type="hidden" value="<?php echo $rencana_penilaian->rombongan_belajar_id; ?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="id_mapel_perencanaan" class="col-sm-2 control-label">Mata Pelajaran</label>
				  <div class="col-sm-5">
                    <select class="select2 form-control" id="id_mapel_perencanaan" disabled>
						<option value="">== Pilih Mata Pelajaran ==</option>
						<?php
						$all_mapel = $this->pembelajaran->find_all_by_rombongan_belajar_id($rencana_penilaian->rombongan_belajar_id);
						//Kurikulum::find_all_by_data_rombel_id($rencana_penilaian->rombel_id);
						if($all_mapel){
							foreach($all_mapel as $mapel){
								//$data_mapel = Datamapel::find_by_id_mapel($mapel->id_mapel);
								?>
								<option value="<?php echo $mapel->id; ?>"<?php echo ($rencana_penilaian->mata_pelajaran_id == $mapel->id) ? ' selected="selected"' : ''; ?>><?php echo get_nama_mapel($mapel->id).' ('.$mapel->id.')'; ?></option>
								<?php
							}
						}
						?>
					</select>
					<input name="id_mapel" type="hidden" value="<?php echo $rencana_penilaian->mata_pelajaran_id; ?>" />
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
			<div class="box-footer table-responsive no-padding">
				<div id="result_kd">
				<?php
				$data_rombel = $this->rombongan_belajar->get($rencana_penilaian->rombongan_belajar_id);
				if($kompetensi_id == 1){
					$aspek = 'P';
				} else {
					$aspek = 'K';
				}
				$all_kd = $this->kompetensi_dasar->find_all("aspek = '$aspek' AND mata_pelajaran_id = $rencana_penilaian->mata_pelajaran_id AND kelas = $data_rombel->tingkat");
			?>
<table class="table table-striped table-bordered" id="clone">
	<thead>
		<tr>
			<th class="text-center" style="min-width:110px">Aktifitas Penilaian</th>
			<th class="text-center" style="min-width:110px;">Teknik</th>
			<th class="text-center" width="10">Bobot</th>
			<?php
			foreach($all_kd as $kd){
			?>
			<th class="text-center"><a href="javascript:void(0)" class="tooltip-top" title="<?php echo $kd->kompetensi_dasar; ?>"><?php echo $kd->id_kompetensi; ?></a></th>
			<?php
			} 
			?>
			<th class="text-center">Keterangan</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<input class="form-control input-sm" type="text" value="<?php echo $rencana_penilaian->nama_penilaian;?>" disabled="disabled" />
				<input type="hidden" name="nama_penilaian" value="<?php echo $rencana_penilaian->nama_penilaian;?>" />
			</td>
			<td>
				<!--select class="form-control input-sm" name="bentuk_penilaian" disabled="disabled"-->
				<select class="form-control input-sm" name="bentuk_penilaian">
					<option value="">- Pilih -</option>
					<?php 
					if($bentuk_penilaian){
						foreach($bentuk_penilaian as $value){ ?>
					<option value="<?php echo $value->id; ?>"<?php echo ($rencana_penilaian->metode_id == $value->id) ? ' selected="selected"' : '';?>><?php echo $value->nama; ?></option>
					<?php } 
					} else {
					?>
					<option value="">Belum ada</option>
					<?php } ?>
				</select>
				<!--input type="hidden" name="bentuk_penilaian" value="<?php echo $rencana_penilaian->metode_id;?>"-->
			</td>
			<td>
				<!--input class="form-control input-sm" type="text" value="<?php echo $rencana_penilaian->bobot; ?>" disabled="disabled"-->
				<input class="form-control input-sm" type="text" value="<?php echo $rencana_penilaian->bobot; ?>" name="bobot_penilaian">
				<!--input type="hidden" name="bobot_penilaian" value="<?php echo $rencana_penilaian->bobot; ?>"-->
			</td>
			<?php
			$i=1;
			foreach($all_kd as $kd){
			$kd_nilai = $this->kd_nilai->find("rencana_penilaian_id = $rencana_penilaian->id AND kd_id = $kd->id");
			$id_nilai_kd = ($kd_nilai) ? $kd_nilai->kd_id : 0;
			?>
			<td style="vertical-align:middle;">
				<!--input type="hidden" name="kd_id_<?php echo $i; ?>" value="<?php echo $kd->id; ?>" /-->
				<div class="text-center"><input type="checkbox" <?php echo ($id_nilai_kd == $kd->id) ? ' checked="checked"' : ''; ?> name="kd[]" value="<?php echo $kd->id; ?>" /></div>
			</td>
			<?php $i++;} ?>
			<td>
				<input class="form-control input-sm" type="text" value="<?php echo $rencana_penilaian->keterangan;?>" disabled="disabled" />
				<input class="form-control input-sm" type="hidden" name="keterangan_penilaian" value="<?php echo $rencana_penilaian->keterangan;?>" />
			</td>
		</tr>
		<?php
		/*<tr>
			<td>
				<input class="form-control input-sm" type="text" value="<?php echo $group->nama_penilaian;?>" disabled="disabled" />
				<input type="hidden" name="nama_penilaian" value="<?php echo $group->nama_penilaian;?>" />
			</td>
			<td>
				<select class="form-control input-sm" disabled="disabled">
					<option value="">- Pilih -</option>
					<?php 
					if($bentuk_penilaian){
						foreach($bentuk_penilaian as $value){ ?>
					<option value="<?php echo $value->id; ?>"<?php echo isset($group->bentuk_penilaian) ? ($group->bentuk_penilaian == $value->id) ? ' selected="selected"' : '' : ''; ?>><?php echo $value->nama_metode; ?></option>
					<?php } 
					} else {
					?>
					<option value="">Belum ada</option>
					<?php } ?>
				</select>
				<input type="hidden" name="bentuk_penilaian" value="<?php echo $group->bentuk_penilaian;?>">
			</td>
			<td>
				<input class="form-control input-sm" type="text" value="<?php echo $group->bobot_penilaian; ?>" disabled="disabled">
				<input type="hidden" name="bobot_penilaian" value="<?php echo $group->bobot_penilaian; ?>">
			</td>
			<?php
			foreach($result as $key=>$kd_result){
				$kd = Kd::find($key);
				$rencana_penilaian = Rencanapenilaian::find_by_rencana_id_and_kompetensi_id_and_nama_penilaian_and_kd_id($rencana->id,$rencana->kompetensi_id,$group->nama_penilaian, $kd->id);
			?>
			<td style="vertical-align:middle;">
				<input type="hidden" name="kd_id_<?php echo $i; ?>" value="<?php echo $kd->id; ?>" />
				<div class="text-center"><input <?php echo (isset($rencana_penilaian->kd_id) && $rencana_penilaian->kd_id == $kd->id) ? 'checked="checked"' : ''; ?> type="checkbox" class="icheck" name="kd_<?php echo $i; ?>" value="<?php echo $kd->id_kompetensi; ?>|<?php echo $kd->id; ?>" /></div>
			</td>
			<?php } ?>
			<td>
				<input class="form-control input-sm" type="text" value="<?php echo $group->keterangan_penilaian;?>" disabled="disabled" />
				<input class="form-control input-sm" type="hidden" name="keterangan_penilaian" value="<?php echo $group->keterangan_penilaian;?>" />
			</td>
			<td class="text-center"><a class="confirm" href="<?php echo site_url('admin/perencanaan/delete_rp/'.$group->id); ?>"><i class="fa fa-trash-o"></i></a></td>
		</tr>
		*/
		?>
	</tbody>
</table>
</div>
<button type="submit" class="btn btn-success simpan">Simpan</button>
</div>
</div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>
<script>
$('a.confirm').bind('click',function(e) {
	var ini = $(this).parents('tr');
	e.preventDefault();
	var url = $(this).attr('href');
	swal({
		title: "Anda Yakin?",
		text: "Tindakan ini tidak bisa dikembalikan!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Hapus!",
		showLoaderOnConfirm: true,
		preConfirm: function() {
			return new Promise(function(resolve) {
				$.get(url)
				.done(function(response) {
					var data = $.parseJSON(response);
					swal({title:data.title, html:data.text, type:data.type}).then(function() {
						ini.remove();
					});
				})
			})
		}
	});
});
</script>