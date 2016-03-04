<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 *  abfcommon
 *
 * @package Role
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-20 20:26:36
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Role extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['page_title'] = '用户组管理';
        $this->load->model('model_role', 'role', TRUE);
        $this->load->model('model_admin_role_priv', 'admin_role_priv', TRUE);
        $this->load->model('model_menus', 'menus', TRUE);
    }

    /**
     * 显示新闻
     *
     * @param type $page_no
     */
    public function index($page_no = 1) {
        $where              = "1=1 ";
        $order_str          = "id asc";
        $total_rows         = $this->role->find_count($where);
        $pagination_link    = pagination_link("auth/role/index/", intval($total_rows), 4, $page_no);
        $data['info_list']  = $this->role->find_all($where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr']    = $pagination_link['link'];
        $data["page_no"]    = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];

        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array("body" => "role/index"), $data);
    }

    /**
     * 添加用户组
     *
     * @param number $page_no
     */
    public function add($page_no = 0) {
        $page_no         = intval($page_no);
        $data['page_no'] = $page_no;
        $obj             = $this->role->read(0);
        // 设置默认表单信息
        $data['published'] = 1;
        $accesss_arr       = array();
        //获取权限数据
        $where             = "published = 1";
        $nodes             = $this->menus->find_all($where);
        $nodes_tree        = create_tree($nodes);
        $nodes_tree_arr    = NULL;
        $nodes_tree_arr    = show_access($nodes_tree, $accesss_arr);
        $data['info_list'] = $nodes_tree_arr;

        //提交
        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', '用户组名', 'trim|required|min_length[2]|max_length[20]|callback_add_group_name_check');
            $this->form_validation->set_rules('description', '描述', 'trim|required|min_length[2]|max_length[20]');
            if ($this->form_validation->run() === true) {
                $items['group_name']  = $this->input->post('group_name');
                $items['description'] = $this->input->post('description');
                $items['published']   = $this->input->post('published');
                $group_id             = $this->role->insert($items);
                // 写入新权限
                $nodes = $this->input->post('ids');
                foreach ($nodes as $k => $v) {
                    $this->admin_role_priv->addAccess($group_id, $k);
                }
                if ($group_id) {
                    $this->success('添加成功！');
                    redirect(site_url('auth/role/index/' . $page_no));
                } else {
                    $this->error("保存数据出错！");
                    redirect(site_url('auth/role/index/' . $page_no));
                }
            }
        }
        $data['data']       = $obj;
        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array("body" => "role/add"), $data);
    }

    /**
     * 编辑用户组
     *
     * @param number $id
     * @param number $page_no
     */
    public function edit($id = 0, $page_no = 1) {
        $id              = intval($id);
        $page_no         = intval($page_no);
        $data['id']      = $id;
        $data['page_no'] = $page_no;
        // 验证编号
        if (!$id) {
            $this->error("错误的信息编号！");
            redirect(site_url('auth/role/index/' . $page_no));
        }
        $obj = $this->role->read($id);
        // 验证对象是否存在
        if (!$obj->id) {
            $this->error("不存在的信息编号！");
            redirect(site_url('auth/role/index/' . $page_no));
        }
        /**
         * 获取用户权限
         */
        $access      = $this->admin_role_priv->getGroupAccesses($id);
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
        $nodes             = $this->menus->find_all($where, '*', "sort_order asc");
        $nodes_tree        = create_tree($nodes);
        $nodes_tree_arr    = NULL;
        $nodes_tree_arr    = show_access($nodes_tree, $accesss_arr);
        $data['info_list'] = $nodes_tree_arr;

        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', '用户组名', 'trim|required|min_length[2]|max_length[20]|callback_group_name_check[' . $id . ']');
            $this->form_validation->set_rules('description', '描述', 'trim|required|min_length[2]|max_length[20]');
            if ($this->form_validation->run() == true) {
                $items['group_name']  = $this->input->post('group_name');
                $items['description'] = $this->input->post('description');
                $items['published']   = $this->input->post('published');

                // 清空用户组权限
                $this->admin_role_priv->delAccess($id);
                // 写入新权限
                $nodes = $this->input->post('ids');
                foreach ($nodes as $k => $v) {
                    $this->admin_role_priv->addAccess($id, $v);
                }
                if ($this->role->save($items, $id)) {
                    $this->success('更新数据成功！');
                    redirect(site_url('auth/role/index/' . $page_no));
                } else {
                    $this->error("保存数据出错！");
                    redirect(site_url('auth/role/index/' . $page_no));
                }
            }
        }
        $data['data']       = $obj;
        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array("body" => "role/edit"), $data);
    }

    /**
     * 删除
     *
     * @param number $id
     * @param number $page_no
     */
    public function delete($id = 0, $page_no = 1) {
        $id = (int) $id;
        if (!$id) {
            $this->error("查询错误！");
            redirect(site_url('auth/role/index/' . $page_no));
        }
        $obj = $this->role->read($id);
        if ($obj->id) {
            if ($this->role->remove($id)) {
                $this->success("删除成功！");
                redirect(site_url('auth/role/index/' . $page_no));
            } else {
                $this->error("删除失败！");
                redirect(site_url('auth/role/index/' . $page_no));
            }
        } else {
            $this->error("不存在的数据！");
            redirect(site_url('auth/role/index/' . $page_no));
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
        $result = $this->role->get_by_name($group_name);
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
        $result = $this->role->get_by_id_name($group_name, $id);
        if ($result) {
            $this->form_validation->set_message('group_name_check', "%s已经存在!");
            return false;
        } else {
            return true;
        }
    }
}