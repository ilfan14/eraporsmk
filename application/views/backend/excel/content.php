<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
        <table id="datatable" class="table table-condensed table-bordered">
            <thead>
            <tr>
                <td style="width: 20%">File</td>
                <td style="width: 3%" class="text-center">Jumlah Data</td>
				<td style="width: 3%" class="text-center">Status</td>
                <td style="width: 5%" class="text-center">Action</td>
            </tr>
            </thead>
			<tbody>
			<?php
				/*foreach($map as $m){
					$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $m);
					$find_dbf = DataDbf::find_by_file($withoutExt);
					if($find_dbf){
						$btn = '<a class="btn btn-sm btn-danger" href="'.site_url('admin/dbf/proses/'.$withoutExt).'">Perbaharui</a>';
						$btn = '<a class="btn btn-sm btn-danger" href="'.site_url('admin/dbf/data_calon/'.$withoutExt).'">Perbaharui</a>';
						$status = 'Sudah';
						$data_calon = DataCalon::find_all_by_id_dbf($find_dbf->id);
						$jumlah_data = count($data_calon);
					} else {
						$status = 'Belum';
						$btn = '<a class="btn btn-sm btn-success" href="'.site_url('admin/dbf/proses/'.$withoutExt).'">Ekstrak Data</a>';
						$btn = '<a class="btn btn-sm btn-success" href="'.site_url('admin/dbf/data_calon/'.$withoutExt).'">Ekstrak Data</a>';
						$jumlah_data = 0;
					}
					echo '<tr>';
					echo '<td>'.$withoutExt.'</td>';
					echo '<td class="text-center">'.$jumlah_data.'</td>';
					echo '<td class="text-center">'.$status.'</td>';
					echo '<td class="text-center">'.$btn.'</td>';
					echo '</tr>';
				}*/
			?>
			</tbody>
		</table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>