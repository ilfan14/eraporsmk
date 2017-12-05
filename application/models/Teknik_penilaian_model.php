<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Teknik_penilaian_model extends MY_Model{
	public $_table = 'teknik_penilaian';
	public $primary_key = 'id';
	public $belongs_to = array(
		'semester' => array('model' => 'semester_model', 'primary_key' => 'semester_id'),
	);//1 ke 1
}