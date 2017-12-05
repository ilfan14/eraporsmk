<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_catatan_ppk_table extends CI_Migration {
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
			'ppk_id'		=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'siswa_id'		=> array(
				'type' => 'INT',
				'constraint' => 11
			),
			'ref_ppk_id'	=> array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'catatan'	=> array(
				'type' => 'TEXT'
			),
			'kegiatan'	=> array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'tempat'	=> array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'penanggung_jawab'	=> array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'waktu'	=> array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'capaian' => array(
				'type' => 'TEXT'
			),
			'created_at' => array(
				'type' => 'datetime'
			),
			'updated_at'	=> array(
				'type' => 'datetime'
			)
		);
		if (!$this->db->table_exists('catatan_ppk')){
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('catatan_ppk');
		}
	}
	public function down(){
		if (!$this->db->table_exists('catatan_ppk')){
			$this->dbforge->drop_table('catatan_ppk', TRUE);
		}
	}
}