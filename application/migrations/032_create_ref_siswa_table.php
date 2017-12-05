<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_siswa_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('data_siswas')){
			if (!$this->db->field_exists('sekolah_id', 'data_siswas')){
				$fields = array(
					'data_sekolah_id' => array(
							'name' => 'sekolah_id',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('data_siswas', $fields);
			}
			$this->dbforge->rename_table('data_siswas', 'ref_siswa');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('ref_siswa', TRUE);
		$fields = array(
			'sekolah_id' => array(
				'name' => 'data_sekolah_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('ref_siswa', $fields);
		$this->dbforge->rename_table('ref_siswa', 'data_siswas');
	}
}