<?php
	
	
	function workranges($str){
		$arr = explode(',' ,$str);
		foreach($arr as $k => $v)	{
			$data[$v]	= __('sitting_'.$v);			
		}
		return $data;
	}
	