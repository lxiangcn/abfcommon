<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Developer.php
 * DateTime : 2015年6月15日
 *
 * author : liuxiang<liuxiang@bit-space.cn>
 * Description :
 * Copyright (c) 2015 http://bit-space.cn All Rights Reserved.
 */
class Cron extends MX_Controller {

	public function index() {
		$this->load->library ( 'cron_schedule' );
		$this->cron_schedule->dispatch ();
	}
}