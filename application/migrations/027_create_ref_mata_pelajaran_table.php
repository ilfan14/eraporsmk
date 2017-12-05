<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_mata_pelajaran_table extends CI_Migration {
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
			'id_nasional' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'jurusan_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'nama_mata_pelajaran' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'nama_mata_pelajaran_alias' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'created_at' => array(
				'type' => 'datetime'
			),
			'updated_at'	=> array(
				'type' => 'datetime'
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ref_mata_pelajaran', TRUE);
	}
	public function down(){
		$this->dbforge->drop_table('ref_mata_pelajaran', TRUE);
	}
}