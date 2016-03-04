<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

class Welcome extends Web_Controller {

	/**
	 * home page
	 */
	function index() {
		$data ['title'] = "";
		
		$this->output ( "home_layout", array ("body" => "welcome/index" ), $data );
	}
}
