<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * 树型下拉菜单预处理
 *
 * @param array $arr
 * @param int $parent_id
 * @param string $spacing
 * @param bool $recursion
 * @param array $new
 * @return array
 */
// function select_tree_prepare($arr, $parent_id = 0, $spacing = '', $recursion = 1, &$new = array()) {
//     if (!is_array($arr) && isset($arr)) {
//         return array();
//     }

//     foreach ($arr as $v) {
//         if ($v['parent_id'] == $parent_id) {
//             $new[$v['id']] = $spacing . $v['name'];
//             if ($recursion) {
//                 select_tree_prepare($arr, $v['id'], $spacing . '&nbsp;&nbsp;', 1, $new);
//             }
//         }
//     }
//     return $new;
// }

/**
 * 给导航管理模块用的树型下拉菜单预处理
 *
 * @param array $arr
 * @param int $parent_id
 * @param string $spacing
 * @param bool $recursion
 * @param array $new
 * @return array
 */
// function select_tree_prepare_nav($arr, $parent_id = 0, $spacing = '', $recursion = 1, &$new = array(), $pathPattern) {
//     if (!is_array($arr)) {
//         return array();
//     }

//     foreach ($arr as $v) {
//         if ($v['parent_id'] == $parent_id) {
//             $new[sprintf($pathPattern, $v['id'])] = $spacing . $v['name'];
//             if ($recursion) {
//                 select_tree_prepare_nav($arr, $v['id'], $spacing . '&nbsp;&nbsp;', 1, $new, $pathPattern);
//             }
//         }
//     }
//     return $new;
// }
