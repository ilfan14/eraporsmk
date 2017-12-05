<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_nilai_table extends CI_Migration {
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
			'kompetensi_id'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'rombongan_belajar_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'mata_pelajaran_id'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'siswa_id'	=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'rencana_penilaian_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'kompetensi_dasar_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'kd_nilai_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'nilai' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'rerata' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'rerata_jadi' => array(
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
		if (!$this->db->table_exists('nilai')){
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('nilai');
		}
	}
	public function down(){
		if (!$this->db->table_exists('nilai')){
			$this->dbforge->drop_table('nilai', TRUE);
		}
	}
}