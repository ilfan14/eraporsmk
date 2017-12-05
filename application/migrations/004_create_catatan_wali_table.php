<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_catatan_wali_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('catatan_walis') && !$this->db->table_exists('catatan_wali')){
			if (!$this->db->field_exists('semester_id', 'catatan_walis')){
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
				$this->dbforge->modify_column('catatan_walis', $fields);
			}
			$this->dbforge->rename_table('catatan_walis', 'catatan_wali');
		}
	}
	public function down(){
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
		$this->dbforge->modify_column('catatan_wali', $fields);
		$this->dbforge->rename_table('catatan_wali', 'catatan_walis');
	}
}