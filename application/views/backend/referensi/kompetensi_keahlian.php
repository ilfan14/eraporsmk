<div class="row">
<!-- left column -->
<div class="col-md-12">
	<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
	<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
	<div class="box box-info">
		<div class="box-body">
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 25%">Bidang Keahlian</th>
						<th style="width: 25%">Program Keahlian</th>
						<th style="width: 25%">Kompetensi Keahlian</th>
						<th style="width: 8%" class="text-center">Tindakan</th>
					</tr>
				</thead>
				<tbody>
				</tbody>      
			</table>
		</div><!-- /.box-body -->
	</div><!-- /.box -->
</div>
</div>