<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
			<?php
			if($data){
				$update = 'success';
				$title = 'Pembaharuan Tersedia';
				$status = 'Gunakan Tombol di bawah ini untuk memperbaharui aplikasi';
				$tombol  = '<a style="text-decoration:none;" class="btn btn-lg btn-warning" id="check_update">Proses Pembaharuan</a>';
			} else {
				$update = 'danger';
				$title = 'Status Pembaharuan Aplikasi';
				$status = 'Belum tersedia pembaharuan untuk versi aplikasi Anda';
				$tombol  = '';
			}
			if(!$set_status){
				$update = 'danger';
				$title = 'Status Koneksi Ke Server';
				$status = 'Tidak terhubung. Pastikan koneksi internet Anda aktif';
				$tombol  = '';
			}
			?>
				<div class="callout callout-<?php echo $update; ?>">
					<h4><?php echo $title; ?></h4>
					<p><?php echo $status; ?></p>
					<p><?php echo $tombol; ?></p>
				</div>
				<table class="table table-bordered" id="result" style="display:none;">
					<tr>
						<td>Mengunduh File Updater</td>
						<td><span class="download"><p class="text-yellow"><strong>[PROSES]</strong></p></span></td>
					</tr>
					<tr>
						<td>Mengekstrak File Updater</td>
						<td><span class="extract_to"><p class="text-yellow"><strong>[PROSES]</strong></p></span></td>
					</tr>
					<tr>
						<td>Memproses Pembaharuan</td>
						<td><span class="update_versi"><p class="text-yellow"><strong>[PROSES]</strong></p></span></td>
					</tr>
				</table>
				<a class="btn btn-success" id="sukses" href="<?php echo site_url('admin/check_update');?>" style="display:none;">Muat Ulang Aplikasi</a>
			</div>		
		</div>
	</div>
</div>
<script>
$('#check_update').click(function(){
	$('#result').show();
	$.ajax({
		url: '<?php echo site_url('admin/check_update/download');?>/<?php echo $versi; ?>',
		type: 'get',
		//data: {versi:'<?php echo $versi; ?>'},
		success: function(response){
			var data = $.parseJSON(response);
			$('.download').html(data.text);
			$.ajax({
				url: '<?php echo site_url('admin/check_update/extract_to');?>/<?php echo $versi; ?>',
				type: 'get',
				//data: {file:'<?php echo $versi; ?>'},
				success: function(response){
					var data = $.parseJSON(response);
					$('.extract_to').html(data.text);
					$.ajax({
						url: '<?php echo site_url('admin/check_update/update_versi');?>/<?php echo $versi; ?>',
						type: 'get',
						//data: {versi:'<?php echo $versi; ?>'},
						success: function(response){
							var data = $.parseJSON(response);
							$('.update_versi').html(data.text);
							window.setTimeout(function() {
								swal({
									title:'Sukses',
									type:'success',
									html:'Berhasil memperbarui apliaksi',
									confirmButtonText:'Muat Ulang Aplikasi',
								}).then(function() {
									window.location.replace('<?php echo site_url('admin/check_update'); ?>');
								}).done();
							}, 1000);
						}
					});
				}
			});
		}
	});
})
</script>