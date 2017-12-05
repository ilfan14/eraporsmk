<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_teknik_penilaian_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('metodes')){
			if (!$this->db->field_exists('semester_id', 'metodes')){
				$fields = array(
					'nama_metode' => array(
							'name' => 'nama',
							'type' => 'VARCHAR',
							'constraint' => 100
					),
				);
				$this->dbforge->modify_column('metodes', $fields);
			}
			$this->dbforge->drop_column('metodes', 'ajaran_id');
			$this->dbforge->rename_table('metodes', 'teknik_penilaian');
		}
	}
	public function down(){
		if ($this->db->table_exists('teknik_penilaian')){
			$fields = array(
				'nama' => array(
					'name' => 'nama_metode',
					'type' => 'VARCHAR',
					'constraint' => 100
				),
			);
			$this->dbforge->modify_column('teknik_penilaian', $fields);
			if (!$this->db->field_exists('ajaran_id', 'teknik_penilaian')){
				$fields = array(
					'ajaran_id'	=> array(
						'type' => 'INT',
						'constraint' => 11,
						'default' => '0',
						'null' => FALSE
					),
				);
				$this->dbforge->add_column('teknik_penilaian', $fields);
			}
			$this->dbforge->rename_table('teknik_penilaian', 'metodes');
		}
	}
}