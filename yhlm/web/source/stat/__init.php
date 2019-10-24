<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

if($action == 'credit1' || $action == 'credit2' || $action == 'cash' || $action == 'card' || $action == 'paycenter'  ) {
	define('FRAME', 'statistics');
}else{
	define('FRAME', 'mc');	
}

$frames = buildframes(array(FRAME));
$frames = $frames[FRAME];
