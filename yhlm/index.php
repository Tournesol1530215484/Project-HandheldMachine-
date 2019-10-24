<?php

/**

 * 入口

 *

 *

 *

 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)

 * @license    http://www.cocogd.com.cn

 * @link       http://www.cocogd.com.cn

 * @since      File available since Release v1.1

 */
 

 

require './framework/bootstrap.inc.php';



 

$host = $_SERVER['HTTP_HOST'];

if (!empty($host)) {

	 $bindhost = pdo_fetch("SELECT * FROM ".tablename('site_multi')." WHERE bindhost = :bindhost", array(':bindhost' => $host));

	if (!empty($bindhost)) {

	 	header("Location: ". $_W['siteroot'] . 'app/index.php?i=8&c=entry&do=shop&m=sz_yi');

		exit; 

	} 

}






  


if($_W['os'] == 'mobile' && (!empty($_GPC['i']) || !empty($_SERVER['QUERY_STRING']))) {	

	header('Location: ./app/index.php?' . $_SERVER['QUERY_STRING']);

} else {

	header("Location: ". $_W['siteroot'] . 'app/index.php?i=8&c=entry&do=shop&m=sz_yi');

}





?>