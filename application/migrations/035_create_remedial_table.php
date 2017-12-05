<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_remedial_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('remedials')){
			if (!$this->db->field_exists('semester_id', 'remedials')){
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
					'mapel_id' => array(
							'name' => 'mata_pelajaran_id',
							'type' => 'VARCHAR',
							'constraint' => 255
					),
					'data_siswa_id' => array(
							'name' => 'siswa_id',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('remedials', $fields);
			}
			$this->dbforge->rename_table('remedials', 'remedial');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('remedial', TRUE);
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
			'mata_pelajaran_id' => array(
				'name' => 'mapel_id',
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'siswa_id' => array(
				'name' => 'data_siswa_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('remedial', $fields);
		$this->dbforge->rename_table('remedial', 'remedials');
	}
}