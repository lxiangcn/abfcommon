<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/**
 * FileName : Module.php
 * DateTime : 2015年5月7日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */

class Module {
	var $modules;
	var $modules_folder;
	var $CI;
	var $modules_locations = array(
			'modules'=>'../modules/'
	);
	
	/**
	 * core modules
	 *
	 * @var unknown
	 */
	var $_core_modules = array(
			'dashboard','api','auth','captcha','filemanager','module_manager',
			'setting','users'
	);

	function __construct() {
		$this->modules = array();
		$this->modules_folder = array();
		
		$this->CI = &get_instance ();
	}

	/*
	 * get valid module
	 */
	function get_module_list() {
		// get all module folders
		foreach ( $this->modules_locations as $dir => $value ) {
			$dir = APPPATH . $dir . DIRECTORY_SEPARATOR;
			$folders = opendir ( $dir );
			while ( ($entry = readdir ( $folders )) !== false ) {
				if ($entry != "." && $entry != "..") {
					if (is_dir ( $dir . $entry )) {
						$this->modules_folder[$dir][] = $entry;
					}
				}
			}
			closedir ( $folders );
		}
		
		// get module config only if it has info.php file
		foreach ( $this->modules_folder as $dir => $folders ) {
			foreach ( $folders as $folder ) {
				$handle = opendir ( $dir . $folder );
				while ( ($entry = readdir ( $handle )) !== false ) {
					if ($entry == "config") {
						if (is_file ( $dir . $folder . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'info.php' )) {
							$this->modules[$folder]['location'] = in_array ( $folder, $this->_core_modules ) ? 'core' : 'dev';
							$this->modules[$folder]['path'] = $dir . $folder . DIRECTORY_SEPARATOR;
							$this->modules[$folder] += $this->get_module_info ( $folder );
						} else {
							continue;
						}
					}
				}
				closedir ( $handle );
			}
		}
		return $this->modules;
	}

	function get_module_info($module, $path = false) {
		if ($path) {
			return ( array ) json_decode ( file_get_contents ( $path . 'info.php' ) );
		} else {
			$module_info = array();
			$this->CI->load->config ( $module . '/info' );
			$version = $this->CI->config->item ( 'version' );
			$module_info['version'] = $version;
			$module_name = $this->CI->config->item ( 'module_name' );
			$module_info['module_name'] = $module_name;
			$description = $this->CI->config->item ( 'description' );
			$module_info['description'] = $description;
			return $module_info;
		}
	}

	/*
	 * check the right asset location of file in module
	 */
	function module_location($module_name) {
		$right_path = false;
		foreach ( $this->modules_locations as $dir => $value ) {
			$dir = APPPATH . $dir;
			if (is_dir ( $dir . $module_name )) {
				$right_path = $dir . $module_name;
			}
		}
		return $right_path;
	}

	function module_asset_location($module_name, $file_path) {
		$right_path = false;
		foreach ( $this->modules_locations as $dir => $value ) {
			$dir = APPPATH . $dir;
			if (file_exists ( $dir . $module_name . '/' . $file_path )) {
				$right_path = $dir . $module_name . '/' . $file_path;
			}
		}
		return $right_path;
	}

}