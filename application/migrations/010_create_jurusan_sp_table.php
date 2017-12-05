<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_jurusan_sp_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('keahlians') && !$this->db->table_exists('jurusan_sp')){
			if (!$this->db->field_exists('semester_id', 'keahlians')){
				$fields = array(
					'ajaran_id' => array(
						'name' => 'semester_id',
						'type' => 'INT',
						'constraint' => 11
					),
				);
				$this->dbforge->modify_column('keahlians', $fields);
			}
			if (!$this->db->field_exists('jurusan_sp_id', 'keahlians')){
				$new_fields = array(
					'jurusan_sp_id'	=> array(
						'type' => 'VARCHAR',
						'constraint' => 255,
						'null' => TRUE
					),
				);
				$this->dbforge->add_column('keahlians', $new_fields);
			}
			$this->dbforge->rename_table('keahlians', 'jurusan_sp');
		}
	}
	public function down(){
		$fields = array(
			'semester_id' => array(
				'name' => 'ajaran_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		if ($this->db->field_exists('jurusan_sp_id', 'jurusan_sp')){
				$this->dbforge->drop_column('jurusan_sp', 'jurusan_sp_id');
			}
		$this->dbforge->modify_column('jurusan_sp', $fields);
		$this->dbforge->rename_table('jurusan_sp', 'keahlians');
	}
}