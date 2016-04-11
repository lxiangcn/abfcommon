<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Member
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-04-11 10:30:31
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Member extends Other_Controller {
    /**
     * Referer
     *
     * @access public
     * @var string
     */
    public $referrer;

    function __construct() {
        parent::__construct();
        $this->_check_referrer();
        $this->lang->load('auth');
    }

    /**
     * 检查referrer
     *
     * @access private
     * @return void
     */
    private function _check_referrer() {
        $ref            = $this->input->get('ref', TRUE);
        $this->referrer = !empty($ref) ? $ref : site_url("dashboard/welcome/index");
    }

    /**
     * redirect if needed, otherwise display the user list
     * @author liux
     * @return
     */
    function index() {
        if (!$this->member_auth->is_login()) {
            redirect('auth/member/login', 'refresh');
        } else {
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->_render_page('auth/index', $data);
        }
    }

    /**
     * login the user in
     *
     * author liux
     * date 2015-07-02
     * return
     */
    function login() {
        $data['page_title'] = __('user_login_title');
        $ref                = $this->input->get("ref");
        $data['ref']        = $ref;
        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        // validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == true) {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');
            if ($this->member_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                // if the login is successful
                // redirect them back to the home page
                $this->session->set_flashdata('message', $this->member_auth->messages());
                redirect('/home/welcome', 'refresh');
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                $this->session->set_flashdata('message', $this->member_auth->errors());
                redirect('auth/member/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $data['message']  = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data['identity'] = array('name' => 'identity', 'id' => 'identity', 'type' => 'text', 'value' => $this->form_validation->set_value('identity'));
            $data['password'] = array('name' => 'password', 'id' => 'password', 'type' => 'password');
            $data['captcha']  = array('name' => 'captcha', 'id' => 'captcha', 'type' => 'text');
            $this->_render_page('auth/login', $data);
        }
    }

    /**
     * 前台用户退出
     * @return
     */
    public function logout() {
        $this->member_auth->logout();
        // redirect them to the login page
        $this->session->set_flashdata('message', $this->member_auth->messages());
        // redirect('auth/login', 'refresh');
        redirect(site_url(), 'refresh');
    }

    /**
     * change password
     * @return
     */
    function change_password() {
        $data['title']      = "Change Password";
        $data['page_title'] = 'Change Password';
        $data['module']     = 'auth';
        $data['view_file']  = 'change_password';

        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'member_auth') . ']|max_length[' . $this->config->item('max_password_length', 'member_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->member_auth->is_login()) {
            redirect('auth/member/login', 'refresh');
        }

        $user = $this->member_auth->user()->row();

        if ($this->form_validation->run() == false) {
            // display the form
            // set the flash data error message if there is one
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $data['min_password_length'] = $this->config->item('min_password_length', 'member_auth');
            $data['old_password']        = array(
                'name' => 'old',
                'id'   => 'old',
                'type' => 'password',
            );
            $data['new_password'] = array(
                'name'    => 'new',
                'id'      => 'new',
                'type'    => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
            );
            $data['new_password_confirm'] = array(
                'name'    => 'new_confirm',
                'id'      => 'new_confirm',
                'type'    => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
            );
            $data['user_id'] = array(
                'name'  => 'user_id',
                'id'    => 'user_id',
                'type'  => 'hidden',
                'value' => $user->id,
            );

            // render
            // $this->_render_page('auth/change_password', $data);

            echo Modules::run('templates', $data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->member_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                // if the password was successfully changed
                $this->session->set_flashdata('message', $this->member_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->member_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    /**
     * forgot password
     * @return
     */
    function forgot_password() {
        $data['page_title'] = 'Forgotten Password';
        // setting validation rules by checking wheather identity is username or email
        if ($this->config->item('identity', 'member_auth') == 'username') {
            $this->form_validation->set_rules('email', $this->lang->line('forgot_password_username_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }

        if ($this->form_validation->run() == false) {
            // setup the input
            $data['email'] = array('name' => 'email', 'id' => 'email');

            if ($this->config->item('identity', 'member_auth') == 'username') {
                $data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
            } else {
                $data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            // set any errors and display the form
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->_render_page('auth/forgot_password', $data);
            // echo Modules::run ( 'templates', $data );
        } else {
            // get identity from username or email
            if ($this->config->item('identity', 'member_auth') == 'username') {
                $identity = $this->member_auth->find(array('username' => strtolower($this->input->post('email'))));
            } else {
                $identity = $this->member_auth->find(array('email' => strtolower($this->input->post('email'))));
            }
            if (empty($identity)) {
                if ($this->config->item('identity', 'member_auth') == 'username') {
                    $this->member_auth->set_message('forgot_password_username_not_found');
                } else {
                    $this->member_auth->set_message('forgot_password_email_not_found');
                }

                $this->session->set_flashdata('message', $this->member_auth->messages());
                redirect("auth/member/forgot_password", 'refresh');
            }
            $identity_label = $this->config->item('identity', 'member_auth');
            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->member_auth->forgotten_password($identity->$identity_label);

            if ($forgotten) {
                // if there were no errors
                $this->session->set_flashdata('message', $this->member_auth->messages());
                redirect("auth/member/login", 'refresh'); // we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->member_auth->errors());
                redirect("auth/member/forgot_password", 'refresh');
            }
        }
    }

    /**
     * reset password - final step for forgotten password
     * @param  string $code 密钥
     * @return
     */
    public function reset_password($code = NULL) {
        $data['title']      = "Reset Password";
        $data['page_title'] = 'Reset Password';
        $data['module']     = 'auth';
        $data['view_file']  = 'reset_password';

        if (!$code) {
            show_404();
        }

        $user = $this->member_auth->forgotten_password_check($code);

        if ($user) {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'member_auth') . ']|max_length[' . $this->config->item('max_password_length', 'member_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {
                // display the form

                // set the flash data error message if there is one
                $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $data['min_password_length'] = $this->config->item('min_password_length', 'member_auth');
                $data['new_password']        = array(
                    'name'    => 'new',
                    'id'      => 'new',
                    'type'    => 'password',
                    'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                );
                $data['new_password_confirm'] = array(
                    'name'    => 'new_confirm',
                    'id'      => 'new_confirm',
                    'type'    => 'password',
                    'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                );
                $data['user_id'] = array(
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                );
                $data['csrf'] = $this->_get_csrf_nonce();
                $data['code'] = $code;

                // render
                // $this->_render_page('auth/reset_password', $data);
                echo Modules::run('templates', $data);
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    // something fishy might be up
                    $this->member_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'member_auth')};

                    $change = $this->member_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->member_auth->messages());
                        $this->logout();
                    } else {
                        $this->session->set_flashdata('message', $this->member_auth->errors());
                        redirect('auth/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->member_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    /**
     * activate the user
     * @param  [type]  $id   [description]
     * @param  boolean $code [description]
     * @return [type]        [description]
     */
    function activate($id, $code = false) {
        if ($code !== false) {
            $activation = $this->member_auth->activate($id, $code);
        } else if ($this->member_auth->is_admin()) {
            $activation = $this->member_auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->member_auth->messages());
            redirect("auth", 'refresh');
        } else {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->member_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    /**
     * deactivate the user
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function deactivate($id = NULL) {
        if (!$this->member_auth->logged_in() || !$this->member_auth->is_admin()) {
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }

        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $data['csrf'] = $this->_get_csrf_nonce();
            $data['user'] = $this->member_auth->user($id)->row();

            $this->_render_page('auth/deactivate_user', $data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->member_auth->logged_in() && $this->member_auth->is_admin()) {
                    $this->member_auth->deactivate($id);
                }
            }

            // redirect them back to the auth page
            redirect('auth', 'refresh');
        }
    }

    /**
     * create a new user
     * @return
     */
    function register() {
        $data['page_title'] = __("user_register_title");
        $ref                = $this->input->get("ref");
        $data['ref']        = $ref;

        $tables = $this->config->item('tables', 'member_auth');

        // validate form input
        $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'required');
        $this->form_validation->set_rules('nickname', $this->lang->line('create_user_validation_nickname_label'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'member_auth') . ']|max_length[' . $this->config->item('max_password_length', 'member_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('username'));
            $email    = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'nickname' => $this->input->post('nickname'),
            );
        }
        if ($this->form_validation->run() == true && $this->member_auth->register($username, $password, $email, $additional_data)) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->member_auth->messages());
            redirect("auth", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : ($this->member_auth->errors() ? $this->member_auth->errors() : $this->session->flashdata('message')));

            $data['username'] = array(
                'name'  => 'username',
                'id'    => 'username',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('username'),
            );
            $data['nickname'] = array(
                'name'  => 'nickname',
                'id'    => 'nickname',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('nickname'),
            );
            $data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->_render_page('auth/register', $data);
        }
    }

    /**
     * edit a user
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function edit_user($id) {
        $data['title']      = "Edit User";
        $data['page_title'] = 'Edit User';
        $data['module']     = 'auth';
        $data['view_file']  = 'edit_user';

        if (!$this->member_auth->logged_in() || (!$this->member_auth->is_admin() && !($this->member_auth->user()->row()->id == $id))) {
            redirect('auth', 'refresh');
        }

        $user          = $this->member_auth->user($id)->row();
        $groups        = $this->member_auth->groups()->result_array();
        $currentGroups = $this->member_auth->get_users_groups($id)->result();

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'member_auth') . ']|max_length[' . $this->config->item('max_password_length', 'member_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'company'    => $this->input->post('company'),
                    'phone'      => $this->input->post('phone'),
                );

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                // Only allow updating groups if user is admin
                if ($this->member_auth->is_admin()) {
                    // Update the groups user belongs to
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData)) {

                        $this->member_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp) {
                            $this->member_auth->add_to_group($grp, $id);
                        }
                    }
                }

                // check to see if we are updating the user
                if ($this->member_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->member_auth->messages());
                    if ($this->member_auth->is_admin()) {
                        redirect('auth', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                } else {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->member_auth->errors());
                    if ($this->member_auth->is_admin()) {
                        redirect('auth', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $data['message'] = (validation_errors() ? validation_errors() : ($this->member_auth->errors() ? $this->member_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $data['user']          = $user;
        $data['groups']        = $groups;
        $data['currentGroups'] = $currentGroups;

        $data['first_name'] = array(
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $data['last_name'] = array(
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $data['company'] = array(
            'name'  => 'company',
            'id'    => 'company',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $data['phone'] = array(
            'name'  => 'phone',
            'id'    => 'phone',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $data['password'] = array(
            'name' => 'password',
            'id'   => 'password',
            'type' => 'password',
        );
        $data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id'   => 'password_confirm',
            'type' => 'password',
        );

        echo Modules::run('templates', $data);
    }

    // create a new group
    function create_group() {
        $data['title'] = $this->lang->line('create_group_title');

        if (!$this->member_auth->is_login() || !$this->member_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

        if ($this->form_validation->run() == TRUE) {
            $new_group_id = $this->member_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
            if ($new_group_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->member_auth->messages());
                redirect("auth", 'refresh');
            }
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : ($this->member_auth->errors() ? $this->member_auth->errors() : $this->session->flashdata('message')));

            $data['group_name'] = array(
                'name'  => 'group_name',
                'id'    => 'group_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('group_name'),
            );
            $data['description'] = array(
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('description'),
            );

            $this->_render_page('auth/create_group', $data);
        }
    }

    // edit a group
    function edit_group($id) {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('auth', 'refresh');
        }

        $data['title'] = $this->lang->line('edit_group_title');

        if (!$this->member_auth->logged_in() || !$this->member_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $group = $this->member_auth->group($id)->row();

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === TRUE) {
                $group_update = $this->member_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                if ($group_update) {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                } else {
                    $this->session->set_flashdata('message', $this->member_auth->errors());
                }
                redirect("auth", 'refresh');
            }
        }

        // set the flash data error message if there is one
        $data['message'] = (validation_errors() ? validation_errors() : ($this->member_auth->errors() ? $this->member_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $data['group'] = $group;

        $data['group_name'] = array(
            'name'  => 'group_name',
            'id'    => 'group_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('group_name', $group->name),
        );
        $data['group_description'] = array(
            'name'  => 'group_description',
            'id'    => 'group_description',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        );

        $this->_render_page('auth/edit_group', $data);
    }

    function _get_csrf_nonce() {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array(
            $key => $value,
        );
    }

    function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false) {
        $this->viewdata = (empty($data)) ? $data : $data;
        $this->output('home_layout', array('body' => $view), $this->viewdata);
    }
}
