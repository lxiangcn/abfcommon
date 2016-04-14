<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package database
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-04-14 11:55:13
 * @author Alex Liu<lxiangcn@gmail.com>
 */

$active_group  = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'          => '',
    'hostname'     => 'localhost',
    'username'     => 'abfcommon',
    'password'     => 'abfcommon',
    'database'     => 'abfcommon',
    'dbdriver'     => 'mysqli',
    'dbprefix'     => 'abf_',
    'pconnect'     => FALSE,
    'db_debug'     => TRUE,
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8',
    'dbcollat'     => 'utf8_general_ci',
    'swap_pre'     => '',
    'encrypt'      => FALSE,
    'compress'     => FALSE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => TRUE,
);