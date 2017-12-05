<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="row">
<!-- left column -->
<div class="col-md-12">
<div class="box box-info">
	<div class="box-header">
	<h3 class="box-title"><?php echo $page_title.' ('.$total_rows.' => '.$inserted. ')'; ?></h3>
	</div>
    <div class="box-body">
	<div class="text-center">
		<?php echo $pagination; ?>
	</div>
	<table class="table table-bordered table-striped table-hover">
            <thead>
				<tr>
					<th class="text-center">No</th>
					<th class="text-center">Nama</th>
					<th class="text-center">No Induk</th>
					<th class="text-center">NISN</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('5') + 1;
				foreach($dapodik as $data){
					$find_diterima_kelas = diterima_kelas($data->peserta_didik_id);
					//test($find_diterima_kelas);
					//test($data);
					//die();
					$nama_siswa 		= addslashes($data->nama_siswa);
					$nama_ibu_kandung 	= addslashes($data->nama_ibu_kandung);
					if($data->nisn){
						$find_data_siswa = $this->siswa->find("nama = '$nama_siswa' AND nisn = '$data->nisn' AND tanggal_lahir = '$data->tanggal_lahir'");
					//Datasiswa::find_by_nama_and_nisn_and_tanggal_lahir($parameter->nama,$parameter->nisn,$tanggal_lahir);
					} else {
						$find_data_siswa = $this->siswa->find("nama = '$nama_siswa' AND tanggal_lahir = '$data->tanggal_lahir' AND nama_ibu = '$nama_ibu_kandung'");
					//Datasiswa::find_by_nama_and_tanggal_lahir_and_nama_ibu($parameter->nama,$tanggal_lahir,$parameter->nama_ibu);
					}
					$data->nisn = ($data->nisn) ? $data->nisn : GenerateNISN();
					$data->email = ($data->email) ? $data->email : GenerateEmail().'@eraporsmk.net';
					$insert_siswa = array(
						"sekolah_id"		=> $loggeduser->sekolah_id,
						'nama' 				=> stripslashes($nama_siswa),
						'no_induk' 			=> $data->nipd,
						'nisn' 				=> $data->nisn,
						'jenis_kelamin' 	=> $data->jenis_kelamin,
						'tempat_lahir' 		=> $data->tempat_lahir,
						'tanggal_lahir' 	=> $data->tanggal_lahir,
						'agama' 			=> $data->agama_id,
						'status' 			=> 'Anak Kandung',
						'anak_ke' 			=> $data->anak_keberapa,
						'alamat' 			=> $data->alamat_jalan,
						'rt' 				=> $data->rt,
						'rw' 				=> $data->rw,
						'desa_kelurahan' 	=> $data->desa_kelurahan,
						'kecamatan' 		=> $data->kecamatan,
						'kode_pos' 			=> $data->kode_pos,
						'no_telp' 			=> $data->nomor_telepon_seluler,
						'sekolah_asal' 		=> $data->sekolah_asal,
						'diterima_kelas' 	=> $find_diterima_kelas,
						'diterima' 			=> $data->tanggal_masuk_sekolah,
						'email' 			=> $data->email,
						'nama_ayah' 		=> $data->nama_ayah,
						'nama_ibu' 			=> $data->nama_ibu_kandung,
						'kerja_ayah' 		=> $data->pekerjaan_id_ayah,
						'kerja_ibu' 		=> $data->pekerjaan_id_ibu,
						'nama_wali' 		=> $data->nama_wali,
						'alamat_wali' 		=> $data->alamat_jalan,
						'telp_wali' 		=> $data->nomor_telepon_seluler,
						'kerja_wali' 		=> $data->pekerjaan_id_wali,
						'photo' 			=> 0,
						'active' 			=> 1,
						'password' 			=> '12345678',
						'petugas' 			=> $loggeduser->username,
						'siswa_id_dapodik' 	=> $data->peserta_didik_id,
					);
					$additional_data = array(
						"sekolah_id"		=> $loggeduser->sekolah_id,
						"nisn"				=> $insert_siswa['nisn'],
						"nipd"				=> $data->nipd,
					);
					$find_rombel = $this->rombongan_belajar->find_by_rombel_id_dapodik($data->rombongan_belajar_id);
					$rombongan_belajar_id = ($find_rombel) ? $find_rombel->id : 0;
					if($find_data_siswa){
						$find_user = $this->user->find_by_siswa_id($find_data_siswa->id);
						$user_id = ($find_user) ? $find_user->id : 0;
						$insert_siswa['user_id'] = $user_id;
						$this->siswa->update($find_data_siswa->id, $insert_siswa);
						$update_user = array(
							'nisn'	=> $data->nisn,
							'email'	=> $data->email,
						);
						$this->ion_auth->update($user_id, $update_user);
						$attributes_update_anggota_rombel = array(
							'semester_id' 				=> $ajaran->id, 
							'rombongan_belajar_id' 		=> $rombongan_belajar_id, 
							'siswa_id' 					=> $find_data_siswa->id,
							'anggota_rombel_id_dapodik'	=> $data->anggota_rombel_id,
						);
						$find_anggota_rombel = $this->anggota_rombel->find("semester_id = $ajaran->id AND siswa_id = $find_data_siswa->id");
						if($find_anggota_rombel){
							$this->anggota_rombel->update($find_anggota_rombel->id, $attributes_update_anggota_rombel);
						} else {
							$this->anggota_rombel->insert($attributes_update_anggota_rombel);
						}
						$result = 'update';
					} else {
						$group = array('4');
						$user_id = $this->ion_auth->register(stripslashes($nama_siswa), $insert_siswa['password'], $insert_siswa['email'], $additional_data, $group);		
						if($user_id){
							$insert_siswa['user_id'] = $user_id;
							$datasiswa = $this->siswa->insert($insert_siswa);
							$attributes_insert_anggota_rombel = array(
								'semester_id' 				=> $ajaran->id, 
								'rombongan_belajar_id' 		=> $rombongan_belajar_id, 
								'siswa_id' 					=> $datasiswa,
								'anggota_rombel_id_dapodik'	=> $data->anggota_rombel_id,
							);			
							$anggota = $this->anggota_rombel->insert($attributes_insert_anggota_rombel);
							$this->user->update($user_id, array('siswa_id' => $datasiswa));
						}
						$result = 'insert';
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td><?php echo stripslashes($nama_siswa); ?></td>
					<td><?php echo $data->nipd; ?></td>
					<td><?php echo $data->nisn; ?></td>
					<td><?php echo $result; ?></td>
				</tr>
			<?php
			//break; 
			} ?>
			</tbody>
		</table>
    </div><!-- /.box-body -->
	<div class="box-footer text-center">
		<?php echo $pagination; ?>
	</div>
</div><!-- /.box -->
</div>
<script>
$(document).ready(function(){
	$('body').mouseover(function(){
		$(this).css({cursor: 'none'});
	});
	var cari = $('body').find('.next');
	if(cari.length>0){
		var cari_a = $(cari).find('a');
		var url = $(cari_a).attr('href');
		window.location.replace(url);
	} else {
		window.location.replace('<?php echo site_url('admin/sinkronisasi'); ?>');
	}
})
</script>