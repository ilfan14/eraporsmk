<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prestasi_model extends MY_Model{
	public $_table = 'prestasi';
	public $primary_key = 'id';
	public $belongs_to = array(
		'siswa' => array('model' => 'siswa_model', 'primary_key' => 'siswa_id'),
		'rombongan_belajar' => array('model' => 'rombongan_belajar_model', 'primary_key' => 'rombongan_belajar_id'),
	);//1 ke 1
}