<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

if($action == 'stat') {
	define('FRAME', 'setting');
	
}elseif( $action == 'payment'  || $action == 'jsauth' || $action == 'notify'){
	define('FRAME', 'platform');
	
} elseif( $_GPC['m'] == 'meepo_nsign'){
	define('FRAME', 'fenxiao');
	
}  else {
	define('FRAME', 'ext');
	if($_COOKIE['ext_type'] == 1) {
		define('ACTIVE_FRAME_URL', url('profile/module/setting', array('m' => $_GPC['m'])));
	} else {
		define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $_GPC['m'])));
	}
}
$frames = buildframes(array(FRAME));
$frames = $frames[FRAME];

