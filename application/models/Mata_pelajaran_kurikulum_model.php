<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mata_pelajaran_kurikulum_model extends MY_Model{
	public $_table = 'mata_pelajaran_kurikulum';
	public $primary_key = 'id';
	public $belongs_to = array(
		'mata_pelajaran' => array('model' => 'mata_pelajaran_model', 'primary_key' => 'mata_pelajaran_id'),
		'kurikulum' => array('model' => 'kurikulum_model', 'primary_key' => 'kurikulum_id'),
	);//1 ke 1
}