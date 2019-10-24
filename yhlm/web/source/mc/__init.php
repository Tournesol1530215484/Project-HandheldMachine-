<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
if ( in_array($action, array('credit1',  'uc', 'fields'))) {
	define('FRAME', 'setting');
	
} elseif($action == 'passport' || $action == 'broadcast' || $action == 'mass' || $action == 'credit' || $action == 'tplnotice') {
	define('FRAME', 'platform');
	
} elseif($action == 'business' ) {
	define('FRAME', 'cancel');
	
} else {
	define('FRAME', 'site');
}

if($action == 'stat') {
	define('ACTIVE_FRAME_URL', url('mc/trade'));
} elseif($action == 'card') {
	if(in_array($do, array('notice', 'credit', 'recommend', 'sign'))) {
		define('ACTIVE_FRAME_URL', url('mc/card/other'));
	}
}
if($action =='fangroup'){
	define('FRAME', 'site');	
}
$frames = buildframes(array(FRAME));
$frames = $frames[FRAME];
