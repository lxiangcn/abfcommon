<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

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
		parent::__construct ();
		$this->load->model ( 'model_configs', 'configs', TRUE ); // 载入配置模型
	}

	/**
	 * 网站基本配置
	 */
	public function site() {
		$this->form_validation->set_rules ( 'site_name', '站点名称', 'htmlspecialchars' );
		$this->form_validation->set_rules ( 'site_url', '网站地址', 'valid_url' );
		$this->form_validation->set_rules ( 'admin_email', '管理员邮箱', 'valid_email' );
		$this->form_validation->set_rules ( 'keywords', '网站关键词', 'htmlspecialchars' );
		$this->form_validation->set_rules ( 'description', '网站描述', 'htmlspecialchars' );
		$this->form_validation->set_rules ( 'site_icp', '备案编号', 'htmlspecialchars' );
		$this->form_validation->set_rules ( 'site_close_tip', '提示内容', 'htmlspecialchars' );
		$this->form_validation->set_rules ( 'upload_path', '附件路径', 'htmlspecialchars' );
		$this->form_validation->set_rules ( 'allowed_types', '允许类型', 'htmlspecialchars' );
		$this->form_validation->set_rules ( 'upload_max_size', '最大文件', 'numeric' );
		$this->form_validation->set_rules ( 'cdn_url', 'CDN URL', 'valid_url' );
		if ($this->input->post ()) {
			$post = $this->input->post ();
			foreach ( $post as $key => $value ) {
				$this->configs->primaryKey = 'tag';
				$this->configs->save ( array ('value' => $value ), $key );
			}
			// 重写缓存
			// write_cache ( TRUE );
			$this->success ( '站点信息设置成功' );
		}
		
		$data ['site_basic'] = $this->data ["config"];
		
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "system/site" ), $data );
	}

	/**
	 * 更新缓存
	 */
	public function clearcache() {
		// write_cache ( TRUE );
		$template_cache_path = FCPATH . "data" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "template";
		$compile_cache_path = FCPATH . "data" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "compile";
		@$this->delFile ( $template_cache_path );
		@$this->delFile ( $compile_cache_path );
		$this->success ( '更新缓存成功' );
		redirect ( 'dashboard/welcome/index' );
	}

	/**
	 * 删除指定目录下的文件，不删除目录文件夹
	 *
	 * @param type $dirName
	 * @return boolean
	 */
	function delFile($dirName) {
		if (file_exists ( $dirName ) && $handle = opendir ( $dirName )) {
			while ( false !== ($item = readdir ( $handle )) ) {
				if ($item != "." && $item != "..") {
					if (file_exists ( $dirName . '/' . $item ) && is_dir ( $dirName . '/' . $item )) {
						delFile ( $dirName . '/' . $item );
					} else {
						if (unlink ( $dirName . '/' . $item )) {
							return true;
						}
					}
				}
			}
			closedir ( $handle );
		}
	}
}
