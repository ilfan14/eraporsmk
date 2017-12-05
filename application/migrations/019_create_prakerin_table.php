<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_prakerin_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('prakerins')){
			if (!$this->db->field_exists('semester_id', 'prakerins')){
				$fields = array(
					'ajaran_id' => array(
							'name' => 'semester_id',
							'type' => 'INT',
							'constraint' => 11
					),
					'rombel_id' => array(
							'name' => 'rombongan_belajar_id',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('prakerins', $fields);
			}
			$this->dbforge->rename_table('prakerins', 'prakerin');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('prakerin', TRUE);
		$fields = array(
			'semester_id' => array(
				'name' => 'ajaran_id',
				'type' => 'INT',
				'constraint' => 11
			),
			'rombongan_belajar_id' => array(
				'name' => 'rombel_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('prakerin', $fields);
		$this->dbforge->rename_table('prakerin', 'prakerins');
	}
}