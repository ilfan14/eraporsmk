<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_pembelajaran_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if ($this->db->table_exists('kurikulums')){
			if (!$this->db->field_exists('semester_id', 'kurikulums')){
				$fields = array(
					'ajaran_id' => array(
						'name' => 'semester_id',
						'type' => 'INT',
						'constraint' => 11
					),
					'data_rombel_id' => array(
						'name' => 'rombongan_belajar_id',
						'type' => 'INT',
						'constraint' => 11
					),
					'id_mapel' => array(
						'name' => 'mata_pelajaran_id',
						'type' => 'VARCHAR',
						'constraint' => 255
					),
					'nama_mapel_alias' => array(
						'name' => 'pembelajaran_id',
						'type' => 'VARCHAR',
						'constraint' => 255
					),
				);
				$this->dbforge->modify_column('kurikulums', $fields);
			}
			if (!$this->db->field_exists('created_at', 'kurikulums')){
				$new_fields = array(
					'created_at' => array(
						'type' => 'datetime'
					),
					'updated_at' => array(
						'type' => 'datetime'
					)
				);
				$this->dbforge->add_column('kurikulums', $new_fields);
			}
			$this->dbforge->rename_table('kurikulums', 'pembelajaran');
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
				'name' => 'data_rombel_id',
				'type' => 'INT',
				'constraint' => 11
			),
			'mata_pelajaran_id' => array(
				'name' => 'id_mapel',
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'pembelajaran_id' => array(
				'name' => 'nama_mapel_alias',
				'type' => 'VARCHAR',
				'constraint' => 255
			),
		);
		$this->dbforge->modify_column('pembelajaran', $fields);
		$this->dbforge->rename_table('pembelajaran', 'kurikulums');
	}
}