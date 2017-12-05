<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_agama_table extends CI_Migration {
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
			'nama' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
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
		$this->dbforge->add_key('nama');
		$this->dbforge->create_table('ref_agama',TRUE); 
		$ref_agama = array(
			array('id' => '1','nama' => 'Islam','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '2','nama' => 'Kristen','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '3','nama' => 'Katholik','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '4','nama' => 'Hindu','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '5','nama' => 'Budha','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '6','nama' => 'Kong Hu Chu','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '7','nama' => 'Kepercayaan kpd Tuhan YME','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '98','nama' => 'Tidak diisi','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21'),
			array('id' => '99','nama' => 'Lainnya','created_at' => '2017-09-19 01:15:21','updated_at' => '2017-09-19 01:15:21')
		);
		$this->db->select('*');
		$this->db->from('ref_agama');
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row();
		if(!$result){
			$this->db->insert_batch('ref_agama', $ref_agama);
		}
	}
	public function down(){
		$this->dbforge->drop_table('ref_agama', TRUE);
	}
}