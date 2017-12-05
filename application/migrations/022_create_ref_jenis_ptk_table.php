<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_ref_jenis_ptk_table extends CI_Migration {
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
		$this->dbforge->create_table('ref_jenis_ptk',TRUE); 
		$ref_jenis_ptk = array(
			array('id' => '3','nama' => 'Guru Kelas','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '4','nama' => 'Guru Mapel','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '5','nama' => 'Guru BK','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '6','nama' => 'Guru Inklusi','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '7','nama' => 'Pengawas Satuan Pendidikan','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '8','nama' => 'Pengawas PLB','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '9','nama' => 'Pengawas Metpel/Rumpun','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '10','nama' => 'Pengawas Bidang','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '11','nama' => 'Tenaga Administrasi Sekolah','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '12','nama' => 'Guru Pendamping','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '13','nama' => 'Guru Magang','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '14','nama' => 'Guru TIK','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '20','nama' => 'Kepala Sekolah','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '25','nama' => 'Pengawas BK','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '26','nama' => 'Pengawas SD','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '30','nama' => 'Laboran','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '40','nama' => 'Tenaga Perpustakaan','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '41','nama' => 'Tukang Kebun','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '42','nama' => 'Penjaga Sekolah','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '43','nama' => 'Petugas Keamanan','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '44','nama' => 'Pesuruh/Office Boy','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '51','nama' => 'Academic Advisor','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '52','nama' => 'Academic Specialist','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '53','nama' => 'Curriculum Development Advisor','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '54','nama' => 'Kindergarten Teacher','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '55','nama' => 'Management Advisor','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '56','nama' => 'Play Group Teacher','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '57','nama' => 'Principal','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '58','nama' => 'Teaching Assistant','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '59','nama' => 'Vice Principal','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04'),
			array('id' => '99','nama' => 'Lainnya','created_at' => '2017-09-19 01:08:04','updated_at' => '2017-09-19 01:08:04')
		);
		$this->db->select('*');
		$this->db->from('ref_jenis_ptk');
		$this->db->where('id',3);
		$query = $this->db->get();
		$result = $query->row();
		if(!$result){
			$this->db->insert_batch('ref_jenis_ptk', $ref_jenis_ptk);
		}
	}
	public function down(){
		$this->dbforge->drop_table('ref_jenis_ptk', TRUE);
	}
}