<?php if($all_siswa){ 
$settings 	= $this->settings->get(1);
?>
<div class="table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="20%">Nama Siswa</th>
				<th width="40%">Deskripsi Pengetahuan</th>
				<th width="40%">Deskripsi Keterampilan</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$result_kd_pengetahuan_tertinggi = '';
			$result_kd_pengetahuan_terendah = '';
			$result_kd_keterampilan_tertinggi = '';
			$result_kd_keterampilan_terendah = '';
			if($settings->desc == 1){
				$result_kd_pengetahuan_tertinggi = 'Belum dilakukan penilaian';
				$result_kd_pengetahuan_terendah = 'Belum dilakukan penilaian';
				$result_kd_keterampilan_tertinggi = 'Belum dilakukan penilaian';
				$result_kd_keterampilan_terendah = 'Belum dilakukan penilaian';
			}
			$kd_pengetahuan_tertinggi 	= 0;
			$kd_pengetahuan_terendah	= 0;	
			$kd_keterampilan_tertinggi 	= 0;
			$kd_keterampilan_terendah	= 0;	
			foreach($all_siswa as $key=>$siswa){
				$siswa_id = $siswa->siswa->id;
				$nilai_pengetahuan = $this->nilai->find_all("semester_id = $ajaran_id and kompetensi_id = 1 and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel and siswa_id = $siswa_id");
				if($nilai_pengetahuan){
					foreach($nilai_pengetahuan as $nilaipengetahuan){
						$rencana_pengetahuan_id[$key] = $nilaipengetahuan->rencana_penilaian_id;
						$get_nilai_pengetahuan[] = $nilaipengetahuan->nilai;
					}
					$bilanganBesar = bilanganBesar($get_nilai_pengetahuan);
					$nilai_pengetahuan_tertinggi = $this->nilai->find("semester_id = $ajaran_id and kompetensi_id = 1 and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel and siswa_id = $siswa_id and nilai = $bilanganBesar");
					if($nilai_pengetahuan_tertinggi){
						$kd_pengetahuan_tertinggi 	= $nilai_pengetahuan_tertinggi->kompetensi_dasar_id;
						$get_kd_pengetahuan_tertinggi = $this->kompetensi_dasar->get($nilai_pengetahuan_tertinggi->kompetensi_id);
						$kd_pengetahuan_tertinggi_value = ($get_kd_pengetahuan_tertinggi) ? $get_kd_pengetahuan_tertinggi->kompetensi_dasar : '';
						if($get_kd_pengetahuan_tertinggi && $get_kd_pengetahuan_tertinggi->kompetensi_dasar_alias){
							$kd_pengetahuan_tertinggi_value = $get_kd_pengetahuan_tertinggi->kompetensi_dasar_alias;
						}
						if($settings->desc == 1){
							$result_kd_pengetahuan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($kd_pengetahuan_tertinggi_value);
						}
					}
					//space tinggi rendah
					$bilanganKecil = bilanganKecil($get_nilai_pengetahuan);
					$nilai_pengetahuan_terendah = $this->nilai->find("semester_id = $ajaran_id and kompetensi_id = 1 and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel and siswa_id = $siswa_id and nilai = $bilanganKecil");
					if($nilai_pengetahuan_terendah){
						$kd_pengetahuan_terendah 	= $nilai_pengetahuan_terendah->kompetensi_dasar_id;
						$get_kd_pengetahuan_terendah = $this->kompetensi_dasar->get($nilai_pengetahuan_terendah->kompetensi_dasar_id);
						$kd_pengetahuan_terendah_value = ($get_kd_pengetahuan_terendah) ? $get_kd_pengetahuan_terendah->kompetensi_dasar : '';
						if($get_kd_pengetahuan_terendah && $get_kd_pengetahuan_terendah->kompetensi_dasar_alias){
							$kd_pengetahuan_terendah_value = $get_kd_pengetahuan_terendah->kompetensi_dasar_alias;
						}
						if($settings->desc == 1){
							$result_kd_pengetahuan_terendah = ' dan perlu meningkatkan kompetensi '.strtolower($kd_pengetahuan_terendah_value);
						}
					}
				}
				$nilai_keterampilan = $this->nilai->find_all("semester_id = $ajaran_id and kompetensi_id = 2 and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel and siswa_id = $siswa_id");
				if($nilai_keterampilan){
					foreach($nilai_keterampilan as $nilaiketerampilan){
						$rencana_keterampilan_id[$key] = $nilaiketerampilan->rencana_penilaian_id;
						$get_nilai_keterampilan[] = $nilaiketerampilan->nilai;
					}
					$bilanganBesar = bilanganBesar($get_nilai_keterampilan);
					$nilai_keterampilan_tertinggi = $this->nilai->find("semester_id = $ajaran_id and kompetensi_id = 2 and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel and siswa_id = $siswa_id and nilai = $bilanganBesar");
					if($nilai_keterampilan_tertinggi){
						$kd_keterampilan_tertinggi 	= $nilai_keterampilan_tertinggi->kompetensi_dasar_id;
						$get_kd_keterampilan_tertinggi = $this->kompetensi_dasar->get($nilai_keterampilan_tertinggi->kompetensi_dasar_id);
						$kd_keterampilan_tertinggi_value = ($get_kd_keterampilan_tertinggi) ? $get_kd_keterampilan_tertinggi->kompetensi_dasar : '';
						if($get_kd_keterampilan_tertinggi && $get_kd_keterampilan_tertinggi->kompetensi_dasar_alias){
							$kd_keterampilan_tertinggi_value = $get_kd_keterampilan_tertinggi->kompetensi_dasar_alias;
						}
						if($settings->desc == 1){
							$result_kd_keterampilan_tertinggi = 'Sangat menonjol pada kompetensi '.strtolower($kd_keterampilan_tertinggi_value);
						}
					}
					//space tinggi rendah
					$bilanganKecil = bilanganKecil($get_nilai_keterampilan);
					$nilai_keterampilan_terendah = $this->nilai->find("semester_id = $ajaran_id and kompetensi_id = 2 and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel and siswa_id = $siswa_id and nilai = $bilanganKecil");
					if($nilai_keterampilan_terendah){
						$kd_keterampilan_terendah 	= $nilai_keterampilan_terendah->kompetensi_dasar_id;
						$get_kd_keterampilan_terendah = $this->kompetensi_dasar->get($nilai_keterampilan_terendah->kompetensi_dasar_id);
						$kd_keterampilan_terendah_value = ($get_kd_keterampilan_terendah) ? $get_kd_keterampilan_terendah->kompetensi_dasar : '';
						if($get_kd_keterampilan_terendah && $get_kd_keterampilan_terendah->kompetensi_dasar_alias){
							$kd_keterampilan_terendah_value = $get_kd_keterampilan_terendah->kompetensi_dasar_alias;
						}
						if($settings->desc == 1){
							$result_kd_keterampilan_terendah = ' dan perlu meningkatkan kompetensi '.strtolower($kd_keterampilan_terendah_value);
						}
					}
				}
				$nilai_pengetahuan_value	= get_nilai_siswa($ajaran_id, 1, $rombel_id, $id_mapel, $siswa->siswa->id);
				$nilai_keterampilan_value	= get_nilai_siswa($ajaran_id, 2, $rombel_id, $id_mapel, $siswa->siswa->id);
				$kkm = get_kkm($ajaran_id, $rombel_id, $id_mapel);
				$class_pengetahuan = ($nilai_pengetahuan_value >= $kkm) ? 'bg-green' : 'bg-red';
				$class_keterampilan = ($nilai_keterampilan_value >= $kkm) ? 'bg-green' : 'bg-red';
				?>
				<input type="hidden" name="siswa_id[]" value="<?php echo $siswa->siswa->id; ?>" />
				<tr>
					<td>
						<div><?php echo $siswa->siswa->nama; ?></div>
						<div class="pull-left">Nilai Akhir : <br />
						<span class="label <?php echo $class_pengetahuan; ?>">P : <?php echo $nilai_pengetahuan_value; ?></span><br />

						<span class="label <?php echo $class_keterampilan; ?>">K : <?php echo $nilai_keterampilan_value; ?></span>
						</div>
						<?php
							$link = 'umum';
							if(isset($mapel->nama_kur) && $mapel->nama_kur == 'k13_mulok' || isset($mapel->nama_kur) && $mapel->nama_kur == 'k_mulok'){
								$link = 'mulok';
							}
						?>
						<?php //echo $kd_pengetahuan_tertinggi.'/'.$kd_pengetahuan_terendah.'/'.$kd_keterampilan_tertinggi.'/'.$kd_keterampilan_terendah;
						?>
						<a href="<?php echo site_url('admin/ajax/get_desc/'.$kd_pengetahuan_tertinggi.'/'.$kd_pengetahuan_terendah.'/'.$kd_keterampilan_tertinggi.'/'.$kd_keterampilan_terendah); ?>" class="btn btn-success pull-right get_desc"><i class="fa fa-lightbulb-o"></i></a>
						<!--get_desc-->
					</td>
				<?php
				$deskripsi_mapel = $this->deskripsi_mata_pelajaran->find("semester_id = $ajaran_id and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $id_mapel and siswa_id = $siswa_id");
				//Deskripsi::find_by_);
					if($deskripsi_mapel){
						$deskripsi_pengetahuan_value = $deskripsi_mapel->deskripsi_pengetahuan;
						$deskripsi_keterampilan_value = $deskripsi_mapel->deskripsi_keterampilan;
					} else {
						//$deskripsi_pengetahuan_value = $result_kd_pengetahuan_tertinggi.$result_kd_pengetahuan_terendah;
						//$deskripsi_keterampilan_value = $result_kd_keterampilan_tertinggi.$result_kd_keterampilan_terendah;
						$deskripsi_pengetahuan_value = '';
						$deskripsi_keterampilan_value = '';
					?>
						<script>
						//$('button.simpan').hide();
						</script>
					<?php
					}
				?>
					<td>
					<textarea name="deskripsi_pengetahuan[]" class="editor" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $deskripsi_pengetahuan_value; ?></textarea>
					</td>
					<td>
					<textarea name="deskripsi_keterampilan[]" class="editor" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $deskripsi_keterampilan_value; ?></textarea>
					</td>
				<?php
			}
			?>
			</tbody>
			</table>
			</div>
		<?php
		} else {
		?>
			<h4>Belum ada siswa di kelas terpilih</h4>
			<script>
			$('button.simpan').remove();
			</script>
		<?php } ?>
<script>
$('a.get_desc').bind('click',function(e) {
	var desc = '';
	e.preventDefault();
	var ini = $(this).parents('tr');
	var url = $(this).attr('href');
	$.get(url).done(function(response) {
		var data = $.parseJSON(response);
		if(data.result.length == 2){
			desc = '<tr><th width="50%">Pengetahuan</th><th width="50%">Keterampilan</th></tr><tr><td class="text-left" style="font-size:14px;">'+data.result[0]+'</td><td class="text-left" style="font-size:14px;">'+data.result[1]+'</td></tr>';
		} else {
			desc = '<tr><td>'+data.result[0]+'</td></tr>';
		}
		swal({
			type: 'info',
			html:
			'<table class="table table-bordered">' +
			desc +
			'</table>',
			width: 800,
			padding: 20,
			showCloseButton: false,
			showCancelButton: false,
			showConfirmButton: false,
		}).done();
	});
});
</script>