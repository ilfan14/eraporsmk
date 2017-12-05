<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title"><?php echo $page_title; ?></h3><br />
	<h4 class="box-title">total_rows = <?php echo $total_rows; ?> | inserted = <?php echo $inserted; ?></h4>
	</div>
    <div class="box-body">
	<div class="text-center">
		<?php echo $pagination; ?>
	</div>
	<table class="table table-bordered">
            <thead>
				<tr>
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center">gelar_akademik_id</th>
					<th class="text-center">kode</th>
					<th>nama</th>
					<th class="text-center">posisi_gelar</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('4') + 1;
				foreach($dapodik as $data){
					//test($data);
					$status = '';
					$find_gelar = $this->gelar->find("gelar_akademik_id = $data->gelar_akademik_id");
					$data_gelar = array(
						'gelar_akademik_id'	=> $data->gelar_akademik_id,
						'kode'				=> trim($data->kode),
						'nama'				=> trim($data->nama),
						'posisi_gelar'		=> $data->posisi_gelar,
					);
					if($find_gelar){
						$status .= 'ignore_gelar<br />';
					} else {
						$status .= 'insert_gelar<br />';
						$this->gelar->insert($data_gelar);
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td class="text-center"><?php echo $data->gelar_akademik_id; ?></td>
					<td class="text-center"><?php echo $data->kode; ?></td>
					<td><?php echo $data->nama; ?></td>
					<td class="text-center"><?php echo $data->posisi_gelar; ?></td>
					<td class="text-center"><?php echo $status; ?></td>
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
	var cari = $('body').find('.next');
	if(cari.length>0){
		var cari_a = $(cari).find('a');
		var url = $(cari_a).attr('href');
		window.location.replace(url);
	} else {
		//window.location.replace('<?php echo site_url('admin/dashboard'); ?>');
	}
})
</script>