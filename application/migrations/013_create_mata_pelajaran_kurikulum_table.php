<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_mata_pelajaran_kurikulum_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		$fields = array(
			'id' 				=> array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'kurikulum_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'mata_pelajaran_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'tingkat_pendidikan_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'a_peminatan' => array(
				'type' => 'VARCHAR',
				'constraint' => 10
			),
			'area_kompetensi' => array(
				'type' => 'VARCHAR',
				'constraint' => 10
			),
			'kelompok_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'created_at' => array(
				'type' => 'datetime'
			),
			'updated_at'	=> array(
				'type' => 'datetime'
			)
		);
		if (!$this->db->table_exists('mata_pelajaran_kurikulum')){
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('mata_pelajaran_kurikulum');
		}
	}
	public function down(){
		if (!$this->db->table_exists('mata_pelajaran_kurikulum')){
			$this->dbforge->drop_table('mata_pelajaran_kurikulum', TRUE);
		}
	}
}