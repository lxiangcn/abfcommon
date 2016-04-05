<?php    
class Model_groups extends MY_Model {
	function  __construct(){
		parent ::__construct();
		$this->load_table('groups');
	} 
	
	
	
}
