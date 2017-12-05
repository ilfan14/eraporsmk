<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gelar_ptk_model extends MY_Model{
	public $_table = 'gelar_ptk';
	public $primary_key = 'id';
	public $belongs_to = array( 'gelar' => array('model' => 'gelar_model', 'primary_key' => 'gelar_akademik_id'));//1 ke 1
}