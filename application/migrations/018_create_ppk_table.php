<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ppk_table extends CI_Migration {
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
			'semester_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'id_kegiatan' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'nama_kegiatan'	=> array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'guru_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'penanggung_jawab'	=> array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'rombongan_belajar_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'posisi_id' => array(
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
		if (!$this->db->table_exists('ppk')){
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('ppk');
		}
	}
	public function down(){
		if (!$this->db->table_exists('ppk')){
			$this->dbforge->drop_table('ppk', TRUE);
		}
	}
}