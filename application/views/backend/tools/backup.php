<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-pencil"></i> Backup Data</a></li>
					<li><a href="#tab_2" data-toggle="tab"><i class="fa fa-upload"></i> Restore Data</a></li>
                    <!--li><a href="#tab_3" data-toggle="tab"><i class="fa fa-download"></i> Download XML</a></li-->
				</ul>
			<div class="tab-content">
				<div class="tab-pane active text-center" id="tab_1">
				<a class="btn btn-success btn-lg btn-block" href="<?php echo site_url('main/backup'); ?>">Backup</a>
				</div>
				<div style="clear:both"></div>
				<div class="tab-pane" id="tab_2">
					<p>Proses restore database tidak tersedia di aplikasi</p>
					<p>Silahkan restore via phpMyadmin atau via command prompt</p>
					<p>Cara restore database via command prompt:</p>
					<ol>
						<li>Buka CMD</li>
						<li>CD ke folder mysql dimana xampp terinstall: Contoh <code>CD C:\xampp\mysql\bin\</code></li>
						<li>Kemudian ketik <code>mysql.exe -u root -p db_erapor &lt; D:\My Documents\db_erapor.sql</code></li>
						<li>Masukkan password (jika ada)</li>
						<li>Tunggu sampai proses selesai</li>
					</ol>
						<?php
						/*
						<p class="text-center"><span class="btn btn-danger btn-file btn-lg btn-block">								
									Browse  <input type="file" id="fileupload_import" name="import" />
								</span></p>
								<div id="gagal" class="alert alert-danger" style="margin-top:20px; display:none;"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Import Data Error!</b> Silahkan pilih Mata Pelajaran terlebih dahulu.</div>
						<div id="progress" class="progress" style="margin-top:10px; display:none;">
							<div class="progress-bar progress-bar-success"></div>
						</div>
						<div id="result" class="callout callout-info lead" style="display:none"></div>
						*/
						?>
				</div>
                <div style="clear:both;"></div>
			</div>
		</div>	
	</div>
</div>
<script>
$(function() {
var url = '<?php echo site_url('import/config');?>';
	console.log(url);
	$('#fileupload_import').fileupload({
        url: url,
		dataType: 'json',
	}).on('fileuploadprogress', function (e, data) {
		var progress = parseInt(data.loaded / data.total * 100, 10);
		$('#progress .progress-bar').css('width',progress + '%');
	}).on('fileuploadsubmit', function (e, data) {
		$('#gagal').hide();
		var mapel = $('#category_id_upload').val();
    	data.formData = {data: mapel};
		if(data.formData.mapel == ''){
			$('#gagal').show();
			return false;
		}else{
			$('#progress').show();
		}
	}).on('fileuploaddone', function (e, data) {
		$('#result').show();	
		$('#progress').hide();
		$('#progress .progress-bar').css('width','0%');
		window.setTimeout(function() { 
			$('#progress').show();
			$('#progress .progress-bar').removeClass('progress-bar-success').addClass('progress-bar-yellow');
			$('#progress').addClass('active');
			$('#progress .progress-bar').addClass('progress-bar-striped');
			$('#progress .progress-bar').css('width',data.result.persen+'%');
		}, 1000);
		DoAjaxProgressImport(data.result,data.result.jumlah,data.result.persen);
	}).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
var index = 0;
var set_insert = 0;
function DoAjaxProgressImport(data,length,persen) {
	$('#result').html('Proses restore akan membutuhkan waktu lama. Dari total <b>'+length+'</b> berkas, <b>'+set_insert+'</b> berkas berhasil di proses');
	$.ajax({
		url: '<?php echo site_url('import/config/proses'); ?>',
		type: 'post',
		data: {total:length, parameter:JSON.stringify(data.result[index])},
		success: function(response){
			var result = $.parseJSON(response);
			$('#progress').show();
			$('#progress .progress-bar').css('width',result.persen+'%');
			$('#result').html('Proses restore akan membutuhkan waktu lama. Dari total <b>'+result.total+'</b> berkas, <b>'+result.parameter+'</b> berkas berhasil di proses');
			set_insert = result.parameter;
			if(length == result.parameter){
				swal({title:result.title,type:result.type,html:result.text}).then(function() {
					window.location.replace('<?php echo site_url('admin/config/backup'); ?>');
				}).done();
				return false;
			}
			DoAjaxProgressImport(data,length);
		}
	});
	index++;
}
function DoAjaxProgressCallImport(data,length){
	setInterval( function() {
		DoAjaxProgressImport(data,length);
	}, 300 );
}
</script>