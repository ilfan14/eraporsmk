<?php
$ajaran = get_ta();
$tahun = $ajaran->tahun;
$smt = $ajaran->semester;
$tahun = substr($tahun, 0,4); // returns "d"
$semester_id = $tahun.$smt;
$tahun_ajaran_id = substr($ajaran->tahun,0,4);?>
<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
	<?php if($extension){?>
	<div class="callout callout-danger lead">Ekstensi <?php echo $extension; ?> belum aktif.<br />
Silahkan aktifkan terlebih dahulu ekstensi tersebut di php.ini kemudian restart Apache!</div>
	<?php }elseif(!$connected){?>
	<div class="callout callout-danger lead">PC/Laptop yang anda gunakan tidak terhubung ke dapodik!</div>
	<?php } else { 
		$sekolah = $this->sekolah->get(1);
		//Datasekolah::first();
		$query_sekolah_dapodik = $this->_database->get_where('sekolah', array('npsn' => $sekolah->npsn));
		$find_dapodik = $query_sekolah_dapodik->row();
		$id_sekolah_dapodik = isset($find_dapodik->sekolah_id) ? $find_dapodik->sekolah_id : NULL;
		if($id_sekolah_dapodik){
	?>
		<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center">Data</th>
					<th class="text-center">Status</th>
					<th class="text-center">Jml Data Dapodik</th>
					<th class="text-center">Jml Data Erapor</th>
					<th class="text-center">Jml Data Sudah Tersinkronisasi</th>
					<th class="text-center">Aksi</th>
	            </tr>
            </thead>
			<tbody>
			<?php
			$sekolah_erapor = $this->sekolah->get_all();
			//Datasekolah::find('all');
			$sekolah_sinkron = $this->sekolah->find_all_by_sekolah_id_dapodik("$id_sekolah_dapodik");
			//Datasekolah::find('all', array('conditions' => "sekolah_id_dapodik = '$id_sekolah_dapodik'"));
			//$sekolah_sinkron = Keahlian::first(array('conditions' => "sekolah_id = $sekolah->id AND ajaran_id = $ajaran->id"));
			$ptk_terdaftar = get_ptk_terdaftar($tahun_ajaran_id, $id_sekolah_dapodik);
			$rombongan_belajar = get_rombongan_belajar($semester_id, $id_sekolah_dapodik);
			$registrasi_peserta_didik = get_registrasi_peserta_didik($semester_id, $id_sekolah_dapodik);
			$pembelajaran_dapodik = $this->dapodik->jumlah_data_pembelajaran($id_sekolah_dapodik);
			//get_pembelajaran_dapodik($semester_id, $id_sekolah_dapodik);
			// ================== erapor =============== //
			$guru_erapor = $this->guru_terdaftar->find_all("semester_id = $ajaran->id AND guru_id IN (SELECT id FROM ref_guru WHERE guru_id_dapodik IS NOT NULL)");
			//GuruAktif::find('all', array('conditions' => "ajaran_id = $ajaran->id",'joins' => array('dataguru')));
			$guru_sinkron = $this->guru_terdaftar->find_all("status = 1 AND semester_id = $ajaran->id AND guru_id IN (SELECT id FROM ref_guru WHERE guru_id_dapodik IS NOT NULL)");
			//GuruAktif::find('all', array('conditions' => "status = 1 AND ajaran_id = $ajaran->id", 'joins' => array('dataguru')));
			$rombel_erapor = $this->rombongan_belajar->find_all_by_semester_id($ajaran->id);
			//Datarombel::find('all', array('conditions' => "ajaran_id = $ajaran->id"));
			$rombel_sinkron = $this->rombongan_belajar->find_all("semester_id = $ajaran->id AND rombel_id_dapodik IS NOT NULL");
			//Datarombel::find('all', array('conditions' => "ajaran_id = $ajaran->id AND rombel_id_dapodik IS NOT NULL"));
			$siswa_erapor = $this->anggota_rombel->find_all_by_semester_id($ajaran->id);
			//AnggotaRombel::find('all', array('conditions' => "ajaran_id = $ajaran->id"/*, 'joins'=> array('datasiswa')*/));
			$siswa_sinkron = $this->anggota_rombel->find_all("semester_id = $ajaran->id AND anggota_rombel_id_dapodik IS NOT NULL");
			//AnggotaRombel::find('all', array('conditions' => "ajaran_id = $ajaran->id AND anggota_rombel_id_dapodik IS NOT NULL"/*, 'joins'=> array('datasiswa')*/));
			$pembelajaran_erapor = $this->pembelajaran->find_all_by_semester_id($ajaran->id);
			//Kurikulum::find('all', array('conditions' => "ajaran_id = $ajaran->id"));
			$pembelajaran_sinkron = $this->pembelajaran->find_all("semester_id = $ajaran->id AND pembelajaran_id IS NOT NULL");
			//Kurikulum::find('all', array('conditions' => "is_dapodik = 1 AND ajaran_id = $ajaran->id"));
			$jurusan_dapodik = 0;
			$jurusan_erapor = 0;
			$jurusan_sinkron = 0;
			$mata_pelajaran_dapodik = 0;
			$mata_pelajaran_erapor = 0;
			$mata_pelajaran_sinkron = 0;
			$data = array(
				0 => 
					array(
						'nama' => 'Sekolah',
						'link' => 'sekolah',
						'get_dapodik' => count($query_sekolah_dapodik->result()),
						'get_erapor' => count($sekolah_erapor),
						'get_sinkron' => count($sekolah_sinkron),
						'class' => 'count_sekolah',
					), 
				1 => 
					array(
						'nama' => 'PTK',
						'link' => 'guru',
						'get_dapodik' => count($ptk_terdaftar),
						'get_erapor' => count($guru_erapor),
						'get_sinkron' => count($guru_sinkron),
						'class' => 'count_guru',
					), 
				2 => 
					array(
						'nama' => 'Rombongan Belajar',
						'link' => 'rombongan_belajar/'.$semester_id,
						'get_dapodik' => count($rombongan_belajar),
						'get_erapor' => count($rombel_erapor),
						'get_sinkron' => count($rombel_sinkron),
						'class' => 'count_rombel',
					), 
				3 => 
					array(
						'nama' => 'Siswa',
						'link' => 'siswa',
						'get_dapodik' => count($registrasi_peserta_didik),
						'get_erapor' => count($siswa_erapor),
						'get_sinkron' => count($siswa_sinkron),
						'class' => 'count_siswa',
					), 
				4 => 
					array(
						'nama' => 'Pembelajaran',
						'link' => 'query_pembelajaran',
						'get_dapodik' => $pembelajaran_dapodik,
						'get_erapor' => count($pembelajaran_erapor),
						'get_sinkron' => count($pembelajaran_sinkron),
						'class' => 'count_pembelajaran',
					),
				/*5 => 
					array(
						'nama' => 'Jurusan',
						'link' => 'jurusan',
						'get_dapodik' => count($jurusan_dapodik),
						'get_erapor' => count($jurusan_erapor),
						'get_sinkron' => count($jurusan_sinkron),
						'class' => 'count_jurusan',
					),
				6 => 
					array(
						'nama' => 'Mata Pelajaran',
						'link' => 'mata_pelajaran',
						'get_dapodik' => count($mata_pelajaran_dapodik),
						'get_erapor' => count($mata_pelajaran_erapor),
						'get_sinkron' => count($mata_pelajaran_sinkron),
						'class' => 'count_pembelajaran',
					),
				7 => 
					array(
						'nama' => 'Referensi Kompetensi Dasar',
						'link' => 'kd',
						'get_dapodik' => 1,
						'get_erapor' => 1,
						'get_sinkron' => 1,
						'class' => 'count_kd',
					),*/
			);
			$i=0;
			foreach($data as $d){
				if($d['get_sinkron']){
					$status = 'Lengkap';
					$btn = 'btn-danger';
					$text = 'Update';
					if($d['get_dapodik'] > $d['get_sinkron']){
						$status = 'Kurang';
						$btn = 'btn-warning';
						$text = 'Sinkron Ulang';
					}
				} else {
					$status = 'Belum';
					$btn = 'btn-success';
					$text = 'Sinkron';
				}
				if($d['link'] == 'mata_pelajaran' || $d['link'] == 'jurusan'){
					$id_sekolah_dapodik = '';
				}
			?>
				<tr>
					<td><?php echo $d['nama']; ?></td>
					<td class="text-center"><?php echo $status; ?></td>
					<td class="text-center"><?php echo $d['get_dapodik']; ?></td>
					<td class="text-center"><?php echo $d['get_erapor']; ?></td>
					<td class="text-center <?php echo $d['class']; ?>"><?php echo $d['get_sinkron']; ?></td>
					<td class="text-center"><a href="<?php echo site_url('admin/sinkronisasi/'.$d['link'].'/'.$id_sekolah_dapodik); ?>" class="<?php echo $d['class']; ?> btn <?php echo $btn; ?> btn-block"><?php echo $text; ?></a></td>
				</tr>
			<?php 
			$i++;
			} ?>
			</tbody>
		</table>
		<div class="progress active" style="display:none;">
			<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
			</div>
		</div>
		<div id="result" class="callout callout-danger lead" style="display:none;"></div>
		<?php } else { ?>
		<div class="callout callout-danger lead">Anda terhubung ke dapodik.<br />Tidak ditemukan NPSN dan Nama Sekolah di Dapodik!<br />Pastikan NPSN dan Nama Sekolah sudah sesuai dengan data dapodik! <a href="<?php echo site_url('admin/profil/sekolah/'); ?>">Klik disini</a> untuk mengupdate NPSN dan Nama Sekolah!</div>
		<?php } ?>
		<?php } ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>
