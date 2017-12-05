<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title"><?php echo $page_title.' ('.$total_rows.' => '.$inserted. ')'; ?>. Belum masuk referensi = <?php echo $total_rows - $inserted; ?></h3>
	</div>
    <div class="box-body">
	<div class="text-center">
		<?php echo $pagination; ?>
	</div>
	<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center">nama_mata_pelajaran</th>
					<th class="text-center">id_kompetensi</th>
					<th class="text-center">aspek</th>
					<th class="text-center">kelas</th>
					<th class="text-center">status</th>
					<th class="text-center">kompetensi_dasar</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('4') + 1;
				foreach($dapodik as $data){
					//test($data);
					/*$this->_panongan->get_where('data_mapels', array('id_mapel' => $data->mata_pelajaran_id));
					$this->_panongan->join('ref.mst_wilayah', 'ref.mst_wilayah.kode_wilayah = sekolah.kode_wilayah');*/
					$this->_panongan->select('*,matpel_komps.kurikulum_id');
					$this->_panongan->from('data_mapels');
					$this->_panongan->join('matpel_komps', 'matpel_komps.id_mapel = data_mapels.id_mapel');
					$this->_panongan->where('matpel_komps.id_mapel', $data->mata_pelajaran_id);
					$this->_panongan->or_where('matpel_komps.id_mapel', $data->mata_pelajaran_id);
					$query = $this->_panongan->get();
					$get_mapel = $query->row();
					if(!$get_mapel){
						$query = $this->_panongan->get_where('data_mapels', array('id_mapel' => $data->mata_pelajaran_id));
						$get_mapel = $query->row();
					}
					$id_mapel_nas = ($get_mapel) ? $get_mapel->id_mapel_nas : 0;
					$get_mata_pelajaran_id = $this->mata_pelajaran->find_by_id_nasional($id_mapel_nas);
					$mata_pelajaran_id = ($get_mata_pelajaran_id) ? $get_mata_pelajaran_id->id : 0;
					$tampil = get_nama_mapel($data->mata_pelajaran_id);
					$status = '';
					$kompetensi_dasar = str_replace($data->id_kompetensi,'',$data->kompetensi_dasar);
					$kompetensi_dasar = str_replace('.  ','',$kompetensi_dasar);
					$kompetensi_dasar = trim($kompetensi_dasar);
					if($mata_pelajaran_id && $tampil == '-'){
						$tampil = $data->mata_pelajaran_id.'=>'.$id_mapel_nas.'=>'.$mata_pelajaran_id.'=>'.get_nama_mapel($mata_pelajaran_id);
						$status .= 'update_kd<br />';
						$this->kompetensi_dasar->update($data->id, array('mata_pelajaran_id' => $mata_pelajaran_id, 'kompetensi_dasar' => trim($kompetensi_dasar)));
					} else {
						if($get_mapel){
							$kur = 2006;
							if(is_numeric($get_mapel->kur)){
								$kur = 2013;
							}
							$kelompok_id = substr($get_mapel->id_mapel, 0, 2);
							$get_kelompok = $this->kelompok->find("nama_kelompok LIKE '%$kelompok_id%' AND kurikulum = $kur");
							$kelompok_id = ($get_kelompok) ? $get_kelompok->id : 0;
							if(isset($get_mapel->kelas_X)){
								$kelas = array(10 => $get_mapel->kelas_X, 11 => $get_mapel->kelas_XI, 12 => $get_mapel->kelas_XII, 13 => $get_mapel->kelas_XIII);
							} else {
								$kelas = array(10 => $get_mapel->k1, 11 => $get_mapel->k2, 12 => $get_mapel->k3, 13 => 0);
							}
							$kelas = array_filter($kelas);
							if($get_mata_pelajaran_id){
								foreach($kelas as $key => $value){
									$data_mapel_kur = array(
										'kurikulum_id'				=> $get_mata_pelajaran_id->kurikulum_id,
										'mata_pelajaran_id'			=> $get_mata_pelajaran_id->id,
										'tingkat_pendidikan_id'		=> $key,
										'a_peminatan'				=> 0,
										'area_kompetensi'			=> 0,
										'kelompok_id'				=> $kelompok_id
									);
									$mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->find("kurikulum_id = $get_mata_pelajaran_id->kurikulum_id AND mata_pelajaran_id = $get_mata_pelajaran_id->id AND tingkat_pendidikan_id = $key");
									if($mata_pelajaran_kurikulum){
										$status .= 'update_matpel_kur_1<br />';
										$this->mata_pelajaran_kurikulum->update($mata_pelajaran_kurikulum->id, $data_mapel_kur);
									} else {
										$status .= 'insert_matpel_kur_1<br />';
										$this->mata_pelajaran_kurikulum->insert($data_mapel_kur);
									}
								}
							} else {
								//die();
								$data_mapel = array(
									'id_nasional'			=> $get_mapel->id_mapel_nas,
									'nama_mata_pelajaran'	=> $get_mapel->nama_mapel,
								);
								$insert_mata_pelajaran = $this->mata_pelajaran->insert($data_mapel);
								//test($get_mapel);
								//die();
								if($insert_mata_pelajaran){
									$status .= 'insert_mata_pelajaran<br />';
									foreach($kelas as $key => $value){
										$data_mapel_kur = array(
											'kurikulum_id'				=> $get_mapel->kurikulum_id,
											'mata_pelajaran_id'			=> $insert_mata_pelajaran,
											'tingkat_pendidikan_id'		=> $key,
											'a_peminatan'				=> 0,
											'area_kompetensi'			=> 0,
											'kelompok_id'				=> $kelompok_id
										);
										$mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->find("kurikulum_id = $get_mapel->kurikulum_id AND mata_pelajaran_id = $insert_mata_pelajaran AND tingkat_pendidikan_id = $key");
										if($mata_pelajaran_kurikulum){
											$status .= 'update_matpel_kur_2<br />';
											$this->mata_pelajaran_kurikulum->update($mata_pelajaran_kurikulum->id, $data_mapel_kur);
										} else {
											$status .= 'insert_matpel_kur_2<br />';
											$this->mata_pelajaran_kurikulum->insert($data_mapel_kur);
										}
									}
								}
							}
						} else {
							$status .= $data->mata_pelajaran_id.'<br />';
							$this->kompetensi_dasar->delete($data->id);
						}
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?>|<?php echo $data->id; ?></td>
					<td><?php echo $tampil; ?></td>
					<td><?php echo $data->id_kompetensi; ?></td>
					<td><?php echo $data->aspek; ?></td>
					<td><?php echo $data->kelas; ?></td>
					<td><?php echo $status; ?></td>
					<td><?php echo $kompetensi_dasar; ?></td>
				</tr>
			<?php 
			//break;
			} ?>
			</tbody>
		</table>
		<?php //test($data); ?>
    </div><!-- /.box-body -->
	<div class="box-footer text-center">
		<?php echo $pagination; ?>
	</div>
</div><!-- /.box -->
</div>
<script>
$(document).ready(function(){
	var cari = $('body').find('.next');
	if(cari.length>0){
		var cari_a = $(cari).find('a');
		var url = $(cari_a).attr('href');
		//window.location.replace(url);
		//window.location.replace('<?php echo site_url(uri_string()); ?>');
	}
})
</script>