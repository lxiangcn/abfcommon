<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : Model_member_auth.php
 * DateTime : 2015年5月12日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_member_auth extends MY_Model {

    /**
     * Holds an array of tables used
     *
     * @var array
     *
     */
    public $tables = array();
    /**
     * Holds an array of join used
     *
     * @var array
     *
     */
    public $join = array();

    /**
     * message (uses lang file)
     *
     * @var string
     *
     */
    public $messages;

    /**
     * error message (uses lang file)
     *
     * @var string
     *
     */
    public $errors;

    /**
     * username
     *
     * @var unknown
     */
    public $identity_column;

    /**
     * caching of groups
     *
     * @var array
     *
     */
    public $_cache_groups = array();

    /**
     * caching of users and their groups
     *
     * @var array
     *
     */
    public $_cache_user_in_group = array();

    /**
     * 验证码存放session name
     *
     * @var unknown
     */
    public $captcha = "captcha";

    /**
     * 禁止任何人访问
     *
     * @var int
     */
    CONST NOBODY = 0;

    /**
     * 拥有权限的人可以访问
     *
     * @var int
     */
    CONST AUTHORITY = 1;

    /**
     * 允许任何人可以访问
     *
     * @var int
     */
    CONST EVERYBODY = 2;

    /**
     * 获取密码加密方式
     *
     * @var string
     */
    private $encryption = "sha1";

    /**
     * 用户登录状态session
     * @var string
     */
    private $local_user_info = "";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->config('auth/member_auth', TRUE);
        $this->load->helper('cookie');
        $this->load->helper('date');
        $this->lang->load('auth/auth');

        // initialize db tables data
        $this->tables = $this->config->item('tables', 'member_auth');
        $this->join   = $this->config->item('join', 'member_auth');

        // initialize data
        $this->identity_column = $this->config->item('identity', 'member_auth');
        $this->salt_length     = $this->config->item('salt_length', 'member_auth');
        $this->encryption      = $this->config->item('encryption', 'member_auth');
        $this->local_user_info = $this->config->item("local_user_info", "member_auth");

        // initialize messages and error
        $this->messages = array();
        $this->errors   = array();

        // load table
        $this->load_table($this->tables['users']);
    }

    /**
     * 判断是否管理员
     *
     * @access public
     * @param boolean $return
     *            是否为返回模式
     * @return boolean
     */
    public function is_admin($id = FALSE) {
        if (!$id) {
            return FALSE;
        }
        $admin_group = $this->config->item('admin_group', 'member_auth');
        return $this->in_group($admin_group, $id);
    }

    /**
     * 分组检查
     *
     * @param string $default_group_id
     * @return boolean
     */
    public function group_check($default_group_id = NULL) {
        if (empty($default_group_id)) {
            return FALSE;
        }
        $query = $this->db->get_where($this->tables['groups'], array(
            'id' => $default_group_id,
        ), 1)->row();

        return isset($query->id) ? $query->id : FALSE;
    }

    /**
     * 权限检查
     *
     * @param unknown $set_security
     * @param unknown $user_id
     * @param unknown $class_name
     * @param unknown $method_name
     * @return boolean
     */
    public function checkAccess($security, $user_id, $class_name, $method_name) {
        switch ($security) {
        case self::NOBODY:
            return FALSE;
            break;
        case self::EVERYBODY:
            return TRUE;
            break;
        case self::AUTHORITY:
            $group_id = @$this->_getUserGroup($user_id); // 检查用户是否存在分组
            if (!$group_id) {
                $this->set_error('err_user_no_group');
                return FALSE;
            }
            if (!$this->_getGroup($group_id)) {
                // 检查用户组
                $this->set_error('err_group_not_defined_or_disabled');
                return FALSE;
            }
            $node = $this->_getNode($class_name, $method_name); // 获取节点
            if (!$node) {
                $this->set_error('err_node_not_defined_or_disabled');
                return FALSE;
            }
            /**
             * 检查方法节点访问权限
             */
            if (is_array($node)) {
                foreach ($node as $v) {
                    if ($this->_getAccess($group_id, $v)) {
                        return TRUE;
                    }
                }
                return FALSE;
            } else {
                if ($this->_getAccess($group_id, $node)) {
                    // 节点表存在对应用户组与节点信息,允许访问
                    return TRUE;
                }
            }
            return FALSE;
            break;
        default:
            return FALSE;
        }
    }

    /**
     * update
     *
     * @return bool
     *
     */
    public function update_user($id, array $data, $groups = array()) {
        $user = $this->user($id);

        $this->db->trans_begin();

        if (array_key_exists($this->identity_column, $data) && $this->identity_check($data[$this->identity_column]) && $user->{$this->identity_column} !== $data[$this->identity_column]) {
            $this->db->trans_rollback();
            $this->set_error('account_creation_duplicate_' . $this->identity_column);
            $this->set_error('update_unsuccessful');

            return FALSE;
        }

        // Filter the data passed
        $data = $this->_filter_data($this->tables['users'], $data);

        if (array_key_exists('username', $data) || array_key_exists('password', $data) || array_key_exists('email', $data)) {
            if (array_key_exists('password', $data)) {
                if (!empty($data['password'])) {
                    $data['password'] = $this->hash_password($data['password'], $user->salt);
                } else {
                    unset($data['password']);
                }
            }
        }

        $this->db->update($this->tables['users'], $data, array(
            'id' => $user->id,
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $this->set_error('update_unsuccessful');
            return FALSE;
        }

        // 设置用户分组
        if (!$this->config->item('default_group', 'member_auth') && empty($groups)) {
            $this->set_error('account_creation_missing_default_group');
            return FALSE;
        }
        // check if the default set in config exists in database
        $query = $this->db->get_where($this->tables['groups'], array(
            'group_name' => $this->config->item('default_group', 'member_auth'),
        ), 1)->row();
        if (!isset($query->id) && empty($groups)) {
            $this->set_error('account_creation_invalid_default_group');
            return FALSE;
        }

        // capture default group details
        $default_group = $query;

        // add in groups array if it doesn't exits and stop adding into default group if default group ids are set
        if (isset($default_group->id) && empty($groups)) {
            $groups[] = $default_group->id;
        }

        if (!empty($groups)) {
            // clear groups
            $this->remove_from_group(NULL, $id);
            // add to groups
            foreach ($groups as $group) {
                $this->add_to_group($group, $id);
            }
        }

        $this->db->trans_commit();

        $this->set_message('update_successful');
        return TRUE;
    }

    /**
     * login
     *
     * @return bool
     *
     */
    public function login($identity, $password, $remember = FALSE) {
        if (empty($identity) || empty($password)) {
            $this->set_error('username_and_password_can_not_be_empty');
            return FALSE;
        }

        $query = $this->db->select($this->identity_column . ', username, email, id, password, active, last_login')->where($this->identity_column, $identity)->limit(1)->order_by('id', 'desc')->get($this->tables['users']);

        // 设置密码错误次数尝试
        if ($this->is_time_locked_out($identity)) {
            $this->hash_password($password);
            $this->set_error('login_timeout');
            return FALSE;
        }

        if ($query->num_rows() === 1) {
            $user = $query->row();

            $password = $this->hash_password_db($user->id, $password);

            if ($password === TRUE) {

                if ($user->active == 0) {
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }
                // 设置登录状态
                $this->set_session($user);
                // 更新最后登录时间
                $this->update_last_login($user->id);
                // 清除尝试登录次数
                $this->clear_login_attempts($identity);

                //记住用户登录
                if ($remember && $this->config->item('remember_users', 'member_auth')) {
                    $this->remember_user($user->id);
                }

                $this->set_message('login_successful');

                return TRUE;
            } else {
                $this->set_error('password_error');
            }
        } else {
            $this->set_error('user_error');
        }

        $this->hash_password($password);

        $this->increase_login_attempts($identity);

        $this->set_error('login_unsuccessful');

        return FALSE;
    }

    /**
     * register
     *
     * @return void
     */
    public function register($username, $password, $email, $additional_data = array(), $groups = array()) {
        $manual_activation = $this->config->item('manual_activation', 'member_auth');

        if ($this->identity_column == 'email' && $this->member_auth_model->email_check($email)) {
            $this->set_error('account_creation_duplicate_email');
            return FALSE;
        } elseif ($this->identity_column == 'username' && $this->member_auth_model->username_check($username)) {
            $this->set_error('account_creation_duplicate_username');
            return FALSE;
        } elseif (!$this->config->item('default_group', 'member_auth') && empty($groups)) {
            $this->set_error('account_creation_missing_default_group');
            return FALSE;
        }
        // check if the default set in config exists in database
        $query = $this->db->get_where($this->tables['groups'], array(
            'group_name' => $this->config->item('default_group', 'member_auth'),
        ), 1)->row();

        if (!isset($query->id) && empty($groups)) {
            $this->set_error('account_creation_invalid_default_group');
            return FALSE;
        }

        // capture default group details
        $default_group = $query;

        // Users table.
        $data = array(
            'username' => $username,
            'email'    => $email,
            'created'  => time(),
            'active'   => ($manual_activation === false ? 1 : 0),
        );

        // IP Address
        $ip_address                    = $this->input->ip_address();
        $salt                          = $this->salt();
        $password                      = $this->hash_password($password, $salt);
        $additional_data['ip_address'] = $ip_address;
        $additional_data['salt']       = $salt;
        $additional_data['password']   = $password;

        $user_data = array_merge($additional_data, $data);

        $user_data = $this->_filter_data($this->tables['users'], $user_data);
        $this->db->insert($this->tables['users'], $user_data);
        $id = $this->db->insert_id();

        // add in groups array if it doesn't exits and stop adding into default group if default group ids are set
        if (isset($default_group->id) && empty($groups)) {
            $groups[] = $default_group->id;
        }

        if (!empty($groups)) {
            // add to groups
            foreach ($groups as $group) {
                $this->add_to_group($group, $id);
            }
        }

        if ($id !== FALSE) {
            $this->set_message('account_creation_successful');
            return $id;
        } else {
            $this->set_error('account_creation_unsuccessful');
            return FALSE;
        }
    }

    /**
     * logout
     *
     * @return void
     *
     */
    public function logout() {
        // delete the remember me cookies if they exist
        if (get_cookie($this->config->item('identity_cookie_name', 'member_auth'))) {
            delete_cookie($this->config->item('identity_cookie_name', 'member_auth'));
        }
        if (get_cookie($this->config->item('remember_cookie_name', 'member_auth'))) {
            delete_cookie($this->config->item('remember_cookie_name', 'member_auth'));
        }

        $this->session->set_userdata($this->local_user_info, '');

        return TRUE;
    }

    /**
     * 忘记密码
     *
     * @param unknown $identity
     */
    public function forgotten_password($identity) {
        if (empty($identity)) {
            return FALSE;
        }

        $activation_code_part = "";
        if (function_exists("openssl_random_pseudo_bytes")) {
            $activation_code_part = openssl_random_pseudo_bytes(128);
        }

        for ($i = 0; $i < 1024; $i++) {
            $activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
        }

        $key = $this->hash_code($activation_code_part . $identity);

        $update = array(
            'lost_password_key'    => $key,
            'lost_password_expire' => time(),
        );

        $this->db->update($this->tables['users'], $update, array(
            $this->identity_column => $identity,
        ));

        $return = $this->db->affected_rows() == 1;

        return $return;
    }

    /**
     * 找回密码验证，获取新的密码
     *
     * @param unknown $code
     * @param string $salt
     * @return boolean|string
     */
    public function forgotten_password_complete($code, $salt = FALSE) {
        if (empty($code)) {
            return FALSE;
        }

        $profile = $this->where('lost_password_key', $code)->users()->row();

        if ($profile) {
            if ($this->config->item('lost_password_expire', 'member_auth') > 0) {
                $expiration = $this->config->item('lost_password_expire', 'member_auth');
                if (time() - $profile->forgotten_password_time > $expiration) {
                    $this->set_error('forgot_password_expired');
                    return FALSE;
                }
            }

            $password = $this->salt();

            $data = array(
                'password'          => $this->hash_password($password, $salt),
                'lost_password_key' => NULL,
                'active'            => 1,
            );

            $this->db->update($this->tables['users'], $data, array(
                'lost_password_key' => $code,
            ));

            return $password;
        }

        return FALSE;
    }

    /**
     * delete_user
     *
     * @return bool
     *
     */
    public function delete_user($id) {
        $this->db->trans_begin();

        // remove user from groups
        $this->remove_from_group(NULL, $id);

        // delete user from users table should be placed after remove from group
        $this->db->delete($this->tables['users'], array(
            'id' => $id,
        ));

        // if user does not exist in database then it returns FALSE else removes the user from groups
        if ($this->db->affected_rows() == 0) {
            return FALSE;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('delete_successful');
        return TRUE;
    }

    /**
     * 用户审核
     *
     * @param int $id
     * @param int $active  状态值 0:待审核；1：可以用；2：禁用
     */
    public function activate($id = NULL, $active = 2) {
        if (!isset($id)) {
            $this->set_error('activate_unsuccessful');
            return FALSE;
        }
        $data = array(
            'active' => $active,
        );

        $this->db->update($this->tables['users'], $data, array(
            'id' => $id,
        ));

        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->set_message('activate_successful');
        } else {
            $this->set_error('activate_unsuccessful');
        }

        return $return;
    }

    /**
     * 解除禁用
     *
     * @param string $id
     */
    public function deactivate($id = NULL) {
        if (!isset($id)) {
            $this->set_error('deactivate_unsuccessful');
            return FALSE;
        }

        $data = array(
            'active' => 0,
        );

        $this->db->update($this->tables['users'], $data, array(
            'id' => $id,
        ));

        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->set_message('deactivate_successful');
        } else {
            $this->set_error('deactivate_unsuccessful');
        }

        return $return;
    }

    /**
     * 清除密码找回code
     *
     * @param unknown $code
     */
    public function clear_forgotten_password_code($code) {
        if (empty($code)) {
            return FALSE;
        }
        $this->db->where('lost_password_key', $code);
        if ($this->db->count_all_results($this->tables['users']) > 0) {
            $data = array(
                'lost_password_key'    => NULL,
                'lost_password_expire' => NULL,
            );
            $this->db->update($this->tables['users'], $data, array(
                'lost_password_key' => $code,
            ));
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 密码重置
     *
     * @param unknown $identity
     * @param unknown $new
     */
    public function reset_password($identity, $new) {
        if (!$this->identity_check($identity)) {
            return FALSE;
        }
        $query = $this->db->select('id, password, salt')->where($this->identity_column, $identity)->limit(1)->order_by('id', 'desc')->get($this->tables['users']);
        if ($query->num_rows() !== 1) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }
        $result = $query->row();

        $new = $this->hash_password($new, $result->salt);

        $data = array(
            'password'             => $new,
            'lost_password_key'    => NULL,
            'lost_password_expire' => NULL,
        );
        $this->db->update($this->tables['users'], $data, array(
            $this->identity_column => $identity,
        ));
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->set_message('password_change_successful');
        } else {
            $this->set_error('password_change_unsuccessful');
        }
        return $return;
    }

    /**
     * 修改密码
     *
     * @param unknown $identity
     * @param unknown $old
     * @param unknown $new
     */
    public function change_password($identity, $old, $new) {
        $query = $this->db->select('id, password, salt')->where($this->identity_column, $identity)->limit(1)->order_by('id', 'desc')->get($this->tables['users']);
        if ($query->num_rows() !== 1) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $user = $query->row();

        $old_password_matches = $this->hash_password_db($user->id, $old);

        if ($old_password_matches === TRUE) {
            $hashed_new_password = $this->hash_password($new, $user->salt);
            $data                = array(
                'password'      => $hashed_new_password,
                'remember_code' => NULL,
            );

            $successfully_changed_password_in_db = $this->db->update($this->tables['users'], $data, array(
                $this->identity_column => $identity,
            ));
            if ($successfully_changed_password_in_db) {
                $this->set_message('password_change_successful');
            } else {
                $this->set_error('password_change_unsuccessful');
            }
            return $successfully_changed_password_in_db;
        }
        $this->set_error('password_change_unsuccessful');
        return FALSE;
    }

    /**
     * Get a boolean to determine if an account should be locked out due to
     * exceeded login attempts within a given period
     *
     * @return boolean
     */
    public function is_time_locked_out($identity) {
        return $this->is_max_login_attempts_exceeded($identity) && $this->get_last_attempt_time($identity) > time() - $this->config->item('lockout_time', 'member_auth');
    }

    /**
     * Get the time of the last time a login attempt occured from given IP-address or identity
     *
     * @param string $identity
     * @return int
     */
    public function get_last_attempt_time($identity) {
        if ($this->config->item('track_login_attempts', 'member_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());

            $this->db->select_max('time');
            if ($this->config->item('track_login_ip_address', 'member_auth')) {
                $this->db->where('ip_address', $ip_address);
            } else if (strlen($identity) > 0) {
                $this->db->or_where('login', $identity);
            }

            $qres = $this->db->get($this->tables['login_attempts'], 1);

            if ($qres->num_rows() > 0) {
                return $qres->row()->time;
            }
        }

        return 0;
    }

    /**
     * is_max_login_attempts_exceeded
     *
     * @param unknown $identity
     */
    public function is_max_login_attempts_exceeded($identity) {
        if ($this->config->item('track_login_attempts', 'member_auth')) {
            $max_attempts = $this->config->item('maximum_login_attempts', 'member_auth');
            if ($max_attempts > 0) {
                $attempts = $this->get_attempts_num($identity);
                return $attempts >= $max_attempts;
            }
        }
        return FALSE;
    }

    /**
     * 获取尝试登录失败次数，根据 identity 或者 ip地址
     *
     * @param string $identity
     * @return int
     */
    function get_attempts_num($identity) {
        if ($this->config->item('track_login_attempts', 'member_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());
            $this->db->select('1', FALSE);
            if ($this->config->item('track_login_ip_address', 'member_auth')) {
                $this->db->where('ip_address', $ip_address);
            } else if (strlen($identity) > 0) {
                $this->db->or_where('login', $identity);
            }

            $qres = $this->db->get($this->tables['login_attempts']);
            return $qres->num_rows();
        }
        return 0;
    }

    /**
     * 获取session用户数据
     * @return array  or bool
     */
    public function get_userdata() {
        if ($this->session->userdata($this->local_user_info)) {
            return $this->session->userdata($this->local_user_info);
        }
        return false;
    }

    /**
     * 获取登录用户的id
     *
     * @return integer
     *
     */
    public function get_user_id() {
        if ($this->session->userdata($this->local_user_info)) {
            $user_id = $this->session->userdata($this->local_user_info)['user_id'];
        }
        if (!empty($user_id)) {
            return $user_id;
        }
        return null;
    }

    /**
     * set_message
     *
     * Set a message
     *
     * @return void
     *
     */
    public function set_message($message) {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * messages
     *
     * Get the messages
     *
     * @return void
     *
     */
    public function messages() {
        $_output = '';
        foreach ($this->messages as $message) {
            $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
            $_output .= $messageLang;
        }
        return $_output;
    }

    /**
     * set_error
     *
     * Set an error message
     *
     * @return void
     *
     */
    public function set_error($error) {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * errors
     *
     * Get the error message
     *
     * @return void
     * @author Ben Edmunds
     *
     */
    public function errors() {
        $_output = '';
        foreach ($this->errors as $error) {
            $errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
            $_output .= $errorLang;
        }
        return $_output;
    }

    /**
     * Checks username
     *
     * @return bool
     *
     */
    public function username_check($username = '') {
        if (empty($username)) {
            return FALSE;
        }
        return $this->db->where('username', $username)->order_by("id", "ASC")->limit(1)->count_all_results($this->tables['users']) > 0;
    }

    /**
     * Checks email
     *
     * @return bool
     *
     */
    public function email_check($email = '') {
        if (empty($email)) {
            return FALSE;
        }
        return $this->db->where('email', $email)->order_by("id", "ASC")->limit(1)->count_all_results($this->tables['users']) > 0;
    }

    /**
     * Identity check
     *
     * @return bool
     *
     */
    public function identity_check($identity = '') {
        if (empty($identity)) {
            return FALSE;
        }
        return $this->db->where($this->identity_column, $identity)->count_all_results($this->tables['users']) > 0;
    }

    /**
     * get_users_groups
     *
     * @return array
     * @author Ben Edmunds
     *
     */
    public function get_users_groups($id = FALSE) {
        return $this->db->select($this->tables['users_groups'] . '.' . $this->join['groups'] . ' as id, ' . $this->tables['groups'] . '.group_name, ' . $this->tables['groups'] . '.description')->where($this->tables['users_groups'] . '.' . $this->join['users'], $id)->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . '=' . $this->tables['groups'] . '.id')->get($this->tables['users_groups']);
    }

    /**
     * add_to_group
     *
     * @return bool
     * @author Ben Edmunds
     *
     */
    public function add_to_group($group_ids, $user_id = false) {
        if (!is_array($group_ids)) {
            $group_ids = array(
                $group_ids,
            );
        }

        $return = 0;

        // Then insert each into the database
        foreach ($group_ids as $group_id) {
            if ($this->db->insert($this->tables['users_groups'], array(
                $this->join['groups'] => (int) $group_id,
                $this->join['users']  => (int) $user_id,
            ))) {
                if (isset($this->_cache_groups[$group_id])) {
                    $group_name = $this->_cache_groups[$group_id];
                } else {
                    $group                          = $this->groups($group_id);
                    $group_name                     = $group[0]->group_name;
                    $this->_cache_groups[$group_id] = $group_name;
                }
                $this->_cache_user_in_group[$user_id][$group_id] = $group_name;

                // Return the number of groups added
                $return += 1;
            }
        }

        return $return;
    }

    /**
     * remove_from_group
     *
     * @return bool
     *
     */
    public function remove_from_group($group_ids = false, $user_id = false) {

        // user id is required
        if (empty($user_id)) {
            return FALSE;
        }

        // if group id(s) are passed remove user from the group(s)
        if (!empty($group_ids)) {
            if (!is_array($group_ids)) {
                $group_ids = array(
                    $group_ids,
                );
            }

            foreach ($group_ids as $group_id) {
                $this->db->delete($this->tables['users_groups'], array(
                    $this->join['groups'] => (int) $group_id,
                    $this->join['users']  => (int) $user_id,
                ));
                if (isset($this->_cache_user_in_group[$user_id]) && isset($this->_cache_user_in_group[$user_id][$group_id])) {
                    unset($this->_cache_user_in_group[$user_id][$group_id]);
                }
            }

            $return = TRUE;
        } else {
            if ($return = $this->db->delete($this->tables['users_groups'], array(
                $this->join['users'] => (int) $user_id,
            ))) {
                $this->_cache_user_in_group[$user_id] = array();
            }
        }
        return $return;
    }

    /**
     * group
     *
     * @return object
     *
     */
    public function groups($id = NULL) {
        if (isset($id)) {
            $this->db->where($this->tables['groups'] . '.id', $id);
        }

        $this->db->limit(1);
        $this->db->order_by('id', 'desc');
        $result = $this->db->get($this->tables['groups'])->result();
        return $result;
    }

    /**
     * 设置密码
     *
     * @param unknown $password
     * @param string $salt
     * @return boolean|string
     */
    public function hash_password($password, $salt = NULL) {
        if (empty($password)) {
            return FALSE;
        }
        $salt = $salt ? $salt : $this->salt();
        if ('crypt' === $this->encryption) {
            return crypt($password);
        } else {
            return sha1(sha1($password) . $salt);
        }
    }

    /**
     * 检查用户密码是否正确
     *
     * @param unknown $id
     * @param unknown $password
     * @return boolean
     */
    public function hash_password_db($id, $password) {
        if (empty($id) || empty($password)) {
            return FALSE;
        }

        $query = $this->db->select('password, salt')->where('id', $id)->limit(1)->order_by('id', 'desc')->get($this->tables['users']);

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }
        if ('crypt' === $this->encryption) {
            $db_password = crypt($password, $hash_password_db->password);
        } else {
            $db_password = sha1(sha1($password) . $hash_password_db->salt);
        }

        if ($db_password == $hash_password_db->password) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 获取一个sha1字符串
     *
     * @param unknown $password
     */
    public function hash_code($password) {
        return $this->hash_password($password);
    }

    /**
     * set_session 设置登录状态
     *
     * @return bool
     *
     */
    public function set_session($user) {
        $session_data = array(
            'identity'       => $user->{$this->identity_column},
            'username'       => $user->username,
            'email'          => $user->email,
            'user_id'        => $user->id, // everyone likes to overwrite id so we'll use user_id
            'old_last_login' => $user->last_login,
        );

        $this->session->set_userdata($this->local_user_info, $session_data);

        return TRUE;
    }

    /**
     * remember_user
     *
     * @return bool
     **/
    public function remember_user($id) {
        if (!$id) {
            return FALSE;
        }
        $user = $this->find(array("id" => $id));
        $salt = $this->salt();
        $this->db->update($this->tables['users'], array('remember_code' => $salt), array('id' => $id));

        if ($this->db->affected_rows() > -1) {
            // if the user_expire is set to zero we'll set the expiration two years from now.
            if ($this->config->item('user_expire', 'member_auth') === 0) {
                $expire = (60 * 60 * 24 * 365 * 2);
            }
            // otherwise use what is set
            else {
                $expire = $this->config->item('user_expire', 'member_auth');
            }
            set_cookie(array(
                'name'   => $this->config->item('identity_cookie_name', 'member_auth'),
                'value'  => $user->{$this->identity_column},
                'expire' => $expire,
            ));
            set_cookie(array(
                'name'   => $this->config->item('remember_cookie_name', 'member_auth'),
                'value'  => $salt,
                'expire' => $expire,
            ));
            return TRUE;
        }
        return FALSE;
    }

    /**
     * login_remembed_user
     *
     * @return bool
     **/
    public function login_remembered_user() {
        // check for valid data
        if (!get_cookie($this->config->item('identity_cookie_name', 'member_auth'))
            || !get_cookie($this->config->item('remember_cookie_name', 'member_auth'))
            || !$this->identity_check(get_cookie($this->config->item('identity_cookie_name', 'member_auth')))) {
            return FALSE;
        }
        // get the user
        $query = $this->db->select($this->identity_column . ', id, email, last_login')
            ->where($this->identity_column, get_cookie($this->config->item('identity_cookie_name', 'member_auth')))
            ->where('remember_code', get_cookie($this->config->item('remember_cookie_name', 'member_auth')))
            ->limit(1)
            ->order_by('id', 'desc')
            ->get($this->tables['users']);
        // if the user was found, sign them in
        if ($query->num_rows() == 1) {
            $user = $query->row();
            $this->update_last_login($user->id);
            $this->set_session($user);
            // extend the users cookies if the option is enabled
            if ($this->config->item('user_extend_on_login', 'member_auth')) {
                $this->remember_user($user->id);
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * salt
     *
     * @return string
     */
    public function salt() {
        return substr(sha1(time() . uniqid()), 0, $this->salt_length);
    }

    /**
     * 更新用户登录时间
     *
     * @return bool
     *
     */
    public function update_last_login($id) {
        $this->db->update($this->tables['users'], array(
            'last_login' => time(),
        ), array(
            'id' => $id,
        ));

        return $this->db->affected_rows() == 1;
    }

    /**
     * 清除尝试登录次数
     *
     * @param string $identity
     *
     */
    public function clear_login_attempts($identity, $expire_period = 86400) {
        if ($this->config->item('track_login_attempts', 'member_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());
            $this->db->where(array(
                'ip_address' => $ip_address,
                'login'      => $identity,
            ));
            // Purge obsolete login attempts
            $this->db->or_where('time <', time() - $expire_period, FALSE);
            return $this->db->delete($this->tables['login_attempts']);
        }
        return FALSE;
    }

    /**
     * 添加尝试登录次数
     *
     * @param string $identity
     *
     */
    public function increase_login_attempts($identity) {
        if ($this->config->item('track_login_attempts', 'member_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());
            return $this->db->insert($this->tables['login_attempts'], array(
                'ip_address' => $ip_address,
                'login'      => $identity,
                'time'       => time(),
            ));
        }
        return FALSE;
    }

    /**
     * 检查当前用户是否为管理组
     *
     * @return bool
     *
     */
    public function in_group($check_group, $id = false, $check_all = false) {
        if (!is_array($check_group)) {
            $check_group = array(
                $check_group,
            );
        }

        if (isset($this->_cache_user_in_group[$id])) {
            $groups_array = $this->_cache_user_in_group[$id];
        } else {
            $users_groups = $this->get_users_groups($id)->result();
            $groups_array = array();
            foreach ($users_groups as $group) {
                $groups_array[$group->id] = $group->group_name;
            }
            $this->_cache_user_in_group[$id] = $groups_array;
        }
        foreach ($check_group as $key => $value) {
            $groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

            /**
             * if !all (default), in_array
             * if all, !in_array
             */
            if (in_array($value, $groups) xor $check_all) {
                /**
                 * if !all (default), true
                 * if all, false
                 */
                return !$check_all;
            }
        }

        /**
         * if !all (default), false
         * if all, true
         */
        return $check_all;
    }

    /**
     * user
     *
     * @return object
     *
     */
    public function user($id = NULL) {
        $id || $id = $this->session->userdata('user_id');

        $this->db->limit(1);
        $this->db->order_by('id', 'desc');
        $this->db->where($this->tables['users'] . '.id', $id);
        return $this->db->get($this->tables['users'])->row();
    }

    /**
     * protected -------------------------------------------------------------------------
     */
    /**
     *
     * @param unknown $ip_address
     * @return unknown
     */
    protected function _prepare_ip($ip_address) {
        return $ip_address;
    }

    /**
     * 检查字段类型
     *
     * @param unknown $table
     * @param unknown $data
     * @return multitype:unknown
     */
    protected function _filter_data($table, $data) {
        $filtered_data = array();
        $columns       = $this->db->list_fields($table);

        if (is_array($data)) {
            foreach ($columns as $column) {
                if (array_key_exists($column, $data)) {
                    $filtered_data[$column] = $data[$column];
                }
            }
        }

        return $filtered_data;
    }

    /**
     * 获取节点信息
     *
     * @param unknown $class
     * @param unknown $method
     * @param number $status
     * @return multitype:|boolean
     */
    protected function _getNode($class, $method, $status = 1) {
        $tableName = $this->tables['node'];

        $where = array(
            'class'     => $class,
            'method'    => $method,
            'published' => $status,
        );

        $result = $this->db->get_where($tableName, $where);
        if ($result->num_rows() > 0) {
            if (1 == $result->num_rows()) {
                return $result->row()->id;
            }
            $tmpArr = array();
            foreach ($result->result() as $v) {
                array_push($tmpArr, $v->id);
            }
            return $tmpArr;
        }
        return FALSE;
    }

    /**
     * 获取权限
     *
     * @param unknown $group_id
     * @param unknown $node_id
     * @return boolean
     */
    protected function _getAccess($group_id, $node_id) {
        $tableName = $this->tables['access'];
        $where     = array(
            'group_id' => $group_id,
            'menu_id'  => $node_id,
        );
        $result = $this->db->get_where($tableName, $where);
        if ($result->num_rows() > 0) {
            return $result->row();
        }
        return FALSE;
    }

    /**
     * 获取用户对应用户组
     *
     * @param unknown $user_id
     * @return boolean
     */
    protected function _getUserGroup($user_id) {
        $tableName = $this->tables['users_groups'];
        $where     = array(
            'user_id' => $user_id,
        );
        $result = $this->db->get_where($tableName, $where);
        if ($result->num_rows() > 0) {
            $data = $result->row();
            return $data->group_id;
        }
        return FALSE;
    }

    /**
     * 获取用户组
     *
     * @param unknown $group
     * @param number $is_byid
     * @param number $status
     * @return boolean
     */
    protected function _getGroup($group, $is_byid = 1, $published = 1) {
        $tableName = $this->tables['groups'];
        if (1 == $is_byid) {
            $where['id'] = $group;
        } else {
            $where['group_name'] = $group;
        }

        $where['published'] = $published;

        $result = $this->db->get_where($tableName, $where);
        if ($result->num_rows() > 0) {
            return $result->row();
        }
        return FALSE;
    }
}