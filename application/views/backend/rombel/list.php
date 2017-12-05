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
                <th style="width:2%" class="text-center"><label><input type="checkbox" id="checkall_atas" title="Select all"/></label></th>
                <th style="width: 10%">Nama Rombel</th>
                <th style="width: 25%">Wali Kelas</th>
                <th style="width: 5%">Tingkat</th>
				<th style="width: 5%" class="text-center">Pembelajaran</th>
				<th style="width: 10%" class="text-center">Anggota Rombel</th>
                <th style="width: 10%" class="text-center">Kenaikan</th>
                <th style="width: 5%" class="text-center">Edit</th>
            </tr>
            </thead>
			<tbody>
			</tbody>
			<tfoot>
			<tr>
				<th style="width:2%" class="text-center"><label><input type="checkbox" id="checkall_bawah" title="Select all"/></label></th>
				<th colspan="6">
				<!--a class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Hapus Data Terpilih</a-->
				<a class="delete_all btn btn-danger btn-sm btn_delete"><i class="fa fa-trash-o"></i> Hapus Data Terpilih</a>
				</th>
			</tr>
			</tfoot>      
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>