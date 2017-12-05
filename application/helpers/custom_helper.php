<?php
// function to check if the system has been installed
$CI =& get_instance();
function check_installer(){
	$CI = & get_instance();
	$CI->load->database();
	$CI->load->dbutil();
		if ($CI->db->database == "") {
			redirect('install');
		}
		if (is_dir('install')) {
			$rename = rename('install','kasldoqiwe');
		}
		//update_version('3.0.1');
		set_time_zone();
		$database = array('absen', 'anggota_rombel', 'catatan_ppk', 'catatan_wali', 'data_excel', 'deskripsi_mata_pelajaran', 'deskripsi_sikap', 'ekstrakurikuler', 'guru_terdaftar', 'jurusan_sp', 'kd_nilai', 'mata_pelajaran_kurikulum', 'nilai', 'nilai_ekstrakurikuler', 'nilai_sikap', 'pembelajaran', 'ppk', 'prakerin', 'prestasi', 'ref_agama', 'ref_guru', 'ref_jenis_ptk', 'ref_jurusan', 'ref_kelompok', 'ref_kompetensi_dasar', 'ref_kurikulum', 'ref_mata_pelajaran', 'ref_ppk', 'ref_sekolah', 'ref_semester', 'ref_sikap', 'ref_siswa', 'ref_status_kepegawaian', 'ref_tingkat_pendidikan', 'remedial', 'rencana_penilaian', 'rombongan_belajar', 'teknik_penilaian', 'import_rencana');
		delete_kurikulum();
		migrasi($database);
		migrasi_field('sekolah_id', 'users');
		install_ref(1,'ref_campur');
		install_ref(1,'ref_gelar');
		install_ref_new(233,'ref_kurikulum');
		install_ref(1,'ref_kurikulum');
		install_ref(1,'ref_mata_pelajaran', 1);
		install_ref(1001,'ref_mata_pelajaran', 2);
		install_ref(2001,'ref_mata_pelajaran', 3);
		install_ref(1,'mata_pelajaran_kurikulum', 1);
		install_ref(1001,'mata_pelajaran_kurikulum', 2);
		install_ref(2001,'mata_pelajaran_kurikulum', 3);
		install_ref(3001,'mata_pelajaran_kurikulum', 4);
		install_ref(4001,'mata_pelajaran_kurikulum', 5);
		install_ref(5001,'mata_pelajaran_kurikulum', 6);
		install_ref(6001,'mata_pelajaran_kurikulum', 7);
		install_ref(7001,'mata_pelajaran_kurikulum', 8);
		install_ref(8001,'mata_pelajaran_kurikulum', 9);
		install_ref(9001,'mata_pelajaran_kurikulum', 10);
		install_ref(10001,'mata_pelajaran_kurikulum', 11);
		install_ref(11001,'mata_pelajaran_kurikulum', 12);
		install_ref(12001,'mata_pelajaran_kurikulum', 13);
		install_ref(13001,'mata_pelajaran_kurikulum', 14);
		install_ref(14001,'mata_pelajaran_kurikulum', 15);
		install_ref(15001,'mata_pelajaran_kurikulum', 16);
		install_ref(16001,'mata_pelajaran_kurikulum', 17);
		install_ref(17001,'mata_pelajaran_kurikulum', 18);
		install_ref(18001,'mata_pelajaran_kurikulum', 19);
		install_ref(19001,'mata_pelajaran_kurikulum', 20);
		install_ref(20001,'mata_pelajaran_kurikulum', 21);
		install_ref(21001,'mata_pelajaran_kurikulum', 22);
		install_ref(22001,'mata_pelajaran_kurikulum', 23);
		install_ref(23001,'mata_pelajaran_kurikulum', 24);
		install_ref(24001,'mata_pelajaran_kurikulum', 25);
		install_ref(25001,'mata_pelajaran_kurikulum', 26);
		install_ref(26001,'mata_pelajaran_kurikulum', 27);
		install_ref(27001,'mata_pelajaran_kurikulum', 28);
		install_ref(28001,'mata_pelajaran_kurikulum', 29);
		install_ref(29001,'mata_pelajaran_kurikulum', 30);
		install_ref(30001,'mata_pelajaran_kurikulum', 31);
		install_ref(1,'ref_kompetensi_dasar', 1);
		install_ref(1001,'ref_kompetensi_dasar', 2);
		install_ref(2001,'ref_kompetensi_dasar', 3);
		install_ref(3001,'ref_kompetensi_dasar', 4);
		install_ref(4001,'ref_kompetensi_dasar', 5);
		install_ref(5001,'ref_kompetensi_dasar', 6);
		install_ref(6001,'ref_kompetensi_dasar', 7);
		install_ref(7001,'ref_kompetensi_dasar', 8);
		install_ref(8001,'ref_kompetensi_dasar', 9);
		install_ref(9001,'ref_kompetensi_dasar', 10);
		install_ref(10001,'ref_kompetensi_dasar', 11);
		install_ref(11001,'ref_kompetensi_dasar', 12);
		install_ref(12001,'ref_kompetensi_dasar', 13);
		install_ref(13001,'ref_kompetensi_dasar', 14);
		install_ref(14001,'ref_kompetensi_dasar', 15);
		install_ref(15001,'ref_kompetensi_dasar', 16);
		install_ref(16001,'ref_kompetensi_dasar', 17);
		install_ref(17001,'ref_kompetensi_dasar', 18);
		install_ref(18001,'ref_kompetensi_dasar', 19);
		install_ref(19001,'ref_kompetensi_dasar', 20);
		migrasi_jurusan_sp();
		check_mapel_ganda();
		check_row_nilai();
		clean_table('datakurikulums');
		clean_table('data_portofolios');
		clean_table('kurikulum_aliases');
		clean_table('matpel_komps');
		clean_table('nilaiakhirs');
		reorder_kelompok();
		kepala_sekolah();
}
function kepala_sekolah(){
	$CI = & get_instance();
	$sekolah = $CI->sekolah->get(1);
	$kepala_sekolah = $sekolah->guru_id;
	if(!$kepala_sekolah){
		$settings = $CI->settings->get(1);
		$guru = $CI->guru->find_by_nama($settings->kepsek);
		if($guru){
			$CI->sekolah->update(1, array('guru_id' => $guru->id));
		}
	}
}
function get_last_id($table){
	$CI = & get_instance();
	$query = $CI->db->query("SELECT max(id) AS last_id FROM $table")->row();
	return $query;
}
function install_ref_new($row,$table){
	$CI = & get_instance();
	$nama_ref = str_replace('ref_','',$table);
	$nama_ref = str_replace('_',' ',$nama_ref);
	$nama_ref = ucfirst($nama_ref);
	$query = $CI->kurikulum->find_by_kurikulum_id($row);
	if(!$query){
		$CI->session->set_userdata('title', "Referensi $nama_ref");
		$CI->session->set_userdata('table', $table);
		$CI->db->truncate($table);
		redirect('core');
	}
}
function install_ref($row,$table, $urut=''){
	$CI = & get_instance();
	if($urut){
		$urut = '('.$urut.')';
	}
	$nama_ref = str_replace('ref_','',$table);
	$nama_ref = str_replace('_',' ',$nama_ref);
	$nama_ref = ucfirst($nama_ref);
	if(!isset($CI->agama)){
		redirect('core/write_autoload');
	}
	if($table == 'ref_campur'){
		$nama_ref = 'Umum';
		$query = $CI->agama->get(1);
	}
	if($table == 'ref_mata_pelajaran'){
		$query = $CI->mata_pelajaran->get($row);
	}
	if($table == 'mata_pelajaran_kurikulum'){
		$query = $CI->mata_pelajaran_kurikulum->get($row);
	}
	if($table == 'ref_kompetensi_dasar'){
		$query = $CI->kompetensi_dasar->get($row);
	}
	if($table == 'ref_kurikulum'){
		$query = $CI->kurikulum->get($row);
	}
	if($table == 'ref_gelar'){
		if ($CI->db->table_exists('ref_gelar')){
			$CI->load->model('gelar_model', 'gelar');
			$query = $CI->gelar->get($row);
		} else {
			$query = 1;
		}
	}
	if(!$query){
		$CI->session->set_userdata('title', "Referensi $nama_ref $urut");
		$CI->session->set_userdata('table', $table.$urut);
		redirect('core');
	}
}
function get_ta(){
	$CI =& get_instance();
	$settings = $CI->settings->get(1);
	$strings = $settings->periode;
	$strings = explode('|',$strings);
	$tapel = str_replace(' ','',$strings[0]);
	$semester = $strings[1];
	$semester = str_replace('Semester','',$semester);
	$semester = str_replace(' ','',$semester);
	$smt = 2;
	if($semester == 'Ganjil'){
		$smt = 1;
	}
	$ajarans = $CI->semester->find("tahun = '$tapel' AND semester = $smt");
	if($ajarans){
		return $ajarans;
	} else {
		$data_ajarans = array(
			'tahun'		=> $tapel,
			'semester' 	=> $smt,
			'nama' 		=> $strings[1]
		);
		$CI->semester->insert($data_ajarans);
	}
}
function get_next_ta(){
	global $CI;
	$semester = get_ta();
	$tahun = $semester->tahun;
	$tahun_plus_1 = date('Y') + 1;
	$tapel = $tahun + 1 .'/'.$tahun_plus_1;
	$get_semester = $CI->semester->find("tahun = '$tapel' AND semester = 1");
	if($get_semester){
		$semester_id = $get_semester->id;
	} else {
		$semester_id = create_ta();
	}
	return $semester_id;
}
function create_ta(){
	global $CI;
	$semester = get_ta();
	$tahun = $semester->tahun;
	$tahun_plus_1 = date('Y') + 1;
	$tapel = $tahun + 1 .'/'.$tahun_plus_1;
	$data_semester = array(
		'tahun'	=> $tapel,
		'semester' 	=> 1,
		'nama' 	=> 'Semester Ganjil'
	);
	$get_semester = $CI->semester->insert($data_semester);
	return $get_semester;
}
function test($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}
function success_msg($msg){
	$display = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <b>Success! </b> ' .$msg. '
                </div>';
	return $display;
}

