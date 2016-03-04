<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : Admin.php
 * DateTime : 2015年6月26日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Category extends Admin_Controller {

    private $tblName = 'category';

    function __construct () {
        parent::__construct();
        $this->load->model('model_' . $this->tblName, $this->tblName, TRUE);
    }

    /**
     * 显示类别
     *
     * @param number $channel_id
     * @param number $page_no
     * @param number $parent_id
     */
    public function index ($channel_id = 0, $page_no = 1, $parent_id = 0) {
        $where = "1=1 ";
        $order_str = "id desc";
        $keyword = $this->input->get('keyword');
        if (! empty($keyword)) {
            $keyword = $keyword ? $keyword : '';
            $where .= " and name like '%$keyword%'";
        }
        $where .= " and channel_id={$channel_id} and parent_id={$parent_id}";
        
        $total_rows = $this->category->find_count($where);
        $pagination_link = pagination_link("archive/category/index/" . $page_no . '/' . $parent_id, intval($total_rows), 4, $page_no);
        $data['info_list'] = $this->category->find_all($where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr'] = $pagination_link['link'];
        $data["page_no"] = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];
        $data['channel_id'] = $channel_id;
        
        $this->output("admin_layout", array(
                "body" => "category/index"
        ), $data);
    }

    /**
     * 添加分类
     *
     * @param number $channel_id
     * @param number $page_no
     */
    public function add ($channel_id = 0, $page_no = 1) {
        $page_no = intval($page_no);
        $data['page_no'] = $page_no;
        $obj = $this->category->read(0);
        $where = "published = 1 and channel_id={$channel_id}";
        $categories = $this->category->find_all($where);
        $this->load->library('tree');
        $this->tree->set_array($categories);
        $no_option = array(
                '0' => '├─顶级分类'
        );
        
        $parents = $no_option + $this->tree->get_tree();
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', '分类名称', 'trim|required|htmlspecialchars');
            $this->form_validation->set_rules('sort_order', '排序', 'integer');
            if ($this->form_validation->run() == true) {
                $items['name'] = $this->input->post('name');
                $items['parent_id'] = $this->input->post('parent_id');
                $items['channel_id'] = $channel_id;
                $items['published'] = $this->input->post('published');
                $items['sort_order'] = $this->input->post('sort_order');
                $items['mapping'] = genMapping($this->input->post('parent_id'), 'category');
                if ($this->category->insert($items)) {
                    $this->success('数据保存成功！');
                    redirect(site_url('archive/category/index/' . $page_no));
                } else {
                    $this->error('保存类别到数据库出错！');
                    redirect(site_url('archive/category/index/' . $page_no));
                }
            }
        }
        $data["data"] = $obj;
        // 设置默认表单信息
        $data['parents'] = $parents;
        $data['parent_id'] = 0;
        $data['published'] = 1;
        $data['sort_order'] = 0;
        $data['channel_id'] = $channel_id;
        
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array(
                "body" => "category/add"
        ), $data);
    }

    /**
     * 单个编辑
     *
     * @param int $channel_id
     * @param int $page_no
     * @param int $id
     */
    public function edit ($channel_id = 0, $id = 0, $page_no = 1) {
        $id = intval($id);
        $page_no = intval($page_no);
        $data['id'] = $id;
        $data['page_no'] = $page_no;
        // 验证编号
        if (! $id) {
            $this->error('错误的类别编号！');
            redirect(site_url('archive/category/index/' . $page_no));
        }
        $obj = $this->category->read($id);
        // 验证对象是否存在
        if (! $obj->id) {
            $this->error('不存在的类别编号！');
            redirect(site_url('archive/category/index/' . $page_no));
        }
        
        $mapping_origin = $obj->mapping;
        $parent_id = $obj->parent_id;
        
        $where = "published = 1 and channel_id={$channel_id}";
        $categories = $this->category->find_all($where);
        $this->load->library('tree');
        $this->tree->set_array($categories);
        $no_option = array(
                '0' => '├─顶级分类'
        );
        $parents = $no_option + $this->tree->get_tree();
        
        $data['id'] = $id;
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', '分类名称', 'trim|required|htmlspecialchars');
            $this->form_validation->set_rules('sort_order', '排序', 'integer');
            if ($this->form_validation->run() == true) {
                $items['name'] = $this->input->post('name');
                $items['channel_id'] = $channel_id;
                $items['parent_id'] = $this->input->post('parent_id');
                $items['published'] = $this->input->post('published');
                $items['sort_order'] = $this->input->post('sort_order');
                if ($this->category->save($items, $id)) {
                    $this->success('数据更新成功！');
                    redirect(site_url('archive/category/index/' . $page_no));
                } else {
                    $this->error('数据更新出错！');
                    redirect(site_url('archive/category/index/' . $page_no));
                }
            }
        }
        // 使用默认数据
        $data['data'] = $obj;
        $data['parents'] = $parents;
        $data['parent_id'] = $parent_id;
        $data['channel_id'] = $channel_id;
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array(
                "body" => "category/edit"
        ), $data);
    }

    /**
     * 单个删除
     *
     * @param number $id
     * @param number $page_no
     */
    public function delete ($id = 0, $page_no = 1) {
        $id = intval($id);
        $page_no = intval($page_no);
        $data['id'] = $id;
        $data['page_no'] = $page_no;
        if (! $id) {
            $this->error("查询错误。");
            redirect(site_url('archive/category/index/' . $page_no));
        }
        $obj = $this->category->read($id);
        if ($obj->id) {
            if ($this->category->remove($id)) {
                $this->success("删除成功。");
                redirect(site_url('archive/category/index/' . $page_no));
            } else {
                $this->error("查询错误。");
                redirect(site_url('archive/category/index/' . $page_no));
            }
        } else {
            $this->error("信息不存在。");
            redirect(site_url('archive/category/index/' . $page_no));
        }
    }
}