<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * FileName : Auth_lang.php
 * DateTime : 2015年8月23日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
    
// Account Creation
$lang['account_creation_successful'] = '创建帐户成功';
$lang['account_creation_unsuccessful'] = '无法创建帐户';
$lang['account_creation_duplicate_email'] = '电子邮件已经使用或无效';
$lang['account_creation_duplicate_username'] = '用户名已被使用或无效';
$lang['account_creation_missing_default_group'] = '默认用户组未设置';
$lang['account_creation_invalid_default_group'] = '无效的默认用户组';

// Password
$lang['password_change_successful'] = '密码修改成功';
$lang['password_change_unsuccessful'] = '无法更改密码';
$lang['forgot_password_successful'] = '密码重置邮件已发送';
$lang['forgot_password_unsuccessful'] = '无法重置密码';

// Activation
$lang['activate_successful'] = '账号激活';
$lang['activate_unsuccessful'] = '无法激活帐号';
$lang['deactivate_successful'] = '帐号已停用';
$lang['deactivate_unsuccessful'] = '无法激活帐户';
$lang['activation_email_successful'] = '激活邮件已发送';
$lang['activation_email_unsuccessful'] = '无法发送激活邮件';

// Login / Logout
$lang['login_successful'] = '登录成功';
$lang['login_unsuccessful'] = '登录出错';
$lang['login_unsuccessful_not_active'] = '账户未审核';
$lang['login_timeout'] = '用户被暂时锁定，稍候再试。';
$lang['logout_successful'] = '注销成功';
$lang['password_error'] = "用户密码错误";
$lang['user_error'] = "不存在的用户";
$lang['username_and_password_can_not_be_empty'] = "用户名或密码不能为空";
$lang['captcha_can_not_be_empty_and_error'] = "验证码不为空或者验证码错误";

// Account Changes
$lang['update_successful'] = '帐户信息已成功更新';
$lang['update_unsuccessful'] = '无法更新帐户信息';
$lang['delete_successful'] = '用户已删除';
$lang['delete_unsuccessful'] = '无法删除用户';

// Groups
$lang['group_creation_successful'] = '用户组创建成功';
$lang['group_already_exists'] = '已经存在的用户组名称';
$lang['group_update_successful'] = '用户组信息更新';
$lang['group_delete_successful'] = '用户组已删除';
$lang['group_delete_unsuccessful'] = '无法删除用户组';
$lang['group_delete_notallowed'] = '不能删除管理员组';
$lang['group_name_required'] = '用户组名称为必填字段';

// Activation Email
$lang['email_activation_subject'] = '账号激活';
$lang['email_activate_heading'] = '激活账户 %s';
$lang['email_activate_subheading'] = '请点击此链接 %s.';
$lang['email_activate_link'] = '激活你的帐号';

// Forgot Password Email
$lang['email_forgotten_password_subject'] = '忘记密码验证';
$lang['email_forgot_password_heading'] = '重设密码 %s';
$lang['email_forgot_password_subheading'] = '请点击此链接 %s.';
$lang['email_forgot_password_link'] = '重置你的密码';

// New Password Email
$lang['email_new_password_subject'] = '新密码';
$lang['email_new_password_heading'] = '新密码 %s';
$lang['email_new_password_subheading'] = '您的密码已重置: %s';

// Errors
$lang['error_csrf'] = '这种形式的表单没有通过我们的安全检查。';

// Login
$lang['login_heading'] = '用户登录';
$lang['login_subheading'] = '请使用Email或用户名，密码进行登陆';
$lang['login_identity_label'] = '用户名:';
$lang['login_password_label'] = '密码:';
$lang['login_remember_label'] = '记住密码:';
$lang['login_submit_btn'] = '登录';
$lang['login_forgot_password'] = '忘记密码?';

// Index
$lang['index_heading'] = '用户';
$lang['index_subheading'] = '下面是用户的列表。';
$lang['index_fname_th'] = 'First Name';
$lang['index_lname_th'] = 'Last Name';
$lang['index_email_th'] = 'Email';
$lang['index_groups_th'] = 'Groups';
$lang['index_status_th'] = 'Status';
$lang['index_action_th'] = 'Action';
$lang['index_active_link'] = 'Active';
$lang['index_inactive_link'] = 'Inactive';
$lang['index_create_user_link'] = 'Create a new user';
$lang['index_create_group_link'] = 'Create a new group';

// Deactivate User
$lang['deactivate_heading'] = 'Deactivate User';
$lang['deactivate_subheading'] = 'Are you sure you want to deactivate the user \'%s\'';
$lang['deactivate_confirm_y_label'] = 'Yes:';
$lang['deactivate_confirm_n_label'] = 'No:';
$lang['deactivate_submit_btn'] = 'Submit';
$lang['deactivate_validation_confirm_label'] = 'confirmation';
$lang['deactivate_validation_user_id_label'] = 'user ID';

