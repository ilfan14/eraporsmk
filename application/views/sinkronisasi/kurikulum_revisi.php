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
					<th class="text-center">level_bidang_id</th>
					<th class="text-center">jurusan_id</th>
					<th class="text-center">jurusan_induk</th>
					<th class="text-center">nama_jurusan</th>
					<th class="text-center">kurikulum_id</th>
					<th class="text-center">nama_kurikulum</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('4') + 1;
				foreach($dapodik as $data){
					//test($data);
					//die();
					$find_jurusan = $this->jurusan->find("jurusan_id = $data->jurusan_id");
					$data_jurusan = array(
						'jurusan_id'		=> $data->jurusan_id,
						'jurusan_induk'		=> ($data->jurusan_induk) ? $data->jurusan_induk : 0,
						'nama_jurusan'		=> $data->nama_jurusan,
						'level_bidang_id'	=> $data->level_bidang_id,
					);
					if($find_jurusan){
						//$this->jurusan->update($find_jurusan->id, $data_jurusan);
					} else {
						//$this->jurusan->insert($data_jurusan);
					}
					$query = $this->_database->get_where('ref.kurikulum', array('jurusan_id' => $data->jurusan_id, 'expired_date' => NULL));
					$get_kurikulum = $query->result();
			?>
				<tr>
					<td rowspan="<?php echo count($get_kurikulum) + 1; ?>" class="text-center"><?php echo $no++; ?></td>
					<td rowspan="<?php echo count($get_kurikulum) + 1; ?>"><?php echo $data->level_bidang_id; ?></td>
					<td rowspan="<?php echo count($get_kurikulum) + 1; ?>"><?php echo $data->jurusan_id; ?></td>
					<td rowspan="<?php echo count($get_kurikulum) + 1; ?>"><?php echo $data->jurusan_induk; ?></td>
					<td rowspan="<?php echo count($get_kurikulum) + 1; ?>"><?php echo $data->nama_jurusan; ?></td>
				<?php
				if(!$get_kurikulum){
				?>
					<td>-</td>
					<td>-</td>
				</tr><!--luar-->
				<?php
				} else {
				?>
				</tr><!--luar-->
				<?php
					foreach($get_kurikulum as $kurikulum){
						$find_kurikulum = $this->kurikulum->find("kurikulum_id = $kurikulum->kurikulum_id AND jurusan_id = $data->jurusan_id");
						if($find_kurikulum){
							//$this->kurikulum->update($find_kurikulum->id, array('jurusan_id' => $data->jurusan_id));
						} else {
							/*$this->kurikulum->insert(array(
								'kurikulum_id'		=> $kurikulum->kurikulum_id,
								'nama_kurikulum'	=> $kurikulum->nama_kurikulum,
								'jurusan_id'		=> $data->jurusan_id,
							));*/
						}
						?>
							<tr>
								<td><?php echo $kurikulum->kurikulum_id; ?></td>
								<td><?php echo $kurikulum->nama_kurikulum; ?></td>
							</tr><!--dalem-->
						<?php
					}
				}
				?>
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
		//window.location.replace(url);
	}
})
</script>