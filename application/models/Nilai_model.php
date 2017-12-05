<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nilai_model extends MY_Model{
	public $_table = 'nilai';
	public $primary_key = 'id';
	public $belongs_to = array(
		'rencana_penilaian' => array('model' => 'rencana_penilaian_model', 'primary_key' => 'rencana_penilaian_id')
	);//1 ke 1
}