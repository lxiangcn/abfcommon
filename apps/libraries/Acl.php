<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : Acl.php
 * DateTime : 2015年5月7日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description : abfcommon
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Acl {

    function __construct () {}

    function __get ($var) {
        return get_instance()->$var;
    }

    /**
     * 获取内容管理菜单
     */
    function get_left_menu () {
        $this->load->model('model_menus', 'menus', TRUE);
        $where = " published = 1 and is_menu = 1 ";
        $nodes = $this->menus->find_all($where, '*', 'sort_order asc');
        $this->load->library("tree");
        $nodes_tree = $this->tree->create_tree($nodes);
        echo $this->navigation($nodes_tree);
    }

    /**
     * 输出后台导航编辑
     */
    function navigation ($arr, $node_name_field = 'name', $children_array_name = 'children', $level_field = '1') {
        $tmpStr = '';
        foreach ($arr as $k => $v) {
            if (count($v->$children_array_name) > 0) {
                $tmpStr .= '<li class="mm-dropdown">';
                $tmpStr .= '<a href="#">';
                if (empty($v->ico)) {
                    $tmpStr .= '<i class="menu-icon fa fa-th"></i><span class="mm-text">';
                } else {
                    $tmpStr .= '<i class="menu-icon fa ' . $v->ico . '"></i><span class="mm-text">';
                }
                $tmpStr .= $v->$node_name_field;
                $tmpStr .= '</span></a>';
            } else {
                $tmpStr .= '<li>';
                $tmpStr .= '<a data-class="' . $v->class . '" href="' . site_url($v->class . '/' . $v->method) . '">';
                $tmpStr .= $v->$node_name_field;
                $tmpStr .= '</a>';
            }
            
            if (count($v->$children_array_name) > 0) {
                $tmpStr .= '<ul>';
                if (259 == $v->id) {
                    $this->load->model('archive/model_channel', 'channel', TRUE);
                    $content_list = $this->channel->find_all();
                    foreach ($content_list as $cv) {
                        $tmpStr .= '<li class="mm-dropdown"><a data-class="archive/archive" href="#"><i class="menu-icon fa fa-archive"></i><span class="mm-text">' . $cv->title . '</span></a>';
                        $tmpStr .= '<ul>';
                        $tmpStr .= '<li><a data-class="archive/category" href="' . site_url("archive/category/index/" . $cv->id) . '">栏目类别</a></li>';
                        $tmpStr .= '<li><a data-class="archive/archive" href="' . site_url("archive/archive/index/" . $cv->id) . '">内容管理</a></li>';
                        $tmpStr .= '</ul></li>';
                    }
                }
                $tmpStr .= $this->navigation($v->$children_array_name, $node_name_field, $children_array_name, $level_field);
                $tmpStr .= '</ul>';
                $level_field ++;
            }
            $tmpStr .= '</li>';
        }
        return $tmpStr;
    }
}