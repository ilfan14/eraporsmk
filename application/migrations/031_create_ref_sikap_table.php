<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_sikap_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('data_sikaps')){
			if (!$this->db->field_exists('semester_id', 'data_sikaps')){
				$fields = array(
					'ajaran_id' => array(
							'name' => 'semester_id',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('data_sikaps', $fields);
			}
			$this->dbforge->rename_table('data_sikaps', 'ref_sikap');
		}
	}
	public function down(){
		if ($this->db->table_exists('ref_sikap')){
			$fields = array(
				'semester_id' => array(
					'name' => 'ajaran_id',
					'type' => 'INT',
					'constraint' => 11
				),
			);
			$this->dbforge->modify_column('ref_sikap', $fields);
			$this->dbforge->rename_table('ref_sikap', 'data_sikaps');
		}
	}
}