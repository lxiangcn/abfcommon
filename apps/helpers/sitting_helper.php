<?php
defined('BASEPATH') or die('No direct script access allowed');
/**
 * create select to lang
 */
if (!function_exists('workranges')) {
    function workranges($str) {
        $arr = explode(',', $str);
        foreach ($arr as $k => $v) {
            $data[$v] = __('sitting_' . $v);
        }
        return $data;
    }

}
