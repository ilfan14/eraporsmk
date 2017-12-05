<div class="row">
<!-- left column -->
<div class="col-md-12">
<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
<div class="box box-info">
    <div class="box-body">
			<?php
				$i=1;
				foreach($matev_rapor as $rapor){
					$cari_rombel = Datarombel::find_by_rombel_id_dapodik($rapor->rombongan_belajar_id);
					$rombel_id = ($cari_rombel) ? $cari_rombel->id : 0;
					$cari_guru = Dataguru::find_by_guru_id_dapodik($rapor->guru_id_dapodik);
					$guru_id = ($cari_guru) ? $cari_guru->id : 0;
					$sel = 'kurikulums.*, b.nama_mapel AS nama_mapel, b.id_mapel_nas AS id_mapel_nas';
					$join = "INNER JOIN data_mapels b ON(kurikulums.id_mapel = b.id_mapel AND b.id_mapel_nas = $rapor->mata_pelajaran_id)";
					$join .= "LEFT JOIN matpel_komps c ON(kurikulums.id_mapel= c.id_mapel AND c.kurikulum_id = $rapor->kurikulum_id)";
					$cari_kurikulum = Kurikulum::find(array('conditions' => "kurikulums.keahlian_id = $rapor->kurikulum_id AND kurikulums.guru_id = $guru_id",'joins'=> $join, 'select' => $sel));
					if($cari_kurikulum){
						$id_mapel = $cari_kurikulum->id_mapel;
						$find_anggota_rombel = Anggotarombel::find('all', array('conditions' => "rombel_id = $rombel_id"));
						if($find_anggota_rombel){
							foreach($find_anggota_rombel as $anggota){
								$all_nilai_pengetahuan_remedial = Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 1, $rombel_id, $id_mapel, $anggota->siswa_id);
								if($all_nilai_pengetahuan_remedial){
									$nilai_pengetahuan_value = $all_nilai_pengetahuan_remedial->rerata_remedial;
								} else {
									$all_nilai_pengetahuan = Nilaiakhir::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran->id,1,$rombel_id,$id_mapel,$anggota->siswa_id);
									if($all_nilai_pengetahuan){
										$nilai_pengetahuan = 0;
										foreach($all_nilai_pengetahuan as $allnilaipengetahuan){
											$nilai_pengetahuan += $allnilaipengetahuan->nilai;
										}
										$nilai_pengetahuan_value = number_format($nilai_pengetahuan,0);
									} else {
										$nilai_pengetahuan_value = 0;
									}
								}
								$all_nilai_keterampilan_remedial = Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 2, $rombel_id, $id_mapel, $anggota->siswa_id);
								if($all_nilai_keterampilan_remedial){
									$nilai_keterampilan_value = $all_nilai_keterampilan_remedial->rerata_remedial;
								} else {
									$all_nilai_keterampilan = Nilaiakhir::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran->id, 2, $rombel_id, $id_mapel, $anggota->siswa_id);
									if($all_nilai_keterampilan){
										$jumlah_penilaian_keterampilan = count($all_nilai_keterampilan);
										$nilai_keterampilan = 0;
										foreach($all_nilai_keterampilan as $allnilaiketerampilan){
											$nilai_keterampilan += $allnilaiketerampilan->nilai; // $jumlah_penilaian_keterampilan;
										}
										$nilai_keterampilan_value = number_format($nilai_keterampilan,0);
									} else {
										$nilai_keterampilan_value = 0;
									}
								}
								if($anggota->datasiswa){
									$s = $anggota->datasiswa[0];
									if($nilai_pengetahuan_value){
										echo 'Nilai => '.$nilai_pengetahuan_value.'=>'.$s->nama.'=>'.$s->id.'=>'.$anggota->siswa_id.'=>'.$anggota->anggota_rombel_id_dapodik.'<br />';
										$konversi_huruf_pengetahuan = konversi_huruf(get_kkm($ajaran->id,$rombel_id,$id_mapel),$nilai_pengetahuan_value);
										$konversi_huruf_keterampilan = konversi_huruf(get_kkm($ajaran->id,$rombel_id,$id_mapel),$nilai_keterampilan_value);
										$insert_nilai = array(
											'nilai_id' => gen_uuid(),
											'id_evaluasi' => $rapor->id_evaluasi,
											'anggota_rombel_id' => $anggota->anggota_rombel_id_dapodik,
											'nilai_kognitif_angka' => $nilai_pengetahuan_value,
											'nilai_kognitif_huruf' => ($konversi_huruf_pengetahuan != '-') ? $konversi_huruf_pengetahuan : 'D',
											'nilai_psim_angka' => $nilai_keterampilan_value,
											'nilai_psim_huruf' => ($konversi_huruf_keterampilan != '-') ? $konversi_huruf_keterampilan : 'D',
											'nilai_afektif_angka' => get_kkm($ajaran->id,$rombel_id,$id_mapel),
											'nilai_afektif2_angka' => get_kkm($ajaran->id,$rombel_id,$id_mapel),
											'a_beku' => 0,
											'soft_delete' => 0,
											'updater_id' => $sekolah->updater_id
										);
										//test($insert_nilai);
										$query = $this->_database->get_where('nilai.nilai_rapor', array('id_evaluasi' => $rapor->id_evaluasi, 'anggota_rombel_id' => $anggota->anggota_rombel_id_dapodik, 'soft_delete' => 0));
										$find_nilai_dapodik = $query->row();
										if($find_nilai_dapodik){
											$update_nilai = array(
												'nilai_kognitif_angka' => $nilai_pengetahuan_value,
												'nilai_kognitif_huruf' => ($konversi_huruf_pengetahuan != '-') ? $konversi_huruf_pengetahuan : 'D',
												'nilai_psim_angka' => $nilai_keterampilan_value,
												'nilai_psim_huruf' => ($konversi_huruf_keterampilan != '-') ? $konversi_huruf_keterampilan : 'D',
												'nilai_afektif_angka' => get_kkm($ajaran->id,$rombel_id,$id_mapel),
												'nilai_afektif2_angka' => get_kkm($ajaran->id,$rombel_id,$id_mapel)
											);
											$this->_database->where('nilai_id', $find_nilai_dapodik->nilai_id);
											$this->_database->update('nilai.nilai_rapor', $update_nilai);
										} else {
											$this->_database->insert('nilai.nilai_rapor', $insert_nilai);
											echo 'insert';
										}
									}
								}
								//test($anggota->datasiswa);
							}
						}
					}
			$i++;
			} ?>
		<?php //test($matev_rapor); ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>