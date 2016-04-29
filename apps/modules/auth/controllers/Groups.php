<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Groups
 * @link http://orzm.net
 * @author Alex Liu<lxiangcn@gmail.com>
 * @version V3.0.1
 * @modifiedtime 2016-04-29 13:16:36
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

class Groups extends Admin_Controller {

    function __Construct() {
        parent::__construct();
        $this->load->model('model_groups', 'groups', true);
    }

    /**
     * member groups list
     * @param  integer $page_no page
     * @return view
     */
    public function index($page_no = 1) {
        $page_no            = intval($page_no);
        $where              = "1=1 ";
        $order_str          = "id asc";
        $total_rows         = $this->groups->find_count($where);
        $pagination_link    = pagination_link("auth/groups/index/", intval($total_rows), 4, $page_no);
        $data['info_list']  = $this->groups->find_all($where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr']    = $pagination_link['link'];
        $data["page_no"]    = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];
        $this->output("admin_layout", array("body" => "group/index"), $data);
    }

    /**
     * add a member
     */
    public function add() {
        $data = array();
        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', '用户组名称', 'trim|required|callback_add_group_name_check');
            $this->form_validation->set_rules('description', '描述', 'trim|required');
            if ($this->form_validation->run() === true) {
                $items['group_name']  = $this->input->post('group_name', true);
                $items['description'] = $this->input->post('description', true);
                $items['published']   = $this->input->post('published', true);
                if ($this->groups->insert($items)) {
                    $this->success("保存数据成功。");
                    redirect(site_url('auth/groups/index'));
                } else {
                    $this->error("保存数据出错。");
                }
            }
        }
        $this->output("admin_layout", array("body" => "group/add"), $data);
    }

    /**
     * edit member info
     * @param  int $id member id
     * @return view
     */
    public function edit($id) {
        $id  = intval($id);
        $obj = $this->groups->read($id);
        if (!$id || !$obj) {
            $this->error('参数错误');
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', '用户组名称', 'trim|required|callback_group_name_check[' . $id . ']');
            $this->form_validation->set_rules('description', '描述', 'trim|required');
            if ($this->form_validation->run() === true) {
                $items['group_name']  = $this->input->post('group_name', true);
                $items['description'] = $this->input->post('description', true);
                $items['published']   = $this->input->post('published', true);
                if ($this->groups->save($items, $id)) {
                    $this->success("保存数据成功。");
                    redirect(site_url('auth/groups/index'));
                } else {
                    $this->error("保存数据出错。");
                }
            }
        }
        $data['id']   = $id;
        $data['data'] = $obj;
        $this->output("admin_layout", array("body" => "group/edit"), $data);
    }

    /**
     * 删除
     * @param number $id
     * @param number $page_no
     */
    public function delete($id = 0, $page_no = 1) {
        $id = (int) $id;
        if (!$id) {
            $this->error("查询错误！");
            redirect(site_url('auth/groups/index/' . $page_no));
        }
        $obj = $this->groups->read($id);
        if ($obj->id) {
            if ($this->groups->remove($id)) {
                $this->success("会员分组删除成功！");
                redirect(site_url('auth/groups/index/' . $page_no));
            } else {
                $this->error("会员分组删除失败！");
                redirect(site_url('auth/groups/index/' . $page_no));
            }
        } else {
            $this->error("不存在的数据！");
            redirect(site_url('auth/groups/index/' . $page_no));
        }
    }

    /**
     * 添加
     * 用户分组名检测
     *
     * @param string $group_name
     * @return boolean
     */
    function add_group_name_check($group_name) {
        $result = $this->groups->get_by_name($group_name);
        if ($result) {
            $this->form_validation->set_message('add_group_name_check', "%s已经存在!");
            return false;
        } else {
            return true;
        }
    }

    /**
     * 编辑
     * 检测用户是否存在
     *
     * @param string $group_name
     * @param int $id
     * @return type
     */
    function group_name_check($group_name, $id) {
        $result = $this->groups->get_by_id_name($group_name, $id);
        if ($result) {
            $this->form_validation->set_message('group_name_check', "%s已经存在!");
            return false;
        } else {
            return true;
        }
    }
}
