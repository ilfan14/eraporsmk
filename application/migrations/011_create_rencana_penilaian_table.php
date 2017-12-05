<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_rencana_penilaian_table extends CI_Migration {
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
			'mata_pelajaran_id'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'rombongan_belajar_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'kompetensi_id'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'nama_penilaian' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'metode_id'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'bobot'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'keterangan' => array(
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
		if (!$this->db->table_exists('rencana_penilaian')){
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('rencana_penilaian');
		}
	}
	public function down(){
		if (!$this->db->table_exists('rencana_penilaian')){
			$this->dbforge->drop_table('rencana_penilaian', TRUE);
		}
	}
}