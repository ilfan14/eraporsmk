<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_import_rencana_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if (!$this->db->table_exists('import_rencana')){
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
				'kompetensi_id' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'rombongan_belajar_id'	=> array(
					'type' => 'INT',
					'constraint' => 11
				),
				'mata_pelajaran_id'	=> array(
					'type' => 'INT',
					'constraint' => 11
				),
				'guru_id'	=> array(
					'type' => 'INT',
					'constraint' => 11
				),
				'created_at' => array(
					'type' => 'datetime'
				),
				'updated_at' => array(
					'type' => 'datetime'
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('import_rencana');
		}
	}
	public function down(){
		$this->dbforge->drop_table('import_rencana', TRUE);
	}
}