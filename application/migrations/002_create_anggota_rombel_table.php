<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_anggota_rombel_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('anggota_rombels') && !$this->db->table_exists('anggota_rombel')){
			if (!$this->db->field_exists('semester_id', 'anggota_rombels')){
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
					'datasiswa_id' => array(
							'name' => 'siswa_id',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('anggota_rombels', $fields);
			}
			$this->dbforge->rename_table('anggota_rombels', 'anggota_rombel');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('anggota_rombel', TRUE);
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
			'siswa_id' => array(
				'name' => 'datasiswa_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('anggota_rombel', $fields);
		$this->dbforge->rename_table('anggota_rombel', 'anggota_rombels');
	}
}