<?php

defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );
define ( 'SECURITY', 2 );

class Contactus extends Web_Controller {

	function __construct() {
		parent::__construct ();
	}

	function index() {
		$this->output ( "home_layout", array(
			'body' => 'contactus/index' 
		), $item );
	}

}

?>
