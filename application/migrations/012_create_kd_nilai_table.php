<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_kd_nilai_table extends CI_Migration {
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
			'rencana_penilaian_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'kd_id'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'id_kompetensi' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
		);
		if (!$this->db->table_exists('kd_nilai')){
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('kd_nilai');
		}
	}
	public function down(){
		if (!$this->db->table_exists('kd_nilai')){
			$this->dbforge->drop_table('kd_nilai', TRUE);
		}
	}
}