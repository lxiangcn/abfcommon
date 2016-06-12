<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package abfcommon
 * @subpackage File
 * @link http://orzm.net
 * @author Alex Liu<lxiangcn@gmail.com>
 * @version 1.1.1
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

class File extends Other_Controller {
    var $upload_path;
    var $allowed_types = "gif|jpg|jpeg|png|bmp|swf|flv|swf|flv|mp3|wav|wma|wmv|mid|avi|mpg|asf|rm|rmvb|doc|docx|xls|xlsx|ppt|txt|zip|rar|gz|bz2";
    var $php_path;
    var $php_url;
    var $max_size;
    var $path_format;
    var $encrypt_name;

    function __construct() {
        parent::__construct();
        // 上传文件目录路径
        $this->upload_path = $this->data["config"]['upload_path'];
        // 上传文件类型
        $this->allowed_types = $this->data["config"]["allowed_types"];
        // 上传文件大小
        $this->max_size = $this->data["config"]["upload_max_size"];
        // 目录格式
        $this->path_format = $this->data["config"]["upload_path_format"];
        // 是否重命名文件
        $this->encrypt_name = $this->data["config"]["upload_encrypt_name"];
        $this->php_path     = FCPATH . 'data' . DIRECTORY_SEPARATOR;
        $this->php_url      = '/data/';
    }

