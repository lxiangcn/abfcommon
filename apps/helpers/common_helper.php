<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * FileName : common_helper.php
 * DateTime : 2014年12月3日
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */

if (! function_exists('version')) {

    function version () {
        return ABFCOMMON_NAME . ' ' . ABFCOMMON_VERSION . ' build ' . ABFCOMMON_BUILDTIME;
    }
}

/**
 * 表情图片处理
 *
 * @param unknown $str
 * @return mixed
 */
if (! function_exists('ubbReplace')) {

    function ubbReplace ($str) {
        $str = str_replace("<", '&lt;', $str);
        $str = str_replace(">", '&gt;', $str);
        $str = str_replace("\n", '<br/>', $str);
        $str = preg_replace("[\[/表情([0-9]*)\]]", "<img src=\"" . base_url('assets/js/face') . "/face/$1.gif\" />", $str);
        return $str;
    }
}
/**
 * 铭感词过滤
 *
 * @author Cai Shengpeng
 * @param string $str
 * @return string $str
 */
if (! function_exists('replace_badword')) {

    function replace_badword ($str) {
        $CI = &get_instance();
        // 加载系统配置敏感词库
        $CI->config->load('system_badword', true);
        $system_badword = $CI->config->item('system_badword');
        // 加载用户配置
        $CI->config->load('user_badword', true);
        $user_badword = $CI->config->item('user_badword');
        // 连接系统词库和用户词库
        $badword['badword'] = trim($system_badword['badword']) . '|' . trim($user_badword['badword']);
        // 替换换行
        $replace = array(
                "\r\n",
                "\n",
                "\r"
        );
        $badword = str_replace($replace, '|', $badword['badword']);
        // 转化成数组，并进行array('敏感词' => '*')结构处理
        $badword_array = explode("|", $badword);
        $badword_array = array_combine($badword_array, array_fill(0, count($badword_array), '*'));
        
        return strtr($str, $badword_array);
    }
}

/**
 * 加载前端js css
 */
if (! function_exists('load_js_file')) {

    function load_js_file ($js_file_arrays = array()) {
        $files_html = "";
        if (! empty($js_file_arrays)) {
            if (is_array($js_file_arrays)) {
                foreach ($js_file_arrays as $file) {
                    $files_html .= "<script type='text/javascript' src='" . $file . "'></script>\n";
                }
            }
        }
        return $files_html;
    }
}
if (! function_exists('load_css_file')) {

    function load_css_file ($css_file_arrays = array()) {
        $files_html = "";
        if (! empty($css_file_arrays)) {
            if (is_array($css_file_arrays)) {
                foreach ($css_file_arrays as $file) {
                    $files_html .= "<link rel='stylesheet' type='text/css' href='" . $file . "' />\n";
                }
            }
        }
        return $files_html;
    }
}

/**
 * 生成缩略图
 *
 * @param unknown $srcFile
 * @param number $dstW
 * @param number $dstH
 * @param string $file_name
 * @param string $save_dir
 * @return multitype:string number
 */
