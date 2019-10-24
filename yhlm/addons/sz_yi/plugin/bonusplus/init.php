<?php

/*=============================================================================
#         Desc: 专业承接微信分销商城二次开发及相关微信功能模块的开发与定制
#       Author: Man.Dan - http://www.jzwshop.com
#        Email: 82089092@qq.com
#     HomePage: http://www.jzwshop.com
#      Version: 0.0.1
#   LastChange: 2016-02-05 02:08:51
#      History:
=============================================================================*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}
$result = pdo_fetchcolumn('select id from ' . tablename('sz_yi_plugin') . ' where identity=:identity', array(':identity' => 'bonusplus'));
if(empty($result)){
  	$displayorder_max = pdo_fetchcolumn('select max(displayorder) from ' . tablename('sz_yi_plugin'));
  	$displayorder = $displayorder_max + 1;
  	$sql = "INSERT INTO " . tablename('sz_yi_plugin') . " (`displayorder`,`identity`,`name`,`version`,`author`,`status`,`category`) VALUES(". $displayorder .",'bonusplus','静态全返','2.0','官方','1','biz');";
	pdo_query($sql);
}
