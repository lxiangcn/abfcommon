<?php
defined('BASEPATH') || exit('No direct script access allowed');

class MY_Security extends CI_Security {
    private $_log_raw_data = false;
    /**
     * $_counter : counter of initialization/uninitialization
     *
     * @var boolean
     */
    private static $_counter = 0;

    /**
     * $_req_msg : request message
     *
     * @var string
     */
    private static $_req_msg = '';

    /**
     *
     * @var array Controllers to ignore during the CSRF cycle.
     *      If part of a module, the controller should be listed as:
     *      {module}/{controller}
     */
    protected $ignored_controllers = array();

    /**
     * The constructor
     *
     * @return void
     */
    public function __construct() {
        // decode json data
        if (0 == self::$_counter) {
            global $CFG;
            $http_mthd = (is_array($_SERVER) && isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : '';
            if (php_sapi_name() === 'cli') {
                global $argv, $argc;
                $uri = '';
                if ($argc > 1) {
                    $tmp = array_slice($argv, 1, $argc - 1);
                    $uri = implode(' ', $tmp);
                }
            } else {
                $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            }
            self::$_req_msg = $http_mthd . ' ' . $uri . '( ' . (isset($_COOKIE[$CFG->item('csrf_cookie_name')]) ? $_COOKIE[$CFG->item('csrf_cookie_name')] : '') . ' )';
            log_message('info', self::$_req_msg);

            parent::__construct();

            $this->ignored_controllers = $this->getIgnoredControllers();
        }
        self::$_counter++;
    }

    public function __destruct() {
        self::$_counter--;
        if (0 == self::$_counter) {
            log_message('info', self::$_req_msg);
        }
    }

    /**
     * Show CSRF Error
     * Override the csrf_show_error method to improve the error message
     *
     * @return void
     */
    public function csrf_show_error() {
        global $RTR;

        $module = $RTR->fetch_module();
        $class  = $RTR->fetch_class();
        $method = $RTR->fetch_method();
        $path   = $module;
        if (!empty($module)) {
            $path .= '/';
        }
        $path .= "{$class}/{$method}";
        log_message('error', '[No CSRF] : ' . $path);

        show_error('The action you have requested is not allowed.', 403);
    }

    /**
     * Verify Cross Site Request Forgery Protection
     * Override the csrf_verify method to allow us to set controllers and
     * modules to override.
     *
     * @return object Returns $this to allow method chaining
     */
    public function csrf_verify() {
        if (!empty($this->ignored_controllers)) {
            global $RTR;

            $module = $RTR->fetch_module();
            $class  = $RTR->fetch_class();
            $method = $RTR->fetch_method();
            $path   = $module;
            if (!empty($module)) {
                $path .= '/';
            }
            $path .= "{$class}/{$method}";

            if (isset($this->ignored_controllers[$module]) && isset($this->ignored_controllers[$module][$class])) {
                if (isset($this->ignored_controllers[$module][$class]["*"])) {
                    log_message('info', '[CSRF] : Current Access has been ignored : ' . $path);
                    return $this->csrf_set_cookie();
                }
                if (isset($this->ignored_controllers[$module][$class][$method]) && $this->ignored_controllers[$module][$class][$method] === "none") {
                    log_message('info', '[CSRF] : Current Access has been ignored : ' . $path);
                    return $this->csrf_set_cookie();
                }
            }
            // if (in_array($path, $this->ignored_controllers)) {
            //     // set cookie for ignored actions
            //     log_message('info', '[CSRF] : Current Access has been ignored : ' . $path);
            //     return $this->csrf_set_cookie();
            // }
        }

        // If it's not a POST request we will set the CSRF cookie
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            log_message('info', 'No CSRF check on non-POST request : ' . $_SERVER['REQUEST_METHOD']);
            return $this->csrf_set_cookie();
        }
        // Do the tokens exist in both the _POST and _COOKIE arrays?
        if (!isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name])) {
            log_message('error', 'No CSRF token in either _POST or _COOKIE');
            $this->csrf_show_error();
        }

        // Do the tokens match?
        if ($_POST[$this->_csrf_token_name] != $_COOKIE[$this->_csrf_cookie_name]) {
            log_message('error', 'CSRF token does not match : POST(' . $_POST[$this->_csrf_token_name] . ') != COOKIE(' . $_COOKIE[$this->_csrf_cookie_name] . ')');
            $this->csrf_show_error();
        }

        // We kill this since we're done and we don't want to
        // polute the _POST array
        unset($_POST[$this->_csrf_token_name]);

        // Nothing should last forever
        unset($_COOKIE[$this->_csrf_cookie_name]);
        $this->_csrf_set_hash();
        log_message('debug', 'CSRF token verified');

        return $this->csrf_set_cookie();
    }

    /**
     * getIgnoredControllers : get ignored controller list
     * [POST]
     * @return array ignored controller list
     */
    private function getIgnoredControllers() {
        $getIgnoredControllers['home']['home']['index']            = 'none';
        $getIgnoredControllers['filemanager']['file']['webupload'] = 'none';
        $getIgnoredControllers['api']['example']['user']           = 'none';

        return $getIgnoredControllers;
        /*return [    'home/home/index',    'filemanager/file/webupload',    ];*/
    }
}
