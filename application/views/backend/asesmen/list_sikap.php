<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<div class="row" style="margin-bottom:10px;">
		<?php 
			$get_jurusan = $this->jurusan_sp->find_all("sekolah_id = $sekolah_id AND semester_id = $ajaran->id");
			$tingkat_pendidikan = $this->tingkat_pendidikan->get_all();
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
                <th style="width: 20%">Nama Siswa</th>
				<th style="width: 10%">Rombel/Tingkat</th>
				<th style="width: 20%">Mata Pelajaran</th>
                <th style="width: 10%" class="text-center">Butir Sikap</th>
                <th style="width: 10%" class="text-center">Opsi Sikap</th>
                <th style="width: 35%" class="text-center">Uraian Sikap</th>
            </tr>
            </thead>
			<tbody>
			</tbody>
		</table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>