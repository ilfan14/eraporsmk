<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends Backend_Controller {
	protected $activemenu = 'profil';
	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->template->set('activemenu', $this->activemenu);
	}

	public function index(){
		$join = 'LEFT JOIN users_groups a ON(users.id = a.user_id)';
		$sel = 'users.*, a.group_id AS id_group';
		$data['users'] = User::all(array('joins' => $join,'select'=>$sel,'conditions' => "a.group_id = 2"));
		$this->template->title('Administrator Panel : Atur Operator')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Data Operator')
        ->build($this->admin_folder.'/users/list', $data);
	}
	public function user(){
		$user = $this->ion_auth->user()->row();
		//validate form input
		$this->form_validation->set_rules('username', 'Nama', 'required');
		$this->form_validation->set_rules('phone', 'Handphone');
		$this->form_validation->set_rules('email', 'Email', 'required');
		if (isset($_POST) && !empty($_POST)){
			if ($this->input->post('password')){
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[10]|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required');
			}
			if ($this->form_validation->run() === TRUE){
				$data = array(
					'username' => $this->input->post('username', TRUE),
					'phone'      => $this->input->post('phone'),
					'email'      => $this->input->post('email', TRUE)
				);
					//update the password if it was posted
				if ($this->input->post('password')){
					$data['password'] = $this->input->post('password');
				}
				//Save the photo if any
				if(!empty($_FILES['profilephoto']['name'])){
					$upload_response = $this->upload_photo('profilephoto');
					if($upload_response['success']){
						if(is_file(PROFILEPHOTOS.$user->photo))						{
							unlink(PROFILEPHOTOS.$user->photo);
							unlink(PROFILEPHOTOSTHUMBS.$user->photo);
						}
						$data['photo']  = $upload_response['upload_data']['file_name'];
					}
					else{
						$this->session->set_flashdata('error', $upload_response['msg']);
					}
				}
				$this->ion_auth->update($user->id, $data);
				$this->session->set_flashdata('success', "Profile Updated");
			} else {
				$this->session->set_flashdata('error', (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message'))));
			}
			redirect('admin/profil/user');
		}
		$data['menu'] = 'profile';
		$data['user'] = $user;
		$this->template->title('Edit Profile')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Edit Profile')
		->set('action', site_url('admin/profil/user'))
        ->build($this->admin_folder.'/profil/user', $data);
	}
	public function sekolah(){
		$loggeduser = $this->ion_auth->user()->row();
		$data['settings'] = $this->settings->get(1);
		$data['sekolah'] = $this->sekolah->get($loggeduser->sekolah_id);
		$data['guru_id'] = $data['sekolah']->guru_id;
		$this->template->title('Administrator Panel')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Profil Sekolah')
        ->build($this->admin_folder.'/profil/sekolah', $data);
	}
	public function kop_surat(){
		if($_POST){
			$sekolah_id = $this->input->post('sekolah_id');
			$kop_surat = $this->input->post('kop_surat');
			if($sekolah_id){
				if($this->sekolah->update($sekolah_id, array('kop_surat' => $kop_surat))){
					$this->session->set_flashdata('success', 'Kop surat berhasil diperbaharui');
				} else {
					$this->session->set_flashdata('error', 'Aksi tidak sah!');
				}
			} else {
				$this->session->set_flashdata('error', 'Aksi tidak sah!');
			}
		} else {
			$this->session->set_flashdata('error', 'Aksi tidak sah!');
		}
		redirect('admin/profil/sekolah');
	}
	public function pengantar_ppk(){
		if($_POST){
			$sekolah_id = $this->input->post('sekolah_id');
			$kop_surat = $this->input->post('pengantar_ppk');
			if($sekolah_id){
				if($this->sekolah->update($sekolah_id, array('pengantar_ppk' => $kop_surat))){
					$this->session->set_flashdata('success', 'Kata Pengantar PPK berhasil diperbaharui');
				} else {
					$this->session->set_flashdata('error', 'Aksi tidak sah!');
				}
			} else {
				$this->session->set_flashdata('error', 'Aksi tidak sah!');
			}
		} else {
			$this->session->set_flashdata('error', 'Aksi tidak sah!');
		}
		redirect('admin/profil/sekolah');
	}
	public function update_sekolah(){
		if($_POST){
			//test($_POST);
			//die();
			$semester = get_ta();
			$loggeduser = $this->ion_auth->user()->row();
			$config['upload_path'] = MEDIAFOLDER;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '0';
			$settings 	= $this->settings->get(1);
			$sekolah 	= $this->sekolah->get(1);
			$data_sekolah = array(
							'nama'				=> $this->input->post('nama_sekolah'),
							'npsn'				=> $this->input->post('npsn_sekolah'),
							'alamat' 			=> $this->input->post('alamat_sekolah'),
							'desa_kelurahan'	=> $this->input->post('desa_kelurahan_sekolah'),
							'kecamatan' 		=> $this->input->post('kecamatan_sekolah'),
							'kabupaten' 		=> $this->input->post('kabupaten_sekolah'),
							'provinsi' 			=> $this->input->post('provinsi_sekolah'),
							'kode_pos' 			=> $this->input->post('kodepos_sekolah'),
							'lintang' 			=> $this->input->post('lintang_sekolah'),
							'bujur' 			=> $this->input->post('bujur_sekolah'),
							'no_telp' 			=> $this->input->post('telp_sekolah'),
							'no_fax' 			=> $this->input->post('fax_sekolah'),
							'email' 			=> $this->input->post('email_sekolah'),
							'website' 			=> $this->input->post('website_sekolah'),
							'user_id' 			=> $loggeduser->id,
							'guru_id' 			=> $this->input->post('guru_id'),
							);
			$data_keahlian = $this->input->post('kompetensi_keahlian');
			//$keahlian = Keahlian::all();
			//$keahlian = Keahlian::find('all', array('conditions' => "ajaran_id = $ajaran->id"));
			//if($keahlian){
				//foreach($keahlian as $ahli){
					//$ahli->delete();
				//}
			//}
			$sekolah_id = $this->input->post('sekolah_id');
			$kurikulum_id = 0;
			foreach($data_keahlian as $datakeahlian){
				$set_kurikulum_id[] = $datakeahlian;
				$find_keahlian = $this->jurusan_sp->find("sekolah_id = $sekolah_id and kurikulum_id = $datakeahlian");
				if(!$find_keahlian){
					$attributes = array('sekolah_id' => $sekolah_id, 'kurikulum_id' => $datakeahlian);
					$keahlian_new = $this->jurusan_sp->insert($attributes);
				}
			}
			if(isset($set_kurikulum_id)){
				$kurikulum_id = implode(',',$set_kurikulum_id);
				$delete_keahlian = $this->jurusan_sp->find_all("sekolah_id = $sekolah_id AND kurikulum_id NOT IN ($kurikulum_id)");
				if($delete_keahlian){
					foreach($delete_keahlian as $del){
						$this->jurusan_sp->delete($del->id);
					}
				}
			}
			//$setting = array(
				//'kepsek' 			=> $this->input->post('kepsek'),
				//'nip_kepsek' 		=> $this->input->post('nip_kepsek'),
				//'sambutan_kepsek'	=> $this->input->post('sambutan_kepsek'),
			//);
				if(!empty($_FILES['profilephoto']['name'])){
					$upload_response = $this->upload_photo('profilephoto');
					if($upload_response['success']){
						if(is_file(PROFILEPHOTOS.$sekolah->logo_sekolah)){
							unlink(PROFILEPHOTOS.$sekolah->logo_sekolah);
							unlink(PROFILEPHOTOSTHUMBS.$sekolah->logo_sekolah);
						}
						$data_sekolah['logo_sekolah']  = $upload_response['upload_data']['file_name'];
					}
					else{
						$this->session->set_flashdata('error', $upload_response['msg']);
					}
				}
				//$this->settings->update(1, $setting);
				$user = $this->user->get($loggeduser->id);
				if(!$user->sekolah_id){
					$this->user->update($loggeduser->id, array('sekolah_id' => $sekolah->id));
				}
				$this->sekolah->update($loggeduser->sekolah_id, $data_sekolah);
				$this->session->set_flashdata('success', 'Profil Sekolah berhasil di update');
				redirect('admin/profil/sekolah');
		}
		else{
			redirect('admin/profil/sekolah');
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------
	function to upload user photos
-------------------------------------------------------------------------------------------------------------------------*/
	public function upload_photo($fieldname) {
		//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
		$config['upload_path'] = PROFILEPHOTOS;
		// set the filter image types
		$config['allowed_types'] = 'png|gif|jpeg|jpg';
		//$config['max_width'] = '500'; 
		$this->load->helper('string');
		$config['file_name']	 = random_string('alnum', 32);
		//load the upload library
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
	
		//if not successful, set the error message
		if (!$this->upload->do_upload($fieldname)) {
			$data = array('success' => false, 'msg' => $this->upload->display_errors());
		} else { 
			$upload_details = $this->upload->data(); //uploading
			$config1 = array(
			      'source_image' => $upload_details['full_path'], //get original image
			      'new_image' => PROFILEPHOTOSTHUMBS, //save as new image //need to create thumbs first
			      'maintain_ratio' => true,
			      'width' => 250,
			      'height' => 250
			    );
		    $this->load->library('image_lib', $config1); //load library
		    $this->image_lib->resize(); //generating thumb
			$data = array('success' => true, 'upload_data' => $upload_details, 'msg' => "Upload success!");
		}
		return $data;
	}
	public function _valid_csrf_nonce(){
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')){
			return TRUE;
		} else {
			return FALSE;
		}
	}
}