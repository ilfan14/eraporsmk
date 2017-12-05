<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Catatan_ppk_model extends MY_Model{
	public $_table = 'catatan_ppk';
	public $primary_key = 'id';
	public $belongs_to = array(
		'siswa' => array('model' => 'siswa_model', 'primary_key' => 'siswa_id')
	);//1 ke 1
}