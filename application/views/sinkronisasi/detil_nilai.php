<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
        <?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th style="width: 5%" class="text-center" rowspan="2" style="vertical-align:middle;">No.</th>
				<th style="width: 35%" rowspan="2" style="vertical-align:middle;">Mata Pelajaran</th>
                <th style="width: 10%" class="text-center" rowspan="2">Jml Siswa</th>
                <th style="width: 10%" class="text-center" colspan="2">Siswa Telah Dinilai</th>
                <th style="width: 5%" class="text-center" rowspan="2" style="vertical-align:middle;">KKM</th>
				<th style="width: 5%" class="text-center" rowspan="2" style="vertical-align:middle;">Aksi</th>
            </tr>
				<th style="width: 5%">Pengetahuan</th>
				<th style="width: 5%">Keterampilan</th>
			<tr>
			</tr>
            </thead>
			<tbody>
			<?php
			if($pembelajaran){
				$data_siswa = get_siswa_by_rombel($rombel_id);
				$i=1;
				foreach($pembelajaran as $belajar){
					$kkm = get_kkm($ajaran_id, $belajar->rombongan_belajar_id, $belajar->mata_pelajaran_id);
					$get_mapel = $this->mata_pelajaran->get($belajar->mata_pelajaran_id);
					$get_id_mapel_dapodik = ($get_mapel) ? $get_mapel->id_nasional : 0;
					$title_pengetahuan = '<table width=100%>';
					$title_pengetahuan .= '<tr><td>Nama Siswa</td><td>Nilai</td></tr>';
					$find_nilai_pengetahuan = 0;
					foreach($data_siswa as $siswa){
						$nilai_pengetahuan = get_nilai_siswa($ajaran_id, 1, $belajar->rombongan_belajar_id, $belajar->mata_pelajaran_id, $siswa->siswa->id);
						if($nilai_pengetahuan){
							$find_nilai_pengetahuan++;
						}
						$title_pengetahuan .= '<tr>';
						$title_pengetahuan .= '<td>'.get_nama_siswa($siswa->siswa->id).'</td>';
						$title_pengetahuan .= '<td>'.$nilai_pengetahuan.'</td>';
						$title_pengetahuan .= '</tr>';
					}
					$title_pengetahuan .= '</table>';
					$title_keterampilan = '<table width=100%>';
					$title_keterampilan .= '<tr><td>Nama Siswa</td><td>Nilai</td></tr>';
					$find_nilai_keterampilan = 0;
					foreach($data_siswa as $siswa){
						$nilai_keterampilan = get_nilai_siswa($ajaran_id, 2, $belajar->rombongan_belajar_id, $belajar->mata_pelajaran_id, $siswa->siswa->id);
						if($nilai_keterampilan){
							$find_nilai_keterampilan++;
						}
						$title_keterampilan .= '<tr>';
						$title_keterampilan .= '<td>'.get_nama_siswa($siswa->siswa->id).'</td>';
						$title_keterampilan .= '<td>'.$nilai_keterampilan.'</td>';
						$title_keterampilan .= '</tr>';
					}
					$title_keterampilan .= '</table>';
				?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td>
						<?php echo get_nama_mapel($belajar->mata_pelajaran_id); ?><br />
						Guru Mapel : <?php echo get_nama_guru($belajar->guru_id); ?>
					</td>
					<td class="text-center"><?php echo count($data_siswa); ?></td>
					<td class="text-center tooltip-nilai"><a title="<?php echo $title_pengetahuan; ?>" data-html="true" rel="tooltip" href="javascript:void(0)" data-placement="left"><?php echo $find_nilai_pengetahuan; ?></a></td>
					<td class="text-center tooltip-nilai"><a title="<?php echo $title_keterampilan; ?>" data-html="true" rel="tooltip" href="javascript:void(0)" data-placement="left"><?php echo $find_nilai_keterampilan; ?></a></td>
					<td class="text-center"><?php echo $kkm; ?></td>
					<td class="text-center"><?php echo '<a href="'.site_url('admin/kirim-nilai/proses/'.$belajar->mata_pelajaran_id.'/'.$get_id_mapel_dapodik.'/'.$belajar->rombongan_belajar_id.'/'.$rombel_id_dapodik.'/'.$updater_id.'/'.$kkm.'/'.$i).'" class="proses_kirim btn btn-success btn-sm btn-block"><i class="fa fa-cloud-upload"></i> Proses Kirim</a>'; ?></td>
				</tr>
			<?php
			$i++;
			}
			}
			/*if($pembelajaran->kurikulum){
				$data_siswa = get_siswa_by_rombel($rombel_id);
				$i=1;
				$attr_nilai = array('group'=>'data_siswa_id');
				foreach($pembelajaran->kurikulum as $belajar){
					$kkm = get_kkm($ajaran_id, $belajar->data_rombel_id, $belajar->id_mapel);
					$get_id_mapel_dapodik = get_id_mapel_dapodik($belajar->id_mapel,$belajar->keahlian_id);
					$find_nilai_pengetahuan = Nilai::find('all', array("conditions" => "ajaran_id =  $ajaran_id AND  kompetensi_id = 1 AND rombel_id = $belajar->data_rombel_id AND mapel_id = '$belajar->id_mapel' AND rerata != 0", 'group'=>'data_siswa_id'));
					if($find_nilai_pengetahuan){
						$title_pengetahuan = '<table width=100%>';
						$title_pengetahuan .= '<tr><td>Nama Siswa</td><td>Nilai</td></tr>';
						foreach($find_nilai_pengetahuan as $nilai_pengetahuan){
							$title_pengetahuan .= '<tr>';
							$title_pengetahuan .= '<td>'.get_nama_siswa($nilai_pengetahuan->data_siswa_id).'</td>';
							$title_pengetahuan .= '<td>'.get_nilai_siswa($ajaran_id, 1, $nilai_pengetahuan->rombel_id, $nilai_pengetahuan->mapel_id, $nilai_pengetahuan->data_siswa_id).'</td>';
							$title_pengetahuan .= '</tr>';
						}
						$title_pengetahuan .= '</table>';
					} else {
						$title_pengetahuan = '<h3>Belum melakukan penilaian</h3>';
					}
					$find_nilai_keterampilan = Nilai::find('all', array("conditions" => "ajaran_id =  $ajaran_id AND  kompetensi_id = 2 AND rombel_id = $belajar->data_rombel_id AND mapel_id = '$belajar->id_mapel' AND rerata != 0", 'group'=>'data_siswa_id'));
					if($find_nilai_keterampilan){
						$title_keterampilan = '<table width=100%>';
						$title_keterampilan .= '<tr><td>Nama Siswa</td><td>Nilai</td></tr>';
						foreach($find_nilai_keterampilan as $nilai_keterampilan){
							$title_keterampilan .= '<tr>';
							$title_keterampilan .= '<td>'.get_nama_siswa($nilai_keterampilan->data_siswa_id).'</td>';
							$title_keterampilan .= '<td>'.get_nilai_siswa($ajaran_id, 2, $nilai_keterampilan->rombel_id, $nilai_keterampilan->mapel_id, $nilai_keterampilan->data_siswa_id).'</td>';
							$title_keterampilan .= '</tr>';
						}
						$title_keterampilan .= '</table>';
					} else {
						$title_keterampilan = '<h3>Belum melakukan penilaian</h3>';
					}
				?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td>
						<?php echo get_nama_mapel($ajaran_id, $belajar->data_rombel_id, $belajar->id_mapel); ?><br />
						Guru Mapel : <?php echo get_nama_guru($belajar->guru_id); ?>
					</td>
					<td class="text-center"><?php echo count($data_siswa); ?></td>
					<td class="text-center tooltip-nilai"><a title="<?php echo $title_pengetahuan; ?>" data-html="true" rel="tooltip" href="javascript:void(0)" data-placement="left"><?php echo count($find_nilai_pengetahuan); ?></a></td>
					<td class="text-center tooltip-nilai"><a title="<?php echo $title_keterampilan; ?>" data-html="true" rel="tooltip" href="javascript:void(0)" data-placement="left"><?php echo count($find_nilai_keterampilan); ?></a></td>
					<td class="text-center"><?php echo $kkm; ?></td>
					<td class="text-center"><?php echo '<a href="'.site_url('admin/kirim-nilai/proses/'.$belajar->id_mapel.'/'.$get_id_mapel_dapodik.'/'.$belajar->data_rombel_id.'/'.$rombel_id_dapodik.'/'.$updater_id.'/'.$kkm.'/'.$i).'" class="proses_kirim btn btn-success btn-sm btn-block"><i class="fa fa-cloud-upload"></i> Proses Kirim</a>'; ?></td>
				</tr>
			<?php
				$i++;
				}
			}*/
				?>
			</tbody>     
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>
<script>
$('.tooltip-nilai').tooltip({
  selector: "a[rel=tooltip]"
})
$('a.proses_kirim').bind('click',function(e) {
	e.preventDefault();
	var url = $(this).attr('href');
	$.get(url, function(response) {
		var data = $.parseJSON(response);
		swal({
			title:data.title, 
			html:
				data.info +
				'<table class="table table-striped table-hover">' +
				'<tr><th class="pull-left">Informasi</th><th class="text-center">Jumlah Data</th></tr>' +
				'<tr><td class="pull-left">Nilai berhasil dikirim</td><td class="text-center">' + data.insert_rapor + '</td></tr>' +
				'<tr><td class="pull-left">Nilai berhasil diperbaharui</td><td class="text-center">' + data.update_rapor + '</td></tr>' +
				'<tr><td class="pull-left">Nilai gagal dikirim</td><td class="text-center">' + data.gagal_rapor + '</td></tr>' +
				'</table>',
			type:data.type
		}).done();
	});
	//swal({title:"Sukses", text:"KKM berhasil ditambahkan", type:"success"}).done();
});
</script>