<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_deskripsi_sikap_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('deskripsi_sikaps') && !$this->db->table_exists('deskripsi_sikap')){
			if (!$this->db->field_exists('semester_id', 'deskripsi_sikaps')){
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
				$this->dbforge->modify_column('deskripsi_sikaps', $fields);
			}
			$this->dbforge->rename_table('deskripsi_sikaps', 'deskripsi_sikap');
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
		$this->dbforge->modify_column('deskripsi_sikap', $fields);
		$this->dbforge->rename_table('deskripsi_sikap', 'deskripsi_sikaps');
	}
}