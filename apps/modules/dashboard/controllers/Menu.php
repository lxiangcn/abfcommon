<?php

/**
 *
 * abfcommon
 *
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @package Menu
 * @version 2016-03-19 20:15:23
 * @author Alex Liu <lxiangcn@gmail.com>
 */

defined('BASEPATH') or die('No direct script access allowed');

class Menu extends Admin_Controller {
    private $ico_list = array('fa-adjust', 'fa-anchor', 'fa-archive', 'fa-arrows', 'fa-arrows-h', 'fa-arrows-v', 'fa-asterisk', 'fa-ban', 'fa-bar-chart-o', 'fa-barcode', 'fa-bars', 'fa-beer', 'fa-bell', 'fa-bell-o', 'fa-bolt', 'fa-book', 'fa-bookmark', 'fa-bookmark-o', 'fa-briefcase', 'fa-bug', 'fa-building-o', 'fa-bullhorn', 'fa-bullseye', 'fa-calendar', 'fa-calendar-o', 'fa-camera', 'fa-camera-retro', 'fa-caret-square-o-down', 'fa-caret-square-o-left', 'fa-caret-square-o-right', 'fa-caret-square-o-up', 'fa-certificate', 'fa-check', 'fa-check-circle', 'fa-check-circle-o', 'fa-check-square', 'fa-check-square-o', 'fa-circle', 'fa-circle-o', 'fa-clock-o', 'fa-cloud', 'fa-cloud-download', 'fa-cloud-upload', 'fa-code', 'fa-code-fork', 'fa-coffee', 'fa-cog', 'fa-cogs', 'fa-comment', 'fa-comment-o', 'fa-comments', 'fa-comments-o', 'fa-compass', 'fa-credit-card', 'fa-crop', 'fa-crosshairs', 'fa-cutlery', 'fa-dashboard', 'fa-desktop', 'fa-dot-circle-o', 'fa-download', 'fa-edit', 'fa-ellipsis-h', 'fa-ellipsis-v', 'fa-envelope', 'fa-envelope-o', 'fa-eraser', 'fa-exchange', 'fa-exclamation', 'fa-exclamation-circle', 'fa-exclamation-triangle', 'fa-external-link', 'fa-external-link-square', 'fa-eye', 'fa-eye-slash', 'fa-female', 'fa-fighter-jet', 'fa-film', 'fa-filter', 'fa-fire', 'fa-fire-extinguisher', 'fa-flag', 'fa-flag-checkered', 'fa-flag-o', 'fa-flash', 'fa-flask', 'fa-folder', 'fa-folder-o', 'fa-folder-open', 'fa-folder-open-o', 'fa-frown-o', 'fa-gamepad', 'fa-gavel', 'fa-gear', 'fa-gears', 'fa-gift', 'fa-glass', 'fa-globe', 'fa-group', 'fa-hdd-o', 'fa-headphones', 'fa-heart', 'fa-heart-o', 'fa-home', 'fa-inbox', 'fa-info', 'fa-info-circle', 'fa-key', 'fa-keyboard-o', 'fa-laptop', 'fa-leaf', 'fa-legal', 'fa-lemon-o', 'fa-level-down', 'fa-level-up', 'fa-lightbulb-o', 'fa-location-arrow', 'fa-lock', 'fa-magic', 'fa-magnet', 'fa-mail-forward', 'fa-mail-reply', 'fa-mail-reply-all', 'fa-male', 'fa-map-marker', 'fa-meh-o', 'fa-microphone', 'fa-microphone-slash', 'fa-minus', 'fa-minus-circle', 'fa-minus-square', 'fa-minus-square-o', 'fa-mobile', 'fa-mobile-phone', 'fa-money', 'fa-moon-o', 'fa-music', 'fa-pencil', 'fa-pencil-square', 'fa-pencil-square-o', 'fa-phone', 'fa-phone-square', 'fa-picture-o', 'fa-plane', 'fa-plus', 'fa-plus-circle', 'fa-plus-square', 'fa-plus-square-o', 'fa-power-off', 'fa-print', 'fa-puzzle-piece', 'fa-qrcode', 'fa-question', 'fa-question-circle', 'fa-quote-left', 'fa-quote-right', 'fa-random', 'fa-refresh', 'fa-reply', 'fa-reply-all', 'fa-retweet', 'fa-road', 'fa-rocket', 'fa-rss', 'fa-rss-square', 'fa-search', 'fa-search-minus', 'fa-search-plus', 'fa-share', 'fa-share-square', 'fa-share-square-o', 'fa-shield', 'fa-shopping-cart', 'fa-sign-in', 'fa-sign-out', 'fa-signal', 'fa-sitemap', 'fa-smile-o', 'fa-sort', 'fa-sort-alpha-asc', 'fa-sort-alpha-desc', 'fa-sort-amount-asc', 'fa-sort-amount-desc', 'fa-sort-asc', 'fa-sort-desc', 'fa-sort-down', 'fa-sort-numeric-asc', 'fa-sort-numeric-desc', 'fa-sort-up', 'fa-spinner', 'fa-square', 'fa-square-o', 'fa-star', 'fa-star-half', 'fa-star-half-empty', 'fa-star-half-full', 'fa-star-half-o', 'fa-star-o', 'fa-subscript', 'fa-suitcase', 'fa-sun-o', 'fa-superscript', 'fa-tablet', 'fa-tachometer', 'fa-tag', 'fa-tags', 'fa-tasks', 'fa-terminal', 'fa-thumb-tack', 'fa-thumbs-down', 'fa-thumbs-o-down', 'fa-thumbs-o-up', 'fa-thumbs-up', 'fa-ticket', 'fa-times', 'fa-times-circle', 'fa-times-circle-o', 'fa-tint', 'fa-toggle-down', 'fa-toggle-left', 'fa-toggle-right', 'fa-toggle-up', 'fa-trash-o', 'fa-trophy', 'fa-truck', 'fa-umbrella', 'fa-unlock', 'fa-unlock-alt', 'fa-unsorted', 'fa-upload', 'fa-user', 'fa-users', 'fa-video-camera', 'fa-volume-down', 'fa-volume-off', 'fa-volume-up', 'fa-warning', 'fa-wheelchair', 'fa-wrench');

