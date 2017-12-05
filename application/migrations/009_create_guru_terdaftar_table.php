<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_guru_terdaftar_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('guru_aktifs') && !$this->db->table_exists('guru_terdaftar')){
			if (!$this->db->field_exists('semester_id', 'guru_aktifs')){
				$fields = array(
					'ajaran_id' => array(
						'name' => 'semester_id',
						'type' => 'INT',
						'constraint' => 11
					),
					'dataguru_id' => array(
						'name' => 'guru_id',
						'type' => 'INT',
						'constraint' => 11
					),
				);
				$this->dbforge->modify_column('guru_aktifs', $fields);
			}
			$this->dbforge->rename_table('guru_aktifs', 'guru_terdaftar');
		}
	}
	public function down(){
		$fields = array(
			'semester_id' => array(
				'name' => 'ajaran_id',
				'type' => 'INT',
				'constraint' => 11
			),
			'guru_id' => array(
				'name' => 'dataguru_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('guru_terdaftar', $fields);
		$this->dbforge->rename_table('guru_terdaftar', 'guru_aktifs');
	}
}