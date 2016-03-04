<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/**
 * This is a sample class to show how to customize the
 */
class MyClass {

	function run_test_job($params = array()) {
		log_message ( 'info', '######## This is a test job ########' );
		global $CI;
		$CI->load->model ( 'bulk/model_bulk_info', 'bulk_info', true );
		$order_list = $CI->bulk_info->find_all ( array ("order_form_state" => 0 ) );
		if (! empty ( $order_list )) {
			foreach ( $order_list as $v ) {
				if ((time () - $v ['created']) > 172800) {
					$CI->bulk_info->save ( array ("order_form_state" => 2 ), $v ['id'] );
				}
			}
		}
	}
}
