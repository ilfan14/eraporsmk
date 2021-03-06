<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends Backend_Controller {
	protected $auth_folder 	= 'auth';
	protected $styles  		= 'auth/partials/css';
	protected $footer 		= 'auth/partials/footer';
	protected $auth_tpl		= 'auth_tpl';

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('ion_auth');
	}
	public function index(){
		//validate form input
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == true) {
			$remember = (bool) $this->input->post('remember');
			if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember)) {
			$loggeduser = $this->ion_auth->user()->row();
			$sekolah = $this->sekolah->get(1);
			if(!$loggeduser->sekolah_id){
				$updatedata = array('sekolah_id'=>$sekolah->id);
				$this->db->where('id', $loggeduser->id);
				$this->db->update('users', $updatedata); 
			}
			$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('admin/dashboard');
			} else {
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('admin/auth'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		} else {
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->template->title('Administrator Login ')
	        ->set_layout($this->auth_tpl)
	        ->set('page_title', 'Forgot Password')
	        ->build($this->auth_folder.'/login', $this->data);
		}
		//setup the input
	}
	public function logout(){
		$user = $this->ion_auth->user()->row();
		$this->db->where('id', $user->id);
		$this->db->update('users', array('login_status' => 0)); 
		//log the user out
		$logout = $this->ion_auth->logout();
		//redirect them to the login page
		$this->session->set_flashdata('success', $this->ion_auth->messages());
		redirect('', 'refresh');
		//redirect('admin/dashboard');
	}
}