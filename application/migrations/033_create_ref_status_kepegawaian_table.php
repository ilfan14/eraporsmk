<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_status_kepegawaian_table extends CI_Migration {
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
		$this->dbforge->create_table('ref_status_kepegawaian',TRUE); 
		$ref_status_kepegawaian = array(
  array('id' => '1','nama' => 'PNS','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '2','nama' => 'PNS Diperbantukan','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '3','nama' => 'PNS Depag','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '4','nama' => 'GTY/PTY','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '5','nama' => 'Honor Daerah TK.I Provinsi','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '6','nama' => 'Honor Daerah TK.II Kab/Kota','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '7','nama' => 'Guru Bantu Pusat','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '8','nama' => 'Guru Honor Sekolah','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '9','nama' => 'Tenaga Honor Sekolah','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '10','nama' => 'CPNS','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '51','nama' => 'Kontrak Kerja WNA','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46'),
  array('id' => '99','nama' => 'Lainnya','created_at' => '2017-09-19 01:10:46','updated_at' => '2017-09-19 01:10:46')
);
		$this->db->select('*');
		$this->db->from('ref_status_kepegawaian');
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row();
		if(!$result){
			$this->db->insert_batch('ref_status_kepegawaian', $ref_status_kepegawaian);
		}
	}
	public function down(){
		$this->dbforge->drop_table('ref_status_kepegawaian', TRUE);
	}
}