<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title"><?php echo $page_title.' ('.$total_rows.' => '.$inserted. ')'; ?></h3>
	</div>
    <div class="box-body">
	<div class="text-center">
		<?php echo $pagination; ?>
	</div>
	<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center">nama_kurikulum</th>
					<th class="text-center">nama</th>
					<th class="text-center">kurikulum_id</th>
					<th class="text-center">mata_pelajaran_id</th>
					<th class="text-center">tingkat_pendidikan_id</th>
					<th class="text-center">wajib</th>
					<th class="text-center">a_peminatan</th>
					<th class="text-center">area_kompetensi</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('4') + 1;
				foreach($dapodik as $data){
					$nama_mata_pelajaran = addslashes($data->nama);
					$data_mapel = array(
						'jurusan_id' 			=> $data->kurikulum_id,
						'id_nasional'			=> $data->mata_pelajaran_id,
						'nama_mata_pelajaran'	=> $nama_mata_pelajaran,
						//'tingkat_pendidikan_id'	=> $data->tingkat_pendidikan_id,
					);
					//$mata_pelajaran = $this->mata_pelajaran->find("jurusan_id = $data->kurikulum_id AND nama_mata_pelajaran = '$nama_mata_pelajaran' AND tingkat_pendidikan_id = $data->tingkat_pendidikan_id");
					$mata_pelajaran = $this->mata_pelajaran->find("jurusan_id = $data->kurikulum_id AND nama_mata_pelajaran = '$nama_mata_pelajaran'");
					if($mata_pelajaran){
						$data_mapel_kur = array(
							'kurikulum_id'				=> $data->kurikulum_id,
							'mata_pelajaran_id'			=> $mata_pelajaran->id,
							'tingkat_pendidikan_id'		=> $data->tingkat_pendidikan_id,
							'a_peminatan'			=> $data->a_peminatan,
							'area_kompetensi'		=> $data->area_kompetensi,
							'kelompok_id'				=> 1
						);
						$mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->find("kurikulum_id = $data->kurikulum_id AND mata_pelajaran_id = $mata_pelajaran->id AND tingkat_pendidikan_id = $data->tingkat_pendidikan_id");
						if($mata_pelajaran_kurikulum){
							$this->mata_pelajaran_kurikulum->update($mata_pelajaran_kurikulum->id, $data_mapel_kur);
						} else {
							$this->mata_pelajaran_kurikulum->insert($data_mapel_kur);
						}
						$this->mata_pelajaran->update($mata_pelajaran->id, $data_mapel);
					} else {
						$insert_mata_pelajaran = $this->mata_pelajaran->insert($data_mapel);
						if($insert_mata_pelajaran){
							$data_mapel_kur = array(
								'kurikulum_id'				=> $data->kurikulum_id,
								'mata_pelajaran_id'			=> $insert_mata_pelajaran,
								'tingkat_pendidikan_id'		=> $data->tingkat_pendidikan_id,
								'a_peminatan'				=> $data->a_peminatan,
								'area_kompetensi'			=> $data->area_kompetensi,
								'kelompok_id'				=> 1
							);
							$mata_pelajaran_kurikulum = $this->mata_pelajaran_kurikulum->find("kurikulum_id = $data->kurikulum_id AND mata_pelajaran_id = $insert_mata_pelajaran AND tingkat_pendidikan_id = $data->tingkat_pendidikan_id");
							if($mata_pelajaran_kurikulum){
								$this->mata_pelajaran_kurikulum->update($mata_pelajaran_kurikulum->id, $data_mapel_kur);
							} else {
								$this->mata_pelajaran_kurikulum->insert($data_mapel_kur);
							}
							//$this->mata_pelajaran->update($mata_pelajaran->id, $data_mapel);
						}
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td><?php echo $data->nama_kurikulum; ?></td>
					<td><?php echo $data->nama; ?></td>
					<td><?php echo $data->kurikulum_id; ?></td>
					<td><?php echo $data->mata_pelajaran_id; ?></td>
					<td><?php echo $data->tingkat_pendidikan_id; ?></td>
					<td><?php echo $data->wajib; ?></td>
					<td><?php echo $data->a_peminatan; ?></td>
					<td><?php echo $data->area_kompetensi; ?></td>
				</tr>
			<?php 
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
		window.location.replace(url);
	}
})
</script>