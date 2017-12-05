<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data_siswa extends Backend_Controller {
	protected $activemenu = 'referensi'; 
	public function __construct() {
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->template->set('activemenu', $this->activemenu);
		//$this->load->model('data_model');
	}

	public function index(){
		$loggeduser = $this->ion_auth->user()->row();
		$super_admin = array(1,2);
		$pilih_rombel = '';
		if($this->ion_auth->in_group($super_admin)){
			$pilih_rombel .= '<a href="'.site_url('admin/data_siswa/tambah_siswa').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data Siswa</a>';
		}
		$this->template->title('Administrator Panel : Data Siswa')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Referensi Data Siswa')
		->set('pilih_rombel', $pilih_rombel)
		->set('sekolah_id', $loggeduser->sekolah_id)
        ->build($this->admin_folder.'/siswa/list');
	}
	public function tambah_siswa(){
		//validate form input
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
		$tables = $this->config->item('tables','ion_auth');
		$this->form_validation->set_rules('rombel_id', 'Rombongan Belajar', 'required');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('nisn', 'NISN');
		$this->form_validation->set_rules('no_induk', 'no_induk');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('agama', 'Agama', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat Jalan');
		$this->form_validation->set_rules('rt', 'RT');
		$this->form_validation->set_rules('rw', 'RW');
		$this->form_validation->set_rules('desa_kelurahan', 'Desa Kelurahan');
		$this->form_validation->set_rules('kecamatan', 'Kecamatan');
		$this->form_validation->set_rules('kode_pos', 'Kode Pos', 'numeric');
		$this->form_validation->set_rules('no_telp', 'Nomor Handphone', 'numeric');
		$this->form_validation->set_rules('email', 'Email', 'valid_email');
		$this->form_validation->set_rules('nama_ayah', 'Nama Ayah', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true){
			$nisn					= $this->input->post('nisn');
			$email					= strtolower($this->input->post('email'));
			if($nisn == ''){
				$nisn 				= GenerateNISN();
			}
			if($email == ''){
				$email = GenerateEmail().'@erapor-smk.net';
			}
			$rombongan_belajar_id = $this->input->post('rombel_id');
			$data = array(
				'sekolah_id'			=> $this->input->post('sekolah_id'),
				//'data_rombel_id'		=> $this->input->post('rombel_id'),
				'nama' 					=> $this->input->post('nama'),
				'no_induk' 				=> $this->input->post('no_induk'),
				'nisn'    				=> $nisn,
				'jenis_kelamin'  		=> $this->input->post('jenis_kelamin'),
				'tempat_lahir'      	=> $this->input->post('tempat_lahir'),
				'tanggal_lahir'      	=> date('Y-m-d', strtotime($this->input->post('tanggal_lahir'))),
				'agama'		    		=> $this->input->post('agama'),
				'status'		      	=> $this->input->post('status'),
				'anak_ke'		      	=> $this->input->post('anak_ke'),
				'alamat'		      	=> $this->input->post('alamat'),
				'rt'      				=> $this->input->post('rt'),
				'rw'      				=> $this->input->post('rw'),
				'desa_kelurahan'      	=> $this->input->post('desa_kelurahan'),
				'kecamatan'				=> $this->input->post('kecamatan'),
				'kode_pos'				=> $this->input->post('kode_pos'),
				'no_telp'				=> $this->input->post('no_telp'),
				'sekolah_asal' 			=> $this->input->post('sekolah_asal'),
				'diterima_kelas'		=> $this->input->post('diterima_kelas'),
				'diterima'     			=> date('Y-m-d', strtotime($this->input->post('diterima'))),
				'email'      			=> $email,
				'nama_ayah'      		=> $this->input->post('nama_ayah'),
				'kerja_ayah'      		=> $this->input->post('kerja_ayah'),
				'nama_ibu'      		=> $this->input->post('nama_ibu'),
				'kerja_ibu'      		=> $this->input->post('kerja_ibu'),
				'nama_wali'      		=> $this->input->post('nama_wali'),
				'alamat_wali'      		=> $this->input->post('alamat_wali'),
				'telp_wali'      		=> $this->input->post('telp_wali'),
				'kerja_wali'      		=> $this->input->post('kerja_wali'),
				'password'      		=> $this->input->post('password'),
				"active"				=> 1,
				'petugas'				=> strtolower($this->input->post('petugas'))
			);
			//Save the photo if any
			if(!empty($_FILES['siswaphoto']['name'])){
				$upload_response = $this->upload_photo('siswaphoto');
				if($upload_response['success']){
					$data['photo']  = $upload_response['upload_data']['file_name'];
					$data_user['photo']  = $upload_response['upload_data']['file_name'];
				} else {
					$this->session->set_flashdata('error', $upload_response['msg']);
				}
			}
			$additional_data = array(
				'sekolah_id'	=> $loggeduser->sekolah_id,
				'id_petugas'	=> $this->session->userdata('user_id'),
				'nisn'    		=> $nisn,
				'nipd'    		=> $this->input->post('no_induk'),
				"active"		=> 1,
			);
			$password 				= $this->input->post('password');
			$username 				= $this->input->post('nama');
			$user_type				= $this->input->post('user_type');
			$find = $this->siswa->find("nisn = '$data[nisn]' AND nama = '$data[nama]'");
			//Datasiswa::all(array('conditions' => array('nisn = ? AND nama = ?', $data['nisn'], $data['nama'])));
			if($find){
				$message = 'Data siswa dengan NISN '.$data['nisn'].' dan Nama '.$data['nama'].' sudah terdaftar';
				$this->session->set_flashdata('message', $message);
				redirect("admin/data_siswa/tambah_siswa", 'refresh');
			} else {
				$group = array('4');
				$user_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
				if($user_id){
					$data['user_id'] = $user_id;
					$datasiswa = $this->siswa->insert($data);
					$updatedata = array('siswa_id'=>$datasiswa);
					$this->db->where('id', $user_id);
					$this->db->update('users', $updatedata); 
					$find_anggota_rombel = $this->anggota_rombel->find("semester_id = $ajaran->id AND rombongan_belajar_id = $rombongan_belajar_id AND siswa_id = $datasiswa");
					$insert_anggota_rombel = array(
						'semester_id'	=> $ajaran->id,
						'rombongan_belajar_id'	=> $rombongan_belajar_id,
						'siswa_id'	=> $datasiswa,
					);
					if(!$find_anggota_rombel){
						$this->anggota_rombel->insert($insert_anggota_rombel);
					}
					$this->session->set_flashdata('success', 'Berhasil menambah data siswa');
					redirect("admin/data_siswa", 'refresh');
				} else{
					$message = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					$this->session->set_flashdata('message', $message);
					redirect("admin/data_siswa/tambah_siswa", 'refresh');
				}

			}
		} else {
			$loggeduser = $this->ion_auth->user()->row();
			$this->data['groups']=$this->ion_auth->groups()->result_array();
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['sekolah_id'] = $loggeduser->sekolah_id;
			$this->data['rombel'] = '';
			$this->data['nama'] = array(
				'name'  => 'nama',
				'id'    => 'nama',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('nama'),
			);
			$this->data['no_induk'] = array(
				'name'  => 'no_induk',
				'id'    => 'no_induk',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('no_induk'),
			);
			$this->data['nisn'] = array(
				'name'  => 'nisn',
				'id'    => 'nisn',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('nisn'),
			);
			$this->data['jenis_kelamin'] = 'L';
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
				'class' => "form-control required datepicker",
				'data-date-format' => "dd-mm-yyyy",
				'value' => $this->form_validation->set_value('tanggal_lahir'),
			);
			$this->data['agama_id'] = 1;
			$this->data['status_id'] = 'Anak Kandung';
			$this->data['anak_ke'] = array(
				'name'  => 'anak_ke',
				'id'    => 'anak_ke',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('anak_ke'),
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
			$this->data['kode_pos'] = array(
				'name'  => 'kode_pos',
				'id'    => 'kode_pos',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('kode_pos'),
			);
			$this->data['no_telp'] = array(
				'name'  => 'no_telp',
				'id'    => 'no_telp',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('no_telp'),
			);
			$this->data['sekolah_asal'] = array(
				'name'  => 'sekolah_asal',
				'id'    => 'sekolah_asal',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('sekolah_asal'),
			);
			$this->data['diterima_kelas'] = array(
				'name'  => 'diterima_kelas',
				'id'    => 'diterima_kelas',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('diterima_kelas'),
			);
			$this->data['diterima'] = array(
				'name'  => 'diterima',
				'id'    => 'diterima',
				'type'  => 'text',
				'class' => "form-control datepicker",
				'data-date-format' => "dd-mm-yyyy",
				'value' => $this->form_validation->set_value('diterima'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'class' => "form-control email",
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['nama_ayah'] = array(
				'name'  => 'nama_ayah',
				'id'    => 'nama_ayah',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('nama_ayah'),
			);
			$this->data['kerja_ayah'] = array(
				'name'  => 'kerja_ayah',
				'id'    => 'kerja_ayah',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('kerja_ayah'),
			);		
			$this->data['nama_ibu'] = array(
				'name'  => 'nama_ibu',
				'id'    => 'nama_ibu',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('nama_ibu'),
			);		
			$this->data['kerja_ibu'] = array(
				'name'  => 'kerja_ibu',
				'id'    => 'kerja_ibu',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('kerja_ibu'),
			);	
			$this->data['nama_wali'] = array(
				'name'  => 'nama_wali',
				'id'    => 'nama_wali',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('nama_wali'),
			);
			$this->data['alamat_wali'] = array(
				'name'  => 'alamat_wali',
				'id'    => 'alamat_wali',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('alamat_wali'),
			);
			$this->data['telp_wali'] = array(
				'name'  => 'telp_wali',
				'id'    => 'telp_wali',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('telp_wali'),
			);
			$this->data['kerja_wali'] = array(
				'name'  => 'kerja_wali',
				'id'    => 'kerja_wali',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('kerja_wali'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'text',
				'class' => "form-control",
				'value' => $this->form_validation->set_value('password'),
			);
			$loggeduser = $this->ion_auth->user()->row();
			$this->data['rombels'] = $this->rombongan_belajar->find_all("semester_id = $ajaran->id AND sekolah_id = $loggeduser->sekolah_id AND guru_id != 0");
			$this->data['semester_id'] = $ajaran->id;
			//Datarombel::find('all', array('conditions' => array('sekolah_id = ? AND guru_id != ?',$loggeduser->sekolah_id,0)));
			$this->template->title('Administrator Panel : Data Siswa')
	        ->set_layout($this->admin_tpl)
	        ->set('page_title', 'Tambah Data Siswa')
	        ->set('form_action', 'admin/data_siswa/tambah')
	        ->build($this->admin_folder.'/siswa/_siswa', $this->data);
		}
	}
	public function edit($id){
		$super_admin = array(1,2);
		if(!$this->ion_auth->in_group($super_admin)){
			$this->session->set_flashdata('error', "Anda tidak memiliki akses");
			redirect('admin/data_siswa/', 'refresh');
		}
		$user = $this->siswa->get($id);
		$loggeduser = $this->ion_auth->user()->row();
		$ajaran = get_ta();
		$anggota = $this->anggota_rombel->find("semester_id = $ajaran->id and siswa_id = $user->id");
		$data_rombel_id = isset($anggota->rombongan_belajar_id) ? $anggota->rombongan_belajar_id : '';
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('no_induk', 'no_induk');
		$this->form_validation->set_rules('nisn', 'NISN');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('agama', 'Agama', 'required');
		$this->form_validation->set_rules('status', 'Status dalam keluarga');
		$this->form_validation->set_rules('anak_ke', 'Anak ke');
		$this->form_validation->set_rules('alamat', 'Alamat Jalan');
		$this->form_validation->set_rules('rt', 'RT');
		$this->form_validation->set_rules('rw', 'RW');
		$this->form_validation->set_rules('desa_kelurahan', 'Desa Kelurahan');
		$this->form_validation->set_rules('kecamatan', 'Kecamatan');
		$this->form_validation->set_rules('kode_pos', 'Kode Pos', 'numeric');
		$this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'numeric');
		$this->form_validation->set_rules('sekolah_asal', 'Sekolah Asal');
		$this->form_validation->set_rules('diterima_kelas', 'Diterima dikelas');
		$this->form_validation->set_rules('diterima', 'Diterima pada tanggal');
		$this->form_validation->set_rules('email', 'Email', 'valid_email');
		$this->form_validation->set_rules('nama_ayah', 'Nama Ayah');
		$this->form_validation->set_rules('kerja_ayah', 'Pekerjaan Ayah');
		$this->form_validation->set_rules('nama_ibu', 'Nama Ibu');
		$this->form_validation->set_rules('kerja_ibu', 'Pekerjaan Ibu');
		$this->form_validation->set_rules('nama_wali', 'Nama Wali');
		$this->form_validation->set_rules('alamat_wali', 'Alamat Wali');
		$this->form_validation->set_rules('telp_wali', 'Nomor Telepon Wali');
		$this->form_validation->set_rules('kerja_wali', 'Pekerjaan Ayah');
		if (isset($_POST) && !empty($_POST)){
			// do we have a valid request?
			if ($this->form_validation->run() === TRUE){
				$data = array(
					'nama' 					=> $this->input->post('nama'),
					'no_induk' 				=> $this->input->post('no_induk'),
					'nisn'    				=> $this->input->post('nisn'),
					'jenis_kelamin'  		=> $this->input->post('jenis_kelamin'),
					'tempat_lahir'      	=> $this->input->post('tempat_lahir'),
					'tanggal_lahir'      	=> date('Y-m-d', strtotime($this->input->post('tanggal_lahir'))),
					'agama'		    		=> $this->input->post('agama'),
					'status'		      	=> $this->input->post('status'),
					'anak_ke'		      	=> $this->input->post('anak_ke'),
					'alamat'		      	=> $this->input->post('alamat'),
					'rt'      				=> $this->input->post('rt'),
					'rw'      				=> $this->input->post('rw'),
					'desa_kelurahan'      	=> $this->input->post('desa_kelurahan'),
					'kecamatan'				=> $this->input->post('kecamatan'),
					'kode_pos'				=> $this->input->post('kode_pos'),
					'no_telp'				=> $this->input->post('no_telp'),
					'sekolah_asal' 			=> $this->input->post('sekolah_asal'),
					'diterima_kelas'		=> $this->input->post('diterima_kelas'),
					'diterima'     			=> date('Y-m-d', strtotime($this->input->post('diterima'))),
					'email'      			=> $this->input->post('email'),
					'nama_ayah'      		=> $this->input->post('nama_ayah'),
					'kerja_ayah'      		=> $this->input->post('kerja_ayah'),
					'nama_ibu'      		=> $this->input->post('nama_ibu'),
					'kerja_ibu'      		=> $this->input->post('kerja_ibu'),
					'nama_wali'      		=> $this->input->post('nama_wali'),
					'alamat_wali'      		=> $this->input->post('alamat_wali'),
					'telp_wali'      		=> $this->input->post('telp_wali'),
					'kerja_wali'      		=> $this->input->post('kerja_wali'),
					'password'      		=> $this->input->post('password')
				);
				$data_user = array(
					'username'				=> $this->input->post('nama'),
					'nisn'    				=> $this->input->post('nisn'),
					'nipd'	 				=> $this->input->post('nipd'),
					'email'      			=> $this->input->post('email')
				);
				$data_anggota = array(
					'rombongan_belajar_id'		=> $this->input->post('rombel_id'),
				);
				if ($this->input->post('password')){
					//$data_user['password'] = $this->input->post('password');
				}
				//Save the photo if any
				if(!empty($_FILES['siswaphoto']['name'])){
					$upload_response = $this->upload_photo('siswaphoto');
					if($upload_response['success']){
						if(is_file(PROFILEPHOTOS.$user->photo))						{
							unlink(PROFILEPHOTOS.$user->photo);
							unlink(PROFILEPHOTOSTHUMBS.$user->photo);
						}
						$data['photo']  = $upload_response['upload_data']['file_name'];
						$data_user['photo']  = $upload_response['upload_data']['file_name'];
					}
					else{
						$this->session->set_flashdata('error', $upload_response['msg']);
					}
				}
				//$this->ion_auth->update($user->id, $data);
				$this->ion_auth->update($user->user_id, $data_user);
				$this->siswa->update($id, $data);
				if($anggota){
					$this->anggota_rombel->update($anggota->id, $data_anggota);
				}
				$this->session->set_flashdata('success', "Siswa berhasil di edit");
				redirect('admin/data_siswa/edit/'.$user->id, 'refresh');
			}
		}
		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		//pass the user to the view
		$this->data['user'] = $user;
		$this->data['nama'] = array(
			'name'  => 'nama',
			'id'    => 'nama',
			'type'  => 'text',
			'class' => "form-control required",
			'value' => $this->form_validation->set_value('nama', $user->nama),
		);
		$this->data['jenis_kelamin'] = $user->jenis_kelamin;
		$this->data['nisn'] = array(
			'name'  => 'nisn',
			'id'    => 'nisn',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nisn', $user->nisn),
		);
		$this->data['no_induk'] = array(
			'name'  => 'no_induk',
			'id'    => 'no_induk',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('no_induk', $user->no_induk),
		);
		$this->data['tempat_lahir'] = array(
			'name'  => 'tempat_lahir',
			'id'    => 'tempat_lahir',
			'type'  => 'text',
			'class' => "form-control required",
			'value' => $this->form_validation->set_value('tempat_lahir', $user->tempat_lahir),
		);
		$date = date_create($user->tanggal_lahir);
		$this->data['tanggal_lahir'] = array(
			'name'  => 'tanggal_lahir',
			'id'    => 'tanggal_lahir',
			'type'  => 'text',
			'class' => "form-control required datepicker",
			'data-date-format' => "dd-mm-yyyy",
			'value' => $this->form_validation->set_value('tanggal_lahir', date_format($date,'d-m-Y')),
		);
		$this->data['agama_id'] = $user->agama;
		$this->data['status_id'] = $user->status;
		$this->data['anak_ke'] = array(
			'name'  => 'anak_ke',
			'id'    => 'anak_ke',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('anak_ke', $user->anak_ke),
		);
		$this->data['alamat'] = array(
			'name'  => 'alamat',
			'id'    => 'alamat',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('alamat', $user->alamat),
		);
		$this->data['rt'] = array(
			'name'  => 'rt',
			'id'    => 'rt',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('rt', $user->rt),
		);
		$this->data['rw'] = array(
			'name'  => 'rw',
			'id'    => 'rw',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('rw', $user->rw),
		);
		$this->data['desa_kelurahan'] = array(
			'name'  => 'desa_kelurahan',
			'id'    => 'desa_kelurahan',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('desa_kelurahan', $user->desa_kelurahan),
		);		
		$this->data['kecamatan'] = array(
			'name'  => 'kecamatan',
			'id'    => 'kecamatan',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('kecamatan', $user->kecamatan),
		);		
		$this->data['kode_pos'] = array(
			'name'  => 'kode_pos',
			'id'    => 'kode_pos',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('kode_pos', $user->kode_pos),
		);		
		$this->data['no_telp'] = array(
			'name'  => 'no_telp',
			'id'    => 'no_telp',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('no_telp', $user->no_telp),
		);
		$this->data['sekolah_asal'] = array(
			'name'  => 'sekolah_asal',
			'id'    => 'sekolah_asal',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('sekolah_asal', $user->sekolah_asal),
		);
		$this->data['diterima_kelas'] = array(
			'name'  => 'diterima_kelas',
			'id'    => 'diterima_kelas',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('diterima_kelas', $user->diterima_kelas),
		);
		$diterima = date_create($user->diterima);
		$this->data['diterima'] = array(
			'name'  => 'diterima',
			'id'    => 'diterima',
			'type'  => 'text',
			'class' => "form-control datepicker",
			'data-date-format' => "dd-mm-yyyy",
			'value' => $this->form_validation->set_value('diterima', date_format($diterima,'d-m-Y')),
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('email', $user->email),
		);		
		$this->data['nama_ayah'] = array(
			'name'  => 'nama_ayah',
			'id'    => 'nama_ayah',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nama_ayah', $user->nama_ayah),
		);		
		$this->data['kerja_ayah'] = array(
			'name'  => 'kerja_ayah',
			'id'    => 'kerja_ayah',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('kerja_ayah', $user->kerja_ayah),
		);		
		$this->data['nama_ibu'] = array(
			'name'  => 'nama_ibu',
			'id'    => 'nama_ibu',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nama_ibu', $user->nama_ibu),
		);		
		$this->data['kerja_ibu'] = array(
			'name'  => 'kerja_ibu',
			'id'    => 'kerja_ibu',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('kerja_ibu', $user->kerja_ibu),
		);	
		$this->data['nama_wali'] = array(
			'name'  => 'nama_wali',
			'id'    => 'nama_wali',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nama_wali', $user->nama_wali),
		);
		$this->data['alamat_wali'] = array(
			'name'  => 'alamat_wali',
			'id'    => 'alamat_wali',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('alamat_wali', $user->alamat_wali),
		);
		$this->data['telp_wali'] = array(
			'name'  => 'telp_wali',
			'id'    => 'telp_wali',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('telp_wali', $user->telp_wali),
		);
		$this->data['kerja_wali'] = array(
			'name'  => 'kerja_wali',
			'id'    => 'kerja_wali',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('kerja_wali', $user->kerja_wali),
		);
		$this->data['password'] = array(
			'name'  => 'password',
			'id'    => 'password',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('password', $user->password),
		);
		$this->data['sekolah_id'] = $user->sekolah_id;
		$this->data['rombel'] = $data_rombel_id;
		$this->data['semester_id'] = $ajaran->id;
		$this->data['rombels'] = $this->rombongan_belajar->find_all("sekolah_id = $loggeduser->sekolah_id");
		//Datarombel::find('all', array('conditions' => array('sekolah_id = ?',$loggeduser->sekolah_id)));
		$this->template->title('Administrator Panel : Edit Siswa')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Edit Siswa')
		->build($this->admin_folder.'/siswa/_siswa', $this->data);
		//->build($this->admin_folder.'/demo');
	}
	public function view($id){
		$siswa = $this->siswa->get($id);
		$this->template->title('Administrator Panel : Detil Siswa')
        ->set_layout($this->modal_tpl)
        ->set('page_title', 'Detil Siswa')
        ->set('siswa', $siswa)
		->set('modal_footer', '')		
        ->build($this->admin_folder.'/siswa/view');
	}
	public function delete($id){
		$super_admin = array(1,2);
		if($this->ion_auth->in_group($super_admin)){
			$find_anggota_rombel = $this->anggota_rombel->find_all("siswa_id = $id");
			if($find_anggota_rombel){
				$html = '';
				$html .= 'Mohon hapus terlebih dahulu siswa ini dari anggota rombel di rombongan belajar berikut';
				$html .= '<table class="table table-bordered table-striped table-hover">';
            	$html .= '<thead>';
            	$html .= '<tr>';
				$html .= '<th class="text-center">Tahun Ajaran</th>';
				$html .= '<th class="text-center">Rombongan Belajar</th>';
                $html .= '</tr>';
            	$html .= '</thead>';
				$html .= '<tbody>';
				foreach($find_anggota_rombel as $anggota_rombel){
					$tahun_ajaran = $this->semester->get($anggota_rombel->semester_id);
					$html .= '<tr>';
					$html .= '<td>'.$tahun_ajaran->tahun.' SMT'.$tahun_ajaran->semester.'</td>';
					$html .= '<td>'.get_nama_rombel($anggota_rombel->rombongan_belajar_id).'</td>';
					$html .= '</tr>';

				}
				$html .= '</tbody>';
				$html .= '</table>';
				$status['type'] = 'error';
				$status['text'] = $html;
				$status['title'] = 'Data turunan peserta didik!';
			} else {
				$data = $this->siswa->get($id);
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
				$this->siswa->delete($id);
				$status['type'] = 'success';
				$status['text'] = 'Data Siswa berhasil dihapus';
				$status['title'] = 'Data Terhapus!';
			}
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data Siswa tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
	public function multidelete(){
		$ids = $_POST['id'];
		$super_admin = array(1,2);
		if($this->ion_auth->in_group($super_admin)){
			foreach($ids as $id){
				$find_anggota_rombel = $this->anggota_rombel->find_all("siswa_id = $id");
				if($find_anggota_rombel){
					$html = '';
					$html .= 'Mohon hapus terlebih dahulu siswa ini dari anggota rombel di rombongan belajar berikut';
					$html .= '<table class="table table-bordered table-striped table-hover">';
					$html .= '<thead>';
					$html .= '<tr>';
					$html .= '<th class="text-center">Tahun Ajaran</th>';
					$html .= '<th class="text-center">Rombongan Belajar</th>';
					$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
					foreach($find_anggota_rombel as $anggota_rombel){
						$tahun_ajaran = $this->semester->get($anggota_rombel->semester_id);
						$html .= '<tr>';
						$html .= '<td>'.$tahun_ajaran->tahun.' SMT'.$tahun_ajaran->semester.'</td>';
						$html .= '<td>'.get_nama_rombel($anggota_rombel->rombongan_belajar_id).'</td>';
						$html .= '</tr>';
	
					}
					$html .= '</tbody>';
					$html .= '</table>';
					$status['type'] = 'error';
					$status['text'] = $html;
					$status['title'] = 'Data turunan peserta didik!';
				} else {
					$data = $this->siswa->get($id);
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
					$this->siswa->delete($id);
					$status['type'] = 'success';
					$status['text'] = 'Data Siswa berhasil dihapus';
					$status['title'] = 'Data Terhapus!';
				}
			}
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data Siswa tidak terhapus';
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
		if ($this->form_validation->run() == FALSE){
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
	        ->build('auth/deactivate_siswa', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
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
			redirect('admin/data_siswa');
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
			redirect("admin/data_siswa");
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('error', $this->ion_auth->errors());
			redirect("admin/data_siswa");
		}
	}
    public function simpan(){
		$nama			= strtoupper(addslashes($this->input->post('nama_pd', TRUE)));
		$nisn	 		= $this->input->post('nisn', TRUE);
		$nik	 		= $this->input->post('nik', TRUE);
		$kelamin 		= $this->input->post('kelamin', TRUE);
		$idSekolah 		= $this->input->post('idSekolah', TRUE);
		$petugas		= $this->input->post('petugas', TRUE);
		$password_asli	= GeneratePassword();
		$password_acak	= sha1($password_asli);
		$SiswaID		= $this->input->post('pd_id', TRUE);
		if($nisn){
			$query_cek_pendaftaran = $this->siswa_model->get_siswa_by_nisn($nisn);
		} else {
		$query_cek_pendaftaran = $this->siswa_model->get_siswa_by_nisn_2();
		}
		if($query_cek_pendaftaran->num_rows()>0){
			$status['status'] = 0;
			$status['error'] = 'Siswa dengan NISN '.$nisn.' sudah ada di database!';
			$status['id']=$SiswaID;
		}else{
			$priveledge = 'operator';
			$statuss	= '1';
			$this->siswa_model->save_siswa($nama,$nisn,$nik,$password_asli,$password_acak,$kelamin,$idSekolah,$priveledge,$statuss,$petugas);
			$status['status'] = 1;
			$status['error'] = '';
			$status['nama']=$nama;
			$status['nisn']=$nisn;
			$status['kelamin']=$kelamin;
			$status['nik']=$nik;
			$status['id']=$SiswaID;
		}
        echo json_encode($status);
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
	public function rombel($id){
		$loggeduser = $this->ion_auth->user()->row();
		$rombels 	= Datarombel::find('all', array('conditions' => array('sekolah_id = ?',$loggeduser->sekolah_id)));
		$this->template->title('Administrator Panel : Detil Rombongan Belajar')
	        ->set_layout($this->modal_tpl)
	        ->set('page_title', 'Nama Rombongan Belajar')
	        ->set('id_mapel', $id)
	        ->set('rombels', $rombels)
			->set('modal_s', 'modal_s')
			->set('modal_footer', '<a href="javascript:void(0)" class="btn btn-success btn-sm pilih_guru"><i class="fa fa-plus-circle"></i> Pilih</a>')			
	        ->build($this->admin_folder.'/siswa/rombel');
	}
	public function setrombel($id){
		/*if(isset($_POST['id'])){
			$rombel_id = $_POST['id'];
			$updatedata = array('data_rombel_id'=>$rombel_id);
			$this->db->where('id', $id);
			$this->db->update('data_siswas', $updatedata); 
			echo 'sukses';
		} else {
			echo 'gagal';
		}*/
	}
    public function list_data_siswa($kompetensi = NULL, $tingkat = NULL, $rombel = NULL){
		$loggeduser = $this->ion_auth->user()->row();
		$nama_group = get_jabatan($loggeduser->id);
		$find_akses = get_akses($loggeduser->id);
		$search = "";
		$start = 0;
		$rows = 10;
		$ajaran = get_ta();
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		$joint = joint($kompetensi, $tingkat, $rombel);
		$where = '';
		if(in_array('guru',$find_akses['name']) && !in_array('waka',$nama_group)){
			$where = "AND rombongan_belajar_id IN(SELECT id FROM rombongan_belajar WHERE id IN(SELECT rombongan_belajar_id FROM pembelajaran WHERE guru_id = $loggeduser->guru_id))";
		}
		$search_form = "siswa_id IN(SELECT id FROM ref_siswa WHERE nama LIKE '%$search%') OR siswa_id IN(SELECT id FROM ref_siswa WHERE nisn LIKE '%$search%')";
		$query = $this->anggota_rombel->with('siswa')->find_all("semester_id = $ajaran->id $where $joint AND siswa_id IN(SELECT id FROM ref_siswa WHERE sekolah_id = $loggeduser->sekolah_id) AND ($search_form)", '*','id desc', $start, $rows);
		$filter = $this->anggota_rombel->with('siswa')->find_all("semester_id = $ajaran->id $where $joint AND siswa_id IN(SELECT id FROM ref_siswa WHERE sekolah_id = $loggeduser->sekolah_id) AND ($search_form)", '*','id desc');

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
			if($temp->siswa->jenis_kelamin == 'L'){
				if($temp->siswa->photo)
					$foto = base_url().PROFILEPHOTOSTHUMBS.$temp->siswa->photo;
				else
					$foto= base_url().'assets/img/no_avatar.jpg';
			} else {
				if($temp->siswa->photo)
					$foto= base_url().PROFILEPHOTOSTHUMBS.$temp->siswa->photo;
				else
					$foto= base_url().'assets/img/no_avatar_f.jpg';
			}
			if($temp->siswa->nisn){
				$nisn = $temp->siswa->nisn;
			} else {
				$nisn = '-';
			}
			$admin_akses = '<div class="btn-group">';
			$admin_akses .= '<button type="button" class="btn btn-default btn-sm">Aksi</button>';
			$admin_akses .= '<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">';
			$admin_akses .= '<span class="caret"></span>';
			$admin_akses .= '<span class="sr-only">Toggle Dropdown</span>';
			$admin_akses .= '</button>';
			$admin_akses .= '<ul class="dropdown-menu pull-right text-left" role="menu">';
			$admin_akses .= '<li><a href="'.site_url('admin/data_siswa/view/'.$temp->siswa->id).'" class="toggle-modal"><i class="fa fa-eye"></i>Detil</a></li>';
			$admin_akses .= '<li><a href="'.site_url('admin/data_siswa/edit/'.$temp->siswa->id).'"><i class="fa fa-pencil"></i>Edit</a></li>';
			$admin_akses .= '<li><a href="'.site_url('admin/data_siswa/delete/'.$temp->siswa->id).'" class="confirm"><i class="fa fa-power-off"></i>Hapus</a></li>';
			$admin_akses .= '</ul>';
			$admin_akses .= '</div>';
			if($loggeduser->guru_id){
				$admin_akses = '<a href="'.site_url('admin/data_siswa/view/'.$temp->siswa->id).'" class="btn btn-success btn-sm toggle-modal"><i class="fa fa-eye"></i> Detil</a>';
			}
			$record = array();
            $tombol_aktif = '';
			if($this->ion_auth->in_group($admin_group)){
				$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->siswa->id.'" /></div>';
			}
			$record[] = '<img src="'.$foto.'" width="50" style="float:left; margin-right:10px;" /> '.$temp->siswa->nama.'<br />
'.$nisn;
			$date = date_create($temp->siswa->tanggal_lahir);
			$record[] = '<div class="text-center">'.$temp->siswa->jenis_kelamin.'</div>';
			$record[] = $temp->siswa->tempat_lahir.', '.TanggalIndo(date_format($date,'Y-m-d'));
			$record[] = '<div class="text-center">'.get_agama($temp->siswa->agama).'</div>';
			$record[] = '<div class="text-center">'.get_rombel_siswa($ajaran->id, $temp->siswa->id).'</div>';
			$record[] = '<div class="text-center">'.$admin_akses.'</div>';
			$output['aaData'][] = $record;
		}
		$filter_table = filter_table($kompetensi, $tingkat, $find_akses['name'], $nama_group);
		if($filter_table){
			$output = array_merge($filter_table,$output);
		} else {
			$all_rombel['value'] = '';
			$all_rombel['text'] = 'No record';
			$output['rombel'][] = $all_rombel;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
}