<script>
/*
var index = 0;
var jumlah = 0;
var siswa_dapodik = <?php echo count($siswa_sinkron); ?>;
function DoAjaxProgressSiswa(data,length) {
	$('#spinner').show();
	$.ajax({
		url: '<?php echo site_url('admin/sinkronisasi/proses'); ?>',
		type: 'post',
		data: {length:length,parameter:JSON.stringify(data.result[index]),siswa_dapodik:siswa_dapodik},
		success: function(response){
			var result = $.parseJSON(response);
			$('.progress-bar').css('width', result.persen+'%').attr('aria-valuenow', result.persen);
			$('.count_siswa').html(result.jumlah);
			$('#result').html(result.text);
			jumlah = result.jumlah;
			if(length == jumlah){
				window.location.replace('<?php echo site_url('admin/sinkronisasi'); ?>');
			}
		}
	});
	index++;
}
function DoAjaxProgressCall(data,length){
	setInterval( function() {
		DoAjaxProgressSiswa(data,length);
	}, 500 );
}
$('a.count_siswa').bind('click',function(e) {
	e.preventDefault();
	var url = $(this).attr('href');
	$.get(url).done(function(response) {
		$('.progress').show();
		$('#result').show();
		var data = $.parseJSON(response);
		var length = data.result.length;
		DoAjaxProgressCall(data,length);
	});
	return false;
});
*/
</script>