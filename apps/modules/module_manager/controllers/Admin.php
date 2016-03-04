<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Admin extends Admin_Controller {

	public function __construct() {
		parent::__construct ();
		
		$this->load->library ( 'module_manager/module' );
	}

	/*
	 * We use index only for redirect
	 */
	function index() {
		$data['modules'] = $this->module->get_module_list ();
		$this->output ( "admin_layout", array(
			"body" => "admin/index" 
		), $data );
	}

}