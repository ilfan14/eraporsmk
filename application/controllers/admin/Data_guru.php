<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data_guru extends Backend_Controller {
	protected $activemenu = 'referensi';
	public function __construct() {
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->template->set('activemenu', $this->activemenu);
	}

	public function index(){
		$super_admin = array(1,2);
		$pilih_rombel = '';
		if($this->ion_auth->in_group($super_admin)){
			$pilih_rombel = '<a href="'.site_url('admin/data_guru/tambah_guru').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data Guru</a>';
		}
		$this->template->title('Administrator Panel : Data Guru')
        ->set_layout($this->admin_tpl)
		->set('pilih_rombel', $pilih_rombel)
        ->set('page_title', 'Referensi Guru')
        ->build($this->admin_folder.'/guru/list');
	}
	public function select($id){
		$loggeduser = $this->ion_auth->user()->row();
		$user 	= $this->guru->find_all_by_sekolah_id($loggeduser->sekolah_id);
		//Dataguru::find('all', array('order' => 'nama asc'));
		$this->template->title('Administrator Panel : Detil Guru')
	        ->set_layout($this->modal_tpl)
	        ->set('page_title', 'Nama Guru')
	        ->set('id_rombel', $id)
	        ->set('gurus', $user)
			->set('modal_footer', '<a href="javascript:void(0)" class="btn btn-success btn-sm pilih_guru"><i class="fa fa-plus-circle"></i> Pilih</a>')			
	        ->build($this->admin_folder.'/guru/select');
	}
	public function tambah_guru(){
		//validate form input
		$loggeduser = $this->ion_auth->user()->row();
		$tables = $this->config->item('tables','ion_auth');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('nuptk', 'NUPTK', 'numeric');
		//$this->form_validation->set_rules('nip', 'NIP');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('status_kepegawaian_id', 'Status Kepegawaian', 'required');
		$this->form_validation->set_rules('jenis_ptk', 'Jenis PTK', 'required');
		$this->form_validation->set_rules('agama_id', 'Agama', 'required');
		//$this->form_validation->set_rules('alamat', 'Alamat');
		$this->form_validation->set_rules('rt', 'RT', 'numeric');
		$this->form_validation->set_rules('rw', 'RW', 'numeric');
		//$this->form_validation->set_rules('desa_kelurahan', 'Desa Kelurahan');
		//$this->form_validation->set_rules('kecamatan', 'Kecamatan');
		$this->form_validation->set_rules('kode_pos', 'Kodepos', 'numeric');
		$this->form_validation->set_rules('no_hp', 'Nomor HP', 'numeric');
		$this->form_validation->set_rules('email', 'Email', 'valid_email|required');
		$this->form_validation->set_rules('sekolah_id', 'Sekolah ID', 'required');
		if ($this->form_validation->run() == true){
			$username 				= $this->input->post('nama');
			$email					= strtolower($this->input->post('email'));
			$password				= $this->input->post('password');
			$password_asli			= $this->input->post('password');
			if ( base64_encode(base64_decode($password)) === $password){
				$password = $password;
			} else {
				$password = base64_encode($password);
			}
			$user_type				= $this->input->post('user_type');
			$data = array(
				'nama'					=> $this->input->post('nama'),
				'sekolah_id'			=> $this->input->post('sekolah_id'),
				'nuptk'    				=> $this->input->post('nuptk'),
				'nip'    				=> $this->input->post('nip'),
				'nik'      				=> $this->input->post('nik'),
				'jenis_kelamin'  		=> $this->input->post('jenis_kelamin'),
				'tempat_lahir'	      	=> $this->input->post('tempat_lahir'),
				'tanggal_lahir'      	=> date('Y-m-d', strtotime($this->input->post('tanggal_lahir'))),
				'status_kepegawaian_id'	=> $this->input->post('status_kepegawaian_id'),
				'jenis_ptk'				=> $this->input->post('jenis_ptk'),
				'agama_id'		      	=> $this->input->post('agama_id'),
				'alamat'				=> $this->input->post('alamat'),
				'rt'      				=> $this->input->post('rt'),
				'rw'      				=> $this->input->post('rw'),
				'desa_kelurahan'      	=> $this->input->post('desa_kelurahan'),
				'kecamatan'				=> $this->input->post('kecamatan'),
				'kode_pos'				=> $this->input->post('kode_pos'),
				'no_hp'					=> $this->input->post('no_hp'),
				'password'				=> $password,
				"active"				=> 1,
				'email'					=> strtolower($this->input->post('email')),
				'petugas'				=> strtolower($this->input->post('petugas'))
			);
			if($data['nuptk'] == ''){
				$data['nuptk'] = GenerateID();
			}
			$additional_data = array(
				'sekolah_id'			=> $loggeduser->sekolah_id,
				'id_petugas'			=> $this->session->userdata('user_id'),
				'nuptk'    				=> $data['nuptk'],
				"active"				=> 1,
			);
			if(!empty($_FILES['guruphoto']['name'])){
				$upload_response = $this->upload_photo('guruphoto');
				if($upload_response['success']){
					$data['photo']  = $upload_response['upload_data']['file_name'];
					$additional_data['photo']  = $upload_response['upload_data']['file_name'];
				}
				else{
					$this->session->set_flashdata('error', $upload_response['msg']);
				}
			}
			$find = $this->guru->find("nuptk = '$data[nuptk]' AND nama = '$data[nama]'");
			if($find){
				$message = 'Data guru dengan NUPTK '.$data['nuptk'].' dan Nama '.$data['nama'].' sudah terdaftar';
				$this->session->set_flashdata('message', $message);
				redirect("admin/data_guru/tambah_guru", 'refresh');
			} else {
				$group = array('3');
				$user_id = $this->ion_auth->register($username, $password_asli, $email, $additional_data, $group);
				if($user_id){
					$data['user_id'] = $user_id;
					$guru_id = $this->guru->insert($data);
					after_create_log('guru',$guru_id);
					$updatedata = array('guru_id'=>$guru_id);
					$this->db->where('id', $user_id);
					$this->db->update('users', $updatedata); 
					//check to see if we are creating the user
					//redirect them back to the admin page
					$this->session->set_flashdata('success', 'Berhasil menambah data guru');
					redirect("admin/data_guru/tambah_guru", 'refresh');
				}
				else{
					$message = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					$this->session->set_flashdata('message', $message);
					redirect("admin/data_guru/tambah_guru", 'refresh');
				}
			}
		} else {
			$loggeduser = $this->ion_auth->user()->row();
			$this->data['sekolah_id'] = $loggeduser->sekolah_id;
			$this->data['password'] = '';
			$this->data['groups']=$this->ion_auth->groups()->result_array();
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['nama'] = array(
				'name'  => 'nama',
				'id'    => 'nama',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('nama'),
			);
			$this->data['jenis_kelamin'] = $this->form_validation->set_value('jenis_kelamin');
			$this->data['status_kepegawaian_id'] = $this->form_validation->set_value('status_kepegawaian_id');
			$this->data['jenis_ptk'] = $this->form_validation->set_value('jenis_ptk');
			$this->data['agama_id'] = $this->form_validation->set_value('agama_id');
			$this->data['tempat_lahir'] = array(
				'name'  => 'tempat_lahir',
				'id'    => 'tempat_lahir',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('tempat_lahir'),
			);
			$this->data['tanggal_lahir'] = array(
				'name'  => 'tanggal_lahir',
				'id'    => 'tanggal_lahir',
				'type'  => 'text',
				//'class' => "form-control required datemask",
				'class' => "form-control required datepicker",
				'data'	=> 'date-format="dd-mm-yyyy"',
				'value' => $this->form_validation->set_value('tanggal_lahir'),
			);
			$this->data['nuptk'] = array(
				'name'  => 'nuptk',
				'id'    => 'nuptk',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('nuptk'),
			);
			$this->data['nip'] = array(
				'name'  => 'nip',
				'id'    => 'nip',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('nip'),
			);
			$this->data['alamat'] = array(
				'name'  => 'alamat',
				'id'    => 'alamat',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('alamat'),
			);
			$this->data['rt'] = array(
				'name'  => 'rt',
				'id'    => 'rt',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('rt'),
			);
			$this->data['rw'] = array(
				'name'  => 'rw',
				'id'    => 'rw',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('rw'),
			);
			$this->data['desa_kelurahan'] = array(
				'name'  => 'desa_kelurahan',
				'id'    => 'desa_kelurahan',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('desa_kelurahan'),
			);
			$this->data['kecamatan'] = array(
				'name'  => 'kecamatan',
				'id'    => 'kecamatan',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('kecamatan'),
			);
			$this->data['no_hp'] = array(
				'name'  => 'no_hp',
				'id'    => 'no_hp',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('no_hp'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'class' => "form-control email required",
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['kode_pos'] = array(
				'name'  => 'kode_pos',
				'id'    => 'kode_pos',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('kode_pos'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('password'),
			);
			$this->template->title('Administrator Panel : Tambah Guru')
	        ->set_layout($this->admin_tpl)
	        ->set('page_title', 'Data Guru')
	        ->set('form_action', 'admin/data_guru/tambah')
	        ->build($this->admin_folder.'/guru/_guru', $this->data);
			//->build($this->admin_folder.'/demo');
		}
	}
	public function edit($id){
		$super_admin = array(1,2);
		if(!$this->ion_auth->in_group($super_admin)){
			$this->session->set_flashdata('error', "Anda tidak memiliki akses");
			redirect('admin/siswa/', 'refresh');
		}
		$guru = $this->guru->get($id);
		//validate form input
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('nuptk', 'NUPTK', 'numeric');
		//$this->form_validation->set_rules('nip', 'NIP');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('status_kepegawaian_id', 'Status Kepegawaian', 'required');
		$this->form_validation->set_rules('jenis_ptk', 'Jenis PTK', 'required');
		$this->form_validation->set_rules('agama_id', 'Agama', 'required');
		//$this->form_validation->set_rules('alamat', 'Alamat');
		$this->form_validation->set_rules('rt', 'RT', 'numeric');
		$this->form_validation->set_rules('rw', 'RW', 'numeric');
		//$this->form_validation->set_rules('desa_kelurahan', 'Desa Kelurahan');
		//$this->form_validation->set_rules('kecamatan', 'Kecamatan');
		$this->form_validation->set_rules('kode_pos', 'Kodepos', 'numeric');
		$this->form_validation->set_rules('no_hp', 'Nomor HP', 'numeric');
		$this->form_validation->set_rules('email', 'Email', 'valid_email|required');
		$this->form_validation->set_rules('sekolah_id', 'Sekolah ID', 'required');
		if (isset($_POST) && !empty($_POST)){
			// do we have a valid request?
			if ($id != $this->input->post('id')){
				show_error('Aksi ini tidak memenuhi pemeriksaan keamanan kami');
			}
			if ($this->form_validation->run() === TRUE){
				$data = array(
					'nama'					=> $this->input->post('nama'),
					'sekolah_id'			=> $this->input->post('sekolah_id'),
					'nuptk'    				=> $this->input->post('nuptk'),
					'nip'    				=> $this->input->post('nip'),
					'nik'      				=> $this->input->post('nik'),
					'jenis_kelamin'  		=> $this->input->post('jenis_kelamin'),
					'tempat_lahir'	      	=> $this->input->post('tempat_lahir'),
					'tanggal_lahir'      	=> date('Y-m-d', strtotime($this->input->post('tanggal_lahir'))),
					'status_kepegawaian_id'	=> $this->input->post('status_kepegawaian_id'),
					'jenis_ptk'				=> $this->input->post('jenis_ptk'),
					'agama_id'		      	=> $this->input->post('agama_id'),
					'alamat'				=> $this->input->post('alamat'),
					'rt'      				=> $this->input->post('rt'),
					'rw'      				=> $this->input->post('rw'),
					'desa_kelurahan'      	=> $this->input->post('desa_kelurahan'),
					'kecamatan'				=> $this->input->post('kecamatan'),
					'kode_pos'				=> $this->input->post('kode_pos'),
					'no_hp'					=> $this->input->post('no_hp'),
					"active"				=> 1,
					'email'					=> strtolower($this->input->post('email')),
					'petugas'				=> strtolower($this->input->post('petugas'))
				);
				$data_user = array(
					'username'				=> $this->input->post('nama'),
					'nuptk'	 				=> $this->input->post('nuptk'),
					'email'      			=> $this->input->post('email')
				);
				//if ($this->input->post('password')){
					//$data_user['password'] = $this->input->post('password');
					//$data['password'] = $password;
				//}
				//Save the photo if any
				if(!empty($_FILES['guruphoto']['name'])){
					$upload_response = $this->upload_photo('guruphoto');
					if($upload_response['success']){
						if(is_file(PROFILEPHOTOS.$guru->photo))						{
							unlink(PROFILEPHOTOS.$guru->photo);
							unlink(PROFILEPHOTOSTHUMBS.$guru->photo);
						}
						$data['photo']  = $upload_response['upload_data']['file_name'];
						$data_user['photo']  = $upload_response['upload_data']['file_name'];
					}
					else{
						$this->session->set_flashdata('error', $upload_response['msg']);
					}
				}
				$this->ion_auth->update($guru->user_id, $data_user);
				$this->guru->update($id, $data);
				after_update_log('guru',$id);
				$this->session->set_flashdata('success', "Guru berhasil di edit");
			} else {
				$this->session->set_flashdata('error', (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message'))));
			}
			redirect('admin/data_guru/edit/'.$guru->id, 'refresh');
		}
		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		//pass the user to the view
		$loggeduser = $this->ion_auth->user()->row();
		$this->data['sekolah_id'] = $loggeduser->sekolah_id;
		$this->data['user'] = $guru;
		$this->data['nama'] = array(
			'name'  => 'nama',
			'id'    => 'nama',
			'type'  => 'text',
			'class' => "form-control required",
			'value' => $this->form_validation->set_value('nama', $guru->nama),
		);
		$this->data['jenis_kelamin'] = $this->form_validation->set_value('jenis_kelamin', $guru->jenis_kelamin);
		$this->data['status_kepegawaian_id'] = $this->form_validation->set_value('status_kepegawaian_id', $guru->status_kepegawaian_id);
		$this->data['jenis_ptk'] = $this->form_validation->set_value('jenis_ptk', $guru->jenis_ptk);
		$this->data['agama_id'] = $this->form_validation->set_value('agama_id', $guru->agama_id);
		$this->data['nip'] = array(
			'name'  => 'nip',
			'id'    => 'nip',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nip', $guru->nip),
		);
		$this->data['nuptk'] = array(
			'name'  => 'nuptk',
			'id'    => 'nuptk',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nuptk', $guru->nuptk),
		);
		$this->data['tempat_lahir'] = array(
			'name'  => 'tempat_lahir',
			'id'    => 'tempat_lahir',
			'type'  => 'text',
			'class' => "form-control required",
			'value' => $this->form_validation->set_value('tempat_lahir', $guru->tempat_lahir),
		);
		$date = date_create($guru->tanggal_lahir);
		$this->data['tanggal_lahir'] = array(
			'name'  => 'tanggal_lahir',
			'id'    => 'tanggal_lahir',
			'type'  => 'text',
			'class' => "form-control required",
			'value' => $this->form_validation->set_value('tanggal_lahir', date_format($date,'d-m-Y')),
		);
		$this->data['alamat'] = array(
			'name'  => 'alamat',
			'id'    => 'alamat',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('alamat', $guru->alamat),
		);
		$this->data['rt'] = array(
			'name'  => 'rt',
			'id'    => 'rt',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('rt', $guru->rt),
		);
		$this->data['rw'] = array(
			'name'  => 'rw',
			'id'    => 'rw',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('rw', $guru->rw),
		);
		$this->data['desa_kelurahan'] = array(
			'name'  => 'desa_kelurahan',
			'id'    => 'desa_kelurahan',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('desa_kelurahan', $guru->desa_kelurahan),
		);		
		$this->data['kecamatan'] = array(
			'name'  => 'kecamatan',
			'id'    => 'kecamatan',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('kecamatan', $guru->kecamatan),
		);		
		$this->data['no_hp'] = array(
			'name'  => 'no_hp',
			'id'    => 'no_hp',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nomor_telepon_seluler', $guru->no_hp),
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
			'class' => "form-control",
			//'readonly'  => 'readonly',
			'value' => $this->form_validation->set_value('email', $guru->email),
		);
		/*$this->data['password'] = array(
			'name'  => 'password',
			'id'    => 'password',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('password'),
		);*/
		$this->template->title('Administrator Panel : Edit Guru')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Edit Guru')
		->build($this->admin_folder.'/guru/_guru', $this->data);
		//->build($this->admin_folder.'/demo');

	}

	public function view($id){
		$user = $this->guru->get($id);
		$this->template->title('Administrator Panel : Detil Guru')
	        ->set_layout($this->modal_tpl)
	        ->set('page_title', 'Detil Guru')
	        ->set('user', $user)
	        //->set('groups', $this->ion_auth->get_users_groups($id)->result())
			->set('modal_footer', '')			
	        ->build($this->admin_folder.'/guru/view');
	}
	public function multidelete(){
		$ids = $_POST['id'];
		$super_admin = array(1,2);
		if($this->ion_auth->in_group($super_admin)){
			foreach($ids as $id){
				$data = $this->guru->get($id);
				if(is_file(PROFILEPHOTOS.$data->photo)){
					unlink(PROFILEPHOTOS.$data->photo);
					unlink(PROFILEPHOTOSTHUMBS.$data->photo);
				}
				$user = $this->user->get($data->user_id);
				if($user && is_file(PROFILEPHOTOS.$user->photo)){
					unlink(PROFILEPHOTOS.$user->photo);
					unlink(PROFILEPHOTOSTHUMBS.$user->photo);
				}
				if($user) $this->ion_auth->delete_user($user->id);
				$this->guru->delete($id);
			}
			$status['type'] = 'success';
			$status['text'] = 'Data Guru berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data Guru tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
	public function delete($id){
		$super_admin = array(1,2);
		if($this->ion_auth->in_group($super_admin)){
			$data = $this->guru->get($id);
			if(is_file(PROFILEPHOTOS.$data->photo)){
				unlink(PROFILEPHOTOS.$data->photo);
				unlink(PROFILEPHOTOSTHUMBS.$data->photo);
			}
			$user = $this->user->get($data->user_id);
			if($user && is_file(PROFILEPHOTOS.$user->photo)){
				unlink(PROFILEPHOTOS.$user->photo);
				unlink(PROFILEPHOTOSTHUMBS.$user->photo);
			}
			if($user) $this->ion_auth->delete_user($user->id);
			$this->guru->delete($id);
			before_destroy('guru','ref_guru',$id);
			$status['type'] = 'success';
			$status['text'] = 'Data Guru berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data Guru tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
	public function _get_csrf_nonce(){
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function deactivate($id = NULL){
		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->template->title('Administrator Panel : Deactivate user')
	        ->set_layout('modal_tpl')
	        ->set_partial('styles', 'backend/partials/css')
	        ->set_partial('header', 'backend/partials/header')
	        ->set_partial('sidebar', 'backend/partials/sidebar')
	        ->set_partial('footer', 'backend/partials/footer')
	        ->set('page_title',  'Deactivate User' )
			->set('modal_footer', '')
	        ->build('auth/deactivate_user', $this->data);
		} else {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}
				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
					$this->session->set_flashdata('success', $this->ion_auth->messages());
				}
			}
			//redirect them back to the auth page
			redirect('admin/guru');
		}
	}

	//activate the user
	function activate($id)
	{

		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('success', $this->ion_auth->messages());
			redirect("admin/guru");
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('error', $this->ion_auth->errors());
			redirect("admin/guru");
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
    public function list_data_guru(){
		$ajaran = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 25;
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		$start = get_start();
		$rows = get_rows();
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$guru_id = $find_akses['id'][0];
			$pembelajaran = $this->pembelajaran->find_all("guru_id = $guru_id", '*','rombongan_belajar_id ASC');
			foreach($pembelajaran as $belajar){
				$id_mapel[] = $belajar->id;
			}
			if(isset($id_mapel)){
				$id_mapel = array_unique($id_mapel);
				$id_mapel = "'" . implode("','", $id_mapel) . "'";//implode(",", $id_mapel);
			} else {
				$id_mapel = '0';
			}
			$join = "INNER JOIN kurikulums a ON(data_gurus.id= a.guru_id AND a.id_mapel IN ($id_mapel) AND a.guru_id != $guru_id)";
			//$query = Dataguru::find('all', array('conditions' => "data_gurus.id IS NOT NULL AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%' OR tempat_lahir LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'nama ASC', 'joins'=> $join, 'group' => 'id'));
			//$filter = Dataguru::find('all', array('conditions' => "data_gurus.id IS NOT NULL AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%' OR tempat_lahir LIKE '%$search%')",'order'=>'nama ASC', 'joins'=> $join, 'group' => 'id'));
			$query = $this->guru->with('pembelajaran')->find_all("sekolah_id = $loggeduser->sekolah_id AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%')", '*','nama ASC', $start, $rows);
			$filter = $this->guru->with('pembelajaran')->find_all("sekolah_id = $loggeduser->sekolah_id AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%')", '*','nama ASC');
		} else {
			//$query = Dataguru::find('all', array('conditions' => "data_gurus.id IS NOT NULL AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%' OR tempat_lahir LIKE '%$search%')",'limit' => $rows, 'offset' => $start,'order'=>'nama ASC'));
			//$filter = Dataguru::find('all', array('conditions' => "data_gurus.id IS NOT NULL AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%' OR tempat_lahir LIKE '%$search%')",'order'=>'nama ASC'));
			$query = $this->guru->with('pembelajaran')->find_all("sekolah_id = $loggeduser->sekolah_id AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%')", '*','nama ASC', $start, $rows);
			$filter = $this->guru->with('pembelajaran')->find_all("sekolah_id = $loggeduser->sekolah_id AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%')", '*','nama ASC');
		}
		$iFilteredTotal = count($query);
		
		$iTotal=count($filter);
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		$admin_group = array(1,2);
	    foreach ($query as $temp) {
			if($temp->jenis_kelamin == 'L'){
				if($temp->photo)
					$fotoguru = base_url().PROFILEPHOTOSTHUMBS.$temp->photo;
				else
					$fotoguru = base_url().'assets/img/no_avatar.jpg';
			} else {
				if($temp->photo)
					$fotoguru = base_url().PROFILEPHOTOSTHUMBS.$temp->photo;
				else
					$fotoguru = base_url().'assets/img/no_avatar_f.jpg';
			}
			if($temp->nuptk){
				$nuptk = $temp->nuptk;
			} else {
				$nuptk = '-';
			}
			$find_guru_terdaftar = $this->guru_terdaftar->find("guru_id = $temp->id and semester_id = $ajaran->id");
			if($find_guru_terdaftar){
				if($find_guru_terdaftar->status == 0){
					$update_guru_terdaftar = array(
						'status' => 1
					);
					$this->guru_terdaftar->update($find_guru_terdaftar->id, $update_guru_terdaftar);
				}
			} else {
				$attributes = array('semester_id' => $ajaran->id, 'guru_id' => $temp->id, 'status' => 1);			
				$guru_terdaftar = $this->guru_terdaftar->insert($attributes);
			}
			$admin_akses = '<div class="btn-group">';
			$admin_akses .= '<button type="button" class="btn btn-default btn-sm">Aksi</button>';
			$admin_akses .= '<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">';
			$admin_akses .= '<span class="caret"></span>';
			$admin_akses .= '<span class="sr-only">Toggle Dropdown</span>';
			$admin_akses .= '</button>';
			$admin_akses .= '<ul class="dropdown-menu pull-right text-left" role="menu">';
			$admin_akses .= '<li><a href="'.site_url('admin/data_guru/view/'.$temp->id).'" class="toggle-modal"><i class="fa fa-eye"></i>Detil</a></li>';
			$admin_akses .= '<li><a href="'.site_url('admin/data_guru/edit/'.$temp->id).'"><i class="fa fa-pencil"></i>Edit</a></li>';
			$admin_akses .= '<li><a href="'.site_url('admin/data_guru/delete/'.$temp->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>';
			$admin_akses .= '</ul>';
			$admin_akses .= '</div>';
			if($loggeduser->guru_id){
				$admin_akses = '<a href="'.site_url('admin/data_guru/view/'.$temp->id).'" class="btn btn-primary btn-sm toggle-modal"><i class="fa fa-eye"></i> Detil</a>';
			}
			$record = array();
            $tombol_aktif = '';
			if($this->ion_auth->in_group($admin_group)){
			$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			}
			//$record[] = '<img src="'.$fotoguru.'" width="50" style="float:left; margin-right:10px;" /> '.$temp->nama.'<br />'.$nuptk;
			$record[] = '<img src="'.$fotoguru.'" width="50" style="float:left; margin-right:10px;" /> '.get_nama_guru($temp->id).'<br />'.$nuptk;
			$record[] = '<div class="text-center">'.$temp->jenis_kelamin.'</div>';
			$date = date_create($temp->tanggal_lahir);
			$record[] = $temp->tempat_lahir.', '.TanggalIndo(date_format($date,'Y-m-d'));
            //$record[] = '<div class="text-center"><a href="'.site_url('admin/data_guru/mapel/'.$temp->id).'" class="toggle-modal btn btn-primary btn-sm">Mapel Diampu</a></div>';
			$record[] = '<div class="text-center">'.status_label($temp->active).'</div>';
			$record[] = '<div class="text-center">'.$admin_akses.'</div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function mapel($id){
		$guru = Dataguru::find($id);
		$kelas = Datarombel::find('all', array('group' => 'tingkat','order'=>'tingkat ASC'));
		$ajaran = get_ta();
		$all_mapel = Kurikulum::find_all_by_ajaran_id_and_guru_id($ajaran->id,$id);
		$this->template->title('Administrator Panel : Detil Guru')
		->set_layout($this->modal_tpl)
		->set('page_title', 'Data Mata Pelajaran yang diampu oleh '.$guru->nama)
		->set('guru_id', $id)
		->set('kelas', $kelas)
		->set('ajaran_id', $ajaran->id)
		->set('all_mapel', $all_mapel)
		->set('modal_footer', '<a href="javascript:void(0)" class="btn btn-success btn-sm simpan_mapel"><i class="fa fa-save"></i> Simpan</a>')
		->build($this->admin_folder.'/guru/mapel');
	}
}