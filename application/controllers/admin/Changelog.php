<?php
class Changelog extends Backend_Controller {
	protected $activemenu = 'changelog';
	public function __construct() {
		parent::__construct(); 
		$this->template->set('activemenu', $this->activemenu);
	}
	public function index(){
		$this->template->title('Daftar Perubahan Aplikasi')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Daftar Perubahan Aplikasi')
        ->build($this->admin_folder.'/changelog');
	}
}