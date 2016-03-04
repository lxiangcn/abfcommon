<?php
$route ['auth/admin'] = 'auth/adminlogin';
$route ['auth/group/(:any)'] = 'group/$1';
$route ['auth/member/(:any)'] = 'member/$1';
$route ['auth/weixinlogin'] = 'weixinlogin/index';
$route ['auth/weixinlogin/(:any)'] = 'weixinlogin/$1';
$route ['auth/(:any)'] = 'auth/$1';