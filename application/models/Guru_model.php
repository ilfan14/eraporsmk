<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guru_model extends MY_Model{
	//protected $deleted_at_key = 'deleted_at';
	public $_table = 'ref_guru';
	public $primary_key = 'id';
	//public $belongs_to = array( 'pembelajaran' => array( 'model' => 'pembelajaran_model' ) ); //1 ke 1
    //public $belongs_to = array( 'gelar_ptk' => array('model' => 'gelar_ptk_model', 'primary_key' => 'guru_id'));//1 ke 1
    //public $has_many = array( 'comments' => array( 'primary_key' => 'parent_post_id' ) );//1 ke banyak
	public $has_many = array( 
			'pembelajaran' => array('model' => 'pembelajaran_model', 'primary_key' => 'guru_id'),
			'gelar_ptk' => array('model' => 'gelar_ptk_model', 'primary_key' => 'guru_id'),
	);//1 ke banyak
}