<?php
$cron_schedule ['run_test_job'] = array (
	'schedule' => array (
		'config_path' => '', // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
		'cron_expr' => '*/1 * * * *' // 直接指定cron表达式 在配置文件或数据库中获取表达式为空
	), 
	'run' => array (
		'filepath' => 'modules/cron', // 文件所在的目录 相对于APPPATH
		'filename' => 'Myclass.php', // 文件名
		'class' => 'Myclass', // 类名 如果只是简单函数 可为空
		'function' => 'run_test_job', // 要执行的函数
		'params' => array () // 需要传递的参数
	) 
); 

 


//$cron_schedule['clear_log'] = ...
//$cron_schedule['create_sitemap'] = ...