<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_nilai_sikap_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('sikaps')){
			if (!$this->db->field_exists('semester_id', 'sikaps')){
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
				);
				$this->dbforge->modify_column('sikaps', $fields);
			}
			$this->dbforge->rename_table('sikaps', 'nilai_sikap');
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
			'mata_pelajaran_id' => array(
				'name' => 'mapel_id',
				'type' => 'VARCHAR',
				'constraint' => 255
			),
		);
		$this->dbforge->modify_column('nilai_sikap', $fields);
		$this->dbforge->rename_table('nilai_sikap', 'sikaps');
	}
}