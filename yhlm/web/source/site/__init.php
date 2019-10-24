<?php
/**
 * 菜单管理
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */
 
if(!empty($_GPC['f']) && $_GPC['f'] == 'multi') {
	define('ACTIVE_FRAME_URL', url('site/multi/display'));
}
$sysmodules = system_modules();
if(!empty($_GPC['styleid'])) {
	define('ACTIVE_FRAME_URL', url('site/style/styles'));
}
if($controller == 'site') {
	$m = $_GPC['m'];
	if(in_array($m, $sysmodules)) {
		define('FRAME', 'platform');
		define('CRUMBS_NAV', 2);
		define('ACTIVE_FRAME_URL', url('platform/reply/', array('m' => $m)));
	}
}

if($action != 'entry' && $action != 'nav') {
	define('FRAME', 'site');
}elseif($action == 'entry') {
//    print_r($_GPC['p']);exit;
	switch( $_GPC['p'] )
	{		
		case 'poster' : case 'postera' : 
			//分销菜单
			define('FRAME', 'fenxiao');
			break;
		case 'commission':  
			if( $_GPC['method']=='increase' ){
				define('FRAME', 'statistics');
			}else{
				define('FRAME', 'fenxiao');
			}
			break;
			
		case 'supplier' : case 'verify' :
			//供应链菜单
			define('FRAME', 'supplier');
			break;
			
		case 'suppliermenu' :  
		 	//供应商操作台
			define('FRAME', 'suppliermenu');
			break;	

		case 'agency' :  
		 	//代理商操作台
			define('FRAME', 'agency');
			break;	

		case 'activity' :  
		 	//易活动 		 
			define('FRAME', 'activity');	 	 
			break;

		case 'match' :  
		 	//易活动 		 
			define('FRAME', 'activity');	 	 
			break;


		case 'activityba' :  
		 	//互动吧 		 
			define('FRAME', 'activityba');	 	 
			break;


		case 'poster' : case 'postera' : case 'dis':  
			//经销商查询
			define('FRAME', 'fenxiao');
			break;

        case 'merchant' :
            //商家商查询
            define('FRAME', 'merchant');
            break;

        case 'dealmerch' :
            //易货商家查询
            define('FRAME', 'dealmerch');
            break;

        case 'bartact' :
            //易活动管理
            define('FRAME', 'bartact');
            break;

		case 'postera' : case 'postera' : case 'jszd':  
			//账单流水
			define('FRAME', 'finance');
			break;
			
		case 'bonus' : case 'bonusplus': case 'return' :  case 'descreturn' :  
			//分红菜单
			define('FRAME', 'bonus');
			break;	
							
		case 'perm' : case 'system' :
			if( $_GPC['method']=='copyright' ){
				//店铺菜单
				define('FRAME', 'mall');
			}elseif( $_GPC['method']=='commission' ){
				define('FRAME', 'fenxiao');
			}else{
				//权限管理菜单
				define('FRAME', 'authority');
			}
			break;	
		case 'creditshop':case 'bartact': case 'virtual': case 'designer':  case 'coupon': case 'taobao': case 'tmessage': case 'article': case 'exhelper': case 'diyform':  case 'choose': case 'list' : case 'yunpay': case 'paihang': case 'transfer': case 'level': case 'group': case 'qiniu':
			//店铺菜单
			define('FRAME', 'mall');
			break;
		case 'yunpay':
			//公众号设置
			define('FRAME', 'platform');
			break;
		/*case 'sale' : 
			if( $do == 'statistics'){
				//统计
				define('FRAME', 'statistics');
			}else{
				//营销宝
				define('FRAME', 'mall﻿');
			}
			break;*/
		case 'sale' : case 'sale_analysis': case 'order': case 'goods': case 'goods_rank': case 'goods_trans': case 'member_cost': case 'tmessage':  case 'member_increase':

			if( $do == 'sysset' || $do == 'shop' || $do == 'plugin'){
				//店铺菜单
				define('FRAME', 'mall');
			}else{
				//数据统计
				define('FRAME', 'statistics');
			}
			break;
			
		default: 
			if( $do == 'sysset' || $do == 'shop' || $do == 'order'){
				//店铺菜单
				define('FRAME', 'mall');
			}
			if($do=='finance'){
				//财务管理
				define('FRAME', 'finance');
				
			}
		break;
	}

}
if ($action == 'editor' && $_GPC['type'] == '4') {
	define('ACTIVE_FRAME_URL', url('site/editor/uc'));
}
if (!empty($_GPC['multiid'])) {
	define('ACTIVE_FRAME_URL', url('site/multi/display'));
}
$frames = buildframes(array(FRAME));
$frames = $frames[FRAME];

$isdealmerch=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where uniacid=:uniacid and uid =:uid and username = :username',array(':username'=>$_W['user']['username'],':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));

if ($isdealmerch['dealmerchid']  > 0 && $isdealmerch['merchid'] == 0){           //是易货商家
    $_W['isdealmerch']=2;
}else if ($isdealmerch['merchid'] > 0 && $isdealmerch['dealmerchid']  == 0 || $isdealmerch['merchid'] == 0 && $isdealmerch['dealmerchid'] ==  0){    //本地商家或普通商家 用的同一个后台
    $_W['isdealmerch']=1;
}
