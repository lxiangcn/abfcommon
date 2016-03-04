<?php

defined('BASEPATH') or die('No direct script access allowed');
/**
 *  abfcommon
 *
 * @package member_auth
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-21 21:05:07
 * @author Alex Liu<lxiangcn@gmail.com>
 */
/*
 * | -------------------------------------------------------------------------
 * | Authentication options.
 * | -------------------------------------------------------------------------
 */
$config['default_group']        = 'members'; // Default group, use name
$config['admin_group']          = 'admin'; // Default administrators group, use name
$config['identity']             = 'username'; // 设置用户登录名管理字段
$config['email']                = 'email'; // 设置用户邮箱字段
$config['manual_activation']    = FALSE; // 注册用户是否需要审核
$config['site_title']           = "site.com"; // 网站名称 site.com
$config['admin_email']          = "admin@site.com"; // 管理员邮箱, admin@site.com
$config['local_user_info']      = "local_user_info"; // 登录状态名称
$config['user_expire']          = 86500; // How long to remember the user (seconds). Set to zero for no expiration
$config['user_extend_on_login'] = FALSE; // Extend the users cookies every time they auto-login
/**
 * salt 长度
 */
$config['salt_length']         = 6;
$config['min_password_length'] = 6;
$config['max_password_length'] = 20;

/*
 * | -------------------------------------------------------------------------
 * | Tables.
 * | -------------------------------------------------------------------------
 * | Database table names.
 */
$config['tables']['users']          = 'member';
$config['tables']['groups']         = 'groups';
$config['tables']['users_groups']   = 'member_group';
$config['tables']['login_attempts'] = 'member_attempts'; // 用户尝试登录失败次数
/* join */
$config['join']['users']  = 'user_id';
$config['join']['groups'] = 'group_id';
/* Permissions */
$config['tables']['node']   = 'menus'; // 节点表,目录表
$config['tables']['access'] = 'admin_role_priv'; // 权限表
/**
 * *
 * 尝试登录限制
 */
$config['track_login_attempts']   = TRUE; // 跟踪用户登录失败的次数.
$config['track_login_ip_address'] = TRUE; // 是否跟踪用户IP地址 (默认: TRUE)
$config['maximum_login_attempts'] = 3; // 最大允许用户尝试失败的次数
$config['lost_password_expire']   = 0; // 忘记密码code有效时间
$config['lockout_time']           = 600; // 尝试登录失败之后锁定时间

/**
 * Send Email using the builtin CI email class, if false it will return the code and the identity
 *
 * @var unknown
 */
$config['use_ci_email'] = TRUE;
//ci email config
//protocol mail, sendmail, or smtp
$config['email_config'] = array(
    'protocol'  => 'smtp',
    //'mailpath'  => "/usr/sbin/sendmail",
    'smtp_host' => 'smtp.163.com',
    'smtp_user' => '',
    'smtp_pass' => '',
    'smtp_port' => '25',
    'charset'   => 'utf-8',
    'wordwrap'  => true,
    'mailtype'  => 'html',
);
$config['email_templates']                = 'auth/email/';
$config['email_activate']                 = 'activate.tpl.php';
$config['email_forgot_password']          = 'forgot_password.tpl.php';
$config['email_forgot_password_complete'] = 'new_password.tpl.php';
/**
 * 是否开启记住用户自动登录
 */
$config['remember_users'] = TRUE; // Allow users to be remembered and enable auto-login

/*
 * | -------------------------------------------------------------------------
 * | Cookie options.
 * | -------------------------------------------------------------------------
 * | remember_cookie_name Default: remember_code
 * | identity_cookie_name Default: identity
 */
$config['remember_cookie_name'] = 'remember_code';
$config['identity_cookie_name'] = 'identity';

/**
 * encryption,初始化系统的时候调整，系统运行中不允许调整。否则密码全部失效
 */
$config['encryption'] = 'sha1'; // sha1 or crypt
