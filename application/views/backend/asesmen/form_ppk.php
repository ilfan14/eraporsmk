<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysiwyg/external/jquery.hotkeys.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysiwyg/external/google-code-prettify/prettify.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysiwyg/bootstrap-wysiwyg.js"></script>
<link href="<?php echo base_url(); ?>assets/css/editor.css" rel="stylesheet" type="text/css" />
<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<?php echo (isset($message) && $message != '') ? error_msg($message) : '';?>
<div class="box box-info">
    <div class="box-body">
		<!-- form start -->
            <?php
			$loggeduser = $this->ion_auth->user()->row();
			$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
			echo form_open_multipart(uri_string(),$attributes);
			$ajaran = get_ta();
			$tahun_ajaran = $ajaran->tahun. ' (SMT '. $ajaran->semester.')';
			$data_rombel = $this->rombongan_belajar->find("guru_id = $loggeduser->guru_id AND semester_id = $ajaran->id");
			//Datarombel::find_by_guru_id_and_ajaran_id($guru_id, $ajaran->id);
			$data_siswa = get_siswa_by_rombel($data_rombel->id);
			$all_ppk = $this->ref_ppk->get_all();
			$extra = 'class="select2 form-control required" id="siswa"';
			?>
              <div class="box-body">
			  	<div class="col-sm-12">
                <div class="form-group">
                  <label for="ajaran_id" class="col-sm-2 control-label">Tahun Ajaran</label>
				  <div class="col-sm-10">
				  	<input type="hidden" name="query" id="query" value="ppk" />
                    <input type="hidden" id="semester_id" name="semester_id" value="<?php echo $ajaran->id; ?>" />
                    <input type="text" class="form-control" value="<?php echo $tahun_ajaran; ?>" readonly />
                  </div>
                </div>
				<div class="form-group">
                  <label for="siswa" class="col-sm-2 control-label">Siswa</label>
				  <div class="col-sm-10">
				  <?php
					$all_siswa = array();
					$all_siswa[''] = 'Pilih Siswa';
					foreach($data_siswa as $data){
						$all_siswa[$data->siswa->id] = $data->siswa->nama;
					}
					echo form_dropdown('siswa', $all_siswa, $siswa, $extra);
					?>
                  </div>
                </div>
				<div class="form-group">
					<label for="karakter" class="col-sm-2 control-label">Petunjuk Pengisian Laporan Perkembangan Karakter
