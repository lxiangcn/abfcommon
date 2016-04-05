<?php    
class Model_member extends MY_Model{
	public function __construct(){
	  parent::__construct();
	  $this->load_table('member');		
	}	
}