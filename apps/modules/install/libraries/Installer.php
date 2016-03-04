<?php

defined('BASEPATH') or die('No direct script access allowed');
/**
 *    abfcommon
 *
 * @package Installer
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-22 20:28:45
 * @author Alex Liu<lxiangcn@gmail.com>
 */

class Installer {

    public $CI;
    public $server;
    public $hostname;
    public $username;
    public $password;
    public $database;
    public $port;
    public $prefix;
    public $driver   = null;
    public $site_url = "admin";
    private $_conn   = null;

    public function __construct($config) {
        $this->CI       = &get_instance();
        $this->server   = $config['server'];
        $this->hostname = $config['db']['hostname'];
        $this->username = $config['db']['username'];
        $this->password = $config['db']['password'];
        $this->database = $config['db']['database'];
        $this->port     = $config['db']['port'];
        $this->prefix   = $config['db']['prefix'];
    }

    public function test_db_connection() {
        if (function_exists('mysqli_connect')) {
            $mysqli = @new mysqli($this->hostname, $this->username, $this->password, $this->database, $this->port);

            if ($mysqli->connect_errno) {
                throw new Exception("数据库连接错误: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
            }

            $mysqli->close();

            $this->driver = 'mysql';
        } else if (function_exists('mysql_connect')) {
            $link = @mysql_connect($this->hostname . ':' . $this->port, $this->username, $this->password);

            if (!$link) {
                throw new Exception('数据库连接错误: ' . mysql_error());
            }

            $db_selected = mysql_select_db($this->database, $link);

            if (!$db_selected) {
                throw new Exception('数据库连接错误: ' . mysql_error());
            }

            mysql_close($link);

            $this->driver = 'mysql';
        } else {
            throw new Exception('Unable to find MySQL on server.');
        }
    }

    public function write_db_config() {
        // Get database config template
        $template = file_get_contents(MODULES . 'install/assets/config/database.php');

        $replace = array(
            '__HOSTNAME__' => $this->hostname,
            '__USERNAME__' => $this->username,
            '__PASSWORD__' => $this->password,
            '__DATABASE__' => $this->database,
            '__PORT__'     => $this->port,
            '__DRIVER__'   => $this->driver,
            '__PREFIX__'   => $this->prefix,
        );

        $template = str_replace(array_keys($replace), $replace, $template);

        $handle = @fopen(APPPATH . 'config/database.php', 'w+');

        if ($handle !== FALSE) {
            $response = @fwrite($handle, $template);
            fclose($handle);

            if ($response) {
                return TRUE;
            }
        }

        throw new Exception('Failed to write to ' . APPPATH . 'config/database.php');
    }

    public function write_ci_config() {
        $this->encryption_key = md5(uniqid('', true));

        $enable_mod_rewrite = ($this->server == 'apache_w') ? TRUE : FALSE;

        $index_page = 'index.php';

        if ($enable_mod_rewrite !== FALSE) {
            $index_page = '';
        }

        // Get database config template
        $template = file_get_contents(APPPATH . 'assets/config/config.php');

        $replace = array(
            '__INDEX_PAGE__'     => $index_page,
            '__ENCRYPTION_KEY__' => $this->encryption_key,
            '__site_url__'       => $this->site_url,
        );

        $template = str_replace(array_keys($replace), $replace, $template);

        $handle = @fopen(CMS_ROOT . 'application/config/config.php', 'w+');

        if ($handle !== FALSE) {
            $response = @fwrite($handle, $template);
            fclose($handle);

            if ($response) {
                return TRUE;
            }
        }

        throw new Exception('Failed to write to ' . CMS_ROOT . 'application/config/config.php');
    }

    public function import_schema() {
        $file = MODULES . 'install/assets/dbscript/abfcommon_schema.sql';
        if ($sql = file($file)) {
            $query = '';
            foreach ($sql as $line) {
                $tsl = trim($line);

                if (($sql != '') && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != '#')) {
                    $query .= $line;

                    if (preg_match('/;\s*$/', $line)) {
                        $query = str_replace("DROP TABLE IF EXISTS `", "DROP TABLE IF EXISTS `" . $this->prefix, $query);
                        $query = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS `" . $this->prefix, $query);
                        $query = str_replace("CREATE TABLE `", "CREATE TABLE `" . $this->prefix, $query);
                        $query = str_replace("LOCK TABLES `", "LOCK TABLES `" . $this->prefix, $query);
                        $query = str_replace("INSERT INTO `", "INSERT INTO `" . $this->prefix, $query);

                        $this->db_query($query);
                        $query = '';
                    }
                }
            }
        }
    }

    /**
     * 设置安装锁文件
     */
    public function write_lock_file() {
        $template = "lock";

        $handle = @fopen(FCPATH . 'install.lock', 'w+');

        if ($handle !== FALSE) {
            $response = @fwrite($handle, $template);
            fclose($handle);
        }
    }

    public function insert_administrator($username, $password, $email) {
        $this->load->library('auth/admin_auth');
        $this->admin_auth->register($this->db_escape($username), $this->db_escape($password), $this->db_escape($email));
    }

    public function update_site_name($site_name) {
        $site_name_sql = "UPDATE `" . $this->prefix . "configs` SET `value` = '" . $this->db_escape($site_name) . "' WHERE `tag` = 'site_name'";
        $this->db_query($site_name_sql);
    }

    public function update_notification_email($notification_email) {
        $this->db_query("UPDATE `" . $this->prefix . "configs` SET `value` = '" . $this->db_escape($notification_email) . "' WHERE `tag` = 'admin_email'");
    }

    public function db_connect() {
        if (empty($this->driver)) {
            throw new Exception('Unable to determine which MySQL driver to use.');
        }

        if ($this->driver == 'mysqli') {
            $this->_conn = new mysqli($this->hostname, $this->username, $this->password, $this->database, $this->port);
            $this->_conn->set_charset("utf8");
        } else if ($this->driver == 'mysql') {
            $this->_conn = mysql_connect($this->hostname . ':' . $this->port, $this->username, $this->password);
            mysql_set_charset('utf8', $this->_conn);
            mysql_select_db($this->database, $this->_conn);
        }
    }

    public function db_query($query) {
        if ($this->driver == 'mysqli') {
            $result = $this->_conn->query($query);

            if (!$result) {
                throw new Exception('Invalid Query: ' . $this->_conn->error);
            }
        } else if ($this->driver == 'mysql') {
            $result = mysql_query($query, $this->_conn);

            if (!$result) {
                throw new Exception('Invalid Query: ' . mysql_error($this->_conn));
            }
        }
    }

    public function db_escape($string) {
        if ($this->driver == 'mysqli') {
            return $this->_conn->real_escape_string($string);
        } else if ($this->driver == 'mysql') {
            return mysql_real_escape_string($string, $this->_conn);
        }
    }

    public function db_close() {
        if ($this->driver == 'mysqli') {
            $this->_conn->close();
        } else if ($this->driver == 'mysql') {
            mysql_close($this->_conn);
        }
    }

}
