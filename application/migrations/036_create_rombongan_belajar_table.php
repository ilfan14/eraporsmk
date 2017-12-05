<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_rombongan_belajar_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('data_rombels')){
			if (!$this->db->field_exists('semester_id', 'data_rombels')){
				$fields = array(
					'ajaran_id' => array(
							'name' => 'semester_id',
							'type' => 'INT',
							'constraint' => 11
					),
					'data_sekolah_id' => array(
							'name' => 'sekolah_id',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('data_rombels', $fields);
			}
			if (!$this->db->field_exists('jurusan_sp_id', 'data_rombels')){
				$new_fields = array(
					'jurusan_sp_id'	=> array(
						'type' => 'INT',
						'constraint' => 255,
					),
					'created_at' => array(
						'type' => 'datetime'
					),
					'updated_at'	=> array(
						'type' => 'datetime'
					)
				);
				$this->dbforge->add_column('data_rombels', $new_fields);
			}
			$this->dbforge->drop_column('data_rombels', 'ts');
			$this->dbforge->rename_table('data_rombels', 'rombongan_belajar');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('rombongan_belajar', TRUE);
		$fields = array(
			'semester_id' => array(
				'name' => 'ajaran_id',
				'type' => 'INT',
				'constraint' => 11
			),
			'sekolah_id' => array(
				'name' => 'data_sekolah_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		//$this->dbforge->drop_column('jurusan_sp_id', 'ts');
		//$this->dbforge->drop_column('created_at', 'ts');
		//$this->dbforge->drop_column('updated_at', 'ts');
		$this->dbforge->modify_column('rombongan_belajar', $fields);
		$this->dbforge->rename_table('rombongan_belajar', 'data_rombels');
	}
}