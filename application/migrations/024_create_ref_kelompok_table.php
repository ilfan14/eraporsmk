<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_kelompok_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'nama_kelompok' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
      		'kurikulum' => array(
				'type' => 'INT',
				'constraint' => 11,
			),
			'created_at' => array(
				'type' => 'datetime'
			),
			'updated_at' => array(
				'type' => 'datetime'
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id',TRUE);
		$this->dbforge->create_table('ref_kelompok',TRUE); 
		$ref_kelompok = array(
  array('id' => '1','nama_kelompok' => 'Kelompok A (Wajib)','kurikulum' => '2013','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '2','nama_kelompok' => 'Kelompok B (Wajib)','kurikulum' => '2013','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '3','nama_kelompok' => 'Kelompok C1 (Dasar Bidang Keahlian)','kurikulum' => '2013','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '4','nama_kelompok' => 'Kelompok C2 (Dasar Program Keahlian)','kurikulum' => '2013','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '5','nama_kelompok' => 'Kelompok C3 (Paket Keahlian)','kurikulum' => '2013','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '6','nama_kelompok' => 'Kelompok A (Wajib)','kurikulum' => '2017','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '7','nama_kelompok' => 'Kelompok B (Wajib)','kurikulum' => '2017','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '8','nama_kelompok' => 'Kelompok C1 (Dasar Bidang Keahlian)','kurikulum' => '2017','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '9','nama_kelompok' => 'Kelompok C2 (Dasar Program Keahlian)','kurikulum' => '2017','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '10','nama_kelompok' => 'Kelompok C3 (Paket Keahlian)','kurikulum' => '2017','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '11','nama_kelompok' => 'Normatif','kurikulum' => '2006','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '12','nama_kelompok' => 'Adaptif','kurikulum' => '2006','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '13','nama_kelompok' => 'Produktif','kurikulum' => '2006','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  /*array('id' => '13','nama_kelompok' => 'Kelompok C1 (Produktif Dasar Bidang Keahlian)','kurikulum' => '2006','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '14','nama_kelompok' => 'Kelompok C2 (Produktif Dasar Program Keahlian)','kurikulum' => '2006','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),
  array('id' => '15','nama_kelompok' => 'Kelompok C3 (Produktif Paket Keahlian)','kurikulum' => '2006','created_at' => '2017-09-19 10:47:50','updated_at' => '2017-09-19 10:47:50'),*/
  array('id' => '99','nama_kelompok' => 'Muatan Lokal','kurikulum' => '0','created_at' => '2017-09-27 00:00:00','updated_at' => '2017-09-27 00:00:00')
);
		$this->db->select('*');
		$this->db->from('ref_kelompok');
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row();
		if(!$result){
			$this->db->insert_batch('ref_kelompok', $ref_kelompok);
		}
	}
	public function down(){
		$this->dbforge->drop_table('ref_kelompok', TRUE);
	}
}