<?php
$semester = get_ta();
$user = $this->ion_auth->user()->row();
$user_id = ($user) ? $user->id : 0;
$guru_id = ($user->guru_id) ? $user->guru_id : 0;
if(!$guru_id){
$data_guru	= $this->guru->find_by_user_id($user->id);
$guru_id = ($data_guru) ? $data_guru->id : 0;
$this->user->update($user->id, array('guru_id' => $guru_id));
}
$siswa_id = ($user) ? $user->siswa_id : 0;
$cari_rombel = $this->rombongan_belajar->find("semester_id = $semester->id and guru_id = $guru_id");
//test($cari_rombel);
$waka = array('waka');
$akses = '';
if($this->ion_auth->in_group('admin')){
	$akses = 'admin';
	$this->load->view('backend/partials/sidebar_admin');
} elseif($this->ion_auth->in_group('tu')){
	$akses = 'tu';
	$this->load->view('backend/partials/sidebar_tu');
} elseif($this->ion_auth->in_group('waka')){
	$akses = 'waka';
	$this->load->view('backend/partials/sidebar_waka');
} elseif($this->ion_auth->in_group('siswa')){
	$akses = 'siswa';
	$this->load->view('backend/partials/sidebar_siswa');
} elseif(!$cari_rombel && $this->ion_auth->in_group('guru')){
	$akses = 'guru';
	$this->load->view('backend/partials/sidebar_guru');
} elseif($cari_rombel && $this->ion_auth->in_group('guru')){
	$akses = 'wali';
	$this->load->view('backend/partials/sidebar_wali');
} elseif($this->ion_auth->in_group('user')){
	$akses = 'user';
	$this->load->view('backend/partials/sidebar_user');
}
?>