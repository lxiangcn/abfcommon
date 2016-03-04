<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : MY_Form_validation.php
 * DateTime : 2015年6月1日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class MY_Form_validation extends CI_Form_validation {
	public $CI;

	public function is_unique($str, $field) {
		if (substr_count ( $field, '.' ) == 3) {
			list ( $table, $field, $id_field, $id_val ) = explode ( '.', $field );
			$query = $this->CI->db->limit ( 1 )->where ( $field, $str )->where ( $id_field . ' != ', $id_val )->get ( $table );
		} else {
			list ( $table, $field ) = explode ( '.', $field );
			$query = $this->CI->db->limit ( 1 )->get_where ( $table, array ($field => $str ) );
		}
		return $query->num_rows () === 0;
	}
}