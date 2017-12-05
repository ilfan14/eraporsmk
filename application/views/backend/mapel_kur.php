<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<title>Extract CSV File</title>
	<meta charset="utf-8">
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="<?php echo site_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo site_url(); ?>assets/js/jquery-2.1.1.js"></script>
</head>
<body>
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title text-center">Mohon menunggu, sedang memproses <?php echo $inserted; ?> data dari total <?php echo $total_rows; ?> data.</h3>
	</div>
    <div class="box-body">
	<div class="text-center">
		<?php
		//echo $pagination;
		$uri = $this->uri->segment_array(); 
		//test($uri);
		?>
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
					<th class="text-center">a_peminatan</th>
					<th class="text-center">area_kompetensi</th>
					<th class="text-center">status</th>
            </thead>
			<tbody>
			<?php
				$i=1 + $offset;
				foreach($show as $value){
					//test($value);
					//die();
					$status = '';
					$id_nasional 			= $value['mata_pelajaran_id'];
					$kurikulum_id 			= $value['kurikulum_id'];
					$tingkat_pendidikan_id 	= $value['tingkat_pendidikan_id'];
					$a_peminatan 			= $value['a_peminatan'];
					$area_kompetensi 		= $value['area_kompetensi'];
					$insert_mata_pelajaran = array(
						'id_nasional' 			=> $id_nasional,
						//'jurusan_id'			=> '',//$value['kurikulum_id'],
						'nama_mata_pelajaran'	=> $value['nama_mata_pelajaran'],
					);
					$find_mata_pelajaran = $this->mata_pelajaran->find_by_id_nasional($id_nasional);
					if($find_mata_pelajaran){
						$mata_pelajaran_id = $find_mata_pelajaran->id;
						$status .= 'ignore_mapel:'.$mata_pelajaran_id.'<br>';
					} else {
						$mata_pelajaran_id = $this->mata_pelajaran->insert($insert_mata_pelajaran);
						$status .= 'insert_mapel'.$mata_pelajaran_id.'<br>';
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
						$status .= 'ignore_matpel_kur:'.$find_matpel_kur->id.'<br />'.$find_matpel_kur->tingkat_pendidikan_id.'=>'.$find_matpel_kur->mata_pelajaran_id.'<br />';
						//$this->mata_pelajaran_kurikulum->update($find_matpel_kur->id, $insert_matpel_kur);
						//$mata_pelajaran_kurikulum_id = $this->mata_pelajaran_kurikulum->insert($insert_matpel_kur);
						//$status .= 'update_matpel_kur:'.$mata_pelajaran_kurikulum_id.'<br />';
					} else {
						$mata_pelajaran_kurikulum_id = $this->mata_pelajaran_kurikulum->insert($insert_matpel_kur);
						$status .= 'insert_matpel_kur:'.$mata_pelajaran_kurikulum_id.'<br />';
					}
				?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td><?php echo $value['nama_kurikulum']; ?></td>
					<td><?php echo $value['nama_mata_pelajaran'].'('.$mata_pelajaran_id.')'; ?></td>
					<td class="text-center"><?php echo $kurikulum_id; ?></td>
					<td class="text-center"><?php echo $mata_pelajaran_id; ?></td>
					<td class="text-center"><?php echo $tingkat_pendidikan_id; ?></td>
					<td class="text-center"><?php echo $a_peminatan; ?></td>
					<td class="text-center"><?php echo $area_kompetensi; ?></td>
					<td><?php echo $status; ?></td>
				</tr>
			<?php 
			$i++;
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
	var cari = $('body').find('.next');
	var url = '<?php echo site_url('admin/csv'); ?>';
	if(cari.length>0){
		var cari_a = $(cari).find('a');
		url = $(cari_a).attr('href');
	}
	window.location.replace(url);
	
})
</script>
</body>
</html>