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
					<th class="text-center">nama</th>
					<th class="text-center">jurusan_sp_id</th>
					<th class="text-center">kurikulum_id</th>
					<th class="text-center">guru_id</th>
					<th class="text-center">tingkat</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('6') + 1;
				foreach($dapodik as $data){
					$find_rombel_erapor = $this->rombongan_belajar->find("semester_id = $ajaran->id AND nama = '$data->nama' AND tingkat = $data->tingkat_pendidikan_id AND kurikulum_id = $data->kurikulum_id");
					$query = $this->_database->get_where('jurusan_sp', array('jurusan_sp_id' => $data->jurusan_sp_id));
					$get_jurusan_sp_id = $query->row();
					$jurusan_sp_id = ($get_jurusan_sp_id) ? $get_jurusan_sp_id->jurusan_id : 0;
					$get_wali = $this->guru->find_by_guru_id_dapodik($data->ptk_id);
					//Dataguru::find_by_guru_id_dapodik($rombel->ptk_id);
					$wali_kelas_id = isset($get_wali->id) ? $get_wali->id : 0;
					$insert_rombel = array(
						'semester_id' 		=> $ajaran->id,
						'sekolah_id' 		=> $loggeduser->sekolah_id,
						'jurusan_sp_id' 	=> $jurusan_sp_id,
						'kurikulum_id' 		=> $data->kurikulum_id,
						'nama' 				=> $data->nama,
						'guru_id' 			=> $wali_kelas_id,
						'tingkat' 			=> $data->tingkat_pendidikan_id,
						'guru_id_dapodik' 	=> $data->ptk_id,
						'rombel_id_dapodik'	=> $data->rombongan_belajar_id,
						'petugas' 			=> $loggeduser->username,
					);
					if($find_rombel_erapor){
						$this->rombongan_belajar->update($find_rombel_erapor->id, $insert_rombel);
						$result = 'update';
					} else {
						$this->rombongan_belajar->insert($insert_rombel);
						$result = 'insert';
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td><?php echo $data->nama; ?></td>
					<td><?php echo get_jurusan($insert_rombel['jurusan_sp_id']); ?></td>
					<td><?php echo get_kurikulum($data->kurikulum_id); ?></td>
					<td><?php echo get_nama_guru($insert_rombel['guru_id']); ?></td>
					<td><?php echo $insert_rombel['tingkat']; ?></td>
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
		$(this).css({cursor: 'none'});
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