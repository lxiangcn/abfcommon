<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * FileName : model_configs.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_configs extends MY_Model {

    function __construct() {
        parent::__construct();
        // Load the associated table
        $this->load_table('configs');
    }

    /**
     *
     * 获取网站配置数组
     *
     * @return array
     */
    function get_configs() {
        $configs = $this->find_all();
        foreach ($configs as $value) {
            $arr[$value->tag] = $value->value;
        }
        return $arr;
    }

    function get_configs_all() {
        $configs = $this->find_all(null, "*", 'id');
        foreach ($configs as $value) {
            $arr[$value->group][] = $value;
        }
        return $arr;
    }

    function get_config() {
        return $this->db->get('configs')->row();
    }

    function get_tag($tag) {
        return $this->db->where('tag', $tag)->get('configs')->order_by('id')->row();
    }

    function update_config($tag, $value) {
        return $this->db->where('tag', $tag)->update('configs', array("value" => $value));
    }

}

/* End of file model_configs.php */
/* Location: ./core/app/models/model_configs.php */
