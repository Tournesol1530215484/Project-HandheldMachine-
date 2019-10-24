<?php
/*=============================================================================
#     FileName: query.php
#         Desc:  
#       Author: Yunzhong - http://www.yunzshop.com
#        Email: 1084070868@qq.com
#     HomePage: http://www.yunzshop.com
#      Version: 0.0.1
#   LastChange: 2016-02-05 02:25:08
#      History:
=============================================================================*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$kwd = trim($_GPC['keyword']);
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$condition = " and pu.uniacid = :uniacid ";	 		
if ($_GPC['model'] == 1) {
	$condition.=' and pu.dealmerchid = 0 ';
}else{
	$condition.=' and pu.dealmerchid > 0 ';
}
$condition.=' and pu.muserid = 0 ';
if (!empty($kwd)) {	 	
	$condition .= " AND ( pu.username LIKE :keyword or pu.realname LIKE :keyword or pu.mobile LIKE :keyword ) ";
	$params[':keyword'] = "%{$kwd}%";	 	 	 	 	 	 		 	 	 	 
}	 	 	 	 		 	 	 	 		 	 	 	 
$ds = pdo_fetchall('SELECT m.openid,m.avatar,pu.username,pu.realname,pu.mobile,pu.uid FROM ' . tablename('sz_yi_perm_user') . " pu left join ".tablename('sz_yi_member')." m on m.openid = pu.openid WHERE 1 {$condition} order by pu.id desc", $params);	 	 	 		 		 	 	 	 	 	 		 	 		 	 		 	 	  			 	 			 	  
// show_json(0,$ds);	 	 
include $this->template('web/member/selmerch');	
		 	 	 	  	 	 		 	 	 		 	 		 	 		 	  		 	 		