function error_msg($msg){
	$display = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <b>Error! </b> ' .$msg. '
                </div>';
	return $display;
}
function hak_akses($group){
	$CI = & get_instance();
    // You may need to load the model if it hasn't been pre-loaded
	//$this->load->library('someclass');
    $CI->load->library('ion_auth');
    // Call a function of the model
    if(!$CI->ion_auth->in_group($group)){
		return show_error('Akses ditolak');
	}
}
function get_jabatan($user_id){
	global $CI;
	$user_groups = $CI->ion_auth->get_users_groups($user_id)->result();
	foreach($user_groups as $user_group){
		$nama_group[] = $user_group->name; 
	}
	return $nama_group;
}
function get_akses($user_id){
	$CI = & get_instance();
	$find_akses = $CI->ion_auth->get_users_groups($user_id)->result();
	$loggeduser = $CI->ion_auth->user($user_id)->row();
	//test($find_akses);
	//test($loggeduser);
	//$find_akses = Usergroup::find_all_by_user_id($user_id);
	foreach($find_akses as $akses){
		$get_nama_akses = $CI->group->get($akses->id);
		if($get_nama_akses->id == 3){
			$find_user_id = $loggeduser->guru_id;
		} elseif($get_nama_akses->id == 4) {
			$find_user_id = $loggeduser->siswa_id;
		} else {
			$find_user_id = $user_id;
		}
		$data['id'][] = $find_user_id;
		$data['name'][] = $get_nama_akses->name;
	}
	//test($data);
	//test($find_user);
	/*$find_guru = Dataguru::find_by_user_id($user_id);
	$find_siswa = Datasiswa::find_by_user_id($user_id);
	if($find_guru){
		$data['name'] = 'guru';
		$data['id'] = $find_guru->id;
	}elseif($find_siswa){
		$data['name'] = 'siswa';
		$data['id'] = $find_siswa->id;
	} else {
		$data['name'] = 0;
		$data['id'] = 0;
	}*/
	return $data;
}
function TanggalIndo($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tgl   = substr($date, 8, 2);
	$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun; 
	return($result);
}
function status_label($status){
	if($status == '1') : 
		$label = '<span class="btn btn-sm btn-success"> Aktif </span>';
	elseif ($status == '0') : 
		$label = '<span class="btn btn-sm btn-danger"> Non Aktif </span>';
	endif;
	return $label;
}
function status_kepegawaian(){
	global $CI;
	$status = $CI->status_kepegawaian->get_all();
	return $status;
}
function jenis_ptk(){
	global $CI;
	$status = $CI->jenis_ptk->get_all();
	return $status;
}
function agama(){
	global $CI;
	$status = $CI->agama->get_all();
	return $status;
}
function GenerateEmail($length = 6) {
	$characters = 'abcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
function GenerateID($length = 10) {
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
function GenerateNISN($length = 12) {
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
function get_agama($a){
	global $CI;
	if(is_numeric($a)){
		$get_agama = $CI->agama->get($a);
		$agama = ($get_agama) ? $get_agama->nama : '-';
	} else {
		$agama = str_replace('Budha','Buddha',$a);
	}
	return $agama;
}
function get_status_kepegawaian($id){
	global $CI;
	$get_status_kepegawaian = $CI->status_kepegawaian->get($id);
	$status_kepegawaian = ($get_status_kepegawaian) ? $get_status_kepegawaian->nama : '-';
	return $status_kepegawaian;
}
function get_jenis_ptk($id){
	global $CI;
	$get_jenis_ptk = $CI->jenis_ptk->get($id);
	$jenis_ptk = ($get_jenis_ptk) ? $get_jenis_ptk->nama : '-';
	return $jenis_ptk;
}
function set_log($table,$query,$user){
	global $CI;
	$settings = $CI->settings->get(1);
	if($settings->zona == 1){ // WIB
		date_default_timezone_set('Asia/Jakarta');
	}
	if($settings->zona == 2){ //WITA
		date_default_timezone_set('Asia/Makassar');
	}
	if($settings->zona == 3){ //WIT
		date_default_timezone_set('Asia/Jayapura');
	}
	$file = './log.txt';
	$message = $table.", ".$query. ", User: ".$user.", Waktu :".date('d-m-y H:i:s').PHP_EOL;
	file_put_contents($file, $message, FILE_APPEND | LOCK_EX);
}
function after_create_log($query,$id){
	global $CI;
	$CI->load->library('ion_auth');
	$loggeduser = $CI->ion_auth->user()->row();
	set_log('Tambah data '.$query,'ID:'.$id,$loggeduser->username);
}
function after_update_log($query,$id){
	global $CI;
	$CI->load->library('ion_auth');
	$loggeduser = $CI->ion_auth->user()->row();
	set_log('Update data '.$query,'ID:'.$id,$loggeduser->username);
}
function before_destroy($query,$table,$id){
	global $CI;
	$CI->load->library('ion_auth');
	$loggeduser = $CI->ion_auth->user()->row();
	set_log('Hapus data '.$query,'ID:'.$id,$loggeduser->username);
}
function get_kurikulum($kurikulum_id,$query='nama'){
	global $CI;
	$get_kurikulum = $CI->kurikulum->find_by_kurikulum_id($kurikulum_id);
	//$jurusan = $CI->ref_jurusan->find_by_jurusan_id($kurikulum_id);
	/*$nama_kompetensi = ($kompetensi) ? $kompetensi->nama_kurikulum : 0;
	if (strpos($nama_kompetensi, 'SMK 2013') !== false) {
		$get_nama_kompetensi['nama'] = str_replace('SMK 2013','',$nama_kompetensi.' (2013)');
		$get_nama_kompetensi['id'] = str_replace('SMK 2013','','2013');
	}
	if (strpos($nama_kompetensi, 'SMK KTSP') !== false) {
		$get_nama_kompetensi['nama'] = str_replace('SMK KTSP','',$nama_kompetensi.' (KTSP)');
		$get_nama_kompetensi['id'] = str_replace('SMK KTSP','','KTSP');
	}
	$result = isset($get_nama_kompetensi[$query]) ? $get_nama_kompetensi[$query] : 'Kompetensi Keahlian tidak ditemukan';
	*/
	$result = ($get_kurikulum) ? $get_kurikulum->nama_kurikulum: 'Kurikulum tidak ditemukan('.$kurikulum_id.')';
	return $result;
}
function get_wali_kelas($id_rombel){
	global $CI;
	$rombel = $CI->rombongan_belajar->get($id_rombel);
	$guru_id = isset($rombel->guru_id) ? $rombel->guru_id : 0;
	$nama_guru = get_nama_guru($guru_id);
	return $nama_guru;
}
function get_nama_guru($id_guru){
	global $CI;
	$CI->load->model('gelar_model', 'gelar');
	$guru = $CI->guru->with('gelar_ptk')->get($id_guru);
	$nama_guru = isset($guru->nama) ? $guru->nama : '-';
	$ptk_id = ($guru) ? $guru->guru_id_dapodik : 0;
	$CI->db->select('a.gelar_akademik_id, b.posisi_gelar, b.kode');
	$CI->db->from('gelar_ptk as a');
	$CI->db->join('ref_gelar as b', 'a.gelar_akademik_id = b.gelar_akademik_id');
	$CI->db->where('a.ptk_id', $ptk_id);
	//$CI->db->where('a.guru_id', $id_guru);
	$CI->db->where('b.posisi_gelar', 1);
	$CI->db->order_by('b.gelar_akademik_id', 'ASC');
	$query_depan = $CI->db->get();
	$gelar_ptk_depan = $query_depan->result_array();
	$set_gelar_depan = '';
	if($gelar_ptk_depan){
		$set_gelar_depan = implode('. ', array_map(function ($entry_depan) {
		  return $entry_depan['kode'];
		}, $gelar_ptk_depan)).'. ';
	}
	$CI->db->select('a.gelar_akademik_id, b.posisi_gelar, b.kode');
	$CI->db->from('gelar_ptk as a');
	$CI->db->join('ref_gelar as b', 'a.gelar_akademik_id = b.gelar_akademik_id');
	$CI->db->where('a.ptk_id', $ptk_id);
	//$CI->db->where('a.guru_id', $id_guru);
	$CI->db->where('b.posisi_gelar', 2);
	$CI->db->where('b.gelar_akademik_id != 99999');
	$CI->db->order_by('b.gelar_akademik_id', 'ASC');
	$query_belakang = $CI->db->get();
	$gelar_ptk_belakang = $query_belakang->result_array();
	$set_gelar_belakang = '';
	if($gelar_ptk_belakang){
		$set_gelar_belakang = ', '.implode(', ', array_map(function ($entry_belakang) {
		  return $entry_belakang['kode'];
		}, $gelar_ptk_belakang));
	}
	$nama_guru = $set_gelar_depan.$nama_guru.$set_gelar_belakang;
	return $nama_guru;
}
function get_nip_guru($id_guru){
	global $CI;
	$guru = $CI->guru->get($id_guru);
	$nip_guru = isset($guru->nip) ? $guru->nip : '-';
	return $nip_guru;
}
function get_nama_mapel($mata_pelajaran_id){
	global $CI;
	$get_nama_mapel = $CI->mata_pelajaran->get($mata_pelajaran_id);
	$nama_mapel = ($get_nama_mapel) ? ($get_nama_mapel->nama_mata_pelajaran_alias) ? $get_nama_mapel->nama_mata_pelajaran_alias : $get_nama_mapel->nama_mata_pelajaran : '-';
	return $nama_mapel;
}
function get_nama_mapel_alias($mata_pelajaran_id){
	global $CI;
	$get_nama_mapel_alias = $CI->mata_pelajaran->get($mata_pelajaran_id);
	$nama_mapel_alias = ($get_nama_mapel_alias) ? $get_nama_mapel_alias->nama_mata_pelajaran_alias : '-';
	return $nama_mapel_alias;
}
function get_start() {
	$start = 0;
	if (isset($_GET['iDisplayStart'])) {
		$start = intval($_GET['iDisplayStart']);
		if ($start < 0)
			$start = 0;
	}
	return $start;
}
function get_rows() {
	$rows = 10;
	if (isset($_GET['iDisplayLength'])) {
		$rows = intval($_GET['iDisplayLength']);
		if ($rows < 5 || $rows > 500) {
			$rows = 10;
		}
	}
	return $rows;
}
function get_sort_dir() {
	$sort_dir = "ASC";
	$sdir = strip_tags($_GET['sSortDir_0']);
	if (isset($sdir)) {
		if ($sdir != "asc" ) {
			$sort_dir = "DESC";
		}
	}
	return $sort_dir;
}
function get_guru_mapel($semester_id, $rombongan_belajar_id, $id_mapel, $query = 'nama'){
	global $CI;
	$get_mapel = $CI->pembelajaran->find("semester_id = $semester_id and rombongan_belajar_id = $rombongan_belajar_id and mata_pelajaran_id = $id_mapel");
	//echo "semester_id = $semester_id and rombongan_belajar_id = $rombongan_belajar_id and mata_pelajaran_id = $id_mapel";
	//test($get_mapel);
	$nama_guru_mapel['id'] = ($get_mapel) ? $get_mapel->guru_id : 0;
	$nama_guru_mapel['nama'] = ($get_mapel) ? get_nama_guru($get_mapel->guru_id) : 0;
	return $nama_guru_mapel[$query];
}
function predikat($kkm, $a){
	//(100-65)/3 = 35/3 = 11,67 = 12 
	$rumus = (100-$kkm) / 3;
	$rumus = number_format($rumus,0);
	$result = array(
				'd'	=> $kkm - 1,
				'c'	=> $kkm + $rumus,
				'b'	=> $kkm + ($rumus * 2),
				'a'	=> $kkm + ($rumus * 3),
				);
	/*$result = array(
				'd'	=> 55, // 0 - 55 D
				'c'	=> 70, // 56 - 70 C
				'b'	=> 85, // 71 - 85 D
				'a'	=> 100, // 86 - 100 A
				);*/
	if($result[$a] > 100)
		$result[$a] = 100;
	return $result[$a];
}
function get_nama_rombel($id_rombel){
	global $CI;
	$rombel = $CI->rombongan_belajar->get($id_rombel);
	$nama_rombel = isset($rombel->nama) ? $rombel->nama : '-';
	return $nama_rombel;
}
function get_bidang_keahlian($id){
	global $CI;
	$get_bidang_keahlian = $CI->bidang_keahlian->get($id);
	$bidang_keahlian = ($get_bidang_keahlian) ? $get_bidang_keahlian->nama : '-';
	return $bidang_keahlian;
}
function get_program_keahlian($id){
	global $CI;
	$get_program_keahlian = $CI->program_keahlian->get($id);
	$program_keahlian = ($get_program_keahlian) ? $get_program_keahlian->nama : '-';
	return $program_keahlian;
}
function get_kompetensi_keahlian($id){
	global $CI;
	$get_kompetensi_keahlian = $CI->kompetensi_keahlian->get($id);
	$kompetensi_keahlian = ($get_kompetensi_keahlian) ? $get_kompetensi_keahlian->nama : '-';
	return $kompetensi_keahlian;
}
function get_jumlah_siswa($id_rombel){
	//$jumlah_siswa = Datasiswa::find_all_by_data_rombongan_belajar_id($id_rombel);
	$jumlah_siswa = get_siswa_by_rombel($id_rombel);
	return count($jumlah_siswa);
}
function get_siswa_by_rombel($rombongan_belajar_id,$conditions = NULL){
	//echo $rombongan_belajar_id;
	global $CI;
	$ajaran = get_ta();
	$data_siswa = $CI->anggota_rombel->with('siswa')->find_all("semester_id = $ajaran->id AND rombongan_belajar_id = $rombongan_belajar_id", '*', 'siswa_id ASC');
	return $data_siswa;
}
function get_rombel_siswa($semester_id, $siswa_id){
	global $CI;
	$anggota_rombel = $CI->anggota_rombel->find("semester_id = $semester_id AND siswa_id = $siswa_id");
	$rombongan_belajar_id = ($anggota_rombel) ? $anggota_rombel->rombongan_belajar_id : 0;
	$rombongan_belajar = $CI->rombongan_belajar->get($rombongan_belajar_id);
	$rombel = ($rombongan_belajar) ? $rombongan_belajar->nama : '-';
	return $rombel;
}
function get_kkm($semester_id, $rombongan_belajar_id, $id){
	global $CI;
	$get_kkm = $CI->pembelajaran->find("semester_id = $semester_id and rombongan_belajar_id = $rombongan_belajar_id AND mata_pelajaran_id = $id");
	$kkm = isset($get_kkm->kkm) && $get_kkm->kkm ? $get_kkm->kkm : 0;
	return $kkm;
}
function get_jumlah_penilaian($semester_id, $rombongan_belajar_id, $mapel_id, $kompetensi_id){
	global $CI;
	$all_rencana = $CI->rencana_penilaian->find_count("semester_id = $semester_id AND rombongan_belajar_id = $rombongan_belajar_id AND mata_pelajaran_id = $mapel_id AND kompetensi_id = $kompetensi_id");
	return status_penilaian($all_rencana);
}
function status_penilaian($status){
	if($status > 0) {
		$label = '<span class="label label-success"> '.ucwords($status).' </span>';
	} else {
		$label = '<span class="label label-danger">'.ucwords($status).' </span>';
	}
	return $label;
}
function get_teknik_penilaian($id){
	global $CI;
	$get_teknik_penilaian = $CI->teknik_penilaian->get($id);
	$teknik_penilaian = ($get_teknik_penilaian) ? $get_teknik_penilaian->nama : '-';
	return $teknik_penilaian;
}
function filter_agama_siswa($nama_mapel,$rombongan_belajar_id){
	$agamas = array(1=>'Islam',2=>'Kristen',3=>'Katholik',4=>'Hindu',5=>'Buddha',6=>'Konghucu',98=>'Tidak diisi',99=>'Lainnya');
	foreach($agamas as $key=>$value){
		$pos = strpos($nama_mapel, $value);
		if ($pos === false) {
			$data_siswa = get_siswa_by_rombel($rombongan_belajar_id);
		} else {
			$conditions = "AND data_siswas.agama = '$value' OR data_siswas.agama = $key";
			$data_siswa = get_siswa_by_rombel($rombongan_belajar_id,$conditions);
			break;
		}
	}
	return $data_siswa;
}
function butir_sikap($id){
	global $CI;
	$data_sikap = $CI->sikap->get($id);
	$result = isset($data_sikap->butir_sikap) ? $data_sikap->butir_sikap : '';
	return $result;
}
function opsi_sikap($opsi,$num = NULL){
	if($num){
		if($opsi == 1) : 
			$label = 'Positif';
		elseif ($opsi == 2) : 
			$label = 'Negatif';
		endif;
	} else {
		if($opsi == 1) : 
			$label = '<span class="label label-success"> Positif </span>';
		elseif ($opsi == 2) : 
			$label = '<span class="label label-danger"> Negatif </span>';
		endif;
	}
	return $label;
}
function filter_table($jurusan, $tingkat, $find_akses, $nama_group){
	global $CI;
	$ajaran = get_ta();
	$output = '';
	if($jurusan && $tingkat){
		if(in_array('guru',$find_akses) && !in_array('waka',$nama_group)){
			$get_all_rombel = $CI->rombongan_belajar->find_all("kurikulum_id = $jurusan AND tingkat = $tingkat AND semester_id = $ajaran->id");
		} else {
			$get_all_rombel = $CI->rombongan_belajar->find_all("kurikulum_id = $jurusan AND tingkat = $tingkat AND semester_id = $ajaran->id");
		}
		foreach($get_all_rombel as $allrombel){
			$all_rombel= array();
			$all_rombel['value'] = $allrombel->id;
			$all_rombel['text'] = $allrombel->nama;
			$output[] = $all_rombel;
		}
	}
	return $output;
}
function where($find_akses, $nama_group, $guru_id){
	$where = '';
	if(in_array('guru',$find_akses) && !in_array('waka',$nama_group)){
		$where = "AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM pembelajaran WHERE guru_id = $guru_id)";
	}
	return $where;
}
function joint($jurusan, $tingkat, $rombel){
	$join = '';
	if($jurusan && $tingkat == NULL && $rombel == NULL){
		$join = "AND rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE jurusan_sp_id = $jurusan)";
	}elseif($jurusan && $tingkat && $rombel == NULL){
		$join = "AND rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE jurusan_sp_id = $jurusan) AND rombongan_belajar_id IN (SELECT id FROM rombongan_belajar WHERE tingkat = $tingkat)";
	} elseif($jurusan && $tingkat && $rombel){
		$join = "AND rombongan_belajar_id = $rombel";
	}
	return $join;
}
function check_great_than_one_fn($val,$satu, $redirect){
	$CI = & get_instance();
	if($val > 100){
  		$CI->session->set_flashdata('error', 'Tambah data nilai '.$redirect.' gagal. Nilai harus tidak lebih besar dari 100');
		//redirect('admin/asesmen/remedial');
		redirect('admin/asesmen/'.$redirect);
	}
	//if($val == 0){
  		//$CI->session->set_flashdata('error', 'Tambah data nilai '.$redirect.' gagal. Nilai harus tidak kurang atau sama dengan 0 (nol)');
		//redirect('admin/asesmen/'.$redirect);
	//}
}
function check_numeric($val,$form, $redirect){
	//test($val);
	//die();
	$CI = & get_instance();
	$nilai = ($val) ? $val[0] : 0;
	$get_redirect = strstr($redirect, '/');
	$get_redirect = str_replace('/','',$get_redirect);
	$form_entry = 'Nilai';
	if($get_redirect == 'prakerin'){
		$form_entry = 'Lamanya (bulan)';
	}
	if(is_numeric($nilai)){
		if($val >= 0){
		} else {
			$CI->session->set_flashdata('error', 'Tambah data nilai '.$get_redirect.' gagal. '.$form_entry.' tidak boleh minus');
			redirect('admin/'.$redirect);
		}
	} else {
		$CI->session->set_flashdata('error', 'Tambah data nilai '.$get_redirect.' gagal. '.$form_entry.' harus berupa angka');
		redirect('admin/'.$redirect);
	}
}
function bilanganKecil($a){
	$MAX_VALUE=100;
	for($i=0;$i< count($a) ;$i++){
		if($a[$i] <	$MAX_VALUE){
			$MAX_VALUE=$a[$i];
		}
	}
	return $MAX_VALUE;
}
function bilanganBesar($a){
	$MAX_VALUE=0;
	for($i=0;$i < count($a) ;$i++){
		if($a[$i] > $MAX_VALUE){
			$MAX_VALUE=$a[$i];
		}
	}
	return $MAX_VALUE;
}
function get_nilai_siswa($semester_id, $kompetensi_id, $rombongan_belajar_id, $mapel_id, $siswa_id){
	/*$get_mapel = Datamapel::find_by_id_mapel($mapel_id);
	if(!$get_mapel){
		$get_mapel = Datamapel::find_by_id_mapel_nas($mapel_id);
	}
	get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapela, $s->id)
	test($get_mapel);*/
	global $CI;
	$nilai_value = 0;
	$rombongan_belajar = $CI->rombongan_belajar->get($rombongan_belajar_id);
	$kelas = ($rombongan_belajar) ? $rombongan_belajar->tingkat : 0;
	$all_nilai_remedial = $CI->remedial->find("semester_id = $semester_id and kompetensi_id = $kompetensi_id and rombongan_belajar_id = $rombongan_belajar_id and mata_pelajaran_id = $mapel_id and siswa_id = $siswa_id");
	//Remedial::find_by_semester_id_and_kompetensi_id_and_rombongan_belajar_id_and_mapel_id_and_data_siswa_id($semester_id, $kompetensi_id, $rombongan_belajar_id, $mapel_id, $siswa_id);
	if($all_nilai_remedial){
		$nilai_value = $all_nilai_remedial->rerata_remedial;
	} else {
		$kkm = get_kkm($semester_id, $rombongan_belajar_id, $mapel_id);
		$aspek = ($kompetensi_id == 1) ? 'P' : 'K';
		$get_all_kd = $CI->kompetensi_dasar->find_all("mata_pelajaran_id = $mapel_id AND kelas = $kelas AND aspek = '$aspek'");
		//if(!$get_all_kd){
			//$get_all_kd = $CI->kompetensi_dasar->find_all("mata_pelajaran_id = $mapel_id AND kelas = $kelas AND aspek = 'PK'");
		//}
		$get_all_kd_finish = count($get_all_kd);
		$set_rerata = 0;
		$pembagi = 0;
		foreach($get_all_kd as $all_kd){
			//$all_nilai = $CI->nilai->with('rencana_penilaian')->find_all("semester_id = $semester_id AND kompetensi_id = $kompetensi_id AND siswa_id = $siswa_id AND kompetensi_dasar_id = $all_kd->id");
			$all_nilai = $CI->nilai->with('rencana_penilaian')->find_all("semester_id = $semester_id AND kompetensi_id = $kompetensi_id AND siswa_id = $siswa_id AND kompetensi_dasar_id = $all_kd->id");
			if($all_nilai){
				$pembagi++;
				if($kompetensi_id == 1){
					foreach($all_nilai as $set_nilai){
						$nilai_siswa[] = $set_nilai->nilai;
					}
					$nilai_remedial = array_sum($nilai_siswa) / count($nilai_siswa); 
				} else {
					$nilai_siswa = array();
					foreach($all_nilai as $set_nilai){
					//test($set_nilai);
						if(is_array($set_nilai->rencana_penilaian)){
							 $metode_id = $set_nilai->rencana_penilaian->metode_id;
						} else {
							$metode_id = 0;
						}
						$nilai_siswa[$set_nilai->kompetensi_dasar_id.'_'.$metode_id][] = $set_nilai->nilai;
					}
					ksort($nilai_siswa, SORT_NUMERIC);
					$n_s_final = 0;
					foreach($nilai_siswa as $n_s){
						if(count($n_s) > 1){
							$n_s_final += max($n_s) / count($nilai_siswa);
						} else {
							$n_s_final += array_sum($n_s) / count($nilai_siswa); 
						}
					}
					$nilai_remedial = $n_s_final;
				}
				$set_rerata += $nilai_remedial;
			}
			if($set_rerata){
				$nilai_value = number_format($set_rerata / $pembagi,0);
			} else {
				$nilai_value = '';
			}
		}
	}
	return $nilai_value;
}
function sebaran($input, $a,$b){
	$range_data = range($a,$b);	
	$output = array_intersect($input , $range_data);
	return $output;
}
function sebaran_tooltip($input, $a,$b,$c){
	$CI = & get_instance();
	$range_data = range($a,$b);
	$output = array_intersect($input , $range_data);
	$data = array();
	$nama_siswa = '';
	foreach($output as $k=>$v){
		$data[] = get_nama_siswa($k);
	}
	if(count($output) == 0){
		$result = count($output);
	} else {
		$result = '<a class="tooltip-'.$c.'" href="javascript:void(0)" title="'.implode('<br />',$data).'" data-html="true">'.count($output).'</a>';
	}
	return $result;
}
function get_nama_siswa($id){
	global $CI;
	$query = $CI->siswa->get($id);
	$nama = isset($query->nama) ? $query->nama : '-';
	return $nama;
}
function get_nilai_siswa_by_kd_new($semester_id, $kompetensi_id, $rombongan_belajar_id, $mapel_id, $siswa_id, $rencana_penilaian_id, $kompetensi_dasar_id){
	global $CI;
	$nilai_akhir = 0;
	$all_nilai = $CI->nilai->find("semester_id = $semester_id AND kompetensi_id = $kompetensi_id AND rombongan_belajar_id = $rombongan_belajar_id AND mata_pelajaran_id = $mapel_id AND siswa_id = $siswa_id AND rencana_penilaian_id = $rencana_penilaian_id AND kompetensi_dasar_id = $kompetensi_dasar_id");
	$result = ($all_nilai) ? $all_nilai->nilai : 0;
	return $result;
}
function get_nilai_siswa_by_kd($id_kd, $semester_id, $kompetensi_id, $rombongan_belajar_id, $mapel_id, $siswa_id, $query = 'asli'){
//get_nilai_siswa_by_kd(count($get_all_kd), $all_kd->id, $semester_id, $kompetensi_id, $siswa_id, 'asli');
	global $CI;
	$set_nilai_akhir = 0;
	$nilai_akhir = 0;
	if($query == 'asli'){
		$all_nilai = $CI->nilai->with('rencana_penilaian')->find_all("semester_id = $semester_id AND kompetensi_id = $kompetensi_id AND rombongan_belajar_id = $rombongan_belajar_id AND siswa_id = $siswa_id AND kompetensi_dasar_id = $id_kd");
		if($all_nilai){
			if($kompetensi_id == 1){
				foreach($all_nilai as $set_nilai){
					$nilai_siswa[] = $set_nilai->nilai;
				}
				$nilai_akhir = array_sum($nilai_siswa) / count($nilai_siswa); 
			} else {
				$nilai_siswa = array();
				foreach($all_nilai as $set_nilai){
					$nilai_siswa[$set_nilai->kompetensi_dasar_id.'_'.$set_nilai->rencana_penilaian->metode_id][] = $set_nilai->nilai;
				}
				ksort($nilai_siswa, SORT_NUMERIC);
				$n_s_final = 0;
				foreach($nilai_siswa as $n_s){
					if(count($n_s) > 1){
						$n_s_final += max($n_s) / count($nilai_siswa);
					} else {
						$n_s_final += array_sum($n_s) / count($nilai_siswa); 
					}
				}
				$nilai_akhir = $n_s_final;
			}
			$set_nilai_akhir += $nilai_akhir;
		}
		if($set_nilai_akhir){
			$nilai_akhir = $set_nilai_akhir;
			//number_format($set_nilai_akhir / $pembagi,0);
		} else {
			$nilai_akhir = '';
		}
	} else {
		$remedial = $CI->remedial->find("semester_id = $semester_id and kompetensi_id = $kompetensi_id and rombongan_belajar_id = $rombongan_belajar_id and mata_pelajaran_id = $mapel_id and siswa_id = $siswa_id");
		if($remedial){
			$nilai_akhir = unserialize($remedial->nilai);
		}
	}
	return $nilai_akhir;
}
function get_butir_sikap($id){
	global $CI;
	$get_butir_sikap = $CI->sikap->get($id);
	$butir_sikap = ($get_butir_sikap) ? $get_butir_sikap->butir_sikap : '-';
	return $butir_sikap;
}
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}
function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
function load_extensions(){
	$extension = 0;
	if (!extension_loaded('pdo_odbc')) {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$extension = 'php_pdo_odbc.dll';
		} else {
			$extension = 'pdo_odbc.so';
		}
	} elseif (!extension_loaded('pdo_pgsql')) {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$extension = 'php_pdo_pgsql.dll';
		} else {
			$extension = 'pdo_pgsql.so';
		}
	} elseif (!extension_loaded('pdo_sqlite')) {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$extension = 'php_pdo_sqlite.dll';
		} else {
			$extension = 'pdo_pgsql.so';
		}
	} elseif (!extension_loaded('pgsql')) {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$extension = 'php_pgsql.dll';
		} else {
			$extension = 'pgsql.so';
		}
	}
	return $extension;
}
function check_is_dapodik(){
	if (!extension_loaded('curl')) {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$extension = 'php_curl.dll';
		} else {
			$extension = 'php_curl.so';
		}
		echo '<h1 style="text-align:center">Ekstensi '.$extension.' belum aktif.<br />
Silahkan aktifkan terlebih dahulu ekstensi tersebut di php.ini kemudian restart Apache!</h1>';
		die();
	}
	$root = "http://".$_SERVER['HTTP_HOST'];
	$parse = parse_url($root);
	$url = $parse['scheme'].'://'.$parse['host'].':5774/';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	$details = curl_getinfo($ch);
	curl_close($ch);
	$respon = $details['http_code'];
	return $respon;
}
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
function get_jurusan($id){
	$CI = & get_instance();
	$CI->load->database();
	$CI->load->dbutil();
	$get_jurusan = $CI->jurusan->find_by_jurusan_id($id);
	$result = ($get_jurusan) ? $get_jurusan->nama_jurusan : '-';
	return $result;
}
function get_ekskul($id){
	global $CI;
	$get_ekskul= $CI->ekstrakurikuler->get($id);
	$result = ($get_ekskul) ? $get_ekskul->nama_ekskul: '-';
	return $result;
}
function filter_agama_mapel($ajaran_id,$get_id_mapel, $all_mapel,$agama_siswa){
	global $CI;
	$get_mapel_agama = $CI->mata_pelajaran->get_many($get_id_mapel);
	foreach($get_mapel_agama as $agama){
		$mapel_agama[$agama->id] = get_nama_mapel($agama->id);
	}
	if(isset($mapel_agama)){
		foreach($mapel_agama as $key=>$m_agama){
			if (strpos($m_agama,get_agama($agama_siswa)) == false) {
				$mapel_agama_jadi[] = $key;
			}
		}
	}
	if(isset($mapel_agama_jadi)){
		$all_mapel = array_diff($all_mapel, $mapel_agama_jadi);
	}
	return $all_mapel;
}
function filter_agama_mapel_old($ajaran_id,$rombel_id,$get_id_mapel, $all_mapel,$agama_siswa){
	global $CI;
	//$kelompok_agama = preg_quote('A01', '~'); // don't forget to quote input string!
	//$normatif_1 = preg_quote(10001, '~'); // don't forget to quote input string!
	//$get_mapel_agama = preg_grep('~' . $kelompok_agama . '~', $get_id_mapel);
	//$get_mapel_agama_alias = preg_grep('~' . $normatif_1 . '~', $get_id_mapel);
	$id_mapel_agama = array(1,2,3,4,5,6,7);
	$get_mapel_agama = $CI->mata_pelajaran->get_many($id_mapel_agama);
	foreach($get_mapel_agama as $agama){
		$mapel_agama[$agama] = get_nama_mapel($ajaran_id,$rombel_id,$agama);
	}
	foreach($get_mapel_agama_alias as $agama_alias){
		$mapel_agama_alias[$agama_alias] = get_nama_mapel($ajaran_id,$rombel_id,$agama_alias);
	}
	if(isset($mapel_agama)){
		foreach($mapel_agama as $key=>$m_agama){
			if (strpos($m_agama,get_agama($agama_siswa)) == false) {
				$mapel_agama_jadi[] = $key;
			}
		}
	}
	if(isset($mapel_agama_alias)){
		foreach($mapel_agama_alias as $key=>$m_agama_alias){
			if (strpos($m_agama_alias,get_agama($agama_siswa)) == false) {
				$mapel_agama_alias_jadi[] = $key;
			}
		}
	}
	if(isset($mapel_agama_jadi)){
		$all_mapel = array_diff($all_mapel, $mapel_agama_jadi);
	}
	if(isset($mapel_agama_alias_jadi)){
		$all_mapel = array_diff($all_mapel, $mapel_agama_alias_jadi);
	}
	return $all_mapel;
}
function konversi_huruf($kkm_value, $n,$show='predikat'){
$predikat	= 0;
$sikap		= 0;
$sikap_full	= 0;
$b = predikat($kkm_value,'b') + 1;
$c = predikat($kkm_value,'c') + 1;
$d = predikat($kkm_value,'d') - 1;
	if($n == 0){
		$predikat 	= '-';
		$sikap		= '-';
		$sikap_full	= '-';
	} elseif($n >= $b){//$settings->a_min){ //86
		$predikat 	= 'A';
		$sikap		= 'SB';
		$sikap_full	= 'Sangat Baik';
	} elseif($n >= $c){ //71
		$predikat 	= 'B';
		$sikap		= 'B';
		$sikap_full	= 'Baik';
	} elseif($n >= $d){ //56
		$predikat 	= 'C';
		$sikap		= 'C';
		$sikap_full	= 'Cukup';
	} elseif($n < $d){ //56
		$predikat 	= 'D';
		$sikap		= 'K';
		$sikap_full	= 'Kurang';
	}
	if($show == 'predikat'){
		$html = $predikat;
	} elseif($show == 'sikap'){
		$html = $sikap;
	} elseif($show == 'sikap_full'){
		$html = $sikap_full;
	} else {
		$html = 'Unknow';
	}
	return $html;
}
function get_deskripsi_nilai($ajaran_id, $rombel_id, $mapel_id, $siswa_id,$query){
	global $CI;
	$deskripsi = $CI->deskripsi_mata_pelajaran->find("semester_id = $ajaran_id AND rombongan_belajar_id = $rombel_id AND mata_pelajaran_id = $mapel_id AND siswa_id = $siswa_id");
	//Deskripsi::find_by_ajaran_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran_id, $rombel_id, $mapel_id, $siswa_id);
	$set_deskripsi[1] = '';
	$set_deskripsi[2] = '';
	if($deskripsi){
		$set_deskripsi[1] = $deskripsi->deskripsi_pengetahuan;
		$set_deskripsi[2] = $deskripsi->deskripsi_keterampilan;
	}
	return $set_deskripsi[$query];
}
function indo_date($date = ''){
	$formated_date = date('Y-m-d', strtotime($date));
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	    $tahun = substr($formated_date, 0, 4);
    	$bulan = substr($formated_date, 5, 2);
	    $tgl   = substr($formated_date, 8, 2);
    	$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun; 
    	return($result);
	//return $formated_date;
}
function get_ppk_siswa($ppk_id, $siswa_id){
	global $CI;
	$catatan_ppk = $CI->catatan_ppk->find("ppk_id = $ppk_id AND siswa_id = $siswa_id");
	//1Catatanppk::find_by_ppk_id_and_siswa_id($ppk_id, $siswa_id);
	$result = ($catatan_ppk) ? $catatan_ppk->catatan : '';
	return $result;
}
function get_kompetensi_dasar($kompetensi_dasar_id,$query='nama'){
	global $CI;
	$get_kompetensi_dasar = $CI->kompetensi_dasar->get($kompetensi_dasar_id);
	$result['id'] 	= ($get_kompetensi_dasar) ? $get_kompetensi_dasar->id_kompetensi : 0;
	$result['nama'] = ($get_kompetensi_dasar) ? ($get_kompetensi_dasar->kompetensi_dasar_alias) ? $get_kompetensi_dasar->kompetensi_dasar_alias : $get_kompetensi_dasar->kompetensi_dasar : 0;
	return $result[$query];
}
function clean($strings){
	$string = preg_replace('/[^a-zA-Z0-9\']/', ' ', $strings);
	$string = str_replace("'", '', $string);
	$string = trim($string);
	return $string;
}
function get_nama_ppk($id){
	global $CI;
	$get_nama_ppk = $CI->ref_ppk->get($id);
	$result = ($get_nama_ppk) ? $get_nama_ppk->nama : '0';
	return $result;
}
function get_kelompok($id){
	global $CI;
	$get_kelompok = $CI->kelompok->get($id);
	$result = ($get_kelompok) ? $get_kelompok->nama_kelompok : '0';
	return $result;
}
function find_jurusan($jurusan_id){
	global $CI;
	$find_ref_jurusan = $CI->jurusan->find_by_jurusan_id($jurusan_id);
	if(!$find_ref_jurusan && $jurusan_id){
		$query_get_ref_jurusan_dapo = $CI->_database->get_where('ref.jurusan', array('jurusan_id' => $jurusan_id));
		$get_ref_jurusan_dapo = $query_get_ref_jurusan_dapo->row();
		$jurusan_induk = ($get_ref_jurusan_dapo->jurusan_induk) ? $get_ref_jurusan_dapo->jurusan_induk : 0;
		$insert_jurusan = array(
			'jurusan_id'		=> $get_ref_jurusan_dapo->jurusan_id,
			'jurusan_induk'		=> $jurusan_induk,
			'nama_jurusan'		=> $get_ref_jurusan_dapo->nama_jurusan,
			'level_bidang_id'	=> $get_ref_jurusan_dapo->level_bidang_id,
		);
		$CI->jurusan->insert($insert_jurusan);
		return find_jurusan($jurusan_induk);
	} else {
		return true;
	}
}
function migrasi($database){
	$CI =& get_instance();
	$CI->load->database();
	$CI->load->dbutil();
	foreach($database as $db){
		if (!$CI->db->table_exists($db)){
			redirect('migrate');
		}
	}
}
function migrasi_field($field, $table){
	$CI =& get_instance();
	$CI->load->database();
	$CI->load->dbutil();
	if (!$CI->db->field_exists($field, $table)){
		redirect('migrate');
	}
}
function migrasi_jurusan_sp(){
	$CI =& get_instance();
	$CI->load->database();
	$CI->load->dbutil();
	$get_jurusan = $CI->jurusan_sp->get_all();
	foreach($get_jurusan as $jurusan){
		$find_jurusan = get_jurusan($jurusan->kurikulum_id);
		if($find_jurusan == '-'){
			$get_kurikulum = $CI->kurikulum->find_by_kurikulum_id($jurusan->kurikulum_id);
			if($get_kurikulum){
				$CI->jurusan_sp->update($jurusan->id, array('kurikulum_id' => $get_kurikulum->jurusan_id));
			}
		}
	}
}
function check_mapel_ganda(){
	$CI =& get_instance();
	$CI->load->database();
	$CI->load->dbutil();
	$query = $CI->db->query("SELECT id_nasional FROM ref_mata_pelajaran WHERE id_nasional != 0 group by id_nasional having count(*) >= 2");
	$result = $query->result();
	if($result){
		$CI->db->truncate('ref_mata_pelajaran');
	}
}
function clean_table($table){
	$CI =& get_instance();
	$CI->load->database();
	$CI->load->dbforge();
	if ($CI->db->table_exists($table)){
		$CI->dbforge->drop_table($table,TRUE);
	}
}
function delete_kurikulum(){
	$CI =& get_instance();
	if ($CI->db->table_exists('mata_pelajaran_kurikulum') && $CI->db->table_exists('ref_mata_pelajaran')){
		$CI->load->model('mata_pelajaran_kurikulum_model', 'mata_pelajaran_kurikulum');
		$delete_kurikulum = $CI->mata_pelajaran_kurikulum->find_all("mata_pelajaran_id NOT IN(SELECT id FROM ref_mata_pelajaran)");
		if($delete_kurikulum){
			foreach($delete_kurikulum as $del_kur){
				$CI->mata_pelajaran_kurikulum->delete($del_kur->id);
			}
		}
	}
	$file = APPPATH.'migrations/001_install_ion_auth.php';
	if(is_file($file)){
		chmod($file, 0777); 
		unlink($file);
	}
}
function check_row_nilai(){
	$CI =& get_instance();
	$CI->load->database();
	if ($CI->db->table_exists('nilais')){
		$count = $CI->db->count_all_results('nilais');
		if($count == 0){
			clean_table('nilais');
			clean_table('rencana_penilaians');
			clean_table('rencanas');
			//clean_table('data_mapels');
		}
	}
}
function reorder_kelompok(){
	$CI =& get_instance();
	$CI->load->database();
	$get_kelompok = $CI->kelompok->find_all("id IN(13,14,15)");
	if($get_kelompok){
		foreach($get_kelompok as $kelompok){
			if($kelompok->nama_kelompok != 'Produktif'){
				$CI->kelompok->delete($kelompok->id);
			}
		}
	}
	$get_kelompok = $CI->kelompok->get(13);
	if(!$get_kelompok){
		 $insert_kelompok = array('id' => '13','nama_kelompok' => 'Produktif','kurikulum' => '2006','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50');
		$CI->kelompok->insert($insert_kelompok);
	}
	$ref_a_rev = $CI->kelompok->get(6);
	if($ref_a_rev){
		if($ref_a_rev->nama_kelompok == 'Kelompok A (Wajib)'){
			 $CI->kelompok->update(6, array('nama_kelompok' => 'Kelompok A (Muatan Nasional)'));
		}
	}
	$ref_b_rev = $CI->kelompok->get(7);
	if($ref_b_rev){
		if($ref_b_rev->nama_kelompok == 'Kelompok B (Wajib)'){
			$CI->kelompok->update(7, array('nama_kelompok' => 'Kelompok B (Muatan Kewilayahan)'));
		}
	}
}
function get_version(){
	global $CI;
	$get_version = $CI->settings->get(1);
	$result = ($get_version) ? $get_version->version : 0;
	return $result;
}
function limit_words($id,$string, $word_limit){
	$string = add_responsive_class($string);
	$words = explode(" ",$string);
	if(count($words)>$word_limit){
		return implode(" ",array_splice($words,0,$word_limit)).' <a href="'.site_url('admin/laporan/view_deskripsi_antar_mapel/'.$id).'">Selengkapnya &raquo;</a>';
	} else {
		return $string;
	}
}
function add_responsive_class($content){
	$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
	$document = new DOMDocument();
	libxml_use_internal_errors(true);
	$document->loadHTML(utf8_decode($content));
	$imgs = $document->getElementsByTagName('img');
	foreach ($imgs as $img){
		$img->setAttribute('class','gambar');
	}
	$html = $document->saveHTML();
	$html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">','',$html);
	$html = str_replace('<html><body>','',$html);
	$html = str_replace('</body></html>','',$html);
	return $html;
}
function get_nama_ekskul($id){
	global $CI;
	$ekskul = $CI->ekstrakurikuler->get($id);
	$nama_ekskul = ($ekskul) ? $ekskul->nama_ekskul : '-';
	return $nama_ekskul;
}
function get_nilai_ekskul($id){
	if($id == 1){
		$nilai_ekskul = 'Sangat Baik';
	} elseif($id == 2){
		$nilai_ekskul = 'Baik';
	} elseif($id == 3){
		$nilai_ekskul = 'Cukup';
	} elseif($id == 4){
		$nilai_ekskul = 'Kurang';
	} else {
		$nilai_ekskul = '-';
	}
	return $nilai_ekskul;
}
function status_connect($status){
	if($status == 1) : 
		$label = '<span class="btn btn-sm btn-success active"> <strong>CONNECTED</strong> </span>';
	elseif ($status == 0) : 
		$label = '<span class="btn btn-sm btn-danger active"> <strong>DISCONNECTED</strong> </span>';
	endif;
	return $label;
}
function set_time_zone(){
	$CI = & get_instance();
	$CI->load->model('settings_model', 'settings');
	$get_settings = $CI->settings->get(1);
	$get_timezone = ($get_settings) ? $get_settings->zona : 1;
	if($get_timezone == 1){
		date_default_timezone_set('Asia/Jakarta');
	}elseif($get_timezone == 2){
		date_default_timezone_set('Asia/Ujung_Pandang');
	}elseif($get_timezone == 3){
		date_default_timezone_set('Asia/Jayapura');
	}
}
function save_to_png($data){
	$img = $_POST['img'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = MEDIAFOLDER . uniqid() . '.png';
	$success = file_put_contents($file, $data);
	//print $success ? $file : 'Unable to save the file.';
}
function base64ToImage($imageData){
	if (check_base64_image($imageData)) {
		$data = 'data:image/png;base64,AAAFBfj42Pj4';
		list($type, $imageData) = explode(';', $imageData);
		list(,$extension) = explode('/',$type);
		list(,$imageData)      = explode(',', $imageData);
		$fileName = MEDIAFOLDER . uniqid().'.'.$extension;
		$imageData = base64_decode($imageData);
		file_put_contents($fileName, $imageData);
		return $fileName;
	} else {
		return str_replace(base_url(),'',$imageData);
	}
}
function find_img($content){
	$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
	$document = new DOMDocument();
	libxml_use_internal_errors(true);
	$document->loadHTML(utf8_decode($content));
	$imgs = $document->getElementsByTagName('img');
	$images = array();
	foreach ($imgs as $img){
		$img1[] = base64ToImage($img->getAttribute('src'));
		$img2[] = $img->getAttribute('src');
		//$html = preg_replace("/<img[^>]+\>/i", '<img src="'.base64ToImage($img->getAttribute('src')).'">', $html); 
	}
	$html = $document->saveHTML();
	if(isset($img2)){
		foreach($img2 as $k=>$dua){
			$html = str_replace($dua,base_url($img1[$k]),$html);
		}
	}
	$html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">','',$html);
	$html = str_replace('<html><body>','',$html);
	$html = str_replace('</body></html>','',$html);
	return $html;
}
function check_base64_image($imageData) {
	$findme   = base_url();
	$pos = strpos($imageData, $findme);
	if ($pos !== false) {
     	return false;
	} else {
		return true;
	}
}
function get_pekerjaan($id){
	$CI = & get_instance();
	$CI->load->model('pekerjaan_model', 'pekerjaan');
	$query = $CI->pekerjaan->get($id);
	$result = ($query) ? $query->nama : '-';
	return $result;
}
function diterima_kelas($peserta_didik_id){
	$CI = & get_instance();
	$CI->_database->select('*');
	$CI->_database->from('anggota_rombel');
	$CI->_database->join('rombongan_belajar', 'rombongan_belajar.rombongan_belajar_id = anggota_rombel.rombongan_belajar_id');
	$CI->_database->where('peserta_didik_id', $peserta_didik_id);
	$CI->_database->where('jenis_pendaftaran_id', 1);
	$query = $CI->_database->get();
	$find_diterima_kelas = $query->row();
	$diterima_kelas = ($find_diterima_kelas) ? $find_diterima_kelas->nama : '-';
	return $diterima_kelas;
}
function update_version($versi){
	$CI = & get_instance();
	$settings 	= $CI->settings->get(1);
	if($settings->version < $versi){
		$CI->settings->update(1, array('version' => $versi));
	}
}