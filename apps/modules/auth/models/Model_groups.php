<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Model_groups
 * @link http://orzm.net
 * @author Alex Liu<lxiangcn@gmail.com>
 * @version V3.0.1
 * @modifiedtime 2016-04-27 16:36:19
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

class Model_groups extends MY_Model {
    function __construct() {
        parent::__construct();
        $this->load_table('groups');
    }

    /**
     * 检查用户组
     *
     * @param unknown $group_name
     */
    function get_by_name($group_name) {
        return $this->db->where('group_name', $group_name)->get('groups')->row();
    }

    /**
     * 编辑用户组 $group_name检查
     *
     * @param string $group_name
     * @param int $id
     * @return unknown
     */
    function get_by_id_name($group_name, $id) {
        $data = $this->db->where(array('group_name' => $group_name, 'id <>' => $id))->get('groups')->row();
        return $data;
    }

}
