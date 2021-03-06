<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_kurikulum_table extends CI_Migration {
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
			'nama_kurikulum' => array(
				'type' => 'TEXT',
			),
			'jurusan_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ref_kurikulum', TRUE);
	}
	public function down(){
		$this->dbforge->drop_table('ref_kurikulum', TRUE);
	}
}