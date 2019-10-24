<?php
/**
 * 导航菜单设置
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

if ($do == 'oauth' || $action == 'credit' || $action == 'passport' || $action == 'uc') {
	define('FRAME', 'bonus');
} else {
	define('FRAME', 'bonus');
}
$frames = buildframes(array(FRAME));
$frames = $frames[FRAME];

