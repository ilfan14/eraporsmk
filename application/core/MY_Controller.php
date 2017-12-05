<?php 
class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('ion_auth');
		check_installer();
	}
}
class Backend_Controller extends MY_Controller {
	protected $admin_folder 		= 'backend';
	protected $sinkronisasi_folder 	= 'sinkronisasi';
	protected $styles  				= 'backend/partials/css';
	protected $header 				= 'backend/partials/header';
	protected $sidebar 				= 'backend/partials/sidebar';
	protected $footer 				= 'backend/partials/footer';
	protected $admin_tpl 			= 'admin_tpl';
	protected $modal_tpl 			= 'modal_tpl';
	protected $blank_tpl 			= 'blank_tpl';
	function __construct() {
		parent::__construct();
		$this->template->set_partial('styles', $this->styles)
        ->set_partial('header', $this->header)
        ->set_partial('sidebar', $this->sidebar)
        ->set_partial('footer', $this->footer);
		$this->load->dbforge();
		if (!$this->db->field_exists('tanggal_rapor', 'settings')){
			$fields = array(
	        	'tanggal_rapor' => array(
					'type' 			=> 'DATE',
				)
			);
			$this->dbforge->add_column('settings', $fields);
		}
		if (!$this->db->field_exists('user_id', 'ref_kompetensi_dasar')){
			$fields = array(
	        	'user_id' => array(
					'type' 			=> 'INT',
					'constraint' 	=> 11,
					'default'		=> 0,
				)
			);
			$this->dbforge->add_column('ref_kompetensi_dasar', $fields);
		}
		if ($this->db->field_exists('deleted_at', 'ref_guru')){
			$this->dbforge->drop_column('ref_guru', 'deleted_at');
		}
		if ($this->db->field_exists('deleted_at', 'users')){
			$this->dbforge->drop_column('users', 'deleted_at');
		}
		if (!$this->db->field_exists('jurusan_sp_id', 'jurusan_sp')){
			$fields = array(
	        	'jurusan_sp_id' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				)
			);
			$this->dbforge->add_column('jurusan_sp', $fields);
		}
		if (!$this->db->field_exists('pembelajaran_id', 'pembelajaran')){
			$fields = array(
	        	'pembelajaran_id' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				)
			);
			$this->dbforge->add_column('pembelajaran', $fields);
		}
		if (!$this->db->field_exists('nama_mata_pelajaran_alias', 'ref_mata_pelajaran')){
			$fields = array(
	        	'nama_mata_pelajaran_alias' => array(
					'type' 			=> 'VARCHAR',
					'constraint' 	=> 255,
					'default'		=> NULL,
				)
			);
			$this->dbforge->add_column('ref_mata_pelajaran', $fields);
		}
		if (!$this->db->field_exists('semester_id', 'catatan_ppk')){
			$fields = array(
	        	'semester_id' => array(
					'type' 			=> 'INT',
					'constraint' 	=> 11,
					'default'		=> 0,
				)
			);
			$this->dbforge->add_column('catatan_ppk', $fields);
		}
		if (!$this->db->field_exists('kegiatan', 'catatan_ppk')){
			$fields = array(
				'kegiatan'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
				'tempat'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
				'penanggung_jawab'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
				'waktu'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
				'capaian'	=> array(
					'type' => 'TEXT',
				),
			);
			$this->dbforge->add_column('catatan_ppk', $fields);
		}
		if (!$this->db->table_exists('import_rencana')){
			$fields = array(
				'id' 				=> array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'semester_id' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'kompetensi_id' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'rombongan_belajar_id'	=> array(
					'type' => 'INT',
					'constraint' => 11
				),
				'mata_pelajaran_id'	=> array(
					'type' => 'INT',
					'constraint' => 11
				),
				'guru_id'	=> array(
					'type' => 'INT',
					'constraint' => 11
				),
				'created_at' => array(
					'type' => 'datetime'
				),
				'updated_at' => array(
					'type' => 'datetime'
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('import_rencana');
		}
		if (!$this->db->field_exists('guru_id', 'ref_sekolah')){
			$fields = array(
	        	'guru_id' => array(
					'type' => 'INT',
					'constraint' => 11,
				),
				'kop_surat' => array(
					'type' => 'LONGTEXT',
				),
				'pengantar_ppk' => array(
					'type' => 'LONGTEXT',
				),
			);
			$this->dbforge->add_column('ref_sekolah', $fields);
		}
		if (!$this->db->field_exists('foto_1', 'catatan_ppk')){
			$fields = array(
	        	'foto_1' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
				'foto_2' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
				'foto_3' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
			);
			$this->dbforge->add_column('catatan_ppk', $fields);
		}
		if (!$this->db->table_exists('ref_kompetensi')){
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'created_at' => array(
					'type' => 'datetime'
				),
				'updated_at' => array(
					'type' => 'datetime'
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('ref_kompetensi');
			$ref_kompetensi = array(
				array('id' => '1','nama' => 'Pengetahuan','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
				array('id' => '2','nama' => 'Keterampilan','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			);
			$this->db->insert_batch('ref_kompetensi', $ref_kompetensi);
		}
		if (!$this->db->table_exists('ref_gelar')){
			$fields = array(
				'gelar_akademik_id' => array(
					'type' => 'INT',
					'constraint' => 11,
				),
				'kode' => array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'posisi_gelar' => array(
					'type' => 'INT',
					'constraint' => 11,
				),
				'created_at' => array(
					'type' => 'datetime'
				),
				'updated_at' => array(
					'type' => 'datetime'
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('gelar_akademik_id', TRUE);
			$this->dbforge->create_table('ref_gelar');
		}
		if (!$this->db->table_exists('gelar_ptk')){
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'gelar_akademik_id' => array(
					'type' => 'INT',
					'constraint' => 11,
				),
				'ptk_id' => array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'guru_id' => array(
					'type' => 'INT',
					'constraint' => 11,
				),
				'created_at' => array(
					'type' => 'datetime'
				),
				'updated_at' => array(
					'type' => 'datetime'
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('gelar_ptk');
		}
		if (!$this->db->table_exists('ref_pekerjaan')){
			$fields = array(
				'pekerjaan_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					//'auto_increment' => TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'created_at' => array(
					'type' => 'datetime'
				),
				'updated_at' => array(
					'type' => 'datetime'
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('pekerjaan_id', TRUE);
			$this->dbforge->create_table('ref_pekerjaan');
			$ref_kompetensi = array(
				array('pekerjaan_id' => 1,'nama' => 'Tidak bekerja','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 2,'nama' => 'Nelayan','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 3,'nama' => 'Petani','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 4,'nama' => 'Peternak','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 5,'nama' => 'PNS/TNI/Polri','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 6,'nama' => 'Karyawan Swasta','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 7,'nama' => 'Pedagang Kecil','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 8,'nama' => 'Pedagang Besar','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 9,'nama' => 'Wiraswasta','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 10,'nama' => 'Wirausaha','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 11,'nama' => 'Buruh','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 12,'nama' => 'Pensiunan','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 13,'nama' => 'Tenaga Kerja Indonesia','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 90,'nama' => 'Tidak dapat diterapkan','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 98,'nama' => 'Sudah Meninggal','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
				array('pekerjaan_id' => 99,'nama' => 'Lainnya','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')),
			);
			$this->db->insert_batch('ref_pekerjaan', $ref_kompetensi);
		}
		if ($this->db->field_exists('uraian_deskripsi', 'deskripsi_sikap')){
			$fields = array(
				'uraian_deskripsi' => array(
					'name' => 'uraian_deskripsi_spiritual',
					'type' => 'LONGTEXT'
				),
			);
			$this->dbforge->modify_column('deskripsi_sikap', $fields);
		}
		if (!$this->db->field_exists('uraian_deskripsi_sosial', 'deskripsi_sikap')){
			$fields = array(
	        	'uraian_deskripsi_sosial' => array(
				'type' 			=> 'LONGTEXT',
				)
			);
			$this->dbforge->add_column('deskripsi_sikap', $fields);
		}
        //login check 
		$exception_urls = array(
			'admin/auth',
		);
		
		if (in_array(uri_string(), $exception_urls) == FALSE) {
			if(!$this->ion_auth->logged_in()){
				redirect('admin/auth/');
			}
		}
	}
}