<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : category_helper.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */

/**
 * 取得分类或条目mapping: 传入父类ID,返回该分类的mapping,
 * 当没有父类时(即该分类为顶级分类)，mapping=""（空字符串）
 *
 * @param String /Integet $parentID 父分类id
 * @param String $parentTableName 父类表名
 * @return String mapping字符串
 */
if (! function_exists ( 'genMapping' )) {

	function genMapping($parentID, $parentTableName) {
		$mapping = '';
		$CI = &get_instance ();
		$parentID = intval ( $parentID );
		$obj = $CI->db->where ( array ('id' => $parentID ) )->get ( $parentTableName )->row ();
		$parentMapping = $obj->mapping;
		if ($parentID == '' || $parentID === 0) {
			$mapping = '';
		} else {
			$mapping = $parentMapping . $parentID . ',';
		}
		return $mapping;
	}
}

/**
 * 查看是否含有子分类
 *
 * @param int $id 类别ID
 * @return boolean 当指定类别含有子类时返回true,否则返回false
 */
if (! function_exists ( 'hasSubCat' )) {

	function hasSubCat($tableName, $id) {
		$CI = &get_instance ();
		$count = $CI->db->where ( array ('parent_id' => $id ) )->get ( $tableName )->num_rows ();
		if ($count > 0) {
			$hasSubCat = true;
		} else {
			$hasSubCat = false;
		}
		return $hasSubCat;
	}
}

/**
 * 获得分类级别，顶级分类的级别为1，以此类推。
 *
 * @param $mapping 分类的mapping
 * @return int 分类级别
 */
if (! function_exists ( 'getCatLevel' )) {

	function getCatLevel($mapping) {
		if ($mapping == '') {
			$catLevel = 1;
		} else {
			$catLevel = substr_count ( $mapping, ',' ) + 1;
		}
		return $catLevel;
	}
}

/**
 * 判断分类是否可以显示在修改分类页面中的父分类下拉框中。
 *
 * @param array $cats 该模块所有分类记录
 * @param object $parentCat 待判断的分类对象
 * @param int $id 正在修改的分类的ID
 * @param String $subMapping 正在修改的分类的下一级子分类mapping
 * @param int $maxLevel 该模块最大允许级别数
 * @return boolean 可以显示这返回true,否则返回false
 */
if (! function_exists ( 'isValidParentCat' )) {

	function isValidParentCat($cats, $parentCat, $id, $subMapping, $maxLevel) {
		$isValid = false;
		$thisCatLevelDeep = getLevelDeep ( $id, $cats, $subMapping );
		$parentCatLevel = getCatLevel ( $parentCat->mapping );
		if ($thisCatLevelDeep + $parentCatLevel <= $maxLevel) {
			if ($parentCat->id != $id) {
				$isValid = true;
			}
		} else {
			$isValid = false;
		}
		return $isValid;
	}
}

/**
 * 获得分类深度，只有自己时，分类深度为1，有一个下级分类时，分类深度为2，以此类推。
 * 本函数用controller传入的分类记录，可以节省再次查找数据库记录的开销
 *
 * @param $catId 分类ID
 * @param $cats 特定模块的所有分类记录
 * @param $mapping 该分类的下一级子分类的mapping
 * @return int 分类深度
 */
if (! function_exists ( 'getLevelDeep' )) {

	function getLevelDeep($catId, $cats, $mapping) {
		$totalDeep = 1;
		foreach ( $cats as $cat ) {
			$isSubcat = strpos ( $cat->mapping, $mapping );
			if ($isSubcat === 0) {
				$subCatLevel = getCatLevel ( $cat->mapping );
				if ($subCatLevel > $totalDeep) {
					$totalDeep = $subCatLevel;
				}
			} else {
				$totalDeep = getCatLevel ( $mapping ) - 1;
			}
		}
		$thisCatLevel = getCatLevel ( $mapping ) - 1;
		$thisCatDeep = $totalDeep - $thisCatLevel + 1;
		return $thisCatDeep;
	}
}

/**
 * 获取信息分类名称，无限级
 * ====================================
 * 表对应关系：
 * 'category' => 'archive',
 * 'download_cats' => 'download',
 * 'product_cats' => 'product',
 * 'mall_cats' => 'mall_product',
 * 'trade_cats' => 'trade',
 * 'ad_cats' => 'ad'
 * ====================================
 *
 * @param type $id 信息id
 * @param type $data
 * @param type $tableName 信息表名
 * @return type
 */
if (! function_exists ( 'getMappingData' )) {

	function getMappingData($id, $tableName, $data = array()) {
		$CI = &get_instance ();
		$tables = array ('archive' => 'category' );
		$orm = $CI->db->where ( array ('id' => $id ) )->get ( $tables [$tableName] )->row ();
		if (empty ( $orm )) {
			return $data;
		}
		if ($orm->id) {
			$data [] = $orm;
			return getMappingData ( $orm->parent_id, $tableName, $data );
		} else {
			return $data;
		}
	}
}
/**
 * 获取信息分类的名称，单级
 */
if (! function_exists ( 'getMapping' )) {

	function getMapping($id, $tableName, $data = array()) {
		$CI = &get_instance ();
		$tables = array ('navigations' => 'navigation_cats' );
		$orm = $CI->db->where ( array ('id' => $id ) )->get ( $tables [$tableName] )->row_array ();
		if (empty ( $orm )) {
			return $data;
		}
		return $orm;
	}
}
