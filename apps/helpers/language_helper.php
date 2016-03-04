<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

// ------------------------------------------------------------------------

/**
 * Fetches a language variable and optionally outputs a form label
 *
 * @access public
 * @param string the language line
 * @param string the id of the form element
 * @return string
 */
if (! function_exists ( '__' )) {

	function __($key, $swap = NULL) {
		$CI = & get_instance ();
		$line = $CI->lang->line ( $key, $swap );
		$line = (! $line) ? '' . $key . '' : $line;
		return $line;
	}
}


/* End of file language_helper.php */
/* Location: ./application/helpers/MY_language_helper.php */