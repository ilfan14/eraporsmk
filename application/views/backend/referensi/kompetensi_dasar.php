<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
        <?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<div class="row" style="margin-bottom:10px;">
		<?php
		$this->db->select('*');
		$this->db->from('pembelajaran');
		//$this->db->where('semester_id', $ajaran->id);
		$this->db->where('guru_id', $user->guru_id);
		$this->db->group_by('mata_pelajaran_id');
		$query = $this->db->get();
		$cari_mapel = $query->result();
		//$get_jurusan = $this->jurusan_sp->find_all("sekolah_id = $user->sekolah_id");
		?>
		<input type="hidden" id="filter_tingkat" value="0" />
		<input type="hidden" id="filter_jurusan" value="0" />
			<div class="col-md-6">
				<select id="filter_mapel" class="form-control">
					<option value="">==Filter Berdasar Mata Pelajaran==</option>
					<?php /*foreach($get_jurusan as $jurusan){ ?>
					<option value="<?php echo $jurusan->kurikulum_id; ?>"><?php echo get_jurusan($jurusan->kurikulum_id).'('.$jurusan->kurikulum_id.')'; ?></option>
					<?php } */?>
					<?php foreach($cari_mapel as $mapel){ ?>
					<option value="<?php echo $mapel->mata_pelajaran_id; ?>"><?php echo get_nama_mapel($mapel->mata_pelajaran_id).'('.$mapel->mata_pelajaran_id.')'; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-6">
				<select id="filter_kompetensi" class="form-control" style="display:none;">
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
                <th style="width: 15%">Mata Pelajaran</th>
				<th style="width: 5%" class="text-center">Kode</th>
                <th style="width: 5%" class="text-center">Kelas</th>
				<th style="width: 20%">Isi Kompetensi</th>
                <th style="width: 20%">Ringkasan Kompetensi</th>
                <th style="width: 5%" class="text-center">Tindakan</th>
            </tr>
            </thead>
			<tbody>
			</tbody>      
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>