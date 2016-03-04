<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Home.php
 * DateTime : 2015年5月18日
 *
 * author : liuxiang<liuxiang@bit-space.cn>
 * Description :
 * Copyright (c) 2015 http://bit-space.cn All Rights Reserved.
 */
class Home extends Other_Controller {
	private $_wxSetting = NULL;

	public function __construct() {
		parent::__construct ();
		$this->load->library ( 'weixin/Weixin' );
	}

	/**
	 * 入口
	 */
	public function index() {
		// verify the call
		log_message ( 'info', 'Receive call from wechat server : ' . $this->input->ip_address () );
		
		// dispatch the message to the processor
		if (! isset ( $GLOBALS ['HTTP_RAW_POST_DATA'] )) {
			$echoStr = isset ( $_GET ['echostr'] ) ? $_GET ['echostr'] : '';
			log_message ( 'error', 'echoStr:' . $echoStr );
			echo $echoStr;
			return;
		} else {
			if (! $this->weixin->dispachMsg ()) { // dispatch message
				log_message ( 'error', 'Failed to response to wechat server!' );
			}
		}
	}

	public function chat() {
		$openid = "obHo2v8I5fPGzwWiEu3iA0tZNwpw";
		$template_id = "KD4Mg4hiAz7BH-DAseNNyQq8UYoyXZRDM4au2di7KlA";
		$data = array ("first" => array ("value" => "您已经成功领取优惠券啦。","color" => "#173177" ),"keyword1" => array ("value" => "电影票","color" => "#173177" ),"keyword2" => array ("value" => '54682165',"color" => "#173177" ),"keyword3" => array ("value" => "3份","color" => "#173177" ),"keyword4" => array ("value" => "平湖大道18-1-XX号","color" => "#173177" ),"keyword5" => array ("value" => "0717-2888888","color" => "#173177" ),"remark" => array ("value" => "您只需要到店出示此优惠券就能享受独家折扣啦。消费完毕记得给商户中肯的评价哦！","color" => "#173177" ) );
		
		$template_id_1 = "qWZSqzYoFtD5PIWyLmeaIN686frkRIeJnUH8-Ctw6HQ";
		$data_1 = array ("first" => array ("value" => "您已成功绑定招商局蛇口工委会微信公众号","color" => "#173177" ),"keyword1" => array ("value" => "柳祥","color" => "#173177" ),"keyword2" => array ("value" => '15623232323',"color" => "#173177" ),"keyword3" => array ("value" => "通过","color" => "#173177" ),"keyword4" => array ("value" => "","color" => "#173177" ),"remark" => array ("value" => "更多精彩，敬请关注","color" => "#173177" ) );
		
		$this->weixin->sendAuditResult ( $openid, $template_id_1, $data_1 );
	}

	public function menu() {
		$menus = array ("button" => array (array ("type" => "view","name" => "链接一","url" => site_url ( 'auth/weixinlogin' ) ),array ("type" => "click","name" => "链接二","key" => "link2" ),array ("name" => "菜单","sub_button" => array (array ("type" => "click","name" => "hello word","key" => "V1001_HELLO_WORLD" ),array ("type" => "click","name" => "赞一下我们","key" => "V1001_GOOD" ) ) ) ) );
		$newmenu = array ("button" => array (array ("name" => "快速认证","sub_button" => array (array ("type" => "view","name" => "绑定帐号",'url' => site_url ( 'auth/weixinlogin' ) ),array ("type" => "click","name" => "账号管理","key" => "Menu_Key_accountmanager" ) ) ),array ("name" => "最新","sub_button" => array (array ("type" => "view","name" => "最新话题",'url' => site_url () ),array ("type" => "view","name" => "最新投票",'url' => site_url () ),array ("type" => "click","name" => "销售管理","key" => "Menu_Key_salesmanage" ) ) ),array ("name" => "我的","sub_button" => array (array ("type" => "click","name" => "我的话题","key" => "Menu_Key_thunder" ),array ("type" => "click","name" => "我的活动","key" => "Menu_Key_activity" ),array ("type" => "click","name" => "联络客服","key" => "Menu_Key_contact" ),array ("type" => "view","name" => "关于我们","url" => "http://eqxiu.com/s/hMwFE5Nu" ),array ("type" => "view","name" => "加入我们","url" => "http://eqxiu.com/s/SFcgyoGh" ) ) ) ) );
	}
}
