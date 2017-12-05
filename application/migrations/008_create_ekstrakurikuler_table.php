<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ekstrakurikuler_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('ekskuls') && !$this->db->table_exists('ekstrakurikuler')){
			if (!$this->db->field_exists('semester_id', 'ekskuls')){
				$fields = array(
					'ajaran_id' => array(
						'name' => 'semester_id',
						'type' => 'INT',
						'constraint' => 11
					),
				);
				$this->dbforge->modify_column('ekskuls', $fields);
			}
			$this->dbforge->rename_table('ekskuls', 'ekstrakurikuler');
		}
	}
	public function down(){
		$fields = array(
			'semester_id' => array(
				'name' => 'ajaran_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('ekstrakurikuler', $fields);
		$this->dbforge->rename_table('ekstrakurikuler', 'ekskuls');
	}
}