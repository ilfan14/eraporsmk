<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<?php echo (isset($message) && $message != '') ? error_msg($message) : '';?>
<div class="box box-info">
    <div class="box-body">
		<table id="datatable" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
				<th style="width: 20%">Nama Siswa</th>
                <th style="width: 70%">Capaian</th>
				<!--th style="width: 20%">Tempat Kegiatan</th>
				<th style="width: 20%">Penanggung Jawab</th-->
                <th style="width: 10%" class="text-center">Aksi</th>
            </tr>
            </thead>
			<tbody>
			</tbody>
		</table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>