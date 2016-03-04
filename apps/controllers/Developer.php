<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Developer
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-26 21:33:08
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Developer extends MX_Controller {

    /**
     * Run migrations defined in migration folder
     *
     * @param none
     * @return none
     */
    public function index() {
        if ($this->input->is_cli_request()) {
            $this->load->library('migration');
            $this->load->config('migration');
            if (!$this->migration->current()) {
                echo '[ERROR]: ' . $this->migration->error_string();
            } else {
                echo 'Migration(s) done : ' . $this->config->item('migration_version') . PHP_EOL;
            }
        } else {
            echo '[ERROR]: You don\'t have permission for this action';
        }
    }

    /**
     * create/update database schema from doctrine entities
     *
     * @param type $mode
     * @return type
     */
    function db_schema($mode = "update") {
        if ($this->input->is_cli_request()) {
            if (!$this->doctrine->create_update_database($mode)) {
                echo '[ERROR]: Failed to do this action';
            }
        } else {
            echo '[ERROR]: You don\'t have permission for this action';
        }
    }

    /**
     * generate doctrine entity files
     *
     * @return type
     */
    function db_entity() {
        if ($this->input->is_cli_request()) {
            if (!$this->doctrine->generate_classes()) {
                echo '[ERROR]: Failed to do this action';
            }
        } else {
            echo '[ERROR]: You don\'t have permission for this action';
        }
    }

    /**
     * 模板自动创建
     * @param string $modulename 模块名称
     */
    public function makemodule($modulename) {
        if ($this->input->is_cli_request()) {
            $this->load->helper("file");
            // 配置替换变量
            $now        = date("Y-m-d H:i:s");
            $modulename = strtolower($modulename);
            $classname  = ucfirst($modulename);
            // 定义全局变量
            define('TPLPATH', APPPATH . "views" . DIRECTORY_SEPARATOR . "tpl" . DIRECTORY_SEPARATOR);
            define('MKMODULEPATH', MODULES . $modulename . DIRECTORY_SEPARATOR);
            define('MKCONTROLLERADMINPATH', MODULES . $modulename . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR);
            define('MKCONTROLLERPATH', MODULES . $modulename . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR);
            define('MKMODELPATH', MODULES . $modulename . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR);
            define('MKCONFIGPATH', MODULES . $modulename . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR);
            define('MKVIEWADMINPATH', MODULES . $modulename . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR);
            define('MKVIEWPATH', MODULES . $modulename . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "default" . DIRECTORY_SEPARATOR);

            $moduldir = MKMODULEPATH;
            if (!is_dir($moduldir)) {
                mkdir($moduldir, 0777, true);
                echo "[MESSAGE] : Create " . $modulename . " directory\n";
            } else {
                echo "[ERROR] : " . $modulename . " Module already exists, it can\'t be duplicated created.\n";
                return;
            }

            // 创建Controller
            $controllerdir = MKCONTROLLERADMINPATH;
            if (!is_dir($controllerdir)) {
                mkdir($controllerdir, 0777, true);
            }
            $controllerCode = file_get_contents(TPLPATH . "controller.html");
            $controllerCode = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($now, $modulename, $classname) {
                return $$matches[1];
            }, $controllerCode);
            $destControllerPath = MKCONTROLLERADMINPATH . $classname . ".php";
            @write_file($destControllerPath, $controllerCode, FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
            echo "[MESSAGE] : Admin Controller creates success\n";
            // Home Controller
            $controllerdir = MKCONTROLLERPATH;
            if (!is_dir($controllerdir)) {
                mkdir($controllerdir, 0777, true);
            }
            $controllerCode = file_get_contents(TPLPATH . "homecontroller.html");
            $controllerCode = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($now, $modulename, $classname) {
                return $$matches[1];
            }, $controllerCode);
            $destControllerPath = MKCONTROLLERPATH . "Home.php";
            @write_file($destControllerPath, $controllerCode, FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
            echo "[MESSAGE] : Home Controller creates success\n";

            // 创建Model
            $modeldir = MKMODELPATH;
            if (!is_dir($modeldir)) {
                mkdir($modeldir, 0777, true);
            }
            $modelCode = file_get_contents(TPLPATH . "model.html");
            $modelCode = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($now, $modulename, $classname) {
                return $$matches[1];
            }, $modelCode);
            $destModelPath = MKMODELPATH . "Model_" . $modulename . ".php";
            write_file($destModelPath, $modelCode, FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
            echo "[MESSAGE] : Model creates success\n";

            // 创建Config
            $configdir = MKCONFIGPATH;
            if (!is_dir($configdir)) {
                mkdir($configdir, 0777, true);
            }
            $configCode = file_get_contents(TPLPATH . "info.html");
            $configCode = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($now, $modulename, $classname) {
                return $$matches[1];
            }, $configCode);
            $destConfigPath = MKCONFIGPATH . "info.php";
            write_file($destConfigPath, $configCode, FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
            echo "[MESSAGE] : Config creates success\n";

            // 创建Views
            $viewdir = MKVIEWADMINPATH;
            if (!is_dir($viewdir)) {
                mkdir($viewdir, 0777, true);
            }
            $viewCode = file_get_contents(TPLPATH . "index.html");
            $viewCode = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($now, $modulename, $classname) {
                return $$matches[1];
            }, $viewCode);
            $destViewPath = MKVIEWADMINPATH . "index.php";
            write_file($destViewPath, $viewCode, FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
            echo "[MESSAGE] : Admin Views creates success\n";
            // Home Views
            $viewdir = MKVIEWPATH;
            if (!is_dir($viewdir)) {
                mkdir($viewdir, 0777, true);
            }
            $viewCode = file_get_contents(TPLPATH . "index.html");
            $viewCode = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($now, $modulename, $classname) {
                return $$matches[1];
            }, $viewCode);
            $destViewPath = MKVIEWPATH . "index.php";
            write_file($destViewPath, $viewCode, FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
            echo "[MESSAGE] : Home Views creates success\n";
        } else {
            echo '[ERROR]: You don\'t have permission for this action\n';
        }
    }
}