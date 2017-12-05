<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_guru_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('data_gurus')){
			if (!$this->db->field_exists('semester_id', 'data_gurus')){
				$fields = array(
					'data_sekolah_id' => array(
							'name' => 'sekolah_id',
							'type' => 'INT',
							'constraint' => 11
					),
					'status_kepegawaian' => array(
							'name' => 'status_kepegawaian_id',
							'type' => 'INT',
							'constraint' => 11
					),
					'agama' => array(
							'name' => 'agama_id',
							'type' => 'INT',
							'constraint' => 11
					),
				);
				$this->dbforge->modify_column('data_gurus', $fields);
			}
			$this->dbforge->rename_table('data_gurus', 'ref_guru');
		}
	}
	public function down(){
		//$this->dbforge->drop_table('ref_guru', TRUE);
		$fields = array(
			'sekolah_id' => array(
				'name' => 'data_sekolah_id',
				'type' => 'INT',
				'constraint' => 11
			),
		);
		$this->dbforge->modify_column('ref_guru', $fields);
		$this->dbforge->rename_table('ref_guru', 'data_gurus');
	}
}