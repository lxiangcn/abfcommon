<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Model_member
 * @link http://orzm.net
 * @author Alex Liu<lxiangcn@gmail.com>
 * @version V3.0.1
 * @modifiedtime 2016-04-29 13:34:44
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

class Model_member extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->load_table('member');
    }

    /**
     * 查询用户数据
     *
     * @param string $id
     */
    function get_user_by_id($id = '') {
        $data = array();
        $this->db->select('member.*,user_groups.name as group_name');
        $this->db->from("member");
        $this->db->join('user_groups', "user_groups.id = member.gid");
        $this->db->where('member.id', $id)->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    /**
     * 检查用户email
     *
     * @param unknown $email
     */
    function get_user_by_email($email) {
        return $this->db->where('email', $email)->get('member')->row();
    }

    /**
     * 编辑用户email检查
     *
     * @param unknown $email
     * @param unknown $id
     * @return unknown
     */
    function get_user_by_id_email($email, $id) {
        $data = $this->db->where(array('email' => $email, 'id <>' => $id))->get('member')->row();
        return $data;
    }}