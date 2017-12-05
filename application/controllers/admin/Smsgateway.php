<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Smsgateway extends Backend_Controller {
	protected $activemenu = 'smsgateway';
	public function __construct(){
		parent::__construct();
		$this->template->set('activemenu', $this->activemenu);
	}

	public function index(){
		$admin_group = array(1,2);
		hak_akses($admin_group);
		$this->template->title('Administrator Panel : Backup / Restore Data')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Sms Gateway')
        ->build($this->admin_folder.'/smsgateway/smsgateway');
	}
}
