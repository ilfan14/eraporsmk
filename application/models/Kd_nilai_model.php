<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kd_nilai_model extends MY_Model{
	public $_table = 'kd_nilai';
	public $primary_key = 'id';
	public $belongs_to = array(
		'kompetensi_dasar' => array('model' => 'kompetensi_dasar_model', 'primary_key' => 'kompetensi_dasar_id'),
	);//1 ke 1
}