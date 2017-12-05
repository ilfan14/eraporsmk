<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_user_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if (!$this->db->field_exists('sekolah_id', 'users')){
			$fields = array(
				'data_sekolah_id' => array(
					'name' => 'sekolah_id',
					'type' => 'INT',
					'constraint' => 11
				),
				'data_guru_id' => array(
					'name' => 'guru_id',
					'type' => 'INT',
					'constraint' => 11
				),
				'data_siswa_id' => array(
					'name' => 'siswa_id',
					'type' => 'INT',
					'constraint' => 11
				),
			);
			$this->dbforge->modify_column('users', $fields);
		}
	}
	public function down(){
		//$this->dbforge->drop_table('ref_guru', TRUE);
		if ($this->db->field_exists('sekolah_id', 'users')){
			$fields = array(
				'sekolah_id' => array(
					'name' => 'data_sekolah_id',
					'type' => 'INT',
					'constraint' => 11
				),
				'guru_id' => array(
					'name' => 'data_guru_id',
					'type' => 'INT',
					'constraint' => 11
				),
				'siswa_id' => array(
					'name' => 'data_siswa_id',
					'type' => 'INT',
					'constraint' => 11
				),
			);
			$this->dbforge->modify_column('users', $fields);
		}
	}
}