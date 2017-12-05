<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ppk_model extends MY_Model{
	public $_table = 'ppk';
	public $primary_key = 'id';
	public $belongs_to = array(
		'guru' => array('model' => 'guru_model', 'primary_key' => 'guru_id'),
		'rombongan_belajar' => array('model' => 'rombongan_belajar_model', 'primary_key' => 'rombongan_belajar_id'),
	);//1 ke 1
}