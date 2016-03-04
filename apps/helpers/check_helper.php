<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : check_helper.php
 * DateTime : 2015年10月30日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */

if (! function_exists ( 'chkval' )) {

	/**
	 * 验证值
	 *
	 * @param string 类型
	 * @param string 待验证值
	 * @param int 最小长度
	 * @param int 最大长度
	 * @return boolean
	 */
	function chkval($type, $val, $min_length = '', $max_length = '') {
		$strLength = strlen ( $val );
		
		if ($min_length >= 0) {
			if ($strLength < $min_length) {
				return false;
			}
		}
		
		if (($max_length >= 0) && ($max_length > $min_length)) {
			if ($strLength > $max_length) {
				return false;
			}
		}
		
		switch ($type) {
			case 'text' :
				$reg = '/^[a-zA-Z][a-zA-Z0-9_]*$/';
				break;
			case 'number' :
				$reg = '/^[0-9]+$/';
				break;
			case 'money' :
				$reg = '/^[0-9]+.?[0-9]+$/';
				break;
			case 'password' :
				$reg = '/^[a-zA-Z0-9_]+$/';
				break;
			case 'date' :
				$reg = '/^((((19|20)\d{2})-(0?(1|[3-9])|1[012])-(0?[1-9]|[12]\d|30))|(((19|20)\d{2})-(0?[13578]|1[02])-31)|(((19|20)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|((((19|20)([13579][26]|[2468][048]|0[48]))|(2000))-0?2-29))$/';
				break;
			case 'email' :
				$reg = '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/';
				break;
			case 'mobile' :
				$reg = '/^1[3|4|5|7|8][0-9][0-9]{8}$/';
				break;
			case 'phone' :
				$reg = '/^\d{3,4},\d{7,8}(,\d{1,4})?$/';
				break;
			case 'url' :
				$reg = '/^https?:\/\/[a-z0-9\-\._]+(\/.*)?$/i';
				break;
			default :
				return false;
				break;
		}
		
		if (preg_match ( $reg, $val ) > 0) {
			return true;
		}
		return false;
	}
}