// Create User
$lang['create_user_heading'] = 'Create User';
$lang['create_user_subheading'] = 'Please enter the user\'s information below.';
$lang['create_user_fname_label'] = 'First Name:';
$lang['create_user_lname_label'] = 'Last Name:';
$lang['create_user_company_label'] = 'Company Name:';
$lang['create_user_email_label'] = 'Email:';
$lang['create_user_phone_label'] = 'Phone:';
$lang['create_user_password_label'] = 'Password:';
$lang['create_user_password_confirm_label'] = 'Confirm Password:';
$lang['create_user_submit_btn'] = 'Create User';
$lang['create_user_validation_fname_label'] = 'First Name';
$lang['create_user_validation_lname_label'] = 'Last Name';
$lang['create_user_validation_email_label'] = 'Email Address';
$lang['create_user_validation_phone1_label'] = 'First Part of Phone';
$lang['create_user_validation_phone2_label'] = 'Second Part of Phone';
$lang['create_user_validation_phone3_label'] = 'Third Part of Phone';
$lang['create_user_validation_company_label'] = 'Company Name';
$lang['create_user_validation_password_label'] = 'Password';
$lang['create_user_validation_password_confirm_label'] = 'Password Confirmation';

// Edit User
$lang['edit_user_heading'] = 'Edit User';
$lang['edit_user_subheading'] = 'Please enter the user\'s information below.';
$lang['edit_user_fname_label'] = 'First Name:';
$lang['edit_user_lname_label'] = 'Last Name:';
$lang['edit_user_company_label'] = 'Company Name:';
$lang['edit_user_email_label'] = 'Email:';
$lang['edit_user_phone_label'] = 'Phone:';
$lang['edit_user_password_label'] = 'Password: (if changing password)';
$lang['edit_user_password_confirm_label'] = 'Confirm Password: (if changing password)';
$lang['edit_user_groups_heading'] = 'Member of groups';
$lang['edit_user_submit_btn'] = 'Save User';
$lang['edit_user_validation_fname_label'] = 'First Name';
$lang['edit_user_validation_lname_label'] = 'Last Name';
$lang['edit_user_validation_email_label'] = 'Email Address';
$lang['edit_user_validation_phone1_label'] = 'First Part of Phone';
$lang['edit_user_validation_phone2_label'] = 'Second Part of Phone';
$lang['edit_user_validation_phone3_label'] = 'Third Part of Phone';
$lang['edit_user_validation_company_label'] = 'Company Name';
$lang['edit_user_validation_groups_label'] = 'Groups';
$lang['edit_user_validation_password_label'] = 'Password';
$lang['edit_user_validation_password_confirm_label'] = 'Password Confirmation';

// Create Group
$lang['create_group_title'] = 'Create Group';
$lang['create_group_heading'] = 'Create Group';
$lang['create_group_subheading'] = 'Please enter the group information below.';
$lang['create_group_name_label'] = 'Group Name:';
$lang['create_group_desc_label'] = 'Description:';
$lang['create_group_submit_btn'] = 'Create Group';
$lang['create_group_validation_name_label'] = 'Group Name';
$lang['create_group_validation_desc_label'] = 'Description';

// Edit Group
$lang['edit_group_title'] = 'Edit Group';
$lang['edit_group_saved'] = 'Group Saved';
$lang['edit_group_heading'] = 'Edit Group';
$lang['edit_group_subheading'] = 'Please enter the group information below.';
$lang['edit_group_name_label'] = 'Group Name:';
$lang['edit_group_desc_label'] = 'Description:';
$lang['edit_group_submit_btn'] = 'Save Group';
$lang['edit_group_validation_name_label'] = 'Group Name';
$lang['edit_group_validation_desc_label'] = 'Description';

// Change Password
$lang['change_password_heading'] = 'Change Password';
$lang['change_password_old_password_label'] = 'Old Password:';
$lang['change_password_new_password_label'] = 'New Password (at least %s characters long):';
$lang['change_password_new_password_confirm_label'] = 'Confirm New Password:';
$lang['change_password_submit_btn'] = 'Change';
$lang['change_password_validation_old_password_label'] = 'Old Password';
$lang['change_password_validation_new_password_label'] = 'New Password';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirm New Password';

// Forgot Password
$lang['forgot_password_heading'] = '忘记密码';
$lang['forgot_password_subheading'] = '请输入你的  %s ，我们将会给你发送一封邮件，根据邮件提示进行密码重置。';
$lang['forgot_password_email_label'] = '%s:';
$lang['forgot_password_submit_btn'] = '提交';
$lang['forgot_password_validation_email_label'] = 'Email Address';
$lang['forgot_password_username_identity_label'] = '用户名';
$lang['forgot_password_email_identity_label'] = 'Email';
$lang['forgot_password_email_not_found'] = 'No record of that email address.';
$lang['forgot_password_username_not_found'] = '用户名不存在';

// Reset Password
$lang['reset_password_heading'] = 'Change Password';
$lang['reset_password_new_password_label'] = 'New Password (at least %s characters long):';
$lang['reset_password_new_password_confirm_label'] = 'Confirm New Password:';
$lang['reset_password_submit_btn'] = 'Change';
$lang['reset_password_validation_new_password_label'] = 'New Password';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirm New Password';

/* Permissions */
$lang['action_allowed'] = '您没有权限';
$lang['action_not_allowed'] = '你没有相关权限，请联系系统管理员。';
$lang['err_user_no_group'] = "用户未指定用户组,默认不允许访问。";
$lang['err_group_not_defined_or_disabled'] = "用户组被禁止或不存在,默认不允许访问。";
$lang['err_node_not_defined_or_disabled'] = "方法节点未在节点表定义或被禁用,默认不允许访问";

/* web fornt */
$lang['create_user'] = "创建用户";

$lang['user_permission_is_not_correct'] = "用户权限不正确";