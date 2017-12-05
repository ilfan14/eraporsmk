<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_ppk_table extends CI_Migration {
	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}
	public function up(){
		$fields = array(
			'id' 				=> array(
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
			'updated_at'	=> array(
				'type' => 'datetime'
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ref_ppk', TRUE);
		$ref_ppk = array(
  array('id' => '1','nama' => 'Religius','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '2','nama' => 'Jujur','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '3','nama' => 'Toleren','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '4','nama' => 'Disiplin','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '5','nama' => 'Bekerja Keras','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '6','nama' => 'Kreatif','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '7','nama' => 'Mandiri','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '8','nama' => 'Demokratis','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '9','nama' => 'Rasa Ingin Tahu','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '10','nama' => 'Semangat Kebangsaan','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '11','nama' => 'Cinta Tanah Air','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '12','nama' => 'Menghargai Prestasi','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '13','nama' => 'Komunikatif','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '14','nama' => 'Cinta Damai','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '15','nama' => 'Gemar Membaca','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '16','nama' => 'Peduli Lingkungan','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '17','nama' => 'Peduli Sosial','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43'),
  array('id' => '18','nama' => 'Bertanggung Jawab','created_at' => '2017-09-27 00:01:43','updated_at' => '2017-09-27 00:01:43')
);
		$this->db->select('*');
		$this->db->from('ref_ppk');
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row();
		if(!$result){
			$this->db->insert_batch('ref_ppk', $ref_ppk);
		}
	}
	public function down(){
		$this->dbforge->drop_table('ref_ppk', TRUE);
	}
}