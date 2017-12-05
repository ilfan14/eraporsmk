<?php
$readonly = '';
$disabled = '';
$loggeduser = $this->ion_auth->user()->row();
$ajaran = get_ta();
if($loggeduser->siswa_id || $loggeduser->guru_id){
	$readonly = 'readonly="true"';
	$disabled = 'disabled';
}
?>
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
        <?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
        <div class="box box-primary">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-university"></i> <?php echo $page_title; ?></a></li>
					<li><a href="#tab_2" data-toggle="tab"><i class="fa fa-envelope"></i> Kop Surat</a></li>
					<li><a href="#tab_3" data-toggle="tab"><i class="fa fa-check-square-o"></i> Kata Pengantar Rapor PPK</a></li>
				</ul>
			</div>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<form role="form" method="POST" action="<?php echo site_url('admin/profil/update_sekolah'); ?>" enctype="multipart/form-data">
						<div class="box-body">
							<div class="row">
							<div class="form-group col-xs-12"><h4><b>Data Sekolah</b></h4><hr></div>
							<div class="col-xs-6">
							<div class="form-group col-xs-12">
								<label>Nama Sekolah</label>
								<input type="hidden" name="sekolah_id" value="<?php echo $sekolah->id; ?>" />
								<input type="text" class="form-control" name="nama_sekolah" value="<?php echo (isset($sekolah->nama)) ? $sekolah->nama: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>NPSN</label>
								<input type="text" class="form-control" name="npsn_sekolah" value="<?php echo (isset($sekolah->npsn)) ? $sekolah->npsn: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Alamat</label>
								<input type="text" class="form-control" name="alamat_sekolah" value="<?php echo (isset($sekolah->alamat)) ? $sekolah->alamat: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Desa/Kelurahan</label>
								<input type="text" class="form-control" name="desa_kelurahan_sekolah" value="<?php echo (isset($sekolah->desa_kelurahan)) ? $sekolah->desa_kelurahan: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Kecamatan</label>
								<input type="text" class="form-control" name="kecamatan_sekolah" value="<?php echo (isset($sekolah->kecamatan)) ? $sekolah->kecamatan: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Kabupaten/Kota</label>
								<input type="text" class="form-control" name="kabupaten_sekolah" value="<?php echo (isset($sekolah->kabupaten)) ? $sekolah->kabupaten: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Provinsi</label>
								<input type="text" class="form-control" name="provinsi_sekolah" value="<?php echo (isset($sekolah->provinsi)) ? $sekolah->provinsi: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Kode Pos</label>
								<input type="text" class="form-control" name="kodepos_sekolah" value="<?php echo (isset($sekolah->kode_pos)) ? $sekolah->kode_pos: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Telepon</label>
								<input type="text" class="form-control" name="telp_sekolah" value="<?php echo (isset($sekolah->no_telp)) ? $sekolah->no_telp: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Fax</label>
								<input type="text" class="form-control" name="fax_sekolah" value="<?php echo (isset($sekolah->no_fax)) ? $sekolah->no_fax: ''; ?>" <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Email</label>
								<input type="text" class="form-control" name="email_sekolah" value="<?php echo (isset($sekolah->email)) ? $sekolah->email: ''; ?>" required <?php echo $readonly; ?>/>
							</div>
							<div class="form-group col-xs-12">
								<label>Website</label>
								<input type="text" class="form-control" name="website_sekolah" value="<?php echo (isset($sekolah->website)) ? $sekolah->website: ''; ?>" <?php echo $readonly; ?>/>
							</div>
							</div>
							<div class="col-xs-6">
								<input type="hidden" class="form-control" name="lintang_sekolah" value="<?php echo (isset($sekolah->lintang)) ? $sekolah->lintang: '1'; ?>" required <?php echo $readonly; ?>/>
								<input type="hidden" class="form-control" name="bujur_sekolah" value="<?php echo (isset($sekolah->bujur)) ? $sekolah->bujur: '1'; ?>" required <?php echo $readonly; ?>/>
							 <div class="form-group col-xs-12">
								<label>Kompetensi Keahlian</label>
								<select class="select2 form-control" name="kompetensi_keahlian[]" multiple="multiple" required <?php echo $disabled; ?>>
								<?php 
								//$keahlian = Keahlian::all();
								$jurusan_sp = $this->jurusan_sp->find_all("sekolah_id = $loggeduser->sekolah_id");
								if($jurusan_sp){
									foreach($jurusan_sp as $jurusan){
										$ahli_id[] = $jurusan->kurikulum_id;
									}
								}
								if(isset($ahli_id)){
									$ahli_id = $ahli_id;
								} else {
									$ahli_id = array();
								}
								//$get_jurusan = $this->bidang_keahlian->get_all();
								$get_jurusan = $this->jurusan->find_all_by_level_bidang_id(11);
								//$this->ref_jurusan->get_all();
								foreach($get_jurusan as $jurusan){
								?>
								<optgroup label="<?php echo $jurusan->nama_jurusan; ?>" id="<?php echo $jurusan->jurusan_id ?>">
								<?php
								$get_level_12 = $this->jurusan->find_all_by_jurusan_induk($jurusan->jurusan_id);
								if($get_level_12){
								?>
										<option value="<?php echo $jurusan->jurusan_id ?>"<?php echo (isset($jurusan_sp) && in_array($jurusan->jurusan_id, $ahli_id)) ? ' selected="selected"' : ''; ?>><?php echo $jurusan->nama_jurusan; ?></option>
								<?php
									foreach($get_level_12 as $level_12){
									?>
										<option value="<?php echo $level_12->jurusan_id ?>"<?php echo (isset($jurusan_sp) && in_array($level_12->jurusan_id, $ahli_id)) ? ' selected="selected"' : ''; ?>><?php echo $level_12->nama_jurusan; ?></option>
									<?php } 
								} else { ?>
										<!--option value="">Kompetensi Keahlian Tidak Ditemukan</option-->
										<option value="<?php echo $jurusan->jurusan_id ?>"<?php echo (isset($jurusan_sp) && in_array($jurusan->jurusan_id, $ahli_id)) ? ' selected="selected"' : ''; ?>><?php echo $jurusan->nama_jurusan; ?></option>
								<?php } ?>
								</optgroup>
								<?php } ?>
								</select>
							</div>
								<div class="form-group col-xs-12">
									<label>Nama Kepala Sekolah</label>
									<!--input type="text" class="form-control" name="kepsek" value="<?php echo (isset($settings->kepsek)) ? $settings->kepsek : ''; ?>" required <?php echo $readonly; ?>/-->
									<?php
									$all_guru = $this->guru->get_all();
									$extra = 'class="select2 form-control required" id="guru_id" '.$disabled;
									$data_guru[''] = '== Pilih Kepala Sekolah ==';
									foreach($all_guru as $guru){
										$data_guru[$guru->id] = get_nama_guru($guru->id);
									}
									echo form_dropdown('guru_id', $data_guru, $guru_id, $extra) 
									?>
								</div>
								<!--div class="form-group col-xs-12">
									<label>NIP Kepala Sekolah</label>
									<input type="text" class="form-control" name="nip_kepsek" value="<?php echo (isset($settings->nip_kepsek)) ? $settings->nip_kepsek : ''; ?>" <?php echo $readonly; ?>/>
								</div-->
								<div class="form-group col-xs-12">
								 <p><img src="<?php echo (isset($sekolah->logo_sekolah) && $sekolah->logo_sekolah != '') ? base_url().PROFILEPHOTOSTHUMBS.$sekolah->logo_sekolah: base_url().'assets/img/300.gif'; ?>" class="img-responsive thumbnail center-block" alt="Responsive image"/></p>
								<label>Ganti Logo Sekolah</label>
								<input type="file" name="profilephoto" />
								</div>
								</div>
							</div>
						</div><!-- /.box-body -->
		
						<div class="box-footer clearfix">
							<button type="submit" class="btn btn-primary pull-right" <?php echo $disabled; ?>>Simpan</button>
						</div>
					</form>
				</div>
				<div class="tab-pane" id="tab_2">
					<?php
					$kop_surat = $sekolah->kop_surat;
					$pengantar_ppk = $sekolah->pengantar_ppk;
					?>
					<form method="POST" action="<?php echo site_url('admin/profil/kop_surat'); ?>">
					<input type="hidden" name="sekolah_id" value="<?php echo $sekolah->id; ?>" />
						<div class="box-body">
							<div class="form-group">
									<textarea class="editor form-control" name="kop_surat" style="width: 100%; height: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $kop_surat; ?></textarea>
								</div>
						</div>
						<div class="box-footer clearfix">
							<button type="submit" class="btn btn-primary pull-right" <?php echo $disabled; ?>>Simpan</button>
						</div>
					</form>
				</div>
				<div class="tab-pane" id="tab_3">
					<form method="POST" action="<?php echo site_url('admin/profil/pengantar_ppk'); ?>">
					<input type="hidden" name="sekolah_id" value="<?php echo $sekolah->id; ?>" />
						<div class="box-body">
							<div class="form-group">
									<textarea class="editor form-control" name="pengantar_ppk" style="width: 100%; height: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $pengantar_ppk; ?></textarea>
								</div>
						</div>
						<div class="box-footer clearfix">
							<button type="submit" class="btn btn-primary pull-right" <?php echo $disabled; ?>>Simpan</button>
						</div>
					</form>
				</div>
			</div><!-- /.box -->
		</div><!--/.col (left) -->
	</div>
</div>