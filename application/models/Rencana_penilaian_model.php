<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rencana_penilaian_model extends MY_Model{
	public $_table = 'rencana_penilaian';
	public $primary_key = 'id';
	public $belongs_to = array(
		'mata_pelajaran' 	=> array('model' => 'mata_pelajaran_model', 'primary_key' => 'mata_pelajaran_id'),
		'semester' 			=> array('model' => 'semester_model', 'primary_key' => 'semester_id')
	);//1 ke 1
	public $has_many = array(
		'kd_nilai' => array('model' => 'kd_nilai_model', 'primary_key' => 'rencana_penilaian_id')
	);
}