if (! function_exists('makethumb')) {

    function makethumb ($srcFile, $dstW = 0, $dstH = 0, $file_name = '', $save_dir = '') {
        if ($save_dir == '') {
            $save_dir = "data/attachments/tmp";
        }
        if ($file_name == '') {
            $file_name = time();
        }
        $save = FCPATH . '/' . $save_dir . '/' . $file_name . '.jpg';
        if (is_file($save)) {
            unlink($save);
        }
        $data = GetImageSize($srcFile);
        switch ($data[2]) {
            case 1:
                $im = @ImageCreateFromGIF($srcFile);
                $type = 'gif';
                break;
            case 2:
                $im = @imagecreatefromjpeg($srcFile);
                $type = 'jpg';
                break;
            case 3:
                $im = @ImageCreateFromPNG($srcFile);
                $type = 'png';
                break;
        }
        $srcW = ImageSX($im);
        $srcH = ImageSY($im);
        if ($dstW == 0) {
            $dstW = $srcW * ($dstH / $srcH);
        } elseif ($dstH == 0) {
            $dstH = $srcH * ($dstW / $srcW);
        }
        $dstX = 0;
        $dstY = 0;
        if ($srcW * $dstH > $srcH * $dstW) {
            $fdstH = round($srcH * $dstW / $srcW);
            $dstY = floor(($dstH - $fdstH) / 2);
            $fdstW = $dstW;
        } else {
            $fdstW = round($srcW * $dstH / $srcH);
            $dstX = floor(($dstW - $fdstW) / 2);
            $fdstH = $dstH;
        }
        // $ni = ImageCreate($dstW,$dstH);
        if ($type != 'gif' && function_exists('imagecreatetruecolor'))
            $ni = imagecreatetruecolor($dstW, $dstH);
        else
            $ni = imagecreate($dstW, $dstH);
        $dstX = ($dstX < 0) ? 0 : $dstX;
        $dstY = ($dstX < 0) ? 0 : $dstY;
        $dstX = ($dstX > ($dstW / 2)) ? floor($dstW / 2) : $dstX;
        $dstY = ($dstY > ($dstH / 2)) ? floor($dstH / s) : $dstY;
        
        if ($type == 'gif' || $type == 'png') {
            $black = imagecolorallocate($ni, 0, 0, 0);
            imagecolortransparent($ni, $black); // 设置为透明色，若注释掉该行则输出绿色的图
        }
        if ('jpg' == $type || 'jpeg' == $type)
            imageinterlace($ni, true);
        
        $imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
        // $black = ImageColorAllocate($ni,0,0,0);//填充的背景色你可以重新指定，我用的是黑色
        // imagefilledrectangle($ni,0,0,$dstW,$dstH,$black);
        if (function_exists("ImageCopyResampled"))
            imagecopyresampled($ni, $im, $dstX, $dstY, 0, 0, $fdstW, $fdstH, $srcW, $srcH);
        else
            imagecopyresized($ni, $im, $dstX, $dstY, 0, 0, $fdstW, $fdstH, $srcW, $srcH);
            // ImageCopyResized($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH);
        if ($type == 'png') {
            $q = 9;
        } else {
            $q = 100;
        }
        $imageFun($ni, $save, $q); // 如果你要把图片直接输出到浏览器，那么把第二个参数去掉，并用header()函数指定mine类型先
        imagedestroy($im);
        imagedestroy($ni);
        $return = array(
                'w' => $dstW,
                'h' => $dstH,
                'image' => $save_dir . '/' . $file_name . '.jpg?' . time()
        );
        return $return;
    }
}

/**
 * 获取客户端ip地址
 *
 * @return Ambigous <string, unknown>
 */
