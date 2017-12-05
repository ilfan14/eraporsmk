<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title"><?php echo $page_title.'. Jumlah data: '.$total_rows.'. Sudah disinkron: '.$inserted. '. Sisa data: '.($total_rows - $inserted); ?></h3>
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
					<th class="text-center">nama_mata_pelajaran</th>
					<th class="text-center">kurikulum_id</th>
					<th class="text-center">mata_pelajaran_id</th>
					<th class="text-center">tingkat_pendidikan_id</th>
					<!--th class="text-center">wajib</th-->
					<th class="text-center">a_peminatan</th>
					<th class="text-center">area_kompetensi</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('4') + 1;
				foreach($dapodik as $data){
					//test($data);
					//die();
					$id_nasional 			= trim($data->mata_pelajaran_id);
					$kurikulum_id			= trim($data->kurikulum_id);
					$tingkat_pendidikan_id	= trim($data->tingkat_pendidikan_id);
					$a_peminatan			= trim($data->a_peminatan);
					$area_kompetensi		= trim($data->area_kompetensi);
					$status = '';
					$insert_ref_mapel = array(
						'id_nasional' 			=> $id_nasional,
						'nama_mata_pelajaran' 	=> trim($data->nama_mata_pelajaran),
					);
					$find_ref_mapel = $this->mata_pelajaran->find_by_id_nasional($id_nasional);
					if($find_ref_mapel){
						$mata_pelajaran_id = $find_ref_mapel->id;
						$status .= 'update_ref_mapel:'.$mata_pelajaran_id.'<br />';
						$this->mata_pelajaran->update($find_ref_mapel->id, $insert_ref_mapel);
					} else {
						$mata_pelajaran_id = $this->mata_pelajaran->insert($insert_ref_mapel);
						$status .= 'insert_ref_mapel:'.$mata_pelajaran_id.'<br />';
					}
					$insert_matpel_kur = array(
						'kurikulum_id'			=> $kurikulum_id,
						'mata_pelajaran_id'		=> $mata_pelajaran_id,
						'tingkat_pendidikan_id'	=> $tingkat_pendidikan_id,
						'a_peminatan'			=> $a_peminatan,
						'area_kompetensi'		=> $area_kompetensi,
						'kelompok_id'			=> 0,
					);
					$find_matpel_kur = $this->mata_pelajaran_kurikulum->find("kurikulum_id = $kurikulum_id AND mata_pelajaran_id = $mata_pelajaran_id AND tingkat_pendidikan_id = $tingkat_pendidikan_id");
					if($find_matpel_kur){
						$status .= 'update_matpel_kur:'.$find_matpel_kur->id.'<br />'.$find_matpel_kur->tingkat_pendidikan_id.'=>'.$find_matpel_kur->mata_pelajaran_id.'<br />';
						$this->mata_pelajaran_kurikulum->update($find_matpel_kur->id, $insert_matpel_kur);
						//$mata_pelajaran_kurikulum_id = $this->mata_pelajaran_kurikulum->insert($insert_matpel_kur);
						//$status .= 'update_matpel_kur:'.$mata_pelajaran_kurikulum_id.'<br />';
					} else {
						$mata_pelajaran_kurikulum_id = $this->mata_pelajaran_kurikulum->insert($insert_matpel_kur);
						$status .= 'insert_matpel_kur:'.$mata_pelajaran_kurikulum_id.'<br />';
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td><?php echo $data->nama_kurikulum; ?></td>
					<td><?php echo $data->nama_mata_pelajaran.'('.$mata_pelajaran_id.')'; ?></td>
					<td class="text-center"><?php echo $data->kurikulum_id; ?></td>
					<td class="text-center"><?php echo $data->mata_pelajaran_id; ?></td>
					<td class="text-center"><?php echo $data->tingkat_pendidikan_id; ?></td>
					<!--td class="text-center"><?php echo $data->wajib; ?></td-->
					<td class="text-center"><?php echo $data->a_peminatan; ?></td>
					<td class="text-center"><?php echo $data->area_kompetensi; ?></td>
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
		//$(this).css({cursor: 'none'});
	});
	var cari = $('body').find('.next');
	if(cari.length>0){
		var cari_a = $(cari).find('a');
		var url = $(cari_a).attr('href');
		window.location.replace(url);
	} else {
		//window.location.replace('<?php echo site_url('admin/sinkronisasi'); ?>');
	}
})
</script>