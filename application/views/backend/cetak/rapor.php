<?php
$data_siswa = get_siswa_by_rombel($rombel_id);
//$rombel = Datarombel::find($rombel_id);
if(isset($query) && $query == 'wali'){
?>
<div class="row">
<div class="col-md-12">
<div class="box box-info">
    <div class="box-body">
<?php } ?>
<div style="clear:both;"></div>
<div class="table-responsive no-padding">
		<table class="table table-bordered table-hover" style="display:none;">
			<tr>
				<td class="text-center">
					<p>Download Kelengkapan Rapor Kelas <?php echo get_nama_rombel($rombel_id); ?></p>
					<p><a href="<?php echo site_url('admin/cetak/rapor_top_all/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id); ?>" target="_blank" class="btn btn-social-icon btn-dropbox tooltip-left" title="Download Cover Rapor Kelas <?php echo get_nama_rombel($rombel_id); ?>">
						<i class="fa fa-cloud-download"></i></a></p>
				</td>
				<td class="text-center">
					<p>Download Nilai Rapor Kelas <?php echo get_nama_rombel($rombel_id); ?></p>
					<p><a href="<?php echo site_url('admin/cetak/rapor_nilai_all/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id); ?>" target="_blank" class="btn btn-social-icon btn-google tooltip-left" title="Download Nilai Rapor Kelas <?php echo get_nama_rombel($rombel_id); ?>">
						<i class="fa fa-cloud-download"></i>
					</a></p>
				</td>
				<td class="text-center">
					<p>Download Berkas Pendukung Rapor Kelas <?php echo get_nama_rombel($rombel_id); ?></p>
					<p><a href="<?php echo site_url('admin/cetak/rapor_bottom_all/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id); ?>" target="_blank" class="btn btn-social-icon btn-facebook tooltip-left" title="Download Legger Sikap Kelas <?php echo get_nama_rombel($rombel_id); ?>">
					<i class="fa fa-cloud-download"></i></a></p>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="40%" style="vertical-align:middle;">Nama Siswa</th>
				<th width="10%" style="vertical-align:middle;" class="text-center">Lihat Nilai</th>
				<th width="10%" class="text-center">Lihat Deskripsi</th>
				<th width="10%" class="text-center">Cetak Rapor (Cover)</th>
				<th width="10%" class="text-center">Cetak Rapor (Nilai)</th>
				<th width="10%" class="text-center">Cetak Rapor PPK (Cover)</th>
				<th width="10%" class="text-center">Cetak Rapor PPK (Capaian)</th>
				<!--th width="10%" class="text-center">Cetak Rapor (PDF) V.2</th-->
				<!--th width="10%" class="text-center">Cetak Rapor (WORD)</th-->
			</tr>
		</thead>
		<tbody>
			<?php 
			if($data_siswa){
				foreach($data_siswa as $siswa){
			?>
			<tr>
				<td><?php echo $siswa->siswa->nama; ?></td>
				<td class="text-center">
					<a href="<?php echo site_url('admin/laporan/review_nilai/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id.'/'.$siswa->siswa->id); ?>" target="_blank" class="btn btn-social-icon btn-dropbox tooltip-left" title="Lihat nilai <?php echo $siswa->siswa->nama; ?>">
						<i class="fa fa-search"></i></a>
				</td>
				<td class="text-center">
					<a href="<?php echo site_url('admin/laporan/review_desc/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id.'/'.$siswa->siswa->id); ?>" target="_blank" class="btn btn-social-icon btn-flickr tooltip-left" title="Lihat deskripsi <?php echo $siswa->siswa->nama; ?>">
						<i class="fa fa-search"></i>
					</a>
				</td>
				<td class="text-center">
					<a href="<?php echo site_url('admin/cetak/rapor_top/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id.'/'.$siswa->siswa->id); ?>" target="_blank" class="btn btn-social-icon btn-foursquare tooltip-left" title="Cetak rapor <?php echo $siswa->siswa->nama; ?>">
					<i class="fa fa-fw fa-file-pdf-o"></i></a>
				</td>
				<td class="text-center">
					<a href="<?php echo site_url('admin/cetak/rapor_pdf/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id.'/'.$siswa->siswa->id); ?>" target="_blank" class="btn btn-social-icon btn-github tooltip-left" title="Cetak rapor <?php echo $siswa->siswa->nama; ?>">
					<i class="fa fa-fw fa-file-pdf-o"></i></a>
				</td>
				<td class="text-center">
					<a href="<?php echo site_url('admin/cetak/cover_ppk/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id.'/'.$siswa->siswa->id); ?>" target="_blank" class="btn btn-social-icon btn-instagram tooltip-left" title="Cetak rapor PPK <?php echo $siswa->siswa->nama; ?>">
					<i class="fa fa-fw fa-file-pdf-o"></i></a>
				</td>
				<td class="text-center">
					<a href="<?php echo site_url('admin/cetak/rapor_ppk/'.$nama_kompetensi.'/'.$ajaran_id.'/'.$rombel_id.'/'.$siswa->siswa->id); ?>" target="_blank" class="btn btn-social-icon btn-tumblr tooltip-left" title="Cetak rapor PPK <?php echo $siswa->siswa->nama; ?>">
					<i class="fa fa-fw fa-file-pdf-o"></i></a>
				</td>
			</tr>
			<?php
				}
			} else {
			?>
			<tr>
				<td colspan="5" class="text-center">Tidak ada data siswa di rombongan belajar terpilih</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<?php if(isset($query) && $query == 'waka'){?>
</div>
</div>
</div>
</div>
<?php } ?>
<script>
$('.tooltip-left').tooltip({
    placement: 'left',
    viewport: {selector: 'body', padding: 2}
  })
</script>