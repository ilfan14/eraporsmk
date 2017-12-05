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
                <th style="width:2%" class="text-center"><label><input type="checkbox" id="checkall_atas" title="Select all"/></label></th>
                <!--th style="width: 8%">Tahun Pelajaran</th-->
				<th style="width: 15%">Nama Ekstrakurikuler</th>
                <th style="width: 15%">Nama Pembina</th>
				<th style="width: 15%">Nama Ketua</th>
                <th style="width: 15%">Nomor Kontak</th>
                <th style="width: 15%">Alamat</th>
                <th style="width: 8%" class="text-center">Tindakan</th>
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