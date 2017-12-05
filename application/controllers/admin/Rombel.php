<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rombel extends Backend_Controller {
	protected $activemenu = 'referensi';
	public function __construct() {
		parent::__construct();
		//$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->template->set('activemenu', $this->activemenu);
	}
	//<a href="http://localhost/eraporsmk/admin/rombel/lanjutkan/16" class="btn btn-info btn-sm toggle-modal"><i class="fa fa-level-up"></i> Lanjutkan Semester</a>
	public function index(){
		$loggeduser = $this->ion_auth->user()->row();
		$semester = get_ta();
		$admin_group = array(1,2,3,5,6);
		hak_akses($admin_group);
		$pilih_rombel = '<a href="'.site_url('admin/rombel/tambah_rombel').'" class="btn btn-success" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Data Rombongan Belajar</a>';
		if($semester->semester == 1){
			$pilih_rombel .= '<a href="'.site_url('admin/rombel/naik_kelas').'" class="btn btn-info toggle-swal_select" style="float:right; margin-right:10px;"><i class="fa fa-level-up"></i> Naik Kelas</a>';
		} else {
			$pilih_rombel .= '<a href="'.site_url('admin/rombel/lanjutkan').'" class="btn btn-info toggle-swal_select" style="float:right; margin-right:10px;"><i class="fa fa-level-up"></i> Lanjutkan Semester</a>';
		}
		$this->template->title('Administrator Panel : Data Rombongan Belajar')
        ->set_layout($this->admin_tpl)
        ->set('pilih_rombel', $pilih_rombel)
		->set('semester', $semester)
		->set('sekolah_id', $loggeduser->sekolah_id)
        ->set('page_title', 'Referensi Rombongan Belajar')
        ->build($this->admin_folder.'/rombel/list');
	}
	function lanjutkan(){
		$loggeduser = $this->ion_auth->user()->row();
		$semester = get_ta();
		$get_prev_ta = $this->semester->find("tahun = '$semester->tahun' AND semester = 1");
		$semester_id = ($get_prev_ta) ? $get_prev_ta->id : 0;
		$all_rombel = $this->rombongan_belajar->find_all("semester_id = $semester_id");
		if($_POST){
			$rombongan_belajar_id = $this->input->post('rombongan_belajar_id');
			$get_rombel = $this->rombongan_belajar->get($rombongan_belajar_id);
			$insert_rombel = array(
				'sekolah_id'	=> $get_rombel->sekolah_id,
				'guru_id'		=> $get_rombel->guru_id,
				'tingkat'		=> $get_rombel->tingkat,
				'kurikulum_id'	=> $get_rombel->kurikulum_id,
				'nama'			=> $get_rombel->nama,
				'petugas'		=> $loggeduser->username,
				'semester_id'	=> $semester->id,
				'jurusan_sp_id'	=> $get_rombel->jurusan_sp_id,
			);
			$id_insert_rombel = $this->rombongan_belajar->insert($insert_rombel);
			if($id_insert_rombel){
				$anggota_rombel = $this->anggota_rombel->find_all("rombongan_belajar_id = $rombongan_belajar_id");
				if($anggota_rombel){
					foreach($anggota_rombel as $anggota){
						$insert_anggota = array(
							'semester_id' 			=> $semester->id,
							'rombongan_belajar_id' 	=> $id_insert_rombel,
							'siswa_id'				=> $anggota->siswa_id,
						);
						$this->anggota_rombel->insert($insert_anggota);
					}
				}
				$output['type'] = 'success';
				$output['text'] = 'Lanjutkan semester berhasil';
				$output['title'] = 'Data Tersimpan!';
			} else {
				$output['type'] = 'error';
				$output['text'] = 'Lanjutkan semester gagal. Silahkan coba lagi';
				$output['title'] = 'Gagal!';
			}
		} elseif($all_rombel){
			foreach($all_rombel as $rombel){
				$find_rombel_sebelumnya = $this->rombongan_belajar->find("semester_id = $semester->id AND tingkat = $rombel->tingkat AND kurikulum_id = $rombel->kurikulum_id AND jurusan_sp_id = $rombel->jurusan_sp_id");
				$tingkat = ($find_rombel_sebelumnya) ? ($find_rombel_sebelumnya->tingkat + 1) : 0;
				$kurikulum_id = ($find_rombel_sebelumnya) ? $find_rombel_sebelumnya->kurikulum_id : 0;
				$jurusan_sp_id = ($find_rombel_sebelumnya) ? $find_rombel_sebelumnya->jurusan_sp_id : 0;
				if($rombel->tingkat != $tingkat && $rombel->kurikulum_id != $kurikulum_id && $rombel->jurusan_sp_id != $jurusan_sp_id){
				//if(!$find_rombel_sebelumnya){
					$output_rombel[$rombel->id][] 	= $rombel->nama;
				}
			}
			if(isset($output_rombel)){
				$output = $output_rombel;
			} else {
				$output[''] = 'Rombongan belajar tidak ditemukan di semester lalu';
			}
		} else {
			$output[''] = 'Rombongan belajar tidak ditemukan di semester lalu';
		}
		echo json_encode($output);
	}
	function naik_kelas(){
		$loggeduser = $this->ion_auth->user()->row();
		$semester = get_ta();
		$tahun = explode('/',$semester->tahun);
		$tapel_1 = $tahun[0] - 1;
		$tapel_2 = $tahun[1] - 1;
		$tahun_ajaran = $tapel_1.'/'.$tapel_2;
		$get_prev_ta = $this->semester->find("tahun = '$tahun_ajaran' AND semester = 2");
		$semester_id = ($get_prev_ta) ? $get_prev_ta->id : 0;
		$all_rombel = $this->rombongan_belajar->find_all("semester_id = $semester_id");
		if($_POST){
			$rombongan_belajar_id = $this->input->post('rombongan_belajar_id');
			$get_rombel = $this->rombongan_belajar->get($rombongan_belajar_id);
			$insert_rombel = array(
				'sekolah_id'	=> $get_rombel->sekolah_id,
				'guru_id'		=> $get_rombel->guru_id,
				'tingkat'		=> $get_rombel->tingkat + 1,
				'kurikulum_id'	=> $get_rombel->kurikulum_id,
				'nama'			=> $get_rombel->nama,
				'petugas'		=> $loggeduser->username,
				'semester_id'	=> $semester->id,
				'jurusan_sp_id'	=> $get_rombel->jurusan_sp_id,
			);
			$id_insert_rombel = $this->rombongan_belajar->insert($insert_rombel);
			if($id_insert_rombel){
				$anggota_rombel = $this->anggota_rombel->find_all("rombongan_belajar_id = $rombongan_belajar_id");
				if($anggota_rombel){
					foreach($anggota_rombel as $anggota){
						$insert_anggota = array(
							'semester_id' 			=> $semester->id,
							'rombongan_belajar_id' 	=> $id_insert_rombel,
							'siswa_id'				=> $anggota->siswa_id,
						);
						$this->anggota_rombel->insert($insert_anggota);
					}
				}
				$output['type'] = 'success';
				$output['text'] = 'Naik kelas berhasil';
				$output['title'] = 'Data Tersimpan!';
			} else {
				$output['type'] = 'error';
				$output['text'] = 'Naik kelas gagal. Silahkan coba lagi';
				$output['title'] = 'Gagal!';
			}
		} elseif($all_rombel){
			foreach($all_rombel as $rombel){
				$next_tingkat = $rombel->tingkat + 1;
				$find_rombel_sebelumnya = $this->rombongan_belajar->find("semester_id = $semester->id AND tingkat = $next_tingkat AND kurikulum_id = $rombel->kurikulum_id AND jurusan_sp_id = $rombel->jurusan_sp_id");
				$tingkat = ($find_rombel_sebelumnya) ? ($find_rombel_sebelumnya->tingkat + 1) : 0;
				$kurikulum_id = ($find_rombel_sebelumnya) ? $find_rombel_sebelumnya->kurikulum_id : 0;
				$jurusan_sp_id = ($find_rombel_sebelumnya) ? $find_rombel_sebelumnya->jurusan_sp_id : 0;
				if($rombel->tingkat != $tingkat && $rombel->kurikulum_id != $kurikulum_id && $rombel->jurusan_sp_id != $jurusan_sp_id){
				//if(!$find_rombel_sebelumnya){
					$output_rombel[$rombel->id][] 	= $rombel->nama;
				}
			}
			if(isset($output_rombel)){
				$output = $output_rombel;
			} else {
				$output[''] = 'Rombongan belajar tidak ditemukan di semester lalu';
			}
		} else {
			$output[''] = 'Rombongan belajar tidak ditemukan di semester lalu';
		}
		echo json_encode($output);
	}
	public function wali($id){
		$ajaran = get_ta();
		if(isset($_POST['id'])){
			$guru_id = $_POST['id'];
			$find = $this->rombongan_belajar->find("semester_id = $ajaran->id AND guru_id = $guru_id");
			//Datarombel::find_by_guru_id($guru_id);
			if($find){
				$status['type'] = 'error';
				$status['text'] = 'Wali kelas terpilih terdeteksi telah menjabat wali kelas di rombel lain';
				$status['title'] = 'Gagal!';
			} else {
				$updatedata = array('guru_id'=>$guru_id);
				$this->rombongan_belajar->update($id, $updatedata); 
				$status['type'] = 'success';
				$status['text'] = 'Wali kelas berhasil di perbaharui';
				$status['title'] = 'Data Tersimpan!';
			}
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Permintaan tidak bisa di proses. Silahkan ulangi lagi.';
			$status['title'] = 'Error';
		}
		echo json_encode($status);
	}
	public function tambah_rombel(){
		$semester = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$sekolah_id = $loggeduser->sekolah_id;
		$this->form_validation->set_rules('sekolah_id', 'Sekolah ID', 'required');
		$this->form_validation->set_rules('nama', 'Nama Rombel', 'required');
		$this->form_validation->set_rules('jurusan_sp_id', 'Bidang Keahlian', 'required');
		$this->form_validation->set_rules('kurikulum_id', 'Kurikulum', 'required');
		$this->form_validation->set_rules('guru_id', 'Wali Kelas', 'required');
		$this->form_validation->set_rules('tingkat', 'Tingkat Pendidikan', 'required');
		$this->form_validation->set_rules('petugas', 'Petugas', 'required');
		if ($this->form_validation->run() == true){			
			$data = array(
				'sekolah_id'	=> $this->input->post('sekolah_id',TRUE),
				'nama'			=> $this->input->post('nama',TRUE),
				'jurusan_sp_id'	=> $this->input->post('jurusan_sp_id',TRUE),
				'kurikulum_id'	=> $this->input->post('kurikulum_id',TRUE),
				'guru_id'    	=> $this->input->post('guru_id',TRUE),
				'tingkat'      	=> $this->input->post('tingkat',TRUE),
				'petugas'  		=> $this->input->post('petugas',TRUE),
				'semester_id'  	=> $semester->id,
			);
			$find = $this->rombongan_belajar->find_by_guru_id($data['guru_id']);
			if($find){
				$this->session->set_flashdata('error', 'Wali kelas terpilih terdeteksi telah menjabat wali kelas di rombel lain');
				redirect("admin/rombel/tambah_rombel", 'refresh');
			} else {
				$find_exist = $this->rombongan_belajar->find_all("tingkat = '$data[tingkat]' AND nama = '$data[nama]' AND semester_id = $semester->id");
				if($find_exist){
					$this->session->set_flashdata('error', 'Gagal menambah data rombongan belajar dengan data existing');
					redirect("admin/rombel/tambah_rombel", 'refresh');
				} else {
					$insert_data = $this->rombongan_belajar->insert($data);
					if($insert_data){
						//check to see if we are creating the user
						//redirect them back to the admin page
						$this->session->set_flashdata('success', 'Data Rombongan Belajaran Berhasil ditambah');
						redirect("admin/rombel");
					} else {
						$message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
						$this->session->set_flashdata('message', $message);
						redirect("admin/rombel/tambah_rombel", 'refresh');
					}
				}
			}
		} else {
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
			$this->data['tingkat'] = $this->form_validation->set_value('tingkat');
			$this->data['nama'] = array(
				'name'  => 'nama',
				'id'    => 'nama',
				'type'  => 'text',
				'class' => "form-control required",
				'value' => $this->form_validation->set_value('nama'),
			);
			$this->data['guru_id'] = $this->form_validation->set_value('guru_id');
			$this->data['jurusan_sp_id'] = $this->form_validation->set_value('jurusan_sp_id');
			$this->data['kurikulum_id'] = $this->form_validation->set_value('kurikulum_id');
			$this->data['gurus'] 	= $this->guru->find_all("sekolah_id = $loggeduser->sekolah_id AND id NOT IN(SELECT guru_id FROM rombongan_belajar WHERE sekolah_id = $loggeduser->sekolah_id AND semester_id = $semester->id)", '*','nama ASC');
			$this->data['jurusan'] = $this->jurusan_sp->get_all();
			$this->data['kurikulum'] = '';
			$this->template->title('Administrator Panel : Tambah Rombel')
	        ->set_layout($this->admin_tpl)
	        ->set('page_title', 'Data Rombel')
	        ->set('form_action', 'admin/rombel/tambah_rombel')
	        ->build($this->admin_folder.'/rombel/_rombel', $this->data);
			//->build($this->admin_folder.'/demo');
		}
	}
	public function edit($id){
		//test($_POST);
		//die();
		$rombel = $this->rombongan_belajar->get($id);
		$ajaran = get_ta();
		//validate form input
		$this->form_validation->set_rules('sekolah_id', 'Sekolah ID', 'required');
		$this->form_validation->set_rules('nama', 'Nama Rombel', 'required');
		$this->form_validation->set_rules('jurusan_sp_id', 'Bidang Keahlian', 'required');
		$this->form_validation->set_rules('kurikulum_id', 'Kurikulum', 'required');
		$this->form_validation->set_rules('guru_id', 'Wali Kelas', 'required');
		$this->form_validation->set_rules('tingkat', 'Tingkat Pendidikan', 'required');
		$this->form_validation->set_rules('petugas', 'Petugas', 'required');
		if ($this->form_validation->run() == true){		
			$data = array(
				'sekolah_id'	=> $this->input->post('sekolah_id',TRUE),
				'nama'			=> $this->input->post('nama',TRUE),
				'jurusan_sp_id'	=> $this->input->post('jurusan_sp_id',TRUE),
				'kurikulum_id'	=> $this->input->post('kurikulum_id',TRUE),
				'guru_id'    	=> $this->input->post('guru_id',TRUE),
				'tingkat'      	=> $this->input->post('tingkat',TRUE),
				'petugas'  		=> $this->input->post('petugas',TRUE),
			);
			$find = $this->rombongan_belajar->find("guru_id = $data[guru_id] AND semester_id = $ajaran->id");
			if($find){
				if($find->id == $id){
					$this->rombongan_belajar->update($id, $data);
					$this->session->set_flashdata('success', "Rombongan Belajar berhasil di edit");
					redirect('admin/rombel');
				} else {
					$this->session->set_flashdata('error', "Wali kelas terpilih terdeteksi telah menjabat wali kelas di rombel lain");
					redirect('admin/rombel/edit/'.$id);
				}
			} else {
				$this->rombongan_belajar->update($id, $data);
				$this->session->set_flashdata('success', "Rombongan Belajar berhasil di edit");
				redirect('admin/rombel');
			}
		}
		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
		//pass the user to the view
		$loggeduser = $this->ion_auth->user()->row();
		$semester = get_ta();
		$this->data['rombel'] = $rombel;
		$this->data['guru_id'] = $rombel->guru_id;
		$this->data['tingkat'] = $rombel->tingkat;
		$this->data['kurikulum_id'] = $rombel->kurikulum_id;
		$this->data['jurusan_sp_id'] = $rombel->jurusan_sp_id;
		$this->data['gurus'] 	= $this->guru->find_all("sekolah_id = $loggeduser->sekolah_id", '*','nama ASC');
		$this->data['jurusan'] = $this->jurusan_sp->get_all();
		$this->data['kurikulum'] = $this->kurikulum->find_all_by_jurusan_id($rombel->jurusan_sp_id);
		//$this->bidang_keahlian->get_all();
		//$this->data['program_keahlian'][$rombel->program_keahlian_id] = get_program_keahlian($rombel->program_keahlian_id);
		//$this->data['kompetensi_keahlian'][$rombel->kompetensi_keahlian_id] = get_kompetensi_keahlian($rombel->kompetensi_keahlian_id);
		$this->data['nama'] = array(
			'name'  => 'nama',
			'id'    => 'nama',
			'type'  => 'text',
			'class' => "form-control",
			'value' => $this->form_validation->set_value('nama', $rombel->nama),
		);
		$this->template->title('Administrator Panel : Edit Rombongan Belajar')
		->set_layout($this->admin_tpl)
		->set('page_title', 'Edit Rombongan Belajar')
		->build($this->admin_folder.'/rombel/_rombel', $this->data);
		//->build($this->admin_folder.'/demo');

	}

	public function view($id){
		$rombel = Datarombel::find($id);
		$this->template->title('Administrator Panel : Detil Guru')
	        ->set_layout($this->modal_tpl)
	        ->set('page_title', 'Detil Guru')
	        ->set('rombel', $rombel)
			->set('modal_footer', '')			
	        ->build($this->admin_folder.'/rombel/view');
	}
	public function multidelete(){
		$ids = $_POST['id'];
		$super_admin = array(1,2);
		if($this->ion_auth->in_group($super_admin)){
			foreach($ids as $id){
				$this->rombongan_belajar->delete($id);
				$this->db->where('rombongan_belajar_id', $id);
				$this->db->delete('anggota_rombel');
			}
			$status['type'] = 'success';
			$status['text'] = 'Data Rombel berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data Rombel tidak terhapus';
			$status['title'] = 'Akses Ditolak!';
		}
		echo json_encode($status);
	}
	public function delete($id){
		$super_admin = array(1,2);
		if($this->ion_auth->in_group($super_admin)){
			$data = Datarombel::find($id);
			$data->delete();
			$status['type'] = 'success';
			$status['text'] = 'Data Siswa berhasil dihapus';
			$status['title'] = 'Data Terhapus!';
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
	public function kenaikan($id_rombel){
		$ajaran = get_ta();
		$next_ta = get_next_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$get_rombel = $this->rombongan_belajar->get($id_rombel);
		$tingkat_plus_1 = $get_rombel->tingkat + 1;
		//Datarombel::find($id_rombel);
		//$anggota = Datasiswa::find('all', array('conditions' => array('data_rombel_id =?', $id_rombel)));
		//$anggota 	= Anggotarombel::find('all', array('conditions' => array('rombel_id =?', $id_rombel)));
		$anggota	= get_siswa_by_rombel($id_rombel);
		//$data_rombel = $this->rombongan_belajar->find_all("semester_id = $next_ta AND kurikulum_id = $get_rombel->kurikulum_id AND tingkat = $tingkat_plus_1");
		$data_rombel = $this->rombongan_belajar->find_all("tingkat = $tingkat_plus_1");
		$this->template->title('Administrator Panel : Proses Kenaikan Kelas')
        ->set_layout($this->modal_tpl)
	        ->set('page_title', 'Proses Kenaikan Kelas')
			->set('data_rombel', $data_rombel)
	        ->set('anggota', $anggota)
			->set('ajaran', $ajaran)
			->set('modal_footer', '<div class="text-center"><a href="javascript:void(0)" class="btn btn-success btn-sm proses_kenaikan"><i class="fa fa-plus-circle"></i> Simpan</a></div>')
	        ->build($this->admin_folder.'/rombel/kenaikan');
	}
	public function proses_kenaikan(){
		$id_rombel = $_POST['id_rombel'];
		$rombel = $this->rombongan_belajar->get($id_rombel);
		//Datarombel::find($id_rombel);
		$id_siswa = $_POST['id_siswa'];
		//Anggotarombel::find_all_by_datasiswa_id($id_siswa);
		foreach($id_siswa as $siswa){
			$anggota_rombel = $this->anggota_rombel->insert(
				array(
					'semester_id' => $rombel->semester_id,
					'rombongan_belajar_id' => $rombel->id,
					'siswa_id'				=> $siswa,
					'anggota_rombel_id_dapodik' => '',
				)
			);
		}
		$output = array(
					'title' 	=> 'Sukses',
					'text'		=> 'Proses kenaikan kelas berhasil',
					'type'		=> 'success',
					'status'	=> 1
						);
		echo json_encode($output);
	}
	public function proses_lanjutkan(){
		$loggeduser = $this->ion_auth->user()->row();
		$id_rombel = $_POST['id_rombel'];
		$rombel_aktif = $this->rombongan_belajar->get($id_rombel);
		$id_siswa = $_POST['id_siswa'];
		$semester = get_ta();
		$get_next_smt = $this->semester->find("tahun = '$semester->tahun' and semester = 2");
		//Ajaran::find_by_tahun_and_smt($ajaran->tahun,2);
		if($get_next_smt){
			$find_rombel = $this->rombongan_belajar->find("semester_id = $get_next_smt->id AND sekolah_id = $rombel_aktif->sekolah_id AND jurusan_sp_id = '$rombel_aktif->jurusan_sp_id' AND kurikulum_id = $rombel_aktif->kurikulum_id AND nama = '$rombel_aktif->nama' AND guru_id = $rombel_aktif->guru_id AND tingkat = $rombel_aktif->tingkat");
			if($find_rombel){
				$rombel_id_next_smt = $find_rombel->id;
			} else {
				$rombel_id_next_smt = $this->rombongan_belajar->insert(
					array(
						'semester_id' 		=> $get_next_smt->id,
						'sekolah_id' 		=> $rombel_aktif->sekolah_id,
						'jurusan_sp_id' 	=> $rombel_aktif->jurusan_sp_id,
						'kurikulum_id' 		=> $rombel_aktif->kurikulum_id,
						'nama' 				=> $rombel_aktif->nama,
						'guru_id' 			=> $rombel_aktif->guru_id,
						'tingkat' 			=> $rombel_aktif->tingkat,
						'guru_id_dapodik' 	=> $rombel_aktif->guru_id_dapodik,
						'rombel_id_dapodik'	=> $rombel_aktif->rombel_id_dapodik,
						'petugas' 			=> $loggeduser->username,
					)
				);
			}
			foreach($id_siswa as $s){
				$attributes = array('semester_id' => $get_next_smt->id, 'rombongan_belajar_id' => $rombel_id_next_smt, 'siswa_id' => $s);
				$anggota = $this->anggota_rombel->insert($attributes);
				//Anggotarombel::create($attributes);
			}
		} else {
			$data_semester = array(
				'tahun'				=> $semester->tahun,
				'semester' 			=> 2,
				'created_at' 		=> date('Y-m-d H:i:s'),
				'updated_at' 		=> date('Y-m-d H:i:s')
			);
			$new_ajaran = $this->semester->insert($data_semester);
			$find_rombel = $this->rombongan_belajar->find("semester_id = $new_ajaran AND sekolah_id = $rombel_aktif->sekolah_id AND jurusan_sp_id = '$rombel_aktif->jurusan_sp_id' AND kurikulum_id = $rombel_aktif->kurikulum_id AND nama = '$rombel_aktif->nama' AND guru_id = $rombel_aktif->guru_id AND tingkat = $rombel_aktif->tingkat");
			if($find_rombel){
				$rombel_id_next_smt = $find_rombel->id;
			} else {
				$rombel_id_next_smt = $this->rombongan_belajar->insert(
					array(
						'semester_id' 		=> $new_ajaran,
						'sekolah_id' 		=> $rombel_aktif->sekolah_id,
						'jurusan_sp_id' 	=> $rombel_aktif->jurusan_sp_id,
						'kurikulum_id' 		=> $rombel_aktif->kurikulum_id,
						'nama' 				=> $rombel_aktif->nama,
						'guru_id' 			=> $rombel_aktif->guru_id,
						'tingkat' 			=> $rombel_aktif->tingkat,
						'guru_id_dapodik' 	=> $rombel_aktif->guru_id_dapodik,
						'rombel_id_dapodik'	=> $rombel_aktif->rombel_id_dapodik,
						'petugas' 			=> $loggeduser->username,
					)
				);
			}
			foreach($id_siswa as $s){
				$attributes = array('semester_id' => $new_ajaran, 'rombongan_belajar_id' => $rombel_id_next_smt, 'siswa_id' => $s);
				$anggota = $this->anggota_rombel->insert($attributes);
			}
		}
		$output = array(
			'title' 	=> 'Sukses',
			'text'		=> 'Proses lanjutkan semester berhasil',
			'type'		=> 'success',
			'status'	=> 1
		);
		echo json_encode($output);
	}
	
	public function lanjutkan_old($id_rombel){
		$semester = get_ta();
		$page_title = 'Proses Kenaikan Kelas';
		if($semester->semester == 1){
			$page_title = 'Proses Lanjutkan Semester';
		}
		$get_next_smt = $this->semester->find("tahun = '$semester->tahun' and semester = 2");
		//Ajaran::find_by_tahun_and_smt($ajaran->tahun,2);
		if($get_next_smt){
			$anggota_next_smt 	= $this->anggota_rombel->find_all("semester_id = $get_next_smt->id AND rombongan_belajar_id = $id_rombel");
			//Anggotarombel::find('all', array('conditions' => array('ajaran_id = ? AND rombel_id = ?', $get_next_smt->id, $id_rombel)));
			if($anggota_next_smt){
				foreach($anggota_next_smt as $ans){
					$id_next_smt[] = $ans->siswa_id;
				}
			}
		}
		$id_next_smt = isset($id_next_smt) ? $id_next_smt : 0;
		if(is_array($id_next_smt)){
			$id_next_smt = implode(',',$id_next_smt);
		}
		$anggota 	= $this->anggota_rombel->with('siswa')->find_all("semester_id = $semester->id AND rombongan_belajar_id = $id_rombel AND siswa_id NOT IN ($id_next_smt)");
		//Anggotarombel::find('all', array('conditions' => array('ajaran_id = ? AND rombel_id = ? AND siswa_id NOT IN (?)', $ajaran->id, $id_rombel, $id_next_smt)));
		$this->template->title('Administrator Panel : Proses Kenaikan Kelas')
        ->set_layout($this->modal_tpl)
	        ->set('page_title', $page_title)
	        ->set('anggota', $anggota)
			->set('rombel_id', $id_rombel)
			->set('modal_footer', '<div class="text-center"><a href="javascript:void(0)" class="btn btn-success btn-sm proses_kenaikan"><i class="fa fa-plus-circle"></i> Simpan</a></div>')
	        ->build($this->admin_folder.'/rombel/lanjutkan');
	}
    public function list_rombel($kompetensi = NULL, $tingkat = NULL){
		$semester = get_ta();
		$loggeduser = $this->ion_auth->user()->row();
		$search = "";
		$start = 0;
		$rows = 25;
		$ajaran = get_ta();
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = get_start();
		$rows = get_rows();
		if($kompetensi && !$tingkat){
			$set_query = "AND jurusan_sp_id = $kompetensi";
		} elseif($kompetensi && $tingkat){
			$set_query = "AND tingkat = $tingkat AND jurusan_sp_id = $kompetensi";
		} else {
			$set_query = '';
		}
		$query = $this->rombongan_belajar->with('pembelajaran')->find_all("sekolah_id = $loggeduser->sekolah_id AND semester_id = $semester->id $set_query AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')", '*','id desc', $start, $rows);
		$filter = $this->rombongan_belajar->find_all("sekolah_id = $loggeduser->sekolah_id AND semester_id = $semester->id $set_query AND (nama LIKE '%$search%' OR kurikulum_id LIKE '%$search%' OR tingkat LIKE '%$search%' OR id LIKE '%$search%')", '*','id desc');
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
		//$barang = $query->result();
	    foreach ($query as $temp) {
			$get_semester = $this->semester->get($temp->semester_id);
			$class = 'btn-danger';
			$text = 'Salin Pembelajaran';
			$link = 'salin_pembelajaran';
			//$kurikulum = $this->pembelajaran->find("semester_id = $semester->id and rombel_id = $temp->id");
			//test($kurikulum);
			if($get_semester->semester == 1 || $temp->pembelajaran){
				$class = 'btn-success';
				$text = 'Pembelajaran';
				$link = 'pembelajaran';
			}
			$walikelas = (get_wali_kelas($temp->id) != '-') ? get_wali_kelas($temp->id).'<a href="'.site_url('admin/data_guru/select/'.$temp->id).'" class="btn btn-primary btn-sm toggle-modal pull-right"><i class="fa fa-search-plus"></i> Ganti Wali Kelas</a>': '<a href="'.site_url('admin/data_guru/select/'.$temp->id).'" class="btn btn-primary btn-sm toggle-modal"><i class="fa fa-search-plus"></i> Pilih Wali Kelas</a>';
			$record = array();
			$record[] = '<div class="text-center"><input type="checkbox" class="satuan" value="'.$temp->id.'" /></div>';
			$record[] = $temp->nama;
			$record[] = $walikelas;
			$record[] = '<div class="text-center">'.$temp->tingkat.'</div>';
			if($temp->kurikulum_id){
				$record[] = '<div class="text-center"><a href="'.site_url('admin/rombel/'.$link.'/'.$temp->id).'" class="btn '.$class.' btn-sm toggle-modal"><i class="fa fa-search-plus"></i> '.$text.'</a></div>';
			} else {
				$record[] = '<div class="text-center"><a href="'.site_url('admin/rombel/jurusan/'.$temp->id).'" class="btn btn-primary btn-sm confirm_input"><i class="fa fa-search-plus"></i> Jurusan</a></div>';
			}
			$record[] = '<div class="text-center"><a href="'.site_url('admin/rombel/anggota/'.$temp->id).'" class="btn btn-primary btn-sm toggle-modal"><i class="fa fa-search-plus"></i> Anggota Rombel</a></div>';
			if($ajaran->semester == 2){
				$record[] = '<div class="text-center"><a href="'.site_url('admin/rombel/kenaikan/'.$temp->id).'" class="btn btn-info btn-sm toggle-modal"><i class="fa fa-level-up"></i> Proses Kenaikan</a></div>';
			} else {
				$record[] = '<div class="text-center"><a href="'.site_url('admin/rombel/lanjutkan/'.$temp->id).'" class="btn btn-info btn-sm toggle-modal"><i class="fa fa-level-up"></i> Lanjutkan Semester</a></div>';
			}			
			$record[] = '<div class="text-center"><a href="'.site_url('admin/rombel/edit/'.$temp->id).'" class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a></div>';
			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function jurusan($id){
		if(isset($_POST['nama'])){
			$nama = $_POST['nama'];
			$updatedata = array('kurikulum_id'=>$nama);
			$this->db->where('id', $id);
			$this->db->update('data_rombels', $updatedata); 
			echo 'sukses';
		} else {
			$keahlian = Keahlian::all();
			if($keahlian){
				foreach($keahlian as $ahli){
					$data_kurikulum = Datakurikulum::find_by_kurikulum_id($ahli->kurikulum_id);
					$output[$data_kurikulum->kurikulum_id] 	= $data_kurikulum->nama_kurikulum;
				}
			} else {
				$output[''] = 'Kompetensi Keahlian tidak ditemukan.';
			}
		echo json_encode($output);
		}
	}	
	public function pembelajaran($id){
		$ajaran = get_ta();
		$get_prev_smt = $this->semester->find("tahun = '$ajaran->tahun' and semester = 1");
		$get_prev_smt_id = isset($get_prev_smt->id) ? $get_prev_smt->id : 0;
		$rombel = $this->rombongan_belajar->get($id);
		//echo get_kurikulum($rombel->kurikulum_id);
		//die();
		if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
			$file = 'pembelajaran_2017';		
		} elseif (strpos(get_kurikulum($rombel->kurikulum_id), 'KTSP') !== false) {
			$file = 'pembelajaran_ktsp';
		} else {
			$file = 'pembelajaran_2013';
		}
		$this->template->title('')
		->set_layout($this->modal_tpl)
		->set('page_title', 'Referensi Pembelajaran Rombongan Belajar '.$rombel->nama)
		->set('data_rombel', $rombel)
		->set('ajaran_id', $get_prev_smt_id)
		->set('ajaran_aktif', $ajaran->id)
		->set('modal_footer', '<span class="text-left" style="float:left;">Kurikulum '.get_kurikulum($rombel->kurikulum_id).' ('.$rombel->kurikulum_id.')</span><a href="javascript:void(0)" class="btn btn-success btn-sm simpan_pembelajaran"><i class="fa fa-plus-circle"></i> Simpan</a>')			
		->build($this->admin_folder.'/rombel/'.$file);
	}
	public function salin_pembelajaran($id){
		$semester = get_ta();
		//$get_prev_smt = $this->semester->find("tahun = '$ajaran->tahun' and semester = 1");
		//$get_prev_smt_id = isset($get_prev_smt->id) ? $get_prev_smt->id : 0;
		$smt = 2;
		$plus_tingkat = 1;
		$tahun = explode('/',$semester->tahun);
		$tapel_1 = $tahun[0] - 1;
		$tapel_2 = $tahun[1] - 1;
		$tahun_ajaran = $tapel_1.'/'.$tapel_2;
		if($semester->semester == 2){
			$smt = 1;
			$plus_tingkat = 0;
			$tahun_ajaran = $semester->tahun;
		}
		$get_prev_ta = $this->semester->find("tahun = '$tahun_ajaran' AND semester = $smt");
		$semester_id = ($get_prev_ta) ? $get_prev_ta->id : 0;
		$rombel = $this->rombongan_belajar->get($id);
		$tingkat = $rombel->tingkat + $plus_tingkat;
		$kurikulum_id = $rombel->kurikulum_id;
		$jurusan_sp_id = $rombel->jurusan_sp_id;
		$rombel_prev = $this->rombongan_belajar->find("semester_id = $semester_id AND tingkat = $tingkat AND kurikulum_id = $kurikulum_id AND jurusan_sp_id = $jurusan_sp_id");
		$id_rombel_prev = ($rombel_prev) ? $rombel_prev->id : 0;
		if (strpos(get_kurikulum($rombel->kurikulum_id), 'REV') !== false) {
			$file = 'pembelajaran_2017';		
		} elseif (strpos(get_kurikulum($rombel->kurikulum_id), 'KTSP') !== false) {
			$file = 'pembelajaran_ktsp';
		} else {
			$file = 'pembelajaran_2013';
		}
		$file = 'salin_pembelajaran';
		$this->template->title('')
		->set_layout($this->modal_tpl)
		->set('page_title', 'Referensi Pembelajaran Rombongan Belajar '.$rombel->nama)
		->set('tingkat', $tingkat)
		->set('data_rombel', $rombel)
		->set('id_rombel_prev', $id_rombel_prev)
		->set('ajaran_id', $semester_id)
		->set('ajaran_aktif', $semester->id)
		->set('modal_footer', '<span class="text-left" style="float:left;">Kurikulum '.get_kurikulum($rombel->kurikulum_id).' ('.$rombel->kurikulum_id.')</span><a href="javascript:void(0)" class="btn btn-success btn-sm simpan_pembelajaran"><i class="fa fa-plus-circle"></i> Simpan</a>')			
		->build($this->admin_folder.'/rombel/'.$file);
	}
	public function anggota($id_rombel){
		$semester = get_ta();
		//$free		= Datasiswa::find('all', array('conditions' => array('data_rombel_id =?', 0)));	
		//$anggota 	= Anggotarombel::find_all_by_ajaran_id_and_rombel_id($ajaran->id, $id_rombel);
		//$join = "JOIN anggota_rombels a ON(data_siswas.id = a.datasiswa_id)";
		//$free = Datasiswa::find('all', array('conditions' => "data_siswas.id IS NOT NULL", 'joins'=> $join));
		//$free = Datasiswa::find_by_sql("SELECT * FROM `data_siswas` WHERE id NOT IN(SELECT datasiswa_id FROM anggota_rombels WHERE ajaran_id = $ajaran->id)");
		$free = $this->siswa->find_all("id NOT IN(SELECT siswa_id FROM anggota_rombel WHERE semester_id = $semester->id)");

		//$free = 0;
		$anggota	= get_siswa_by_rombel($id_rombel);
		//test($anggota);
		//die();
		$this->template->title('Administrator Panel : Pilih Anggota Rombel')
        ->set_layout($this->modal_tpl)
		->set('page_title', 'Pilih Anggota Rombel')
		->set('anggota', $anggota)
		->set('free', $free)
		->set('id_rombel', $id_rombel)
		//->set('modal_footer', '<div class="text-center"><a href="javascript:void(0)" class="btn btn-success btn-sm simpan_anggota"><i class="fa fa-plus-circle"></i> Simpan</a></div>')
		->build($this->admin_folder.'/rombel/anggota');
	}
	public function hapus_anggota(){
		$rombel_id 	= $_POST['rombel_id'];
		$siswa_id	= $_POST['siswa_id'];
		$semester = get_ta();
		$find = $this->anggota_rombel->find("semester_id = $semester->id and rombongan_belajar_id = $rombel_id and siswa_id = $siswa_id");
		if($find){
			$this->anggota_rombel->delete($find->id);
			$status['type'] = 'error';
			$status['text'] = 'Berhasil menghapus anggota rombel';
			$status['title'] = 'Data Tersimpan!';
		} else {
			$status['type'] = 'error';
			$status['text'] = 'Data anggota rombel tidak ditemukan';
			$status['title'] = 'Data Tersimpan!';
		}
		echo json_encode($status);
	}
	public function simpan_anggota(){
		$rombel_id 	= $_POST['rombel_id'];
		$siswa_id	= $_POST['siswa_id'];
		$semester = get_ta();
		$find = $this->anggota_rombel->find("semester_id = $semester->id and rombongan_belajar_id = $rombel_id and siswa_id = $siswa_id");
		if($find){
			$this->anggota_rombel->update($find->id, array('rombongan_belajar_id' => $rombel_id));
			$status['type'] = 'warning';
			$status['text'] = 'Berhasil mengupdate anggota rombel';
			$status['title'] = 'Data Tersimpan!';
		} else {
			$this->anggota_rombel->insert(array('semester_id' => $semester->id, 'rombongan_belajar_id' => $rombel_id, 'siswa_id' => $siswa_id));
			$status['type'] = 'success';
			$status['text'] = 'Berhasil menambah anggota rombel';
			$status['title'] = 'Data Tersimpan!';
		}
		echo json_encode($status);
	}
	public function tambah_alias(){
		$id = $this->input->post('pk');
		$value = $this->input->post('value');
		$get_mapel = $this->mata_pelajaran->get($id);
		if($get_mapel){
			$this->mata_pelajaran->update($id, array('nama_mata_pelajaran_alias' => $value));
		}
	}
	public function simpan_mapel(){
		$guru_id = $this->input->post('guru_id');
		$ajaran_id = $this->input->post('ajaran_id');
		$rombel_id = $this->input->post('rombel_id');
		$tingkat = $this->input->post('tingkat');
		$mapel_id = $this->input->post('mapel_id');
		foreach($tingkat as $k=>$tingkat){
			if($tingkat){
				$post = explode("#", $rombel_id[$k]);
				$set_keahlian_id = $post[0];
				$set_rombel_id = $post[1];
				$find_all = Kurikulum::find(array('conditions'=>array('ajaran_id = ? AND data_rombel_id = ? AND guru_id = ?',$ajaran_id,$set_rombel_id,$guru_id)));
				if($find_all){
					test($find_all);
					$data_kurikulum_update = array(
						'id_mapel' => $mapel_id[$k],
					);
					if($mapel_id[$k] == $find_all->id_mapel){
						$find_all->update_attributes($data_kurikulum_update);
						echo 'update<br />';
					} else {
						$find_guru_lain = Kurikulum::find(array('conditions'=>array('ajaran_id = ? AND data_rombel_id = ? AND id_mapel = ? AND guru_id != ?',$ajaran_id,$set_rombel_id,$mapel_id[$k], $guru_id)));
						if($find_guru_lain){
							$find_guru_lain->delete();
							echo 'delete<br />';
						}
						$find_mapel_lain = Kurikulum::find(array('conditions'=>array('ajaran_id = ? AND data_rombel_id = ? AND id_mapel = ? AND guru_id = ?',$ajaran_id,$set_rombel_id,$mapel_id[$k], $guru_id)));
						if($find_mapel_lain){
							$find_mapel_lain->delete();
							echo 'delete<br />';
						} 
					}
				} else {
					echo 'create bawah 2<br />';
					$new_data				= new Kurikulum();
					$new_data->ajaran_id	= $ajaran_id;
					$new_data->rombel_id	= $set_rombel_id;
					$new_data->id_mapel		= $mapel_id[$k];
					$new_data->guru_id		= $guru_id;
					$new_data->keahlian_id	= $set_keahlian_id;
					$new_data->save();
				}
			} else {
				echo 'bawah<br />';
			}
		}
	}
	public function simpan_pembelajaran(){
		$ajaran = get_ta();
		$query 				= $this->input->post('query');
		$rombel_id 			= $this->input->post('rombel_id');
		$mata_pelajaran_id 	= $this->input->post('mapel_id');
		$guru_id 			= $this->input->post('guru_id');
		$matpel_kur_id 		= $this->input->post('matpel_kur_id');
		$kelompok_id 		= $this->input->post('kelompok_id');
		$data_pembelajaran = array(
			'semester_id'			=> $ajaran->id,
			'rombongan_belajar_id'	=> $rombel_id,
			'mata_pelajaran_id'		=> $mata_pelajaran_id,
			'guru_id'				=> $guru_id,
		);
		//test($data_pembelajaran);
		if($query == 'pembelajaran'){
			$get_matpel_kur_id = $this->mata_pelajaran_kurikulum->get($matpel_kur_id);
			if($get_matpel_kur_id){
				$this->mata_pelajaran_kurikulum->update($get_matpel_kur_id->id, array('kelompok_id' => $kelompok_id));
			}
			$find = $this->pembelajaran->find("semester_id = $ajaran->id and rombongan_belajar_id = $rombel_id and mata_pelajaran_id = $mata_pelajaran_id");
			//Kurikulum::find_by_;
			if($find){
				//if($find->guru_id == 0){
				if(!$guru_id){
					$this->pembelajaran->delete($find->id);
					$status['type'] = 'error';
					$status['text'] = 'Berhasil menghapus pembelajaran '.get_nama_mapel($mata_pelajaran_id);
					$status['title'] = 'Data Terhapus!';
				} else {
					$this->pembelajaran->update($find->id, $data_pembelajaran);
					$status['type'] = 'warning';
					$status['text'] = 'Berhasil mengupdate pembelajaran '.get_nama_mapel($mata_pelajaran_id);
					$status['title'] = 'Data Tersimpan!';
				}
			} else {
				if($guru_id){
					$this->pembelajaran->insert($data_pembelajaran);
					$status['type'] = 'success';
					$status['text'] = 'Berhasil menambah pembelajaran '.get_nama_mapel($mata_pelajaran_id);
					$status['title'] = 'Data Tersimpan!';
				} else {
					$status['type'] = 'info';
					$status['text'] = 'Guru tidak dipilih untuk mata pelajaran '.get_nama_mapel($mata_pelajaran_id);
					$status['title'] = 'Data dilewati!';
				}
			}
		}
		echo json_encode($status);
	}
	public function set_unknow(){
		$status['type'] = 'error';
		$status['text'] = 'Permintaan tidak diproses';
		$status['title'] = 'Akses tidak sah!';
		echo json_encode($status);
	}
	public function guru(){
		$data_guru = $this->guru->find_all('','','nama ASC');
		//Dataguru::find('all', array('order' => 'nama asc'));
		foreach($data_guru as $guru){
			$status = array();
			$status['id'] = $guru->id;
			$status['text'] = $guru->nama;
			$output[] = $status;
		}
		echo json_encode($output);
	}
	public function kelompok($kur){
		$get_kurikulum = get_kurikulum($kur);
		if (strpos($get_kurikulum, 'REV') !== false) {
			$kurikulum = 2017;
		} elseif (strpos($get_kurikulum, 'KTSP') !== false) {
			$kurikulum = 2006;
		} else {
			$kurikulum = 2013;
		}
		$get_kelompok = $this->kelompok->find_all("kurikulum = $kurikulum OR kurikulum = 0");
		if($get_kelompok){
			foreach($get_kelompok as $kelompok){
				$record= array();
				$record['id'] 	= $kelompok->id;
				$record['text'] = $kelompok->nama_kelompok;
				$output[] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan kelompok mata pelajaran';
			$output[] = $record;
		}
		echo json_encode($output);
		//$data_guru = $this->guru->find_all('','','nama ASC');
		//Dataguru::find('all', array('order' => 'nama asc'));
		//foreach($data_guru as $guru){
			//$status = array();
			//$status['id'] = $guru->id;
			//$status['text'] = $guru->nama;
			//$output[] = $status;
		//}
		//echo json_encode($output);
	}
}