</label>
					<div class="col-sm-10">
					<ol style="margin-left:-25px; text-align:justify;">
						<li>Laporan Perkembangan Karakter merupakan catatan prilaku/karakter siswa dalam dan atau di luar satuan pendidikan.</li>
						<li>Sumber informasi untuk catatan prilaku dapat diperoleh dari catatan (jurnal) guru dan atau fortopolio (dokumen keikutsertaan, piagam, sertifikat kegiatan) siswa di dalam dan atau di luar satuan pendidikan.</li>
						<li>Tujuan laporan untuk menunjukkan kelebihan dan atau keunikan siswa, dan memotivasi siswa untuk penguatan karakter dan atau kompetensi.</li>
						<li>Laporan berbentuk narasi (maksimal 1 halaman) yang ditulis dalam kalimat positif.</li>
						<li>Pada bagian atas Laporan, dituliskan identitas peserta didik dan dapat dilengkapi dengan foto aktifitas siswa.</li>
						<li>Laporan Perkembangan Karakter ditandatangani wali kelas dan diisi setiap akhir semester serta dijadikan dokumen catatan yang berkelanjutan.</li>
					</ol>
					</div>
					<!--label for="karakter" class="col-sm-2 control-label">Komponen Karakter</label-->
					<?php
					/*$set_ref_ppk_id = ($ref_ppk_id) ? $ref_ppk_id : array();
					$ref_ppk = $this->ref_ppk->get_all();
					$chunkPpk = array_chunk($ref_ppk, count($ref_ppk) / 3);
					$count = count(current($chunkPpk));
					//for($i = 0; $i < $count; $i++){
					foreach($chunkPpk as $c_ppk){
					echo '<div class="col-sm-3">';
					foreach($c_ppk as $ppk){
					//test($ppk);
					?>
						<label><input type="checkbox" class="icheck" name="ppk_id[]" value="<?php echo $ppk->id; ?>"<?php if(in_array($ppk->id, $set_ref_ppk_id)) { echo ' checked="checked"'; } ?>> <?php echo $ppk->nama; ?></label><br />
					<?php
						}
					echo '</div>';
					}
					*/?>
				</div>
				<div class="form-group">
					<label for="karakter" class="col-sm-2 control-label">Capaian Karakter</label>
					<div class="col-sm-10">
						<div id="alerts"></div>
						<div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
							<div class="btn-group">
								<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i> <b class="caret"></b></a>
								<ul class="dropdown-menu">
								</ul>
							</div>
							<div class="btn-group">
								<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
									<li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
									<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
								</ul>
							</div>
							<div class="btn-group">
								<a class="btn btn-default" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
								<a class="btn btn-default" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
								<a class="btn btn-default" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
								<a class="btn btn-default" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
							</div>
							<div class="btn-group">
								<a class="btn btn-default" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
								<a class="btn btn-default" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
								<a class="btn btn-default" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
								<a class="btn btn-default" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
							</div>
							<div class="btn-group">
								<a class="btn btn-default" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
								<a class="btn btn-default" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
								<a class="btn btn-default" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
								<a class="btn btn-default" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
							</div>
							<div class="btn-group">
								<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
								<div class="dropdown-menu input-append">
									<input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
									<button class="btn btn-default" type="button">Add</button>
								</div>
								<a class="btn btn-default" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
							</div>
							<div class="btn-group">
								<a class="btn btn-default" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-image"></i></a>
								<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
							</div>
							<div class="btn-group">
								<a class="btn btn-default" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
								<a class="btn btn-default" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
							</div>
							<input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
						</div>
						<div id="editor"><?php echo ($capaian) ? $capaian : set_value('capaian'); ?></div>
						<textarea id="copy_editor" name="copy_editor" style="display:none;"></textarea>
					</div>
				</div>
				<?php /*for($i=1; $i<=3; $i++){?>
				<div class="form-group">
					<label for="kegiatan" class="col-sm-3 control-label">Foto <?php echo $i; ?></label>
					<div class="col-sm-9">
						<input type="file" name="foto_<?php echo $i; ?>" />
					</div>
				</div>
				<?php } */?>
			</div>
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-success simpan">Simpan</button>
		</div>
		<?php echo form_close();  ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>
<script>
$(function(){
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 
            'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
            'Times New Roman', 'Verdana'],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function (idx, fontName) {
          fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
      });
      $('a[title]').tooltip({container:'body'});
    	$('.dropdown-menu input').click(function() {return false;})
		    .change(function () {$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
        .keydown('esc', function () {this.value='';$(this).change();});

      $('[data-role=magic-overlay]').each(function () { 
        var overlay = $(this), target = $(overlay.data('target')); 
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      });
      if ("onwebkitspeechchange"  in document.createElement("input")) {
        var editorOffset = $('#editor').offset();
        $('#voiceBtn').css('position','absolute').offset({top: editorOffset.top, left: editorOffset.left+$('#editor').innerWidth()-35});
      } else {
        $('#voiceBtn').hide();
      }
	};
	function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	};
    initToolbarBootstrapBindings();  
	$('#editor').wysiwyg({ fileUploadError: showErrorAlert} );
	$('#editor').cleanHtml();
    window.prettyPrint && prettyPrint();
  });
$('#myform').submit(function(){
	//e.preventDefault();
	var capaian = $('#editor').html();
	$('#copy_editor').html(capaian);
	$.ajax({
		//url: '<?php echo site_url('admin/asesmen/add_ppk'); ?>',
		url: '<?php echo site_url('admin/asesmen/get_ppk'); ?>',
		type: 'post',
		data: $("#myform").serialize(),
		success: function(response){
			console.log(response);
		}
	});
});
</script>