<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : Archive.php
 * DateTime : 2015年6月26日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Archive extends Admin_Controller {

    private $tblName = 'archive';

    private $maxLevelTag = "cat_level";

    function __construct () {
        parent::__construct();
        $this->load->model('model_' . $this->tblName, $this->tblName, TRUE);
        $this->load->model('model_category', 'category', TRUE);
    }

    /**
     * 列表数据
     *
     * @param number $channel_id
     * @param number $page_no
     */
    public function index ($channel_id = 0, $page_no = 1) {
        $where = "1=1 and channel_id={$channel_id}";
        $order_str = "id desc";
        
        $total_rows = $this->archive->find_count($where);
        $pagination_link = pagination_link("archive/archive/index", intval($total_rows), 4, $page_no);
        $data['info_list'] = $this->archive->find_all($where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr'] = $pagination_link['link'];
        $data["page_no"] = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];
        $data['channel_id'] = $channel_id;
        $this->output("admin_layout", array(
                "body" => "archive/archive/index"
        ), $data);
    }

    /**
     * 添加内容
     *
     * @param number $channel_id
     * @param number $page_no
     */
    public function add ($channel_id = 0, $page_no = 1) {
        $page_no = intval($page_no);
        $data['page_no'] = $page_no;
        $obj = $this->archive->read(0);
        
        $where = "published = 1 and channel_id={$channel_id}";
        $categories = $this->category->find_all($where);
        $this->load->library('tree');
        $this->tree->set_array($categories);
        $parents = $this->tree->get_tree();
        // 设置默认表单信息
        $data['parents'] = $parents;
        $data['published'] = 1;
        $data['parent_id'] = $obj['category_id'];
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', '标题', 'trim|required|htmlspecialchars|min_length[1]|max_length[40]');
            $this->form_validation->set_rules('keywords', '关键词', 'htmlspecialchars|min_length[0]|max_length[40]');
            $this->form_validation->set_rules('description', '描述信息', 'htmlspecialchars|min_length[0]|max_length[40]');
            $this->form_validation->set_rules('content', '内容', 'htmlspecialchars');
            if ($this->form_validation->run() == true) {
                $items['title'] = $this->input->post('title');
                $items['keywords'] = $this->input->post('keywords');
                $items['description'] = $this->input->post('description');
                $items['category_id'] = $this->input->post('category_id');
                $items['channel_id'] = $channel_id;
                $items['published'] = $this->input->post('published') ? 1 : 0;
                $items['recent'] = $this->input->post('recent') ? 1 : 0;
                $items['hot'] = $this->input->post('hot') ? 1 : 0;
                $items['content'] = $this->input->post('content');
                $items['image'] = $this->input->post('image');
                $items['created'] = time();
                if ($this->archive->insert($items)) {
                    $this->success("数据添加成功！");
                    redirect(site_url('archive/archive/index/' . $channel_id));
                } else {
                    $this->error("保存数据出错！");
                }
            }
        }
        $data['data'] = $obj;
        $data['channel_id'] = $channel_id;
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array(
                "body" => "archive/archive/add"
        ), $data);
    }

    /**
     * 编辑文章
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
            redirect(site_url('archive/archive/index/' . $page_no));
        }
        $obj = $this->archive->read($id);
        // 验证对象是否存在
        if (! $obj->id) {
            $this->error("不存在的信息编号！");
            redirect(site_url('archive/archive/index/' . $page_no));
        }
        
        $where = "published = 1";
        $categories = $this->category->find_all($where);
        $this->load->library('tree');
        $this->tree->set_array($categories);
        $parents = $this->tree->get_tree();
        
        // 设置默认表单信息
        $data['parents'] = $parents;
        $data['published'] = 1;
        $data['page_no'] = $page_no;
        $data['parent_id'] = $obj->category_id;
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', '标题', 'trim|required|htmlspecialchars|min_length[1]|max_length[40]');
            $this->form_validation->set_rules('keywords', '关键词', 'htmlspecialchars|min_length[0]|max_length[40]');
            $this->form_validation->set_rules('description', '描述信息', 'htmlspecialchars|min_length[0]|max_length[40]');
            $this->form_validation->set_rules('content', '内容', 'htmlspecialchars');
            if ($this->form_validation->run() == true) {
                $items['title'] = $this->input->post('title');
                $items['keywords'] = $this->input->post('keywords');
                $items['description'] = $this->input->post('description');
                $items['category_id'] = $this->input->post('category_id');
                $items['published'] = $this->input->post('published') ? 1 : 0;
                $items['recent'] = $this->input->post('recent') ? 1 : 0;
                $items['hot'] = $this->input->post('hot') ? 1 : 0;
                $items['content'] = $this->input->post('content');
                $items['image'] = $this->input->post('image');
                if ($this->archive->save($items, $id)) {
                    $this->success('更新数据成功！');
                    redirect(site_url('archive/archive/index/' . $page_no));
                } else {
                    $this->error("保存数据出错！");
                }
            }
        }
        $data['data'] = $obj;
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array(
                "body" => "archive/archive/edit"
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
            redirect(site_url('archive/archive/index/' . $page_no));
        }
        $obj = $this->archive->read($id);
        if ($obj->id) {
            if ($this->archive->remove($id)) {
                $this->success("删除成功。");
                redirect(site_url('archive/archive/index/' . $page_no));
            } else {
                $this->error("查询错误。");
                redirect(site_url('archive/archive/index/' . $page_no));
            }
        } else {
            $this->error("信息不存在。");
            redirect(site_url('archive/archive/index/' . $page_no));
        }
    }
}