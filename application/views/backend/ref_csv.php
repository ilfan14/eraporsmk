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
		echo $pagination;
		$uri = $this->uri->segment_array(); 
		//test($uri);
		?>
	</div>
	<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center">id_kd</th>
					<th class="text-center">aspek</th>
					<th class="text-center">mata_pelajaran_id</th>
					<th>nama_mata_pelajaran</th>
					<th>status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$i=1 + $offset;
				foreach($show as $value){
					$status = '';
					$find_data_excel = $this->data_excel->find_by_file($file);
					if(!$find_data_excel){
						$data = array(
							'file'		=> $file,
							'status'	=> 1,
						);
						$id_csv =  $this->data_excel->insert($data);
					} else {
						$id_csv = $find_data_excel->id;
					}
					$id_nasional = ($value['mata_pelajaran_id'] != '#N/A') ? $value['mata_pelajaran_id'] : 0;
					$excel_id = $id_csv;
					$insert_mata_pelajaran = array(
						'id_nasional' 			=> $id_nasional,
						'jurusan_id'			=> '',//$value['kurikulum_id'],
						'nama_mata_pelajaran'	=> $value['nama_mata_pelajaran'],
					);
					$find_mata_pelajaran = $this->mata_pelajaran->find("id_nasional = $id_nasional");
					if($find_mata_pelajaran){
						$mata_pelajaran_id = $find_mata_pelajaran->id;
						$status .= 'ignore_mapel:'.$mata_pelajaran_id.'<br>';
					} else {
						$mata_pelajaran_id = $this->mata_pelajaran->insert($insert_mata_pelajaran);
						$status .= 'insert_mapel'.$mata_pelajaran_id.'<br>';
					}
					$get_mata_pelajaran_id = ($id_nasional) ? $id_nasional : $mata_pelajaran_id;
					$id_kompetensi = $value['id_kd'];
					$aspek = $value['aspek'];
					$kompetensi_dasar = array(
						'id_kompetensi' 	=> $id_kompetensi,
						'aspek'				=> $aspek,
						'mata_pelajaran_id'	=> $mata_pelajaran_id,
						'kelas'				=> 0,
						'kompetensi_dasar'	=> clean($value['kompetensi_dasar']),
						'excel_id'			=> $excel_id
					);
					$value_kd = clean($value['kompetensi_dasar']);
					$find_kd = $this->kompetensi_dasar->find("id_kompetensi = '$id_kompetensi' AND aspek = '$aspek' AND mata_pelajaran_id = $mata_pelajaran_id AND kelas = 0 AND kompetensi_dasar = '$value_kd'");
					if(!$find_kd){
						$kompetensi_dasar_id = $this->kompetensi_dasar->insert($kompetensi_dasar);
						$status .= 'insert_kd:'.$kompetensi_dasar_id.'<br>';
					} else {
						$status .= 'ignore_kd:'.$find_kd->id.'<br>';
					}
				?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td class="text-center"><?php echo $value['id_kd']; ?></td>
					<td class="text-center"><?php echo $value['aspek']; ?></td>
					<td class="text-center"><?php echo $get_mata_pelajaran_id; ?></td>
					<td><?php echo $value['nama_mata_pelajaran']; ?></td>
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