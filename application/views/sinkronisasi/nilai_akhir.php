/*$find_siswa = Datasiswa::find('all', array('conditions' => "data_rombel_id = $rombel_id"));
						if($find_siswa){
							foreach($find_siswa as $s){
								$all_nilai_pengetahuan_remedial = Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran->id, 1, $rombel_id, $id_mapel, $s->id);
								if($all_nilai_pengetahuan_remedial){
									$nilai_pengetahuan_value = $all_nilai_pengetahuan_remedial->rerata_remedial;
								} else {
									$all_nilai_pengetahuan = Nilaiakhir::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran_id,1,$rombel_id,$mapela,$s->id);
									if($all_nilai_pengetahuan){
										$nilai_pengetahuan = 0;
										foreach($all_nilai_pengetahuan as $allnilaipengetahuan){
											$nilai_pengetahuan += $allnilaipengetahuan->nilai;
										}
										$nilai_pengetahuan_value = number_format($nilai_pengetahuan,0);
									} else {
										$nilai_pengetahuan_value = '-';
									}
								}
								$all_nilai_keterampilan_remedial = Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran_id, 2, $rombel_id, $mapela, $s->id);
								if($all_nilai_keterampilan_remedial){
									$nilai_keterampilan_value = $all_nilai_keterampilan_remedial->rerata_remedial;
								} else {
									$all_nilai_keterampilan = Nilaiakhir::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran_id,2,$rombel_id,$mapela,$s->id);
									if($all_nilai_keterampilan){
										$jumlah_penilaian_keterampilan = count($all_nilai_keterampilan);
										$nilai_keterampilan = 0;
										foreach($all_nilai_keterampilan as $allnilaiketerampilan){
											$nilai_keterampilan += $allnilaiketerampilan->nilai; // $jumlah_penilaian_keterampilan;
										}
										$nilai_keterampilan_value = number_format($nilai_keterampilan,0);
									} else {
										$nilai_keterampilan_value = '-';
									}
								}
							}*/