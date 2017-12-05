<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_kompetensi_dasar_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('kds') && !$this->db->table_exists('ref_kompetensi_dasar')){
			if (!$this->db->field_exists('mata_pelajaran_id', 'kds')){
				$fields = array(
					'id_mapel' => array(
							'name' => 'mata_pelajaran_id',
							'type' => 'VARCHAR',
							'constraint' => 255
					),
				);
				$this->dbforge->modify_column('kds', $fields);
			}
			if (!$this->db->field_exists('excel_id', 'kds')){
				$new_fields = array(
					'excel_id' => array(
						'type' => 'INT',
						'constraint' => 11,
						'default' => '0',
						'null' => FALSE
					),
					'user_id' => array(
						'type' => 'INT',
						'constraint' => 11,
						'default' => '0',
						'null' => FALSE
					)
				);
				$this->dbforge->add_column('kds', $new_fields);
			}
			$this->dbforge->rename_table('kds', 'ref_kompetensi_dasar');
		}
	}
	public function down(){
		$fields = array(
			'mata_pelajaran_id' => array(
				'name' => 'id_mapel',
				'type' => 'VARCHAR',
				'constraint' => 255
			),
		);
		if ($this->db->table_exists('ref_kompetensi_dasar')){
			$this->dbforge->drop_column('ref_kompetensi_dasar', 'excel_id');
			$this->dbforge->drop_column('ref_kompetensi_dasar', 'user_id');
			$this->dbforge->modify_column('ref_kompetensi_dasar', $fields);
			$this->dbforge->rename_table('ref_kompetensi_dasar', 'kds');
		}
	}
}