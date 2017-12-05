<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
        <?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
		<?php if($extension){?>
	<div class="callout callout-danger lead">Ekstensi <?php echo $extension; ?> belum aktif. Aktifkan dulu ekstensi tersebut di php.ini!</div>
	<?php }elseif(!$connected){?>
	<div class="callout callout-danger lead">PC/Laptop yang anda gunakan tidak terhubung ke dapodik!</div>
	<?php } else { ?>
		<div class="row" style="margin-bottom:10px;">
		<?php 
			$tingkat_pendidikan = $this->tingkat_pendidikan->get_all();
			//$data_kompetensi = Keahlian::all();
			$jurusan_sp = $this->jurusan_sp->find_all_by_semester_id($ajaran->id);
		?>
			<div class="col-md-6">
				<select id="filter_jurusan" class="form-control">
					<option value="">==Filter Berdasar Kompetensi Keahlian==</option>
					<?php foreach($jurusan_sp as $jurusan){ ?>
					<option value="<?php echo $jurusan->kurikulum_id; ?>"><?php echo get_jurusan($jurusan->kurikulum_id); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-6">
				<select id="filter_tingkat" class="form-control" style="display:none;">
					<option value="">==Filter Berdasar Tingkat==</option>
					<?php foreach($tingkat_pendidikan as $tingkat){ ?>
					<option value="<?php echo $tingkat->id; ?>"><?php echo $tingkat->nama; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
        <table id="datatable" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th style="width: 10%">Nama Rombel</th>
                <th style="width: 25%" class="text-center">Jumlah Pembelajaran eRapor</th>
                <th style="width: 25%" class="text-center">Jumlah Pembelajaran Dapodik</th>
				<th style="width: 10%" class="text-center">Detil Nilai</th>
                <!--th style="width: 10%" class="text-center">Proses Kirim</th-->
            </tr>
            </thead>
			<tbody>
			</tbody>     
        </table>
		<?php } ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>