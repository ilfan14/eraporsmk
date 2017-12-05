<div class="row">
	<div class="col-md-12">
		<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
		<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
		<div class="box box-primary">
			<form role="form" method="POST" action="<?php echo site_url('admin/config/update'); ?>" enctype="multipart/form-data">
				<div class="box-body">
					<div class="col-xs-12 no-padding">
						<div class="form-group col-md-6">
							<label>Periode Aktif</label>
							<select class="form-control" name="periode" required>
								<?php
								$tahun = array(0,0,1,1,2,2);
								$i=0;
								foreach($tahun as $t){
									if($i%2){
										$periode = 'Genap';
									} else {
										$periode = 'Ganjil';
									}
									$t1 = (date('Y')+$t)-2;
									$t2 = (date('Y')+$t)-1;
									$t3 = 'Semester '.$periode;
									$value = $t1.'/'.$t2.' | '.$t3;
									?>
									<option value="<?php echo (date('Y')+$t)-2; ?>/<?php echo (date('Y')+$t)-1; ?> | Semester <?php echo $periode; ?>"<?php echo ($settings->periode == $value) ? ' selected="selected"' : '';?>><?php echo (date('Y')+$t)-2; ?>/<?php echo (date('Y')+$t)-1; ?> Semester <?php echo $periode; ?></option>
								<?php 
									$i++;
								}
								?>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label>Tampilkan Menu Sinkronisasi</label>
							<select class="form-control" name="sinkronisasi" required>
								<option value="0"<?php echo ($settings->sinkronisasi == 0) ? ' selected="selected"' : ''; ?>>Tidak</option>
								<option value="1"<?php echo ($settings->sinkronisasi == 1) ? ' selected="selected"' : ''; ?>>Ya</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<!--label>Tampilkan Rumus di Laman Penilaian</label>
							<select class="form-control" name="rumus" required>
								<option value="0"<?php echo ($settings->rumus == 0) ? ' selected="selected"' : ''; ?>>Tidak</option>
								<option value="1"<?php echo ($settings->rumus == 1) ? ' selected="selected"' : ''; ?>>Ya</option>
							</select-->
							<label for="tanggal_rapor">Tanggal Rapor</label>
							<!--input class="form-control" name="tanggal_rapor" id="tanggal_rapor" /-->
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
								<input name="tanggal_rapor" value="<?php $tanggal_rapor = ($settings->tanggal_rapor) ? date('d-m-Y', strtotime($settings->tanggal_rapor)) : date('d-m-Y'); echo $tanggal_rapor; ?>" id="tanggal_rapor" class="form-control required datepicker" data-date-format="dd-mm-yyyy" type="text">
							</div>
						</div>
						<div class="form-group col-md-6">
							<label>Tampilkan Menu Import (Siswa &amp; Guru)</label>
							<select class="form-control" name="import" required>
								<option value="0"<?php echo ($settings->import == 0) ? ' selected="selected"' : ''; ?>>Tidak</option>
								<option value="1"<?php echo ($settings->import == 1) ? ' selected="selected"' : ''; ?>>Ya</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label>Otomatis Tampilkan Deskripsi Per Mata Pelajaran</label>
							<select class="form-control" name="desc" required>
								<option value="0"<?php echo ($settings->desc == 0) ? ' selected="selected"' : ''; ?>>Tidak</option>
								<option value="1"<?php echo ($settings->desc == 1) ? ' selected="selected"' : ''; ?>>Ya</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label>Zona Waktu</label>
							<select class="form-control" name="zona" required>
								<option value="1"<?php echo ($settings->zona == 1) ? ' selected="selected"' : ''; ?>>Waktu Indonesia Barat (WIB)</option>
								<option value="2"<?php echo ($settings->zona == 2) ? ' selected="selected"' : ''; ?>>Waktu Indonesia Tengah (WITA)</option>
								<option value="3"<?php echo ($settings->zona == 3) ? ' selected="selected"' : ''; ?>>Waktu Indonesia Timur (WIT)</option>
							</select>
						</div>
					</div>
				</div>
				<div class="box-footer clearfix">
					<button type="submit" class="btn btn-primary pull-right">Simpan</button>
					<div class="col-xs-12" style="margin-top:20px;">
						<label>Log Akses</label>
						<?php
						$hits = '';
						$filename = "./log.txt";
						if (file_exists($filename)) {
							$hits = file_get_contents($filename);
						} else {
							$hits = file_put_contents($filename, '');
						}
						?>
						<textarea class="form-control" rows="3"><?php echo $hits; ?></textarea>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$(function () {
	function nextChar(c) {
		return String.fromCharCode(c.charCodeAt(0) - 1);
	}
	function ThisChar(c) {
		return String.fromCharCode(c.charCodeAt(0));
	}
    $('.add_max').on('click',function(){
        var $qty=$(this).closest('div.input-group').find('.form-control');
        var currentVal = parseInt($qty.val());
		var className = $($qty).attr('id');
		var className_next = className.replace(className.substr(1),'_min');
		if (!isNaN(currentVal) && currentVal >= 100) {
			swal(
				'Oops...',
				'Nilai maksimal tidak boleh di atas 100',
				'error'
			);
        } else{
			if(className == 'a_min'){
				$qty.val(currentVal + 1);
				return false;
			}
			if(className != 'a_max'){
				var minimal = $('#'+className).val();
				var minimal_plus = (parseInt(minimal) + 1);
				var maksimal = $('#'+nextChar(className.substr(0,1))+'_min').val();
				if(minimal_plus >= maksimal){
					swal(
						'Oops...',
						'Maksimal Nilai '+ ThisChar(className.substr(0,1)).toUpperCase() +' tidak boleh sama atau lebih besar dari Minimal Nilai '+ nextChar(className.substr(0,1)).toUpperCase(),
						'error'
					);
					return false;
				}
			}
			$qty.val(currentVal + 1);
		}
    });
    $('.minus_max').on('click',function(){
        var $qty=$(this).closest('div.input-group').find('.form-control');
        var currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 0) {
            $qty.val(currentVal - 1);
        }
    });
	$('.add_min').on('click',function(){
        var $qty=$(this).closest('div.input-group').find('.form-control');
        var currentVal = parseInt($qty.val());
		var className = $($qty).attr('id');
		var classNameBefore = className.replace(className.substr(1),'_max');
		if (!isNaN(currentVal) && currentVal >= 100) {
			swal(
				'Oops...',
				'Nilai maksimal tidak boleh di atas 100',
				'error'
			);
        } else{
			if(className == 'a_min'){
				$qty.val(currentVal + 1);
				return false;
			}
			if(className == 'd_min'){
				swal(
					'Oops...',
					'Minimal Nilai D tidak boleh lebih besar dari 0',
					'error'
				);
				return false;
			}
			if(className != 'a_max'){
				var minimal = $('#'+className).val();
				var minimal_plus = (parseInt(minimal) + 1);
				var maksimal = $('#'+ThisChar(classNameBefore.substr(0,1))+'_max').val();
				if(minimal_plus >= maksimal){
					swal(
						'Oops...',
						'Minimal Nilai '+ ThisChar(className.substr(0,1)).toUpperCase() +' tidak boleh sama atau lebih besar dari Maksimal Nilai '+ ThisChar(className.substr(0,1)).toUpperCase(),
						'error'
					);
					return false;
				}
			}
			$qty.val(currentVal + 1);
		}
    });
    $('.minus_min').on('click',function(){
        var $qty=$(this).closest('div.input-group').find('.form-control');
        var currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 0) {
            $qty.val(currentVal - 1);
        }
    });
});
</script>