<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Memberadmin
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-04-11 10:29:01
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Memberadmin extends Admin_Controller {
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
        $pagination_link    = pagination_link("auth/manage/index/", intval($total_rows), 4, $page_no);
        $data['info_list']  = $this->member->find_all($data_where, '*', $order_str, $pagination_link['offset'], $pagination_link['limit']);
        $data['pagestr']    = $pagination_link['link'];
        $data["page_no"]    = $page_no;
        $data['total_rows'] = $pagination_link['total_page'];
        $this->output("admin_layout", array("body" => "member/index"), $data);
    }

}
