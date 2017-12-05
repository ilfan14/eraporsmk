<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Siswa_model extends MY_Model{
	public $_table = 'ref_siswa';
	public $primary_key = 'id';
	public $belongs_to = array(
								'user' => array('model' => 'user_model', 'primary_key' => 'siswa_id'),
								);//1 ke 1
}