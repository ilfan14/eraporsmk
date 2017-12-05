<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_nilai_ekstrakurikuler_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('nilai_ekskuls')){
			if (!$this->db->field_exists('semester_id', 'nilai_ekskuls')){
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
					'ekskul_id' => array(
						'name' => 'ekstrakurikuler_id',
						'type' => 'INT',
						'constraint' => 11
					),
				);
				$this->dbforge->modify_column('nilai_ekskuls', $fields);
			}
			$this->dbforge->rename_table('nilai_ekskuls', 'nilai_ekstrakurikuler');
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
			'ekstrakurikuler_id' => array(
				'name' => 'ekskul_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('nilai_ekstrakurikuler', $fields);
		$this->dbforge->rename_table('nilai_ekstrakurikuler', 'nilai_ekskuls');
	}
}