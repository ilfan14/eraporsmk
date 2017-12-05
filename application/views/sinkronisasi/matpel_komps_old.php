<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<title>eRaporSMK 2017</title>
	<meta charset="utf-8">
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="<?php echo site_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo site_url(); ?>assets/js/jquery-2.0.3.min.js"></script>
</head>
<body>
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title">Referensi Mata Pelajaran Kompetensi Dapodik <?php echo $total_rows; ?> => <?php echo $inserted; ?></h3>
	</div>
    <div class="box-body">
	<div class="text-center">
		<?php echo $pagination; ?>
	</div>
	<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center">id_ref_dapo</th>
					<th class="text-center">kurikulum_id</th>
					<th class="text-center">nama_kurikulum</th>
					<th class="text-center">mata_pelajaran_id</th>
					<th class="text-center">nama_mata_pelajaran</th>
					<th class="text-center">tingkat</th>
					<th class="text-center">area_kompetensi</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$i=1 + $offset;
				foreach($pembelajaran as $belajar){
				test($belajar);
			?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td class="text-center"><?php echo $id_ref_dapo; ?></td>
					<td class="text-center"><?php echo $belajar['kurikulum_id']; ?></td>
					<td><?php echo $belajar['nama_kurikulum']; ?></td>
					<td class="text-center"><?php echo $belajar['mata_pelajaran_id']; ?></td>
					<td><?php echo $belajar['nama_mata_pelajaran']; ?></td>
					<td class="text-center"><?php echo $belajar['tingkat']; ?></td>
					<td><?php echo $belajar['area_kompetensi']; ?></td>
				</tr>
			<?php 
			$i++;
			} ?>
			</tbody>
		</table>
		<?php //test($pembelajaran); ?>
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
	}
})
</script>
</body>
</html>