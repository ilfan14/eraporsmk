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
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center">nama</th>
					<th class="text-center">jenis_kelamin</th>
					<th class="text-center">tempat_lahir</th>
					<th class="text-center">tanggal_lahir</th>
					<th class="text-center">nik</th>
					<th class="text-center">nuptk</th>
					<th class="text-center">email</th>
					<th class="text-center">status</th>
	            </tr>
            </thead>
			<tbody>
			<?php
				$no = $this->uri->segment('5') + 1;
				foreach($dapodik as $data){
					$data->nuptk = str_replace(' ','',$data->nuptk);
					$this->_database->select('*');
					$this->_database->from('rwy_pend_formal');
					$this->_database->where('ptk_id', $data->ptk_id);
					$this->_database->where('gelar_akademik_id IS NOT NULL');
					$query = $this->_database->get();
					$find_gelar = $query->result();
					//test($find_gelar);
					$query = $this->_database->get_where('ptk', array('ptk_id' => $data->ptk_id));
					$ptk = $query->row();
					$nama_ptk 		= addslashes($ptk->nama);
					if($ptk->nuptk && is_numeric($ptk->nuptk) && strlen($ptk->nuptk) > 10){
						$ptk->nuptk = $ptk->nuptk;
						$data_guru = $this->guru->find("nama = '$nama_ptk' AND nuptk = '$ptk->nuptk' AND tanggal_lahir = '$ptk->tanggal_lahir'");
						//Dataguru::find_by_nama_and_nuptk_and_tanggal_lahir($ptk->nama,$ptk->nuptk,$ptk->tanggal_lahir);
					} else {
						$ptk->nuptk = GenerateID();
						$data_guru = $this->guru->find("nama = '$nama_ptk' AND tanggal_lahir = '$ptk->tanggal_lahir'");
						//Dataguru::find_by_nama_and_tanggal_lahir($ptk->nama,$ptk->tanggal_lahir);
					}
					$ptk->email = ($ptk->email) ? $ptk->email : GenerateEmail().'@eraporsmk.net';
					//$ptk->nuptk = ($ptk->nuptk) ? $ptk->nuptk : GenerateID();
					if($ptk->email == $loggeduser->email){
						$ptk->email = GenerateEmail().'@eraporsmk.net';
					}
					$query = $this->_database->get_where('ref.mst_wilayah', array('kode_wilayah' => $ptk->kode_wilayah));
					$kecamatan = $query->row();
					$additional_data = array(
						"sekolah_id"=> $loggeduser->sekolah_id,
						"nuptk"		=> $ptk->nuptk,
					);
					$password = 12345678;
					if($data_guru){
						$result = 'update';
						$this->guru->update($data_guru->id, array('guru_id_dapodik' => $ptk->ptk_id));
						$this->user->update($data_guru->user_id, array('guru_id' => $data_guru->id));
						$find_guru_aktif = $this->guru_terdaftar->find("guru_id = $data_guru->id and semester_id = $ajaran->id");
						if($find_guru_aktif){
							$update_guru_aktif = array(
								'status' => 1
							);
							$this->guru_terdaftar->update($find_guru_aktif->id, $update_guru_aktif);
						} else {
							$attributes = array('semester_id' => $ajaran->id, 'guru_id' => $data_guru->id, 'status' => 1);			
							$guru_aktif = $this->guru_terdaftar->insert($attributes);
						}
						if($find_gelar){
							foreach($find_gelar as $gelar){
								if($gelar->gelar_akademik_id){
									$find_gelar_ptk = $this->gelar_ptk->find("ptk_id = '$data->ptk_id' AND gelar_akademik_id = $gelar->gelar_akademik_id");
									if($find_gelar_ptk){
										$this->gelar_ptk->delete($find_gelar_ptk->id);
									}
									//if(!$find_gelar_ptk){
									//	$this->gelar_ptk->insert(array('gelar_akademik_id' => $gelar->gelar_akademik_id, 'ptk_id' => $data->ptk_id, 'guru_id' => $data_guru->id));
									//}
									$this->gelar_ptk->insert(array('gelar_akademik_id' => $gelar->gelar_akademik_id, 'ptk_id' => $data->ptk_id, 'guru_id' => $data_guru->id));
								}
							}
						}
					} else {
						$group = array('3');
						$user_id = $this->ion_auth->register(stripslashes($nama_ptk), $password, $ptk->email, $additional_data, $group);
						if($user_id){
							$insert_guru = array(
								'sekolah_id' 			=> $loggeduser->sekolah_id,
								'user_id' 				=> $user_id,
								'nama' 					=> stripslashes($nama_ptk),
								'nuptk' 				=> $ptk->nuptk,
								'nip' 					=> $ptk->nip,
								'nik' 					=> $ptk->nik,
								'jenis_kelamin' 		=> $ptk->jenis_kelamin,
								'tempat_lahir' 			=> $ptk->tempat_lahir,
								'tanggal_lahir' 		=> $ptk->tanggal_lahir,
								'status_kepegawaian_id'	=> $ptk->status_kepegawaian_id,
								'jenis_ptk' 			=> $ptk->jenis_ptk_id,
								'agama_id' 				=> $ptk->agama_id,
								'alamat' 				=> $ptk->alamat_jalan,
								'rt' 					=> $ptk->rt,
								'rw' 					=> $ptk->rw,
								'desa_kelurahan' 		=> $ptk->desa_kelurahan,
								'kecamatan' 			=> $kecamatan->nama,
								'kode_pos'				=> $ptk->kode_pos,
								'no_hp'					=> $ptk->no_hp,
								'email' 				=> $ptk->email,
								'photo' 				=> '',
								'active' 				=> 1,
								'password' 				=> $password,
								'petugas' 				=> $loggeduser->username,
								'guru_id_dapodik' 		=> $ptk->ptk_id,
							);
							$dataguru = $this->guru->insert($insert_guru);
							$find_guru_aktif = $this->guru_terdaftar->find("guru_id = $dataguru and semester_id = $ajaran->id");
							if($find_guru_aktif){
								$update_guru_aktif = array(
									'status' => 1
								);
								$this->guru_terdaftar->update($find_guru_aktif->id, $update_guru_aktif);
							} else {
								$attributes = array('semester_id' => $ajaran->id, 'guru_id' => $dataguru, 'status' => 1);			
								$guru_aktif = $this->guru_terdaftar->insert($attributes);
							}
							$this->user->update($user_id, array('guru_id' => $dataguru));
							if($find_gelar){
								foreach($find_gelar as $gelar){
									if($gelar->gelar_akademik_id){
										$find_gelar_ptk = $this->gelar_ptk->find("ptk_id = '$data->ptk_id' AND gelar_akademik_id = $gelar->gelar_akademik_id");
										if(!$find_gelar_ptk){
											$this->gelar_ptk->insert(array('gelar_akademik_id' => $gelar->gelar_akademik_id, 'ptk_id' => $data->ptk_id, 'guru_id' => $dataguru));
										}
									}
								}
							}
						}
						$result = 'insert';
					}
			?>
				<tr>
					<td class="text-center"><?php echo $no++; ?></td>
					<td><?php echo $ptk->nama; ?></td>
					<td><?php echo $ptk->jenis_kelamin; ?></td>
					<td><?php echo $ptk->tempat_lahir; ?></td>
					<td><?php echo $ptk->tanggal_lahir; ?></td>
					<td><?php echo $ptk->nik; ?></td>
					<td><?php echo $ptk->nuptk; ?></td>
					<td><?php echo $ptk->email; ?></td>
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