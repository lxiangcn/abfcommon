<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/**
 * FileName : Navigations_helper.php
 * DateTime : 2015年6月26日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
if (! function_exists ( 'nav' )) {

	function nav($nav_id, $config = array()) {
		$CI = & get_instance ();
		
		$CI->load->library ( 'navigations/navigations_library' );
		
		// Set the attribute id as the tag id
		if (isset ( $config ['id'] )) {
			$config ['tag_id'] = $config ['id'];
			unset ( $config ['id'] );
		}
		
		// Set the id as the config attribute id;
		$config ['id'] = $nav_id;
		
		$Navigations_library = new Navigations_library ();
		return $Navigations_library->list_nav ( $config );
	}
}

// ------------------------------------------------------------------------

/*
 * Breadcrumb
 * Builds a breadcrumb unordered list
 * @param int
 * @param array
 * @return string
 */
if (! function_exists ( 'breadcrumb' )) {

	function breadcrumb($id, $config = array()) {
		$CI = & get_instance ();
		
		$CI->load->library ( 'navigations/navigations_library' );
		
		// Set the attribute id as the tag id
		if (isset ( $config ['id'] )) {
			$config ['tag_id'] = $config ['id'];
			unset ( $config ['id'] );
		}
		
		// Set the id as the config attribute id;
		$config ['id'] = $id;
		
		$Navigations_library = new Navigations_library ();
		return $Navigations_library->breadcrumb ( $config );
	}
}
