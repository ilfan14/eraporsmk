<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rombongan_belajar_model extends MY_Model{
	public $_table = 'rombongan_belajar';
	public $primary_key = 'id';
	public $has_many = array(
		'pembelajaran' => array('model' => 'pembelajaran_model', 'primary_key' => 'rombongan_belajar_id'),
	);//1 ke 1
}