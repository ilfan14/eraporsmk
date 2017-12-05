<div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
    <div class="box-body">
		<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center">No</th>
					<?php $fields = $this->db->list_fields('ref_siswa');
					foreach($fields as $field){ ?>
					<th class="text-center" style="width:5px;"><?php echo $field; ?></th>
					<?php } ?>
	            </tr>
            </thead>
			<tbody>
				<?php 
				$no = $this->uri->segment('6') + 1;
				foreach($dapodik as $data){ 
				test($data);
				?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $sekolah; ?></td>
						<td><?php echo $d->peserta_didik_id; ?></td>
						<td>0</td>
						<td><?php echo $d->nama_siswa; ?></td>
						<td><?php echo $d->nipd; ?></td>
						<td><?php echo $d->nisn; ?></td>
						<td><?php echo $d->jenis_kelamin; ?></td>
						<td><?php echo $d->tempat_lahir; ?></td>
						<td><?php echo $d->tanggal_lahir; ?></td>
						<td><?php echo $d->agama_id; ?></td>
						<td><?php echo $d->status_data; ?></td>
						<td>0</td>
						<td><?php echo $d->alamat_jalan; ?></td>
						<td><?php echo $d->rt; ?></td>
						<td><?php echo $d->rw; ?></td>
						<td><?php echo $d->desa_kelurahan; ?></td>
						<td><?php echo $d->kecamatan; ?></td>
						<td><?php echo $d->kode_pos; ?></td>
						<td><?php echo $d->nomor_telepon_seluler; ?></td>
						<td><?php echo $d->sekolah_asal; ?></td>
						<td>0</td>
						<td><?php echo $d->tanggal_masuk_sekolah; ?></td>
						<td><?php echo $d->email; ?></td>
						<td><?php echo $d->nama_ayah; ?></td>
						<td><?php echo $d->pekerjaan_id_ayah; ?></td>
						<td><?php echo $d->nama_ibu_kandung; ?></td>
						<td><?php echo $d->pekerjaan_id_ibu; ?></td>
						<td><?php echo $d->nama_wali; ?></td>
						<td><?php echo $d->alamat_jalan; ?></td>
						<td><?php echo $d->nomor_telepon_seluler; ?></td>
						<td><?php echo $d->pekerjaan_id_wali; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>