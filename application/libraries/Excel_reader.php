<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
require_once APPPATH."/third_party/simplexlsx.class.php"; 
 
class Excel_reader extends SimpleXLSX { 
    public function __construct() { 
        parent::__construct(); 
    } 
}