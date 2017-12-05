<?php
$admin_group = array(1,2);
?>
<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<div class="row" style="margin-bottom:10px;">
		<?php
		$get_jurusan = $this->jurusan_sp->find_all("sekolah_id = $sekolah_id");
		?>
			<div class="col-md-6">
				<select id="filter_jurusan" class="form-control">
					<option value="">==Filter Berdasar Kompetensi Keahlian==</option>
					<?php foreach($get_jurusan as $jurusan){ ?>
					<option value="<?php echo $jurusan->kurikulum_id; ?>"><?php echo get_jurusan($jurusan->kurikulum_id).'('.$jurusan->kurikulum_id.')'; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-6">
				<select id="filter_tingkat" class="form-control" style="display:none;">
					<option value="">==Filter Berdasar Tingkat Kelas==</option>
					<option value="10">Kelas 10</option>
					<option value="11">Kelas 11</option>
					<option value="12">Kelas 12</option>
					<option value="13">Kelas 13</option>
				</select>
			</div>
		</div>
        <table id="datatable" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
				<?php if($this->ion_auth->in_group($admin_group)){ ?>
                <th style="width:2%" class="text-center"><label><input type="checkbox" id="checkall_atas" title="Select all"/></label></th>
				<?php } ?>
                <th style="width: 40%">Nama</th>
                <th style="width: 3%" class="text-center">L/P</th>
                <th style="width: 20%">Tempat, Tanggal Lahir</th>
				<th style="width: 10%" class="text-center">Agama</th>
                <th style="width: 10%" class="text-center">Rombel/Tingkat</th>
                <th style="width: 10%" class="text-center">Tindakan</th>
            </tr>
            </thead>
			<tbody>
			</tbody>
			<?php if($this->ion_auth->in_group($admin_group)){ ?>
			<tfoot>
			<tr>
				<th style="width:2%" class="text-center"><label><input type="checkbox" id="checkall_bawah" title="Select all"/></label></th>
				<th colspan="5">
				<a class="delete_all btn btn-danger btn-sm btn_delete"><i class="fa fa-trash-o"></i> Hapus Data Terpilih</a>
				</th>
			</tr>
			</tfoot>
			<?php } ?>
		</table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>