<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : System.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class System extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_configs', 'configs', TRUE); // 载入配置模型
    }

    /**
     * 网站基本配置
     */
    public function site() {
        $data["page_title"] = "系统设置";
        if ($this->input->post()) {
            $post = $this->input->post();
            foreach ($post as $key => $value) {
                $this->configs->primaryKey = 'tag';
                $this->configs->save(array('value' => $value), $key);
            }
            // 重写缓存
            // write_cache ( TRUE );
            $this->success('站点信息设置成功');
        }
        $data['site_basic'] = $this->configs->get_configs_all()	;
        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->output("admin_layout", array("body" => "system/site"), $data);
    }

    /**
     * 更新缓存
     */
    public function clearcache() {
        // write_cache ( TRUE );
        $template_cache_path = FCPATH . "data" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "template";
        $compile_cache_path  = FCPATH . "data" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "compile";
        @$this->delFile($template_cache_path);
        @$this->delFile($compile_cache_path);
        $this->success('更新缓存成功');
        redirect('dashboard/welcome/index');
    }

    /**
     * 删除指定目录下的文件，不删除目录文件夹
     *
     * @param type $dirName
     * @return boolean
     */
    function delFile($dirName) {
        if (file_exists($dirName) && $handle = opendir($dirName)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (file_exists($dirName . '/' . $item) && is_dir($dirName . '/' . $item)) {
                        delFile($dirName . '/' . $item);
                    } else {
                        if (unlink($dirName . '/' . $item)) {
                            return true;
                        }
                    }
                }
            }
            closedir($handle);
        }
    }
}
