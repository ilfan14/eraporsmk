<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pembelajaran_model extends MY_Model{
	public $_table = 'pembelajaran';
	public $primary_key = 'id';
	public $belongs_to = array(
		'mata_pelajaran' => array('model' => 'mata_pelajaran_model', 'primary_key' => 'mata_pelajaran_id'),
	);//1 ke 1
}