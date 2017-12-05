<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title"><?php echo $page_title; ?></h3>
	</div>
    <div class="box-body">
	<div class="callout callout-warning">
		<h4>PERHATIAN!</h4>
		<p>Proses ini akan memakan waktu cukup lama tergantung dari banyaknya row di tabel Rencana Penilaian, KD &amp; Nilai.</p>
		<p>Berhasil memproses <strong><?php echo number_format($inserted,0,',','.'); ?></strong> data, tersisa <strong><?php echo number_format($total_rows,0,',','.'); ?></strong> data.</p>
	</div>
	<!--div class="text-center">
		<?php echo $pagination; ?>
	</div-->
	<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center">mata_pelajaran</th>
					<th class="text-center">rombongan_belajar</th>
					<th class="text-center">nama_penilaian</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('4') + 1;
				foreach($result as $row){
					//test($row);
					//die();
					$status = '';
					$query_mapel = $this->db->get_where('data_mapels', array('id_mapel' => $row->mapel_id))->row();
					$id_mapel_nas = ($query_mapel) ? $query_mapel->id_mapel_nas : 0;
					$query_ref_mapel = $this->mata_pelajaran->find_by_id_nasional($id_mapel_nas);
					$mata_pelajaran_id = ($query_ref_mapel) ? $query_ref_mapel->id : $row->mata_pelajaran_id;
					if(!$query_ref_mapel){
						$query_ref_mapel = $this->mata_pelajaran->get($mata_pelajaran_id);
					}
					$get_kompetensi_dasar = $this->kompetensi_dasar->get($row->kd_id);
					if($get_kompetensi_dasar && $query_ref_mapel && !is_numeric($get_kompetensi_dasar->mata_pelajaran_id)){
						$this->kompetensi_dasar->update($get_kompetensi_dasar->id, array('mata_pelajaran_id' => $query_ref_mapel->id));
					}
					if($query_ref_mapel){
						$insert_rencana_penilaian = array(
							'semester_id'			=> $row->ajaran_id,
							'mata_pelajaran_id'		=> $query_ref_mapel->id,
							'rombongan_belajar_id'	=> $row->rombel_id,
							'kompetensi_id'			=> $row->kompetensi_id,
							'nama_penilaian'		=> $row->nama_penilaian,
							'metode_id'				=> $row->bentuk_penilaian,
							'bobot'					=> $row->bobot_penilaian,
							'keterangan'			=> $row->keterangan_penilaian,
						);
						$find_rencana = $this->rencana_penilaian->find("semester_id = $row->ajaran_id AND mata_pelajaran_id = $query_ref_mapel->id AND rombongan_belajar_id = $row->rombel_id AND kompetensi_id = $row->kompetensi_id AND metode_id = $row->bentuk_penilaian AND nama_penilaian = '$row->nama_penilaian'");
						if($find_rencana){
							$insert_kd_nilai = array(
								'rencana_penilaian_id'	=> $find_rencana->id,
								'kd_id'					=> $row->kd_id,
								'id_kompetensi'			=> $row->kd,
							);
							$status .= 'ignore_rencana:'.$find_rencana->id.'<br />';
							$find_kd_nilai = $this->kd_nilai->find("rencana_penilaian_id = $find_rencana->id AND kd_id = $row->kd_id");
							if($find_kd_nilai){
								$status .= 'ignore_kd_nilai_1:'.$find_kd_nilai->id.'<br />';
								$insert_nilai = array(
									'semester_id'			=> $row->ajaran_id,
									'kompetensi_id'			=> $row->kompetensi_id,
									'rombongan_belajar_id'	=> $row->rombel_id,
									'mata_pelajaran_id'		=> $query_ref_mapel->id,
									'siswa_id'				=> $row->data_siswa_id,
									'rencana_penilaian_id'	=> $find_rencana->id,
									'kompetensi_dasar_id'	=> $row->kd_id,
									'kd_nilai_id'			=> $find_kd_nilai->id,
									'nilai'					=> $row->nilai,
									'rerata'				=> $row->rerata,
									'rerata_jadi'			=> $row->rerata_jadi,
								);
								$find_nilai = $this->nilai->find("semester_id = $row->ajaran_id AND kompetensi_id = $row->kompetensi_id AND rombongan_belajar_id = $row->rombel_id AND mata_pelajaran_id = $query_ref_mapel->id AND siswa_id = $row->data_siswa_id AND rencana_penilaian_id = $find_rencana->id AND kompetensi_dasar_id = $row->kd_id AND kd_nilai_id = $find_kd_nilai->id");
								if(!$find_nilai){
									$id_insert_nilai = $this->nilai->insert($insert_nilai);
									$status .= 'insert_nilai_1:'.$id_insert_nilai.'<br />';
								} else {
									$status .= 'ignore_nilai_1:'.$find_nilai->id.'<br />';
								}
							} else {
								$id_insert_kd_nilai = $this->kd_nilai->insert($insert_kd_nilai);
								$status .= 'insert_kd_nilai_1:'.$id_insert_kd_nilai.'<br />';
								$insert_nilai = array(
									'semester_id'			=> $row->ajaran_id,
									'kompetensi_id'			=> $row->kompetensi_id,
									'rombongan_belajar_id'	=> $row->rombel_id,
									'mata_pelajaran_id'		=> $query_ref_mapel->id,
									'siswa_id'				=> $row->data_siswa_id,
									'rencana_penilaian_id'	=> $find_rencana->id,
									'kompetensi_dasar_id'	=> $row->kd_id,
									'kd_nilai_id'			=> $id_insert_kd_nilai,
									'nilai'					=> $row->nilai,
									'rerata'				=> $row->rerata,
									'rerata_jadi'			=> $row->rerata_jadi,
								);
								$find_nilai = $this->nilai->find("semester_id = $row->ajaran_id AND kompetensi_id = $row->kompetensi_id AND rombongan_belajar_id = $row->rombel_id AND mata_pelajaran_id = $row->id AND siswa_id = $row->data_siswa_id AND rencana_penilaian_id = $find_rencana->id AND kompetensi_dasar_id = $row->kd_id AND kd_nilai_id = $id_insert_kd_nilai");
								if(!$find_nilai){
									$id_insert_nilai = $this->nilai->insert($insert_nilai);
									$status .= 'insert_nilai_2:'.$id_insert_nilai.'<br />';
								} else {
									$status .= 'ignore_nilai_2:'.$find_nilai->id.'<br />';
								}
							}
						} else {
							$insert_rencana = $this->rencana_penilaian->insert($insert_rencana_penilaian);
							$status .= 'insert_rencana:'.$insert_rencana.'<br />';
							$insert_kd_nilai = array(
								'rencana_penilaian_id'	=> $insert_rencana,
								'kd_id'					=> $row->kd_id,
								'id_kompetensi'			=> $row->kd,
							);
							$find_kd_nilai = $this->kd_nilai->find("rencana_penilaian_id = $insert_rencana AND kd_id = $row->kd_id");
							if($find_kd_nilai){
								$status .= 'ignore_kd_nilai_2:'.$find_kd_nilai->id.'<br />';
								$insert_nilai = array(
									'semester_id'			=> $row->ajaran_id,
									'kompetensi_id'			=> $row->kompetensi_id,
									'rombongan_belajar_id'	=> $row->rombel_id,
									'mata_pelajaran_id'		=> $query_ref_mapel->id,
									'siswa_id'				=> $row->data_siswa_id,
									'rencana_penilaian_id'	=> $insert_rencana,
									'kompetensi_dasar_id'	=> $row->kd_id,
									'kd_nilai_id'			=> $find_kd_nilai->id,
									'nilai'					=> $row->nilai,
									'rerata'				=> $row->rerata,
									'rerata_jadi'			=> $row->rerata_jadi,
								);
								$find_nilai = $this->nilai->find("semester_id = $row->ajaran_id AND kompetensi_id = $row->kompetensi_id AND rombongan_belajar_id = $row->rombel_id AND mata_pelajaran_id = $query_ref_mapel->id AND siswa_id = $row->data_siswa_id AND rencana_penilaian_id = $insert_rencana AND kompetensi_dasar_id = $row->kd_id AND kd_nilai_id = $find_kd_nilai->id");
								if(!$find_nilai){
									$id_insert_nilai = $this->nilai->insert($insert_nilai);
									$status .= 'insert_nilai_3:'.$id_insert_nilai.'<br />';
								} else {
									$status .= 'ignore_nilai_3:'.$find_nilai->id.'<br />';
								}
							} else {
								$id_insert_kd_nilai = $this->kd_nilai->insert($insert_kd_nilai);
								$status .= 'insert_kd_nilai_2:'.$id_insert_kd_nilai.'<br />';
								$insert_nilai = array(
									'semester_id'			=> $row->ajaran_id,
									'kompetensi_id'			=> $row->kompetensi_id,
									'rombongan_belajar_id'	=> $row->rombel_id,
									'mata_pelajaran_id'		=> $query_ref_mapel->id,
									'siswa_id'				=> $row->data_siswa_id,
									'rencana_penilaian_id'	=> $insert_rencana,
									'kompetensi_dasar_id'	=> $row->kd_id,
									'kd_nilai_id'			=> $id_insert_kd_nilai,
									'nilai'					=> $row->nilai,
									'rerata'				=> $row->rerata,
									'rerata_jadi'			=> $row->rerata_jadi,
								);
								$find_nilai = $this->nilai->find("semester_id = $row->ajaran_id AND kompetensi_id = $row->kompetensi_id AND rombongan_belajar_id = $row->rombel_id AND mata_pelajaran_id = $row->id AND siswa_id = $row->data_siswa_id AND rencana_penilaian_id = $insert_rencana AND kompetensi_dasar_id = $row->kd_id AND kd_nilai_id = $id_insert_kd_nilai");
								if(!$find_nilai){
									$id_insert_nilai = $this->nilai->insert($insert_nilai);
									$status .= 'insert_nilai_4:'.$id_insert_nilai.'<br />';
								} else {
									$status .= 'ignore_nilai_4:'.$find_nilai->id.'<br />';
								}
							}
						}
						//$this->db->where('id', $row->rencana_penilaian_id);
						//$this->db->delete('rencana_penilaians');
						$this->db->where('id', $row->id_nilai);
						$this->db->delete('nilais');
						//$this->db->where('id', $row->rencana_id);
						//$this->db->delete('rencanas');
					} else {
						$status .= 'no_mapel:'.$row->id_mapel;
					}
			?>
				<tr>
					<td class="text-center" style="vertical-align:middle;"><?php echo $no++; ?></td>
					<td style="vertical-align:middle;"><?php echo get_nama_mapel($mata_pelajaran_id).'('.$mata_pelajaran_id.')'; ?></td>
					<td class="text-center" style="vertical-align:middle;"><?php echo get_nama_rombel($row->rombel_id).'('.$row->rombel_id.')'; ?></td>
					<td>
						rencana_id: <?php echo $row->rencana_id; ?><br />
						rencana_penilaian_id: <?php echo $row->rencana_penilaian_id; ?><br />
						nama_penilaian: <?php echo $row->nama_penilaian; ?>
					</td>
					<td><?php echo $status; ?></td>
				</tr>
			<?php
			//break; 
			} ?>
			</tbody>
		</table>
    </div><!-- /.box-body -->
	<div class="box-footer text-center">
		<?php echo $pagination; ?>
	</div>
</div><!-- /.box -->
</div>
<script>
$(document).ready(function(){
	$('body').mouseover(function(){
		$(this).css({cursor: 'none'});
	});
	$('a').mouseover(function(){
		$(this).css({cursor: 'none'});
	});
	var cari = $('body').find('.next');
	if(cari.length>0){
		var cari_a = $(cari).find('a');
		var url = $(cari_a).attr('href');
		//window.location.replace(url);
		window.location.replace('<?php echo site_url(uri_string());?>');
	} else {
		window.location.replace('<?php echo site_url('admin/dashboard'); ?>');
	}
})
</script>