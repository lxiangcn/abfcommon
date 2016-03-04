<?php

/**
 *
 * abfcommon
 *
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @package Link
 * @version 2016-02-04 10:26:22
 * @author Alex Liu <lxiangcn@gmail.com>
 */

defined('BASEPATH') or die('No direct script access.');

class Link extends Admin_Controller {
    private $tblName = 'links';
    // 表名，路径名
    public function __construct() {
        parent::__construct();
        $this->load->model('model_links', 'links', TRUE);
    }

    /**
     * 分页显示链接列表
     *
     * @param type $page_no
     * @param type $parent_id
     */
    public function index($page_no = 1, $parent_id = 0) {
        $page_no            = intval($page_no);
        $where              = "1=1 ";
        $order_str          = "id desc";
        $total_rows         = $this->links->find_count($where);
        $pagination_link    = pagination_link("dashboard/link/index/", intval($total_rows), 4, $page_no);
        $data['items']      = $this->links->find_all($where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr']    = $pagination_link['link'];
        $data["page_no"]    = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];

        $this->output("admin_layout", array("body" => "link/index"), $data);
    }

    /**
     * 添加数据
     *
     * @param type $page_no
     */
    function add($page_no = 1) {
        $page_no           = intval($page_no);
        $data['page_no']   = $page_no;
        $data['published'] = 1;

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', '链接名称', 'trim|required|htmlspecialchars|min_length[1]|max_length[20]|is_unique[links.name]');
            $this->form_validation->set_rules('url', '链接地址', 'trim|valid_url');
            if ($this->form_validation->run() == true) {
                $name               = $this->input->post('name');
                $url                = $this->input->post('url');
                $img                = $this->input->post('img');
                $published          = $this->input->post('published') ? 1 : 0;
                $items['name']      = $name;
                $items['url']       = $url;
                $items['img']       = $img;
                $items['published'] = $published;
                $items['created']   = time();
                if ($this->links->insert($items)) {
                    $this->success("添加数据成功。");
                    redirect(site_url('dashboard/link/index'));
                } else {
                    $this->error("保存数据出错。");
                }
            }
        }
        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array("body" => "link/add"), $data);
    }

    /**
     * 单个编辑
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
        }
        $obj = $this->links->read($id);
        // 验证对象是否存在
        if (!$obj->id) {
            $this->error('不存在的信息编号！');
        }
        $page_no         = intval($page_no);
        $data['page_no'] = $page_no;

        // 设置默认表单信息
        $data['published'] = 1;
        $data['page_no']   = $page_no;

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', '链接名称', "trim|required|htmlspecialchars|min_length[1]|max_length[20]|is_unique[links.name.id.{$id}]");
            $this->form_validation->set_rules('url', '链接地址', 'trim|valid_url');
            if ($this->form_validation->run() == true) {
                $name               = $this->input->post('name');
                $url                = $this->input->post('url');
                $img                = $this->input->post('img');
                $published          = $this->input->post('published') ? 1 : 0;
                $items['name']      = $name;
                $items['url']       = $url;
                $items['img']       = $img;
                $items['published'] = $published;
                if ($this->links->save($items, $id)) {
                    $this->success("编辑数据成功。");
                    redirect(site_url('dashboard/link/index/' . $page_no));
                } else {
                    $this->error("保存数据出错。");
                }
            }
        }
        $data['data']       = $obj;
        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array("body" => "link/edit"), $data);
    }

    /**
     * 单个删除
     *
     * @param unknown_type $id
     */
    public function delete($id, $page_no = 0) {
        $id = (int) $id;
        if (!$id) {
            $this->error("查询错误。");
        }
        if ($this->links->remove($id)) {
            $this->success("删除成功。");
            redirect(site_url('dashboard/link/index/' . $page_no));
        } else {
            $this->error("不存在的数据。");
        }
    }

    public function batch_del() {
        if (request::method() == 'post' && is_array($_POST['ids']) && !empty($_POST['ids'])) {
            $data = array();
            foreach ($_POST['ids'] as $v) {
                if ($v = (int) $v) {
                    $data[] = $v;
                }
            }
            if (!empty($v)) {
                foreach ($data as $v) {
                    ORM::factory($this->tblName)->del($v);
                }
                exit("{result:1}");
            }
        }
        exit("{result:0,msg:\"请求数据为空\"}");
    }

    /**
     * 更改发布状态
     *
     * @param int $id
     * @param int $published
     */
    public function set_publish($id, $published) {

        // 验证编号
        $id = (int) $id;
        if (!$id) {
            exit("{result:0,msg:\"错误的编号\"}");
        }

        // published 必须为数字
        $published = (int) $published;

        // 验证对象是否存在
        $obj = ORM::factory('link', $id);
        if (!$obj->loaded) {
            exit("{result:0,msg:\"对象不错在\"}");
        }

        $obj->published = $published;

        if ($obj->save()) {
            exit("{result:1}");
        } else {
            exit("{result:0,msg:\"没有对象需要更新。\"}");
        }
    }

    public function del_img($id) {
        if (ORM::factory('link')->del_file($id)) {
            exit("{result:1}");
        } else {
            exit("{result:0,msg:\"记录不存在\"}");
        }
    }

    /**
     * 批量为发布状态
     */
    public function batch_publish() {
        if (request::method() == 'post' && is_array($_POST['ids']) && !empty($_POST['ids'])) {
            $data = array();
            foreach ($_POST['ids'] as $v) {
                if ($v = (int) $v) {
                    $data[] = $v;
                }
            }
            if (!empty($v)) {
                $db    = Database::instance();
                $query = $db->query('UPDATE `links` SET published = 1 WHERE id in (' . implode(',', $data) . ')');
                if ($query->count()) {
                    exit("{result:1}");
                } else {
                    exit("{result:0,msg:\"没有对象需要更新\"}");
                }
            }
        }
        exit("{result:0,msg:\"请求数据为空\"}");
    }

    /**
     * 批量为未发布状态
     */
    public function batch_unpublish() {
        if (request::method() == 'post' && is_array($_POST['ids']) && !empty($_POST['ids'])) {
            $data = array();
            foreach ($_POST['ids'] as $v) {
                if ($v = (int) $v) {
                    $data[] = $v;
                }
            }
            if (!empty($v)) {
                $db    = Database::instance();
                $query = $db->query('UPDATE `links` SET published = 0 WHERE id in (' . implode(',', $data) . ')');
                if ($query->count()) {
                    exit("{result:1}");
                } else {
                    exit("{result:0,msg:\"没有对象需要更新\"}");
                }
            }
        }
        exit("{result:0,msg:\"请求数据为空\"}");
    }
}

?>