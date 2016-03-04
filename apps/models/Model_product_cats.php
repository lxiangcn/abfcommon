<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_product_cats.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_product_cats extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'product_cats' );
	}
}

/* End of file Model_products_cats.php */
/* Location: ./application/model/Model_products_cats.php */ 