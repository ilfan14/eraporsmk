<div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<?php 
		$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
		echo form_open($form_action,$attributes);
		$all_ppk = $this->ref_ppk->get_all();
	?>
	<input type="hidden" name="ppk_id" value="<?php echo $ppk_id; ?>" />
    <div class="box-body">
		<table class="table table-bordered miring">
            <thead>
            <tr>
                <th style="width: 5%; vertical-align:middle;" class="text-center">No.</th>
				<th style="width: 20%; vertical-align:middle;">Nama Siswa</th>
				<?php foreach($all_ppk as $ppk){?>
				<th class="text-center"><span><?php echo $ppk->nama; ?></span></th>
				<?php } ?>
				<th style="width: 20%; vertical-align:middle;" class="text-center">Catatan</th>
            </tr>
            </thead>
			<tbody>
				<?php
				$i=1;
				foreach($data_siswa as $siswa){
					$siswa_id = $siswa->siswa->id;
					$catatan_ppk = $this->catatan_ppk->find("ppk_id = $ppk_id AND siswa_id = $siswa_id");
					$ref_ppk_id = ($catatan_ppk) ? unserialize($catatan_ppk->ref_ppk_id) : array(0);
					//Catatanppk::find_by_ppk_id_and_siswa_id($ppk_id, $siswa->datasiswa_id);
				?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td><?php echo get_nama_siswa($siswa_id); ?> <input type="hidden" name="siswa_id[]" value="<?php echo $siswa_id; ?>" /></td>
					<?php foreach($all_ppk as $ppk){?>
					<td>
					<!--textarea class="form-control" rows="3" name="catatan[]"><?php echo ($catatan_ppk) ? $catatan_ppk->catatan : ''; ?></textarea-->
					<div class="text-center">
						<input class="icheck" type="checkbox" name="ref_ppk_id[]" value="<?php echo $ppk->id ?>"<?php if(in_array($ppk->id, $ref_ppk_id)) echo ' checked="checked"';?> />
					</div>
					</td>
					<?php } ?>
					<td>
					<textarea class="form-control" rows="3" name="catatan[]"><?php echo ($catatan_ppk) ? $catatan_ppk->catatan : ''; ?></textarea>
					</td>
				</tr>
				<?php $i++; } ?>
			</tbody>
		</table>
    </div><!-- /.box-body -->
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<?php echo form_close();  ?>
</div><!-- /.box -->
</div>
</div>
<script>
$(function() {
    var header_height = 0;
    $('table th span').each(function() {
        if ($(this).outerWidth() > header_height) header_height = $(this).outerWidth();
    });

    $('table th').height(header_height);
});
</script>