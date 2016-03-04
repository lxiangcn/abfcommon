<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Test.php
 * DateTime : 2015年10月30日
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Test extends Other_Controller {

	function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->library ( 'Excel' );
	}

	public function index() {
		// $data = array (array ('username','nickname','email' ),array ('刘翔','飞人','15612345678' ),array ('刘翔','飞人','15612345678' ),array ('刘翔','飞人','15612345678' ),array ('刘翔','飞人','15612345678' ) );
		// $this->excel->write ( $data );
		$file = FCPATH . "2015110217608498.xlsx";
		$data = $this->excel->read ( $file );
		print_r ( '<pre/>' );
		print_r ( $data );
	}

	public function webupload() {
		$this->load->view ( "test" );
	}
}