<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Png.php
 * DateTime : 2015年6月26日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description : 验证码
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Png extends Other_Controller {

	function index() {
		$conf ['name'] = 'verify'; // 作为配置参数
		$conf ['width'] = "80";
		$conf ['height'] = "30";
		$this->load->library ( 'Captcha', $conf );
		$this->captcha->ImageCode ( $this->getRandStr ( 4 ) );
	}

	function getRandStr($length) {
		$str = 'abcdefghijklmnopqrstuvwxyz';
		$randString = '';
		$len = strlen ( $str ) - 1;
		for($i = 0; $i < $length; $i ++) {
			$num = mt_rand ( 0, $len );
			$randString .= $str [$num];
		}
		return $randString;
	}
}

?>
