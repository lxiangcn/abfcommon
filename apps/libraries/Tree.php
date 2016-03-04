<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Show_Tree.php
 * DateTime : 2015年6月5日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Tree {
	
	/**
	 * 从数据库查询出的所有分类信息
	 *
	 * @var array
	 */
	var $arr;
	/**
	 * 如下格式
	 * var $arr = array(
	 * 1 => array('id'=>'1′,'parentid'=>0,'name'=>'一级栏目一'),
	 * 2 => array('id'=>'2′,'parentid'=>0,'name'=>'一级栏目二'),
	 * 3 => array('id'=>'3′,'parentid'=>1,'name'=>'二级栏目一'),
	 * );
	 */
	
	/**
	 * 输出结构
	 *
	 * @var array
	 */
	var $tree = array ();
	/**
	 * 树形递归的深度
	 *
	 * @var int
	 */
	var $deep = 1;
	
	/**
	 * 生成树形的修饰符号
	 *
	 * @var array
	 */
	var $icon = array ('│','├─','└─' );

	/**
	 * 生成指定id的下级树形结构
	 *
	 * @param int $rootid 要获取树形结构的id
	 * @param string $add 递归中使用的前缀
	 * @param bool $parent_end 标识上级分类是否是最后一个
	 * @param bool $array_list 标识返回数据是否为全部
	 */
	public function get_tree($rootid = 0, $add = '', $parent_end = TRUE, $array_list = FALSE) {
		$is_top = 1;
		$child_arr = $this->getChild ( $rootid );
		if (is_array ( $child_arr )) {
			$cnt = count ( $child_arr );
			foreach ( $child_arr as $key => $child ) {
				$cid = $child->id;
				$child_child = $this->getChild ( $cid );
				$space = $this->icon [1];
				if ($is_top == 1 && $this->deep > 1) {
					$space = $this->icon [1];
					if (! $parent_end) {
						$add .= $this->icon [0];
					} else {
						$add .= '──';
					}
					if ($is_top == $cnt) {
						$space = $this->icon [2];
						// $parent_end = true;
					} else {
						$space = $this->icon [1];
						// $parent_end = false;
					}
				}
				$parent_end = true;
				if ($array_list) {
					$this->tree [$cid] = $child;
					$this->tree [$cid]->name = $space . $add . $child->name;
				} else {
					$this->tree [$cid] = $space . $add . $child->name;
				}
				$is_top ++;
				
				$this->deep ++;
				
				if ($this->getChild ( $cid )) $this->get_tree ( $cid, $add, $parent_end, $array_list );
				$this->deep --;
			}
		}
		return $this->tree;
	}

	/**
	 * 生成树形数组
	 *
	 * @param array $arr 数据源
	 * @param string $id_field id字段
	 * @param string $pid_field pid 字段
	 * @param string $children_array_name 子集合名称
	 * @param number $pid_index 默认pid值
	 * @return array $ret
	 */
	function create_tree($arr, $id_field = 'id', $pid_field = 'parent_id', $children_array_name = 'children', $pid_index = 0) {
		$ret = array ();
		foreach ( $arr as $k => $v ) {
			if ($v->$pid_field == $pid_index) {
				$tmp = $arr [$k];
				unset ( $arr [$k] );
				$tmp->$children_array_name = $this->create_tree ( $arr, $id_field, $pid_field, $children_array_name, $v->$id_field );
				$ret [] = $tmp;
			}
		}
		return $ret;
	}

	/**
	 * 获取下级分类数组
	 *
	 * @param int $root
	 */
	private function getChild($root = 0) {
		$a = $child = array ();
		foreach ( $this->arr as $id => $a ) {
			if ($a->parent_id == $root) {
				$child [$a->id] = $a;
			}
		}
		return $child ? $child : false;
	}

	/**
	 * 设置源数组
	 *
	 * @param $arr
	 */
	public function set_array($arr = array()) {
		$this->arr = $arr;
	}
}
