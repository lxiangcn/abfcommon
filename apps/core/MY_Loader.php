<?php

defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : MY_Loader.php
 * DateTime : UTF-8,21:47:53,2014-5-17
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description : 
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
/* load the MX_Loader class */
require APPPATH . "third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

	public function __construct() {
		parent::__construct ();
	}

	/**
	 * 获取CI 加载view路径
	 *
	 * @return multitype:
	 */
	public function get_ci_view_paths() {
		return $this->_ci_view_paths;
	}

	/*
	 * Theme
	 *
	 * Includes and eval a php file located in the themes directory
	 * Works the same as the MVC view just different path
	 *
	 * @param string
	 * @param array
	 * @param bool
	 * @param string
	 * @return sring
	 */
	public function theme($view, $vars = array(), $return = FALSE, $path = 'theme') {
		$absolute_path = FCPATH . trim ( $path, '/' ) . '/';
		
		$this->_ci_view_paths = array(
			$absolute_path => TRUE 
		) + $this->_ci_view_paths;
		
		return $this->_ci_load ( array(
			'_ci_view' => $view,
			'_ci_vars' => $this->_ci_object_to_array ( $vars ),
			'_ci_return' => $return 
		) );
	}

}