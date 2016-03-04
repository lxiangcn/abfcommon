<?php
class Home extends Web_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'Model_archive', 'archive', true );
	}
	
	/**
	 * 文章内容
	 *
	 * @param unknown $id        	
	 */
	public function show($id) {
		$id = intval ( $id );
		$obj = $this->archive->read ( $id );
		if (! $id || empty ( $obj )) {
			$this->error ( "没有该文章" );
			// redirect ( site_url ( '' ) );
		}
		$data ['data'] = $obj;
		$this->output ( "home_layout", array (
				"body" => "default/show" 
		), $data );
	}
}

