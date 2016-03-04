<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : Group.php
 * DateTime : 2015年4月24日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Group extends Admin_Controller {

    public function __construct () {
        parent::__construct();
        $this->data['page_title'] = '用户组管理';
        $this->load->model('model_groups', 'groups', TRUE);
        $this->load->model('model_user_access', 'user_access', TRUE);
        $this->load->model('model_menus', 'menus', TRUE);
    }

    /**
     * 显示新闻
     *
     * @param type $page_no
     */
    public function index ($page_no = 1) {
        $where = "1=1 ";
        $order_str = "id asc";
        $total_rows = $this->groups->find_count($where);
        $pagination_link = pagination_link("auth/group/index/", intval($total_rows), 4, $page_no);
        $data['info_list'] = $this->groups->find_all($where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr'] = $pagination_link['link'];
        $data["page_no"] = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];
        
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array(
                "body" => "group/index"
        ), $data);
    }

    /**
     * 添加用户组
     *
     * @param number $page_no
     */
    public function add ($page_no = 0) {
        $page_no = intval($page_no);
        $data['page_no'] = $page_no;
        $obj = $this->groups->read(0);
        // 设置默认表单信息
        $data['published'] = 1;
        $accesss_arr = array();
        /**
         * 获取权限数据
         */
        $where = "published = 1";
        $nodes = $this->menus->find_all($where);
        $nodes_tree = create_tree($nodes);
        $nodes_tree_arr = NULL;
        $nodes_tree_arr = show_access($nodes_tree, $accesss_arr);
        $data['info_list'] = $nodes_tree_arr;
        
        /**
         * 提交
         */
        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', '用户组名', 'trim|required|min_length[2]|max_length[20]|callback_add_group_name_check');
            $this->form_validation->set_rules('description', '描述', 'trim|required|min_length[2]|max_length[20]');
            if ($this->form_validation->run() === true) {
                $items['group_name'] = $this->input->post('group_name');
                $items['description'] = $this->input->post('description');
                $items['published'] = $this->input->post('published');
                $group_id = $this->groups->insert($items);
                // 写入新权限
                $nodes = $this->input->post('ids');
                foreach ($nodes as $k => $v) {
                    $this->user_access->addAccess($group_id, $k);
                }
                if ($group_id) {
                    $this->success('添加成功！');
                    redirect(site_url('auth/group/index/' . $page_no));
                } else {
                    $this->error("保存数据出错！");
                    redirect(site_url('auth/group/index/' . $page_no));
                }
            }
        }
        $data['data'] = $obj;
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array(
                "body" => "group/add"
        ), $data);
    }

    /**
     * 编辑用户组
     *
     * @param number $id
     * @param number $page_no
     */
    public function edit ($id = 0, $page_no = 1) {
        $id = intval($id);
        $page_no = intval($page_no);
        $data['id'] = $id;
        $data['page_no'] = $page_no;
        // 验证编号
        if (! $id) {
            $this->error("错误的信息编号！");
            redirect(site_url('auth/group/index/' . $page_no));
        }
        $obj = $this->groups->read($id);
        // 验证对象是否存在
        if (! $obj->id) {
            $this->error("不存在的信息编号！");
            redirect(site_url('auth/group/index/' . $page_no));
        }
        /**
         * 获取用户权限
         */
        $access = $this->user_access->getGroupAccesses($id);
        $accesss_arr = array();
        if ($access) {
            foreach ($access as $v) {
                array_push($accesss_arr, $v->menu_id);
            }
        }
        $data['accesss_arr'] = $accesss_arr;
        
        /**
         * 获取权限数据
         */
        $where = "published = 1";
        $this->load->model('model_menus', 'menus', TRUE);
        $nodes = $this->menus->find_all($where, '*', "sort_order asc");
        $nodes_tree = create_tree($nodes);
        $nodes_tree_arr = NULL;
        $nodes_tree_arr = show_access($nodes_tree, $accesss_arr);
        $data['info_list'] = $nodes_tree_arr;
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', '用户组名', 'trim|required|min_length[2]|max_length[20]|callback_group_name_check[' . $id . ']');
            $this->form_validation->set_rules('description', '描述', 'trim|required|min_length[2]|max_length[20]');
            if ($this->form_validation->run() == true) {
                $items['group_name'] = $this->input->post('group_name');
                $items['description'] = $this->input->post('description');
                $items['published'] = $this->input->post('published');
                
                // 清空用户组权限
                $this->user_access->delAccess($id);
                // 写入新权限
                $nodes = $this->input->post('ids');
                foreach ($nodes as $k => $v) {
                    $this->user_access->addAccess($id, $v);
                }
                if ($this->groups->save($items, $id)) {
                    $this->success('更新数据成功！');
                    redirect(site_url('auth/group/index/' . $page_no));
                } else {
                    $this->error("保存数据出错！");
                    redirect(site_url('auth/group/index/' . $page_no));
                }
            }
        }
        $data['data'] = $obj;
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array(
                "body" => "group/edit"
        ), $data);
    }

    /**
     * 删除
     *
     * @param number $id
     * @param number $page_no
     */
    public function delete ($id = 0, $page_no = 1) {
        $id = (int) $id;
        if (! $id) {
            $this->error("查询错误！");
            redirect(site_url('auth/group/index/' . $page_no));
        }
        $obj = $this->groups->read($id);
        if ($obj->id) {
            if ($this->groups->remove($id)) {
                $this->success("删除成功！");
                redirect(site_url('auth/group/index/' . $page_no));
            } else {
                $this->error("删除失败！");
                redirect(site_url('auth/group/index/' . $page_no));
            }
        } else {
            $this->error("不存在的数据！");
            redirect(site_url('auth/group/index/' . $page_no));
        }
    }

    /**
     * 添加
     * 用户分组名检测
     *
     * @param string $group_name
     * @return boolean
     */
    function add_group_name_check ($group_name) {
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
    function group_name_check ($group_name, $id) {
        $result = $this->groups->get_by_id_name($group_name, $id);
        if ($result) {
            $this->form_validation->set_message('group_name_check', "%s已经存在!");
            return false;
        } else {
            return true;
        }
    }
}