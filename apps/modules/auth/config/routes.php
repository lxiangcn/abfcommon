<?php
$route['auth/admin/login']   = 'login/adminlogin';
$route['auth/admin/logout']  = 'login/adminlogout';
$route['auth/role/(:any)']   = 'role/$1';
$route['auth/admin/(:any)']  = 'admin/$1';
$route['auth/group/(:any)']  = 'group/$1';
$route['auth/member/(:any)'] = 'member/$1';