<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_semester_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('ajarans')){
			if (!$this->db->field_exists('semester', 'ajarans')){
				$fields = array(
					'smt' => array(
							'name' => 'semester',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('ajarans', $fields);
			}
			$this->dbforge->rename_table('ajarans', 'ref_semester');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('ref_semester', TRUE);
		$fields = array(
			'semester' => array(
				'name' => 'smt',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('ref_semester', $fields);
		$this->dbforge->rename_table('ref_semester', 'ajarans');
	}
}