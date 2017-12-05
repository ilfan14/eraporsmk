<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<div class="row" style="margin-bottom:10px;">
		<?php 
			$tingkat_pendidikan = $this->tingkat_pendidikan->get_all();
			$get_jurusan = $this->jurusan_sp->find_all("sekolah_id = $sekolah_id AND semester_id = $ajaran->id");
		?>
			<div class="col-md-4">
				<select id="filter_jurusan" class="form-control">
					<option value="">==Filter Berdasar Kompetensi Keahlian==</option>
					<?php foreach($get_jurusan as $jurusan){ ?>
					<option value="<?php echo $jurusan->kurikulum_id; ?>"><?php echo get_jurusan($jurusan->kurikulum_id); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-4">
				<select id="filter_tingkat" class="form-control" style="display:none;">
					<option value="">==Filter Berdasar Tingkat==</option>
					<?php foreach($tingkat_pendidikan as $tingkat){ ?>
					<option value="<?php echo $tingkat->id; ?>"><?php echo $tingkat->nama; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-4">
				<select id="filter_rombel" class="form-control" style="display:none;"></select>
			</div>
		</div>
        <table id="datatable" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
				<th style="vertical-align:middle;width:2%" class="text-center"><label><input type="checkbox" id="checkall_atas" title="Select all"/></label></th>
                <th style="vertical-align:middle" width="30%">Mata Pelajaran</th>
                <th style="vertical-align:middle" width="10%">Kelas</th>
                <th style="vertical-align:middle" width="10%">Aktifitas Penilaian</th>
                <th class="text-center" style="vertical-align:middle" width="8%">Teknik Penilaian</th>
                <th style="vertical-align:middle" width="10%">Bobot</th>
				<th class="text-center" width="5%">Jumlah <br />KD</th>
                <th  style="vertical-align:middle;width: 5%" class="text-center">Tindakan</th>
            </tr>
            </thead>
			<tbody>
			</tbody>
			<tfoot>
			<tr>
				<th style="width:2%" class="text-center"><label><input type="checkbox" id="checkall_bawah" title="Select all"/></label></th>
				<th colspan="5">
				<a class="delete_all btn btn-danger btn-sm btn_delete"><i class="fa fa-trash-o"></i> Hapus Data Terpilih</a>
				</th>
			</tr>
			</tfoot>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>