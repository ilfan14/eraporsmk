<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends Backend_Controller {
	protected $activemenu = 'config';
	public function __construct(){
		parent::__construct();
		$this->template->set('activemenu', $this->activemenu);
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1'); 
	}
	public function index(){
	}
	public function backup(){
		$admin_group = array(1,2);
		hak_akses($admin_group);
		$this->template->title('Administrator Panel : Backup / Restore Data')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Backup / Restore Data')
        ->build($this->admin_folder.'/tools/backup');
		//->build($this->admin_folder.'/perbaikan');
	}
	public function general(){
		$admin_group = array(1,2);
		hak_akses($admin_group);
		$data['settings'] = $this->settings->get(1);
		$this->template->title('Administrator Panel : General Setting')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'General Setting')
        ->build($this->admin_folder.'/general', $data);
	}
	public function update(){
		if($_POST){
			$settings 	= $this->settings->get(1);
			$setting = array(
				/*'a_max'			=> $_POST['a_max'],
				'a_min' 		=> $_POST['a_min'],
				'b_max'			=> $_POST['b_max'],
				'b_min' 		=> $_POST['b_min'],
				'c_max'			=> $_POST['c_max'],
				'c_min' 		=> $_POST['c_min'],
				'd_max'			=> $_POST['d_max'],
				'd_min' 		=> $_POST['d_min'],*/
				'periode' 		=> $this->input->post('periode'),
				'sinkronisasi'	=> $this->input->post('sinkronisasi'),
				//'rumus' 		=> $_POST['rumus'],
				'tanggal_rapor'	=> date('Y-m-d', strtotime($this->input->post('tanggal_rapor'))),
				'import' 		=> $this->input->post('import'),
				'desc' 			=> $this->input->post('desc'),
				'zona' 			=> $this->input->post('zona'),
			);
			$strings = $setting['periode'];
			$strings = explode('|',$strings);
			$tapel = str_replace(' ','',$strings[0]);
			$semester = $strings[1];
			$semester = str_replace('Semester','',$semester);
			$semester = str_replace(' ','',$semester);
			$smt = 2;
			if($semester == 'Ganjil'){
				$smt = 1;
			}
			$semesters = $this->semester->find("tahun = '$tapel' AND semester = $smt");
			$data_semester = array(
				'tahun'		=> $tapel,
				'semester' 	=> $smt,
				'nama' 		=> $strings[1]
			);
			if(!$semesters){
				$this->semester->insert($data_semester);
			}
			/*if($setting['a_min'] >= $setting['a_max']){
				$this->session->set_flashdata('error', 'Minimal Nilai A tidak boleh sama atau lebih besar dari Maksimal Nilai A');
			} elseif($setting['b_min'] >= $setting['b_max']){
				$this->session->set_flashdata('error', 'Minimal Nilai B tidak boleh sama atau lebih besar dari Maksimal Nilai B');
			} elseif($setting['c_min'] >= $setting['c_max']){
				$this->session->set_flashdata('error', 'Minimal Nilai C tidak boleh sama atau lebih besar dari Maksimal Nilai C');
			} elseif($setting['d_min'] > 0){
				$this->session->set_flashdata('error', 'Minimal Nilai D tidak boleh lebih besar dari 0');
			} else {
				$settings->update_attributes($setting);
			}*/
			$this->settings->update(1, $setting);
			$this->session->set_flashdata('success', 'General Setting berhasil di update');
			redirect('admin/config/general');
		} else {
			redirect('admin/dashboard');
		}
	}
	public function users(){
		$this->template->title('Administrator Panel : Data Pengguna')
        ->set_layout($this->admin_tpl)
        ->set('page_title', 'Data Pengguna')
        ->build($this->admin_folder.'/users/list');
	}
	public function list_users(){
		$search = "";
		$iSortCol_0 = "";
		$start = 0;
		$rows = 10;

		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		/*if (isset($_GET['iSortCol_0']) && $_GET['iSortCol_0'] != "" ) {
			$iSortCol_0 = $_GET['iSortCol_0'];
		}
		if($iSortCol_0 == 1){
			$iSortCol_0 = 'username';
		}
		if($iSortCol_0 == 2){
			$iSortCol_0 = 'last_login';
		}
		if($iSortCol_0 == 3){
			$iSortCol_0 = 'phone';
		}
		if($iSortCol_0 == 3){
			$iSortCol_0 = 'phone';
		}*/
		$loggeduser = $this->ion_auth->user()->row();
		// limit
		$start = get_start();
		$rows = get_rows();
		//$get_sort_dir = get_sort_dir();
		//$order = ($iSortCol_0) ? $iSortCol_0 : 'id';
		$join = "LEFT JOIN users_groups a ON(users.id = a.user_id)";
		$join .= "INNER JOIN groups b ON(a.group_id = b.id)";
		$sel = 'users.*, a.group_id AS id_group, b.description as nama_group';
		$where = "username LIKE '%$search%' OR nisn LIKE '%$search%' OR nuptk LIKE '%$search%' OR email LIKE '%$search%' OR id IN(SELECT user_id FROM users_groups WHERE group_id IN (SELECT id FROM groups WHERE name LIKE '%$search%'))";
		//$query = $this->user->find_all("sekolah_id = $loggeduser->sekolah_id AND users.id != $loggeduser->id AND ($where)", '*','id desc', $start, $rows);
		$query = $this->user->find_all("sekolah_id = $loggeduser->sekolah_id AND users.id != $loggeduser->id AND ($where)", '*',"username asc", $start, $rows);
		$filter = $this->user->find_count("sekolah_id = $loggeduser->sekolah_id AND users.id != $loggeduser->id AND ($where)", '*','username asc');
		$iFilteredTotal = count($query);
		//echo "$order $get_sort_dir";
		$iTotal= $filter;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
	    foreach ($query as $temp) {
			$record = array();
			//if($loggeduser->id != $temp->id){
			$aktifkan = ($temp->active == 1)  ? '<a href="'.site_url('admin/config/deactivate/'.$temp->id).'" class="toggle-modal"><i class="fa fa-power-off"></i>Non Aktifkan</a>' : '<a href="'.site_url('admin/config/activate/'.$temp->id).'"><i class="fa fa-check-square-o"></i>Aktifkan</a>';
			$groups = $this->ion_auth->get_users_groups($temp->id)->result_array(); 
			$record[] = '<div class="text-center">'.($i + 1).'</div>';
			$record[] = $temp->username;
			$record[] = ($temp->last_login) ? indo_date(date('d-m-Y H:i:s', $temp->last_login)).' '.date('H:i:s', $temp->last_login) : '<div class="text-center">-</div>';
			$record[] = $temp->phone;
			$record[] = implode(', ', array_map(function ($entry) {
					 	return $entry['description'];
					}, $groups));
			$record[] = status_label($temp->active);
			$record[] = '<div class="text-center"><div class="btn-group">
							<button type="button" class="btn btn-default btn-sm">Aksi</button>
							<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<ul class="dropdown-menu pull-right text-left" role="menu">
								<li><a href="'.site_url('admin/config/view/'.$temp->id).'" class="toggle-modal"><i class="fa fa-eye"></i>Detil</a></li>
								<li><a href="'.site_url('admin/users/edit/'.$temp->id).'"><i class="fa fa-pencil"></i>Edit</a></li>
								<li>'.$aktifkan.'</li>
								<!--li><a href="'.site_url('admin/users/delete/'.$temp->id).'" class="confirm"><i class="fa fa-trash-o"></i>Hapus</a></li-->
							</ul>
						</div></div>';
			$i++;
			$output['aaData'][] = $record;
			//}
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
	}
	public function view($id){
		$user = $this->user->get($id);
		$this->template->title('Administrator Panel : view user')
	        ->set_layout($this->modal_tpl)
	        ->set('page_title', 'Detil Pengguna')
	        ->set('user', $user)
			->set('modal_footer','')
			->set('modal_s','modal-sm')
	        ->set('groups', $this->ion_auth->get_users_groups($id)->result())
	        ->build($this->admin_folder.'/users/view');
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
			$this->template->title('Administrator Panel : Non Aktifkan Pendaftar')
	        ->set_layout('modal_tpl')
			->set('modal_footer','')			
	        ->set('page_title',  'Deactivate User' )
	        ->build('auth/deactivate_user', $this->data);
		} else {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes'){
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')){
					show_error($this->lang->line('error_csrf'));
				}
				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()){
					$this->ion_auth->deactivate($id);
					$this->session->set_flashdata('success', $this->ion_auth->messages());
				}
			}
			//redirect them back to the auth page
			redirect('admin/config/users');
		}
	}

	//activate the user
	function activate($id){

		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()){
			$activation = $this->ion_auth->activate($id);
		}
		if ($activation){
			//redirect them to the auth page
			$this->session->set_flashdata('success', $this->ion_auth->messages());
			redirect("admin/config/users");
		} else {
			//redirect them to the forgot password page
			$this->session->set_flashdata('error', $this->ion_auth->errors());
			redirect("admin/config/users");
		}
	}
	public function delete($id){
		$user = User::find($id);
		if(is_file(PROFILEPHOTOS.$user->photo)){
			unlink(PROFILEPHOTOS.$user->photo);
			unlink(PROFILEPHOTOSTHUMBS.$user->photo);
		}
		$user->delete();
		//redirect("admin/config/users");
	}
	public function _get_csrf_nonce()
	{
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
}
