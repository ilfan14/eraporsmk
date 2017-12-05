<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_data_excel_table extends CI_Migration {
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
			'file' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
      		'status' => array(
				'type' => 'INT',
				'constraint' => 11
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
		$this->dbforge->create_table('data_excel',TRUE); 
	}
	public function down(){
		$this->dbforge->drop_table('data_excel', TRUE);
	}
}