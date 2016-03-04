<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : user_auth.php
 * DateTime : 2015年5月12日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
/*
 * | -------------------------------------------------------------------------
 * | Authentication options.
 * | -------------------------------------------------------------------------
 */
$config ['default_group'] = 'members'; // Default group, use name
$config ['admin_group'] = 'admin'; // Default administrators group, use name
$config ['identity'] = 'username'; // 设置用户登录名管理字段
$config ['email'] = 'email'; // 设置用户邮箱字段
$config ['manual_activation'] = FALSE; // 注册用户是否需要审核

$config ['site_title'] = "site.com"; // 网站名称 site.com
$config ['admin_email'] = "admin@site.com"; // 管理员邮箱, admin@site.com
/**
 * salt 长度
 */
$config ['salt_length'] = 6;

/*
 * | -------------------------------------------------------------------------
 * | Tables.
 * | -------------------------------------------------------------------------
 * | Database table names.
 */
$config ['tables'] ['users'] = 'users';
$config ['tables'] ['groups'] = 'groups';
$config ['tables'] ['users_groups'] = 'user_groups';
$config ['tables'] ['login_attempts'] = 'user_login_attempts'; // 用户尝试登录失败次数
/* join */
$config ['join'] ['users'] = 'user_id';
$config ['join'] ['groups'] = 'group_id';
/* Permissions */
$config ['tables'] ['node'] = 'menus'; // 节点表,目录表
$config ['tables'] ['access'] = 'user_access'; // 权限表
/**
 * *
 * 尝试登录限制
 */
$config ['track_login_attempts'] = TRUE; // 跟踪用户登录失败的次数.
$config ['track_login_ip_address'] = TRUE; // 是否跟踪用户IP地址 (默认: TRUE)
$config ['maximum_login_attempts'] = 3; // 最大允许用户尝试失败的次数
$config ['lost_password_expire'] = 0; // 忘记密码code有效时间
$config ['lockout_time'] = 600; // 尝试登录失败之后锁定时间

/**
 * Send Email using the builtin CI email class, if false it will return the code and the identity
 *
 * @var unknown
 */
$config ['use_ci_email'] = TRUE;
$config ['email_config'] = array (
	'protocol' => 'mail',
	'mailtype' => 'html' 
);
$config ['email_templates'] = 'auth/email/';
$config ['email_activate'] = 'activate.tpl.php';
$config ['email_forgot_password'] = 'forgot_password.tpl.php';
$config ['email_forgot_password_complete'] = 'new_password.tpl.php';
/**
 * 是否开启记住用户自动登录
 */
$config ['remember_users'] = TRUE; // Allow users to be remembered and enable auto-login

/*
 * | -------------------------------------------------------------------------
 * | Cookie options.
 * | -------------------------------------------------------------------------
 * | remember_cookie_name Default: remember_code
 * | identity_cookie_name Default: identity
 */
$config ['remember_cookie_name'] = 'remember_code';
$config ['identity_cookie_name'] = 'identity';

/**
 * encryption,初始化系统的时候调整，系统运行中不允许调整。否则密码全部失效
 */
$config ['encryption'] = 'sha1'; // sha1 or crypt
