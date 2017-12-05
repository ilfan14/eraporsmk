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
					<th class="text-center">jurusan_id</th>
					<th class="text-center">jurusan_induk</th>
					<th class="text-center">nama_jurusan</th>
					<th class="text-center">kurikulum_id</th>
					<th class="text-center">nama_kurikulum</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('4') + 1;
				foreach($dapodik as $data){
					$status = '';
					$find_jurusan = $this->jurusan->find("jurusan_id = $data->jurusan_id");
					$data_jurusan = array(
						'jurusan_id'		=> $data->jurusan_id,
						'jurusan_induk'		=> ($data->jurusan_induk) ? $data->jurusan_induk : 0,
						'nama_jurusan'		=> $data->nama_jurusan,
						'level_bidang_id'	=> $data->level_bidang_id,
					);
					$data_kurikulum = array(
						'kurikulum_id'		=> $data->kurikulum_id,
						'nama_kurikulum'	=> $data->nama_kurikulum,
						'jurusan_id'		=> $data->jurusan_id,
					);
					if($find_jurusan){
						$status .= 'ignore_jurusan<br />';
						//$this->jurusan->update($find_jurusan->id, $data_jurusan);
						$find_kurikulum = $this->kurikulum->find("kurikulum_id = $data->kurikulum_id");
						if($find_kurikulum){
							$status .= 'ignore_kurikulum_1<br />';
						} else {
							$this->kurikulum->insert($data_kurikulum);
							$status .= 'insert_kurikulum_1<br />';
						}
					} else {
						$status .= 'insert_jurusan<br />';
						$jurusan_id_insert = $this->jurusan->insert($data_jurusan);
						$find_jurusan = $this->jurusan->get($jurusan_id_insert);
						$data_kurikulum = array(
							'kurikulum_id'		=> $data->kurikulum_id,
							'nama_kurikulum'	=> $data->nama_kurikulum,
							'jurusan_id'		=> $find_jurusan->jurusan_id,
						);
						$find_kurikulum = $this->kurikulum->find("kurikulum_id = $data->kurikulum_id");
						if($find_kurikulum){
							$status .= 'ignore_kurikulum_2<br />';
						} else {
							$this->kurikulum->insert($data_kurikulum);
							$status .= 'insert_kurikulum_2<br />';
						}
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td class="text-center"><?php echo $data->jurusan_id; ?></td>
					<td class="text-center"><?php echo $data->jurusan_induk; ?></td>
					<td class="text-center"><?php echo $data->nama_jurusan; ?></td>
					<td class="text-center"><?php echo $data->kurikulum_id; ?></td>
					<td class="text-center"><?php echo $data->nama_kurikulum; ?></td>
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
		//window.location.replace(url);
	}
})
</script>