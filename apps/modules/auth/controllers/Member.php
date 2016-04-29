<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Member
 * @link http://orzm.net
 * @author Alex Liu<lxiangcn@gmail.com>
 * @version V3.0.1
 * @modifiedtime 2016-04-29 13:37:34
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

class Member extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('auth/model_member', 'member', true);
    }

    public function index($page_no = 1) {
        $page_no = intval($page_no);
        if (!$page_no) {
            $page_no = 1;
        }
        $data_where         = '1=1';
        $order_str          = "id desc";
        $total_rows         = $this->member->find_count($data_where);
        $pagination_link    = pagination_link("auth/member/index/", intval($total_rows), 4, $page_no);
        $data['info_list']  = $this->member->find_all($data_where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr']    = $pagination_link['link'];
        $data["page_no"]    = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];
        $this->output("admin_layout", array("body" => "member/index"), $data);
    }

    /**
     * 添加会员
     *
     * @param number $page_no
     */
    public function add($page_no = 1) {
        $data["page_no"] = $page_no;
        $result          = $this->db->get('groups')->result();
        foreach ($result as $v) {
            $roles[$v->id] = $v->group_name . '(' . $v->description . ')';
        }
        $data['roles'] = $roles;

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', '邮箱', 'trim|required|valid_email|callback_add_email_check');
            $this->form_validation->set_rules('password', '密码', 'trim|required');
            $this->form_validation->set_rules('username', '用户名', 'trim|required');
            if ($this->form_validation->run() === true) {
                $email               = $this->input->post('email');
                $username            = $this->input->post('username');
                $password            = $this->input->post('password');
                $items['nickname']   = $this->input->post('nickname');
                $items['active']     = $this->input->post('active');
                $items['gender']     = $this->input->post('gender');
                $items['logins']     = 1;
                $items['last_login'] = time();
                $items['created']    = time();
                $group[]             = $this->input->post('role_id');

                $this->load->library("auth/member_auth");
                if ($this->admin_auth->register($username, $password, $email, $items, $group)) {
                    $this->success('数据保存成功。');
                    redirect(site_url('auth/member/index/' . $page_no));
                } else {
                    $this->error('保存到数据库出错。');
                }
            }
        }
        $data['active'] = 1;
        $data['gender'] = 'male';

        $this->output("admin_layout", array("body" => "member/add"), $data);
    }

    /**
     * 编辑
     *
     * @param type $id
     * @param type $page_no
     */
    public function edit($id = 0, $page_no = 1) {
        $page_no = (int) $page_no;
        // 验证编号
        $id = (int) $id;
        if (!$id) {
            $this->error('错误的信息编号！');
            redirect(site_url('auth/member/index/' . $page_no));
        }
        $data['id'] = $id;
        $obj        = $this->member->read($id);
        // 验证对象是否存在
        if (!$obj->id) {
            $this->error('不存在的信息编号！');
            redirect(site_url('auth/member/index/' . $page_no));
        }
        $page_no         = intval($page_no);
        $data['page_no'] = $page_no;

        $result = $this->db->get('groups')->result();

        foreach ($result as $v) {
            $roles[$v->id] = $v->group_name . '(' . $v->description . ')';
        }

        $data['roles'] = $roles;
        // 查询用户权限
        $user_groups = $this->db->where(array(
            "user_id" => $id,
        ))->get('admin_role')->result();
        if (!empty($user_groups)) {
            $data['gid'] = $user_groups[0]->group_id;
        } else {
            $data['gid'] = 0;
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', '邮箱', 'trim|required|valid_email|callback_email_check[' . $id . ']');
            $this->form_validation->set_rules('username', '用户名', 'trim|required');
            if ($this->form_validation->run() === true) {
                $items['email']    = $this->input->post('email');
                $items['username'] = $this->input->post('username');
                $items['nickname'] = $this->input->post('nickname');
                $items['gender']   = $this->input->post('gender');
                $password          = $this->input->post('password');
                if (trim($password)) {
                    $items['password'] = $password;
                }
                $items['active'] = $this->input->post('active');
                $items['gender'] = $this->input->post('gender');
                $group_id[]      = $this->input->post('role_id');
                $this->load->library("auth/admin_auth");
                if ($this->member_auth->update_user($id, $items, $group_id)) {
                    $this->success("保存数据成功。");
                    redirect(site_url('auth/member/index/' . $page_no));
                } else {
                    $this->error("保存数据出错。");
                }
            }
        }
        $data['data'] = $obj;
        $this->output("admin_layout", array("body" => "member/edit"), $data);
    }

    /**
     * 添加邮箱检测
     *
     * @param unknown $email
     * @return boolean
     */
    function add_email_check($email) {
        $result = $this->member->get_user_by_email($email);
        if ($result) {
            $this->form_validation->set_message('add_email_check', "%s已存在!");
            return false;
        } else {
            return true;
        }
    }

    /**
     * 编辑
     * 检测用户是否存在
     *
     * @return type
     */
    function email_check($email, $id) {
        $result = $this->member->get_user_by_id_email($email, $id);
        if ($result) {
            $this->form_validation->set_message('email_check', "%s已存在!"); // 设置消息模式
            return false;
        } else {
            return true;
        }
    }

    /**
     * 单个删除
     *
     * @param int $id
     */
    public function delete($id, $page_no = 1) {
        $id = (int) $id;
        if (!$id) {
            $this->error("信息不存在。");
            redirect(site_url('auth/member/index/' . $page_no));
        }
        $obj = $this->member->read($id);
        if ($obj->id) {
            $this->load->library("auth/member_auth");
            if ($this->member_auth->delete_user($id)) {
                $this->success("删除成功。");
                redirect(site_url('auth/member/index/' . $page_no));
            } else {
                $this->error("删除失败。");
                redirect(site_url('auth/member/index/' . $page_no));
            }
        } else {
            $this->error("信息不存在。");
            redirect(site_url('auth/member/index/' . $page_no));
        }
    }/**
     * 审核
     *
     * @param type $id
     * @param type $page_no
     */
    public function audit($id = 0, $page_no = 1) {
        $page_no = (int) $page_no;
        // 验证编号
        $id = (int) $id;
        if (!$id) {
            $this->error('错误的信息编号！');
            redirect(site_url('auth/member/index/' . $page_no));
        }
        $data['id'] = $id;
        $obj        = $this->member->read($id);
        // 验证对象是否存在
        if (!$obj->id) {
            $this->error('不存在的信息编号！');
            redirect(site_url('auth/member/index/' . $page_no));
        }
        $this->data['page_js'] = array();
        $page_no               = intval($page_no);
        $data['page_no']       = $page_no;

        $result = $this->db->get('role')->result();

        foreach ($result as $v) {
            $roles[$v->id] = $v->group_name . '(' . $v->description . ')';
        }

        $data['roles'] = $roles;

        // 查询用户权限
        $user_groups = $this->db->where(array(
            "user_id" => $id,
        ))->get('admin_role')->result();
        if (!empty($user_groups)) {
            $data['gid'] = $user_groups[0]->group_id;
        } else {
            $data['gid'] = 0;
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('role_id', '', 'trim|required');
            if ($this->form_validation->run() === true) {
                $active = $active = $this->input->post('active');
                $this->load->library("auth/member_auth");
                if ($this->member_auth->activate($id, $active)) {
                    // // 认证审核 推送微信信息
                    // $openid = $obj ['username'];
                    // $this->load->model ( 'weixin/settings_model', 'setting_model' );
                    // $this->_wxSetting = $this->setting_model->getAll ();
                    // $this->load->library ( 'weixin/Weixin', $this->_wxSetting );

                    // if ($this->data ['sys_env'] != 'production') {
                    // // 测试模板
                    // $template_id = "qWZSqzYoFtD5PIWyLmeaIN686frkRIeJnUH8-Ctw6HQ";
                    // } else {
                    // $template_id = "kL_ysWMMOz9uLEM2wKG4puJTgBj-uqlL_aW81JWhdiI";
                    // }
                    // if ($active == 1) {
                    // $data = array ("first" => array ("value" => "您已成功绑定招商局蛇口工委会微信公众号","color" => "#173177" ),"keyword1" => array ("value" => $obj ['nickname'],"color" => "#173177" ),"keyword2" => array ("value" => $obj ['mobile'],"color" => "#173177" ),"keyword3" => array ("value" => "认证通过","color" => "#173177" ),"keyword4" => array ("value" => "","color" => "#173177" ),"remark" => array ("value" => "更多精彩，敬请关注","color" => "#173177" ) );
                    // } else {
                    // $data = array ("first" => array ("value" => "很遗憾，您未通过招商局蛇口工委会微信公众号认证","color" => "#173177" ),"keyword1" => array ("value" => $obj ['nickname'],"color" => "#173177" ),"keyword2" => array ("value" => $obj ['mobile'],"color" => "#173177" ),"keyword3" => array ("value" => "认证未通过","color" => "#173177" ),"keyword4" => array ("value" => "","color" => "#173177" ),"remark" => array ("value" => "更多精彩，敬请关注","color" => "#173177" ) );
                    // }
                    // $this->weixin->sendAuditResult ( $openid, $template_id, $data );

                    $this->success("保存数据成功。");
                    redirect(site_url('auth/member/index/' . $page_no));
                } else {
                    $this->error("保存数据出错：" . $this->member_auth->errors());
                }
            }
        }
        $data['data'] = $obj;
        $this->output("admin_layout", array("body" => "member/audit"), $data);
    }}
