<?php
$user = $this->ion_auth->user()->row();
$guru_id = ($user->guru_id) ? $user->guru_id : 0;
$ajaran = get_ta();
if(!$guru_id){
$data_guru	= $this->guru->find_by_user_id($user->id);
$guru_id = ($data_guru) ? $data_guru->id : 0;
$this->user->update($user->id, array('guru_id' => $guru_id));
}
$cari_mapel = $this->pembelajaran->find_all("semester_id = $ajaran->id and guru_id = $guru_id");
//$cari_mulok = Mulok::find_all_by_ajaran_id_and_guru_id($ajaran->id, $user->data_guru_id);
$cari_rombel = $this->rombongan_belajar->find("guru_id = $guru_id and semester_id= $ajaran->id");
?>
<h4 class="page-header">Mata Pelajaran yang diampu di Tahun Pelajaran <?php echo $ajaran->tahun; ?> Semester <?php echo $ajaran->semester; ?></h4>
<div class="row">
	<div class="col-lg-12 col-xs-12">
	<?php if($cari_mapel){ ?>
		<table class="table table-bordered table-striped table-hover datatable">
			<thead>
				<tr>
					<th style="width: 10px">#</th>
					<th>Mata Pelajaran</th>
					<th>Rombel</th>
					<th>Wali Kelas</th>
					<th class="text-center">Jumlah Siswa</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$i=1;
				foreach($cari_mapel as $m){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo get_nama_mapel($m->mata_pelajaran_id).' ('.$m->mata_pelajaran_id.')'; ?></td>
					<td><?php echo get_nama_rombel($m->rombongan_belajar_id); ?></td>
					<td><?php echo get_wali_kelas($m->rombongan_belajar_id); ?></td>
					<td class="text-center"><?php echo get_jumlah_siswa($m->rombongan_belajar_id); ?></td>
				</tr>
				<?php $i++;
				 }
				 ?>
			</tbody>
		</table>
	<?php } else { ?>
			<table class="table table-bordered table-striped table-hover">
				<tr><td class="text-center">Anda tidak memiliki jadwal mengajar!</td></tr>
			</table>
		<?php } if($cari_rombel){
		$data['cari_rombel'] = $cari_rombel;
		$this->load->view('backend/dashboard/walas', $data);
		}
		?>
	</div>
</div>