    function __construct() {
        parent::__construct();
        $this->load->model('model_menus', 'menus', TRUE);
    }

    /**
     * 显示菜单
     */
    public function index() {
        /**
         * 获取菜单数据
         */
        $where = " published = 1 ";
        $nodes = $this->menus->find_all($where, '*', 'sort_order asc');
        $this->load->library("tree");
        $this->tree->set_array($nodes);
        $nodes_tree_arr    = $this->tree->get_tree(0, '', true, true);
        $data['info_list'] = $nodes_tree_arr;
        $this->output("admin_layout", array("body" => "menu/index"), $data);
    }

    /**
     * 添加菜单
     */
    public function add() {
        /**
         * 获取菜单数据
         */
        $where = "published = 1";
        $nodes = $this->menus->find_all($where);
        $this->load->library("tree");
        $this->tree->set_array($nodes);
        $nodes_tree_arr    = $this->tree->get_tree();
        $no_option         = array('0' => '根节点');
        $data['parents']   = $no_option + $nodes_tree_arr;
        $data['parent_id'] = 0;

        $icolist     = $this->ico_list;
        $data['ico'] = array();
        foreach ($icolist as $k => $v) {
            $data['ico'][$v] = $v;
        }
        $data['ico_name'] = 'fa-th';

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', '菜单名称', 'trim|required|htmlspecialchars|min_length[1]|max_length[9]');
            $this->form_validation->set_rules('class', '模块目录/类名', 'trim|htmlspecialchars');
            $this->form_validation->set_rules('method', '方法名', 'trim|htmlspecialchars');
            if ($this->form_validation->run() == true) {
                $items['name']       = $this->input->post('name');
                $items['class']      = $this->input->post('class');
                $items['method']     = $this->input->post('method');
                $items['ico']        = $this->input->post('ico');
                $items['parent_id']  = $this->input->post('parent_id');
                $items['level']      = $this->input->post('level');
                $items['is_menu']    = $this->input->post('is_menu');
                $items['published']  = $this->input->post('published');
                $items['sort_order'] = $this->input->post('sort_order');
                if ($this->menus->insert($items)) {
                    $this->success("保存数据成功。");
                    redirect(site_url('dashboard/menu/index'));
                } else {
                    $this->error("保存数据出错。");
                }
            }
        }
        $data['is_menu']    = 1;
        $data['published']  = 1;
        $data['sort_order'] = 100;
        $this->output("admin_layout", array("body" => "menu/add"), $data);
    }

    /**
     * 编辑菜单
     *
     * @param number $id
     */
    public function edit($id = 0) {
        // 验证编号
        $id         = (int) $id;
        $data['id'] = $id;
        if (!$id) {
            $this->error('错误的信息编号！');
            redirect(site_url('dashboard/menu/index'));
        }
        $obj = $this->menus->read($id);
        // 验证对象是否存在
        if (!$obj->id) {
            $this->error('不存在的信息编号！');
            redirect(site_url('dashboard/menu/index'));
        }
        /**
         * 获取菜单数据
         */
        $where = "published = 1";
        $nodes = $this->menus->find_all($where);
        $this->load->library("tree");
        $this->tree->set_array($nodes);
        $nodes_tree_arr    = $this->tree->get_tree();
        $no_option         = array('0' => '根节点');
        $data['parents']   = $no_option + $nodes_tree_arr;
        $data['parent_id'] = $obj->parent_id;

        $icolist     = $this->ico_list;
        $data['ico'] = array();
        foreach ($icolist as $k => $v) {
            $data['ico'][$v] = $v;
        }
        $data['ico_name'] = $obj->ico;

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', '菜单名称', 'trim|required|htmlspecialchars|min_length[1]|max_length[9]');
            $this->form_validation->set_rules('class', '模块目录/类名', 'trim|htmlspecialchars');
            $this->form_validation->set_rules('method', '方法名', 'trim|htmlspecialchars');
            if ($this->form_validation->run() == true) {
                $items['name']       = $this->input->post('name');
                $items['class']      = $this->input->post('class');
                $items['method']     = $this->input->post('method');
                $items['ico']        = $this->input->post('ico');
                $items['parent_id']  = $this->input->post('parent_id');
                $items['level']      = $this->input->post('level');
                $items['is_menu']    = $this->input->post('is_menu');
                $items['published']  = $this->input->post('published');
                $items['sort_order'] = $this->input->post('sort_order');
                if ($this->menus->save($items, $id)) {
                    $this->success("保存数据成功。");
                    redirect(site_url('dashboard/menu/index'));
                } else {
                    $this->error("保存类别到数据库出错");
                }
            }
        }
        $data['data'] = $obj;
        $this->output("admin_layout", array("body" => "menu/edit"), $data);
    }

    /**
     * 删除数据
     */
    public function delete($id = 0) {
        // 验证编号
        $id = (int) $id;
        if (!$id) {
            $this->error('错误的信息编号！');
            redirect(site_url('dashboard/menu/index'));
        }
        if ($this->menus->remove($id)) {
            $this->success('删除数据成功！');
            redirect(site_url('dashboard/menu/index'));
        } else {
            $this->error('数据删除错误！');
            redirect(site_url('dashboard/menu/index'));
        }
    }
}

?>
