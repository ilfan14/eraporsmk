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
					<th class="text-center">mata_pelajaran</th>
					<th class="text-center">rombongan_belajar</th>
					<th class="text-center">guru_mata_pelajaran</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('5') + 1;
				foreach($dapodik as $data){
					//test($data);
					$get_rombel = $this->rombongan_belajar->find_by_rombel_id_dapodik($data->rombongan_belajar_id);
					$rombongan_belajar_id = ($get_rombel) ? $get_rombel->id : 0;
					$get_guru = $this->guru->find_by_guru_id_dapodik($data->ptk_id);
					$guru_id = ($get_guru) ? $get_guru->id : 0;
					$get_mapel = $this->mata_pelajaran->find("id_nasional = $data->mata_pelajaran_id");
					$mata_pelajaran_id = ($get_mapel) ? $get_mapel->id : 0;
					$insert_pembelajaran = array(
						'semester_id'			=> $ajaran->id,
						'rombongan_belajar_id'	=> $rombongan_belajar_id,
						'guru_id'				=> $guru_id,
						'mata_pelajaran_id'		=> $mata_pelajaran_id,
						'kkm'					=> 0,
						'is_dapodik'			=> 1,
						'pembelajaran_id'		=> $data->pembelajaran_id,
					);
					$find_pembelajaran = $this->pembelajaran->find("semester_id = $ajaran->id AND rombongan_belajar_id = $rombongan_belajar_id AND guru_id = $guru_id AND mata_pelajaran_id = $mata_pelajaran_id");
					if($find_pembelajaran && $mata_pelajaran_id){
						$this->pembelajaran->update($find_pembelajaran->id, $insert_pembelajaran);
						$result = 'update:'.$find_pembelajaran->id;
					} elseif(!$find_pembelajaran && !$mata_pelajaran_id){
						$insert_mapel = array(
							'id_nasional'			=> $data->mata_pelajaran_id,
							'jurusan_id'			=> $data->kurikulum_id,
							'nama_mata_pelajaran'	=> $data->nama_mata_pelajaran,
						);
						$id_insert_mapel = $this->mata_pelajaran->insert($insert_mapel);
						if($id_insert_mapel){
							$insert_mapel_kur = array(
								'kurikulum_id'			=> $data->kurikulum_id,
								'mata_pelajaran_id'		=> $id_insert_mapel,
								'tingkat_pendidikan_id'	=> $data->tingkat_pendidikan_id,
								'a_peminatan'			=> 0,
								'area_kompetensi'		=> 0,
								'kelompok_id'			=> 0,
							);
							$this->mata_pelajaran_kurikulum->insert($insert_mapel_kur);
						}
						$insert_pembelajaran_create = array(
							'semester_id'			=> $ajaran->id,
							'rombongan_belajar_id'	=> $rombongan_belajar_id,
							'guru_id'				=> $guru_id,
							'mata_pelajaran_id'		=> $id_insert_mapel,
							'kkm'					=> 0,
							'is_dapodik'			=> 1,
							'pembelajaran_id'		=> $data->pembelajaran_id,
						);
						$find_pembelajaran_insert = $this->pembelajaran->find("semester_id = $ajaran->id AND rombongan_belajar_id = $rombongan_belajar_id AND guru_id = $guru_id AND mata_pelajaran_id = $id_insert_mapel");
						if(!$find_pembelajaran_insert){
							$id_insert_pembelajaran = $this->pembelajaran->insert($insert_pembelajaran_create);
						}
						$result = 'create_mapel:'.$id_insert_pembelajaran;
					} elseif(!$find_pembelajaran && $mata_pelajaran_id){
						$id_insert_pembelajaran = $this->pembelajaran->insert($insert_pembelajaran);
						$result = 'insert:'.$id_insert_pembelajaran;
					} else {
						$result = 'skip';
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td><?php echo get_nama_mapel($mata_pelajaran_id).'('.$mata_pelajaran_id.')'; ?></td>
					<td><?php echo get_nama_rombel($rombongan_belajar_id).'('.$rombongan_belajar_id.')'; ?></td>
					<td><?php echo get_nama_guru($guru_id).'('.$guru_id.')'; ?></td>
					<td><?php echo $result; ?></td>
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
		//$(this).css({cursor: 'none'});
	});
	var cari = $('body').find('.next');
	if(cari.length>0){
		var cari_a = $(cari).find('a');
		var url = $(cari_a).attr('href');
		window.location.replace(url);
	} else {
		window.location.replace('<?php echo site_url('admin/sinkronisasi'); ?>');
	}
})
</script>