    /**
     * 上传文件管理
     */
    public function manager() {
        // 根目录路径，可以指定绝对路径，比如 /var/www/attached/
        $root_path = $this->php_path . $this->upload_path . DIRECTORY_SEPARATOR . 'attached' . DIRECTORY_SEPARATOR;
        // 根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
        $root_url = $this->php_url . $this->upload_path . '/attached/';
        $ext_arr  = array();
        $ext_arr  = explode('|', $this->allowed_types);
        // 图片扩展名
        if (!is_array($ext_arr)) {
            $ext_arr = array(
                'gif',
                'jpg',
                'jpeg',
                'png',
                'bmp',
            );
        }
        // 目录名
        $dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
        if (!in_array($dir_name, array(
            '',
            'image',
            'flash',
            'media',
            'file',
        ))) {
            echo "Invalid Directory name.";
            exit();
        }
        if ($dir_name !== '') {
            $root_path .= $dir_name . DIRECTORY_SEPARATOR;
            $root_url .= $dir_name . "/";
            if (!file_exists($root_path)) {
                @mkdir($root_path, 0777, true);
            }
        } else {
            @mkdir($root_path, 0777, true);
        }
        // 根据path参数，设置各路径和URL
        if (empty($_GET['path'])) {
            $current_path     = realpath($root_path) . '/';
            $current_url      = $root_url;
            $current_dir_path = '';
            $moveup_dir_path  = '';
        } else {
            $current_path     = realpath($root_path) . '/' . $_GET['path'];
            $current_url      = $root_url . $_GET['path'];
            $current_dir_path = $_GET['path'];
            $moveup_dir_path  = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        echo realpath($root_path);
        // 排序形式，name or size or type
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

        // 不允许使用..移动到上一级目录
        if (preg_match('/\.\./', $current_path)) {
            echo 'Access is not allowed.';
            exit();
        }
        // 最后一个字符不是/
        if (!preg_match('/\/$/', $current_path)) {
            echo 'Parameter is not valid.';
            exit();
        }
        // 目录不存在或不是目录
        if (!file_exists($current_path) || !is_dir($current_path)) {
            echo 'Directory does not exist.';
            exit();
        }

        // 遍历目录取得文件信息
        $file_list = array();
        if ($handle = opendir($current_path)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.') {
                    continue;
                }

                $file = $current_path . $filename;
                if (is_dir($file)) {
                    $file_list[$i]['is_dir']   = true; // 是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); // 文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; // 文件大小
                    $file_list[$i]['is_photo'] = false; // 是否图片
                    $file_list[$i]['filetype'] = ''; // 文件类别，用扩展名判断
                } else {
                    $file_list[$i]['is_dir']   = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);
                    $file_list[$i]['dir_path'] = '';
                    $file_tmp                  = explode('.', trim($file));
                    $file_ext                  = strtolower(array_pop($file_tmp));
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                }
                $file_list[$i]['filename'] = $filename; // 文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); // 文件最后修改时间
                $i++;
            }
            closedir($handle);
        }

        // 排序
        function cmp_func($a, $b) {
            global $order;
            if ($a['is_dir'] && !$b['is_dir']) {
                return -1;
            } else if (!$a['is_dir'] && $b['is_dir']) {
                return 1;
            } else {
                if ($order == 'size') {
                    if ($a['filesize'] > $b['filesize']) {
                        return 1;
                    } else if ($a['filesize'] < $b['filesize']) {
                        return -1;
                    } else {
                        return 0;
                    }
                } else if ($order == 'type') {
                    return strcmp($a['filetype'], $b['filetype']);
                } else {
                    return strcmp($a['filename'], $b['filename']);
                }
            }
        }

        usort($file_list, 'cmp_func');
        $result = array();
        // 相对于根目录的上一级目录
        $result['moveup_dir_path'] = $moveup_dir_path;
        // 相对于根目录的当前目录
        $result['current_dir_path'] = $current_dir_path;
        // 当前目录的URL
        $result['current_url'] = $current_url;
        // 文件数
        $result['total_count'] = count($file_list);
        // 文件列表数组
        $result['file_list'] = $file_list;
        // 输出
        echo json_encode($result);
    }

    /**
     * 文件上传
     */
    public function upload() {
        // 文件保存目录路径
        $save_path = $this->php_path . $this->upload_path . DIRECTORY_SEPARATOR . 'attached' . DIRECTORY_SEPARATOR;
        // 根目录URL
        $save_url = $this->php_url . $this->upload_path . '/attached/';
        // 文件保存目录URL
        $ext_arr = array();
        $ext_arr = explode('|', $this->allowed_types);
        if (!is_array($ext_arr)) {
            $ext_arr = array(
                'image' => array(
                    'gif',
                    'jpg',
                    'jpeg',
                    'png',
                    'bmp',
                ),
            );
        } else {
            $ext_arr = array(
                'image' => $ext_arr,
            );
        }
        // 最大文件大小
        $this->max_size = 1000000;

        // $save_path = realpath($save_path) . DIRECTORY_SEPARATOR;

        // PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch ($_FILES['imgFile']['error']) {
            case '1':
                $error = '超过php.ini允许的大小。';
                break;
            case '2':
                $error = '超过表单允许的大小。';
                break;
            case '3':
                $error = '图片只有部分被上传。';
                break;
            case '4':
                $error = '请选择图片。';
                break;
            case '6':
                $error = '找不到临时目录。';
                break;
            case '7':
                $error = '写文件到硬盘出错。';
                break;
            case '8':
                $error = 'File upload stopped by extension。';
                break;
            case '999':
            default:
                $error = '未知错误。';
            }
            $this->alert($error);
        }
        // 有上传文件时
        if (empty($_FILES) === false) {
            // 原文件名
            $file_name = $_FILES['imgFile']['name'];
            // 服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            // 文件大小
            $file_size = $_FILES['imgFile']['size'];
            // 检查文件名
            if (!$file_name) {
                $this->alert("请选择文件。");
            }
            // 创建文件夹
            if ($dir_name !== '') {
                $save_path .= $dir_name . DIRECTORY_SEPARATOR;
                $save_url .= $dir_name . "/";
                if (!file_exists($save_path)) {
                    @mkdir($save_path, 0777, true);
                }
            }
            // 检查目录
            if (@is_dir($save_path) === false) {
                $this->alert("上传目录不存在。");
            }
            // 检查目录写权限
            if (@is_writable($save_path) === false) {
                $this->alert("上传目录没有写权限。");
            }
            // 检查是否已上传
            if (@is_uploaded_file($tmp_name) === false) {
                $this->alert("上传失败。");
            }
            // 检查文件大小
            if ($file_size > $this->max_size) {
                $this->alert("上传文件大小超过限制。");
            }
            // 检查目录名
            $dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
            if (empty($ext_arr[$dir_name])) {
                $this->alert("目录名不正确。");
            }
            // 获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            // 检查扩展名
            if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
                $this->alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
            }

            $ymd = $this->path_format;
            if (isset($ymd) && empty($ymd)) {
                $ymd = date("Ymd");
            } else {
                $ymd = date($ymd);
            }
            $save_path .= $ymd . DIRECTORY_SEPARATOR;
            $save_url .= $ymd . "/";
            if (!file_exists($save_path)) {
                @mkdir($save_path, 0777, true);
            }
            // 是否重命名文件
            if ($this->encrypt_name) {
                // 新文件名
                $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
            } else {
                $new_file_name = $file_name;
            }
            // 移动文件
            $file_path = $save_path . $new_file_name;
            if (move_uploaded_file($tmp_name, $file_path) === false) {
                $this->alert("上传文件失败。");
            }
            @chmod($file_path, 0644);
            $file_url = $save_url . $new_file_name;
            // 输出图片
            echo json_encode(array(
                'error' => 0,
                'url'   => $file_url,
            ));
            exit();
        }
    }

    /**
     * 信息提示
     *
     * @param type $msg
     */
    function alert($msg) {
        echo json_encode(array('error' => 1, 'message' => $msg));
        exit();
    }

    function webupload() {
        // !! 注意
        // !! 此文件只是个示例，不要用于真正的产品之中。
        // !! 不保证代码安全性。
        // !! IMPORTANT:
        // !! this file is just an example, it doesn't incorporate any security checks and
        // !! is not recommended to be used in production environment as it is. Be sure to
        // !! revise it and customize to your needs.
        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(); // finish preflight CORS requests here
        }
        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                exit();
            }
        }
        // header("HTTP/1.0 500 Internal Server Error");
        // exit;
        // 5 minutes execution time
        @set_time_limit(5 * 60);
        // Uncomment this one to fake upload time
        // usleep(5000);
        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir        = $this->php_path . $this->upload_path . DIRECTORY_SEPARATOR . 'attached' . DIRECTORY_SEPARATOR . 'temp';
        $uploadDir        = $this->php_path . $this->upload_path . DIRECTORY_SEPARATOR . 'attached';
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge       = 5 * 3600;
        // Temp file age in seconds
        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }
        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $filePath   = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
        // Chunking might be enabled
        $chunk  = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            // die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $index = 0;
        $done  = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        if ($done) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
        }
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    /**
     * 此页面用来协助 IE6/7 预览图片，因为 IE 6/7 不支持 base64
     */
    function preview() {
        $DIR = 'preview';
        // Create target dir
        if (!file_exists($DIR)) {
            @mkdir($DIR, 0777, true);
        }
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge       = 5 * 3600; // Temp file age in seconds
        if ($cleanupTargetDir) {
            if (!is_dir($DIR) || !$dir = opendir($DIR)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $DIR . DIRECTORY_SEPARATOR . $file;
                // Remove temp file if it is older than the max age and is not the current file
                if (@filemtime($tmpfilePath) < time() - $maxFileAge) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
        $src = file_get_contents('php://input');
        if (preg_match("#^data:image/(\w+);base64,(.*)$#", $src, $matches)) {
            $previewUrl = sprintf("%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
            $previewUrl = str_replace("preview.php", "", $previewUrl);
            $base64     = $matches[2];
            $type       = $matches[1];
            if ($type === 'jpeg') {
                $type = 'jpg';
            }
            $filename = md5($base64) . ".$type";
            $filePath = $DIR . DIRECTORY_SEPARATOR . $filename;
            if (file_exists($filePath)) {
                die('{"jsonrpc" : "2.0", "result" : "' . $previewUrl . 'preview/' . $filename . '", "id" : "id"}');
            } else {
                $data = base64_decode($base64);
                file_put_contents($filePath, $data);
                die('{"jsonrpc" : "2.0", "result" : "' . $previewUrl . 'preview/' . $filename . '", "id" : "id"}');
            }
        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "un recoginized source"}}');
        }
    }
}
