<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_sekolah_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		if (!$this->db->table_exists('ref_sekolah')){
			if (!$this->db->field_exists('guru_id', 'data_sekolahs')){
				$fields = array(
					'guru_id' => array(
						'type' => 'INT',
						'constraint' => 11,
					),
					'kop_surat' => array(
						'type' => 'LONGTEXT',
					),
					'pengantar_ppk' => array(
						'type' => 'LONGTEXT',
					),
				);
				$this->dbforge->add_column('data_sekolahs', $fields);
			}
			$this->dbforge->rename_table('data_sekolahs', 'ref_sekolah');
		}
	}
	public function down(){
		if (!$this->db->table_exists('data_sekolahs')){
			if (!$this->db->field_exists('guru_id', 'ref_sekolah')){
				$this->dbforge->drop_column('ref_sekolah', 'guru_id');
			}
			if (!$this->db->field_exists('kop_surat', 'ref_sekolah')){
				$this->dbforge->drop_column('ref_sekolah', 'kop_surat');
			}
			if (!$this->db->field_exists('pengantar_ppk', 'ref_sekolah')){
				$this->dbforge->drop_column('ref_sekolah', 'pengantar_ppk');
			}
			$this->dbforge->rename_table('ref_sekolah', 'data_sekolahs');
		}
	}
}