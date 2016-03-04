<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : database.php
 * DateTime : 2015年6月26日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Database extends Admin_Controller {

	/**
	 * 数据优化
	 */
	public function index() {
		$data ['info_list'] = $this->db->list_tables ();
		$data ['act'] = $this->uri->segment ( 3 );
		if ($this->input->post ()) {
			$tables = $this->input->post ();
			$this->load->dbutil ();
			if (isset ( $tables ['ids'] )) {
				for($i = 0; $i <= sizeof ( $tables ['ids'] ); $i ++) {
					if ($this->input->post ( 'optimize' )) {
						if ($this->dbutil->optimize_table ( $tables ['ids'] [$i] )) {
							$this->success ( "表结构优化成功！" );
						}
					}
					if ($this->input->post ( 'repair' )) {
						if ($this->dbutil->repair_table ( $tables ['ids'] [$i] )) {
							$this->success ( '修复表成功！' );
						}
					}
				}
			} else {
				$this->error ( '请选择数据表！' );
			}
		}
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "database/index" ), $data );
	}

	/**
	 * 数据备份
	 */
	public function backup() {
		$data ['info_list'] = $this->db->list_tables ();
		if ($this->input->post ()) {
			$tables = $this->input->post ();
			for($i = 0; $i < sizeof ( $tables ); $i ++) {
				$new_tables [] = $tables [$i];
			}
			$prefs = array ('tables' => $new_tables,'ignore' => array (),'format' => 'txt','filename' => 'mybackup.sql','add_drop' => TRUE,'add_insert' => TRUE,'newline' => "\n" );
			$this->load->dbutil ();
			$backup = $this->dbutil->backup ( $prefs );
			$file = "mysql_" . date ( 'YmdHi', time () ) . "_" . md5 ( time () . rand ( 2, 4 ) ) . '.sql';
			if (write_file ( FCPATH . 'data/backup/' . $file, $backup )) {
				$this->success ( '备份数据成功!' );
				redirect ( 'dashboard/database/restore' );
			}
		}
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "database/backup" ), $data );
	}

	/**
	 * 数据恢复
	 *
	 * @param string $sqlfile
	 */
	public function restore($sqlfile = '') {
		$data ['info_list'] = get_dir_file_info ( FCPATH . 'data/backup', $top_level_only = TRUE );
		if ($this->input->post ()) {
			// 默认始终保留一个备份
			$sqlfiles = array_slice ( $this->input->post (), 0, - 1 );
			// echo var_export($sqlfiles);
			foreach ( $sqlfiles as $k => $v ) {
				unlink ( FCPATH . 'data/backup/' . $v );
			}
			show_message ( '删除sql文件成功!', site_url ( '/database/restore' ), 1 );
		}
		if ($sqlfile) {
			$sql = file_get_contents ( FCPATH . 'data/backup/' . $sqlfile );
			if ($this->run_sql ( $sql )) {
				show_message ( '还原sql文件成功!', site_url ( '/database/restore' ), 1 );
			}
		}
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "database/restore" ), $data );
	}

	private function run_sql($sql) {
		$sqls = $this->sql_split ( $sql );
		$result = 0;
		if (is_array ( $sqls )) {
			foreach ( $sqls as $sql ) {
				if (trim ( $sql ) != '') {
					$this->db->query ( $sql );
					$result += mysql_affected_rows ();
				}
			}
		} else {
			$this->db->query ( $sqls );
			$result += mysql_affected_rows ();
		}
		return $result;
	}

	private function sql_split($sql) {
		$sql = str_replace ( "\r", "\n", $sql );
		$ret = array ();
		$num = 0;
		$queriesarray = explode ( ";\n", trim ( $sql ) );
		unset ( $sql );
		foreach ( $queriesarray as $query ) {
			$ret [$num] = '';
			$queries = explode ( "\n", trim ( $query ) );
			$queries = array_filter ( $queries );
			foreach ( $queries as $query ) {
				$str1 = substr ( $query, 0, 1 );
				if ($str1 != '#' && $str1 != '-') $ret [$num] .= $query;
			}
			$num ++;
		}
		return ($ret);
	}
}

/* End of file database.php */
/* Location: ./application/controllers/database.php */ 