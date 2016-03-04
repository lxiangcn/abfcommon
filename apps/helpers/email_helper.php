<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/**
 * Send an email
 *
 * @access public
 * @return bool
 */
function MY_Send_Email($to_email, $mail_template, $mail_data) {
	// 载入类库
	$ci = &get_instance ();
	$ci->load->library ( 'email' );
	$ci->load->library ( 'parser' );
	$ci->config->load ( 'custom', TRUE );
	
	// 获取配置信息
	$site_info = $ci->config->item ( 'site_info', 'custom' );
	$mail_template = $ci->config->item ( $mail_template, 'custom' );
	$mail_server = $ci->config->item ( 'mail_server', 'custom' );
	// 初使化邮件发送配置
	if ($mail_server['protocol'] == 'smtp') {
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $mail_server['smtp_host'];
		$config['smtp_user'] = $mail_server['smtp_user'];
		$config['smtp_pass'] = $mail_server['smtp_pass'];
		$config['smtp_port'] = $mail_server['smtp_port'];
	} else {
		$config['protocol'] = 'mail';
	}
	$config['mailtype'] = $mail_template['mailtype'];
	$ci->email->initialize ( $config );
	
	// 邮件内容设置
	$mail['subject'] = $mail_template['subject'];
	$mail['message'] = $ci->parser->parse_string ( $mail_template['template'], $mail_data, TRUE );
	$mail['from'] = $mail_server['from'];
	$mail['sender'] = $mail_server['sender'];
	// 发送邮件
	$ci->email->from ( $mail['from'], $mail['sender'] );
	$ci->email->to ( $to_email );
	$ci->email->subject ( $mail['subject'] );
	$ci->email->message ( $mail['message'] );
	
	return $ci->email->send ();
}

