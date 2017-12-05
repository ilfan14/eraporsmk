<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_tingkat_pendidikan_table extends CI_Migration {
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
		$this->dbforge->create_table('ref_tingkat_pendidikan',TRUE); 
		$ref_tingkat_pendidikan = array(
  array('id' => '10','nama' => 'Kelas 10','created_at' => '2017-09-19 08:00:05','updated_at' => '2017-09-19 08:00:05'),
  array('id' => '11','nama' => 'Kelas 11','created_at' => '2017-09-19 08:00:05','updated_at' => '2017-09-19 08:00:05'),
  array('id' => '12','nama' => 'Kelas 12','created_at' => '2017-09-19 08:00:05','updated_at' => '2017-09-19 08:00:05'),
  array('id' => '13','nama' => 'Kelas 13','created_at' => '2017-09-19 08:00:05','updated_at' => '2017-09-19 08:00:05')
);
		$this->db->select('*');
		$this->db->from('ref_tingkat_pendidikan');
		$this->db->where('id',10);
		$query = $this->db->get();
		$result = $query->row();
		if(!$result){
			$this->db->insert_batch('ref_tingkat_pendidikan', $ref_tingkat_pendidikan);
		}
	}
	public function down(){
		$this->dbforge->drop_table('ref_tingkat_pendidikan', TRUE);
	}
}