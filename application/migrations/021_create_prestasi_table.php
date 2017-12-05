<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_prestasi_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('prestasis')){
			if (!$this->db->field_exists('semester_id', 'prestasis')){
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
				$this->dbforge->modify_column('prestasis', $fields);
			}
			$this->dbforge->rename_table('prestasis', 'prestasi');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('prestasi', TRUE);
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
		$this->dbforge->modify_column('prestasi', $fields);
		$this->dbforge->rename_table('prestasi', 'prestasis');
	}
}