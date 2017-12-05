<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends Backend_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function index(){
		redirect('admin/dashboard/');
		//$this->load->view('welcome_message');
	}
}
