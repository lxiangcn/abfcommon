<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

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
    public $vars = array();

    /**
     * Constructor
     *
     * @access public
     */
    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('user_agent');
        log_message('debug', "Template Class Initialized");
    }

    /*
     * Set
     * @param string
     * @param string
     * @return object
     */
    function set($name, $value) {
        $this->vars[$name] = $value;
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
        $tpl        = array();
        $this->vars = array_merge($vars, $this->vars);
        // Check for partials to load
        if (count($view) > 0) {
            // Load views into var array
            foreach ($view as $key => $file) {
                $this->set($key, $this->CI->load->view($file, $vars, TRUE));
            }
        }
        // Load master template
        return $this->CI->load->view($template, $this->vars, $return);
    }
}