if (! function_exists('getip')) {

    function getip () {
        if ($_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if ($_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if ($_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "unknown";
        return $ip;
    }
}

/**
 * 截取字符串
 *
 * @param string $string
 * @param number $sublen
 * @param number $start
 * @param string $code
 */
if (! function_exists("cutstr")) {

    function cutstr ($string = "", $sublen = 8, $start = 0, $code = 'UTF-8') {
        if ($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if (count($t_string[0]) - $start > $sublen)
                return join('', array_slice($t_string[0], $start, $sublen)) . "...";
            return join('', array_slice($t_string[0], $start, $sublen));
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';
            for ($i = 0; $i < $strlen; $i ++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129)
                    $i ++;
            }
            if (strlen($tmpstr) < $strlen)
                $tmpstr .= "...";
            return $tmpstr;
        }
    }
}
/**
 * 屏蔽关键数据
 *
 * @param unknown $string
 * @param string $type
 * @param number $sublen
 * @param number $start
 * @return string
 */
if (! function_exists("hide_type")) {

    function hide_type ($string = null, $type = "string", $sublen = 10, $start = 0) {
        switch ($type) {
            case "string":
                return hidestr($string, 0, $sublen);
                break;
            case "mobile":
                return hidestr($string, 3, 4);
                break;
            case "ip":
                list ($a, $b, $c, $d) = explode('.', $string);
                return $a . '.' . $b . ".***.***";
                break;
            case "email":
                list ($name, $domain) = explode('@', $string);
                return hidestr($name, 1, - 1) . '@' . hidestr($domain, 0, 2);
                break;
        }
    }
}

/**
 * 将一个字符串部分字符用$re替代隐藏
 *
 * @param string $string 待处理的字符串
 * @param int $start 规定在字符串的何处开始，
 *        正数 - 在字符串的指定位置开始
 *        负数 - 在从字符串结尾的指定位置开始
 *        0 - 在字符串中的第一个字符处开始
 * @param int $length 可选。规定要隐藏的字符串长度。默认是直到字符串的结尾。
 *        正数 - 从 start 参数所在的位置隐藏
 *        负数 - 从字符串末端隐藏
 * @param string $re 替代符
 * @return string 处理后的字符串
 */
if (! function_exists("hidestr")) {

    function hidestr ($string, $start = 0, $length = 0, $re = '*') {
        if (empty($string))
            return false;
        $strarr = array();
        $mb_strlen = mb_strlen($string);
        while ($mb_strlen) {
            $strarr[] = mb_substr($string, 0, 1, 'utf8');
            $string = mb_substr($string, 1, $mb_strlen, 'utf8');
            $mb_strlen = mb_strlen($string);
        }
        $strlen = count($strarr);
        $begin = $start >= 0 ? $start : ($strlen - abs($start));
        $end = $last = $strlen - 1;
        if ($length > 0) {
            $end = $begin + $length - 1;
        } elseif ($length < 0) {
            $end -= abs($length);
        }
        for ($i = $begin; $i <= $end; $i ++) {
            $strarr[$i] = $re;
        }
        if ($begin >= $end || $begin >= $last || $end > $last)
            return false;
        return implode('', $strarr);
    }
}
/**
 * 载入编辑器
 */
if (! function_exists('load_editor')) {

    function load_editor ($content = '', $id = 'content', $isfilemanager = FALSE, $width = '100%', $height = '300px') {
        $CI = &get_instance();
        $allowFileManager = $isfilemanager ? "true" : "false";
        return '
<textarea id="' . $id . '" name="' . $id . '" style="width:' . $width . ';height:' . $height . ';">' . $content . '</textarea>
<script type="text/javascript">
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create("#' . $id . '", {
			allowFileManager : ' . $allowFileManager . ',
			uploadJson : "' . site_url("filemanager/file/upload") . '",
			fileManagerJson : "' . site_url("filemanager/file/manager") . '",
			items : [\'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\',\'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',\'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\'],
			extraFileUploadParams : {"' . $CI->security->get_csrf_token_name() . '" : "' . $CI->security->get_csrf_hash() . '"}
		});
	});
</script>';
    }
}

/**
 * 载入图片上传按钮
 */
if (! function_exists('load_imagebtn')) {

    function load_imagebtn ($inputid = 'image_src', $btnid = 'image', $isfilemanager = FALSE) {
        $CI = &get_instance();
        $allowFileManager = $isfilemanager ? "true" : "false";
        return '
<script type="text/javascript">
$(function() {
	KindEditor.ready(function(K) {
		var editor = K.editor({
			allowFileManager : ' . $allowFileManager . ',
			uploadJson: "' . site_url("filemanager/file/upload") . '",
			fileManagerJson:"' . site_url("filemanager/file/manager") . '",
			items : [\'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\',\'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',\'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\'],
			extraFileUploadParams : {"' . $CI->security->get_csrf_token_name() . '" : "' . $CI->security->get_csrf_hash() . '"}
		});
       	K("#' . $btnid . '").click(function() {
	        editor.loadPlugin("image", function () {
	            editor.plugin.imageDialog({
	                imageUrl: $("#' . $btnid . '").val(),
	                clickFn: function (url, title) {
	                    $("#' . $inputid . '").val(url);
	                    editor.hideDialog();
	                }
	            });
	        });
      	});
	});
});
</script>';
    }
}

/*
 * Theme Url
 * Create a url to the current theme
 * @param string
 * @return string
 */
if (! function_exists('theme_url')) {

    function theme_url ($uri = '') {
        $CI = & get_instance();
        $is_cdn = empty($CI->load->_ci_cached_vars['config']['is_cdn']) ? "" : $CI->load->_ci_cached_vars['config']['is_cdn'];
        if ($is_cdn) {
            return '//' . $CI->load->_ci_cached_vars['config']['cdn_url'] . '/theme/' . trim($uri, '/');
        } else {
            return base_url('theme/' . trim($uri, '/'));
        }
    }
}

if (! function_exists('time_diff')) {

    /**
     * 生成友好时间形式
     *
     * @param unknown $from
     * @return string
     */
    function time_diff ($from) {
        static $now = NULL;
        $now == NULL && $now = time();
        ! is_numeric($from) && $from = strtotime($from);
        $seconds = $now - $from;
        
        $minutes = floor($seconds / 60);
        $hours = floor($seconds / 3600);
        $day = round((strtotime(date('Y-m-d', $now)) - strtotime(date('Y-m-d', $from))) / 86400);
        if ($seconds == 0) {
            return '刚刚';
        }
        if (($seconds >= 0) && ($seconds <= 60)) {
            return "{$seconds}秒前";
        }
        if (($minutes >= 0) && ($minutes <= 60)) {
            return "{$minutes}分钟前";
        }
        if (($hours >= 0) && ($hours <= 24)) {
            return "{$hours}小时前";
        }
        if (($day >= 0) && ($day <= 2)) {
            switch ($day) {
                case 0:
                    return date('今天H:i', $from);
                    break;
                case 1:
                    return date('昨天H:i', $from);
                    break;
                default:
                    return "{$day} 天前";
                    break;
            }
        }
        return date('Y-m-d H:i', $from);
    }
}
/*
 * Is Ajax
 * 判断是否是ajax请求的通用方法，通常用于数据传输安全和防跳墙
 * @return bool
 */
if (! function_exists('is_ajax')) {

    function is_ajax () {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
}

/*
 * In URI
 * Checks if current uri segments exist in array of uri strings
 * @param string or array
 * @param string
 * @param bool
 * @return bool
 */
if (! function_exists('in_uri')) {

    function in_uri ($uri_array, $uri = null, $array_keys = FALSE) {
        if (! is_array($uri_array)) {
            $uri_array = array(
                    $segments
            );
        }
        
        $CI = & get_instance();
        
        if (! empty($uri)) {
            $uri_string = '/' . trim($uri, '/');
        } else {
            $uri_string = '/' . trim($CI->uri->uri_string(), '/');
        }
        
        $uri_array = ($array_keys) ? array_keys($uri_array) : $uri_array;
        
        foreach ($uri_array as $string) {
            if (strpos($uri_string, ($string != '') ? '/' . trim($string, '/') : ' ') === 0) {
                return true;
            }
        }
        
        return false;
    }
}

if (! function_exists('strip_some')) {

    /**
     * 去除字符 单/双引号/反斜杠
     */
    function strip_some ($str) {
        return trim(strip_slashes(strip_quotes($str)));
    }
}

if (! function_exists("cut_string")) {

    /**
     * 截取字符串
     *
     * @param string $string
     * @param number $sublen
     * @param number $start
     * @param string $code
     */
    function cut_string ($string = "", $sublen = 8, $start = 0, $code = 'UTF-8') {
        if ($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if (count($t_string[0]) - $start > $sublen)
                return join('', array_slice($t_string[0], $start, $sublen)) . "...";
            return join('', array_slice($t_string[0], $start, $sublen));
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';
            for ($i = 0; $i < $strlen; $i ++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129)
                    $i ++;
            }
            if (strlen($tmpstr) < $strlen)
                $tmpstr .= "...";
            return $tmpstr;
        }
    }
}

/**
 * ajax
 *
 * @param string $result
 * @param string $message
 * @param array $data
 */
if (! function_exists('ajax_response')) {

    function ajax_response ($result = TRUE, $message = "信息提示", $data = array()) {
        exit(json_encode(array(
                "result" => $result,
                "msg" => $message,
                'data' => $data
        )));
    }
}

/**
 * 生成树形数组
 */
if (! function_exists('create_tree')) {

    function create_tree ($arr, $id_field = 'id', $pid_field = 'parent_id', $children_array_name = 'children', $pid_index = 0) {
        $ret = array();
        foreach ($arr as $k => $v) {
            if ($v->$pid_field == $pid_index) {
                $tmp = $arr[$k];
                unset($arr[$k]);
                $tmp->$children_array_name = create_tree($arr, $id_field, $pid_field, $children_array_name, $v->$id_field);
                $ret[] = $tmp;
            }
        }
        return $ret;
    }
}

/**
 * 输出权限编辑
 */
if (! function_exists('show_access')) {

    function show_access ($arr, $accesss_arr, $node_name_field = 'name', $children_array_name = 'children', $level_field = 'level') {
        $tmpStr = '';
        foreach ($arr as $k => $v) {
            $checked = "";
            if (is_array($accesss_arr)) {
                if (in_array($v->id, $accesss_arr)) {
                    $checked = ' checked="checked"';
                }
            }
            // $i = $v[$level_field];
            if (0 == $v->parent_id) {
                $tmpStr .= '<label class="checkbox-inline"><input name="ids[]" type="checkbox" value="' . $v->id . '" ' . $checked . ' class="px access_' . $v->id . '" onclick="check(\'' . $v->id . '\',this)">';
            } else {
                $tmpStr .= '<label class="checkbox-inline"><input name="ids[]" type="checkbox" value="' . $v->id . '" ' . $checked . ' class="px sub_access_' . $v->parent_id . '" onclick="checkrelevance(\'' . $v->parent_id . '\',this)">';
            }
            $tmpStr .= '<span class="lbl">' . $v->$node_name_field . '</span>';
            $tmpStr .= '</label>';
            if (count($v->$children_array_name) > 0) {
                $tmpStr .= '<ol>';
                $tmpStr .= show_access($v->$children_array_name, $accesss_arr, $node_name_field, $children_array_name, $level_field);
                $tmpStr .= '</ol>';
            }
            // $tmpStr .= '</li>';
        }
        return $tmpStr;
    }
}