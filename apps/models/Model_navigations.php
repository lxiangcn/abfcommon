<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_navigations.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_navigations extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'navigations' );
	}
}

/* End of file navigations.php */
/* Location: ./application/model/navigations.php */ 