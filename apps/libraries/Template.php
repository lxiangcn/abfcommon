<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/**
 * FileName : Template.php
 * DateTime : 2015年4月20日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Template {
	private $CI;
	
	/**
	 *
	 * @var array
	 */
	public $vars = array ();
	/**
	 * 检查手机访问
	 *
	 * @var boolean
	 */
	public $is_mobile = FALSE;
	
	/**
	 * 是否开启双模
	 *
	 * @var boolean
	 */
	public $is_mobile_switch = TRUE;
	
	/**
	 * 开启双模版后的手机模板文件夹
	 *
	 * @var string
	 */
	public $mobile_view_path = "mobile";

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct() {
		$this->CI = & get_instance ();
		$this->CI->load->library ( 'user_agent' );
		log_message ( 'debug', "Template Class Initialized" );
	}

	/*
	 * 设置为手机访问
	 * @param boolean
	 * @return object
	 */
	public function set_mobile($is_mobile = FALSE) {
		$this->is_mobile = $is_mobile;
		return $this;
	}

	/*
	 * Set
	 * @param string
	 * @param string
	 * @return object
	 */
	function set($name, $value) {
		$this->vars [$name] = $value;
		return $this;
	}

	/*
	 * 设置为手机访问
	 * @param boolean
	 * @return object
	 */
	public function set_mobile_view_path($mobile_view_path = "mobile") {
		$this->mobile_view_path = $mobile_view_path;
		return $this;
	}

	/**
	 * Load template
	 *
	 * @access public
	 * @param String
	 * @param Array
	 * @param Array
	 * @param bool
	 * @return parsed view
	 */
	function load($template = '', $view = array(), $vars = array(), $return = FALSE) {
		$tpl = array ();
		$this->vars = array_merge ( $vars, $this->vars );
		$this->is_mobile = $this->CI->agent->is_mobile ();
		// Check for partials to load
		if (count ( $view ) > 0) {
			// Load views into var array
			foreach ( $view as $key => $file ) {
				$this->set ( $key, $this->CI->load->view ( $this->_set_view_folder ( $file ), $vars, TRUE ) );
			}
		}
		// Load master template
		return $this->CI->load->view ( $this->_set_view_folder ( $template ), $this->vars, $return );
	}

	/**
	 * find layout files, they could be mobile or web
	 *
	 * @return string
	 */
	private function _set_view_folder($view_folder = NULL) {
		if ($this->is_mobile_switch === TRUE) {
			// Would they like the mobile version?
			if ($this->is_mobile === TRUE) {
				// Use mobile as the base location for views
				$view_folder = $this->mobile_view_path . DIRECTORY_SEPARATOR . $view_folder;
			}
		}
		return $view_folder;
	}
}
